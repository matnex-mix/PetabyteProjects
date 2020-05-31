<?php include 'src/components/functions.php'; ?>

<?php
	s_storage('scripts', array(

		'Empty' => 'is_null'

	));
?>

<?php page_title("Challenges - Techgeekclub"); ?>

<?php s_header(); ?>

<br/>

<?php

	if(!isset($_GET["mode"])){ ?>

		<?php s_get_component('challenges'); ?>

	<?php } else if($_GET["mode"] == "leaderboard"){ ?>

		<?php s_get_component('challenges#leaderboard'); ?>

	<?php } ?>

<?php ?>

<br/>

<?php s_footer(); ?>