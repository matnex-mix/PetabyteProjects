<?php include 'src/components/functions.php'; ?>

<?php
  //Pre-defined needs
  s_storage('scripts', array(
    'empty' => 'is_null'
  ));
?>

<?php page_title("User Dashboard - Techgeekclub"); ?>

<?php s_header(); ?>

<br/>

<?php s_get_component("dashboard"); ?>

<br/>

<?php s_footer(); ?>