<?php

	if(isset($_POST["msg"])){

		$_GLOBAL["MSG"] = $_POST["msg"];

	}
	if(isset($_POST["to"])){

		$_GLOBAL["TO"] = $_POST["to"];

	}
	if(isset($_POST["from"])){

		$_GLOBAL["FROM"] = $_POST["from"];

	}
	if(isset($_POST["sbj"])){

		$_GLOBAL["SBJ"] = $_POST["sbj"];

	}

	$sent = mail($_GLOBAL["TO"], $_GLOBAL["SBJ"], $_GLOBAL["MSG"], $headers);

	if($sent){

		echo "Email Sent Successfully";

	}else{

		echo "Email not sent";

	}

?>