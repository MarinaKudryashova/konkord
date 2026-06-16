<?php
$page_id = $args["page_id"];
$page_title = $page_id ? get_the_title($page_id) : 'Услуги';

// Только ЧПУ, без поддержки GET-параметров
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$current_cat = get_query_var('services_category');

$args = array(
    'post_type' => 'services',
    'post_status' => 'publish',
    'posts_per_page' => get_services_per_page(),
    'paged' => $paged,
    'orderby' => 'menu_order',
    'order' => 'ASC'
);

// Добавляем фильтр по категории из ЧПУ
if(!empty($current_cat)) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'services_category',
            'field' => 'slug',
            'terms' => $current_cat
        )
    );
}

$services_query = new WP_Query($args);
$page_slug = get_post_field('post_name', $page_id);
?>

<section class="sec-services sec-offset">
  <?php get_template_part('template-parts/components/breadcrumbs'); ?>
  
  <div class="sec-services__container container">
    <h1 class="sec-services__title sec-title" data-aos="fade-up"><?php echo esc_html($page_title); ?></h1>

    <div class="sec-services__content">
      <?php if($services_query->have_posts()) : ?>
      <!-- Навигация по категориям с ЧПУ ссылками -->
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
            $url = home_url('/' . $page_slug . '/' . $cat->slug . '/');
          ?>
            <li class="categories-nav__item">
              <a href="<?php echo esc_url($url); ?>" class="categories-nav__link <?php echo $active; ?>">
                <?php echo esc_html($cat->name); ?>
                <!-- <span class="count">(<?php //echo $cat->count; ?>)</span> -->
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      
      <!-- Список услуг -->
      <ul class="sec-services__list" data-aos="fade-up" data-aos-delay="400">
        <?php $index = 0; ?>
        <?php while($services_query->have_posts()) : $services_query->the_post(); ?>
        <?php 
          $service_card_title = get_the_title();
          $service_card_url = get_permalink() ?: '#';
          $service_card_thumbnail_url = get_the_post_thumbnail_url();
          $service_card_img = $service_card_thumbnail_url 
              ? get_image_versions($service_card_thumbnail_url)
              : get_placeholder_image();
          ?>
          <li class="sec-services__item" data-aos="fade-up" data-aos-duration="600" data-aos-delay="<?php echo $index++*100 + 50; ?>"> 
          <a class="service-card" href="<?php echo esc_url($service_card_url); ?>"
            aria-label="Перейти в услугу «<?php echo esc_html($service_card_title); ?>»">
            <h3 class="service-card__title"><?php echo esc_html($service_card_title); ?></h3>
            <picture class="service-card__img">
              <source srcset="<?php echo esc_url($service_card_img['webp_1x']); ?>" type="image/webp">
              <img src="<?php echo esc_url($service_card_img['original_1x']); ?>" width="360" height="354" alt="<?php echo esc_html($service_card_title); ?>">
            </picture>
          </a>
          </li>

        <?php endwhile; ?>
      </ul>

      <?php wp_reset_postdata(); ?>
      <?php else : ?>
        <p class="not-found">Услуг не найдено</p>
      <?php endif; ?>
    </div>

  </div>
</section>