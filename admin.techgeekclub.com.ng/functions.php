<?php

function backend($path, $file='') {

	if($file=='') {

		$path = explode("\\", $path);
		$pathl = sizeof($path);
		$path = $path[$pathl-2]."-".$path[$pathl-1];
		$file = $path;

	}

	return __DIR__.'\backend\\'.$file;

}

function blog_code_create_html($node, $attr, $content) {

	$ctag = ["br", "img", "hr"];
	$attr_map = array(
		"p" => ["align"],
		"img" => ["src", "width", "height"],
		"hr" => ["noshade"],
		"font" => ["color", "size"]
	);
	$node_map = array(
		"color" => "font"
	);

	if (isset($node_map[$node])) $created = "<".$node_map[$node];
	else $created = "<".$node;

	if(sizeof($attr)) {
		if($attr[0] == "") array_shift($attr);

		foreach ($attr as $ind => $value) {
			if (isset($attr_map[$node][$ind])) {
				$created .= " ".$attr_map[$node][$ind]."=\"".$value."\"";
			}
		}

	}

	if($node == "img") $created .= "alt=\"".$content."\">";
	else $created .= ">".$content;

	if(!in_array($node, $ctag)) {
		$created .= "</".$node.">";
	}

	return $created;

}

function blog_code($text) {

	preg_match_all("/\[\[(\w+)((?:=[^\s]+)*)\s+([^\]\])]*)\]\]/", $text, $matches);

	if(sizeof($matches)) {

		foreach ($matches[0] as $index => $blog_code) {
				
			$code_node = $matches[1][$index];
			$code_attr = explode("=", $matches[2][$index]);
			$code_content = $matches[3][$index];

			$text = str_replace($blog_code, blog_code_create_html($code_node, $code_attr, $code_content), $text);

		}

	}

	return $text;

}

function char_escape($string) {

	$arr = array(
		"quote" => "'"
	);
	foreach ($arr as $key => $value) {
		
		$string = str_replace($value, "&".$key.";", $string);

	}

	return $string;

}

function check_admin($post) {

	return $post == "";

}

function db_close() {

	if(isset($GLOBALS["db_connect"])) {

		$GLOBALS["db_connect"]->close();

	}

}

function db_connect(){

	if(!isset(SITE_SET["DB_Host"]) || !SITE_SET["DB_Host"] || !isset(SITE_SET["DB_Name"]) || !SITE_SET["DB_Name"] || !isset(SITE_SET["DB_User"]) || !isset(SITE_SET["DB_Pass"])){

		die("<h3>Missing Database Information.</h3>");

	}

	$GLOBALS["db_connect"] = new mysqli(SITE_SET["DB_Host"], SITE_SET["DB_User"], SITE_SET["DB_Pass"], SITE_SET["DB_Name"]);

	if($GLOBALS["db_connect"]->connect_error){

		die("Connection to ".$database." Failed: ".$GLOBALS["db_connect"]->connect_error);
		
	}

}

function do_eval($template_string) {

	preg_match_all("/\{\([^\}\{]*\)\}/", $template_string, $matches);

	if(!sizeof($matches) || !sizeof($matches[0])) return $template_string;
	$matches = $matches[0];

	foreach ($matches as $value) {
		
		$value = str_replace("{", "", str_replace("}", "", $value));
		$l_value = $value;

		if( strpos( $value, "(=" ) === 0 ) {

			$l_value = str_replace("(=", "", str_replace(")", "", $l_value));
			$l_value = "''; ".$l_value."; return ''";

		}

		eval('$new_val = (function () { $eval = '.$l_value.'; if(is_bool($eval)) { if($eval) { return "True"; } else { return "False"; } } else { return $eval; } })();');

		$value = '/\{'.preg_quote($value, '/').'\}/';
		$template_string = preg_replace($value, $new_val, $template_string, 1);

	}

	preg_match_all("/(?:(?:False|True)(?:{[^{}]*}))+(?:{[^{}]*})?/", $template_string, $matches);
	
	if(!sizeof($matches) || !sizeof($matches[0])) return $template_string;
	$matches = $matches[0];

	foreach ($matches as $valus) {

		$value = $valus;
		
		$value = preg_replace("/(False|True)/", 'if (${1})', $value);
		$value = str_replace("{", '{ $new_val = \'', $value);
		$value = str_replace("}", "'; } else ", $value);
		$value .= "?";

		$value = str_replace(" else ?", "", $value);
		eval($value);

		$valus = '/'.preg_quote($valus, '/').'/';
		$template_string = preg_replace($valus, $new_val, $template_string, 1);

	}

	return do_eval($template_string);

}

