<?php

	include "connect.php";

	//$table_name = "users";

	/*$data_list = array(
		'Name' => "Tobiloba Joshua",
		'School' => "Tobi Emmannuel",
		'Class' => "SSS3",
		'Rank' => "BEGINNER++"
	);*/

	//$action = "EDIT";

	//$condition = " WHERE Class = 'SSS3'";

	if(!isset($table_name)){
		die("Table Name Not Set");
	}

	if(!isset($data_list)){
		die("Data List Not Set");
	}

	if(!isset($condition)){
		$condition = "";
	}

	if(!isset($action)){
		die("Action Not Set");
	}

	if($action == "NEW"){
		$sql = "INSERT INTO ".$table_name." ( ";

		foreach ($data_list as $key => $value) {
			$sql .= $key.", ";
		}
		//echo substr_replace(", ", "", $sql, strlen($sql) - 4);
		
		$sql = substr($sql, 0, strlen($sql) -2);

		$sql .= " ) VALUES ( ";

		foreach ($data_list as $key => $value) {
			if(is_string($value)){
				$sql .= "'".$value."', ";
			}else {
				$sql .= $value.", ";
			}
		}
		$sql = substr($sql, 0, strlen($sql) - 2);

		$sql .= " )".$condition;
	}else if($action == "EDIT"){
		$sql = "UPDATE ".$table_name." SET ";

		foreach ($data_list as $key => $value) {
			if(is_string($value) || is_array($value)){
				$sql .= $key." = '".$value."', ";
			}else {
				$sql .= $key." = ".$value.", ";
			}
		}

		$sql = substr($sql, 0, strlen($sql) - 2);

		$sql .= $condition;
	}
	
	//echo $sql;
	$res = $tgc_connect->query($sql);

	//print_r($tgc_connect);

	$status = "Failure";

	if($res === TRUE){
		$status = "Success";
	}else {
		$status = "Failure";
	}

?>