<?php
  $page_id = $args["id"];
  $sec_name = $args["name"]["value"];
  $sec_is_last = $args["lastblock"];

  $faq_class = 'faq';
  if($sec_is_last != 1) {
    $faq_class .= ' sec-offset';
  }

  $block_title = get_field('faq_title_main');
  $block_list = get_field('faq_list_main');
?>

<section class="faq sec-offset" itemscope itemtype="https://schema.org/FAQPage">
  <div class="container">
    <?php if($block_title) : ?>
      <h2 class="sec-title" data-aos="fade-up"><?php echo $block_title; ?></h2>
    <?php endif; ?>

    <?php if(is_array($block_list)) : ?>
      <div class="faq__content accordion" data-aos="fade-up">
        <?php foreach($block_list as $index => $faq) : 
            $is_first = ($index === 0);
            $item_class = 'accordion__item';
            if ($is_first) {
              $item_class .= ' is-open';
            }
          ?>
          <div class="<?php echo $item_class; ?>" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
            <button class="accordion__control" aria-expanded="false">
              <span class="accordion__title" itemprop="name"><?php echo esc_html($faq->post_title); ?></span>
              <span class="accordion__icon">
                <svg>
                  <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#icon-chevron-down"></use>
                </svg>
              </span>
            </button>
            <div class="accordion__content" aria-hidden="true" itemprop="acceptedAnswer" itemscope="" itemtype="http://schema.org/Answer">
              <p class="accordion__text" itemprop="text"><?php echo esc_html($faq->post_content); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <p><?php esc_html_e( 'В этом разделе пока нет информации.', 'konkord' ); ?></p>
    <?php endif; ?>
  </div>
</section>