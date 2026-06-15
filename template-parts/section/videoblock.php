<?php 
  /* 
  * Section: Блок с видео (парк оборудования)
  */
  
  $page_id = $args["page_id"];
  $sec_name = $args["name"]["value"];
  $sec_is_last = (int) $args["lastblock"] ?? 0;

  $sec_class = 'videoblock';
  if($sec_is_last != 1) {
    $sec_class .= ' sec-offset';
  }

  $field_title = $sec_name . "_title";
  $field_descr = $sec_name . "_descr";
  $field_video_url = $sec_name . "_video_url";
  $field_preview = $sec_name . "_preview";

  $videoblock_title = get_field($field_title, $page_id);
  $videoblock_descr = get_field($field_descr, $page_id);
  $videoblock_video_url = get_field($field_video_url, $page_id);
  $videoblock_preview = get_field($field_preview, $page_id);

?>

<?php $post_id = $args ?>
<div class="<?php echo esc_attr($sec_class); ?>">
  <div class="container">
    <?php if($videoblock_title) :?>
    <h2 class="videoblock__title sec-title" data-aos="fade-up"><?php echo esc_html($videoblock_title); ?></h2>
      <?php if(!empty($videoblock_descr)) : ?>
      <div class="videoblock__descr"><?php echo wp_kses_post($videoblock_descr); ?></div>
      <?php endif; ?>
    <?php endif; ?>
    <?php if($videoblock_video_url || $videoblock_preview) :?>

    <div class="videoblock__content" data-aos="fade-up">
      <button class="btn-reset videoblock__play" aria-label="Play video">
        <svg>
          <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg?v=2.0#play"></use>
        </svg>
      </button>
      <video src="<?php echo esc_url($videoblock_video_url); ?>" class="videoblock__video" <?php if($videoblock_preview) :?>poster="<?php echo esc_url( $videoblock_preview); ?>"<?php endif; ?>  preload="metadata"></video>
    </div>
      <?php endif; ?> 

  </div>
</div>