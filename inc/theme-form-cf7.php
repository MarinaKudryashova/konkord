<?php
// 1.Contact Form 7 remove auto added p tags
add_filter('wpcf7_autop_or_not', '__return_false');

// 2.Contact Form 7 change tag [submit]: input replace button 
/*removing default submit tag*/
remove_action('wpcf7_init', 'wpcf7_add_form_tag_submit');

/*adding action with function which handles our button markup*/
add_action('wpcf7_init', 'theme_child_cf7_button');

/*adding out submit button tag*/
if (!function_exists('theme_child_cf7_button')) {
  function theme_child_cf7_button() {
  wpcf7_add_form_tag('submit', 'theme_child_cf7_button_handler');
  }
}

/*out button markup inside handler*/
if (!function_exists('theme_child_cf7_button_handler')) {
  function theme_child_cf7_button_handler($tag) {
  $tag = new WPCF7_FormTag($tag);
  $class = wpcf7_form_controls_class($tag->type);
  $atts = array();
  $atts['class'] = $tag->get_class_option($class);
  $atts['class'] .= '';
  $atts['id'] = $tag->get_id_option();
  $atts['tabindex'] = $tag->get_option('tabindex', 'int', true);
  $value = isset($tag->values[0]) ? $tag->values[0] : '';
  if (empty($value)) {
  $value = esc_html__('Send', 'infacade');
  }
  $atts['type'] = 'submit';
  $atts = wpcf7_format_atts($atts);
  $html = sprintf('<button %1$s><span class="ui-btn-arrow__text">%2$s</span><span class="ui-btn-arrow__arrow"><span class="ui-arrow"><svg class="ui-arrow__svg" aria-hidden><use xlink:href="%3$s/img/sprite.svg#icon-arrow-diagonal"></use></svg><svg class="ui-arrow__svg ui-arrow__svg--copy" aria-hidden><use xlink:href="%3$s/img/sprite.svg#icon-arrow-diagonal"></use></svg></span></span></button>', $atts, $value, get_template_directory_uri());
  
  return $html;
  }
  }


