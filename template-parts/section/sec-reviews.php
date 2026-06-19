<?php 
  $page_id = $args["page_id"];
  $sec_name = $args["name"]["value"];
  $sec_is_last = (int) $args["lastblock"] ?? 0;

  $sec_class = '';
  if($sec_is_last != 1) {
    $sec_class .= ' sec-offset';
  }

  $sec_reviews_title = get_field('reviews_title', 'option');
  $sec_reviews_widget_map = get_field('reviews_widget_map', 'option');
  $sec_reviews_widget_reviews = get_field('reviews_widget_reviews', 'option');
?>
<section class="<?php echo esc_attr($sec_class); ?>">
  <div class="sec-reviews__container container">
    <?php if($sec_reviews_title && !is_page('reviews')) : ?>
      <h2 class="sec-reviews__title sec-title" data-aos="fade-up"><?php echo $sec_reviews_title; ?></h2>
    <?php endif; ?>

    <?php if($sec_reviews_widget_map && $sec_reviews_widget_reviews) : ?>
    <div class="sec-reviews__widgets">
      <?php if($sec_reviews_widget_map) : ?>
      <div class="sec-reviews__widget-map"><?php echo $sec_reviews_widget_map; ?></div>
      <?php endif; ?>

      <?php if($sec_reviews_widget_reviews) : ?>
      <div class="sec-reviews__widget-reviews"><?php echo $sec_reviews_widget_reviews; ?></div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  </div>
</section>