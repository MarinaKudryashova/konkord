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
				'slug' => 'services-category',
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
			'slug' => 'services-feature',
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
		'has_archive'           => false,
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

/**
 * ==========================================
 * НАСТРОЙКИ СТРАНИЦЫ ЗАПИСЕЙ УСЛУГ
 * ==========================================
 */

/**
 * 1. Добавляем подменю "Настройки" в CPT Услуги
 */
add_action('admin_menu', 'add_services_settings_submenu');
function add_services_settings_submenu() {
    add_submenu_page(
        'edit.php?post_type=services',
        'Настройки услуг',
        'Настройки',
        'manage_options',
        'services-settings',
        'services_settings_page_callback'
    );
}

/**
 * 2. Страница настроек услуг
 */
function services_settings_page_callback() {
    if(isset($_POST['submit']) && check_admin_referer('services_settings')) {
        update_option('page_for_services', absint($_POST['page_for_services']));
        update_option('services_per_page', absint($_POST['services_per_page']));
        echo '<div class="notice notice-success"><p>Настройки сохранены!</p></div>';
    }
    
    $page_for_services = get_option('page_for_services', 0);
    $services_per_page = get_option('services_per_page', 12);
    ?>
    <div class="wrap">
        <h1>Настройки услуг</h1>
        
        <div class="notice notice-info">
            <p><strong>Как настроить:</strong> Создайте страницу &rarr; назначьте ей шаблон "Каталог услуг" &rarr; выберите её выше &rarr; сохраните настройки &rarr; обновите постоянные ссылки.</p>
        </div>
        
        <form method="post" action="">
            <?php wp_nonce_field('services_settings'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="page_for_services">Страница записей услуг</label>
                    </th>
                    <tr>
                        <?php
                        wp_dropdown_pages(array(
                            'name' => 'page_for_services',
                            'id' => 'page_for_services',
                            'selected' => $page_for_services,
                            'show_option_none' => '— Выберите страницу —',
                            'option_none_value' => '0',
                            'post_type' => 'page',
                            'sort_column' => 'post_title'
                        ));
                        ?>
                        <p class="description">Страница, на которой будут отображаться все услуги</p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="services_per_page">Услуг на странице</label>
                    </th>
                    <td>
                        <input type="number" 
                               name="services_per_page" 
                               id="services_per_page" 
                               value="<?php echo $services_per_page; ?>" 
                               min="1"
                               max="50"
                               class="small-text">
                        <p class="description">Количество услуг, отображаемых на одной странице</p>
                    </td>
                </tr>
             </table>
            
            <p class="submit">
                <input type="submit" name="submit" class="button button-primary" value="Сохранить настройки">
            </p>
        </form>
    </div>
    <?php
}

/**
 * 3. Добавляем настройки в кастомайзер
 */
add_action('customize_register', 'customizer_services_settings');
function customizer_services_settings($wp_customize) {
    $wp_customize->add_section('services_archive_section', array(
        'title'    => 'Страница записей услуг',
        'priority' => 100,
    ));
    
    $wp_customize->add_setting('page_for_services', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'type'              => 'option',
    ));
    
    $wp_customize->add_control('page_for_services', array(
        'label'       => 'Страница записей услуг',
        'section'     => 'services_archive_section',
        'type'        => 'dropdown-pages',
    ));
    
    $wp_customize->add_setting('services_per_page', array(
        'default'           => 12,
        'sanitize_callback' => 'absint',
        'type'              => 'option',
    ));
    
    $wp_customize->add_control('services_per_page', array(
        'label'       => 'Услуг на странице',
        'section'     => 'services_archive_section',
        'type'        => 'number',
        'input_attrs' => array('min' => 1, 'max' => 50),
    ));
}

/**
 * 4. Вспомогательные функции
 */
function get_services_page_id() {
    return absint(get_option('page_for_services', 0));
}

function get_services_per_page() {
    return absint(get_option('services_per_page', 12));
}

/**
 * 5. Форсируем использование archive-services.php для страницы услуг
 */
add_filter('template_include', 'force_archive_template_for_services');
function force_archive_template_for_services($template) {
    $services_page_id = get_services_page_id();
    
    if($services_page_id && is_page($services_page_id)) {
        $archive_template = locate_template('archive-services.php');
        if($archive_template) {
            return $archive_template;
        }
    }
    
    return $template;
}

/**
 * 6. Перенаправляем /services на выбранную страницу
 */
add_action('template_redirect', 'redirect_services_archive_to_page');
function redirect_services_archive_to_page() {
    if(is_post_type_archive('services')) {
        $services_page_id = get_services_page_id();
        if($services_page_id) {
            wp_redirect(get_permalink($services_page_id), 301);
            exit;
        }
    }
}

/**
 * 7. Добавляем подпись "— Страница записей услуг" в списке страниц
 */
