<?php

	if(isset($_POST["do_act"])) {

		$data1 = array(
			'name' => $_POST["title"],
			'description' => $_POST["content"],
			'author' => 0-intval($_SESSION["user"]),
			'date' => Date('Y-m-d H:i:s')
		);

		$badges = "";
		$count = 0;
		$found = false;

		foreach ($_POST as $key => $value) {
			if(strpos($key, "badge") === 0 && $count < 5) {
				$found = true;
				$badges .= "-".str_replace("badge", "", $key);
				$count++;
			} else if($found == true) break;
		}
		$data1["badges"] = $badges;

		$langs = "";
		$count = 0;
		$found = false;

		foreach ($_POST as $key => $value) {
			if(strpos($key, "lang") === 0 && $count < 5) {
				$found = true;
				$langs .= "-".str_replace("lang", "", $key);
				$count++;
			} else if($found == true) break;
		}
		$data1["langs"] = $langs;

		if(table_insert("forums", $data1)) {
			table_insert("siteusage", array(
				"userid" => intval($_SESSION["user"]),
				"name" => "NewForum",
				"value" => "forums;##id##"
			));
			$AddForum["message"] = "<font color=green>Forum Created Successfully. <a href='".SITE_SET["Url"]."/posts/?/new' >View Forums</a></font>";
		} else {
			$AddForum["message"] = "<font color=red>Server Error Occurred</font>";
			$AddForum = array_merge($AddForum, $_POST);
		}
		
	}

	$AddForum["forum_badges"] = parse_table("forum_badges", "*");
	$AddForum["langs"] = parse_table("languages", "*");

	foreach ($AddForum["forum_badges"] as $key => $value) {
		$Forum = $value["color"];
		$AddForum["forum_badges"][$key]["colori"] = base_convert(16777215 - base_convert($Forum, 16, 10), 10, 16);
	}