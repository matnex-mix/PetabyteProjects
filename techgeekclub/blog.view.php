<?php include 'src/components/functions.php'; ?>

<?php
	s_storage('scripts', array(

		'Empty' => 'is_null'

	));
?>

<?php

	if(!isset($_GET["$"])){

		header('Location: '.s_base_url().'/blog.php');

	}

?>

<?php page_title(" - Techgeekclub"); ?>

<?php s_header(); ?>

<br/>

<?php s_get_component('blog.view'); ?>

<br/>

<?php s_footer(); ?>