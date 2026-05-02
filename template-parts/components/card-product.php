<?php 
$car_id = $args['car'];

$car = wc_get_product($car_id);
if (!$car) {
    return;
}

// Заголовок
$car_title = $car->get_name();

// Год выпуска
$car_year = $car->get_attribute( 'pa_car-year' );

// Страна
$car_category_ids = $car->get_category_ids();
$car_country_id = !empty($car_category_ids) ? $car_category_ids[0] : 0;
$car_country = get_term( $car_country_id, 'product_cat' );
$car_country_name = $car_country -> name;

// Цены
$car_price = $car->get_price_html();

// Изображение
$car_thumbnail_id = $car->get_image_id();
$car_thumbnail = get_image_versions($car_thumbnail_id, 'full');
$car_thumbnail_alt = !empty($car_thumbnail["alt"]) ? $car_thumbnail["alt"] : sprintf(__('Изображение товара %s', 'primasnab'), $car_title);

// Миниатюра товара с кастомным плейсхолдером(по умолчанию от Woocomerce)
$car_fallback_img_url = wc_placeholder_img_src('large');// URL заглушки по умолчанию 600x600
$car_fallback_img_id = attachment_url_to_postid($car_fallback_img_url);

?>


<article class="ui-card product-card" itemscope itemtype="https://schema.org/Product">
  <?php /*-- Изображение --*/ ?>
  <?php if(has_post_thumbnail($car_id)) : ?>
  <picture class="product-card__view">
    <?php if(isset($car_thumbnail['webp_1x']) && !empty($car_thumbnail['webp_1x'])) : ?>
    <source srcset="<?php echo esc_url($car_thumbnail["webp_1x"]); ?>" type="image/webp">
    <?php endif; ?>
    <img class="product-card__img" src="<?php echo esc_url($car_thumbnail["original_1x"]); ?>"
      itemprop="image" alt="<?php echo esc_attr($car_thumbnail_alt); ?>" width="400" height="350">
  </picture>
  <?php else : ?>
  <div class="product-card__view">
    <img class="product-card__img" src="<?php echo esc_url($car_fallback_img_url); ?>" itemprop="image"
      alt="<?php echo esc_attr('Изображения товара нет', 'primasnab') ?>" width="400" height="350">
  </div>
  <?php endif; ?>

  <?php /*-- Информация --*/ ?>
  <div class="product-card__info">
    <?php /*-- Название с годом выпуска --*/ ?>
    <h3 class="product-card__title" itemprop="name">
      <?php if($car_year) : ?>
      <?php echo esc_html( $car_title ) . ', '; ?><span><?php echo esc_html( $car_year ); ?></span>
      <?php else :?>
        <?php echo esc_html( $car_title ); ?>
      <?php endif; ?>
    </h3>

    <?php /*-- Страна --*/ ?>
    <?php if (!empty($car_country_name)) : ?>
    <span class="product-card__category"><?php echo esc_html( $car_country_name ); ?></span>
    <?php endif; ?>

    <?php /*-- Цена --*/ ?>
    <?php if (!empty($car_price)) : ?>
    <div class="product-card__prices"> <?php echo $car_price; ?></div>
    <?php endif; ?>
  </div>


  <?php /*-- Кнопка с формой --*/ ?>
  <button type="button" class="product-card__btn ui-btn ui-btn--pink" data-graph-path="modal-leadform">Оставить заявку</button>

</article>