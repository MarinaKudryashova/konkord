<?php

/**
 * Отключаем только сверхбольшие служебные размеры WP.
 * large (1024) — десктоп-карточки; medium_large (768) — мобилка 2x.
 */
add_filter( 'intermediate_image_sizes', 'disable_default_image_sizes' );
function disable_default_image_sizes( $sizes ) {
	$disabled_sizes = array( '1536x1536', '2048x2048' );
	return array_diff( $sizes, $disabled_sizes );
}

/**
 * Явно регистрируем medium_large, если тема/ядро его убрали.
 */
add_action(
	'after_setup_theme',
	static function () {
		if ( ! array_key_exists( 'medium_large', wp_get_additional_image_sizes() ) && ! in_array( 'medium_large', get_intermediate_image_sizes(), true ) ) {
			add_image_size( 'medium_large', 768, 0, false );
		}
	}
);
