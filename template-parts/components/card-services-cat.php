<?php
  $page_id = $args["page_id"]  ?? 0;
  $services_cat_id = $args["services-cat-id"] ?? null;
  $services_cat_index = $args["services-cat-index"] ?? null;

  $services_cat_obj = get_term($services_cat_id);
  if($services_cat_obj && !is_wp_error($services_cat_obj)) :
    $services_cat_name = $services_cat_obj->name;
    $services_cat_link = get_term_link($services_cat_obj);
    $services_cat_img_url = get_field('services_category_img', 'term_' . $services_cat_obj->term_id);
    $services_cat_img = $services_cat_img_url 
      ? get_image_versions($services_cat_img_url)
      : get_placeholder_image();
    $services_cat_img_url_mobile = get_field( 'services_category_img_mobile', 'term_' . $services_cat_obj->term_id );
    $services_cat_img_mobile     = konkord_resolve_mobile_sources( $services_cat_img, $services_cat_img_url_mobile );

    // Большие карточки
    $services_cat_positions = [3, 9, 13, 19];
    $is_big = in_array($services_cat_index, $services_cat_positions);
    if($is_big) {
        $img_width = 770;
        $img_height = 354;
    } else {
        $img_width = 374;
        $img_height = 354;
    }
  endif;
  ?>
  <a class="card-services-cat" href="<?php echo esc_url($services_cat_link); ?>"
    aria-label="Перейти в категорию «<?php echo esc_html($services_cat_name); ?>»">
    <h3 class="card-services-cat__title"><?php echo esc_html($services_cat_name); ?></h3>
    <picture class="card-services-cat__img">
      <?php konkord_picture_mobile_sources( $services_cat_img_mobile ); ?>
      <source srcset="<?php echo esc_url($services_cat_img['webp_1x']); ?>" type="image/webp">
      <img loading="lazy" src="<?php echo esc_url($services_cat_img['original_1x']); ?>" width="<?php echo esc_attr($img_width); ?>" height="<?php echo esc_attr($img_height); ?>" alt="<?php echo esc_attr($services_cat_name); ?>">
    </picture>
  </a>
