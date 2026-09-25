<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package konkord
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function konkord_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'konkord_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function konkord_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'konkord_pingback_header' );

/**
 * Изменение длины Excerpt (количество слов)
 */
function custom_excerpt_length($length) { return 24; }
add_filter('excerpt_length', 'custom_excerpt_length');

/**
 * Получение первого абзаца из контента
 */
function get_first_paragraph_from_content($post_id) {
    $content = get_post_field('post_content', $post_id);
    
    // Убираем все HTML-теги
    $text = wp_strip_all_tags($content);
    
    // Декодируем HTML-сущности (&nbsp; → пробел)
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
    // Убираем лишние пробелы
    $text = preg_replace('/\s+/', ' ', $text);
    
    // Берем до первой точки (если она есть и не слишком далеко)
    $dot_pos = mb_strpos($text, '.');
    if ($dot_pos !== false && $dot_pos < 300) {
        return mb_substr($text, 0, $dot_pos + 1);
    }
    
    // Если точки нет - берем первые 200 символов
    return mb_substr($text, 0, 200);
}

/**
 * Изменяем excerpt: берем первый абзац, обрезаем до 24 слов
 */
function custom_excerpt_from_first_paragraph($excerpt, $post) {
    if (empty($excerpt) || $excerpt === '…') {
        $first_paragraph = get_first_paragraph_from_content($post->ID);
        
        if (!empty($first_paragraph)) {
            // Обрезаем до 24 слов
            $words = explode(' ', $first_paragraph);
            if (count($words) > 24) {
                $excerpt = implode(' ', array_slice($words, 0, 24)) . '...';
            } else {
                $excerpt = $first_paragraph;
            }
        }
    }
    return $excerpt;
}
add_filter('get_the_excerpt', 'custom_excerpt_from_first_paragraph', 10, 2);

/**
 * Изменение текста "[...]" на "..."
 */
function custom_excerpt_more($more) {
    return ''; // Или '…' если хотите многоточие одним символом
}
add_filter('excerpt_more', 'custom_excerpt_more');

/**
 * Split a phone field into separate numbers (new line or second +7 on the same line).
 */
function konkord_split_phone_list( $value ) {
	if ( ! is_string( $value ) || $value === '' ) {
		return array();
	}

	$normalized = preg_replace( '/\r\n|\r/', "\n", $value );
	$parts      = preg_split( '/\n+|(?<=\d)\s+(?=\+\s*7)/u', $normalized );

	return array_values( array_filter( array_map( 'trim', $parts ) ) );
}

/**
 * tel: href from a display phone number. Keeps leading + if present.
 *
 * @param string $phone
 * @return string
 */
function konkord_phone_href( $phone ) {
	$phone = trim( (string) $phone );
	if ( $phone === '' ) {
		return '';
	}

	if ( strpos( $phone, '+' ) === 0 ) {
		return preg_replace( '/[^0-9+]/', '', $phone );
	}

	return preg_replace( '/[^0-9]/', '', $phone );
}

/**
 * BEM modifier for a company messenger icon (vk / whatsapp).
 *
 * @param string $value ACF choice slug.
 * @return string
 */
function konkord_messenger_link_class( $value ) {
	$class = 'messanges__link';
	if ( 'vk' === $value ) {
		$class .= ' messanges__link--vk';
	} elseif ( 'whatsapp' === $value ) {
		$class .= ' messanges__link--whatsapp';
	}

	return $class;
}

/**
 * Whether a dedicated SEO plugin is active (Yoast / Rank Math / AIOSEO).
 */
function konkord_has_seo_plugin() {
	return defined( 'WPSEO_VERSION' )
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'AIOSEO_VERSION' )
		|| function_exists( 'aioseo' );
}

/**
 * Yoast social option, with a safe fallback if the Options API is unavailable.
 *
 * @param string $key     Option key (opengraph, twitter).
 * @param mixed  $default Default when missing.
 * @return mixed
 */
