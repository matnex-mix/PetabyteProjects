<?php

	include(backend(__FILE__));

	template("Header", SITE_SET["Url"]);

	echo parse_template("PostsView", array(
		"data" => $messages,
		"message" => $message
	));