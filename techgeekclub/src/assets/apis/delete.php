<?php 

	include "connect.php";

	$table_name = "users";
	$condtiton = " WHERE Class = 'SSS3'";
	$action = "TABLE";

	if(!isset($table_name)){
		die("Table Name Not Set");
	}

	if(!isset($condition)){
		$condition = "";
	}

	if(!isset($action)){
		die("Action Not Set");
	}

	if($action = "TABLE"){

		$sql = "DROP TABLE ".$table_name;

	}else if($action = "TABLE_DATA"){
		$sql = "DELETE FROM ".$table_name.$condition;
	}

	if($tgc_connect->query($sql) === TRUE){
		echo "Success";
	}else {
		echo "Failure";
	}

?>