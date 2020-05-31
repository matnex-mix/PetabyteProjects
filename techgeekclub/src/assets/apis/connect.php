<?php

if(!isset($database)){
	$database = "techgeekclub";
}
if(!isset($password)){
	$password = "admin";
}
if(!isset($username)){
	$username = "admin";
}
if(!isset($server)){
	$server = "localhost";
}

$tgc_connect = new mysqli($server, $username, $password, $database);

if($tgc_connect->connect_error){

	die("Connection to ".$database." Failed: ".$tgc_connect->connect_error);
	
}

?>