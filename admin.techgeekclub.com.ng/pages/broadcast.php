<?php

	include(backend(__FILE__));

	template("Header", SITE_SET["Url"]);
	echo parse_template("BroadCastMessages", array(
		"messages" => $messages,
		"logged_in" => $logged_in
	));