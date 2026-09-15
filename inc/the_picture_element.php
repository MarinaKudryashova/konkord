<?php

/**
 * Универсальная функция для получения всех версий изображения по ID или URL
 *
 * @param mixed       $image       ID вложения, URL, или массив ACF.
 * @param string      $size        Размер WordPress (thumbnail, medium, medium_large, full…).
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
	$webp_1x     = str_replace( '/uploads/', '/uploads-webpc/uploads/', $directory ) . '/' . $filename . '.' . $extension . '.webp';

	$original_2x = '';
	$webp_2x     = '';

	if ( ! empty( $size_suffix ) ) {
		$original_2x = $directory . '/' . $filename . $size_suffix . '.' . $extension;
		$webp_2x     = str_replace( '/uploads/', '/uploads-webpc/uploads/', $directory ) . '/' . $filename . $size_suffix . '.' . $extension . '.webp';
	}

	$alt_text = '';
	if ( $image_id ) {
		$alt_text = (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	} elseif ( is_array( $image ) && isset( $image['alt'] ) ) {
		$alt_text = (string) $image['alt'];
	}

	// Mobile/tablet candidate from WP intermediate size (без отдельного ACF-поля).
	$mobile = array();
	if ( $image_id && $size === 'full' ) {
		foreach ( array( 'medium_large', 'medium' ) as $mobile_size ) {
			$mobile_url = wp_get_attachment_image_url( $image_id, $mobile_size );
			if ( ! $mobile_url || $mobile_url === $original_1x ) {
				continue;
			}
			$m_info = pathinfo( $mobile_url );
			$mobile = array(
				'original_1x' => $mobile_url,
				'webp_1x'     => str_replace( '/uploads/', '/uploads-webpc/uploads/', $m_info['dirname'] ) . '/' . $m_info['filename'] . '.' . ( $m_info['extension'] ?? $extension ) . '.webp',
				'format'      => $m_info['extension'] ?? $extension,
			);
			break;
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
 * Mobile sources: ACF mobile field → medium_large/medium из desktop.
 *
 * @param array $desktop    Результат get_image_versions() для desktop.
 * @param mixed $acf_mobile ACF image (ID|URL|array) или уже get_image_versions(); пусто — только WP size.
 * @return array|null
 */
function konkord_resolve_mobile_sources( $desktop, $acf_mobile = null ) {
	$desktop_url = $desktop['original_1x'] ?? '';

	if ( ! empty( $acf_mobile ) ) {
		$acf = ( is_array( $acf_mobile ) && isset( $acf_mobile['original_1x'] ) )
			? $acf_mobile
			: get_image_versions( $acf_mobile );
		if ( ! empty( $acf['original_1x'] ) && $acf['original_1x'] !== $desktop_url ) {
			return $acf;
		}
	}

	if ( ! empty( $desktop['mobile']['original_1x'] ) && $desktop['mobile']['original_1x'] !== $desktop_url ) {
		return $desktop['mobile'];
	}

	$image_id = (int) ( $desktop['id'] ?? 0 );
	if ( ! $image_id && $desktop_url ) {
		$image_id = (int) attachment_url_to_postid( $desktop_url );
	}

	if ( $image_id ) {
		foreach ( array( 'medium_large', 'medium' ) as $size ) {
			$sized = get_image_versions( $image_id, $size );
			if ( ! empty( $sized['original_1x'] ) && $sized['original_1x'] !== $desktop_url ) {
				return $sized;
			}
		}
	}

	return null;
}

/**
 * Echo <source media="…"> для mobile breakpoint.
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

	if ( ! empty( $mobile['webp_1x'] ) ) {
		printf(
			'<source media="%s" srcset="%s" type="image/webp">' . "\n",
			esc_attr( $media ),
			esc_url( $mobile['webp_1x'] )
		);
	}

	printf(
		'<source media="%s" srcset="%s" type="%s">' . "\n",
		esc_attr( $media ),
		esc_url( $mobile['original_1x'] ),
		esc_attr( $mime )
	);
}

/**
 * Вывод <picture> с optional mobile breakpoint (WP medium_large/medium или ACF mobile).
 *
 * @param array $sources Результат get_image_versions().
 * @param array $attrs   width, height, alt, loading, class, sizes, mobile_media, use_mobile, acf_mobile.
 */
function the_picture_element( $sources, $attrs = array() ) {
	if ( empty( $sources['original_1x'] ) ) {
		return;
	}

	$default_attrs = array(
		'width'        => '',
		'height'       => '',
		'alt'          => $sources['alt'] ?? '',
		'loading'      => 'lazy',
		'decoding'     => 'async',
		'fetchpriority'=> 'auto',
		'class'        => '',
		'sizes'        => '',
		'mobile_media' => '(max-width: 576px)',
		'use_mobile'   => true,
		'acf_mobile'   => null,
	);

	$attrs = wp_parse_args( $attrs, $default_attrs );

	if ( $attrs['loading'] === 'eager' ) {
		$attrs['decoding']     = 'async';
		$attrs['fetchpriority'] = 'high';
	} else {
		unset( $attrs['fetchpriority'] );
		$attrs['decoding'] = 'async';
	}

	$has_2x = ! empty( $sources['original_2x'] ) && ! empty( $sources['webp_2x'] );
	$format = $sources['format'] ?? 'jpg';
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
