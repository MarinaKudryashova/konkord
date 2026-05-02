<?php
// Класс для обработки меню футера с поддержкой подменю
class Footer_Menu_Walker extends Walker_Nav_Menu {

    // Начало уровня вложенности (подменю)
    public function start_lvl(&$output, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $output .= "{$n}{$indent}<ul class=\"footer-menu__submenu\">{$n}";
    }

    // Конец уровня вложенности
    public function end_lvl(&$output, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $output .= "$indent</ul>{$n}";
    }

    // Начало элемента меню
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ($depth) ? str_repeat($t, $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes);
        
        // Добавляем класс для элементов с подменю
        $li_classes = array('footer-menu__item');
        if ($has_children) {
            $li_classes[] = 'footer-menu__item--has-children';
        }
        
        $class_names = implode(' ', apply_filters('nav_menu_css_class', $li_classes, $item, $args, $depth));
        
        $output .= $indent . '<li class="' . esc_attr($class_names) . '">';

        // Атрибуты ссылки
        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        
        if ('_blank' === $item->target && empty($item->xfn)) {
            $atts['rel'] = 'noopener noreferrer';
        }
        
        $atts['href'] = !empty($item->url) ? $item->url : '';
        $atts['aria-current'] = $item->current ? 'page' : '';
        $atts['class'] = 'footer-menu__link ui-link';
        
        if ($item->current) {
            $atts['aria-current'] = 'page';
        }

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (is_scalar($value) && '' !== $value && false !== $value) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . ' data-menu-item data-text="' . esc_attr($item->title) . '">';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        
        // Добавляем кнопку для открытия подменю (только если есть дети)
        if ($has_children) {
            $item_output .= $n . $indent . "\t" . '<button class="footer-menu__toggle" data-dropdown-open aria-expanded="false" aria-label="Открыть подменю">';
            $item_output .= '<svg width="12" height="8" aria-hidden="true">';
            $item_output .= '<use xlink:href="' . get_template_directory_uri() . '/img/sprite.svg#icon-caret-down"></use>';
            $item_output .= '</svg>';
            $item_output .= '</button>';
        }
        
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    // Конец элемента меню
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $output .= "</li>{$n}";
    }
}