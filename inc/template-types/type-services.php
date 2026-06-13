<?php 

// Услуги services
add_action( 'init', 'services_register_post_types' );
add_action( 'init', 'register_services_features_taxonomy' );
add_action( 'init', 'theme_register_services_category');

// Register new Taxonomy Категории услуг
function theme_register_services_category(){
	
	$labels = array(
		'name'              => _x( 'Категории услуг', 'taxonomy general name', 'konkord' ),
		'singular_name'     => _x( 'Категория услуги', 'taxonomy singular name', 'konkord' ),
		'search_items'      => 'Поиск категории',
		'all_items'         => 'Все категории',
		'view_item '        => 'Посмотреть категорию',
		'edit_item'         => 'Редактировать категорию',
		'update_item'       => 'Обновить категорию',
		'add_new_item'      => 'Добавить новую категорию',
		'new_item_name'     => 'Новая категория',
		'menu_name'         => 'Категории услуг',
	);
	
	$args = array (
		'label'                 => 'Категории услуг', 
		'labels'                => $labels,
		'description'           => '', 
		'public'                => true,
		'hierarchical'			=> true,
		'show_in'     		    => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'show_admin_column'     => true,
		'show_in_quick_edit'	=> true,
		'rewrite'               => array(
				'slug' => 'services-category', // ИЗМЕНЕНО: уникальный slug для таксономии
				'with_front' => false,
				'hierarchical' => true
		),
	);
	
	register_taxonomy( 'services_category', [ 'services' ], $args );
}

// Register new Taxonomy Характеристики услуг
function register_services_features_taxonomy() {
	$labels = array(
		'name'              => _x( 'Характеристики услуг', 'taxonomy general name', 'konkord' ),
		'singular_name'     => _x( 'Характеристика услуги', 'taxonomy singular name', 'konkord' ),
		'search_items'      => 'Поиск характеристик',
		'all_items'         => 'Все характеристики',
		'view_item'         => 'Посмотреть характеристику',
		'edit_item'         => 'Редактировать характеристику',
		'update_item'       => 'Обновить характеристику',
		'add_new_item'      => 'Добавить новую характеристику',
		'new_item_name'     => 'Новая характеристика',
		'menu_name'         => 'Характеристики услуг',
		'popular_items'     => 'Популярные характеристики',
		'separate_items_with_commas' => 'Разделяйте характеристики запятыми',
		'add_or_remove_items' => 'Добавить или удалить характеристики',
		'choose_from_most_used' => 'Выберите из часто используемых',
		'not_found'         => 'Характеристики не найдены',
	);

	$args = array(
		'label'                 => 'Характеристики услуг',
		'labels'                => $labels,
		'public'                => true,
		'publicly_queryable'    => true,
		'hierarchical'          => false, 
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'show_in_rest'          => true,
		'show_tagcloud'         => true,
		'show_in_quick_edit'    => true,
		'show_admin_column'     => true,
		'rewrite'               => array(
			'slug' => 'services-feature', // уникальный slug для характеристик
			'with_front' => false,
		),
	);

	register_taxonomy( 'services_feature', array( 'services' ), $args );
}

// Create new Custom Post Type
function services_register_post_types(){

	$labels = array(
		'name'                  => _x( 'Услуги', 'Post Type General Name', 'konkord' ),
		'singular_name'         => _x( 'Услуга', 'Post Type Singular Name', 'konkord' ),
		'menu_name'             => __( 'Услуги', 'konkord' ),
		'name_admin_bar'        => __( 'Услуга', 'konkord' ),
		'add_new_item'          => __( 'Добавить новую услугу', 'konkord' ),
		'add_new'               => __( 'Добавить услугу', 'konkord' ),
		'new_item'              => __( 'Новая услуга', 'konkord' ),
		'edit_item'             => __( 'Редактировать услугу', 'konkord' ),
		'view_item'             => __( 'Посмотреть услугу', 'konkord' ),
		'view_items'            => __( 'Посмотреть все услуги', 'konkord' ),
		'search_items'          => __( 'Поиск услуги', 'konkord' ),
		'not_found'             => __( 'Не найдено', 'konkord' ),
		'not_found_in_trash'    => __( 'В корзине не найдено', 'konkord' ),
		'uploaded_to_this_item' => __( 'Загружено изображение услуги', 'konkord' ),
		'featured_image'        => __( 'Изображение услуги', 'konkord' ),
		'set_featured_image'    => __( 'Установить изображение услуги', 'konkord' ),
		'remove_featured_image' => __( 'Удалить изображение услуги', 'konkord' ),
		'use_featured_image'    => __( 'Использовать как изображение услуги', 'konkord' ),
	);
  
	$args = array(
		'label'                 => __( 'Услуги', 'konkord' ),
		'labels'                => $labels,
		'description'           => __( 'Все услуги', 'konkord' ),
		'public'                => true,
		'publicly_queryable'    => true,
		'rewrite' 				=> array(
			'slug' => 'services',
			'with_front' => false,
			'pages' => true,
		),
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'menu_position'         => 4,
		'menu_icon'             => 'dashicons-book',
		'supports'              => array('title', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes', 'editor'),
		'taxonomies'            => array('services_category', 'services_feature'),
		'has_archive'           => 'services',
	);
	
	register_post_type( 'services', $args );
}

// Добавление фильтра по категориям в админке
function services_true_taxonomy_filter() {
	global $typenow;
	
	if( $typenow == 'services' ) {
		$taxes = array('services_category');
		
		foreach ($taxes as $tax) {
			$current_tax = isset( $_GET[$tax] ) ? $_GET[$tax] : '';
			$tax_obj = get_taxonomy($tax);
			$tax_name = mb_strtolower($tax_obj->labels->name);
			$terms = get_terms(array(
				'taxonomy' => $tax,
				'hide_empty' => false,
			));
			
			if(count($terms) > 0) {
				echo "<select name='$tax' id='$tax' class='postform'>";
				echo "<option value=''>Все $tax_name</option>";
				
				foreach ($terms as $term) {
					echo '<option value='. $term->slug . (($current_tax == $term->slug) ? ' selected="selected"' : '') . '>' . $term->name .' (' . $term->count .')</option>'; 
				}
				echo "</select>";
			}
		}
	}
}
 
add_action( 'restrict_manage_posts', 'services_true_taxonomy_filter' );