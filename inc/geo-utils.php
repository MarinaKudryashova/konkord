<?php
/**
 * GEO-утилиты для работы с городами BelingoGeo
 * 
 * @package konkord
 */

/**
 * Получить текущий город из BelingoGeo
 * 
 * @return string|null Слаг города или null
 */
function get_current_geo_city() {
    if(function_exists('belingo_get_current_city')) {
        $city = belingo_get_current_city();
        if(!empty($city['slug'])) {
            return $city['slug'];
        }
    }
    
    return null;
}

/**
 * Получить текущий город из query vars или из BelingoGeo
 * 
 * @return string|null Слаг города или null
 */
function get_geo_city_from_query() {
    $city = get_query_var('geo_city');
    
    if(empty($city)) {
        $city = get_current_geo_city();
    }
    
    return $city;
}

/**
 * Построить URL с городом
 * 
 * @param string $path Путь без ведущего слеша
 * @param string|null $city Слаг города (если null — используется текущий)
 * @return string URL
 */
function build_geo_url($path = '', $city = null) {
    $path = ltrim($path, '/');
    
    if(empty($city)) {
        $city = get_geo_city_from_query();
    }
    
    if(!empty($city)) {
        return home_url('/' . $city . '/' . $path);
    }
    
    return home_url('/' . $path);
}

/**
 * Схема текущего запроса гостя (по Referer), иначе схема AJAX.
 * На *.local всегда http: OpenServer/Super Cache на HTTPS отдаёт
 * index-https.html.gz как application/x-gzip → «Без названия.gz».
 *
 * @param string $url Redirect URL.
 * @return string
 */
function konkord_belingo_same_scheme_url( $url ) {
	$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
	if ( $host && preg_match( '/\.local$/i', $host ) ) {
		return set_url_scheme( $url, 'http' );
	}

	$scheme = is_ssl() ? 'https' : 'http';
	if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
		$ref = wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) );
		if ( ! empty( $ref['scheme'] ) && in_array( $ref['scheme'], array( 'http', 'https' ), true ) ) {
			$scheme = $ref['scheme'];
		}
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) {
		$proto = strtolower( (string) wp_unslash( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) );
		if ( in_array( $proto, array( 'http', 'https' ), true ) ) {
			$scheme = $proto;
		}
	}

	return set_url_scheme( $url, $scheme );
}

/**
 * Slug города «Дзержинск» в Belingo (основной город = без префикса в URL).
 *
 * @return string
 */
function konkord_belingo_default_city_slug() {
	return 'dzerzhinsk-2';
}

/**
 * Дзержинск = город по умолчанию Belingo → URL без /city/, только основной домен.
 */
function konkord_belingo_ensure_dzerzhinsk_default() {
	if ( ! function_exists( 'belingogeo_get_city_by' ) ) {
		return;
	}

	$city = belingogeo_get_city_by( 'slug', konkord_belingo_default_city_slug() );
	if ( ! $city || ! method_exists( $city, 'get_id' ) ) {
		return;
	}

	$id  = (int) $city->get_id();
	$opt = get_option( 'belingo_geo_basic_default_nonecity' );
	if ( is_array( $opt ) && isset( $opt[0] ) && (int) $opt[0] === $id ) {
		return;
	}

	update_option( 'belingo_geo_basic_default_nonecity', array( $id ) );
	flush_rewrite_rules( false );
}
add_action( 'init', 'konkord_belingo_ensure_dzerzhinsk_default', 5 );

/**
 * Редирект Belingo после выбора города: та же схема + Дзержинск без /slug/.
 *
 * @param string      $url          Redirect URL.
 * @param bool|int    $is_exclude   Exclude flag from Belingo.
 * @param bool|string $disable_urls Disable city URLs option.
 * @param string      $city_slug    Selected city slug.
 * @return string
 */
function konkord_belingo_backurl( $url, $is_exclude = false, $disable_urls = false, $city_slug = '' ) {
	$url = konkord_belingo_same_scheme_url( $url );

	$default_slug = konkord_belingo_default_city_slug();
	if ( function_exists( 'belingogeo_get_default_city' ) ) {
		$def = belingogeo_get_default_city();
		if ( $def && method_exists( $def, 'get_slug' ) && $def->get_slug() ) {
			$default_slug = $def->get_slug();
		}
	}

	if ( $city_slug && $city_slug === $default_slug && function_exists( 'belingogeo_remove_city_url' ) ) {
		$url = belingogeo_remove_city_url( $url, $city_slug );
	}

	return $url;
}

