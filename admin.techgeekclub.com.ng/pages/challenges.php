<?php

	include( backend(__FILE__) );

	template("Header", SITE_SET["Url"]);
	echo parse_template("Challenges", array(
		"data" => $data,
		"message" => $message,
		"page" => $page
	));