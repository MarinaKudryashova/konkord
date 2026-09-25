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
   wpcf7_add_form_tag( 'img', 'custom_add_form_tag_img_handler' );
}
add_action( 'wpcf7_init', 'custom_add_form_tag_img' );

/**
 * Скрытое поле-ловушка для ботов (honeypot).
 * В шаблоны CF7 ничего добавлять не нужно — поле вставляется само.
 */
add_filter( 'wpcf7_form_elements', 'konkord_cf7_honeypot_field' );
function konkord_cf7_honeypot_field( $html ) {
	static $i = 0;
	$i++;
	$id = 'company-website-' . $i;

	$hp = sprintf(
		'<div class="form-field__hp" aria-hidden="true">'
		. '<label for="%1$s">Сайт компании</label>'
		. '<input type="text" name="company_website" id="%1$s" value="" tabindex="-1" autocomplete="off">'
		. '</div>',
		esc_attr( $id )
	);

	return $hp . $html;
}

function konkord_cf7_honeypot_filled() {
	if ( ! isset( $_POST['company_website'] ) ) {
		return false;
	}

	$value = trim( (string) wp_unslash( $_POST['company_website'] ) );

	return '' !== $value;
}

function konkord_cf7_client_ip() {
	$candidates = array();

	if ( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
		$candidates[] = $_SERVER['HTTP_CF_CONNECTING_IP'];
	}

	if ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
		$candidates[] = $_SERVER['REMOTE_ADDR'];
	}

	foreach ( $candidates as $raw ) {
		$ip = trim( explode( ',', (string) $raw )[0] );
		if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			return $ip;
		}
	}

	return '0.0.0.0';
}

function konkord_cf7_rate_key( $ip ) {
	return 'kcf7_' . md5( $ip );
}

function konkord_cf7_rate_limit() {
	return (int) apply_filters( 'konkord_cf7_rate_limit', 4 );
}

function konkord_cf7_rate_window() {
	return (int) apply_filters( 'konkord_cf7_rate_window', 10 * MINUTE_IN_SECONDS );
}

function konkord_cf7_rate_hits( $ip ) {
	$hits = get_transient( konkord_cf7_rate_key( $ip ) );
	if ( ! is_array( $hits ) ) {
		$hits = array();
	}

	$since = time() - konkord_cf7_rate_window();
	$hits  = array_values( array_filter( $hits, function ( $t ) use ( $since ) {
		return (int) $t > $since;
	} ) );

	return $hits;
}

function konkord_cf7_rate_is_limited( $ip ) {
	return count( konkord_cf7_rate_hits( $ip ) ) >= konkord_cf7_rate_limit();
}

function konkord_cf7_rate_hit() {
	$ip   = konkord_cf7_client_ip();
	$key  = konkord_cf7_rate_key( $ip );
	$hits = konkord_cf7_rate_hits( $ip );
	$hits[] = time();

	set_transient( $key, $hits, konkord_cf7_rate_window() );
}

add_filter( 'wpcf7_spam', 'konkord_cf7_honeypot_spam', 9, 2 );
function konkord_cf7_honeypot_spam( $spam, $submission ) {
	if ( $spam ) {
		return $spam;
	}

	if ( ! konkord_cf7_honeypot_filled() ) {
		return $spam;
	}

	if ( $submission instanceof WPCF7_Submission ) {
		$submission->add_spam_log( array(
			'agent'  => 'honeypot',
			'reason' => 'hidden field filled',
		) );
	}

	konkord_cf7_rate_hit();

	return true;
}

/**
 * Не больше 4 заявок с одного IP за 10 минут.
 * Админов не ограничиваем — чтобы можно было тестировать формы.
 */
add_action( 'wpcf7_before_send_mail', 'konkord_cf7_rate_limit_abort', 1, 3 );
function konkord_cf7_rate_limit_abort( $contact_form, &$abort, $submission ) {
	if ( current_user_can( 'manage_options' ) ) {
		return;
	}

	$ip = konkord_cf7_client_ip();
	if ( ! konkord_cf7_rate_is_limited( $ip ) ) {
		return;
	}

	$abort = true;
	$submission->set_status( 'aborted' );
	$submission->set_response(
		'Слишком много заявок с вашего адреса. Подождите 10 минут и попробуйте снова.'
	);
}

add_action( 'wpcf7_mail_sent', 'konkord_cf7_rate_on_sent' );
function konkord_cf7_rate_on_sent() {
	if ( current_user_can( 'manage_options' ) ) {
		return;
	}

	konkord_cf7_rate_hit();
}