<?php

	$Dash = [];
	include(backend(__FILE__));

	template("Header", SITE_SET["Url"]);
	template("AdminDash", $Dash);