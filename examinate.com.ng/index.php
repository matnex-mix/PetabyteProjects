<?php include 'functions.php'; ?>

<?php

	if(file_exists("site_settings.json")) $site_sets = file_get_contents("site_settings.json");
	else die("Site_Settings Not Found");

	$site_sets = json_decode($site_sets, true);

	define('SITE_SET', $site_sets);

	db_connect();

	$seeking = $_SERVER["REQUEST_URI"];

	$match = strpos($seeking, "?");
	if($match !== FALSE) $seeking = substr($seeking, 0, $match);

	$seeking = substr_replace($seeking, "", 0, strlen(SITE_SET["Folder"]));
	$seeking = str_replace(".php", "", $seeking);
	if(!$seeking) $seeking = "index";

	if(is_dir("pages/".$seeking)) $seeking .= "/index";
	$seeking = str_replace("//", "/", $seeking);

	$seeking = "pages/".$seeking.".php";
	$seeking = str_replace("/.", ".", $seeking);
	$seeking = str_replace("//", "/", $seeking);

?>

<?php global_include($seeking); ?>

<?php

	if(file_exists($seeking)) include $seeking;
	else if (file_exists("pages/404.php")) include "pages/404.php";
	else echo "<h1>Not Found</h1><p>The Requested Page Was Not Found It May Have been moved or deleted</p>";

	db_close();

?>