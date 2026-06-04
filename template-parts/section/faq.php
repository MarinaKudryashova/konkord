<?php
  $page_id = $args["page_id"];
  $sec_name = $args["name"]["value"];
  $sec_is_last = $args["lastblock"];

  $faq_class = 'faq';
  if($sec_is_last != 1) {
    $faq_class .= ' sec-offset';
  }

  $title_field = $sec_name . '_title';
  $list_field  = $sec_name . '_list';

  $block_title = get_field($title_field, $page_id);
  $block_list = get_field($list_field, $page_id);

  $faq_img_url = get_field('faq_img', $page_id);
  $faq_img = get_image_versions($faq_img_url);

  if($faq_img) {
    $faq_img_mobile_url = get_field('faq_img_mobile', $page_id);
    $faq_img_mobile = $faq_img_mobile_url ? get_image_versions($faq_img_mobile_url) : $faq_img;
  }
?>

<section class="faq" itemscope itemtype="https://schema.org/FAQPage">
  <div class="container">
    <div class="faq__heading">
      <?php if($block_title) : ?>
        <h2 class="faq__title sec-title" data-aos="fade-up"><?php echo $block_title; ?></h2>
      <?php endif; ?>

      <?php /*-- Кнопка с формой --*/ ?>
			<button type="button" class="faq__callback ui-btn" data-graph-path="modal-leadform">Задать вопрос</button>
    </div>

    <div class="faq__inner">

      <?php if($faq_img) : ?>
        <picture class="faq__img">
          <?php if($faq_img["webp_1x"]) : ?>
            <?php if (!empty( $faq_img_mobile['webp_1x'])): ?>
          <source media="(max-width: 576px)" srcset="<?php echo esc_url( $faq_img_mobile['webp_1x']); ?>" type="image/webp">
          <?php endif; ?>
          <?php if (!empty( $faq_img_mobile['original_1x'])): ?>
            <source media="(max-width: 576px)" srcset="<?php echo esc_url( $faq_img_mobile['original_1x']); ?>" type="image/jpg">
          <?php endif; ?>
            <source srcset="<?php echo esc_url($faq_img["webp_1x"]); ?>" type="image/webp">
          <?php endif; ?>
          <img src="<?php echo esc_url($faq_img["original_1x"]); ?>" width="550" height="1035" alt="" aria-hidden="true">
        </picture>
      <?php endif; ?>

      <?php if(is_array($block_list)) : ?>
        <div class="faq__content accordion" data-aos="fade-up">
          <?php foreach($block_list as $index => $faq) : 
              $is_first = ($index === 0);
              $item_class = 'accordion__item';
              if ($is_first) {
                $item_class .= ' is-open';
              }
            ?>
            <div class="<?php echo $item_class; ?>" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
              <button class="accordion__control" aria-expanded="false">
                <span class="accordion__title" itemprop="name"><?php echo esc_html($faq->post_title); ?></span>
                <span class="accordion__icon">
                  <svg>
                    <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#plus"></use>
                  </svg>
                </span>
              </button>
              <div class="accordion__content" aria-hidden="true" itemprop="acceptedAnswer" itemscope="" itemtype="http://schema.org/Answer">
                <div class="accordion__text" itemprop="text"><?php echo wp_kses_post($faq->post_content); ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else : ?>
        <p><?php esc_html_e( 'В этом разделе пока нет информации.', 'konkord' ); ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>