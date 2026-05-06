<?php
  $page_id = $args["id"];
  
  $promo_title = get_field('promo_title', $page_id);
  $promo_descr = get_field('promo_descr', $page_id);
?>

<section class="promo sec-offset sec-light">

  <div class="promo__container container">
    <div class="promo__content">
      <?php if(!empty($promo_title)) : ?>
      <h1 class="promo__title"><?php echo wp_kses_post($promo_title); ?></h1>
      <?php endif; ?>
  
      <?php if(!empty($promo_descr)) : ?>
      <p class="promo__descr"><?php echo esc_html($promo_descr); ?></p>
      <?php endif; ?>
      
      <picture class="promo__picture">
        <!-- <source srcset="<?php echo get_template_directory_uri(); ?>/img/promo/promo-picture.webp" type="image/webp"> -->
        <img src="<?php echo get_template_directory_uri(); ?>/img/promo/promo-picture.png" class="image" width="651" height="545" alt="">
      </picture>

      <?php /*-- Кнопка с формой --*/ ?>
			<button type="button" class="promo__callback ui-btn" data-graph-path="modal-leadform">Узнать стоимость</button>
    </div>
  </div>
</section>