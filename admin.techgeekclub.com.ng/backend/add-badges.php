<?php

	if(isset($_POST["done"])) {

		$data1 = array(
			"author" => intval($_SESSION["user"]),
			"name" => $_POST["badge_name"],
			"description" => $_POST["badge_desc"]
		);

		if(table_insert("badges", $data1)) {
			table_insert("siteusage", array(
				"userid" => intval($_SESSION["user"]),
				"name" => "NewBadge",
				"value" => "badges;##id##"
			));
			$AddBadges["message"] = "<font color=green>Badge Added Successfully</font>";
		} else {
			$AddBadges["message"] = "<font color=red>Server Error Occurred</font>";
			$AddBadges = array_merge($AddBadges, $_POST);
		}

	}