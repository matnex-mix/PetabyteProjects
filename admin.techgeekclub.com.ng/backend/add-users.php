<?php

	if(isset($_POST["added"])) {

		if($_POST["u_pass"] == $_POST["u_c_pass"]){

			$test = parse_table("users", "id", "WHERE username = '".$_POST["u_name"]."'");

			if(!sizeof($test)) {

				$test = parse_table("users", "id", "WHERE email = '".$_POST["u_email"]."'");

				if(!sizeof($test)) {

					$data1 = array(
						"username" => $_POST["u_name"],
						"password" => md5($_POST["u_pass"]),
						"name" => $_POST["full_name"],
						"email" => $_POST["u_email"],
						"gender" => intval($_POST["u_gender"]),
						"phone" => $_POST["u_phone"],
						"dob" => $_POST["u_dob"]
					);

					if(table_insert("users", $data1)) {
						table_insert("siteusage", array(
							"userid" => "##user_id##",
							"name" => "NewUser",
							"value" => "users;##user_id##"
						));
						$AddUser["message"] = "<font color='green'>Acount Created Successfully</font>";
					}

				} else {
					$AddUser["message"] = "<font color='red'>Email Exists Already</font>";
				}

			} else {
				$AddUser["message"] = "<font color='red'>Username Exists Already</font>";
			}

		} else {
			$AddUser["message"] = "<font color='red'>Passwords Do Not Match</font>";
		}
		$AddUser = array_merge($AddUser, $_POST);

	}