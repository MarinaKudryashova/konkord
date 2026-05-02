<?php

class BEM_Walker_Nav_Menu extends Walker_Nav_Menu {

    public function start_lvl(&$output, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";  
        }
        $indent = str_repeat($t, $depth);
        $output .= "{$n}{$indent}<ul class=\"sub-menu\" data-dropdown-menu>{$n}";
    }

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
        
        // Добавляем атрибут data-dropdown для элементов с подменю
        $li_attributes = $has_children ? ' data-dropdown' : '';
        
        $output .= $indent . '<li' . $li_attributes . '>';

        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        if ('_blank' === $item->target && empty($item->xfn)) {
            $atts['rel'] = 'noopener noreferrer';
        }
        $atts['href'] = !empty($item->url) ? $item->url : '';
        $atts['aria-current'] = $item->current ? 'page' : '';

        if ($item->current) $atts['href'] = '';

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
        $item_output .= '<a class="ui-link"' . $attributes . ' data-menu-item>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        
        // Добавляем кнопку открытия подменю для элементов с детьми
        if ($has_children) {
            $item_output .= $n . $indent . "\t" . '<button data-dropdown-open aria-expanded="false" aria-label="Открыть подменю">' . $n;
            $item_output .= $indent . "\t\t" . '<svg>' . $n;
            $item_output .= $indent . "\t\t\t" . '<use xlink:href="'. get_template_directory_uri() .'/img/sprite.svg#icon-caret-down"></use>' . $n;
            $item_output .= $indent . "\t\t" . '</svg>' . $n;
            $item_output .= $indent . "\t" . '</button>' . $n;
        }
        
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

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
}// BEM_Walker_Nav_Menu
