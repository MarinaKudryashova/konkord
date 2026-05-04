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
 * Получение первого абзаца из контента (упрощенный)
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
