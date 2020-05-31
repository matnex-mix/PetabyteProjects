<?php include 'src/components/functions.php'; ?>

<?php
  //Pre-defined needs
  s_storage('scripts', array(
    'swap-form' => 'swap-form.js'
  ));
?>

<?php page_title("Contact Us at TechGeekClub"); ?>

<?php s_header(); ?>

<br/>

<?php s_get_component("contact"); ?>

<br/>

<?php s_footer(); ?>