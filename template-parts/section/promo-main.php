<?php
  /* 
  * Section: Промо блок на главной странице
  */
  
  $page_id = $args["page_id"];
  
  $promo_title = get_field('promo_title', $page_id);
  $promo_descr = get_field('promo_descr', $page_id);
  $promo_img_url = get_field('promo_bgimg', $page_id);
  $promo_img = $promo_img_url ? get_image_versions($promo_img_url) : '#';
?>

<section class="promo sec-offset sec-light">

  <div class="promo__container container">
    <div class="promo__content">
      <?php if(!empty($promo_title)) : ?>
      <h1 class="promo__title" data-aos="fade-up" data-aos-delay="50"><?php echo wp_kses_post($promo_title); ?></h1>
      <?php endif; ?>
  
      <?php if(!empty($promo_descr)) : ?>
      <p class="promo__descr" data-aos="fade-up" data-aos-delay="150"><?php echo esc_html($promo_descr); ?></p>
      <?php endif; ?>
      
      <picture class="promo__picture" data-aos="fade-up" data-aos-delay="50">
        <?php if($promo_img["webp_1x"]) : ?>
          <source srcset="<?php echo esc_url($promo_img["webp_1x"]); ?>" type="image/webp">
        <?php endif; ?>
        <img src="<?php echo esc_url($promo_img["original_1x"]); ?>" class="image" width="651" height="545" alt="">
      </picture>

      <?php /*-- Кнопка с формой --*/ ?>
			<button type="button" class="promo__callback ui-btn" data-graph-path="modal-leadform" data-aos="fade-up"  data-aos-delay="300">Узнать стоимость</button>
    </div>
  </div>
</section>