<?php
/**
 * Колонка «Размер файла» в медиабиблиотеке (режим списка).
 */

/**
 * Реальный путь к файлу вложения.
 * На локали OpenServer в `_wp_attached_file` часто лежит абсолютный путь —
 * get_attached_file() тогда склеивает basedir + абсолют и файл «пропадает».
 *
 * @param int $attachment_id Attachment ID.
 * @return string Readable path or empty string.
 */
function konkord_resolve_attachment_path( $attachment_id ) {
	$attachment_id = (int) $attachment_id;
	if ( ! $attachment_id ) {
		return '';
	}

	$stored = get_post_meta( $attachment_id, '_wp_attached_file', true );
	if ( ! is_string( $stored ) || $stored === '' ) {
		return '';
	}

	$stored_norm = wp_normalize_path( $stored );

	// Абсолютный путь в meta (Windows D:/… или /var/…).
	$is_absolute = (bool) preg_match( '#^[a-zA-Z]:/#', $stored_norm ) || ( isset( $stored_norm[0] ) && $stored_norm[0] === '/' );
	if ( $is_absolute ) {
		if ( is_readable( $stored_norm ) ) {
			return $stored_norm;
		}
		// Вытащить хвост после /uploads/.
		if ( preg_match( '#/uploads/(.+)$#', $stored_norm, $m ) ) {
			$uploads = wp_get_upload_dir();
			$candidate = wp_normalize_path( trailingslashit( $uploads['basedir'] ) . $m[1] );
			if ( is_readable( $candidate ) ) {
				return $candidate;
			}
		}
	}

	$path = get_attached_file( $attachment_id );
	if ( is_string( $path ) && $path !== '' ) {
		$path = wp_normalize_path( $path );
		if ( is_readable( $path ) ) {
			return $path;
		}
		// Раздвоенный путь: …/uploads/D:/…/uploads/YYYY/…
		if ( preg_match( '#/uploads/.+/uploads/(.+)$#', $path, $m ) ) {
			$uploads   = wp_get_upload_dir();
			$candidate = wp_normalize_path( trailingslashit( $uploads['basedir'] ) . $m[1] );
			if ( is_readable( $candidate ) ) {
				return $candidate;
			}
		}
	}

	// Относительный путь как есть.
	$uploads   = wp_get_upload_dir();
	$candidate = wp_normalize_path( trailingslashit( $uploads['basedir'] ) . ltrim( $stored_norm, '/' ) );
	if ( is_readable( $candidate ) ) {
		return $candidate;
	}

	return '';
}

/**
 * Пересчитать размер с диска и синхронизировать post meta.
 *
 * @param int $attachment_id Attachment ID.
 * @return int Bytes (0 if missing).
 */
function konkord_media_filesize_refresh_meta( $attachment_id ) {
	$attachment_id = (int) $attachment_id;
	if ( ! $attachment_id ) {
		return 0;
	}

	$path  = konkord_resolve_attachment_path( $attachment_id );
	$bytes = ( $path !== '' ) ? (int) filesize( $path ) : 0;

	if ( $bytes > 0 ) {
		update_post_meta( $attachment_id, '_konkord_filesize', $bytes );
	} else {
		delete_post_meta( $attachment_id, '_konkord_filesize' );
	}

	return $bytes;
}

/**
 * Размер вложения в байтах.
 * Всегда читает файл с диска; meta нужна для сортировки колонки.
 *
 * @param int $attachment_id Attachment ID.
 * @return int
 */
function konkord_get_attachment_filesize_bytes( $attachment_id ) {
	return konkord_media_filesize_refresh_meta( $attachment_id );
}

/**
 * @param array $columns Media columns.
 * @return array
 */
function konkord_media_filesize_column( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['konkord_filesize'] = 'Размер файла';
		}
	}
	if ( ! isset( $new['konkord_filesize'] ) ) {
		$new['konkord_filesize'] = 'Размер файла';
	}
	return $new;
}
add_filter( 'manage_media_columns', 'konkord_media_filesize_column' );

/**
 * @param string $column_name Column key.
 * @param int    $post_id     Attachment ID.
 */
function konkord_media_filesize_column_content( $column_name, $post_id ) {
	if ( 'konkord_filesize' !== $column_name ) {
		return;
	}

	$bytes = konkord_get_attachment_filesize_bytes( $post_id );
	if ( $bytes <= 0 ) {
		echo '—';
		return;
	}

	echo esc_html( size_format( $bytes, 1 ) );
}
add_action( 'manage_media_custom_column', 'konkord_media_filesize_column_content', 10, 2 );

/**
 * @param array $columns Sortable columns.
 * @return array
 */
function konkord_media_filesize_sortable( $columns ) {
	$columns['konkord_filesize'] = 'konkord_filesize';
	return $columns;
}
add_filter( 'manage_upload_sortable_columns', 'konkord_media_filesize_sortable' );

/**
 * Сортировка списка медиа по размеру файла.
 *
 * @param WP_Query $query Query.
 */
function konkord_media_filesize_orderby( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$orderby = $query->get( 'orderby' );
	if ( 'konkord_filesize' !== $orderby ) {
		return;
	}

	$query->set( 'meta_key', '_konkord_filesize' );
	$query->set( 'orderby', 'meta_value_num' );
}
add_action( 'pre_get_posts', 'konkord_media_filesize_orderby' );

add_action( 'add_attachment', 'konkord_media_filesize_refresh_meta' );
add_action( 'edit_attachment', 'konkord_media_filesize_refresh_meta' );

/**
 * После правки/регенерации метаданных изображения (кроп, scaled и т.п.).
 *
 * @param array $data          Attachment metadata.
 * @param int   $attachment_id Attachment ID.
 * @return array
 */
function konkord_media_filesize_on_metadata( $data, $attachment_id ) {
	konkord_media_filesize_refresh_meta( (int) $attachment_id );
	return $data;
}
add_filter( 'wp_update_attachment_metadata', 'konkord_media_filesize_on_metadata', 20, 2 );

/**
 * Пересчитать размеры пачкой при открытии медиабиблиотеки
 * (чтобы сортировка по колонке совпадала с диском, даже для строк вне текущей страницы).
 */
function konkord_media_filesize_backfill() {
	$batch  = 200;
	$offset = (int) get_transient( 'konkord_filesize_backfill_offset' );

	$ids = get_posts(
		array(
			'post_type'              => 'attachment',
			'post_status'            => 'inherit',
			'posts_per_page'         => $batch,
			'offset'                 => $offset,
			'orderby'                => 'ID',
			'order'                  => 'ASC',
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( empty( $ids ) ) {
		delete_transient( 'konkord_filesize_backfill_offset' );
		return;
	}

	foreach ( $ids as $id ) {
		konkord_media_filesize_refresh_meta( (int) $id );
	}

	if ( count( $ids ) < $batch ) {
		delete_transient( 'konkord_filesize_backfill_offset' );
	} else {
		set_transient( 'konkord_filesize_backfill_offset', $offset + $batch, HOUR_IN_SECONDS );
	}
}
add_action( 'load-upload.php', 'konkord_media_filesize_backfill' );
