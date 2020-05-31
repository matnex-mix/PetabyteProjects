<?php
	//var_dump($_GET);

	$condition = "";
	$limit = "";
	$offset = "";
	$transforms = Array();
	$msgsuccess = "The process was completed successfully.";
	$msgfailure = "An Error occurred while processing the request. Please check and try Again.";

	if(!isset($_GET["table"])){
		die("<div class='notify notify-warning border-warning border-left-5'>A sever Error occurred while processing the request.</div>");
	}

	if(!isset($_GET["action"])){
		die("<div class='notify notify-warning border-warning border-left-5'>A sever Error occurred while processing the request.</div>");
	}

	if(!isset($_GET["data"])){
		die("<div class='notify notify-warning border-warning border-left-5'>A sever Error occurred while processing the request.</div>");
	}

	if(isset($_GET["condition"])){
		$condition = $_GET["condition"];
	}

	if(isset($_GET["limit"])){
		$limit = $_GET["limit"];
	}

	if(isset($_GET["offset"])){
		$offset = $_GET["offset"];
	}

	if(isset($_GET["onsuccess"])){
		$msgsuccess = $_GET["onsuccess"];
	}

	if(isset($_GET["onfailure"])){
		$msgfailure = $_GET["onfailure"];
	}

	if(isset($_GET["transforms"])){
		$transforms = json_decode($_GET["transforms"], true);
	}

	$table_name = $_GET["table"];
	$data_list = json_decode($_GET["data"], true);
	$action = $_GET["action"];
	//var_dump($data_list,$transforms);

	foreach ($transforms as $key => $value) {
		if(!isset($data_list[$key])){ die("No Corresponding column for `".$key."` in DATA_LIST"); }

		$data_list[$key] = Transform($data_list[$key], $value);
	}

	//var_dump($condition,$data_list);

	include 'data-alter.php';

	echo $sql;

	if($status === "Success"){
		echo "<div class='notify notify-success border-success border-left-5'>".$msgsuccess."</div>";
	}else {
		echo "<div class='notify notify-danger border-danger border-left-5'>".$msgfailure."</div>";
	}

	function Transform($data, $transform){

		if($transform == "MD5"){
			return md5($data);
		}

	}

?>