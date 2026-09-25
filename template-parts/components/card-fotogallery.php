<?php 
  $page_id = $args["page_id"]  ?? 0;
  $fotogallery_url = $args["slide"] ?? null;

  if (!$fotogallery_url) {
      return;
  }
  
  // Превью в сетке — large; lightbox href — оригинал (full URL из ACF).
  $fotogallery_img = $fotogallery_url ? get_image_versions( $fotogallery_url, 'large' ) : null;
  $mobile = $fotogallery_img ? konkord_resolve_mobile_sources( $fotogallery_img ) : null;
   if($fotogallery_img) : 
  ?>
    <a data-fslightbox="fotogallery-<?php echo $page_id ?>" data-caption="" href="<?php echo esc_url( $fotogallery_url ); ?>" class="card-fotogallery__link" aria-label="Открыть фото в галерее">
      <picture class="card-fotogallery__img">
        <?php konkord_picture_mobile_sources( $mobile ); ?>
        <?php if ( ! empty( $fotogallery_img["webp_1x"] ) ) : ?>
        <source srcset="<?php echo esc_url($fotogallery_img ["webp_1x"]); ?>" type="image/webp">
        <?php endif; ?>
        <img loading="lazy" src="<?php echo esc_url($fotogallery_img["original_1x"]); ?>" width="540" height="540" alt="" aria-hidden="true">
      </picture>
    </a>
  <?php endif; ?>
