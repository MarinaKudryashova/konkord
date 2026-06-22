<?php 
  $page_id = $args["page_id"]  ?? 0;
  $card_id = $args["slide"] ?? null;

  $card_obj = get_term($card_id);
  if($card_obj && !is_wp_error($card_obj)) :
    $card_name = $card_obj->name;
    $card_link = get_term_link($card_obj);
    $card_img_url = get_field('services_category_img', 'term_' . $card_obj->term_id);
    $card_mg = $card_img_url 
      ? get_image_versions($card_img_url)
      : get_placeholder_image();
    $card_img_url_mobile = get_field('services_category_img_mobile', 'term_' . $card_obj->term_id);
    $card_img_mobile = $card_img_url_mobile 
      ? get_image_versions($card_img_url_mobile)
      : $card_mg;

  endif;

 ?>

  <a class="card-services-cat" href="<?php echo esc_url($card_link); ?>"
    aria-label="Перейти в категорию «<?php echo esc_attr($card_name); ?>»">
    <h3 class="card-services-cat__title"><?php echo esc_html($card_name); ?></h3>
    <picture class="card-services-cat__img">
    <?php if (!empty( $card_img_mobile['webp_1x'])): ?>
      <source media="(max-width: 576px)" srcset="<?php echo esc_url( $card_img_mobile['webp_1x']); ?>" type="image/webp">
    <?php endif; ?>
    <?php if (!empty( $card_img_mobile['original_1x'])): ?>
      <source media="(max-width: 576px)" srcset="<?php echo esc_url( $card_img_mobile['original_1x']); ?>" type="image/jpg">
    <?php endif; ?>
      <source srcset="<?php echo esc_url($card_mg['webp_1x']); ?>" type="image/webp">
      <img src="<?php echo esc_url($card_mg['original_1x']); ?>" width="374" height="354" alt="<?php echo esc_attr($card_name); ?>">
    </picture>
  </a>