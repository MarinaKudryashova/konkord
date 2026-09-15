<?php 
  $page_id = $args["page_id"]  ?? 0;
  $card_id = $args["slide"] ?? null;
  $date = get_the_date('j F, Y', $card_id);
  $card_title = get_the_title($card_id);
  $card_url = get_permalink($card_id) ?: '#';
  $thumb_id = get_post_thumbnail_id( $card_id );
  $card_img = $thumb_id
      ? get_image_versions( $thumb_id, 'full' )
      : get_placeholder_image();
  $card_excerpt = get_field('card-news_text', $card_id);
  $mobile = konkord_resolve_mobile_sources( $card_img );
 ?>
<a href="<?php echo esc_url($card_url) ?>" class="card-news">
  <picture  class="card-news__img">
    <?php konkord_picture_mobile_sources( $mobile ); ?>
    <?php if ( ! empty( $card_img['webp_1x'] ) ) : ?>
    <source srcset="<?php echo esc_url($card_img["webp_1x"]); ?>" type="image/webp">
    <?php endif; ?>
    <img loading="lazy" decoding="async" src="<?php echo esc_url($card_img["original_1x"]); ?>" width="313" height="216" alt="" aria-hidden="true" sizes="(max-width: 576px) 90vw, 313px">
  </picture>
  <div class="card-news__content">
    <?php /* == Дата публикации == */ ?>
    <span class="card-news__date"><?php echo esc_html($date); ?></span>

    <?php if($card_title) : ?>
      <h3 class="card-news__title"><?php echo esc_html($card_title); ?></h3>
    <?php endif; ?>
    <?php if($card_excerpt) : ?>
    <div class="card-news__excerpt is-clamp" style="--lines:8;"><?php echo esc_html($card_excerpt); ?></div>
    <?php endif; ?>
  </div>
</a>
