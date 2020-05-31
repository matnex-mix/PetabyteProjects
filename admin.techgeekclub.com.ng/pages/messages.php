<?php

	include( backend(__FILE__) );

	template( "Header", SITE_SET["Url"] );

	if ( isset($show_edit) ) {

		echo parse_template( "EditMessages", array(
			"data" => $data,
			"message" => $message
		) );

	}

	echo parse_template( "Messages", array(
		"data" => $data,
		"page" => $page,
		"message" => $message
	) );