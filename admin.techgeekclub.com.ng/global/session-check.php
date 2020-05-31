<?php

	if(!isset($_SESSION["user"])) header('Location: '.SITE_SET["Url"].'/secure/login');