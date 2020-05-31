<?php
	if(!isset($_SESSION)){
		session_start();
	}

	$GLOBALS['store'] = array();

	function s_base_url(){
		return 'http://localhost/PetabyteProjects/techgeekclub';
	}

	function page_title($param){
		if($param){
			$_SESSION['page_title'] = $param;
		}
		else {
			return $_SESSION['page_title'];
		}
	}

	function s_header(){
		return include 'header.html';
	}

	function s_footer(){
		return include 'footer.html';
	}

	function s_get_component($param){
		return include 'src/components/'.$param.'.html';
	}

	function s_get_asset($param){
		return include 'src/assets'.$param;
	}

	function s_ret_asset($param){
		return 'src/assets/'.$param;
	}

	function s_image($param){
		return 'src/assets/images/'.$param;
	}

	function s_script($param){
		return 'src/assets/tgc/'.$param;
	}

	function s_use_api($param){
		return 'src/assets/apis/'.$param;
	}

	function s_render_scripts($param){
		if( is_array($param) ){
			foreach ($param as $name => $src) {
				echo '<script type="text/javascript" src="'.s_script($src).'" id="'.$name.'"></script>'."\n";
			}
		}
	}

	function s_storage($name, $param){
		if( $param && $name ){
			$GLOBALS['store'][$name] = $param;
		}else if( $name ){
			return $GLOBALS['store'][$name];
		}
	}

	function s_get_from_user_folder($file_path){
		return "users/".$_SESSION["details"]["ID"]."/".$file_path;
	}

	function s_get_profile_image($user_id){
		$table_name = "users";
		$columns = ["Dpics"];
		$condition = " WHERE `ID` = '".$user_id."'";

		include s_use_api('retrieve.php');

		if(sizeof($data)) return $data[0]["Dpics"];

		return "";
	}

	function array_count($param){

		$count = 0;

		if(is_array($param)){

			foreach ($param as $key) {
				$count++;
			}

		}

		return $count;
	}

	function array_split($text, $needle){

		$first_count = 0;
		$tmp_array = array();

		for($x = 0;$x < strlen($text);$x++){

			if($text[$x] == $needle){

				if($x == strlen($text) - 1){
					break;
				}

				if($x == strripos($text, $needle)){
					array_push($tmp_array, substr($text, $first_count));
					break;
				}

				array_push($tmp_array, substr($text, $first_count, $x));
				$first_count = $x + 1;
			
			}else if($first_count == 0 && $x = strlen($text) - 1){

				array_push($tmp_array, $text);
				
			}

		}

		return $tmp_array;

	}

	function starts_with($haystack, $needle){

		if($haystack && is_string($haystack)){

			return ($haystack[0] == $needle);

		} else {
			return false;
		}

	}

	function ends_with($haystack, $needle){

		if(is_array($haystack)){

			return ($haystack[sizeof($haystack) - 1] == $needle);

		}

		if(is_string($haystack)){

			return ($haystack[strlen($haystack) - 1] == $needle);

		}

	}

	function str_lookup($haystack, $needle) {

		if(strpos($haystack, $needle) !== FALSE) return TRUE;
		return FALSE;

	}

	function to_html($text){

		$text = str_replace("``", "!@!@!@!", $text);
		$text = str_replace("<", "&lt;", $text);
		$text = str_replace(">", "&gt;", $text);

		$text = preg_replace("/(http:\/\/[\w._\/-]+)/", '<a href="$1">$1</a>', $text);

		while(strpos($text, "`") !== FALSE) {

			$pos1 = strpos($text, "`");

			$pos2 = strpos($text, "`", $pos1+1);

			$pos3 = strpos($text, "=LANG", $pos1+1);

			$lang = substr($text, $pos1+1, $pos3-$pos1-1);

			if($pos2 !== FALSE) {

				$text = substr_replace($text, '</code>', $pos2, 1);
				$text = substr_replace($text, '<code type="'.$lang.'" class="d-block p-3 my-2 monospace bg-light">', $pos1, 1);
				
				$pos3 = strpos($text, "=LANG", $pos1+1);
				$text = substr_replace($text, "", $pos3-strlen($lang), strlen($lang)+5);
				//$text .= $lang;

			}

			//die($text);

		}

		//$text = preg_replace('/(<code[\w=\d" -]>)([^><]+)(<\/code>)/', '$1'.str_replace("\n", "<br/>", "$2").'$3', $text);
		$text = str_replace("!@!@!@!", "``", $text);

		return $text;

	}

?>