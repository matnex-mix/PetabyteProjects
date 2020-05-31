<?php include 'src/components/functions.php'; ?>

<?php
	s_storage('scripts', array(

		'Empty' => 'is_null'

	));
?>

<?php
	$GLOBALS["forum"] = "All";
	if(isset($_GET["mode"])){
		$GLOBALS["forum"] = $_GET["$"];
	}
?>

<?php page_title("Forum - Techgeekclub"); ?>

<?php s_header(); ?>

<br/>

<?php if(isset($_GET["$"])&&$_GET["$"] == "ADD") s_get_component('forum-edit'); 
	  else if(isset($_GET["$"])) s_get_component('forum'); 
	  else s_get_component('list-forum'); ?>

<br/>

<?php s_footer(); ?>