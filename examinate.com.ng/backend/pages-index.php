<?php

	#session_unset();
	$message = "";
	if(isset($_POST["user"]) && isset($_POST["pass"])) {

		$users = parse_table( "users_data", "*", "WHERE status = 0 AND username = '" . $_POST["user"] . "' AND password = '" . md5( $_POST["pass"] ) . "'" );
		
		if(sizeof( $users ) ) {
			$_SESSION["session_user"] = $users[0]["id"];
			die("<font color=green>Logged In Successfully</font><script>LoadPreview();</script>");
		} else {
			die("<font color=red>Incorrect Username or Password</font>");
		}

	}

	if(isset($_POST["previewed"])) {

		$_SESSION["session_user_preview"] = 1;

	}

	if(isset($_SESSION["session_user"]) && !isset($_SESSION["session_user_preview"])) {
		
		$data = parse_table("users_data", "*", "WHERE id = ".$_SESSION["session_user"])[0];

		$data["exam_count"] = parse_table("courses_exam", "*", "WHERE id = ".$data["exam"])[0];
		$data["exam_time"] = $data["exam_count"]["max_time"];
		$data["exam_count"] = $data["exam_count"]["question_count"];

		$_SESSION["exam_time"] = $data["exam_time"];
		$_SESSION["exam_count"] = $data["exam_count"];

		$time_text = "";
		if(intval($data["exam_time"]/3600) > 0) {
			$time_text .= intval($data["exam_time"]/3600)." hours ";
			$data["exam_time"] = $data["exam_time"]%3600;
		}

		if($data["exam_time"]/60 > 0) {
			$time_text .= intval($data["exam_time"]/60)." minutes ";
			$data["exam_time"] = $data["exam_time"]%60;
		}

		if($data["exam_time"] > 0) {
			$time_text .= $data["exam_time"]." seconds ";
		}

		$data["exam_time"] = $time_text;

	} else if(isset($_SESSION["session_user"])) {

		if( isset( $_SESSION["exam_qst"] ) ) {

			$tmp_a = $_SESSION["exam_qst"];

		} else {

			$data_e = parse_table("users_data", "exam", "WHERE id = ".$_SESSION["session_user"])[0]["exam"];
			$tmp_a = parse_table("courses_question", "*", "WHERE courses_id LIKE '%".$data_e."%' ORDER BY RAND() LIMIT 0,".$_SESSION["exam_count"]);

			foreach ($tmp_a as $key => $value) {
				$tmp_a[$key]["options"] = array(
					["a", $value["a"]],
					["b", $value["b"]],
					["c", $value["c"]],
					["d", $value["d"]]
				);
				shuffle($tmp_a[$key]["options"]);

				unset($tmp_a[$key]["a"]);
				unset($tmp_a[$key]["b"]);
				unset($tmp_a[$key]["c"]);
				unset($tmp_a[$key]["d"]);
			}
			$_SESSION["exam_qst"] = $tmp_a;

		}

		if(isset($_POST["done"])) {

			$ans = [];
			$score = 0;
			$attempt = 0;
			$rem_time = $_SESSION["exam_time"]-(time()-$_SESSION["start_time"]);

			foreach ($_SESSION["exam_qst"] as $key => $value) {
				
				if( isset( $_POST[strval( $value["id"] )] ) ) {

					$ans[$value["id"]] = $_POST[strval( $value["id"] )];

					if( $ans[$value["id"]] == $value["answer"] ) {
						$score++;
					}

					$attempt++;

				} else {

					$ans[$value["id"]] = "";

				}

			}

			table_insert("exam_scores", array(
				"user_id" => $_SESSION["session_user"],
				"score" => $score,
				"attempted" => $attempt,
				"time_remaining" => $rem_time
			));

			$count = 1;
			foreach ($ans as $key => $value) {
				
				table_insert("forensic_exam_data", array(
					"user_id" => $_SESSION["session_user"],
					"qst_no" => $count,
					"qst_id" => $key,
					"ans" => $value
				) );
				$count++;

			}

			table_update("users_data", array(
				"status" => 1
			), "WHERE id = ".$_SESSION["session_user"]);

			session_unset();
			template("Header"); template("exam-finished"); die("");

		}

	}