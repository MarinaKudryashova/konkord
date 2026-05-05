<?php
  $page_id = $args["id"]  ?? 0;
  $employee_id = $args["slide"] ?? null;

  $employee_name = get_the_title($employee_id);
  $employee_thumbnail_url = get_the_post_thumbnail_url($employee_id);
  $employee_img = $employee_thumbnail_url 
      ? get_image_versions($employee_thumbnail_url)
      : get_placeholder_image();
  $employee_position = get_field('employee_position', $employee_id);
  $employee_email = get_field('employee_email', $employee_id);

  $employee_phone = get_field('employee_phone', $employee_id);
  $employee_phone = explode(PHP_EOL, $employee_phone);
  $employee_phone_href = preg_replace('![^0-9]+!', '', $employee_phone);
?>

<div class="card-employee">
  <div class="card-employee__view">
    <picture class="card-employee__img">
      <source srcset="<?php echo esc_url($employee_img["webp_1x"]); ?>" type="image/webp">
      <img loading="lazy" src="<?php echo esc_url($employee_img["original_1x"]); ?>" alt="Фото сотрудника" width="272" height="360" aria-hidden="true">
    </picture>
  </div>
  <div class="card-employee__info">
    <?php if($employee_name) : ?>
      <h3 class="card-employee__title"><?php echo esc_html($employee_name); ?></h3>
    <?php endif; ?>

    
    <?php if($employee_email) : ?>
    <a href="mailto:<?php echo esc_attr($employee_email); ?>" class="card-employee__email"><?php echo esc_html($employee_email); ?></a>
    <?php endif; ?>

    <?php if(!empty($employee_phone[0]) && is_array($employee_phone)) : ?>
    <a href="tel:<?php echo $employee_phone_href[0]; ?>" class="card-employee__phone">
        <svg width="14" height="14">
          <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#phone"></use>
        </svg>
        <span><?php echo $employee_phone[0]; ?></span>
    </a>
    <?php endif; ?>

    <?php if($employee_position) : ?>
    <p class="card-employee__position"><?php echo esc_html($employee_position); ?></p>
    <?php endif; ?>
  </div>
  <ul class="card-employee__messanges messanges">
    <li class="messanges__item">
        <a href="#" class="messanges__link messanges__link__accent" aria-label="Свяжитесь с нами в Максе" target="_blank">
          <img src="<?php echo get_template_directory_uri();?>/img/icon/max.svg" alt="Иконка Макс" width="22" height="22">
        </a>
    </li>
    <li>
        <a href="#" class="messanges__link" aria-label="Свяжитесь с нами в Telegram" target="_blank">
          <img src="<?php echo get_template_directory_uri();?>/img/icon/telegram.svg" alt="Иконка Телеграм" width="22" height="22">
        </a>
    </li>
  </ul>
</div>