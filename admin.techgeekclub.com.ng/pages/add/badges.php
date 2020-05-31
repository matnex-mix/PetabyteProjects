<?php

	$AddBadges = array("badge_name"=>"", "badge_desc"=>"", "message"=>"");
	include(backend(__FILE__));

	template("Header", SITE_SET["Url"]);
	template("AddBadges", $AddBadges);