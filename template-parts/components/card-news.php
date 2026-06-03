<?php 
  $page_id = $args["id"]  ?? 0;
  $card_id = $args["slide"] ?? null;
  $card_title = get_the_title($card_id);
  $card_url = get_permalink($card_id) ?: '#';
  $card_thumbnail_url = get_the_post_thumbnail_url($card_id);
  $card_img = $card_thumbnail_url 
      ? get_image_versions($card_thumbnail_url)
      : get_placeholder_image();
  $card_excerpt = get_field('card-news_text', $card_id);
 ?>
<a href="<?php echo esc_url($card_url) ?>" class="card-news">
  <picture  class="card-news__img">
    <source srcset="<?php echo esc_url($card_img["webp_1x"]); ?>" type="image/webp">
    <img loading="lazy" src="<?php echo esc_url($card_img["original_1x"]); ?>" width="313" height="216" alt="" aria-hidden="true">
  </picture>
  <div class="card-news__content">
    <?php if($card_title) : ?>
      <h3 class="card-news__title"><?php echo esc_html($card_title); ?></h3>
    <?php endif; ?>
    <?php if($card_excerpt) : ?>
    <div class="card-news__excerpt is-clamp" style="--lines:8;"><?php echo esc_html($card_excerpt); ?></div>
    <?php endif; ?>
  </div>
</a>