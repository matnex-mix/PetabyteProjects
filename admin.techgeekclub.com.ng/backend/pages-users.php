<?php

	$message = "";
	$qry = query_hash();
	$page = 0;

	if( isset($qry[0]) && $qry[0] == "delete" ) {

		array_shift($qry);

		if( strpos( $qry[0], "-" ) !== FALSE ) {
			$cond = "WHERE ids = ";
			$cond .= implode(" OR id = ", explode('-', $qry[0]));
		} else {
			$cond = "WHERE ids = ".$qry[0];
		}

		if( table_delete("users", $cond) ) {
			$message = "<font color=green>User was Trashed successfully</font>";
		}

	} else if( isset($qry[0]) && $qry[0] == "export" ) {

		array_shift($qry);

		if( strpos( $qry[0], "-" ) !== FALSE ) {
			$cond = "WHERE id = ";
			$cond .= implode(" OR id = ", explode('-', $qry[0]));
		} else {
			$cond = "WHERE id = ".$qry[0];
		}

		$json = parse_table( "users", "*", $cond );
		header('Content-Type: application/json');
		header('Content-Filename: TECHGEEKCLUB_EXPORT_.json');
		die(json_encode($json));

	}

	if(!sizeof($qry)) {

		if(isset($_GET["p"])) {
			$page = intval($_GET["p"]);
		}

		$user_list = parse_table("users", "*", "LIMIT ".(30*$page).", 30");

	} else if( intval($qry[0]) != 0 ) {

		if(isset($_POST["id"])) {

			$cond = "WHERE id = ".$_POST["id"];
			unset($_POST["id"]);
			$data1 = $_POST;

			if( table_update( "users", $data1, $cond ) ) {
				$message = "<font color='green'>User Updated Successfully</font>";
			} else {
				$message = "<font color='red'>An Error Ocurred. Contact Administrators</font>";
			}

		}

		$user_info = parse_table("users", "*", "WHERE id = ".$qry[0]);

		if(!sizeof($user_info)) die("User Not Found");
		$user_info = $user_info[0];

		$user_info["language"] = parse_table("user_meta", "value", "WHERE name = 'language' AND ref_id = ".$user_info["id"]);
		foreach ($user_info["language"] as $key => $value) {
			
			$name = parse_table( "languages", "name", "WHERE id = ".$value["value"] )[0]["name"];
			$user_info["language"][$key] = $name;

		}

		$user_info["badge"] = parse_table("user_meta", "value", "WHERE name = 'badge' AND ref_id = ".$user_info["id"]);
		foreach ($user_info["badge"] as $key => $value) {
			
			$name = parse_table( "badges", "name", "WHERE id = ".$value["value"] )[0]["name"];
			$user_info["badge"][$key] = $name;

		}

		$user_info["usage"] = parse_table("siteusage", "*", "WHERE userid = ".$user_info["id"]." ORDER BY stamp DESC LIMIT 0,25");
		$user_info["usage1"] = parse_table("siteusage", "*", "WHERE userid = ".$user_info["id"]." ORDER BY stamp DESC LIMIT 25,25");

		$user_info["challenge_count"] = sizeof(parse_table("scoreboard", "id", "WHERE q_name = 'challenge' AND user_id = ".$user_info["id"]));
		$user_info["question_count"] = sizeof(parse_table("siteusage", "userid", "WHERE name = 'NewForum' AND userid = ".$user_info["id"]));
		$user_info["answer_count"] = sizeof(parse_table("siteusage", "userid", "WHERE name = 'NewForumPost' AND userid = ".$user_info["id"]));

	}
