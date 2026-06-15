<?php 
  $page_id = $args["page_id"];
  $sec_is_last = (int)$args["lastblock"] ?? 0;

  $sec_class = 'layout';
  if($sec_is_last != 1) {
    $sec_class .= ' sec-offset';
  }
  
  $layout_title = get_the_title($page_id);
  $layout_requirements_title = get_field('layout_requirements_title', $page_id);
  $layout_requirements_list = get_field('layout_requirements_list', $page_id);
  $layout_catalog = get_field('layout_catalog', $page_id);
?>

<section class="<?php echo esc_attr($sec_class); ?>">
   <div class="layout__container container">
      <h1 class="layout__title sec-title"><?php echo $layout_title; ?></h1>

      <?php /* == Общие требования == */ ?>
      <?php if (!empty($layout_requirements_list)) : ?>
      <div class="layout__requirements">
        <?php if (!empty($layout_requirements_title)) : ?>
        <h3 class="layout__subtitle"><?php echo $layout_requirements_title; ?></h3>
        <?php endif; ?>

        <?php if(is_array($layout_requirements_list)) : ?>
        <ul class="layout__list">
          <?php foreach($layout_requirements_list as $ids => $item) : ?>
          <li class="layout__item">
              <p class="layout__text"><?php echo wp_kses_post($item["text"]); ?></p>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>

      </div>
      <?php endif; ?>

      <?php /* == Требования по услугам == */ ?>
      <?php if(!empty($layout_catalog) && is_array($layout_catalog)) : ?>
      <?php foreach($layout_catalog as $ids => $block) : 
        $block_title = $block["title"];
        $block_type = $block["type"];
        $block_items = $block["item"];
        ?>
      <div class="layout__services layout-catalog">
         <div class="layout-catalog__heading">
            <?php if (!empty($block_title)) : ?>
            <h3 class="layout-catalog__title layout__subtitle"><?php echo esc_html($block_title); ?></h3>
            <?php endif; ?>

            <?php if (!empty($block_type)) : ?>
            <span class="layout-catalog__type"><?php echo esc_html($block_type); ?></span>
            <?php endif; ?>
         </div>

         <?php if(!empty($block_items) && is_array($block_items)) : ?>
         <ul class="layout-catalog__list">
            <?php foreach($block_items as $ids => $items) : 
              $items_name = $items['name'];
              $items_file = !empty($items['file']) ? $items['file'] : '#';
              ?>
            <li class="layout-catalog__item">
               <span class="layout-catalog__count"></span>
               <span class="layout-catalog__file">
                  <img loading="lazy" src="<?php echo get_template_directory_uri();?>/img/icon/pdf-file.svg" width="24" height="24" alt="" aria-hidden="true">
                  <span><?php echo esc_html($items_name);?></span>
               </span>
               <a href="<?php echo esc_url($items_file);?>" class="layout-catalog__link" target="_blank" rel="noopener noreferrer">
                  <svg>
                    <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#download"></use>
                  </svg>
                  <span>Скачать</span>
               </a>
            </li>
            <?php endforeach; ?>
         </ul>
         <?php endif; ?>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
   </div>
</section>