function do_upload($names, $paths, $type, $maxsize, $rename=array()) {

	$errors = array();

	foreach ($names as $ind => $name) {

		$tmp_name = $_FILES[$name]['tmp_name'];
		$f_name = $_FILES[$name]['name'];
		$f_size = $_FILES[$name]['size'];
		$errors = [];

		$file_ext = strtolower(substr($f_name, strrpos($f_name, '.')+1));
		$extensions = explode(",", $type[$ind]);

		if(in_array($file_ext, $extensions) === false){
			array_push($errors, "extension not allowed, please choose a ".str_replace(",", " or ", $type[$ind])." file.");
		}

		if($f_size > $maxsize[$ind]){
			array_push($errors, 'File size must be excately '.$maxsize[$ind].'B');
		}

		if(sizeof($errors) == 0){

			if(isset($rename[$ind])) $f_name = $rename[$ind].".".$file_ext;
			move_uploaded_file($tmp_name, $paths[$ind]."/".$f_name);
			array_push($errors, array(true, $paths[$ind]."/".$f_name));

		}

		return $errors;

	}

}

function global_include($page){

	$tar_dir = dirname(__FILE__).'\global';
	$tar_files = parse_folder($tar_dir);
	foreach ($tar_files as $key => $value) {
		
		if(global_includeable($page, $value)) {

			return include($tar_dir.'\\'.$value);

		}

	}

}

function global_includeable($page, $file_name) {

	if(isset(SITE_SET["globals"][$file_name])) {

		$ex = SITE_SET["globals"][$file_name];
		return !in_array($page, $ex);

	} return true;

}

function img($name) {
	return SITE_SET["Url"]."static/img/".$name;
}

function parse_date($date2, $date1="") {

	if(!$date1) $date1 = Date('Y-m-d H:i:s');

	$date_diff = date_diff(date_create($date2), date_create($date1));
	$str = "";

	if($date_diff->y > 0) $str = $date_diff->y." Years";
	else if($date_diff->m > 0) $str = $date_diff->m." Months";
	else if($date_diff->d > 0) $str = $date_diff->d." Days";
	else if($date_diff->h > 0) $str = $date_diff->h." Hours";
	else if($date_diff->i > 0) $str = $date_diff->i." Mins";
	else if($date_diff->s > 0) $str = $date_diff->s." Secs";

	return $str." ago";

}

function parse_folder($folder_name, $filter = "", $show = "") {

	$file_contents = [];

	if(!(file_exists($folder_name) && is_dir($folder_name))) { show_error("Folder Error", [array("errno"=>"x102x00", "error"=>"$folder_name is either not a Folder or was Not Found")]); return; }

	if($dir = opendir($folder_name)){
				//echo "opened, ";
		while(($file = readdir($dir)) !== FALSE){

			if($file == ".." || $file == "."){

				continue;

			}

			//echo "while loop, ";
			if($filter){
				//echo "Filter: ".$filter.", ";
				$filetype = strtolower(pathinfo($file,PATHINFO_EXTENSION));

				if(strpos($filter, $filetype) === FALSE){

					continue;

				}
			}

			if($show == "FILE_NAME"){
			
				array_push($file_contents, substr($file, 0, strripos($file, ".")));
			
			}else if($show == "FILE_EXT"){
			
				array_push($file_contents, substr($file, strripos($file, ".")));
			
			}else {
			
				array_push($file_contents, $file);
			
			}

		}
	}

	return $file_contents;

}

