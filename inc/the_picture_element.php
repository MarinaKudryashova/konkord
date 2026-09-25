<?php

/**
 * Converter for Media webp URL, only if the file exists on disk.
 *
 * @param string $image_url JPEG/PNG URL in /uploads/.
 * @return string
 */
function konkord_converter_webp_url( $image_url ) {
	if ( ! is_string( $image_url ) || $image_url === '' || strpos( $image_url, '/uploads/' ) === false ) {
		return '';
	}

	$webp_url = str_replace( '/uploads/', '/uploads-webpc/uploads/', $image_url ) . '.webp';

	// Схему не сравниваем: вложения бывают https, а content_url() — http.
	$path         = wp_parse_url( $webp_url, PHP_URL_PATH );
	$content_path = wp_parse_url( content_url(), PHP_URL_PATH );
	if ( ! is_string( $path ) || ! is_string( $content_path ) || $content_path === '' || strpos( $path, $content_path ) !== 0 ) {
		return '';
	}

	$file = wp_normalize_path( WP_CONTENT_DIR . substr( $path, strlen( $content_path ) ) );

	return is_readable( $file ) ? $webp_url : '';
}

/**
 * Мобильные источники: medium (1x) + medium_large (2x).
 *
 * @param int $image_id Attachment ID.
 * @return array|null
 */
function konkord_mobile_density_from_id( $image_id ) {
	$image_id = (int) $image_id;
	if ( ! $image_id ) {
		return null;
	}

	$url_1x = wp_get_attachment_image_url( $image_id, 'medium' );
	$url_2x = wp_get_attachment_image_url( $image_id, 'medium_large' );

	if ( ! $url_1x ) {
		$url_1x = $url_2x ? $url_2x : wp_get_attachment_image_url( $image_id, 'full' );
		$url_2x = '';
	}

	if ( ! $url_1x ) {
		return null;
	}

	$ext = pathinfo( $url_1x, PATHINFO_EXTENSION ) ?: 'jpg';
	$out = array(
		'original_1x' => $url_1x,
		'webp_1x'     => konkord_converter_webp_url( $url_1x ),
		'original_2x' => '',
		'webp_2x'     => '',
		'format'      => $ext,
		'id'          => $image_id,
	);

	if ( $url_2x && $url_2x !== $url_1x ) {
		$out['original_2x'] = $url_2x;
		$out['webp_2x']     = konkord_converter_webp_url( $url_2x );
	}

	return $out;
}

/**
 * srcset "url" или "url, url2 2x".
 *
 * @param string $url_1x
 * @param string $url_2x
 * @return string
 */
function konkord_srcset_1x_2x( $url_1x, $url_2x = '' ) {
	if ( ! $url_1x ) {
		return '';
	}
	$srcset = esc_url( $url_1x );
	if ( $url_2x && $url_2x !== $url_1x ) {
		$srcset .= ', ' . esc_url( $url_2x ) . ' 2x';
	}
	return $srcset;
}

/**
 * Универсальная функция для получения всех версий изображения по ID или URL
 *
 * @param mixed       $image       ID вложения, URL, или массив ACF.
 * @param string      $size        Размер WordPress (thumbnail, medium, medium_large, large, full…).
 * @param string|bool $size_suffix Суффикс ретины ('@2x') или false — без выдуманных 2x URL.
 * @return array
 */
