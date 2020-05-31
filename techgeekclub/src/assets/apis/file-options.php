   <?php

	//$path = "../../../uploads/18950d621134be918b2e49890841f612";
	//$action = "LIST_FILE";
	//$mode = "ONCE";
	//$count = "ALL";
	//$show = "FILE_EXT";
	//$filter = "png, jpg";
	//$write_to_file = "Just Testing";

	if(!isset($action)){
		die("Action is Not Specified");
	}

	if(!isset($path)){
		die("File Path Not Set");
	}

	if(!isset($mode)){
		$mode = "ONCE";
	}

	if(!isset($count)){
		$count = 1;
	}

	if(!isset($filter)){
		$filter = "";
	}

	if(!isset($show)){
		$show = "";
	}

	if($action != "LIST_FILE" && !isset($write_to_file)){
		die("Nothing to write to File");
	}

	$file_contents = array();

	if($action == "READ_FILE"){

		$file = fopen($path, "r");

		if($mode == "ONCE"){

			$file_contents = fread($file, filesize($path));

		}else if($mode == "LINE"){

			$pointer = 0;

			while(!feof($file)){

				array_push($file_contents, fgets($file));

				$pointer++;

				if($count != "ALL"){
					if($count == $pointer){
						break;
					}
				}

			}

		}

		fclose($file);
	}else if($action == "LIST_FILE"){

		if(is_dir($path)){
			//echo "is dir, ";
			if($dir = opendir($path)){
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
		}

	}else if($action == "FILE_WRITE") {
		//echo "Writing";

		$file = fopen($path, "w"); 

		$do = fwrite($file, $write_to_file);

		//echo $do;

		fclose($file);

	}

	if(isset($output) && $output === "DO_SHOW") {

		if(is_array($file_contents)){

			foreach ($file_contents as $key => $value) {
				echo $key." => ".$value."<br/>";
			}

		}else {

			echo $file_contents;

		}

	}

?>