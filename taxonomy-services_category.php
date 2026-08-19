<?php
/**
 * Категория услуг
 * taxonomy-services_category.php
 * 
 * @deprecated Используется редирект на страницу каталога с ЧПУ
 */

get_header();

$services_page_id = get_services_page_id();
?>
    <main class="main">
        <?php 
        if($services_page_id) {
            get_template_part('template-parts/content-services', '', array('page_id' => $services_page_id)); 
        } else {
          ?>
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
          <?php  
        }
        ?>
    </main>
<?php
get_footer();

    // $page_slug = get_post_field('post_name', $services_page_id);
    // $category = get_queried_object();
    
    // if($category) {
    //     wp_redirect(home_url('/' . $page_slug . '/' . $category->slug . '/'), 301);
    //     exit;
    // }
        // if ($category) {
        // // Получаем город
        // $city = get_geo_city_from_query();
        
        // // Строим URL с учётом города
        // if (!empty($city)) {
        //     $redirect_url = home_url('/' . $city . '/' . $page_slug . '/' . $category->slug . '/');
        // } else {
        //     $redirect_url = home_url('/' . $page_slug . '/' . $category->slug . '/');
        // }
        
        // wp_redirect($redirect_url, 301);
        // exit;
    // }