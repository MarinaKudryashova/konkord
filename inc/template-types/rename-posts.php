<?php 
//события(блог, новости)
## заменим слово «записи» на «события»
add_filter('post_type_labels_post', 'rename_posts_labels');
function rename_posts_labels( $labels ){
	// заменять автоматически не пойдет например заменили: Запись = Статья, а в тесте получится так "Просмотреть статья"
	$new = array(
		'name'                  => _x( 'Новости', 'konkord' ),
		'singular_name'         => _x( 'Статья', 'konkord' ),
		'add_new'               => __( 'Добавить статья', 'konkord' ),
		'add_new_item'          => __( 'Добавить новое статья', 'konkord' ),
		'edit_item'             => __( 'Редактировать статья', 'konkord' ),
		'new_item'              => __( 'Новое статья', 'konkord' ),
		'view_item'             => __( 'Посмотреть статья', 'konkord' ),
		'search_items'          => __( 'Поиск статей', 'konkord' ),
		'not_found'             => __( 'Статей не найдено.', 'konkord' ),
		'parent_item_colon'     => '',
		'all_items'             => __( 'Все статьи', 'konkord' ),
		'archives'              => __( 'Архив статей', 'konkord' ),
		'menu_name'             => __( 'Новости', 'konkord' ),
		'name_admin_bar'        => __( 'Статья', 'konkord' ), // пункте "добавить"
	);

	return (object) array_merge( (array) $labels, $new );
}