<?php
/*
Template Name: Главная страница
Template Post Type: page
*/

$page_id = get_the_ID();
?>

<?php get_header(); ?>
  <main class="main">
    <?php 
      get_template_part( "template-parts/section/promo-main", null, array('page_id' => $page_id) );
      get_template_part( "template-parts/section/sec-services-cat", null, array('page_id' => $page_id) );
      get_template_part('template-parts/components/sections', '', array(
          'page_id' => $page_id,
          'include_breadcrumbs' => false, //не нужены хлебные крошки
          'content_template' => false, // не нужен шаблон контента 
      ));
    ?>
  </main>
  
<?php get_footer(); ?>