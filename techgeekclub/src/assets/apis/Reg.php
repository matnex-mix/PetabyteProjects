<?php

	session_start();

	if(!isset($_GET["full_name"]) || !isset($_GET["u_pass"]) || !isset($_GET["u_name"]) || !isset($_GET["u_email"]) || !isset($_GET["u_phone"]) || !isset($_GET["u_gender"]) || !isset($_GET["u_dob"])){

		die("<div class='notify bg-light notify-warning border-warning border-left-5'>Some Fields are Blank Please Check and Retry</div>");

	}

	$Uname = htmlspecialchars($_GET["u_name"]);
	$Upass = htmlspecialchars($_GET["u_pass"]);
	$Ufname = htmlspecialchars($_GET["full_name"]);
	$Email = htmlspecialchars($_GET["u_email"]);
	$Phone = htmlspecialchars($_GET["u_phone"]);
	$Ugender = htmlspecialchars($_GET["u_gender"]);
	$Udob = htmlspecialchars($_GET["u_dob"]);
	//$Utandc = htmlspecialchars($_GET["u_tandc"]);

	include 'connect.php';

	$table_name = "users";
	$columns = ["Username", "Email"];

	include "retrieve.php";

	$success = 0;

	foreach ($data as $key => $value) {
		//echo $key." => ".print_r($value)."<br/>";
		//echo $Uname;
		if($value["Username"] == $Uname){

			die("<div class='notify bg-light notify-danger border-danger border-left-5'>Username Exists already</div>");
			
		}else if($Email && $value["Email"] == $Email){

			die("<div class='notify bg-light notify-danger border-danger border-left-5'>Email is already registered</div>");
			
		}

	}

	$table_name = "users";

	$data_list = array(

		"ID" => md5($Uname),
		"Username" => $Uname,
		"Password" => md5($Upass),
		"Name" => $Ufname,
		"Email" => $Email,
		"Phone" => $Phone,
		"Gender" => $Ugender,
		"Dob" => $Udob,
		"Dpics" => "http://127.0.0.1/mjm/images/logotop.jpg"

	);

	$action = "NEW";

	include 'data-alter.php';

	if($status == "Success"){

		echo "<div class='notify bg-light notify-success border-success border-left-5'>Registeration Was Successfull, you can now <a href=''>Login</a></div>";

	}else {

		echo "<div class='notify bg-light notify-danger border-danger border-left-5'>An error occurred during the registeration, Please Try again</div>";

	}