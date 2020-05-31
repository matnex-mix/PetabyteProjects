<?php

	if(isset(apache_request_headers()["JAX-Ver"])) {

		die(blog_code($_POST["blog_content"]));

	}
	if(isset($_POST["done"])) {

		if(!isset($_POST["blog_title"]) || !$_POST["blog_title"]) {
			$AddPost["message"] = "<font color=red>Blog Title Cannot Be Empty</font>";
			$AddPost = array_merge($AddPost, $_POST);
		} else {

			$data1 = array(
				"author" => intval($_SESSION["user"]),
				"title" => $_POST["blog_title"],
				"content" => $_POST["blog_content"],
				"date" => Date("Y-m-d H:i:s"),
				"status" => intval($_POST["blog_status"])
			);

			if(table_insert("blog_posts", $data1)) {
				table_insert("siteusage", array(
					"userid" => intval($_SESSION["user"]),
					"name" => "NewPost",
					"value" => "blog_posts;##id##"
				));
				$AddPost["message"] = "<font color=green>Blog Added Successfully. <a href='".SITE_SET["Url"]."/posts/?/new' >View Posts</a></font>";
			} else {
				$AddPost["message"] = "<font color=red>Server Error Occurred</font>";
				$AddPost = array_merge($AddPost, $_POST);
			}

		}

	}