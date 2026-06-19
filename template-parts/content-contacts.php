<?php 
  $page_id = $args["page_id"];
  $sec_is_last = (int) $args["lastblock"] ?? 0;

  $sec_class = 'sec-contacts';
  if($sec_is_last != 1) {
    $sec_class .= ' sec-offset';
  }
  
  $contacts_title = get_the_title($page_id);
  $main_office_img_url = get_field('company_main_office_photo', 'option');
  $main_office_img = $main_office_img_url 
      ? get_image_versions($main_office_img_url)
      : get_placeholder_image();

  $main_office_map_link = get_field('company_main_office_map_link', 'option');
  $main_office_city = get_field('company_main_office_city', 'option');
  $main_office_address = get_field('company_main_office_address-local', 'option');
  $main_office_timework = get_field('company_main_office_timework', 'option');
  
  $company_branch_office_city = get_field('company_branch_office_city', 'option');

  $main_office_phones = get_field('department_sales_phone', 'option');
  $main_office_tel_arr = explode(PHP_EOL, $main_office_phones);
  $main_office_tel_arr_href = preg_replace('![^0-9]+!', '', $main_office_tel_arr);

  $company_branch_office_phones_branch = get_field('department_sales_phone_branch', 'option');
  $company_branch_office_tel_arr = explode(PHP_EOL, $company_branch_office_phones_branch);
  $company_branch_office_tel_arr_href = preg_replace('![^0-9]+!', '', $company_branch_office_tel_arr);

  $main_office_map = get_field('company_main_office_map_center', 'option');
  
  $company_manufacture_city = get_field('company_manufacture_city', 'option');
  $company_manufacture_address_local = get_field('company_manufacture_address-local', 'option');
  $company_manufacture_map_link = get_field('company_manufacture_map_link', 'option');
  $company_manufacture_img_url_1 = get_field('company_manufacture_photo_1', 'option');
  $company_manufacture_img_1 = $company_manufacture_img_url_1 
      ? get_image_versions($company_manufacture_img_url_1)
      : get_placeholder_image();
  $company_manufacture_img_url_2 = get_field('company_manufacture_photo_2', 'option');
  $company_manufacture_img_2 = $company_manufacture_img_url_2 
      ? get_image_versions($company_manufacture_img_url_2)
      : get_placeholder_image();
  $company_manufacture_map_link = get_field('company_manufacture_map_link', 'option');
  $company_manufacture_timework = get_field('company_manufacture_timework', 'option');
  $company_manufacture_map = get_field('company_manufacture_map_center', 'option');

  $company_requisite_ip_5 = get_field('company_requisite_ip_5', 'option');
  $company_requisite_ltd = get_field('company_requisite_ltd', 'option');

