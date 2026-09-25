<?php 
/**
 * Регистрация опцийных страниц ACF
 */

function custom_acf_options() {
	if (function_exists("acf_add_options_page")) {
		acf_add_options_page(array(
			"page_title" => __("Настройки сайта", 'konkord'),
			"menu_title" => __("Настройки сайта", 'konkord'),
			"menu_slug"  => "site_settings",
			"redirect"    => true,
			'position'      => 2,
		));
		acf_add_options_sub_page(array(
			"page_title"  => __("Контактная информация", 'konkord'),
			"menu_title"  => __("Контактная информация", 'konkord'),
			"parent_slug" => "site_settings",
			"menu_slug"   => "site_settings_contacts",
		));
		
		acf_add_options_sub_page(array(
			"page_title"  => __("Социальные сети", 'konkord'),
			"menu_title"  => __("Социальные сети", 'konkord'),
			"parent_slug" => "site_settings",
			"menu_slug"   => "site_settings_social",
		));

		acf_add_options_sub_page(array(
			"page_title"  => __("Отзывы", 'konkord'),
			"menu_title"  => __("Отзывы", 'konkord'),
			"parent_slug" => "site_settings",
			"menu_slug"   => "site_settings_reviews",
		));
		
		acf_add_options_sub_page(array(
			"page_title"  => __("Формы", 'konkord'),
			"menu_title"  => __("Формы", 'konkord'),
			"parent_slug" => "site_settings",
			"menu_slug"   => "site_settings_forms",
		));

		// acf_add_options_sub_page(array(
		// 	"page_title"  => __("Футер", 'konkord'),
		// 	"menu_title"  => __("Футер", 'konkord'),
		// 	"parent_slug" => "site_settings",
		// 	"menu_slug"   => "site_settings_footer",
		// ));

		acf_add_options_sub_page(array(
			"page_title"  => "Cookie",
			"menu_title"  => "Cookie",
			"parent_slug" => "site_settings",
			"menu_slug"   => "site_settings_cookie",
		));

		acf_add_options_sub_page(array(
			"page_title"  => __( 'Аналитика и верификация', 'konkord' ),
			"menu_title"  => __( 'Аналитика', 'konkord' ),
			"parent_slug" => "site_settings",
			"menu_slug"   => "site_settings_analytics",
		));

		acf_add_options_sub_page(array(
			"page_title"  => "404",
			"menu_title"  => "404",
			"parent_slug" => "site_settings",
			"menu_slug"   => "site_settings_404",
		));
	}
}

add_action('init', 'custom_acf_options', 5);

/**
 * Поля: Google/Yandex verification + ID Метрики.
 * Новая группа (не правит существующие ACF-группы из БД).
 */
function konkord_register_analytics_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_konkord_analytics',
			'title'                 => 'Аналитика и верификация',
			'fields'                => array(
				array(
					'key'          => 'field_konkord_google_site_verification',
					'label'        => 'Google site verification',
					'name'         => 'google_site_verification',
					'type'         => 'text',
					'instructions' => 'Только значение content (без тега meta). Пример: oGd70dZpMfZB1S9CGxRhrSZl1Weqg9fQPvMynbwjVUs',
					'required'     => 0,
				),
				array(
					'key'          => 'field_konkord_yandex_verification',
					'label'        => 'Yandex verification',
					'name'         => 'yandex_verification',
					'type'         => 'text',
					'instructions' => 'Только значение content. Пример: af4fb4693ef00e04',
					'required'     => 0,
				),
				array(
					'key'          => 'field_konkord_yandex_metrika_id',
					'label'        => 'Яндекс.Метрика — ID счётчика',
					'name'         => 'yandex_metrika_id',
					'type'         => 'text',
					'instructions' => 'Только числовой ID. Пример: 113006964. Код выводится в head, выполняется после согласия cookie.',
					'required'     => 0,
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'site_settings_analytics',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
		)
	);
}
add_action( 'acf/init', 'konkord_register_analytics_acf_fields' );

/**
 * Один раз подставить текущие коды, если поля ещё пустые.
 */
function konkord_seed_analytics_acf_defaults() {
	if ( ! function_exists( 'get_field' ) || ! function_exists( 'update_field' ) ) {
		return;
	}
	if ( get_option( 'konkord_analytics_seeded' ) ) {
		return;
	}

	$defaults = array(
		'field_konkord_google_site_verification' => array(
			'name'  => 'google_site_verification',
			'value' => 'oGd70dZpMfZB1S9CGxRhrSZl1Weqg9fQPvMynbwjVUs',
		),
		'field_konkord_yandex_verification'      => array(
			'name'  => 'yandex_verification',
			'value' => 'af4fb4693ef00e04',
		),
		'field_konkord_yandex_metrika_id'        => array(
			'name'  => 'yandex_metrika_id',
			'value' => '113006964',
		),
	);

	foreach ( $defaults as $key => $item ) {
		$current = get_field( $item['name'], 'option' );
		if ( $current === null || $current === false || $current === '' ) {
			update_field( $key, $item['value'], 'option' );
		}
	}

	update_option( 'konkord_analytics_seeded', 1, false );
}
add_action( 'acf/init', 'konkord_seed_analytics_acf_defaults', 20 );