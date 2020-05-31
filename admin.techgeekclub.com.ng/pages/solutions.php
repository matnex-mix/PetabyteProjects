<?php

	include( backend(__FILE__) );

	if(!sizeof($data1)) {
		$data1 = array(
			"id" => 0,
			"score" => 0
		);
	}

	template( "Header", SITE_SET["Url"] );
	echo parse_template( "Solutions", array(
		"data" => $data,
		"page" => $page,
		"message" => $message,
		"scoredata" => $data1,
		"scoring" => $scored,
		"sol" => $data1_sol
	) );