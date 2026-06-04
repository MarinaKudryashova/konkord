<?php
$page_id = $args['id'] ?? get_the_ID();
$arBlock = get_field('show_block_page_main', $page_id);

$light_sections = array(
    'why-benefits',
    'sec-fotogallery',
    'steps',
    'videoblock',
    'sec-reviews',
    'sec-news',
    'faq'
);

if ($arBlock) :
    $light_group = array();
    foreach ($arBlock as $index => $block) {
        $section_name = $block['value'];
        $is_light_section = in_array($section_name, $light_sections);
        
        if ($is_light_section) {
            $light_group[] = $block;
            $next_is_light = isset($arBlock[$index + 1]) && in_array($arBlock[$index + 1]['value'], $light_sections);
            
            if (!$next_is_light) {
                $group_count = count($light_group);
                echo '<div class="sec-light sec-offset">';
                foreach ($light_group as $group_index => $group_block) {
                    $lastblock = ($group_index === $group_count - 1) ? 1 : 0;
                    get_template_part("template-parts/section/$group_block[value]", '', array(
                        'id' => $page_id, 
                        'name' => $group_block,
                        'lastblock' => $lastblock
                    ));
                }
                echo '</div>';
                $light_group = array();
            }
        } else {
            $lastblock = 1;
            get_template_part("template-parts/section/$section_name", '', array(
                'id' => $page_id, 
                'name' => $block,
                'lastblock' => null
            ));
        }
    }
endif;
?>