add_filter('display_post_states', 'add_services_page_state', 10, 2);
function add_services_page_state($post_states, $post) {
    $services_page_id = get_services_page_id();
    
    if($services_page_id && $post->ID == $services_page_id) {
        $post_states['page_for_services'] = 'Страница записей услуг';
    }
    
    return $post_states;
}

/**
 * 8. Показываем уведомление при редактировании архивной страницы
 */
add_action('admin_notices', 'services_page_edit_notice');
function services_page_edit_notice() {
    global $post;
    
    if(!$post) return;
    
    $services_page_id = get_services_page_id();
    
    if($services_page_id && $post->ID == $services_page_id) {
        ?>
        <div class="notice notice-warning">
            <p>
                <strong>⚠️ Внимание!</strong> 
                Вы редактируете страницу, которая выбрана как <strong>«Страница записей услуг»</strong>.
                На этой странице будут автоматически отображаться все добавленные услуги.
            </p>
            <p>
                🔧 Управлять выводом услуг можно в 
                <a href="<?php echo admin_url('edit.php?post_type=services&page=services-settings'); ?>">настройках услуг</a>.
            </p>
        </div>
        <?php
    }
}

/**
 * 9. Скрываем секцию "Атрибуты страницы" для страницы услуг
 */

add_action('admin_head-post.php', 'hide_page_attributes_for_services');
add_action('admin_head-post-new.php', 'hide_page_attributes_for_services');
function hide_page_attributes_for_services() {
    global $post;
    
    if(!$post) return;
    
    $services_page_id = get_services_page_id();
    
    if($services_page_id && $post->ID == $services_page_id) {
        ?>
        <style>
            /* Скрываем всю секцию "Атрибуты страницы" */
            .postbox-container .postbox:has(.post-attributes-label-wrapper),
            #pageparentdiv,
            .editor-post-status .components-panel__row:has(.editor-post-parent) {
                display: none !important;
            }
        </style>
        <?php
    }
}

/**
 * 10. Принудительно устанавливаем шаблон для страницы услуг
 */
add_action('save_post', 'force_template_for_services_page', 10, 2);
function force_template_for_services_page($post_id, $post) {
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if(!current_user_can('edit_post', $post_id)) return;
    if($post->post_type !== 'page') return;
    
    $services_page_id = get_services_page_id();
    
    if($services_page_id && $post_id == $services_page_id) {
        update_post_meta($post_id, '_wp_page_template', 'default');
    }
}

/**
 * 11. Добавляем ЧПУ для категорий на странице каталога
 */
add_action('init', 'add_pretty_services_category_rewrite_rules');
function add_pretty_services_category_rewrite_rules() {
    $services_page_id = get_services_page_id();
    
    if($services_page_id) {
        $page_slug = get_post_field('post_name', $services_page_id);
        
        if($page_slug) {
            add_rewrite_tag('%services_category%', '([^&]+)');
            
            // /katalog/etiketki/
            add_rewrite_rule(
                '^' . $page_slug . '/([^/]+)/?$',
                'index.php?page_id=' . $services_page_id . '&services_category=$matches[1]',
                'top'
            );
            
            // /katalog/etiketki/page/2/
            add_rewrite_rule(
                '^' . $page_slug . '/([^/]+)/page/([0-9]+)/?$',
                'index.php?page_id=' . $services_page_id . '&services_category=$matches[1]&paged=$matches[2]',
                'top'
            );
            
            // /katalog/page/2/
            add_rewrite_rule(
                '^' . $page_slug . '/page/([0-9]+)/?$',
                'index.php?page_id=' . $services_page_id . '&paged=$matches[1]',
                'top'
            );
        }
    }
}

/**
 * 12. Добавляем services_category в query vars
 */
add_filter('query_vars', 'add_services_category_query_var');
function add_services_category_query_var($vars) {
    $vars[] = 'services_category';
    return $vars;
}

/**
 * 13. Перенаправляем стандартные страницы категорий на ЧПУ
 */
add_action('template_redirect', 'redirect_services_category_to_pretty_url');
function redirect_services_category_to_pretty_url() {
    if(is_tax('services_category')) {
        $services_page_id = get_services_page_id();
        $category = get_queried_object();
        
        if($services_page_id && $category) {
            $page_slug = get_post_field('post_name', $services_page_id);
            $pretty_url = home_url('/' . $page_slug . '/' . $category->slug . '/');
            wp_redirect($pretty_url, 301);
            exit;
        }
    }
}

/**
 * 14. Заменяем ссылки категорий в меню на ЧПУ
 */
add_filter('nav_menu_link_attributes', 'change_category_links_to_pretty_url', 10, 3);
function change_category_links_to_pretty_url($atts, $item, $args) {
    if(isset($item->object) && $item->object == 'services_category') {
        $term = get_term($item->object_id, 'services_category');
        if($term && !is_wp_error($term)) {
            $services_page_id = get_services_page_id();
            if($services_page_id) {
                $page_slug = get_post_field('post_name', $services_page_id);
                $atts['href'] = home_url('/' . $page_slug . '/' . $term->slug . '/');
            }
        }
    }
    return $atts;
}