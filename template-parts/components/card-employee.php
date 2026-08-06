<?php
  $page_id = $args["page_id"]  ?? 0;
  $employee_id = $args["slide"] ?? null;

  $employee_name = get_the_title($employee_id);

  $employee_thumbnail_url = get_the_post_thumbnail_url($employee_id);
  $employee_img = $employee_thumbnail_url 
      ? get_image_versions($employee_thumbnail_url)
      : get_placeholder_image();

  $employee_thumbnail_url_mobile = get_field('employee_img_mobile', $employee_id);
  $employee_img_mobile = $employee_thumbnail_url_mobile 
      ? get_image_versions($employee_thumbnail_url_mobile)
      : $employee_img;

  $employee_position = get_field('employee_position', $employee_id);
  $employee_email = get_field('employee_email', $employee_id);

  $employee_phone = get_field('employee_phone', $employee_id);
  $employee_phone = explode(PHP_EOL, $employee_phone);
  $employee_phone_href = preg_replace('![^0-9]+!', '', $employee_phone);

  $messengers = [
    'max' => [
      'icon' => 'max.svg',
      'label' => 'Свяжитесь с нами в Максе',
      'class' => 'messanges__link__accent',
      'base_url' => 'https://max.ru/'
    ],
    'telegram' => [
      'icon' => 'telegram.svg',
      'label' => 'Свяжитесь с нами в Telegram',
      'class' => '',
      'base_url' => 'https://t.me/'
    ]
  ];

  $employee_messanges = get_field('employee_messanges', $employee_id);
  if (!empty($employee_messanges) && is_array($employee_messanges)) {
    foreach ($employee_messanges as $key => $value) {
      $value = trim($value);
      
      if (isset($messengers[$key])) {
        $base_url = $messengers[$key]['base_url'];
        if (strpos($value, $base_url) === 0) {
          $clean_value = substr($value, strlen($base_url));
          $clean_value = trim($clean_value, '/');
          
          if (!empty($clean_value)) {
            $employee_messanges[$key] = $value;
          } else {
            unset($employee_messanges[$key]);
          }
        } else {
          if (!empty($value)) {
            $employee_messanges[$key] = $value;
          } else {
            unset($employee_messanges[$key]);
          }
        }
      }
    }
  }

?>

<div class="card-employee">
  <div class="card-employee__view">
    <picture class="card-employee__img">
      <?php if (!empty( $employee_img_mobile['webp_1x'])): ?>
        <source media="(max-width: 576px)" srcset="<?php echo esc_url( $employee_img_mobile['webp_1x']); ?>" type="image/webp">
      <?php endif; ?>
      <?php if (!empty( $employee_img_mobile['original_1x'])): ?>
        <source media="(max-width: 576px)" srcset="<?php echo esc_url( $employee_img_mobile['original_1x']); ?>" type="image/jpg">
      <?php endif; ?>
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

    <?php if(!empty($employee_phone) && is_array($employee_phone)) : ?>
    <?php foreach($employee_phone as $ind => $phone) : ?>
    <a href="tel:<?php echo $employee_phone_href[$ind]; ?>" class="card-employee__phone">
        <svg width="14" height="14">
          <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#phone"></use>
        </svg>
        <span><?php echo $employee_phone[$ind]; ?></span>
    </a>
    <?php endforeach; ?>

    <?php endif; ?>

    <?php if($employee_position) : ?>
    <p class="card-employee__position"><?php echo esc_html($employee_position); ?></p>
    <?php endif; ?>
  </div>

  <?php if(!empty($employee_messanges) && is_array($employee_messanges)) : ?>
  <ul class="card-employee__messanges messanges">
    <?php foreach($messengers as $key => $messenger) : ?>
      <?php if(!empty($employee_messanges[$key])) : ?>
      <li class="messanges__item">
          <a href="<?php echo esc_url($employee_messanges[$key]); ?>" class="messanges__link <?php echo esc_attr($messenger['class']); ?>" aria-label="<?php echo esc_attr($messenger['label']); ?>" target="_blank">
            <img src="<?php echo get_template_directory_uri();?>/img/icon/<?php echo esc_attr($messenger['icon']); ?>" alt="<?php echo esc_attr($messenger['label']); ?>" width="22" height="22">
          </a>
      </li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</div>