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
            // var_dump($section_name);
            if ($is_light_section) {
                // Добавляем секцию в текущую группу
                $light_group[] = $block;
                
                // Проверяем, является ли следующая секция тоже light-секцией
                $next_is_light = isset($arBlock[$index + 1]) && in_array($arBlock[$index + 1]['value'], $light_sections);
                
                // Если следующая секция не light или это последняя секция - закрываем группу
                if (!$next_is_light) {
                    echo '<div class="sec-light">';
                    foreach ($light_group as $group_block) {
                        get_template_part("template-parts/section/$group_block[value]", '', array('id' => $page_id, 'name' => $group_block));
                    }
                    echo '</div>';
                    $light_group = array(); // Сбрасываем группу
                }
            } else {
                // Обычная секция без обертки
                get_template_part("template-parts/section/$section_name", '', array('id' => $page_id, 'name' => $block));
            }
        }
      endif;
    ?>
  </main>
  
<?php get_footer(); ?>