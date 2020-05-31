<?php

	$hashes = query_hash();
	$message = "";
	$page = 0;

	if ( isset( $hashes[0] ) && $hashes[0] == "edit" ) {

	} else if( isset($hashes[0]) && $hashes[0] == "delete" ) {

		array_shift($hashes);

		if( strpos( $hashes[0], "-" ) !== FALSE ) {
			$cond = "WHERE id = ";
			$cond .= implode(" OR id = ", explode('-', $hashes[0]));
		} else {
			$cond = "WHERE id = ".$hashes[0];
		}

		if( table_delete("challenges", $cond) ) {
			$message = "<font color=green>User was Trashed successfully</font>";
		} else {
			$message = "<font color=red>An Error Occured</font>";
		}

	}

	if(isset($_GET["p"])) {
		$page = intval($_GET["p"]);
	}

	if ( isset($hashes[0]) && $hashes[0] == "u" && isset( $hashes[1] ) ) {

		$data = parse_table( "challenges", "*", "WHERE author = ".intval( $hashes[1] )." LIMIT ".(30*$page).", 30" );

	} else {

		$data = parse_table( "challenges", "*", "LIMIT ".(30*$page).", 30" );

	}

	foreach ($data as $key => $value) {

		$data[$key]["author_n"] = parse_table("admin_user", "*", "WHERE id = ".$value["author"])[0]["username"];

		$data[$key]["srate"] = parse_table("scoreboard", "SUM(score)", "WHERE q_name = 'challenge' AND q_id = ".$value["id"]);

		$data[$key]["srate"] = $data[$key]["srate"][0]["SUM(score)"]/sizeof($data[$key]["srate"]);

		$data[$key]["solved"] = sizeof( parse_table("scoreboard", "id", "WHERE q_name = 'challenge' AND q_id = ".$value["id"]) );

	}