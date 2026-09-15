<?php
  /* 
  * Section: Промо блок на главной странице
  */
  
  $page_id = $args["page_id"];
  
  $promo_title = get_field('promo_title', $page_id);
  $promo_descr = get_field('promo_descr', $page_id);
  $promo_img_url = get_field('promo_bgimg', $page_id);
  $promo_img = $promo_img_url ? get_image_versions($promo_img_url) : '#';

  $promo_video = get_field('promo_video', $page_id);
  $promo_video_webm = get_field('promo_video_webm', $page_id);
  $poster_url = '';
  if ($promo_img) {
    $poster_url = !empty($promo_img['webp_1x']) 
      ? $promo_img['webp_1x'] 
      : (!empty($promo_img['original_1x']) 
        ? $promo_img['original_1x'] 
        : '');
  }
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

      <?php if ($promo_video || $promo_video_webm || $poster_url) : ?>
      <div class="promo__video" data-aos="fade-up" data-aos-delay="150">
        <video
          id="promoVideo"
          playsinline
          muted
          loop
          preload="none"
          aria-hidden="true"
          tabindex="-1"
          <?php if ($poster_url) : ?>poster="<?php echo esc_url($poster_url); ?>"<?php endif; ?>
          <?php if ($promo_video_webm) : ?>data-src-webm="<?php echo esc_url($promo_video_webm); ?>"<?php endif; ?>
          <?php if ($promo_video) : ?>data-src-mp4="<?php echo esc_url($promo_video); ?>"<?php endif; ?>
        ></video>
      </div>
      <?php endif; ?>

      <?php /*-- Кнопка с формой --*/ ?>
			<button type="button" 
              class="promo__callback ui-btn"
              data-graph-path="modal-leadform" data-aos="fade-up" data-aos-delay="150">
        Узнать стоимость
      </button>
    </div>
  </div>
</section>
