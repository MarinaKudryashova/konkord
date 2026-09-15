<?php
/**
 * Defer third-party analytics (Metrika / gtag) until after window load.
 * Only rewrites known analytics script hosts — does not touch other scripts.
 *
 * @package konkord
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return bool
 */
function konkord_should_defer_analytics(): bool {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return false;
	}
	if ( is_feed() || is_preview() || is_customize_preview() ) {
		return false;
	}
	return (bool) apply_filters( 'konkord_defer_analytics', true );
}

/**
 * @return string[] Hosts that may be deferred.
 */
function konkord_deferred_analytics_hosts(): array {
	return (array) apply_filters(
		'konkord_deferred_analytics_hosts',
		array(
			'mc.yandex.ru',
			'mc.yandex.com',
			'www.googletagmanager.com',
			'www.google-analytics.com',
			'google-analytics.com',
		)
	);
}

/**
 * @param string $html Full HTML.
 * @return string
 */
function konkord_defer_analytics_buffer( string $html ): string {
	if ( $html === '' || stripos( $html, '<script' ) === false ) {
		return $html;
	}

	$hosts = konkord_deferred_analytics_hosts();
	if ( empty( $hosts ) ) {
		return $html;
	}

	$host_re = implode(
		'|',
		array_map(
			static function ( $h ) {
				return preg_quote( $h, '#' );
			},
			$hosts
		)
	);

	$items = array();

	$html = preg_replace_callback(
		'#<script\b([^>]*)>(.*?)</script>#is',
		static function ( $m ) use ( &$items, $host_re ) {
			$attrs = $m[1];
			$body  = $m[2];

			// Skip non-JS script types (JSON-LD, etc.).
			if ( preg_match( '#\btype\s*=\s*([\'"])(?!text/javascript|application/javascript|module|)([^\'"]*)\1#i', $attrs, $tm ) ) {
				$type = strtolower( trim( $tm[2] ) );
				if ( $type !== '' && $type !== 'text/javascript' && $type !== 'application/javascript' && $type !== 'module' ) {
					return $m[0];
				}
			}

			if ( preg_match( '#\bsrc\s*=\s*([\'"])(.*?)\1#i', $attrs, $sm ) ) {
				$src = $sm[2];
				if ( preg_match( '#^(?:https?:)?//(?:' . $host_re . ')#i', $src ) ) {
					$items[] = array(
						'type' => 'src',
						'src'  => esc_url_raw( $src ),
					);
					return '<!-- konkord:deferred-analytics -->';
				}
				return $m[0];
			}

			if ( $body !== '' && preg_match( '#' . $host_re . '#i', $body ) ) {
				$items[] = array(
					'type' => 'inline',
					'code' => $body,
				);
				return '<!-- konkord:deferred-analytics -->';
			}

			return $m[0];
		},
		$html
	);

	if ( empty( $items ) || ! is_string( $html ) ) {
		return is_string( $html ) ? $html : '';
	}

	$payload = wp_json_encode( $items, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	if ( ! is_string( $payload ) ) {
		return $html;
	}

	$loader = '<script id="konkord-deferred-analytics">'
		. 'window.addEventListener("load",function(){setTimeout(function(){'
		. 'try{var items=' . $payload . ';'
		. 'for(var i=0;i<items.length;i++){var item=items[i];'
		. 'if(item.type==="src"&&item.src){var s=document.createElement("script");s.src=item.src;s.async=true;document.head.appendChild(s);}'
		. 'else if(item.type==="inline"&&item.code){var s2=document.createElement("script");s2.text=item.code;document.head.appendChild(s2);}'
		. '}}catch(e){}'
		. '},1);});'
		. '</script>';

	if ( stripos( $html, '</body>' ) !== false ) {
		return (string) preg_replace( '#</body>#i', $loader . '</body>', $html, 1 );
	}

	return $html . $loader;
}

add_action(
	'template_redirect',
	static function () {
		if ( ! konkord_should_defer_analytics() ) {
			return;
		}
		ob_start( 'konkord_defer_analytics_buffer' );
	},
	0
);
