<?php

	$AddPost = array("blog_title"=>"", "blog_content"=>"", "message"=>"");
	include(backend(__FILE__));

	template("Header", SITE_SET["Url"]);
	template("AddPost", $AddPost);