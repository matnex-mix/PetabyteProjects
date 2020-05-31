<?php

	$POST = array("login" => "", "pass" => "", "message" => "");
	$message = "";
	$name = "";
	$pass = "";

	if( isset($_POST["login"]) && isset($_POST["pass"]) ) {

		$name = $_POST["login"];
		$pass = $_POST["pass"];

		$user = parse_table("admin_user", "*", "WHERE username = '".$name."'");

		if(sizeof($user) && $user[0]["username"] == $name) {

			if($user[0]["password"] == md5($pass)) {

				$_SESSION["user"] = intval($user[0]["id"]);

				$message = "<font color = 'green'>Logged In</font><br/><script>setTimeout(function(){ location.href = '".SITE_SET["Url"]."'; }, 800);</script>";

			} else {
				$message = "<font color = 'red'>Invalid Password</font><br/>";
			}

		} else {
			$message = "<font color = 'red'>Invalid Username</font><br/>";
		}

	}

	$POST["message"] = $message;
	$POST["login"] = $name;
	$POST["pass"] = $pass;