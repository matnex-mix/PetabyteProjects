<?php include 'src/components/functions.php'; ?>

<?php
	s_storage('scripts', array(

		'Empty' => 'is_null'

	));
?>

<?php page_title("Blog - Techgeekclub"); ?>

<?php s_header(); ?>

<br/>

<?php s_get_component('blog'); ?>

<br/>

<?php s_footer(); ?>