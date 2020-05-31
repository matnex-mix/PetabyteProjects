<?php

	$AddChallenges["langs"] = parse_table("languages", "id, name");
	$qry = query_hash();
	$cond = "";

	if(isset(apache_request_headers()["JAX-Ver"])) {

		$els = array(
			"content" => blog_code($_POST["chl_content"]),
			"obj" => blog_code($_POST["chl_obj"]),
			"input" => blog_code($_POST["chl_input"]),
			"output" => blog_code($_POST["chl_output"]),
			"example" => blog_code($_POST["chl_example"]),
			"lang" => parse_table("languages", "id, name", "WHERE id = ".intval($_POST["chl_lang"]))[0]["name"],
			"title" => $_POST["chl_title"],
		);
		template("ChlPreview", $els);
		die();

	}

	if ( isset( $qry[0] ) && $qry[0] == "edit" && isset( $qry[1] ) ) {
	
		$data1 = parse_table( "challenges", "*", "WHERE id = ".intval($qry[1]) )[0];
		$cond = "WHERE id = ".intval($qry[1]);

		$data1 = array(
			"chl_content" => blog_code($data1["content"]),
			"chl_obj" => blog_code($data1["objective"]),
			"chl_input" => blog_code($data1["input"]),
			"chl_output" => blog_code($data1["output"]),
			"chl_example" => blog_code($data1["example"]),
			"chl_lang" => intval($data1["lang"]),
			"chl_title" => $data1["title"],
		);

		$AddChallenges = array_merge($AddChallenges, $data1);

	}

	if(isset($_POST["done"])) {

		$data1 = array(
			"content" => blog_code($_POST["chl_content"]),
			"objective" => blog_code($_POST["chl_obj"]),
			"input" => blog_code($_POST["chl_input"]),
			"output" => blog_code($_POST["chl_output"]),
			"example" => blog_code($_POST["chl_example"]),
			"lang" => intval($_POST["chl_lang"]),
			"author" => intval($_SESSION["user"]),
			"title" => $_POST["chl_title"],
			"date" => Date('Y-m-d H:i:s')
		);

		if( ( $cond && table_update( "challenges", $data1, $cond ) ) || table_insert( "challenges", $data1 )) {
			table_insert("siteusage", array(
				"userid" => intval($_SESSION["user"]),
				"name" => "NewChallenge",
				"value" => "challenges;##id##"
			));
			$AddChallenges["message"] = "<font color=green>Challenge Added Successfully. <a href='".SITE_SET["Url"]."/challenges/?/0' >View Challenges</a></font>";
		} else {
			$AddChallenges["message"] = "<font color=red>Server Error Occurred</font>";
			$AddChallenges = array_merge($AddChallenges, $_POST);
		}
		
	}