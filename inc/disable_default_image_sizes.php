<?php

/* Отключаем только конкретные стандартные размеры изображений */

add_filter('intermediate_image_sizes', 'disable_default_image_sizes');
function disable_default_image_sizes($sizes) {
    $disabled_sizes = ['medium_large', 'large', '1536x1536', '2048x2048'];
    return array_diff($sizes, $disabled_sizes);
}