add_filter( 'belingogeo_backurl_in_ajax', 'konkord_belingo_backurl', 10, 4 );
add_filter( 'belingogeo_backurl_in_nogeo_ajax', 'konkord_belingo_same_scheme_url' );

/**
 * admin-ajax Belingo в той же схеме, что и страница (http↔https).
 *
 * @param array $data Localized belingoGeo data.
 * @return array
 */
function konkord_belingo_ajax_data_same_scheme( $data ) {
	if ( ! is_array( $data ) ) {
		return $data;
	}
	if ( ! empty( $data['ajaxurl'] ) ) {
		$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
		$scheme = ( $host && preg_match( '/\.local$/i', $host ) )
			? 'http'
			: ( is_ssl() ? 'https' : 'http' );
		$data['ajaxurl'] = set_url_scheme( $data['ajaxurl'], $scheme );
	}
	return $data;
}
add_filter( 'belingogeo_ajax_data', 'konkord_belingo_ajax_data_same_scheme' );

/**
 * Получить паттерн для городов (для rewrite правил)
 * 
 * @return string|null Регулярное выражение для городов или null
 */
function get_geo_city_pattern() {
    $cities = get_option('belingo_geo_cities', array());
    $city_slugs = array();

    foreach ($cities as $city) {
        if (!empty($city['slug'])) {
            $city_slugs[] = preg_quote($city['slug'], '/');
        }
    }

    if (empty($city_slugs)) {
        return null;
    }

    return '(' . implode('|', $city_slugs) . ')';
}

/**
 * Телефон в шапке: для Нижнего Новгорода — поля BelingoGeo
 * (Telephone / Telephone link), иначе ACF company_tel.
 *
 * @return array{display:string,href:string}
 */
function konkord_get_header_phone() {
	$fallback = '';
	if ( function_exists( 'get_field' ) ) {
		$fallback = (string) get_field( 'company_tel', 'option' );
	}
	$fallback_list = function_exists( 'konkord_split_phone_list' )
		? konkord_split_phone_list( $fallback )
		: array_filter( array( trim( $fallback ) ) );
	$display = $fallback_list[0] ?? '';
	$href    = function_exists( 'konkord_phone_href' ) ? konkord_phone_href( $display ) : preg_replace( '/[^0-9+]/', '', $display );

	if ( ! function_exists( 'belingoGeo_get_current_city' ) ) {
		return array(
			'display' => $display,
			'href'    => $href,
		);
	}

	$city = belingoGeo_get_current_city();
	if ( ! $city || ! is_object( $city ) || ! method_exists( $city, 'get_slug' ) ) {
		return array(
			'display' => $display,
			'href'    => $href,
		);
	}

	// Нижний Новгород — Telephone / Telephone link из карточки города BelingoGeo.
	if ( 'nizhnij-novgorod' !== $city->get_slug() || ! method_exists( $city, 'get_meta' ) ) {
		return array(
			'display' => $display,
			'href'    => $href,
		);
	}

	$meta         = $city->get_meta();
	$city_phone   = isset( $meta['city_phone'][0] ) ? trim( (string) $meta['city_phone'][0] ) : '';
	$city_phone_l = isset( $meta['city_phone_link'][0] ) ? trim( (string) $meta['city_phone_link'][0] ) : '';

	if ( $city_phone === '' ) {
		return array(
			'display' => $display,
			'href'    => $href,
		);
	}

	$display = $city_phone;
	if ( $city_phone_l !== '' ) {
		$href = preg_replace( '/[^0-9+]/', '', $city_phone_l );
	} elseif ( function_exists( 'konkord_phone_href' ) ) {
		$href = konkord_phone_href( $city_phone );
	} else {
		$href = preg_replace( '/[^0-9+]/', '', $city_phone );
	}

	return array(
		'display' => $display,
		'href'    => $href,
	);
}
