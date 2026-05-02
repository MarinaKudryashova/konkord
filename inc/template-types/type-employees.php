<?php 

/* Создание кастомного типа записи Сотрудники */
add_action( 'init', 'employees_register_post_types' );

function employees_register_post_types(){

	$labels = array(
		'name'                  => _x( 'Сотрудники', 'Post Type General Name', 'konkord' ),
		'singular_name'         => _x( 'Сотрудник', 'Post Type Singular Name', 'konkord' ),
		'menu_name'             => __( 'Сотрудники', 'konkord' ),
		'name_admin_bar'        => __( 'Сотрудники', 'konkord' ),
		'add_new_item'          => __( 'Добавить нового сотрудника', 'konkord' ),
		'add_new'               => __( 'Добавить нового', 'konkord' ),
		'new_item'              => __( 'Новый сотрудник', 'konkord' ),
		'edit_item'             => __( 'Изменить сотрудника', 'konkord' ),
		'view_item'             => __( 'Посмотреть сотрудника', 'konkord' ),
		'view_items'            => __( 'Посмотреть всех сотрудников', 'konkord' ),
		'search_items'          => __( 'Поиск сотрудников', 'konkord' ),
		'not_found'             => __( 'Не найдено', 'konkord' ),
		'not_found_in_trash'    => __( 'Не найдено в удаленных', 'konkord' ),
	);
	
	$args = array(
		'label'                 => __( 'Сотрудники', 'konkord' ),
		'labels'                => $labels,
		'description'           => __( 'Список сотрудников компании', 'konkord' ),
		'public'                => true,
		'publicly_queryable'    => false,  // Не показываем на сайте отдельно
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'show_in_rest'          => true,   // Включено для Gutenberg
		'menu_position'         => 5,      // Позиция в меню (после лицензий)
		'menu_icon'             => 'dashicons-businessperson', // Иконка для сотрудников
		'capability_type'       => 'post',
		'supports'              => array('title', 'thumbnail', 'excerpt', 'editor', 'page-attributes'),
		'taxonomies'            => array(),
		'has_archive'           => false,
		'can_export'            => true,
	);
	
	register_post_type( 'employees', $args );
}

/* Добавляем колонку "Порядок" в админке */
add_filter( 'manage_employees_posts_columns', 'employees_add_order_column' );
function employees_add_order_column( $columns ) {
    $new_columns = array();
    foreach ( $columns as $key => $value ) {
        $new_columns[$key] = $value;
        if ( $key === 'title' ) {
            $new_columns['menu_order'] = __( 'Порядок', 'konkord' );
        }
    }
    return $new_columns;
}

add_action( 'manage_employees_posts_custom_column', 'employees_show_order_column', 10, 2 );
function employees_show_order_column( $column_name, $post_id ) {
    if ( $column_name === 'menu_order' ) {
        echo get_post_field( 'menu_order', $post_id );
    }
}

/* Делаем колонку "Порядок" сортируемой */
add_filter( 'manage_edit-employees_sortable_columns', 'employees_make_order_column_sortable' );
function employees_make_order_column_sortable( $columns ) {
    $columns['menu_order'] = 'menu_order';
    return $columns;
}

/* Добавляем колонку "Должность" в админке (опционально) */
add_filter( 'manage_employees_posts_columns', 'employees_add_position_column', 15 );
function employees_add_position_column( $columns ) {
    $new_columns = array();
    foreach ( $columns as $key => $value ) {
        $new_columns[$key] = $value;
        if ( $key === 'title' ) {
            $new_columns['position'] = __( 'Должность', 'konkord' );
        }
    }
    return $new_columns;
}

add_action( 'manage_employees_posts_custom_column', 'employees_show_position_column', 10, 2 );
function employees_show_position_column( $column_name, $post_id ) {
    if ( $column_name === 'position' ) {
        // Получаем должность из произвольного поля (если используете ACF)
        $position = get_post_meta( $post_id, 'employee_position', true );
        echo !empty( $position ) ? esc_html( $position ) : '—';
    }
}

/* Добавляем колонку с фото (миниатюрой) */
add_filter( 'manage_employees_posts_columns', 'employees_add_thumbnail_column', 10 );
function employees_add_thumbnail_column( $columns ) {
    $new_columns = array();
    foreach ( $columns as $key => $value ) {
        if ( $key === 'cb' ) {
            $new_columns[$key] = $value;
            $new_columns['thumbnail'] = __( 'Фото', 'konkord' );
        } else {
            $new_columns[$key] = $value;
        }
    }
    return $new_columns;
}

add_action( 'manage_employees_posts_custom_column', 'employees_show_thumbnail_column', 10, 2 );
function employees_show_thumbnail_column( $column_name, $post_id ) {
    if ( $column_name === 'thumbnail' ) {
        if ( has_post_thumbnail( $post_id ) ) {
            echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
        } else {
            echo '—';
        }
    }
}