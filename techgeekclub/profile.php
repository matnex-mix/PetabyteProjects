<?php include 'src/components/functions.php'; ?>

<?php
	s_storage('scripts', array(

		'Empty' => 'is_null'

	));
?>

<?php page_title("Public Profile - Techgeekclub"); ?>

<?php s_header(); ?>

<br/>

<?php s_get_component('profile'); ?>

<br/>

<?php s_footer(); ?>