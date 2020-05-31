<?php

	include(backend(__FILE__));

	template("Header", SITE_SET["Url"]);
	template("login", $POST);
	template("Footer", SITE_SET["Url"]);