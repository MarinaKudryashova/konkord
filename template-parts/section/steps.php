<?php 
  $page_id = $args["page_id"];
  $sec_name = $args["name"]["value"];
  $sec_is_last = (int) $args["lastblock"] ?? 0;

  $sec_class = 'steps';
  if($sec_is_last != 1) {
    $sec_class .= ' sec-offset';
  }

  $title_field = $sec_name . '_title';
  $list_field  = $sec_name;

  $steps_title = get_field($title_field, $page_id);
  $steps_list  = get_field($list_field, $page_id);
  $step_count = 1;
?>
<section class="<?php echo esc_attr($sec_class); ?>">
  <div class="steps__container container">
    <?php if($steps_title) : ?>
      <h2 class="steps__title sec-title" data-aos="fade-up"><?php echo $steps_title; ?></h2>
    <?php endif; ?>
    <?php if(!empty($steps_list) && is_array($steps_list)) : ?>
    <ul class="steps__list">
      <?php foreach($steps_list as $ids => $step) : 
        $step_type = $step["type"];
        $step_title = $step["title"];
        $step_text = $step["text"];
        $step_img_url = $step["img"];
        $step_img = $step_img_url 
        ? get_image_versions($step_img_url)
        : get_placeholder_image();
        ?>
        <li class="steps__item" data-aos="fade-up" data-aos-offset="0" data-aos-duration="500" data-aos-delay="<?php echo $ids*100 + 50; ?>">
          <?php if($step_type === 'text') : ?>
          <div class="card-steps" <?php if($step_type === 'text') : ?>data-step = "<?php echo $step_count++; ?>"<?php endif; ?>>
            <span class="card-steps__title"><?php echo wp_kses_post($step_title); ?></span>
            <span class="card-steps__text"><?php echo wp_kses_post($step_text); ?></span>
          </div>
           <?php endif; ?>
          <?php if($step_type === 'img') : ?>
            <?php /*-- Изображение --*/ ?>
            <picture class="steps__img">
              <?php if($step_img["webp_1x"]) : ?>
                <source srcset="<?php echo esc_url($step_img["webp_1x"]); ?>" type="image/webp">
              <?php endif; ?>
              <img loading="lazy" src="<?php echo esc_url($step_img["original_1x"]); ?>" width="465" height="344" alt="" aria-hidden="true">
            </picture>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</section>