<?php

	include 'connect.php';

	//$table_name = "Blogs";

	//$Likes = 15;

	$action = "EDIT";

	//$column_name = "ID";

	//$column_value = "UHV0ZXJh";

	if(!isset($_GET["table_name"])){
		die("Table Not Set");
	}

	if(!isset($_GET["Likes"]) && !isset($_GET["Shares"])){
		die("Likes or Shares not Set");
	}

	if(!isset($_GET["column_name"])){
		die("Column Name Not Set");
	}

	if(!isset($_GET["column_value"])){
		die("Column Name Not Set");
	}

	$table_name = $_GET["table_name"];
	//$Likes = $_GET["Likes"];
	$column_name = $_GET["column_name"];
	$column_value = $_GET["column_value"];

	$condition  = " WHERE ".$column_name." = '".$column_value."'";

	$data_list = array();
	$What = "";
	
	if(isset($_GET["Likes"])){
		$data_list = array(

			"Likes" => $_GET["Likes"] + 1
		);

		$What = $_GET["Likes"];
	}

	if(isset($_GET["Shares"])){
		$data_list = array(

			"Shares" => $_GET["Shares"] + 1
		);

		$What = $_GET["Shares"];
	}

	include 'data-alter.php';

	if($status == "Success"){

		echo $What + 1;

	}else {

		echo '<div class="notify bg-light notify-danger border-danger border-right-5">A Connection Error Occurred to Server.</div>"';

	}

?>