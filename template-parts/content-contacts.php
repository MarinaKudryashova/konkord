<section class="sec-contacts">
   <div class="sec-contacts__container container">
      <h1 class="sec-contacts__title sec-title"><?php echo get_the_title($page_id); ?></h1>
      <div class="sec-contacts__content">
         <!-- Офис -->
         <div class="sec-contacts__address">
            <div class="card-contact">
               <picture class="card-contact__img">
                  <img src="<?php echo get_template_directory_uri(); ?>/img/contacts/office.jpg" alt="Фотография офиса" width="336" height="214">
               </picture>
               <div class="card-contact__address">
                  <span class="card-contact__title">
                     <img src="<?php echo get_template_directory_uri(); ?>/img/icon/map-pin.svg" alt="" width="24" height="24" aria-hidden="true">
                     <span>Адрес офиса</span>
                  </span>
                  <span class="card-contact__value">г. Дзержинск, пр-т Дзержинского 14а</span>
               </div>
               <a href="#" class="card-contact__link ui-btn">Проложить маршрут</a>
               <div class="card-contact__workHours">
                  <span class="card-contact__title">
                     <img src="<?php echo get_template_directory_uri(); ?>/img/icon/clock.svg" alt="" width="24" height="24" aria-hidden="true">
                     <span>Режим работы офиса</span>
                  </span>
                  <span class="card-contact__value">Будни 09:00–18:00</span>
               </div>
               <div class="card-contact__phones">
                  <span class="card-contact__title">
                     <img src="<?php echo get_template_directory_uri(); ?>/img/icon/phone.svg" alt="" width="24" height="24" aria-hidden="true">
                     <span>Телефон типографии</span>
                  </span>
                  <div class="card-contact__city">
                     <span class="card-contact__value">Дзержинск</span>
                     <ul class="card-contact__phone-list">
                        <li>
                           <a href="tel:+78313230406">
                              <svg>
                                 <use xlink:href="https://konkord.local/wp-content/themes/konkord/img/sprite.svg#phone"></use>
                              </svg>
                              <span>+7 (8313) 23-04-06</span>
                           </a>
                        </li>
                        <li>
                           <a href="tel:+78313230121" class="card-contact__phone">
                              <svg>
                                 <use xlink:href="https://konkord.local/wp-content/themes/konkord/img/sprite.svg#phone"></use>
                              </svg>
                              <span>+7 (8313) 23-01-21</span>
                           </a>
                        </li>
                        <li>
                           <a href="tel:+78313232005" class="card-contact__phone">
                              <svg>
                                 <use xlink:href="https://konkord.local/wp-content/themes/konkord/img/sprite.svg#phone"></use>
                              </svg>
                              <span>+7 (8313) 23-20-05</span>
                           </a>
                        </li>
                        <li>
                           <a href="tel:+79601651717" class="card-contact__phone">
                              <svg>
                                 <use xlink:href="https://konkord.local/wp-content/themes/konkord/img/sprite.svg#phone"></use>
                              </svg>
                              <span>+7 (960) 165-17-17</span>
                           </a>
                        </li>
                     </ul>
                  </div>
                  <div class="card-contact__city">
                     <span class="card-contact__value">Н.Новгород</span>
                     <ul class="card-contact__phone-list">
                        <li>
                           <a href="tel:+78312626717">
                              <svg>
                                 <use xlink:href="https://konkord.local/wp-content/themes/konkord/img/sprite.svg#phone"></use>
                              </svg>
                              <span>+7 (831) 26-26-717</span>
                           </a>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
            <!-- Карта -->
            <div class="sec-contacts__map"></div>
         </div>

         <!-- Производство -->
         <div class="sec-contacts__address sec-contacts__address--production">
            <div class="card-contact">
               <div class="card-contact__address">
                  <span class="card-contact__title">
                     <img src="<?php echo get_template_directory_uri(); ?>/img/icon/map-pin.svg" alt="" width="24" height="24" aria-hidden="true">
                     <span>Адрес производства</span>
                  </span>
                  <span class="card-contact__value">г. Дзержинск, пр-т Чкалова, 61</span>
               </div>
               <a href="#" class="card-contact__link ui-btn">Проложить маршрут</a>
               <div class="card-contact__workHours">
                  <span class="card-contact__title">
                     <img src="<?php echo get_template_directory_uri(); ?>/img/icon/clock.svg" alt="" width="24" height="24" aria-hidden="true">
                     <span>Режим работы производства</span>
                  </span>
                  <span class="card-contact__value">Будни 09:00–18:00</span>
               </div>
               <div class="card-contact__gallery">
                  <picture class="card-contact__img">
                     <img src="<?php echo get_template_directory_uri(); ?>/img/contacts/production-1.jpg" alt="Фотография офиса" width="164" height="101">
                  </picture>
                  <picture class="card-contact__img">
                     <img src="<?php echo get_template_directory_uri(); ?>/img/contacts/production-2.jpg" alt="Фотография офиса" width="164" height="101">
                  </picture>
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
                  <div class="tabs__panel tabs__panel--active">
                     <p class="card-contact__requisities">
                        ИП Афонина Татьяна Вячеславовна<br>
                        ИНН 524914846456<br>
                        ОГРН 310524911300079<br>
                        КПП 524901001<br>
                        ОКПО 0169745236<br>
                        Расчетный счет 40802810223750000183<br>
                        Приволжский филиал ОАО АКБ "РОСБАНК" г. Н.Новгород<br>
                        БИК 042202747<br>
                        Кор.счет 30101810400000000747<br>
                        Юридический адрес 603052, Нижегородская обл.,
                        г. Нижний Новгород, Ш. Сормовское, 17-1
                     </p>
                     <a href="#" class="card-contact__link ui-btn">Скачать реквизиты ип</a>
                  </div>
                  <div class="tabs__panel">
                     <p class="card-contact__requisities">
                        ИП Афонина Татьяна Вячеславовна<br>
                        ИНН 524914846456<br>
                        ОГРН 310524911300079<br>
                        КПП 524901001<br>
                        ОКПО 0169745236<br>
                        Расчетный счет 40802810223750000183<br>
                        Приволжский филиал ОАО АКБ "РОСБАНК" г. Н.Новгород<br>
                        БИК 042202747<br>
                        Кор.счет 30101810400000000747<br>
                        Юридический адрес 603052, Нижегородская обл.,
                        г. Нижний Новгород, Ш. Сормовское, 17-1
                     </p>
                     <a href="#" class="card-contact__link ui-btn">Скачать реквизиты ООО</a>
                  </div>
               </div>
               
            </div>
            <div class="sec-contacts__map"></div>
         </div>
      </div>
   </div>
</section>