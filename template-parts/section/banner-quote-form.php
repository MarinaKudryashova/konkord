<?php 
  $page_id = $args["id"];
  $sec_name = $args["name"]["value"];

  // Получаем поля из админки
  $title = get_field($sec_name . '_title', $page_id);
  $image = get_field($sec_name . '_image', $page_id);
  $form_shortcode = get_field($sec_name . '_form', $page_id);

  // Значения по умолчанию (если поля не заполнены)
$title = !empty($title) ? $title : 'Рассчитаем стоимость вашего автомобиля';
$form_shortcode = !empty($form_shortcode) ? $form_shortcode : '[contact-form-7 id="7d07704" title="Форма Запрос расчета"]';
?>


<section class="banner-quote-form sec-offset">
  <div class="container">
    <div class="banner-quote-form__wrap">
      <!-- <div class="banner-quote-form__block"> -->
      <!-- Левая часть с формой -->
      <div class="banner-quote-form__content">
        <!-- Обёртка для заголовка с фоном на всю ширину -->
        <div class="banner-quote-form__title-wrapper">
          <h2 class="banner-quote-form__title sec-title" data-aos="fade-up">
           <?php echo esc_html($title); ?>
          </h2>
        </div>

       <!-- Выводим форму через шорткод CF7 -->
        <?php echo do_shortcode($form_shortcode); ?>
      </div>

      <!-- Правая часть с картинкой -->
     <div class="banner-quote-form__image">
        <?php if(!empty($image)) : ?>
          <picture class="banner-quote-form__picture">
            <?php if(!empty($image['webp_1x'])) : ?>
              <source srcset="<?php echo esc_url($image['webp_1x']); ?>" type="image/webp">
            <?php endif; ?>
                <img 
                  class="banner-quote-form__img"
                  src="<?php echo esc_url($image['url']); ?>"
                  alt="<?php echo esc_attr($image['alt']); ?>"
                  width="412"
                  height="405"
                  loading="lazy"
                />
          </picture>
        <?php endif; ?>
    </div>
  
    </div>
  </div>
</section>