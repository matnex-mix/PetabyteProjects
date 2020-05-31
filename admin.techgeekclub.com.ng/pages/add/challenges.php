<?php

	$AddChallenges = array("chl_title"=>"", "chl_content"=>"", "chl_obj"=>"", "chl_input"=>"", "chl_output"=>"", "chl_example"=>"", "chl_lang"=>"-1", "message"=>"");
	include(backend(__FILE__));

	template("Header", SITE_SET["Url"]);
	template("AddChallenges", $AddChallenges);