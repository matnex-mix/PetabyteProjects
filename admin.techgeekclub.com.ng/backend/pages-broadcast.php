<?php

	if(isset($_POST["msg"])) {

		$data1 = array(
			"message" => char_escape($_POST["msg"]),
			"user_id" => $_SESSION["user"]
		);

		table_insert("broadcasts", $data1);

	}

	$hashes = query_hash();
	if(isset($hashes[0]) && $hashes[0]=="delete" && isset($hashes[1])) {

		$message = parse_table("broadcasts", "user_id", "WHERE id = ".$hashes[1]);
		if(isset($message[0]) && $message[0]["user_id"] == $_SESSION["user"]) {

			table_delete("broadcasts", "WHERE id = ".$hashes[1]);

		}

	}

	$messages = parse_table("broadcasts", "*");

	foreach ($messages as $key => $value) {
		$messages[$key]["author"] = parse_table("admin_user", "*", "WHERE id = ".$value["user_id"])[0]["username"];
		$messages[$key]["message"] = char_escape($value["message"]);
	}

	$logged_in = $_SESSION["user"];