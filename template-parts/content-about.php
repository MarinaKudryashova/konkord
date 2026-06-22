<?php 
  $page_id = $args["page_id"];
  $sec_is_last = (int) $args["lastblock"] ?? 0;

  $sec_class = 'about';
  if($sec_is_last != 1) {
    $sec_class .= ' sec-offset';
  }
  
  $about_title = get_the_title($page_id);
  $about_img_url = get_the_post_thumbnail_url($page_id, 'full');
  $about_img = $about_img_url 
    ? get_image_versions($about_img_url)
    : get_placeholder_image();

  $about_img_mobile_url = get_field('page_img_mobile', $page_id);
  $about_img_mobile = $about_img_mobile_url 
    ? get_image_versions($about_img_mobile_url)
    : $about_img;
  $about_short_descr = get_field('about_short_descr', $page_id);
  $about_qoute = get_field('about_qoute', $page_id);
  $about_gallery_steps = get_field('about_gallery_steps', $page_id);

?>

<section class="<?php echo esc_attr($sec_class); ?>">
   <div class="about__container container">
      <div class="about__heading">
         <picture class="about__bgimg">
            <?php if (!empty( $about_img_mobile['webp_1x'])): ?>
            <source media="(max-width: 576px)" srcset="<?php echo esc_url( $about_img_mobile['webp_1x']); ?>" type="image/webp">
            <?php endif; ?>
            <?php if (!empty( $about_img_mobile['original_1x'])): ?>
            <source media="(max-width: 576px)" srcset="<?php echo esc_url( $about_img_mobile['original_1x']); ?>" type="image/jpg">
            <?php endif; ?>
            <source srcset="<?php echo esc_url($about_img["webp_1x"]); ?>" type="image/webp">
            <img src="<?php echo esc_url($about_img["original_1x"]); ?>" alt="Фотофон страницы" width="1160" height="476" aria-hidden="true">
         </picture>
         <h1 class="about__title" data-aos="fade-up"><?php echo $about_title; ?><br> Конкорд</h1>
         <?php /* == Краткое описание == */ ?>
          <?php if (!empty( $about_short_descr)): ?>
            <p class="about__descr" data-aos="fade-up" data-aos-delay="200"><?php echo $about_short_descr; ?></p>
          <?php endif; ?>
      </div>

      <?php /* == Цитата == */ ?>
      <?php if (!empty($about_qoute)): ?>
      <div class="about__quote" data-aos="fade-up">
         <blockquote><?php echo $about_qoute; ?></blockquote>
         <img class="about__icon" loading="lazy" src="<?php echo get_template_directory_uri();?>/img/icon/quotation.svg" width="88" height="57" alt="" aria-hidden="true">
         <img class="about__icon about__icon--right" loading="lazy" src="<?php echo get_template_directory_uri();?>/img/icon/quotation.svg" width="88" height="57" alt="" aria-hidden="true">
      </div>
      <?php endif; ?>

      <?php /* == Галерея с этапами == */ ?>
      <?php if(!empty($about_gallery_steps) && is_array($about_gallery_steps)) : ?>
      <ul class="about__gallery" data-aos="fade-up">
      <?php foreach($about_gallery_steps as $ids => $step) : 
        $step_type = $step["type"];
        $step_title = $step["title"];
        $step_text = $step["text"];
        $step_img_url = $step["img"];
        $step_img = $step_img_url 
        ? get_image_versions($step_img_url)
        : get_placeholder_image();
        ?>
         <li class="about__gallery-item about-gallery" data-aos="fade-up" data-aos-once="false" data-aos-duration="600" data-aos-delay="<?php echo $ids*100 + 50; ?>">
           <?php if($step_type === 'img') : ?>
               <?php /*-- Изображение --*/ ?>
            <a data-fslightbox="about-gallery-<?php echo $page_id ?>" data-caption="" href="<?php echo $step_img_url ?>" class="about-gallery__link">
              <picture class="about-gallery__img">
                <?php if($step_img["webp_1x"]) : ?>
                  <source srcset="<?php echo esc_url($step_img["webp_1x"]); ?>" type="image/webp">
                <?php endif; ?>
                <img loading="lazy" src="<?php echo esc_url($step_img["original_1x"]); ?>" width="374" height="374" alt="" aria-hidden="true">
              </picture>
            </a>
            <?php endif; ?>
            
            <?php if($step_type === 'text') : ?>
             <div class="gallery-card">
                <span class="gallery-card__name"><?php echo wp_kses_post($step_title); ?></span>
                <p class="gallery-card__text"><?php echo wp_kses_post($step_text); ?></p>
             </div>
             <?php endif; ?>
         </li>
         <?php endforeach; ?>
      </ul>
      <?php endif; ?>
   </div>
</section>