<?php

	$AddLangs = array("lang_name"=>"", "lang_desc"=>"", "message"=>"");
	include(backend(__FILE__));

	template("Header", SITE_SET["Url"]);
	template("AddLangs", $AddLangs);