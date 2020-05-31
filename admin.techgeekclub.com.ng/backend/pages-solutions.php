<?php

	$hashes = query_hash();
	$scored = 0;
	$data1 = [];
	$data1_sol = 0;

	$page = 0;
	$data = [];
	$message = "";

	if ( isset( $_POST["scored"] ) ) {

		$data2 = array (
			"score" => $_POST["score_amt"]
		);

		if ( $_POST["sol_id"] ) {

			$_qry = parse_table( "solutions", "*", "WHERE id = ".intval($_POST["sol_id"]) )[0];

			$data2["user_id"] = $_qry["author"];
			$data2["q_name"] = $_qry["ref_table"];
			$data2["q_id"] = $_qry["ref_id"];
			$data2["solution_ref"] = $_qry["id"];

		}

		$cond = "WHERE id = ".$_POST["score_id"];

		if( ( $_POST["score_id"] && table_update( "scoreboard", $data2, $cond ) ) || table_insert( "scoreboard", $data2 ) ) {
			$message = "<font color='green'>Score Updated Successfully</font>";
		} else {
			$message = "<font color='red'>An Error Ocurred. Contact Administrators</font>";
		}

	}

	if ( isset($hashes[0]) && $hashes[0] == "score" && isset( $hashes[1] ) ) {

		$data1 = parse_table( "scoreboard", "score, id", "WHERE solution_ref = ".intval( $hashes[1] ) );
		$scored = 1;
		$data1_sol = $hashes[1];

		if ( sizeof( $data1 ) ) {
			$data1 = $data1[0];
		}

	}

	if(isset($_GET["p"])) {
		$page = intval($_GET["p"]);
	}

	if ( isset($hashes[0]) && $hashes[0] == "u" && isset( $hashes[1] ) ) {

		$data = parse_table( "solutions", "*", "WHERE author = ".intval( $hashes[1] )." ORDER BY `stamp` DESC LIMIT ".(30*$page).", 30" );

	} else {

		$data = parse_table( "solutions", "*", " ORDER BY `stamp` DESC LIMIT ".(30*$page).", 30" );

	}

	foreach ($data as $key => $value) {

		$data[$key]["author_n"] = parse_table("users", "*", "WHERE id = ".$value["author"])[0]["username"];

		$data[$key]["srate"] = parse_table("scoreboard", "SUM(score)", "WHERE q_name = 'challenge' AND q_id = ".$value["id"]);

		$data[$key]["srate"] = $data[$key]["srate"][0]["SUM(score)"]/sizeof($data[$key]["srate"]);

		$data[$key]["solved"] = sizeof( parse_table("scoreboard", "id", "WHERE q_name = 'challenge' AND q_id = ".$value["id"]) );

	}