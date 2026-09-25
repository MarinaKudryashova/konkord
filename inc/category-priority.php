<?php
/**
 * Приоритет рубрик (category).
 *
 * Term meta: _konkord_term_priority (целое ≥ 0).
 * Сначала 1, 2, 3…; 0 / не задано — в конце по алфавиту; если все 0 — весь список по алфавиту.
 */

define( 'KONKORD_TERM_PRIORITY_META', '_konkord_term_priority' );

/** Слаг основной рубрики новостей (= страница /novosti-i-akczii/). */
define( 'KONKORD_PRIMARY_NEWS_CAT_SLUG', 'news' );

/**
 * @param int|WP_Term $term Term ID or object.
 * @return int
 */
function konkord_get_term_priority( $term ) {
	$term_id = 0;
	if ( is_object( $term ) && isset( $term->term_id ) ) {
		$term_id = (int) $term->term_id;
	} else {
		$term_id = (int) $term;
	}
	if ( ! $term_id ) {
		return 0;
	}
	$val = get_term_meta( $term_id, KONKORD_TERM_PRIORITY_META, true );
	return ( $val !== '' && is_numeric( $val ) ) ? (int) $val : 0;
}

/**
 * Основная рубрика «Новости» (slug news), иначе первая по приоритету.
 *
 * @return WP_Term|null
 */
function konkord_get_primary_news_category() {
	$term = get_term_by( 'slug', KONKORD_PRIMARY_NEWS_CAT_SLUG, 'category' );
	if ( $term && ! is_wp_error( $term ) ) {
		return $term;
	}
	$cats = konkord_get_categories_by_priority( array( 'hide_empty' => false ) );
	return ! empty( $cats[0] ) ? $cats[0] : null;
}

/**
 * URL страницы «Новости и акции» (page_for_posts).
 *
 * @return string
 */
function konkord_get_news_page_url() {
	$page_id = (int) get_option( 'page_for_posts' );
	return $page_id ? (string) get_permalink( $page_id ) : home_url( '/' );
}

/**
 * Страница записей блога.
 *
 * @return bool
 */
function konkord_is_news_posts_page() {
	return is_home() && ! is_front_page();
}

/**
 * Список рубрик, отсортированных по приоритету.
 *
 * @param array $args Доп. аргументы get_terms.
 * @return WP_Term[]
 */
function konkord_get_categories_by_priority( $args = array() ) {
	$terms = get_terms(
		wp_parse_args(
			$args,
			array(
				'taxonomy'   => 'category',
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		)
	);

	if ( is_wp_error( $terms ) || ! is_array( $terms ) ) {
		return array();
	}

	// 1, 2, 3… сначала; 0 в конце; при равенстве / все нули — по имени.
	usort(
		$terms,
		static function ( $a, $b ) {
			$pa = konkord_get_term_priority( $a );
			$pb = konkord_get_term_priority( $b );

			$a_unset = ( 0 === $pa );
			$b_unset = ( 0 === $pb );

			if ( $a_unset !== $b_unset ) {
				return $a_unset ? 1 : -1;
			}
			if ( $pa !== $pb ) {
				return $pa <=> $pb;
			}
			return strcasecmp( $a->name, $b->name );
		}
	);

	return $terms;
}

/**
 * На /novosti-i-akczii/ показываем только записи основной рубрики «Новости».
 *
 * @param WP_Query $query Query.
 */
function konkord_news_page_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( ! $query->is_home() ) {
		return;
	}
	$primary = konkord_get_primary_news_category();
	if ( $primary ) {
		$query->set( 'cat', (int) $primary->term_id );
	}
}
add_action( 'pre_get_posts', 'konkord_news_page_query' );

/**
 * /category/news/ → /novosti-i-akczii/ (без дубля).
 */
function konkord_redirect_primary_news_category() {
	if ( ! is_category() ) {
		return;
	}
	$primary = konkord_get_primary_news_category();
	if ( ! $primary ) {
		return;
	}
	$term = get_queried_object();
	if ( ! $term || (int) $term->term_id !== (int) $primary->term_id ) {
		return;
	}
	$url = konkord_get_news_page_url();
	if ( $url ) {
		wp_safe_redirect( $url, 301 );
		exit;
	}
}
add_action( 'template_redirect', 'konkord_redirect_primary_news_category' );

/**
 * Ссылка рубрики «Новости» ведёт на страницу записей.
 *
 * @param string  $url      Term URL.
 * @param WP_Term $term     Term.
 * @param string  $taxonomy Taxonomy.
 * @return string
 */
function konkord_primary_news_term_link( $url, $term, $taxonomy ) {
	if ( 'category' !== $taxonomy || ! $term instanceof WP_Term ) {
		return $url;
	}
	$primary = konkord_get_primary_news_category();
	if ( $primary && (int) $term->term_id === (int) $primary->term_id ) {
		$posts_url = konkord_get_news_page_url();
		if ( $posts_url ) {
			return $posts_url;
		}
	}
	return $url;
}
add_filter( 'term_link', 'konkord_primary_news_term_link', 10, 3 );

/**
 * Поле при создании рубрики.
 */
function konkord_term_priority_add_field() {
	?>
	<div class="form-field term-priority-wrap">
		<label for="konkord_term_priority">Приоритет</label>
		<input type="number" name="konkord_term_priority" id="konkord_term_priority" value="0" min="0" step="1">
		<p>Число задаёт порядок ссылок рубрик: сначала 1, потом 2, 3 и т.д. Без приоритета оставьте 0 — такие рубрики будут в конце (по алфавиту). Если приоритет ни у кого не указан — весь список по алфавиту.</p>
	</div>
	<?php
}
add_action( 'category_add_form_fields', 'konkord_term_priority_add_field' );

