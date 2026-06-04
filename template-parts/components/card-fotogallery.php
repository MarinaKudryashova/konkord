<?php 
  $page_id = $args["page_id"]  ?? 0;
  $fotogallery_url = $args["slide"] ?? null;

  if (!$fotogallery_url) {
      return;
  }
  
  $fotogallery_img = $fotogallery_url ? get_image_versions($fotogallery_url) : null;
   if($fotogallery_img) : 
  ?>
    <a data-fslightbox="fotogallery-<?php echo $page_id ?>" data-caption="" href="<?php echo $fotogallery_url ?>" class="card-fotogallery__link">
      <picture class="card-fotogallery__img">
        <source srcset="<?php echo esc_url($fotogallery_img ["webp_1x"]); ?>" type="image/webp">
        <img loading="lazy" src="<?php echo esc_url($fotogallery_img["original_1x"]); ?>" width="540" height="540" alt="" aria-hidden="true">
      </picture>
    </a>
  <?php endif; ?>