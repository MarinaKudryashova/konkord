<?php 
  // $page_id = $args["page_id"];
  // var_dump($page_id);
  
  // $catalog_title = get_the_title($page_id);


?>

<!-- <section class="sec-catalog">
   <div class="sec-catalog__container container">
      <h1 class="sec-catalog__title sec-title"><?php //echo $catalog_title; ?></h1>
      <div class="sec-catalog__content"> -->
        <?php 
        // Получаем все категории services_category (кастомная таксономия)
        // $categories = get_terms(array(
        //   'taxonomy' => 'services_category',
        //   'orderby' => 'name',
        //   'order' => 'ASC',
        //   'hide_empty' => false,
        // ));
        
        // URL страницы каталога (текущая страница)
        // $catalog_page_url = get_permalink($page_id);
        ?>
        
        <?php //if(!empty($categories) && !is_wp_error($categories)) : ?>
          <!-- Навигация по категориям услуг -->
          <!-- <nav class="sec-catalog__nav categories-nav">
            <ul class="categories-nav__list">
              <li class="categories-nav__item">
                <a href="<?php //echo esc_url($catalog_page_url); ?>" class="categories-nav__link <?php //echo !is_tax('services_category') ? 'is-active' : ''; ?>">
                  <?php //echo __('Все', 'konkord'); ?>
                </a>
              </li>
              <?php //foreach($categories as $cat) : ?>
                <li class="categories-nav__item">
                  <a href="<?php //echo esc_url(get_term_link($cat)); ?>" class="categories-nav__link <?php //echo (is_tax('services_category', $cat->term_id)) ? 'is-active' : ''; ?>">
                    <?php //echo esc_html($cat->name); ?>
                  </a>
                </li>
              <?php //endforeach; ?>
            </ul>
          </nav> -->
          
          <?php 
          // // Определяем контекст: страница категории или главная каталога
          // if(is_tax('services_category')) {
          //     // Страница категории услуг
          //     $current_term = get_queried_object();
              
          //     $args = array(
          //         'post_type' => 'services',
          //         'post_status' => 'publish',
          //         'posts_per_page' => -1,
          //         'tax_query' => array(
          //             array(
          //                 'taxonomy' => 'services_category',
          //                 'field' => 'term_id',
          //                 'terms' => $current_term->term_id,
          //             ),
          //         ),
          //     );
          // } else {
          //     // Главная страница каталога
          //     $args = array(
          //         'post_type' => 'services',
          //         'post_status' => 'publish',
          //         'posts_per_page' => -1,
          //     );
          // }
          
          // $query = new WP_Query($args);
          ?>
          
          <!-- Вывод услуг -->
          <!-- <div class="posts-container">
            <?php //if($query->have_posts()) : ?>
              <ul class="posts list-reset">
                <?php //while($query->have_posts()) : $query->the_post(); ?>
                  <li class="posts__item">
                    <?php g//et_template_part('template-parts/content', 'post-card'); ?>
                  </li>
                <?php //endwhile; ?>
                <?php //wp_reset_postdata(); ?>
              </ul>
            <?php //else: ?>
              <p class="no-posts">Услуг не найдено</p>
            <?php //endif; ?>
          </div>
        <?php //endif; ?>
      </div>
    </div>
</section> -->