function get_image_versions( $image, $size = 'full', $size_suffix = false ) {
	$image_url = '';
	$image_id  = 0;

	if ( is_numeric( $image ) ) {
		$image_id  = (int) $image;
		$image_url = wp_get_attachment_image_url( $image_id, $size );
	} elseif ( is_array( $image ) && isset( $image['url'] ) ) {
		$image_url = $image['url'];
		$image_id  = isset( $image['ID'] ) ? (int) $image['ID'] : 0;

		if ( $size !== 'full' && isset( $image['sizes'][ $size ] ) ) {
			$image_url = $image['sizes'][ $size ];
		} elseif ( $image_id && $size !== 'full' ) {
			$sized = wp_get_attachment_image_url( $image_id, $size );
			if ( $sized ) {
				$image_url = $sized;
			}
		}
	} elseif ( is_string( $image ) ) {
		$image_url = $image;
		$image_id  = attachment_url_to_postid( $image_url );
	}

	if ( empty( $image_url ) ) {
		return array();
	}

	// Если запрошенный size не сгенерирован — WP мог вернуть full; пробуем явный size по ID.
	if ( $image_id && $size !== 'full' ) {
		$sized = wp_get_attachment_image_url( $image_id, $size );
		if ( $sized ) {
			$image_url = $sized;
		}
	}

	$pathinfo  = pathinfo( $image_url );
	$filename  = $pathinfo['filename'];
	$extension = $pathinfo['extension'] ?? 'jpg';
	$directory = $pathinfo['dirname'];

	$supported_formats = array( 'jpg', 'jpeg', 'png', 'webp' );

	if ( ! in_array( strtolower( $extension ), $supported_formats, true ) ) {
		return array(
			'original_1x' => $image_url,
			'webp_1x'     => '',
			'original_2x' => '',
			'webp_2x'     => '',
			'mobile'      => array(),
			'alt'         => '',
			'format'      => $extension,
			'id'          => $image_id,
		);
	}

	$original_1x = $image_url;
	$webp_1x     = konkord_converter_webp_url( $original_1x );

	$original_2x = '';
	$webp_2x     = '';

	if ( ! empty( $size_suffix ) ) {
		$original_2x = $directory . '/' . $filename . $size_suffix . '.' . $extension;
		$webp_2x     = konkord_converter_webp_url( $original_2x );
	}

	$alt_text = '';
	if ( $image_id ) {
		$alt_text = (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	} elseif ( is_array( $image ) && isset( $image['alt'] ) ) {
		$alt_text = (string) $image['alt'];
	}

	// Mobile density: не строим при запросе самих medium/medium_large (избегаем рекурсии).
	$mobile = array();
	if ( $image_id && ! in_array( $size, array( 'thumbnail', 'medium', 'medium_large' ), true ) ) {
		$built = konkord_mobile_density_from_id( $image_id );
		if ( $built ) {
			$mobile = $built;
		}
	}

	return array(
		'original_1x' => $original_1x,
		'webp_1x'     => $webp_1x,
		'original_2x' => $original_2x,
		'webp_2x'     => $webp_2x,
		'mobile'      => $mobile,
		'alt'         => $alt_text,
		'format'      => $extension,
		'id'          => $image_id,
	);
}

/**
 * Mobile sources: ACF mobile → иначе medium (1x) + medium_large (2x).
 *
 * @param array $desktop    Результат get_image_versions() для desktop.
 * @param mixed $acf_mobile ACF image (ID|URL|array) или уже get_image_versions(); пусто — WP density.
 * @return array|null
 */
function konkord_resolve_mobile_sources( $desktop, $acf_mobile = null ) {
	$desktop_url = $desktop['original_1x'] ?? '';

	if ( ! empty( $acf_mobile ) ) {
		$acf_id = 0;
		if ( is_numeric( $acf_mobile ) ) {
			$acf_id = (int) $acf_mobile;
		} elseif ( is_array( $acf_mobile ) && ! empty( $acf_mobile['ID'] ) ) {
			$acf_id = (int) $acf_mobile['ID'];
		} elseif ( is_array( $acf_mobile ) && ! empty( $acf_mobile['id'] ) ) {
			$acf_id = (int) $acf_mobile['id'];
		} elseif ( is_string( $acf_mobile ) ) {
			$acf_id = (int) attachment_url_to_postid( $acf_mobile );
		}

		// Отдельный ACF-кадр: берём density с этого ID (medium + medium_large).
		if ( $acf_id ) {
			$from_acf = konkord_mobile_density_from_id( $acf_id );
			if ( $from_acf && ! empty( $from_acf['original_1x'] ) && $from_acf['original_1x'] !== $desktop_url ) {
				return $from_acf;
			}
			// Если density совпал с desktop — всё равно вернём full ACF как 1x.
			$acf_full = get_image_versions( $acf_id, 'full' );
			if ( ! empty( $acf_full['original_1x'] ) && $acf_full['original_1x'] !== $desktop_url ) {
				return array(
					'original_1x' => $acf_full['original_1x'],
					'webp_1x'     => $acf_full['webp_1x'] ?? '',
					'original_2x' => '',
					'webp_2x'     => '',
					'format'      => $acf_full['format'] ?? 'jpg',
					'id'          => $acf_id,
				);
			}
		} elseif ( is_array( $acf_mobile ) && isset( $acf_mobile['original_1x'] ) ) {
			if ( $acf_mobile['original_1x'] !== $desktop_url ) {
				return $acf_mobile;
			}
		}
	}

	if ( ! empty( $desktop['mobile']['original_1x'] ) ) {
		return $desktop['mobile'];
	}

	$image_id = (int) ( $desktop['id'] ?? 0 );
	if ( ! $image_id && $desktop_url ) {
		$image_id = (int) attachment_url_to_postid( $desktop_url );
	}

	if ( $image_id ) {
		return konkord_mobile_density_from_id( $image_id );
	}

	return null;
}

/**
 * Echo <source media="…"> для mobile breakpoint (1x medium, 2x medium_large).
 *
 * @param array|null $mobile Результат konkord_resolve_mobile_sources().
 * @param string     $media  Media query.
 */
function konkord_picture_mobile_sources( $mobile, $media = '(max-width: 576px)' ) {
	if ( empty( $mobile['original_1x'] ) ) {
		return;
	}

	$format = $mobile['format'] ?? 'jpg';
	$mime   = ( $format === 'png' ) ? 'image/png' : 'image/jpeg';

	$has_2x  = ! empty( $mobile['original_2x'] ) && $mobile['original_2x'] !== $mobile['original_1x'];
	$webp_1x = $mobile['webp_1x'] ?? '';
	$webp_2x = $mobile['webp_2x'] ?? '';

	// WebP-source только если для всех плотностей srcset есть webp,
	// иначе ретина застрянет на webp 1x без 2x.
	$emit_webp = $webp_1x && ( ! $has_2x || $webp_2x );

	if ( $emit_webp ) {
		printf(
			'<source media="%s" srcset="%s" type="image/webp">' . "\n",
			esc_attr( $media ),
			konkord_srcset_1x_2x( $webp_1x, $webp_2x )
		);
	}

	printf(
		'<source media="%s" srcset="%s" type="%s">' . "\n",
		esc_attr( $media ),
		konkord_srcset_1x_2x( $mobile['original_1x'], $mobile['original_2x'] ?? '' ),
		esc_attr( $mime )
	);
}

/**
 * Вывод <picture> с optional mobile breakpoint (medium/medium_large или ACF mobile).
 *
 * @param array $sources Результат get_image_versions().
 * @param array $attrs   width, height, alt, loading, class, sizes, mobile_media, use_mobile, acf_mobile.
 */
function the_picture_element( $sources, $attrs = array() ) {
	if ( empty( $sources['original_1x'] ) ) {
		return;
	}

	$default_attrs = array(
		'width'         => '',
		'height'        => '',
		'alt'           => $sources['alt'] ?? '',
		'loading'       => 'lazy',
		'decoding'      => 'async',
		'fetchpriority' => 'auto',
		'class'         => '',
		'sizes'         => '',
		'mobile_media'  => '(max-width: 576px)',
		'use_mobile'    => true,
		'acf_mobile'    => null,
	);

	$attrs = wp_parse_args( $attrs, $default_attrs );

	if ( $attrs['loading'] === 'eager' ) {
		$attrs['decoding']      = 'async';
		$attrs['fetchpriority'] = 'high';
	} else {
		unset( $attrs['fetchpriority'] );
		$attrs['decoding'] = 'async';
	}

	$has_2x    = ! empty( $sources['original_2x'] ) && ! empty( $sources['webp_2x'] );
	$format    = $sources['format'] ?? 'jpg';
	$mime_type = ( $format === 'png' ) ? 'image/png' : 'image/jpeg';

	$mobile = array();
	if ( ! empty( $attrs['use_mobile'] ) ) {
		$resolved = konkord_resolve_mobile_sources( $sources, $attrs['acf_mobile'] );
		if ( $resolved ) {
			$mobile = $resolved;
		}
	}
	?>
	<picture class="picture-element <?php echo esc_attr( $attrs['class'] ); ?>">
		<?php konkord_picture_mobile_sources( $mobile, $attrs['mobile_media'] ); ?>

		<?php if ( ! empty( $sources['webp_1x'] ) ) : ?>
			<source srcset="<?php echo esc_url( $sources['webp_1x'] ); ?><?php echo $has_2x ? ', ' . esc_url( $sources['webp_2x'] ) . ' 2x' : ''; ?>" type="image/webp">
		<?php endif; ?>

		<source srcset="<?php echo esc_url( $sources['original_1x'] ); ?><?php echo $has_2x ? ', ' . esc_url( $sources['original_2x'] ) . ' 2x' : ''; ?>" type="<?php echo esc_attr( $mime_type ); ?>">

		<img src="<?php echo esc_url( $sources['original_1x'] ); ?>"
			<?php if ( $has_2x ) : ?>
				srcset="<?php echo esc_url( $sources['original_2x'] ); ?> 2x"
			<?php endif; ?>
			<?php if ( ! empty( $attrs['sizes'] ) ) : ?>
				sizes="<?php echo esc_attr( $attrs['sizes'] ); ?>"
			<?php endif; ?>
			width="<?php echo esc_attr( $attrs['width'] ); ?>"
			height="<?php echo esc_attr( $attrs['height'] ); ?>"
			alt="<?php echo esc_attr( $attrs['alt'] ); ?>"
			loading="<?php echo esc_attr( $attrs['loading'] ); ?>"
			decoding="<?php echo esc_attr( $attrs['decoding'] ); ?>"
			<?php if ( $attrs['loading'] === 'eager' && ! empty( $attrs['fetchpriority'] ) ) : ?>
				fetchpriority="<?php echo esc_attr( $attrs['fetchpriority'] ); ?>"
			<?php endif; ?>
			class="<?php echo esc_attr( $attrs['class'] ); ?>">
	</picture>
	<?php
}

/**
 * Convert iframe src → data-src for lazy load below the fold.
 *
 * @param string $html Widget HTML with iframes.
 * @return string
 */
function konkord_lazy_iframes_html( $html ) {
	if ( ! is_string( $html ) || $html === '' || stripos( $html, '<iframe' ) === false ) {
		return $html;
	}

	return preg_replace_callback(
		'#<iframe\b([^>]*)>#i',
		static function ( $m ) {
			$attrs = $m[1];
			if ( preg_match( '#\bdata-src\s*=#i', $attrs ) ) {
				return $m[0];
			}
			if ( ! preg_match( '#\bsrc\s*=\s*([\'"])(.*?)\1#i', $attrs, $sm ) ) {
				return $m[0];
			}
			$src   = $sm[2];
			$attrs = preg_replace( '#\bsrc\s*=\s*([\'"]).*?\1#i', 'src="about:blank" data-src="' . esc_url( $src ) . '"', $attrs, 1 );
			if ( ! preg_match( '#\bloading\s*=#i', $attrs ) ) {
				$attrs .= ' loading="lazy"';
			}
			if ( ! preg_match( '#\bclass\s*=#i', $attrs ) ) {
				$attrs .= ' class="js-lazy-iframe"';
			} elseif ( ! preg_match( '#js-lazy-iframe#', $attrs ) ) {
				$attrs = preg_replace( '#\bclass\s*=\s*([\'"])(.*?)\1#i', 'class="$2 js-lazy-iframe"', $attrs, 1 );
			}
			return '<iframe' . $attrs . '>';
		},
		$html
	);
}

/**
 * Yandex Maps embed pastes a wrapper with inline width/height (560×500).
 * Drop those so the theme layout can size the widget.
 *
 * @param string $html Widget HTML.
 * @return string
 */
function konkord_reviews_widget_html( $html ) {
	$html = konkord_lazy_iframes_html( $html );
	if ( ! is_string( $html ) || $html === '' ) {
		return $html;
	}

	return preg_replace_callback(
		'#<div\b([^>]*\bstyle\s*=\s*([\'"])(.*?)\2[^>]*)>#i',
		static function ( $m ) {
			$style = preg_replace( '/\b(?:width|height)\s*:\s*[^;]+;?/i', '', $m[3] );
			$style = trim( preg_replace( '/\s*;\s*;+/', ';', $style ), " \t\n\r\0\x0B;" );
			$attrs = preg_replace(
				'#\sstyle\s*=\s*([\'"]).*?\1#i',
				$style === '' ? '' : ' style="' . esc_attr( $style ) . '"',
				$m[1],
				1
			);
			return '<div' . $attrs . '>';
		},
		$html
	);
}
