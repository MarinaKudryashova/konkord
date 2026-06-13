<?php 
  $page_id = $args["page_id"];
  
  $news_title = get_the_title($page_id);

  $news_img_url = get_the_post_thumbnail_url($page_id, 'full');
  $news_img = $news_img_url 
    ? get_image_versions($news_img_url)
    : get_placeholder_image();

  $news_img_mobile_url = get_field('page_img_mobile', $page_id);
  $news_img_mobile = $news_img_mobile_url 
    ? get_image_versions($news_img_mobile_url)
    : $news_img;

	$news_banner_text = get_field('news-banner_text', $page_id);
	$news_banner_email = get_field('news-banner_email', $page_id);
	$news_banner_phone = get_field('news-banner_phone', $page_id);
	$news_banner_phone_href = preg_replace('![^0-9]+!', '', $news_banner_phone);
?>

<section class="post-news">
  <div class="post-news__container container">
    <div class="post-news__heading">
      <picture class="post-news__bgimg">
          <?php if (!empty( $news_img_mobile['webp_1x'])): ?>
          <source media="(max-width: 576px)" srcset="<?php echo esc_url( $news_img_mobile['webp_1x']); ?>" type="image/webp">
          <?php endif; ?>
          <?php if (!empty( $news_img_mobile['original_1x'])): ?>
          <source media="(max-width: 576px)" srcset="<?php echo esc_url( $news_img_mobile['original_1x']); ?>" type="image/jpg">
          <?php endif; ?>
          <source srcset="<?php echo esc_url($news_img["webp_1x"]); ?>" type="image/webp">
          <img src="<?php echo esc_url($news_img["original_1x"]); ?>" alt="Фотофон страницы" width="1160" height="476" aria-hidden="true">
      </picture>
      <h1 class="post-news__title"><?php echo $news_title; ?></h1>
    </div>
    
    <div class="post-news__content">
      <?php the_content(); ?>
      <?php if ($news_banner_text || $news_banner_email || $news_banner_phone) : ?>
      <div class="post-news__banner">
        <?php if (!empty( $news_banner_text)): ?>
        <p><?php echo wp_kses_post($news_banner_text); ?></p>
        <?php endif; ?>
        <div class="post-news__links">
          <?php if($news_banner_email) : ?>
            <a href="mailto:<?php echo esc_attr($news_banner_email); ?>" class="post-news__email">
              <svg>
                <use xlink:href="<?php echo esc_url(get_template_directory_uri()); ?>/img/sprite.svg#email"></use>
              </svg>
              <?php echo esc_html($news_banner_email); ?>
            </a>
          <?php endif; ?>

          <?php if(!empty($news_banner_phone)) : ?>
          <a href="tel:<?php echo $news_banner_phone_href; ?>" class="post-news__phone">
            <svg>
              <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#phone"></use>
            </svg>
            <span><?php echo $news_banner_phone; ?></span>
          </a>
          <?php endif; ?>
        </div>

      </div>
      <?php endif; ?>

    </div>

  </div>
</section>