<?php
/**
 * konkord functions and definitions
 * @package konkord
 * Author: Cosmo Design
 * Telegram: @cosmo_dsgn
 * Email: info@cosmo-design.com
 * Site: http://cosmo-design.com
 */

if ( ! defined( '_S_VERSION' ) ) {
	$theme = wp_get_theme();
	define( '_S_VERSION', $theme->get( 'Version' ) );
}

/**
 * Функцию для загрузки темы
 * 
 * Sets up theme defaults and registers support for various WordPress features.
*/

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function konkord_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_post_type_support( 'post', 'page-attributes' );
	add_post_type_support( 'page', array('excerpt') );
	register_nav_menus( [
		'header' => esc_html__("Main menu", 'konkord'),
		'footer' => esc_html__("Footer menu", 'konkord'),
		'footer_policies' => esc_html__("Policies", 'konkord'),
	] );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	// Add theme support for selective refresh for widgets.
	// add_theme_support( 'customize-selective-refresh-widgets' );
}
add_action( 'after_setup_theme', 'konkord_setup' );

/**
 * Функцию для загрузки переводов
 */
function konkord_load_textdomain() {
	load_theme_textdomain( 'konkord', get_template_directory() . '/languages' );
}

add_action( 'init', 'konkord_load_textdomain' );


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
// function konkord_content_width() {
// 	$GLOBALS['content_width'] = apply_filters( 'konkord_content_width', 640 );
// }
// add_action( 'after_setup_theme', 'konkord_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
// function konkord_widgets_init() {
// 	register_sidebar(
// 		array(
// 			'name'          => esc_html__( 'Sidebar', 'konkord' ),
// 			'id'            => 'sidebar-1',
// 			'description'   => esc_html__( 'Add widgets here.', 'konkord' ),
// 			'before_widget' => '<section id="%1$s" class="widget %2$s">',
// 			'after_widget'  => '</section>',
// 			'before_title'  => '<h2 class="widget-title">',
// 			'after_title'   => '</h2>',
// 		)
// 	);
// }
// add_action( 'widgets_init', 'konkord_widgets_init' );

/**
 * ОТКЛЮЧЕНИЕ КОММЕНТАРИЕВ ПОЛНОСТЬЮ
 */
function healthypaw_disable_comments() {
    // 1. Отключаем поддержку комментариев
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
    
    // 2. Закрываем все комментарии
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);
    
    // 3. Скрываем существующие
    add_filter('comments_array', '__return_empty_array', 10, 2);
    
    // 4. Удаляем из админки
    add_action('admin_menu', function() {
        remove_menu_page('edit-comments.php');
    });
    
    // 5. Удаляем из админ-бара
    add_action('wp_before_admin_bar_render', function() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
    });
}

add_action('after_setup_theme', 'healthypaw_disable_comments');

/**
 * THEME STYLES & SCRIPTS
 */
function konkord_styles_and_scripts() {
	$css_path = get_template_directory_uri() . '/css/';
	$js_path = get_template_directory_uri() . '/js/';
	$ver = defined('_S_VERSION') ? _S_VERSION : wp_get_theme()->get('Version');
	if ( defined('WP_DEBUG') && WP_DEBUG ) {
			$ver = $ver . '.' . time();
	}

	// основные стили темы
	wp_enqueue_style( 'konkord-style', get_stylesheet_uri(), array(), $ver );

	// дополнительные стили
	wp_enqueue_style( 'css-vendor', $css_path . 'vendor.css', array(), $ver); // стили (библиотеки)
	wp_enqueue_style( 'css-main', $css_path . 'main.css', array('css-vendor'), $ver); // основные стили темы

	// скрипт навигации	
	wp_enqueue_script( 'konkord-navigation', get_template_directory_uri() . '/js/navigation.js', array(), $ver, true );

	// основные скрипты темы	
	wp_enqueue_script( 'js-main', $js_path . 'main.min.js', array(), $ver, array( 'in_footer' => true, 'strategy' => 'defer'));
	
	// Локализация для JS
	wp_localize_script('konkord-main', 'konkord_ajax', array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('konkord_nonce'),
		'theme_url' => get_template_directory_uri()
	));
}
add_action( 'wp_enqueue_scripts', 'konkord_styles_and_scripts' );

/**
 * THEME EXTRAS
 */
require_once get_template_directory() . '/inc/thumbnail.php'; // Подключаем функционал управления миниатюрами записей из общего списка записей в админ-панели WordPress
require_once get_template_directory() . '/inc/theme-svg.php'; // Добавляет поддержку SVG изображений в медиабиблиотеку
require_once get_template_directory() . '/inc/disable_default_image_sizes.php'; // Отключаем только конкретные стандартные размеры изображений
require_once get_template_directory() . '/inc/the_picture_element.php'; // Отключаем только конкретные стандартные размеры изображений
require_once get_template_directory() . '/inc/post-options.php';
require_once get_template_directory() . '/inc/BEM_Walker_Nav_Menu.php';
require_once get_template_directory() . '/inc/Footer_Menu_Walker.php';

// require_once get_template_directory() . '/inc/theme-form-cf7.php';

/**
 * Post types & taxonomies
 */
require_once get_template_directory() . '/inc/template-types/type-faq.php'; // Подключаем функционал кастомного типа записи FAQ
require_once get_template_directory() . '/inc/template-types/type-services.php'; // Подключаем функционал кастомного типа записи Услуги
require_once get_template_directory() . '/inc/template-types/type-employees.php'; // Подключаем функционал кастомного типа записи Сотрудники
require_once get_template_directory() . '/inc/template-types/rename-posts.php'; // Переименовываем стандартный тип записи "Записи" в "Новости"/ "Блог"


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}