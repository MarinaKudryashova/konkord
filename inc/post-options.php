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
			"page_title"  => "404",
			"menu_title"  => "404",
			"parent_slug" => "site_settings",
			"menu_slug"   => "site_settings_404",
		));
	}
}

add_action('init', 'custom_acf_options', 5);