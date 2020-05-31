<?php include 'src/components/functions.php'; ?>

<?php
  //Pre-defined needs
  s_storage('scripts', array(
    'empty' => 'is_null'
  ));
?>

<?php page_title("About Us - Techgeekclub"); ?>

<?php s_header(); ?>

<br/>

<?php s_get_component("about"); ?>

<br/>

<?php s_footer(); ?>