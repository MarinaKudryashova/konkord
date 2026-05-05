<?php
/*
Template Name: Главная страница
Template Post Type: page
*/

$page_id = get_the_ID();
?>

<?php get_header(); ?>
  <main class="main">
    <?php get_template_part( "template-parts/section/promo-main", null, array('id' => $page_id) );

      $arBlock = get_field('show_block_page_main');
      
      // Список секций, которые нужно оборачивать в sec-light
      $light_sections = array(
        'why-benefits',
        'sec-fotogallery',
        'steps',
        'park-equipment',
        'sec-reviews',
        'sec-news',
        'faq'
      );
      
      if ($arBlock) :
        $light_group = array(); // Временное хранение группы секций для обертки
        
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
                    $light_group = array(); // Сбрасываем группу
                }
            } else {
                // Обычная секция без обертки
                $lastblock = 1; // Для одиночных секций всегда 1
                get_template_part("template-parts/section/$section_name", '', array(
                    'id' => $page_id, 
                    'name' => $block,
                    'lastblock' => null
                ));
            }
        }
      endif;
    ?>
  </main>
  
<?php get_footer(); ?>