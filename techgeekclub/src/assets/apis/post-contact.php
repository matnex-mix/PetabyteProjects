<?php

	function escape_string($param){

		return str_replace('"', '\"', str_replace("'", "\'", $param));

	}

	if(!isset($_GET["fname"]) || !$_GET["fname"]){
	
		die("<div class='notify bg-light notify-danger border-danger border-right-5'>Error: First Name Not Set</div>");
	
	}else if(!isset($_GET["lname"]) || !$_GET["lname"]){
	
		die("<div class='notify bg-light notify-danger border-danger border-right-5'>Error: Last Name Not Set</div>");
	
	}else if(!isset($_GET["phone"]) || !$_GET["phone"]){
	
		die("<div class='notify bg-light notify-danger border-danger border-right-5'>Error: Please input a Phone Number</div>");
	
	}else if(!isset($_GET["email"]) || !$_GET["email"]){
	
		die("<div class='notify bg-light notify-danger border-danger border-right-5'>Error: Please Set Your Email</div>");
	
	}else if(!isset($_GET["msg"]) || !$_GET["msg"]){
	
		die("<div class='notify bg-light notify-danger border-danger border-right-5'>Error: Your Message is empty</div>");
	
	}

	$Fname = escape_string(htmlspecialchars($_GET["fname"]));
	$Lname = escape_string(htmlspecialchars($_GET["lname"]));
	$Phone = escape_string(htmlspecialchars($_GET["phone"]));
	$Email = escape_string(htmlspecialchars($_GET["email"]));
	$Message = escape_string(htmlspecialchars($_GET["msg"]));

	$action = "NEW";

	$table_name = "contactpost";

	$data_list = array(

		"Fname" => $Fname,
		"Lname" => $Lname,
		"Phone" => $Phone,
		"Email" => $Email,
		"Message" => $Message

	);

	include 'data-alter.php';
	
	if($status == "Success"){

		echo '<div class="notify bg-light notify-success border-success border-right-5">Message Was Sent Succefully, Please check your email for Response.</div>';
	
	}else {

		echo '<div class="notify bg-light notify-danger border-danger border-right-5">Error occurred while sending message to the server, Please try again.</div>';

	}

?>