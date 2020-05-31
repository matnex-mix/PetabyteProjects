<?php

	$AddForum = array( 'Title'=>'Ask a Question', 'real_data' => array(
		'name'=>'',
		'description'=>'',
		'id'=>'',
		'downvote' => 0,
		'upvote' => 0,
		'feeds' => 0,
		'ans' => 0,
		'date' => '0000-00-00 00:00:00'
	), 'message'=>'', 'title'=>'', 'content'=>'', 'id'=>'' );
	include(backend(__FILE__));

	template("Header", SITE_SET["Url"]);
	template("AddForum", $AddForum);