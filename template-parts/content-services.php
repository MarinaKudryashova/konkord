<?php
$page_id = $args["page_id"];
$page_title = $page_id ? get_the_title($page_id) : 'Услуги';

// Только ЧПУ, без GET-параметров
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$current_cat = get_query_var('services_category');
$current_city = get_geo_city_from_query(); 

$post_args = array(
    'post_type' => 'services',
    'post_status' => 'publish',
    'posts_per_page' => get_services_per_page(),
    'paged' => $paged,
    'orderby' => 'menu_order',
    'order' => 'ASC'
);

// Фильтр по категории
if(!empty($current_cat)) {
    $post_args['tax_query'] = array(
        array(
            'taxonomy' => 'services_category',
            'field' => 'slug',
            'terms' => $current_cat
        )
    );
}

$services_query = new WP_Query($post_args);
$max_pages = $services_query->max_num_pages;
$page_slug = get_post_field('post_name', $page_id);

// Передаём актуальные данные в JS
wp_add_inline_script('js-main', '
    if (typeof konkord_ajax !== "undefined") {
        konkord_ajax.services.max_pages = ' . intval($max_pages) . ';
        konkord_ajax.services.page_id = ' . intval($page_id) . ';
        konkord_ajax.services.cat_slug = "' . esc_js($current_cat) . '";
    } else {
        console.warn("konkord_ajax не определён!");
    }
', 'after');
?>

<section class="sec-services sec-offset">
  <?php get_template_part('template-parts/components/breadcrumbs'); ?>
  
  <div class="sec-services__container container">
    <h1 class="sec-services__title sec-title" data-aos="fade-up"><?php echo esc_html($page_title); ?></h1>

    <div class="sec-services__content">
      
      <!-- Навигация по категориям -->
      <div class="sec-services__nav categories-nav" data-aos="fade-up" data-aos-delay="200">
        <ul class="categories-nav__list">
          <li class="categories-nav__item">
            <a href="<?php echo get_permalink($page_id); ?>" class="categories-nav__link <?php echo empty($current_cat) ? 'is-active' : ''; ?>">
              Все услуги
            </a>
          </li>
          <?php
          $categories = get_terms(array(
            'taxonomy' => 'services_category',
            'hide_empty' => false
          ));
          
          foreach($categories as $cat) :
            $active = ($current_cat == $cat->slug) ? 'is-active' : '';
            // ИСПРАВЛЕНО: ссылки на категории ведут на /services-category/nazvanie/
            // Получаем полный путь для вложенных категорий
            $full_path = get_category_full_path($cat);
            $url = build_geo_url('services-category/' . $full_path, $current_city);
          ?>
            <li class="categories-nav__item">
              <a href="<?php echo esc_url($url); ?>" class="categories-nav__link <?php echo $active; ?>">
                <?php echo esc_html($cat->name); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      
      <!-- Список услуг -->
      <?php if($services_query->have_posts()) : ?>
      <ul class="sec-services__list" id="services-list">
        <?php $index = 0; ?>
        <?php while($services_query->have_posts()) : $services_query->the_post(); ?>
          <?php 
          $delay = 400 + $index++ * 100;
          ?>
          <li class="sec-services__item" data-aos="fade-up" data-aos-anchor=".sec-services__nav" data-aos-delay="<?php echo $delay; ?>"> 
            <?php render_service_item(get_the_ID()); ?>
          </li>
        <?php endwhile; ?>
      </ul>

      <!-- Индикатор загрузки -->
      <div id="services-loader" style="display: none; text-align: center; padding: 20px;">
        <span>Загрузка...</span>
      </div>

      <!-- Триггер для бесконечной загрузки -->
      <?php if ($max_pages > 1) : ?>
        <div id="services-trigger" style="height: 1px;"></div>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>
      <?php else : ?>
        <p class="not-found">Услуг не найдено</p>
      <?php endif; ?>
    </div>

  </div>
</section>