/**
 * Поле при редактировании рубрики.
 *
 * @param WP_Term $term Term.
 */
function konkord_term_priority_edit_field( $term ) {
	$priority = konkord_get_term_priority( $term );
	?>
	<tr class="form-field term-priority-wrap">
		<th scope="row"><label for="konkord_term_priority">Приоритет</label></th>
		<td>
			<input type="number" name="konkord_term_priority" id="konkord_term_priority"
				value="<?php echo esc_attr( (string) $priority ); ?>" min="0" step="1" class="small-text">
			<p class="description">Число задаёт порядок ссылок рубрик: сначала 1, потом 2, 3 и т.д. Без приоритета оставьте 0 — такие рубрики будут в конце (по алфавиту). Если приоритет ни у кого не указан — весь список по алфавиту.</p>
		</td>
	</tr>
	<?php
}
add_action( 'category_edit_form_fields', 'konkord_term_priority_edit_field' );

/**
 * Сохранение приоритета рубрики.
 *
 * @param int $term_id Term ID.
 */
function konkord_term_priority_save( $term_id ) {
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	if ( ! isset( $_POST['konkord_term_priority'] ) ) {
		return;
	}
	$priority = max( 0, (int) wp_unslash( $_POST['konkord_term_priority'] ) );
	update_term_meta( $term_id, KONKORD_TERM_PRIORITY_META, $priority );
}
add_action( 'created_category', 'konkord_term_priority_save' );
add_action( 'edited_category', 'konkord_term_priority_save' );

/**
 * Колонка в списке рубрик.
 *
 * @param array $columns Columns.
 * @return array
 */
function konkord_term_priority_column( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'name' === $key ) {
			$new['konkord_term_priority'] = 'Приоритет';
		}
	}
	if ( ! isset( $new['konkord_term_priority'] ) ) {
		$new['konkord_term_priority'] = 'Приоритет';
	}
	return $new;
}
add_filter( 'manage_edit-category_columns', 'konkord_term_priority_column' );

/**
 * @param string $content     Empty by default for custom cols.
 * @param string $column_name Column.
 * @param int    $term_id     Term ID.
 * @return string
 */
function konkord_term_priority_column_content( $content, $column_name, $term_id ) {
	if ( 'konkord_term_priority' !== $column_name ) {
		return $content;
	}
	return esc_html( (string) konkord_get_term_priority( $term_id ) );
}
add_filter( 'manage_category_custom_column', 'konkord_term_priority_column_content', 10, 3 );

/**
 * @param array $columns Sortable.
 * @return array
 */
function konkord_term_priority_sortable( $columns ) {
	$columns['konkord_term_priority'] = 'konkord_term_priority';
	return $columns;
}
add_filter( 'manage_edit-category_sortable_columns', 'konkord_term_priority_sortable' );

/**
 * Сортировка рубрик в админке по приоритету.
 *
 * @param array $clauses  SQL clauses.
 * @param array $taxonomies Taxonomies.
 * @param array $args     get_terms args.
 * @return array
 */
function konkord_term_priority_admin_clauses( $clauses, $taxonomies, $args ) {
	if ( ! is_admin() ) {
		return $clauses;
	}
	if ( empty( $args['orderby'] ) || 'konkord_term_priority' !== $args['orderby'] ) {
		return $clauses;
	}
	if ( ! in_array( 'category', (array) $taxonomies, true ) ) {
		return $clauses;
	}

	global $wpdb;
	$clauses['join'] .= " LEFT JOIN {$wpdb->termmeta} AS konkord_tpm ON (t.term_id = konkord_tpm.term_id AND konkord_tpm.meta_key = '" . esc_sql( KONKORD_TERM_PRIORITY_META ) . "') ";

	// 0 / NULL в конце при ASC; при DESC — наоборот.
	$asc = ! isset( $args['order'] ) || 'ASC' === strtoupper( (string) $args['order'] );
	if ( $asc ) {
		$order_sql = '(CASE WHEN konkord_tpm.meta_value IS NULL OR CAST(konkord_tpm.meta_value AS SIGNED) = 0 THEN 1 ELSE 0 END) ASC, CAST(COALESCE(konkord_tpm.meta_value, 0) AS SIGNED) ASC, t.name ASC';
	} else {
		$order_sql = '(CASE WHEN konkord_tpm.meta_value IS NULL OR CAST(konkord_tpm.meta_value AS SIGNED) = 0 THEN 1 ELSE 0 END) DESC, CAST(COALESCE(konkord_tpm.meta_value, 0) AS SIGNED) DESC, t.name DESC';
	}

	// WP_Term_Query склеивает "$orderby $order" — направления уже в orderby, order должен быть пустым.
	$clauses['orderby'] = 'ORDER BY ' . $order_sql;
	$clauses['order']   = '';

	return $clauses;
}
add_filter( 'terms_clauses', 'konkord_term_priority_admin_clauses', 10, 3 );

/**
 * Дозаполнить priority = 0 у рубрик без meta.
 */
function konkord_term_priority_backfill() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => false,
			'fields'     => 'ids',
		)
	);
	if ( is_wp_error( $terms ) ) {
		return;
	}
	foreach ( $terms as $term_id ) {
		$existing = get_term_meta( $term_id, KONKORD_TERM_PRIORITY_META, true );
		if ( $existing === '' || $existing === false ) {
			update_term_meta( (int) $term_id, KONKORD_TERM_PRIORITY_META, 0 );
		}
	}
}
add_action( 'load-edit-tags.php', 'konkord_term_priority_backfill' );
