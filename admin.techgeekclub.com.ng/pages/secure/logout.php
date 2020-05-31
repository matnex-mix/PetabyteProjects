<?php

	session_unset();
	session_destroy();
	header('Location: '.SITE_SET["Url"]."/secure/login");