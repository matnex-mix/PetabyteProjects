<?php

	include(backend(__FILE__));

	template("Header");

	if(!isset($_SESSION["session_user"])) {
		template("index", array(

		));
	} else if(!isset($_SESSION["session_user_preview"])) {
		echo parse_template("exam-preview", array(
			"data" => $data
		));
	} else {

		if(!isset($_SESSION["start_time"])) {
			$_SESSION["start_time"] = time();
		}

		echo parse_template("exam", array(
			"questions" => $tmp_a,
			"total_qst" => sizeof($tmp_a),
			"time" => $_SESSION["exam_time"]-(time()-$_SESSION["start_time"]),
			"main_time" => $_SESSION["exam_time"]
		));
	}

	template("Footer");