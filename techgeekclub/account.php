<?php include 'src/components/functions.php'; ?>

<?php if(isset($_GET["Logout"])&& isset($_SESSION["details"])){?>

<?php //echo $_SERVER["HTTP_USER_AGENT"];

	$table_name = "users";

	$action = "EDIT";

	$set = json_decode($_SESSION["details"]["Settings"], true);

	$set["LLDate"] = Date("d/m/Y h:i:s");

	$set["LLAdd"] = "Lagos";

	$set["LLAgent"] = $_SERVER["HTTP_USER_AGENT"];

	$data_list = Array(
		"Settings" => json_encode($set)
	);

	$condition = " WHERE ID = '".$_SESSION["details"]["ID"]."'";

	include s_use_api("data-alter.php");
?>

<?php session_unset(); session_destroy();} ?>

<?php if(isset($_SESSION["details"])){header('Location: dashboard.php');}; ?>

<?php
  //Pre-defined needs
  s_storage('scripts', array(
    'swap-form' => 'swap-form.js'
  ));
?>

<?php page_title("Account - TechGeekClub"); ?>

<?php s_header(); ?>

<br/>

<?php s_get_component("account"); ?>

<br/>

<?php s_footer(); ?>