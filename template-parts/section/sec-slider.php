

<?php 
  $page_id = $args["id"];
  $sec_name = $args["name"];

  $title_field = $sec_name . '_title';
  $list_field  = $sec_name . '_list';

  $slider_title = get_field($title_field, $page_id);
  $slider_list  = get_field($list_field, $page_id);

?>

<?php if(is_array($slider_list)) : ?>

<section class="sec-slider sec-offset" data-id="<?php echo $sec_name; ?>">
  <div class="container">

    <div class="sec-slider__heading">

      <?php if ($slider_title) : ?>
      <h2 class="sec-slider__title sec-title"><?php echo esc_html($slider_title) ?></h2>
      <?php endif; ?>

      <div class="sec-slider__nav">
        <button class="sec-slider__btn-prev">
          <svg class="sec-slider__icon">
            <use xlink:href="<?php echo esc_url(get_template_directory_uri()); ?>/img/sprite.svg#icon-chevron-left"></use>
          </svg>
        </button>
        <button class="sec-slider__btn-next">
          <svg class="sec-slider__icon">
            <use xlink:href="<?php echo esc_url(get_template_directory_uri()); ?>/img/sprite.svg#icon-chevron-right"></use>
          </svg>
        </button>
      </div>
    </div>
    <div class="swiper sec-slider__content">
      <div class="swiper-wrapper">
  
        <?php foreach($slider_list as $index => $slide) : ?>
        <div class="swiper-slide">
          <?php
            switch ($sec_name) {
              case 'sec-fotogallery':
                get_template_part('template-parts/components/card-fotogallery', null, ['id' => $page_id, 'block' => $sec_name, 'slide' => $slide]);
                break;
              case 'sec-employees':
                get_template_part('template-parts/components/card-employee', null, ['id' => $page_id, 'block' => $sec_name, 'slide' => $slide]);
                break;
              case 'sec-news':
                get_template_part('template-parts/components/card-news', null, ['id' => $page_id, 'block' => $sec_name, 'slide' => $slide]);
                break;
            }
          ?>

        </div>
        <?php endforeach; ?>

      </div>
    </div>
  </div>
</section>

<?php endif; ?>
