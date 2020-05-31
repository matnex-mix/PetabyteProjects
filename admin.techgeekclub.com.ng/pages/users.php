<?php

	include(backend(__FILE__));

	template("Header", SITE_SET["Url"]);

	if(isset($user_list)) {

		echo parse_template( "Users", array(
			"data" => $user_list,
			"message" => $message,
			"page" => $page
		) );

	} else if (isset($user_info)) {

		echo parse_template( "UserEdit", array(
			"data" => $user_info,
			"message" => $message
		) );

	}