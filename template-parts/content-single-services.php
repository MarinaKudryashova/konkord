<?php 
  $page_id = $args["page_id"];
  
  $service_title = get_the_title($page_id);
  $service_shortdescr = get_the_content($page_id);

  $service_img_url = get_the_post_thumbnail_url($page_id, 'full');
  $service_img = $service_img_url 
    ? get_image_versions($service_img_url)
    : get_placeholder_image();


	$ss_variant_gallery = get_field('services_gallery', $page_id);
	$ss_variant_title = get_field('services_descr_title', $page_id);
	$ss_variant_descr = get_field('services_descr_text', $page_id);

  $ss_benefit = get_field('benefits', $page_id);
  
  $ss_special_title = get_field('special_title', $page_id);
  $ss_special_descr = get_field('special_descr', $page_id);

  // var_dump($ss_benefit);
?>

<section class="single-services">
  <div class="single-services__container container">
    <div class="single-services__heading sec-offset">
      <div class="single-services__content">
        <h1 class="single-services__title sec-title"><?php echo $service_title; ?></h1>
        <div class="single-services__shortdescr"><?php echo wp_kses_post($service_shortdescr); ?></div>
        <button type="button" class="single-services__callback ui-btn" data-graph-path="modal-leadform">Заказать печать</button>
      </div>
      <picture class="single-services__bgimg">
          <source srcset="<?php echo esc_url($service_img["webp_1x"]); ?>" type="image/webp">
          <img src="<?php echo esc_url($service_img["original_1x"]); ?>" alt="Услуга: <?php echo $service_title; ?>" width="620" height="504">
      </picture>
    </div>

    <?php /* Варианты изготовления */ ?>
     <?php if($ss_variant_title || $ss_variant_descr || $ss_variant_gallery) : ?>
    <div class="ss-variant sec-offset">
      <?php if ($ss_variant_gallery && is_array($ss_variant_gallery)) : ?>
        <div class="ss-variant__gallery slider-thumbs">
          <div class="swiper slider-thumbs-main slider">
            <div class="swiper-wrapper">
              <?php 
              foreach ($ss_variant_gallery as $image) :
                $image_url = esc_url($image['url']);
                $image_alt = esc_attr($image['alt']);
              ?>
                <div class="swiper-slide">
                  <picture class="slider-thumbs-main__img">
                    <img src="<?php echo $image_url; ?>" width="526" height="526" alt="<?php echo $image_alt; ?>" itemprop="image">
                  </picture>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="swiper slider-thumbs-navs slider">
            <div class="swiper-wrapper">
              <?php 
              foreach ($ss_variant_gallery as $image) : ?>
                <div class="swiper-slide">
                  <picture class="slider-thumbs-navs__img">
                    <img src="<?php echo esc_url($image['url']); ?>" width="93" height="93" alt="<?php echo esc_attr($image['alt']); ?>" itemprop="image">
                  </picture>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <div class="ss-variant__content">
        <?php if($ss_variant_title) : ?>
          <h2 class="ss-variant__title"><?php echo $ss_variant_title; ?></h2>
        <?php endif; ?>
        <?php if($ss_variant_descr) : ?>
          <div class="ss-variant__descr"><?php echo wp_kses_post($ss_variant_descr); ?></div>
        <?php endif; ?>
        <button type="button" class="ss-variant__callback ui-btn" data-graph-path="modal-leadform">рассчитать стоимость тиража</button>
      </div>
    </div>
    <?php endif; ?>

    <?php /* Преимущества */ ?>
    <?php if($ss_benefit && is_array($ss_benefit)) : ?>
      <div class="single-services__benefits sec-offset">
        <ul class="benefits">
          <?php foreach($ss_benefit as $key => $value): ?>
          <li class="benefits__item">
            <img loading="lazy" src="<?php echo esc_url($value['icon']); ?>" class="benefits__icon" width="80" height="80" alt="<?php echo $value['title']; ?>" aria-hidden="true">
            <span class="benefits__title" ><?php echo $value['title']; ?></span>
            <span class="benefits__text" ><?php echo $value['text']; ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php /* Особенности */ ?>
    <?php if ($ss_special_title &&  $ss_special_descr) : ?>
    <div class="single-services__special special accordion">
      <div class="accordion__item" data-aos="fade-up" data-aos-duration="600">
        <button class="accordion__control">
          <div class="special__title"><?php echo esc_html($ss_special_title); ?></div>
          <span class="accordion__icon">
            <svg>
              <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#shevron-down"></use>
            </svg>
          </span>
        </button>
        <div class="accordion__content" aria-hidden="true">
          <?php if (!empty($ss_special_descr)) : ?>
            <div class="special__descr"><?php echo wp_kses_post($ss_special_descr); ?></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>

  </div>
</section>