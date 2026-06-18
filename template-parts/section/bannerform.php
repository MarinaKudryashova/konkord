<?php 
  /*
  * Section: Баннер-форма 
  */
  
  $page_id = $args["page_id"];
  $sec_name = $args["name"]["value"];

  $bannerform_title = get_field('bannerform_title', 'option');
  $bannerform_descr = get_field('bannerform_descr', 'option');
  $bannerform_shortcode = get_field('bannerform_shortcode', 'option');
  $bannerform_messanges = get_field('bannerform_messanges', 'option');
  $messanges = get_field('messengers_list', 'options');

  $bannerform_img_url = get_field('bannerform_img', 'option');
  $bannerform_img = $bannerform_img_url 
    ? get_image_versions($bannerform_img_url)
    : get_placeholder_image();

  $bannerform_img_url_mobile = get_field('bannerform_img_mobile', 'option');
  $bannerform_img_mobile = $bannerform_img_url_mobile 
    ? get_image_versions($bannerform_img_url_mobile)
    : $employee_img;
?>

<section class="bannerform">
  <div class="bannerform__bg">
    <picture class="bannerform__picture">
      <?php if (!empty($bannerform_img_mobile['webp_1x'])) : ?>
      <source media="(max-width: 576px)" srcset="<?php echo esc_url($bannerform_img_mobile['webp_1x']); ?>" type="image/jpg">
      <?php endif; ?>
      <?php if (!empty($bannerform_img_mobile['original_1x'])) : ?>
      <source media="(max-width: 576px)" srcset="<?php echo esc_url($bannerform_img_mobile['original_1x']); ?>" type="image/jpg">
      <?php endif; ?>

      <?php if (!empty($bannerform_img['webp_1x'])) : ?>
      <source srcset="<?php echo esc_url($bannerform_img['webp_1x']); ?>" type="image/webp">
      <?php endif; ?>
      <img class="bannerform__img" src="<?php echo esc_url($bannerform_img['original_1x']); ?>" width="1443" height="534" alt="Фото" aria-hidden="true" loading="lazy">
    </picture>
  </div>
  <div class="bannerform__container container" data-aos="fade-up">
    <div class="bannerform__content">
      <?php if (!empty($bannerform_title)) : ?>
      <h2 class="bannerform__title"><?php echo esc_html($bannerform_title); ?></h2>
      <?php endif; ?>

      <?php if (!empty($bannerform_descr)) : ?>
      <p class="bannerform__descr"><?php echo esc_html($bannerform_descr); ?></p>
      <?php endif; ?>

      <!-- Мессенджеры тут-->
       <?php if($messanges) : ?>
						<ul class="bannerform__messanges messanges" title="messanges">
							<?php foreach($messanges as $li) : ?>
								<a href="<?php  echo get_field($li['value'], 'options'); ?>" target="_blank" class="messanges__link <?php if($li["value"] == 'vk') : ?>messanges__link--vk<?php endif; ?>" aria-label="Свяжитесь с нами в <?php echo $li['label']; ?>">
									<img loading="lazy" src="<?php echo get_template_directory_uri();?>/img/icon/<?php echo esc_html__($li['value']); ?>.svg" class="messanges__icon" width="16" height="16" alt="иконка <?php  echo $li['label']; ?>" aria-hidden="true">
								</a>
							</li>
							<?php endforeach;	?>
						</ul>
					<?php endif; ?>
    </div>


      <?php if (!empty($bannerform_shortcode)) : ?>
      <div class="bannerform__form form">
        <?php echo do_shortcode($bannerform_shortcode); ?>
      </div>
      <?php endif; ?>
  </div>
</section>