<div id="cookie-notice" class="cookie-notice">
  <div class="cookie-notice__container">
    <?php 
      if(!empty(get_field('cookie_text', 'option'))) : 
        $allowed_html = [
          'a' => [
            'href' => [],
            'title' => [],
            'target' => [],
            'rel' => [],
          ],
          'p' => [],
          'br' => [],
          'strong' => [],
          'em' => [],
        ];
    ?>

      <div class="cookie-notice__text"><?php echo wp_kses( get_field('cookie_text', 'option'), $allowed_html); ?></div>
    <?php endif; ?>
    <button id="cookie-accept" class="cookie-notice__btn ui-btn ui-btn--blue"><?php if(!empty(get_field('cookie_btn_text', 'option'))) : echo esc_html(get_field('cookie_btn_text', 'option')); else : echo esc_html('Согласен'); endif; ?></button>
  </div>
</div>