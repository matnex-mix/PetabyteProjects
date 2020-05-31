<?php include 'src/components/functions.php'; ?>

<?php
  //Pre-defined needs
  s_storage('scripts', array(
    'empty' => 'is_null'
  ));
?>

<?php page_title("TECHGEEKCLUB - We prepare the future"); ?>

<?php s_header(); ?>

<br/>

<?php s_get_component("index"); ?>

<br/>

<?php s_footer(); ?>