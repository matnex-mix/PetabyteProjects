<?php include 'src/components/functions.php'; ?>

<?php
  //Pre-defined needs
  s_storage('scripts', array(
    'empty' => 'is_null'
  ));
?>

<?php page_title("LeaderBoard - Techgeekclub"); ?>

<?php s_header(); ?>

<br/>

<?php s_get_component("leaderboard"); ?>

<br/>

<?php s_footer(); ?>