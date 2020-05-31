<?php

	$AddUser = array("u_name"=>"", "full_name"=>"", "u_phone"=>"", "u_gender"=>"-1", "u_dob"=>"", "u_email"=>"", "message"=>"");
	include(backend(__FILE__));

	template("Header", SITE_SET["Url"]);
	template("AddUser", $AddUser);