function konkord_yoast_social_option( $key, $default = false ) {
	if ( class_exists( 'WPSEO_Options' ) ) {
		return WPSEO_Options::get( $key, $default );
	}

	$social = get_option( 'wpseo_social', array() );
	if ( is_array( $social ) && array_key_exists( $key, $social ) ) {
		return $social[ $key ];
	}

	return $default;
}

/**
 * True when an SEO plugin will print Open Graph tags.
 */
function konkord_seo_outputs_opengraph() {
	if ( defined( 'WPSEO_VERSION' ) ) {
		return konkord_yoast_social_option( 'opengraph', true ) === true;
	}

	return konkord_has_seo_plugin();
}

/**
 * True when an SEO plugin will print Twitter Card tags.
 */
function konkord_seo_outputs_twitter() {
	if ( defined( 'WPSEO_VERSION' ) ) {
		return konkord_yoast_social_option( 'twitter', true ) === true;
	}

	return konkord_has_seo_plugin();
}

/**
 * Fallback title / description / image when Yoast is off or OG is disabled.
 *
 * @return array{title:string,description:string,url:string,image:string}
 */
function konkord_fallback_share_meta() {
	$title = wp_get_document_title();
	$desc  = get_bloginfo( 'description', 'display' );

	if ( is_singular() ) {
		$excerpt = get_the_excerpt();
		if ( is_string( $excerpt ) && $excerpt !== '' ) {
			$desc = wp_strip_all_tags( $excerpt );
		}
	}

	$url = home_url( '/' );
	if ( is_singular() ) {
		$permalink = get_permalink();
		if ( $permalink ) {
			$url = $permalink;
		}
	}

	$image = get_template_directory_uri() . '/img/site-preview.jpg';
	if ( is_singular() && has_post_thumbnail() ) {
		$thumb = get_the_post_thumbnail_url( null, 'full' );
		if ( $thumb ) {
			$image = $thumb;
		}
	}

	return array(
		'title'       => $title,
		'description' => $desc,
		'url'         => $url,
		'image'       => $image,
	);
}

/**
 * Theme OG / Twitter / description only if the SEO plugin does not output them.
 */
function konkord_fallback_meta_tags() {
	$meta    = konkord_fallback_share_meta();
	$site    = get_bloginfo( 'name', 'display' );
	$printed = false;

	if ( ! konkord_has_seo_plugin() && $meta['description'] !== '' ) {
		printf(
			'<meta name="description" content="%s">' . "\n",
			esc_attr( $meta['description'] )
		);
		$printed = true;
	}

	if ( ! konkord_seo_outputs_opengraph() ) {
		printf(
			'<meta property="og:type" content="%s">' . "\n" .
			'<meta property="og:site_name" content="%s">' . "\n" .
			'<meta property="og:title" content="%s">' . "\n" .
			'<meta property="og:description" content="%s">' . "\n" .
			'<meta property="og:url" content="%s">' . "\n" .
			'<meta property="og:image" content="%s">' . "\n" .
			'<meta property="og:image:width" content="1200">' . "\n" .
			'<meta property="og:image:height" content="630">' . "\n" .
			'<meta property="og:locale" content="%s">' . "\n",
			is_singular() ? 'article' : 'website',
			esc_attr( $site ),
			esc_attr( $meta['title'] ),
			esc_attr( $meta['description'] ),
			esc_url( $meta['url'] ),
			esc_url( $meta['image'] ),
			esc_attr( get_locale() )
		);
		$printed = true;
	}

	if ( ! konkord_seo_outputs_twitter() ) {
		printf(
			'<meta name="twitter:card" content="summary_large_image">' . "\n" .
			'<meta name="twitter:title" content="%s">' . "\n" .
			'<meta name="twitter:description" content="%s">' . "\n" .
			'<meta name="twitter:image" content="%s">' . "\n",
			esc_attr( $meta['title'] ),
			esc_attr( $meta['description'] ),
			esc_url( $meta['image'] )
		);
		$printed = true;
	}

	return $printed;
}
add_action( 'wp_head', 'konkord_fallback_meta_tags', 1 );
