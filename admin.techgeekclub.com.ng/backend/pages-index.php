<?php

	$user_new = parse_table("siteusage", "*", "WHERE stamp > '2019-04-23 17:32:47' AND name = 'AddedStorm'");
	$brd_new = parse_table("siteusage", "*", "WHERE stamp > '2019-04-23 17:32:47' AND name = 'EditedStorm'");
	$post_new = parse_table("siteusage", "*", "WHERE stamp > '2019-04-23 17:32:47' AND name = 'StormResponse'");
	$chll_new = parse_table("siteusage", "*", "WHERE stamp > '2019-04-23 17:32:47' AND name = 'QueryDownload'");
	$solu_new = parse_table("siteusage", "*", "WHERE stamp > '2019-04-23 17:32:47' AND name = 'Storm'");
	$file_new = parse_table("siteusage", "*", "WHERE stamp > '2019-04-23 17:32:47' AND name = 'LoggedOut'");
	$msgs_new = parse_table("siteusage", "*", "WHERE stamp > '2019-04-23 17:32:47' AND name = 'LoggedIn'");
	$help_new = parse_table("siteusage", "*", "WHERE stamp > '2019-04-23 17:32:47' AND name = 'ViewedResponse'");

	$Dash["UN"] = sizeof($user_new);
	$Dash["BN"] = sizeof($brd_new);
	$Dash["PN"] = sizeof($post_new);
	$Dash["CN"] = sizeof($chll_new);
	$Dash["SN"] = sizeof($solu_new);
	$Dash["FN"] = sizeof($file_new);
	$Dash["MN"] = sizeof($msgs_new);
	$Dash["HN"] = sizeof($help_new);

	$user_new = parse_table("siteusage", "*", "WHERE name = 'AddedStorm'");
	$brd_new = parse_table("siteusage", "*", "WHERE name = 'EditedStorm'");
	$post_new = parse_table("siteusage", "*", "WHERE name = 'StormResponse'");
	$chll_new = parse_table("siteusage", "*", "WHERE name = 'QueryDownload'");
	$solu_new = parse_table("siteusage", "*", "WHERE name = 'Storm'");
	$file_new = parse_table("siteusage", "*", "WHERE name = 'LoggedOut'");
	$msgs_new = parse_table("siteusage", "*", "WHERE name = 'LoggedIn'");
	$help_new = parse_table("siteusage", "*", "WHERE name = 'ViewedResponse'");

	$Dash["TUN"] = sizeof($user_new);
	$Dash["TBN"] = sizeof($brd_new);
	$Dash["TPN"] = sizeof($post_new);
	$Dash["TCN"] = sizeof($chll_new);
	$Dash["TSN"] = sizeof($solu_new);
	$Dash["TFN"] = sizeof($file_new);
	$Dash["TMN"] = sizeof($msgs_new);
	$Dash["THN"] = sizeof($help_new);