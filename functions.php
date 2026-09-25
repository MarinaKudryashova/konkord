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
}
add_action( 'after_setup_theme', 'konkord_setup' );

/**
 * Site Icon из «Настройки → Общие» 
 */
remove_action( 'wp_head', 'wp_site_icon', 99 );

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
function konkord_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'konkord_content_width', 800 );
}
add_action( 'after_setup_theme', 'konkord_content_width', 0 );


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
 * Версия ассета = время файла. После замены CSS/JS на сервере URL меняется,
 * телефоны качают заново. HTML с новым ?ver= появится после сброса Super Cache.
 */
function konkord_asset_ver( $relative ) {
	$path = get_template_directory() . '/' . ltrim( $relative, '/' );
	if ( is_readable( $path ) ) {
		return (string) filemtime( $path );
	}
	return defined( '_S_VERSION' ) ? _S_VERSION : '1';
}

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

	if ( is_admin_bar_showing() ) {
		wp_enqueue_style( 'konkord-style', get_stylesheet_uri(), array(), $ver );
	}

	wp_enqueue_style( 'css-vendor', $css_path . 'vendor.css', array(), konkord_asset_ver( 'css/vendor.css' ) );
	wp_enqueue_style( 'css-main', $css_path . 'main.css', array( 'css-vendor' ), konkord_asset_ver( 'css/main.css' ) );

	// скрипт навигации	
	wp_enqueue_script( 'konkord-navigation', get_template_directory_uri() . '/js/navigation.js', array(), konkord_asset_ver( 'js/navigation.js' ), true );
	if (is_page_template('page-contacts.php')) {
		wp_enqueue_script('js-maps', 'https://api-maps.yandex.ru/2.1/?apikey=ваш API-ключ&lang=ru_RU', array(), $ver, 'defer');
	}

	if ( is_front_page() ) {
		wp_enqueue_script(
			'promo-video',
			$js_path . 'promo-video.min.js',
			array(),
			konkord_asset_ver( 'js/promo-video.min.js' ),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}

	// основные скрипты темы	
	wp_enqueue_script( 'js-main', $js_path . 'main.min.js', array(), konkord_asset_ver( 'js/main.min.js' ), array( 'in_footer' => true, 'strategy' => 'defer'));

	// AOS только ≥768: Super Cache не режет HTML по UA, решаем в matchMedia.
	$aos_src = add_query_arg( 'ver', konkord_asset_ver( 'js/aos.min.js' ), $js_path . 'aos.min.js' );
	wp_add_inline_script(
		'js-main',
		'if(window.matchMedia("(min-width:768px)").matches){var s=document.createElement("script");s.src=' . wp_json_encode( $aos_src ) . ';s.defer=true;document.body.appendChild(s);}',
		'after'
	);
	wp_enqueue_script(
		'js-lazy-iframe',
		$js_path . 'lazy-iframe.js',
		array(),
		konkord_asset_ver( 'js/lazy-iframe.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	
	// Локализация для JS
	wp_localize_script('js-main', 'konkord_ajax', array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('konkord_nonce'),
		'theme_url' => get_template_directory_uri(),

		// Добавляем параметры для бесконечной загрузки услуг
		'services'   => array(
				'per_page'    => get_services_per_page(),
				'initial_page'=> 1,
				'max_pages'   => 0, // будет заполнено на странице услуг
		)
	));

	// jQuery-скрипт только на каталоге услуг (тема на фронте без jQuery)
	if ( is_post_type_archive( 'services' ) || is_singular( 'services' ) || is_tax( 'services_category' ) ) {
		wp_enqueue_script(
			'js-services-load-more',
			$js_path . 'services-load-more.js',
			array( 'jquery' ),
			konkord_asset_ver( 'js/services-load-more.js' ),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'konkord_styles_and_scripts' );

/**
 * Постер промо на главной (LCP).
 */
function konkord_get_promo_poster_url() {
	static $poster = null;
	if ( null !== $poster ) {
		return $poster;
	}

	$poster = '';
	if ( ! is_front_page() || ! function_exists( 'get_image_versions' ) ) {
		return $poster;
	}

	$page_id = (int) get_queried_object_id();
	if ( ! $page_id ) {
		$page_id = (int) get_option( 'page_on_front' );
	}
	if ( ! $page_id ) {
		return $poster;
	}

	$promo_img_url = get_field( 'promo_bgimg', $page_id );
	if ( ! $promo_img_url ) {
		return $poster;
	}

	$promo_img = get_image_versions( $promo_img_url );
	if ( ! empty( $promo_img['webp_1x'] ) ) {
		$poster = $promo_img['webp_1x'];
	} elseif ( ! empty( $promo_img['original_1x'] ) ) {
		$poster = $promo_img['original_1x'];
	}

	return $poster;
}

/**
 * Web app manifest (PWA icons).
 */
add_action( 'wp_footer', function () {
	printf(
		'<link rel="manifest" href="%s/favicon/site.webmanifest">' . "\n",
		esc_url( get_template_directory_uri() )
	);
}, 1 );

/**
 * AOS CSS только ≥768 (как JS). На телефоне файл не качается.
 * Инлайн-сброс перебивает старый закэшированный aos.css.
 */
add_action( 'wp_head', function () {
	echo '<style id="aos-mobile-visible">@media (max-width:767px){[data-aos]{opacity:1!important;transform:none!important;pointer-events:auto!important}}</style>' . "\n";

	$aos_css = get_template_directory() . '/css/aos.css';
	if ( ! is_readable( $aos_css ) ) {
		return;
	}

	$href = add_query_arg( 'ver', (string) filemtime( $aos_css ), get_template_directory_uri() . '/css/aos.css' );
	echo '<script>if(window.matchMedia("(min-width:768px)").matches){var l=document.createElement("link");l.rel="stylesheet";l.href=' . wp_json_encode( $href ) . ';document.head.appendChild(l);}</script>' . "\n";
}, 2 );

/**
 * CF7 / Belingo: не блокируют первый экран.
 */
add_filter( 'style_loader_tag', function ( $html, $handle, $href ) {
	$deferred = array( 'contact-form-7', 'belingo-geo' );
	if ( ! in_array( $handle, $deferred, true ) ) {
		return $html;
	}

	$html = str_replace( "media='all'", "media='print' onload=\"this.media='all'\"", $html );
	$html = str_replace( 'media="all"', "media=\"print\" onload=\"this.media='all'\"", $html );

	if ( $href ) {
		$html .= sprintf( '<noscript><link rel="stylesheet" href="%s"></noscript>' . "\n", esc_url( $href ) );
	}

	return $html;
}, 10, 3 );

/**
 * Заглушкка для изображений
 */
function get_placeholder_image() {
    return [
		'original_1x' => get_template_directory_uri() . '/img/placeholder.png',
		'webp_1x' => get_template_directory_uri() . '/img/placeholder.webp',
		'alt' => 'Изображение отсутствует',
    ];
}

add_action('wp_default_scripts', function ($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            array('jquery-migrate')
        );
    }
});

/**
 * jQuery в head блокирует отрисовку. defer после разбора HTML.
 * Belingo тоже defer: иначе его скрипт в футере выполнится раньше jQuery.
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( is_admin() ) {
		return;
	}
	foreach ( array( 'jquery', 'jquery-core', 'belingo-geo-scripts' ) as $handle ) {
		if ( wp_script_is( $handle, 'registered' ) ) {
			wp_script_add_data( $handle, 'strategy', 'defer' );
		}
	}
}, 100 );


/**
 * THEME EXTRAS
 */
require_once get_template_directory() . '/inc/thumbnail.php'; // Подключаем функционал управления миниатюрами записей из общего списка записей в админ-панели WordPress
require_once get_template_directory() . '/inc/theme-svg.php'; // Добавляет поддержку SVG изображений в медиабиблиотеку
require_once get_template_directory() . '/inc/media-library-filesize.php'; // Колонка «Размер файла» в медиабиблиотеке
require_once get_template_directory() . '/inc/category-priority.php'; // Приоритет рубрик новостей: колонка и сортировка ссылок
require_once get_template_directory() . '/inc/disable_default_image_sizes.php'; // Отключаем только конкретные стандартные размеры изображений
require_once get_template_directory() . '/inc/the_picture_element.php'; // Отключаем только конкретные стандартные размеры изображений
require_once get_template_directory() . '/inc/post-options.php';
require_once get_template_directory() . '/inc/BEM_Walker_Nav_Menu.php';
require_once get_template_directory() . '/inc/Footer_Menu_Walker.php';
require_once get_template_directory() . '/inc/theme-form-cf7.php';
require_once get_template_directory() . '/inc/geo-utils.php';
require_once get_template_directory() . '/inc/defer-analytics.php';
require_once get_template_directory() . '/inc/site-verification-analytics.php'; // Google/Yandex verification + Metrika (consent-gated)

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

/**
 * AJAX обработчик для подгрузки услуг
 */
add_action('wp_ajax_load_more_services', 'load_more_services_callback');
add_action('wp_ajax_nopriv_load_more_services', 'load_more_services_callback');

function load_more_services_callback() {
    // Проверка nonce
    if (!wp_verify_nonce($_POST['nonce'], 'konkord_nonce')) {
        wp_send_json_error('Invalid nonce');
        return;
    }

    $page = intval($_POST['page']);
    $cat_slug = sanitize_text_field($_POST['cat_slug'] ?? '');
    $page_id = intval($_POST['page_id'] ?? 0);
    $per_page = intval(get_services_per_page());

    $args = array(
        'post_type' => 'services',
        'post_status' => 'publish',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );

    if (!empty($cat_slug)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'services_category',
                'field' => 'slug',
                'terms' => $cat_slug
            )
        );
    }

    $query = new WP_Query($args);
    $html = '';

    if ($query->have_posts()) {
        ob_start();
        $index = ( $page - 1 ) * $per_page;
        while ( $query->have_posts() ) {
            $query->the_post();
            $delay = ( $index % 3 ) * 80;
            echo '<li class="sec-services__item" data-aos="fade-up" data-aos-delay="' . (int) $delay . '">';
            render_service_item( get_the_ID(), $index );
            echo '</li>';
            $index++;
        }
        $html = ob_get_clean();
    }
    wp_reset_postdata();

    wp_send_json_success(array(
        'html' => $html,
        'max_pages' => $query->max_num_pages,
        'current_page' => $page
    ));
}

