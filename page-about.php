<?php
/*
Template Name: О компании
Template Post Type: page
*/

$page_id = get_the_ID();
?>

<?php get_header(); ?>
  <main class="main">
    <?php 
      get_template_part('template-parts/components/sections', '', array(
        'page_id' => $page_id,
        'include_breadcrumbs' => true,
        'content_template' => 'about' // content-about.php
      ));
    ?>
  </main>
  
<?php get_footer(); ?>