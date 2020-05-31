<?php

	if(isset($_POST["done"])) {

		$data1 = array(
			"author" => intval($_SESSION["user"]),
			"name" => $_POST["lang_name"],
			"description" => $_POST["lang_desc"]
		);

		if(table_insert("languages", $data1)) {
			table_insert("siteusage", array(
				"userid" => intval($_SESSION["user"]),
				"name" => "NewLanguage",
				"value" => "languages;##id##"
			));
			$AddLangs["message"] = "<font color=green>Language Added Successfully</font>";
		} else {
			$AddLangs["message"] = "<font color=red>Server Error Occurred</font>";
			$AddLangs = array_merge($AddLangs, $_POST);
		}

	}