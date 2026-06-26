<?php 
/* 
* Components: Модальное окно
*/

$leadform_title = get_field('form_title', 'options');
$leadform_shortcode = get_field('form_shortcode', 'option');

$modalform_title = get_field('modalform_title', 'options');
$modalform_shortcode = get_field('modalform_shortcode', 'option');

$modalsend_title = get_field('modalsend_title', 'options');
$modalsend_descr = get_field('modalsend_descr', 'options');
$modalsend_messanges = get_field('modalsend_messanges', 'option');
$messanges = get_field('messengers_list', 'options');
$modalsend_logo = get_field('modalsend_logo', 'options');

$modalsend_img_url = get_field('modalsend_img', 'option');
$modalsend_img = $modalsend_img_url 
  ? get_image_versions($modalsend_img_url)
  : get_placeholder_image();

$modalsend_img_url_mobile = get_field('modalsend_img_mobile', 'option');
$modalsend_img_mobile = $modalsend_img_url_mobile 
  ? get_image_versions($modalsend_img_url_mobile)
  : $modalsend_img;
?>

<div class="graph-modal">
  <div class="graph-modal__container" role="dialog" aria-modal="true" data-graph-target="modal-leadform">
    <div class="graph-modal__content form">
      <div class="graph-modal__header">
        <?php if(!empty($leadform_title)) : ?>
          <h2 class="form__title"><?php echo esc_html($leadform_title); ?></h2>
          <?php endif; ?>
          <button class="btn-reset js-modal-close graph-modal__close" aria-label="Закрыть модальное окно">
            <svg>
              <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#close"></use>
            </svg>
          </button>
      </div>

      <?php if(!empty($leadform_shortcode)) : ?>
       <?php echo do_shortcode($leadform_shortcode); ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="graph-modal__container" role="dialog" aria-modal="true" data-graph-target="modalform">
    <div class="graph-modal__content form">
      <div class="graph-modal__header">
        <?php if(!empty($modalform_title)) : ?>
          <h2 class="form__title"><?php echo esc_html($modalform_title); ?></h2>
          <?php endif; ?>
          <button class="btn-reset js-modal-close graph-modal__close" aria-label="Закрыть модальное окно">
            <svg>
              <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#close"></use>
            </svg>
          </button>
      </div>

      <?php if(!empty($modalform_shortcode)) : ?>
       <?php echo do_shortcode($modalform_shortcode); ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="graph-modal__container" role="dialog" aria-modal="true" data-graph-target="modal-send">
    <button class="btn-reset js-modal-close graph-modal__close" aria-label="Закрыть модальное окно">
      <svg>
        <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg?v=2#close"></use>
      </svg>
    </button>
    <div class="bannerform__bg">
    <picture class="bannerform__picture">
      <?php if (!empty($modalsend_img_mobile['webp_1x'])) : ?>
        <source media="(max-width: 576px)" srcset="<?php echo esc_url($modalsend_img_mobile['webp_1x']); ?>" type="image/jpg">
        <?php endif; ?>
        <?php if (!empty($modalsend_img_mobile['original_1x'])) : ?>
        <source media="(max-width: 576px)" srcset="<?php echo esc_url($modalsend_img_mobile['original_1x']); ?>" type="image/jpg">
        <?php endif; ?>

        <?php if (!empty($modalsend_img['webp_1x'])) : ?>
        <source srcset="<?php echo esc_url($modalsend_img['webp_1x']); ?>" type="image/webp">
        <?php endif; ?>
        <img class="bannerform__img" src="<?php echo esc_url($modalsend_img['original_1x']); ?>" width="1443" height="534" alt="Фото" aria-hidden="true" loading="lazy">
      </picture>
    </div>
    <div class="graph-modal__content message-success">

      <div class="message-success__content">
        <?php if(!empty($modalsend_title)) : ?>
         <h2 class="message-success__title"><?php echo esc_html($modalsend_title); ?></h2>
        <?php endif; ?>
        <?php if(!empty($modalsend_descr)) : ?>
         <p class="message-success__text"><?php echo esc_html($modalsend_descr); ?></p>
        <?php endif; ?>
        <!-- Мессенджеры тут-->
        <?php if($modalsend_messanges && $messanges && is_array($messanges)) : ?>
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
      <?php if($modalsend_logo) : ?>
        <a href="<?php bloginfo('url'); ?>" class="header__logo logo">
          <img class="logo__img" src="<?php echo get_field('site_logo', 'option') ?>" alt="Logo <?php bloginfo('name'); ?>" width="214" height="40">
        </a>
      <?php endif; ?>

      <!-- <h3 class="message-success__title">Спасибо</h3> -->
      <!-- <p class="message-success__text">Ваши данные успешно отправлены.</p> -->
    </div>
  </div>

  <div class="graph-modal__container" role="dialog" aria-modal="true" data-graph-target="modal-b24">
    <div class="graph-modal__content form">
        <div class="graph-modal__header">
            <h2 class="form__title">Заказать печать</h2>
            <button class="btn-reset js-modal-close graph-modal__close" aria-label="Закрыть модальное окно">
                <svg>
                    <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#close"></use>
                </svg>
            </button>
        </div>
        <div id="b24-form-container"></div>
    </div>
  </div>
</div>