function parse_folder_generation($folder_name, $folder_contents) {

	$folder_contentss = [];

	foreach ($folder_contents as $index => $name) {
		
		if(is_dir($folder_name."/".$name)) {

			$folder_contents_ = parse_folder($folder_name."/".$name);

			$folder_contents_ = parse_folder_generation($folder_name."/".$name, $folder_contents_);

			$folder_contentss[$name] = $folder_contents_;

		} else {

			$folder_contentss[$name] = $folder_name."/".$name;

		}

	}

	return $folder_contentss;

}

function parse_table($table_name, $columns="*", $condition = ""){

	if(!$table_name) { echo "TABLE_NAME Not Set"; return; }

	if(!is_string($condition)) $condition = "";

	if(is_array($columns)) {

		$column = implode(",", $columns);

	} else if(is_string($columns)) {

		$column = $columns;

	}

	$sql = "SELECT $column FROM $table_name $condition";

	$result = $GLOBALS["db_connect"]->query($sql);

	$result_set = [];

	if($result && $result->num_rows > 0) {

		while($row = $result->fetch_assoc()) {

			array_push($result_set, $row);

		}

	}

	if($GLOBALS["db_connect"]->errno) show_error("MySql Errors", $GLOBALS["db_connect"]->error_list);

	return $result_set;

}

function parse_template($template, $data=array(), $eval=0) {

	$data["site"] = SITE_SET;
	$url = "static/templates/".$template.".html";

	if(!file_exists($url)) { show_error("Template Not Found", [array("errno"=>"x101x00", "error"=>"Template File: $url Not Found")]); return; }

	$template_string = file_get_contents($url);

	foreach ($data as $key => $value) {

		if(is_array($value)) $template_string = parse_template_block($template_string, $key, $value);

		else $template_string = str_replace("##$key##", $value, $template_string);
		
	}

	if($eval !== 1) return do_eval($template_string);
	else return $template_string;

}

function parse_template_block($template_string, $block_key, $block_data) {

	$block_start = strpos($template_string, "##start_block:$block_key##");
	$block_end = strpos($template_string, "##end_block:$block_key##");

	if($block_start !== FALSE && $block_end !== FALSE) {

		$ts_before = substr($template_string, 0, $block_start);
		$ts_after = substr($template_string, $block_end+14+strlen($block_key));
		$ts_block = substr($template_string, ($block_start+16+strlen($block_key)), $block_end-($block_start+16+strlen($block_key)));
		$ts_multiply = "";

		foreach ($block_data as $key => $value) {
			
			if(is_array($value)) $ts_multiply .= parse_template_block(str_replace("##$block_key.$##", $key, $ts_block), ".", $value);

			else $ts_multiply .= str_replace("##.##", $value, str_replace("##$block_key.$##", $key, $ts_block));
			
		}

		$template_string = $ts_before.$ts_multiply.$ts_after;

	}

	foreach ($block_data as $key => $value) {
		
		if(is_array($value)) $template_string = parse_template_block($template_string, "$block_key.$key", $value);
		else $template_string = str_replace("##$block_key.$key##", $value, $template_string);

	}

	return $template_string;

}

function query_hash() {

	$text = $_SERVER["REQUEST_URI"];
	if(strpos($text, "?/") === FALSE) return [];

	$hashes = substr($text, strpos($text, "?/")+2);

	$hashes = explode("/", $hashes);
	if(end($hashes) == "") array_pop($hashes);

	return $hashes;

}

