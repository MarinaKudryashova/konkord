<?php
/**
 * Категория услуг
 * taxonomy-services_category.php
 * 
 * @deprecated Используется редирект на страницу каталога с ЧПУ
 */

$services_page_id = get_services_page_id();

if($services_page_id) {
    $page_slug = get_post_field('post_name', $services_page_id);
    $category = get_queried_object();
    
    if($category) {
        wp_redirect(home_url('/' . $page_slug . '/' . $category->slug . '/'), 301);
        exit;
    }
} else {
    get_header(); ?>
    <main class="main">
      <div class="sec-light sec-offset">
        <div class="container">
            <h1 class="sec-services__title sec-title"><?php single_term_title(); ?></h1>
            <?php if(have_posts()) : ?>
                <?php while(have_posts()) : the_post(); ?>
                    <h2><?php the_title(); ?></h2>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
      </div>
    </main>
    <?php get_footer();
}