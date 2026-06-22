<?php
/**
 * Шаблон пагинации
 * 
 * @param array $args Параметры пагинации
 *   - 'mid_size' - количество страниц слева/справа (по умолчанию 2)
 *   - 'prev_text' - текст/разметка кнопки "Назад"
 *   - 'next_text' - текст/разметка кнопки "Вперед"
 *   - 'wrapper_class' - класс обертки (по умолчанию 'pagination')
 *   - 'show_aria_labels' - показывать ли aria-label (по умолчанию true)
 */

$btn_prev = '<svg><use xlink:href="'.get_template_directory_uri().'/img/sprite.svg#arrow-left"></use></svg>';
$btn_next = '<svg><use xlink:href="'.get_template_directory_uri().'/img/sprite.svg#arrow-right"></use></svg>';

// Аргументы по умолчанию
$default_args = array(
    'mid_size'          => 3,
    'prev_text'         => $btn_prev,
    'next_text'         => $btn_next,
    'wrapper_class'     => 'pagination',
    'show_aria_labels'  => true,
);

$pagination_args = wp_parse_args($args, $default_args);

global $wp_query;

$current_page = max(1, get_query_var('paged'));
if (is_singular() || is_front_page()) {
    $current_page = max(1, get_query_var('page'));
}

$paginate_config = array(
    'total'         => $wp_query->max_num_pages,
    'current'       => $current_page,
    'mid_size'      => $pagination_args['mid_size'],
    'prev_text'     => $pagination_args['prev_text'],
    'next_text'     => $pagination_args['next_text'],
    'type'          => 'array',
);

$paginate_links = paginate_links($paginate_config);

if (!$paginate_links || $wp_query->max_num_pages <= 1) {
    return;
}
?>

<div class="news__pagination <?php echo esc_attr($pagination_args['wrapper_class']); ?>" data-aos="fade-up"
     <?php echo $pagination_args['show_aria_labels'] ? 'aria-label="Постраничная навигация"' : ''; ?>>
    
    <?php foreach ($paginate_links as $link) : 
        
        if (strpos($link, 'current') !== false) {
            $custom_link = str_replace(
                '<span aria-current="page" class="page-numbers current',
                '<span aria-current="page" class="pagination__link current',
                $link
            );
        } 
        elseif (strpos($link, 'next') !== false) {
            $custom_link = str_replace(
                '<a class="next page-numbers',
                '<a aria-label="Следующая страница" class="next pagination__link',
                $link
            );
        } 
        elseif (strpos($link, 'prev') !== false) {
            $custom_link = str_replace(
                '<a class="prev page-numbers',
                '<a aria-label="Предыдущая страница" class="prev pagination__link',
                $link
            );
        } 
        elseif (strpos($link, 'dots') !== false) {
            $custom_link = str_replace(
                '<span class="page-numbers dots',
                '<span class="pagination__dots',
                $link
            );
        } 
        else {
            $custom_link = str_replace(
                '<a class="page-numbers',
                '<a class="pagination__link',
                $link
            );
        }
        
        echo $custom_link;
        
    endforeach; ?>
    
</div>