function recovery() {

	$r_name = md5($_SERVER["HTTP_HOST"].'/'.$_SERVER["REDIRECT_URL"]);

	if(isset($_POST["signature"])) {
		recovery_process();
	}

	if(isset($_GET[$r_name])) {
		
		print_r('<font color="purple">Recovery Process **'.$r_name.'**</font>');
		die( parse_template_block("

<form method='post'>
	<textarea name='signature' rows='20' cols='70'></textarea>
	<br/>
	<input type='submit' value='START RECOVERY' style='width: 200px; height: 50px'>
</form>

		", "", array()) );

	}

}

function recovery_process() {

	define( 'SITE_SET', json_decode( file_get_contents( "site_settings.json" ), true ) );

	if( SITE_SET["recovery"]["signature"] == $_POST["signature"] || check_admin($_POST["signature"]) ) {

		$rootPath = dirname(__FILE__);
		$file_name = 'recovery/SPHPRECOVERY'.Date('YmdHis').'.zip';
		sql_backup();

		if(!is_dir($rootPath.'/recovery')) {
			mkdir($rootPath.'/recovery');
		}

		$zip = new ZipArchive();
		$zip->open($file_name, ZipArchive::CREATE | ZipArchive::OVERWRITE);

		$files = new RecursiveIteratorIterator(
		    new RecursiveDirectoryIterator($rootPath),
		    RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach ($files as $name => $file)
		{
		    if (!$file->isDir())
		    {
		        $filePath = $file->getRealPath();
		        $relativePath = substr($filePath, strlen($rootPath) + 1);

		        $zip->addFile($filePath, $relativePath);
		    }
		}

		$zip->close();
		unlink( str_replace( "zip", "sql", str_replace( "recovery/", "", $file_name) ) );

		die( parse_template_block( "
<p>Site Recovery is done and ready to be <a href='http://".SITE_SET["Url"].$file_name."'>downloaded</a>.</p>
		", "", array() ) );

	} else {

		echo parse_template_block( "
<br/>
<font color='red'>Invalid Signature</font>
<br/>
		", "", array() );
	}

}

function secure(){

	foreach ($_GET as $key => $value) {
		
		$_GET[$key] = strip_dangerous(htmlspecialchars($value));

	}

	foreach ($_POST as $key => $value) {
		
		$_POST[$key] = strip_dangerous(htmlspecialchars($value));

	}

}

function session_on($name, $url) {

	if(!isset($_SESSION[$name])) {
		header('Location: '.SITE_SET["Url"].$url);
	}

}

function show_error($error_title = "Errors", $error_list=array()){

	if(sizeof($error_list)) echo "
		<div style='width: 90%; margin: 1rem auto;'>
			<h3 align='center'>$error_title</h3>
	";

	foreach ($error_list as $index => $error) {

		echo '
			<div style="border-left: 5px solid tomato; background: pink; padding: .7rem 1rem; margin: .5rem 0;">
				'.$error["errno"].':
				'.$error["error"].'
			</div>
		';

	}

	if(sizeof($error_list)) echo "
		</div>
	";

}

function sources($path) {

	$files = parse_folder(__DIR__.$path);
	$ret = [];
	foreach ($files as $value) {
		
		array_push($ret, [pathinfo(__DIR__.$path.'/'.$value, PATHINFO_EXTENSION), SITE_SET["Url"].$path."/".$value]);

	}

	return $ret;

}

function sql_backup() {

	db_connect();
    $mysqli = $GLOBALS["db_connect"]; 

    $mysqli->query("SET NAMES 'utf8'");

    $queryTables    = $mysqli->query('SHOW TABLES');

    while($row = $queryTables->fetch_row()) { 
        $target_tables[] = $row[0]; 
    }

    foreach($target_tables as $table) {

        $result         =   $mysqli->query('SELECT * FROM '.$table);  
        $fields_amount  =   $result->field_count;  
        $rows_num=$mysqli->affected_rows;     
        $res            =   $mysqli->query('SHOW CREATE TABLE '.$table); 
        $TableMLine     =   $res->fetch_row();
        $content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";

        for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) 
        {
            while($row = $result->fetch_row())  
            { //when started (and every after 100 command cycle):
                if ($st_counter%100 == 0 || $st_counter == 0 )  
                {
                        $content .= "\nINSERT INTO ".$table." VALUES";
                }
                $content .= "\n(";
                for($j=0; $j<$fields_amount; $j++)  
                { 
                    $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
                    if (isset($row[$j]))
                    {
                        $content .= '"'.$row[$j].'"' ; 
                    }
                    else 
                    {   
                        $content .= '""';
                    }     
                    if ($j<($fields_amount-1))
                    {
                            $content.= ',';
                    }      
                }
                $content .=")";
                //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) 
                {   
                    $content .= ";";
                } 
                else 
                {
                    $content .= ",";
                } 
                $st_counter=$st_counter+1;
            }
        } $content .="\n\n\n";
    }

    file_put_contents('SPHPRECOVERY'.Date('YmdHis').'.sql', $content);

}

function sql_execute($sql) {

	$exec = $GLOBALS["db_connect"]->query($sql);

	if($GLOBALS["db_connect"]->errno) show_error("MySql Errors", $GLOBALS["db_connect"]->error_list);
	return $exec;

}

function static_resource($path, $attr="") {

	if(!file_exists("static/$path")) show_error("Warning: Static File Not Found", [array("errno"=>"x101x00", "error"=>"Static File: $path Not Found")]);

	$base = strtolower(pathinfo("static/$path",PATHINFO_EXTENSION));
	
	$html = "";

	if($base == "css") $html = "<link type='stylesheet' href='$path' $attr>";
	if($base == "js") $html = "<script src='$path' $attr></script>";

	return $html;

}

function strip_dangerous($words){

	$unaccepted = [""];

	foreach ($unaccepted as $word) {

		$words = str_replace($word, "", $words);

	}

	return /*mysqli_escape_string(*/$words;//);

}

function table_delete($table_name, $cond) {

	$sql = "DELETE FROM $table_name $cond";
	//echo $sql;

	$result = $GLOBALS["db_connect"]->query($sql);

	if($GLOBALS["db_connect"]->errno) show_error("MySql Errors", $GLOBALS["db_connect"]->error_list);

	return $result;

}

function table_insert($table_name, $data_list){

	if(!$table_name || !is_array($data_list) || !sizeof($data_list) ) return false;

	$columns = "(";
	$data = "(";

	foreach ($data_list as $key => $value) {
		
		$columns .= ",".$key;

		if(is_string($value)) $value = "'".$value."'";

		$data .= ",".$value;

	}

	$columns = substr_replace($columns, "", 1, 1);
	$data = substr_replace($data, "", 1, 1);

	$sql = "INSERT INTO $table_name $columns) VALUES $data)";

	$result = $GLOBALS["db_connect"]->query($sql);

	if($GLOBALS["db_connect"]->errno) show_error("MySql Errors", $GLOBALS["db_connect"]->error_list);

	return $result;

}

function table_update($table_name, $data_list, $condition = ""){

	if(!$table_name || !is_array($data_list) || !sizeof($data_list) ) return false;

	$data = "";

	foreach ($data_list as $key => $value) {
		
		$data .= $key." = ";

		if(is_string($value)) $value = "'".$value."'";

		$data .= $value.",";

	}

	$data = substr_replace($data, "", strlen($data)-1);

	$sql = "UPDATE $table_name SET $data $condition";
	//echo $sql;

	$result = $GLOBALS["db_connect"]->query($sql);

	if($GLOBALS["db_connect"]->errno) show_error("MySql Errors", $GLOBALS["db_connect"]->error_list);

	return $result;

}

function template($tmp_name, $tmp_val=array()) {

	echo parse_template($tmp_name, array($tmp_name => $tmp_val));

}

function url($path) {
	return SITE_SET["Url"].$path;
}

secure();
session_start();
recovery();