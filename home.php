<?php
/*
* Шаблон для страницы записей (Блог/Новости)
*/
?>

<?php 
get_header(); 
$page_id = get_option('page_for_posts');

?>

<?php get_header(); ?>
  <main class="main">
    <?php 
      get_template_part('template-parts/components/sections', '', array(
        'page_id' => $page_id,
        'include_breadcrumbs' => true,
        'content_template' => 'news' // content-news.php
      ));
    ?>
  </main>
  
<?php get_footer(); ?>