//   var_dump($company_branch_office_phone_branch);
?>
<section class="<?php echo esc_attr($sec_class); ?>">
   <div class="sec-contacts__container container">
      <h1 class="sec-contacts__title sec-title" data-aos="fade-up"><?php echo get_the_title($page_id); ?></h1>
      <div class="sec-contacts__content">
         <!-- Офис -->
         <div class="sec-contacts__address" data-aos="fade-up" data-aos-once="false" data-aos-delay="400">
            <div class="card-contact">
               <picture class="card-contact__img">
                  <source srcset="<?php echo esc_url($main_office_img["webp_1x"]); ?>" type="image/webp">
                  <img src="<?php echo esc_url($main_office_img["original_1x"]); ?>" alt="Фотография офиса" width="336" height="214">
               </picture>
               <div class="card-contact__address">
                  <span class="card-contact__title">
                     <img src="<?php echo get_template_directory_uri(); ?>/img/icon/map-pin.svg" alt="" width="24" height="24" aria-hidden="true">
                     <span>Адрес офиса</span>
                  </span>
                  <?php if($main_office_city && $main_office_address) : ?>
                  <span class="card-contact__value">г. <?php echo esc_html($main_office_city); ?>, <?php echo esc_html($main_office_address); ?></span>
                  <?php endif; ?>
               </div>
               <?php if($main_office_map_link) : ?>
               <a href="<?php echo esc_url($main_office_map_link); ?>" class="card-contact__link ui-btn" target="_blank" rel="nofollow">Проложить маршрут</a>
               <?php endif; ?>

               <?php if($main_office_timework) : ?>
               <div class="card-contact__workHours">
                  <span class="card-contact__title">
                     <img src="<?php echo get_template_directory_uri(); ?>/img/icon/clock.svg" alt="" width="24" height="24" aria-hidden="true">
                     <span>Режим работы офиса</span>
                  </span>
                  <span class="card-contact__value"><?php echo esc_html($main_office_timework); ?></span>
               </div>
               <?php endif; ?>

               <?php if($main_office_city || $company_branch_office_city) : ?>
               <div class="card-contact__phones">
                  <span class="card-contact__title">
                     <img src="<?php echo get_template_directory_uri(); ?>/img/icon/phone.svg" alt="" width="24" height="24" aria-hidden="true">
                     <span>Телефон типографии</span>
                  </span>
                  <div class="card-contact__city">
                     <span class="card-contact__value"><?php echo esc_html($main_office_city); ?></span>
                     <?php if($main_office_tel_arr && is_array($main_office_tel_arr)) : ?>
                     <ul class="card-contact__phone-list">
                        <?php foreach($main_office_tel_arr as $ind => $tel) : ?>
                        <li>
                           <a href="tel:<?php echo $main_office_tel_arr_href[$ind] ?>">
                              <svg>
                                 <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#phone"></use>
                              </svg>
                              <span><?php echo esc_html($main_office_tel_arr[$ind]); ?></span>
                           </a>
                        </li>
                        <?php endforeach; ?>                        
                     </ul>
                     <?php endif; ?>
                  </div>
                  <div class="card-contact__city">
                     <span class="card-contact__value"><?php echo esc_html($company_branch_office_city); ?></span>
                     <?php if($company_branch_office_tel_arr && is_array($company_branch_office_tel_arr)) : ?>
                     <ul class="card-contact__phone-list">
                        <?php foreach($company_branch_office_tel_arr as $ind => $tel) : ?>
                        <li>
                           <a href="tel:<?php echo esc_html($company_branch_office_tel_arr_href[$ind]); ?>">
                              <svg>
                                 <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#phone"></use>
                              </svg>
                              <span><?php echo esc_html($company_branch_office_tel_arr[$ind]); ?></span>
                           </a>
                        </li>
                        <?php endforeach; ?> 
                     </ul>
                     <?php endif; ?>
                  </div>
               </div>
               <?php endif; ?>
            </div>
            <!-- Карта -->
            <?php if($main_office_map) : ?>
            <div class="sec-contacts__map" data-center="<?php echo esc_attr($main_office_map); ?>"></div>
            <?php endif; ?>
         </div>

         <!-- Производство -->
         <div class="sec-contacts__address sec-contacts__address--production" data-aos="fade-up" data-aos-once="false">
            <div class="card-contact">
               <?php if($company_manufacture_city && $company_manufacture_address_local) : ?>
               <div class="card-contact__address">
                  <span class="card-contact__title">
                     <img src="<?php echo get_template_directory_uri(); ?>/img/icon/map-pin.svg" alt="" width="24" height="24" aria-hidden="true">
                     <span>Адрес производства</span>
                  </span>
                  <span class="card-contact__value">г. <?php echo esc_html($company_manufacture_city); ?>, <?php echo esc_html($company_manufacture_address_local); ?></span>
               </div>
               <?php endif; ?>

               <?php if($company_manufacture_map_link) : ?>
               <a href="<?php echo esc_url($company_manufacture_map_link); ?>" class="card-contact__link ui-btn" target="_blank" rel="nofollow">Проложить маршрут</a>
               <?php endif; ?>

               <?php if($company_manufacture_timework) : ?>
               <div class="card-contact__workHours">
                  <span class="card-contact__title">
                     <img src="<?php echo get_template_directory_uri(); ?>/img/icon/clock.svg" alt="" width="24" height="24" aria-hidden="true">
                     <span>Режим работы производства</span>
                  </span>
                  <span class="card-contact__value"><?php echo esc_html($company_manufacture_timework); ?></span>
               </div>
               <?php endif; ?>

               <div class="card-contact__gallery">
                  <?php if($company_manufacture_img_1 && is_array($company_manufacture_img_1)) : ?>
                  <picture class="card-contact__img">
                     <source srcset="<?php echo esc_url($company_manufacture_img_1["webp_1x"]); ?>" type="image/webp">
                     <img src="<?php echo esc_url($company_manufacture_img_1["original_1x"]); ?>" alt="Фотография офиса" width="164" height="101">
                  </picture>
                  <?php endif; ?>

                  <?php if($company_manufacture_img_2 && is_array($company_manufacture_img_2)) : ?>
                  <picture class="card-contact__img">
                     <source srcset="<?php echo esc_url($company_manufacture_img_2["webp_1x"]); ?>" type="image/webp">
                     <img src="<?php echo esc_url($company_manufacture_img_2["original_1x"]); ?>" alt="Фотография офиса" width="164" height="101">
                  </picture>
                  <?php endif; ?>
               </div>
            </div>

            <div class="card-contact card-contact--requisities" data-tabs="contacts-tabs">
               <span class="card-contact__heading">Реквизиты</span>
               <ul class="card-contact__org-type tabs__nav">
                  <li class="tabs__nav-item">
                     <button class="tabs__nav-btn tabs__nav-btn--active card-contact__btn" type="button">
                        <span>ИП (5% НДС)</span>
                     </button>
                  </li>
                  <li class="tabs__nav-item">
                     <button class="tabs__nav-btn card-contact__btn" type="button">
                        <span>ООО (22% НДС)</span>
                     </button>
                  </li>
               </ul>
               <div class="tabs__content">
                  <?php if($company_requisite_ip_5 && is_array($company_requisite_ip_5)) : ?>
                  <div class="tabs__panel tabs__panel--active">
                     <p class="card-contact__requisities"><?php echo wp_kses_post($company_requisite_ip_5["text"]); ?></p>
                     <?php if($company_requisite_ip_5["doc"]) : ?>
                     <a href="<?php echo esc_url($company_requisite_ip_5["doc"]); ?>" class="card-contact__link ui-btn" target="_blank" rel="nofollow">Скачать реквизиты ип</a>
                     <?php endif; ?>
                  </div>
                  <?php endif; ?>

                  <?php if($company_requisite_ltd && is_array($company_requisite_ltd)) : ?>
                  <div class="tabs__panel">
                     <p class="card-contact__requisities"><?php echo wp_kses_post($company_requisite_ltd["text"]); ?></p>
                     <?php if($company_requisite_ltd["doc"]) : ?>
                     <a href="<?php echo esc_url($company_requisite_ltd["doc"]); ?>" class="card-contact__link ui-btn" target="_blank" rel="nofollow">Скачать реквизиты ООО</a>
                     <?php endif; ?>
                  </div>
                  <?php endif; ?>
               </div>
               
            </div>
            <?php if($company_manufacture_map) : ?>
            <div class="sec-contacts__map" data-center="<?php echo esc_attr($company_manufacture_map); ?>"></div>
            <?php endif; ?>
         </div>
      </div>
   </div>
</section>