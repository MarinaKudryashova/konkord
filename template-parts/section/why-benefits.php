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
  <div class="container">
    <?php if($sec_why_title) : ?>
      <h2 class="sec-title" data-aos="fade-up"><?php echo $sec_why_title; ?></h2>
    <?php endif; ?>

    <?php if(!empty($sec_why_list) && is_array($sec_why_list)) : ?>
    <ul class="why-benefits__list">
      <?php foreach($sec_why_list as $benefit) : 
        $benefit_title = $benefit["title"];
        $benefit_text = $benefit["text"];
        ?>
        <li class="why-benefits__item">
          <div class="card-benefits">
            <span class="card-benefits__name"><?php echo esc_html($benefit_title); ?></span>
            <span class="card-benefits__text"><?php echo esc_html($benefit_text); ?></span>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    
    <?php /*-- Кнопка с формой --*/ ?>
    <button type="button" class="promo__callback ui-btn" data-graph-path="modal-leadform">Связаться с менеджером</button>
  </div>
</section>