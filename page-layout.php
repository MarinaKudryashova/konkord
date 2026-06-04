<?php
/*
Template Name: Требования к макетам
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
        'content_template' => 'layout' // content-layout.php
      ));
    ?>
  </main>
  
<?php get_footer(); ?>