/**
 * Функция для рендеринга одного элемента услуги
 */
function render_service_item($post_id, $index = 0) {
    $title = get_the_title($post_id);
    $url = get_permalink($post_id) ?: '#';
    $thumbnail_id = get_post_thumbnail_id( $post_id );
    $img_data     = $thumbnail_id ? get_image_versions( $thumbnail_id, 'large' ) : get_placeholder_image();
    $img_mobile   = konkord_resolve_mobile_sources( $img_data );
    $loading_attr = ( $index >= 3 ) ? 'loading="lazy"' : '';
    $priority_attr = ( $index < 2 ) ? ' fetchpriority="high"' : '';
    ?>
		<a class="service-card" href="<?php echo esc_url($url); ?>"
				aria-label="Перейти в услугу «<?php echo esc_html($title); ?>»">
				<h3 class="service-card__title"><?php echo esc_html($title); ?></h3>
				<picture class="service-card__img">
						<?php konkord_picture_mobile_sources( $img_mobile ); ?>
						<?php if ( ! empty( $img_data['webp_1x'] ) ) : ?>
						<source srcset="<?php echo esc_url($img_data['webp_1x']); ?>" type="image/webp">
						<?php endif; ?>
						<img <?php echo $loading_attr; ?><?php echo $priority_attr; ?> src="<?php echo esc_url($img_data['original_1x']); ?>" width="360" height="354" alt="<?php echo esc_html($title); ?>">
				</picture>
		</a>
    <?php
}

add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Редиректит author-архивы на главную.
 */
add_action( 'template_redirect', function () {
	if ( is_author() ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
} );
