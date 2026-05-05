<?php
/*
Template Name: Главная страница
Template Post Type: page
*/

$page_id = get_the_ID();
?>

<?php get_header(); ?>
  <main class="main">
    <?php get_template_part( "template-parts/section/promo-main", null, array('id' => $page_id) );

      $arBlock = get_field('show_block_page_main');
      if ($arBlock) :
        foreach ($arBlock as $block) {
           get_template_part( "template-parts/section/$block[value]", '', array('id' => $page_id, 'name'  => $block) );
        }
      endif;
    ?>
  </main>
  
<?php get_footer(); ?>