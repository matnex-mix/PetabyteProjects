<?php

	$mark = "";
	$file = "../txt/blog_Account.txt";

	if(!isset($_GET["ID"])  && $_GET["ID"] != ""){

		die("Invalid ID was Referenced");

	}else if(!isset($_GET["Content"]) && $_GET["Content"] != ""){

		die("No Content to Add");

	}
	if(isset($_GET["File"]) && $_GET["File"] != ""){

		$file = $_GET["File"];

	}
	if(isset($_GET["Marker"])){

		$mark = $_GET["Marker"];

	}

	$stream = file_get_contents($file);

	$ID = $_GET["ID"];
	$Content = $_GET["Content"];

	$trContent = json_decode($Content, true);

	if($trContent["User_Name"] == ""){

		$trContent["User_Name"] = "Unknown";

	}

	if($trContent["Pics"] == ""){

		$trContent["Pics"] = "http://127.0.0.1:8081/mjm/images/logotop.jpg";

	}

	if(!$mark){

		$my_new_Obj = $trContent;

		$my_new_Obj["Date"] = Date('d-m-Y h:i:s');

		$json_Obj = json_decode($stream, true);

		$json_Obj_Work = $json_Obj[$ID];

		array_push($json_Obj_Work, $my_new_Obj);

		$json_Obj[$ID] = $json_Obj_Work;

		$data = json_encode($json_Obj);

		file_put_contents($file, $data);

	}else if($mark) {

		$my_new_Obj = $trContent;

		$my_new_Obj["Date"] = Date('d-m-Y h:i:s');

		$json_Obj = json_decode($stream, true);

		$json_Obj_Work = $json_Obj[$ID];

		foreach ($json_Obj_Work as $key => $value) {
			//print_r($value);

			if($value["Date"] == $mark){
				echo "yes";

				$json_Obj_Work[$key] = $my_new_Obj;

			}
		}

		$json_Obj[$ID] = $json_Obj_Work;

		$data = json_encode($json_Obj);

		file_put_contents($file, $data);

	}

?>