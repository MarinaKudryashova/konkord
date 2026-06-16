<?php
/**
 * Шаблон "хлебных крошек"
*/

if (function_exists('yoast_breadcrumb')) {
    yoast_breadcrumb();
} else {
    $page_main_id = get_option('page_on_front');
    $page_main_url = get_permalink($page_main_id);
    $page_main_title = get_the_title($page_main_id);
    
    // Получаем страницу записей (блога)
    $page_blog_id = get_option('page_for_posts');
    $page_blog_url = $page_blog_id ? get_permalink($page_blog_id) : '';
    $page_blog_title = $page_blog_id ? get_the_title($page_blog_id) : 'Новости';
    
    // Получаем страницу записей услуг
    $page_services_id = get_services_page_id();
    $page_services_url = $page_services_id ? get_permalink($page_services_id) : '';
    $page_services_title = $page_services_id ? get_the_title($page_services_id) : 'Услуги';
    
    $page_current_id = get_the_ID();
    $position = 1;
    ?>
    
    <ul class="breadcrumbs container" itemscope itemtype="https://schema.org/BreadcrumbList" data-aos="fade-up">
        <!-- Главная -->
        <li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a class="breadcrumbs__link" href="<?php echo esc_url($page_main_url); ?>" title="Главная" itemprop="item">
                <span itemprop="name"><?php echo esc_html($page_main_title); ?></span>
                <meta itemprop="position" content="<?php echo $position++; ?>">
            </a>
        </li>

        <?php if (is_singular('services')) : ?>
            <!-- Страница записей услуг (архив) -->
            <?php if ($page_services_url) : ?>
                <li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a class="breadcrumbs__link" href="<?php echo esc_url($page_services_url); ?>" title="<?php echo esc_attr($page_services_title); ?>" itemprop="item">
                        <span itemprop="name"><?php echo esc_html($page_services_title); ?></span>
                        <meta itemprop="position" content="<?php echo $position++; ?>">
                    </a>
                </li>
            <?php endif; ?>
            
            <!-- Категория услуги (если есть) -->
            <?php
            $service_categories = get_the_terms($page_current_id, 'services_category');
            if(!empty($service_categories) && !is_wp_error($service_categories)) {
                // Берем первую категорию
                $category = $service_categories[0];
                $category_url = $page_services_url ? $page_services_url . $category->slug . '/' : get_term_link($category);
                ?>
                <li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a class="breadcrumbs__link" href="<?php echo esc_url($category_url); ?>" title="<?php echo esc_attr($category->name); ?>" itemprop="item">
                        <span itemprop="name"><?php echo esc_html($category->name); ?></span>
                        <meta itemprop="position" content="<?php echo $position++; ?>">
                    </a>
                </li>
                <?php
            }
            ?>
            
            <!-- Текущая услуга -->
            <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page" itemprop="itemListElement"
                itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php echo esc_html(get_the_title($page_current_id)); ?></span>
                <meta itemprop="position" content="<?php echo $position++; ?>">
            </li>
            
        <?php elseif (is_tax('services_category')) : ?>
            <!-- Страница категории услуг -->
            <?php if ($page_services_url) : ?>
                <li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a class="breadcrumbs__link" href="<?php echo esc_url($page_services_url); ?>" title="<?php echo esc_attr($page_services_title); ?>" itemprop="item">
                        <span itemprop="name"><?php echo esc_html($page_services_title); ?></span>
                        <meta itemprop="position" content="<?php echo $position++; ?>">
                    </a>
                </li>
            <?php endif; ?>
            
            <!-- Текущая категория -->
            <?php
            $current_term = get_queried_object();
            ?>
            <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page" itemprop="itemListElement"
                itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php echo esc_html($current_term->name); ?></span>
                <meta itemprop="position" content="<?php echo $position++; ?>">
            </li>
            
        <?php elseif (is_page() && $page_services_id && get_the_ID() == $page_services_id) : ?>
            <!-- Страница записей услуг (архивная страница) -->
            <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page" itemprop="itemListElement"
                itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php echo esc_html($page_services_title); ?></span>
                <meta itemprop="position" content="<?php echo $position++; ?>">
            </li>
            
        <?php elseif (is_single()) : ?>
            <!-- Страница записей (блог) -->
            <?php if ($page_blog_url) : ?>
                <li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a class="breadcrumbs__link" href="<?php echo esc_url($page_blog_url); ?>" title="<?php echo esc_attr($page_blog_title); ?>" itemprop="item">
                        <span itemprop="name"><?php echo esc_html($page_blog_title); ?></span>
                        <meta itemprop="position" content="<?php echo $position++; ?>">
                    </a>
                </li>
            <?php endif; ?>
            
            <!-- Текущая статья -->
            <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page" itemprop="itemListElement"
                itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php echo esc_html(get_the_title($page_current_id)); ?></span>
                <meta itemprop="position" content="<?php echo $position++; ?>">
            </li>
            
        <?php elseif (is_page()) : ?>
            <!-- Для обычных страниц -->
            <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page" itemprop="itemListElement"
                itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php echo esc_html(get_the_title($page_current_id)); ?></span>
                <meta itemprop="position" content="<?php echo $position++; ?>">
            </li>
            
        <?php elseif (is_home()) : ?>
            <!-- Страница блога (список новостей) -->
            <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page" itemprop="itemListElement"
                itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php echo esc_html($page_blog_title); ?></span>
                <meta itemprop="position" content="<?php echo $position++; ?>">
            </li>
            
        <?php elseif (is_post_type_archive('services')) : ?>
            <!-- Архив услуг (если не перенаправлен) -->
            <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page" itemprop="itemListElement"
                itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php echo esc_html($page_services_title); ?></span>
                <meta itemprop="position" content="<?php echo $position++; ?>">
            </li>
            
        <?php elseif (is_search()) : ?>
            <!-- Результаты поиска -->
            <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page" itemprop="itemListElement"
                itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name">Поиск: <?php echo esc_html(get_search_query()); ?></span>
                <meta itemprop="position" content="<?php echo $position++; ?>">
            </li>
            
        <?php elseif (is_category()) : ?>
            <!-- Архив категории блога -->
            <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page" itemprop="itemListElement"
                itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php echo esc_html(single_cat_title('', false)); ?></span>
                <meta itemprop="position" content="<?php echo $position++; ?>">
            </li>
            
        <?php elseif (is_tag()) : ?>
            <!-- Архив тега -->
            <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page" itemprop="itemListElement"
                itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php echo esc_html(single_tag_title('', false)); ?></span>
                <meta itemprop="position" content="<?php echo $position++; ?>">
            </li>
            
        <?php elseif (is_404()) : ?>
            <!-- Страница 404 -->
            <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page" itemprop="itemListElement"
                itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name">Страница не найдена</span>
                <meta itemprop="position" content="<?php echo $position++; ?>">
            </li>
            
        <?php else : ?>
            <!-- Все остальные случаи -->
            <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page" itemprop="itemListElement"
                itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php echo esc_html(get_the_title($page_current_id)); ?></span>
                <meta itemprop="position" content="<?php echo $position++; ?>">
            </li>
        <?php endif; ?>
    </ul>
    
<?php } ?>