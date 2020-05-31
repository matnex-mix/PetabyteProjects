<?php

	$hashes = query_hash();
	$message = "";

	if( isset( $hashes[0] ) && $hashes[0] == "edit" && isset( $hashes[1] )  ) {

		$message = parse_table("forum_posts", "content", "WHERE id = ".intval($hashes[1]));

		if( sizeof( $message ) ) {

			die($message[0]["content"]);

		}

	}

	if ( isset( $_POST["edit-id"] ) ) {

		if ( table_update( "forum_posts", array( "content"=>$_POST["edit-text"] ), "WHERE id = ".$_POST["edit-id"] ) ) {

			$message = "<font color=green>Post Editted!</font>";

		}

	}

	if ( isset( $hashes[0] ) && $hashes[0] == "u" && isset( $hashes[1] ) ) {

		$messages = parse_table("forum_posts", "*", "WHERE author = ".intval($hashes[1]));

	} else if ( isset( $hashes[0] ) && $hashes[0] != 0 ) {

		$messages = parse_table("forum_posts", "*", "WHERE ref_id = ".intval($hashes[0]));

	} else {

		$messages = parse_table("forum_posts", "*");

	}

	foreach ($messages as $key => $value) {

		$messages[$key]["username"] = parse_table("users", "*", "WHERE id = ".$value["author"])[0]["username"];

		$messages[$key]["user_image"] = parse_table("user_meta", "value", "WHERE name = 'image' AND ref_table = 'users' AND ref_id = ".$value["author"]);

		$messages[$key]["content"] = char_escape($value["content"]);
		$messages[$key]["date"] = parse_date($value["date"]);

	}