<?php

	$hashes = query_hash();

	$page = 0;
	$data = [];
	$message = "";

	if ( isset( $hashes[0] ) && $hashes[0] == "edit" ) {

		$show_edit = true;
		$data = parse_table( "messages", "*", "WHERE id = ".intval( $hashes[1] ) );

		if( sizeof($data) ) {
			$data = $data[0];
		} else {
			die( "<h2>Not Found!!!</h2>" );
		}

	} else if( isset($hashes[0]) && $hashes[0] == "delete" ) {

		array_shift($hashes);

		if( strpos( $hashes[0], "-" ) !== FALSE ) {
			$cond = "WHERE id = ";
			$cond .= implode(" OR id = ", explode('-', $hashes[0]));
		} else {
			$cond = "WHERE id = ".$hashes[0];
		}

		if( table_delete("messages", $cond) ) {
			$message = "<font color=green>Message was Trashed successfully</font>";
		} else {
			$message = "<font color=red>An Error Occured</font>";
		}

	}

	if(isset($_GET["p"])) {
		$page = intval($_GET["p"]);
	}

	if ( isset($hashes[0]) && $hashes[0] == "u" && isset( $hashes[1] ) ) {

		$data = parse_table( "messages", "*", "WHERE sender = ".intval( $hashes[1] )." ORDER BY `stamp` DESC LIMIT ".(30*$page).", 30" );

	} else if ( isset($hashes[0]) && $hashes[0] == "-u" && isset( $hashes[1] ) ) {

		$data = parse_table( "messages", "*", "WHERE recepient = ".intval( $hashes[1] )." ORDER BY `stamp` DESC LIMIT ".(30*$page).", 30" );

	} else {

		$data = parse_table( "messages", "*", " ORDER BY `stamp` DESC LIMIT ".(30*$page).", 30" );

	}

	foreach ($data as $key => $value) {

		$data[$key]["sender_n"] = parse_table("users", "*", "WHERE id = ".$value["sender"])[0]["username"];
		$data[$key]["recepient_n"] = parse_table("users", "*", "WHERE id = ".$value["recepient"])[0]["username"];

	}