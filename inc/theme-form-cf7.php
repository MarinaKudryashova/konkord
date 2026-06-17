<?php

// 1. Contact Form 7 remove auto added p tags
add_filter('wpcf7_autop_or_not', '__return_false');

// 2. Contact Form 7 change tag [submit]: input replace button 
remove_action('wpcf7_init', 'wpcf7_add_form_tag_submit');
add_action('wpcf7_init', 'theme_child_cf7_button');

if (!function_exists('theme_child_cf7_button')) {
    function theme_child_cf7_button() {
        wpcf7_add_form_tag('submit', 'theme_child_cf7_button_handler');
    }
}

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
        $html = sprintf(
            '<button %1$s>%2$s</button>',
            $atts,
            $value,
            get_template_directory_uri()
        );
        
        return $html;
    }
}

// 3. Custom tag [img] для иконок
/**
 * Handler for [img] tag
 * Usage: [img icon/check.svg 24 24]
 * 
 * Обработчик тега [img]
 * Пример: [img icon/check.svg 24 24]
 */
function custom_add_form_tag_img_handler( $tag ) {
    $options = $tag['options'];
    
    $image_path = isset( $options[0] ) ? $options[0] : '';
    
    if ( empty( $image_path ) ) {
        return '';
    }
    
    $width = isset( $options[1] ) && is_numeric( $options[1] ) ? intval( $options[1] ) : 16;
    $height = isset( $options[2] ) && is_numeric( $options[2] ) ? intval( $options[2] ) : 16;
    
    $src = esc_url( get_stylesheet_directory_uri() . '/img/' . $image_path );
    
    return sprintf(
        '<img loading="lazy" src="%s" width="%d" height="%d" alt="" aria-hidden="true">',
        $src,
        $width,
        $height
    );
}

/**
 * Register custom tag [img]
 * 
 * Регистрируем кастомный тег [img]
 */
function custom_add_form_tag_img() {
    if ( function_exists( 'wpcf7_add_shortcode' ) ) {
        wpcf7_add_shortcode( 'img', 'custom_add_form_tag_img_handler' );
    }
    
    if ( function_exists( 'wpcf7_add_form_tag' ) ) {
        wpcf7_add_form_tag( 'img', 'custom_add_form_tag_img_handler' );
    }
}
add_action( 'wpcf7_init', 'custom_add_form_tag_img' );