<?php
$page_id = $args['page_id'] ?? get_the_ID();
$include_breadcrumbs = $args['include_breadcrumbs'] ?? true;
$content_template = $args['content_template'] ?? 'page'; // page, about, contacts и т.д.

// Массив секций для обертки sec-light
$light_sections = array(
    'breadcrumbs',
    'content-page',
    'content-about',
    'content-layout',
    'content-news',
    'content-contacts',
    'content-catalog',
    'content-single-services', 
    'why-benefits',
    'sec-fotogallery',
    'steps',
    'videoblock',
    'sec-reviews',
    'sec-news',
    'faq',
);
    // 'sec-category',
    // 'sec-employees',
    // 'banner-form',

// Собираем все блоки
$all_blocks = array();

// Добавляем breadcrumbs
if ($include_breadcrumbs) {
    $all_blocks[] = array('value' => 'breadcrumbs', 'type' => 'component');
}

// Добавляем content-шаблон
if ($content_template !== false && !empty($content_template)) {
    $all_blocks[] = array('value' => 'content-' . $content_template, 'type' => 'content');
}

// Добавляем секции из ACF
$arBlock = get_field('show_block_page_main', $page_id);
if (!empty($arBlock) && is_array($arBlock)) {
    foreach ($arBlock as $block) {
        $all_blocks[] = $block;
    }
}


// Обработка блоков с группировкой
$light_group = array();

foreach ($all_blocks as $index => $block) {
    $section_name = $block['value'];
    $is_light_section = in_array($section_name, $light_sections);
    
    if ($is_light_section) {
        $light_group[] = $block;
        $next_block = isset($all_blocks[$index + 1]) ? $all_blocks[$index + 1] : null;
        $next_is_light = $next_block && in_array($next_block['value'], $light_sections);
        
        if (!$next_is_light) {
            $group_count = count($light_group);
            echo '<div class="sec-light sec-offset">';
            
            foreach ($light_group as $group_index => $group_block) {
                $lastblock = ($group_index === $group_count - 1) ? 1 : 0;
                $block_name = $group_block['value'];
                
                if ($block_name === 'breadcrumbs') {
                    get_template_part("template-parts/components/breadcrumbs", '', array('page_id' => $page_id));
                } elseif (strpos($block_name, 'content-') === 0) {
                    $content_type = str_replace('content-', '', $block_name);
                    get_template_part('template-parts/content', $content_type, array('page_id' => $page_id));
                } else {
                    get_template_part("template-parts/section/$block_name", '', array(
                        'page_id' => $page_id, 
                        'name' => $group_block,
                        'lastblock' => $lastblock,
                        'is_last_in_light_group' => $is_last_in_group
                    ));
                }
            }
            
            echo '</div>';
            $light_group = array();
        }
    } else {
        $lastblock = 1;
        
        if (strpos($section_name, 'content-') === 0) {
            $content_type = str_replace('content-', '', $section_name);
            get_template_part('template-parts/content', $content_type, array('page_id' => $page_id));
        } else {
            get_template_part("template-parts/section/$section_name", '', array(
                'page_id' => $page_id, 
                'name' => $block,
                'lastblock' => null
            ));
        }
    }
}
?>