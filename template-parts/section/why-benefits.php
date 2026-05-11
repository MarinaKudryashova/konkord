<?php
  $page_id = $args["id"];
  $sec_name = $args["name"]["value"];
  $sec_is_last = $args["lastblock"];
  
  $field_title = $sec_name . "_title";
  $field_list = $sec_name;
  
  $sec_why_title = get_field($field_title, $page_id);
  $sec_why_list = get_field($field_list, $page_id);

?>
<section class="why-benefits <?php if($sec_is_last != 1) : ?>sec-offset"<?php endif; ?>>
  <!-- Самолетик -->
  <div class="why-benefits__decor-img" aria-hidden="true">
    <img class="why-benefits__plane" src="<?php echo get_template_directory_uri();?>/img/svg/plane.svg" alt="" width="198" height="103">
    <img class="why-benefits__plane-trail" src="<?php echo get_template_directory_uri();?>/img/svg/plane-trail.svg" alt="" width="341" height="296">
  </div>

  <div class="why-benefits__container container">
    <?php if(!empty($sec_why_list) && is_array($sec_why_list)) : ?>
    <ul class="why-benefits__list">
      <?php foreach($sec_why_list as $ids => $benefit) : 
        $benefit_title = $benefit["title"];
        $benefit_text = $benefit["text"];

        $total_items = count($sec_why_list);
        $item_type = match(true) {
            $ids == 0 => 'first',
            $ids == $total_items - 1 => 'last',
            default => 'middle'
        };
        ?>
        <li class="why-benefits__item">
          <?php if($item_type === 'first' && $sec_why_title) : ?>
            <h2 class="sec-title" data-aos="fade-up"><?php echo $sec_why_title; ?></h2>
          <?php endif; ?>
          <div class="card-benefits">
            <span class="card-benefits__name"><?php echo wp_kses_post($benefit_title); ?></span>
            <span class="card-benefits__text"><?php echo wp_kses_post($benefit_text); ?></span>
          </div>
          <?php if($item_type === 'last') : ?>
            <?php /*-- Кнопка с формой --*/ ?>
            <a href="#sec-employees" class="why-benefits__link ui-btn">Связаться с менеджером</a>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</section>