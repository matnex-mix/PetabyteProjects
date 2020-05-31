<?php
	
	session_start();

	include "connect.php";

	if(!isset($_GET["auto_log"]) || !isset($_GET["pass"]) || !isset($_GET["user"])){

		die("There is an error on the server, contact administartor for help.");

	}else if(!$_GET["user"] || !$_GET["pass"]){

		die("<div class='notify bg-light notify-danger border-danger border-left-5'>Please fill in a Genuine Username and Password</div>");

	}

	$Uname = htmlspecialchars($_GET["user"]);
	$Upass = htmlspecialchars($_GET["pass"]);
	$auto_log = htmlspecialchars($_GET["auto_log"]);

	//die($auto_log);

	$table_name = "users";
	$columns = ["ID", "Username", "Password", "Dpics", "Settings", "Progress", "Badges", "Name", "Phone", "Email", "Gender", "Dob"];

	include "retrieve.php";

	$success = 0;

	foreach ($data as $key => $value) {
		//echo $key." => ".print_r($value)."<br/>";
		//echo $Uname;
		if($value["Username"] == $Uname && $value["Password"] == md5($Upass)){

			$_SESSION["details"] = $value;
			$data = $value;
			echo "<div class='notify bg-light notify-success border-success border-left-5'>Logged In. Successfully.<script>setTimeout(function(){location.href = ('dashboard.php');}, 2000);</script></div>";
			$success = 1;
			break;

		}else if($value["Username"] == $Uname){
			//echo implode(", ", $value);
			die("<div class='notify bg-light notify-warning border-warning border-left-5'>This Does not seem to be the right Password.</div>");
			$success = 1;
			break;
		
		}

	}

	if($success == 0) {

		die("<div class='notify bg-light notify-danger border-danger border-left-5'>Username and Password Incorrect, You would be banned after 5 trials.</div>");
		
	}

	if($auto_log != "false"){

		# cookie code....
		setcookie("details", implode(",", $data), time() + (86400 * 30), "/");

	}

?>