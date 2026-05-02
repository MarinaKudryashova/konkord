<?php

/**
 * Универсальная функция для получения всех версий изображения по ID или URL
 * @param mixed $image Может быть: ID вложения, URL изображения, массив ACF
 * @param string $size Размер WordPress (thumbnail, medium, large, full и т.д.)
 * @param string|bool $size_suffix Суффикс для ретины (например: '@2x', false - без 2x версий)
 * @return array Массив с путями ко всем версиям изображения
 */
function get_image_versions($image, $size = 'full', $size_suffix = '@2x') {
  // Получаем URL изображения в зависимости от типа входных данных
  $image_url = '';
  $image_id = 0;
  
  if (is_numeric($image)) {
    // Если передан ID
    $image_id = intval($image);
    $image_url = wp_get_attachment_image_url($image_id, $size);
  } elseif (is_array($image) && isset($image['url'])) {
    // Если передан массив ACF
    $image_url = $image['url'];
    $image_id = $image['ID'] ?? 0;
    
    // Если нужен конкретный размер и он есть в массиве ACF
    if ($size !== 'full' && isset($image['sizes'][$size])) {
        $image_url = $image['sizes'][$size];
    }
  } elseif (is_string($image)) {
    // Если передан URL
    $image_url = $image;
  }
  
  if (empty($image_url)) {
    return [];
  }
  
  // Базовое имя файла без расширения
  $pathinfo = pathinfo($image_url);
  $filename = $pathinfo['filename'];
  $extension = $pathinfo['extension'] ?? 'jpg';
  $directory = $pathinfo['dirname'];
  
  // Поддерживаемые форматы
  $supported_formats = ['jpg', 'jpeg', 'png', 'webp'];
  
  // Проверяем формат
  if (!in_array(strtolower($extension), $supported_formats)) {
    return [
      'original_1x' => $image_url,
      'webp_1x' => '',
      'original_2x' => '',
      'webp_2x' => '',
      'alt' => '',
      'format' => $extension
    ];
  }
  
  // 1x версии
  $original_1x = $image_url;
  $webp_1x = str_replace('/uploads/', '/uploads-webpc/uploads/', $directory) . '/' . $filename . '.' . $extension . '.webp';
  
  // 2x версии (только если указан суффикс)
  $original_2x = '';
  $webp_2x = '';
  
  if (!empty($size_suffix)) {
    $original_2x = $directory . '/' . $filename . $size_suffix . '.' . $extension;
    $webp_2x = str_replace('/uploads/', '/uploads-webpc/uploads/', $directory) . '/' . $filename . $size_suffix . '.' . $extension . '.webp';
  }
  
  // Получаем alt текст если есть ID
  $alt_text = '';
  if ($image_id) {
    $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
  } elseif (is_array($image) && isset($image['alt'])) {
    $alt_text = $image['alt'];
  }
  
  return [
    'original_1x' => $original_1x,
    'webp_1x' => $webp_1x,
    'original_2x' => $original_2x,
    'webp_2x' => $webp_2x,
    'alt' => $alt_text,
    'format' => $extension,
    'id' => $image_id
  ];
}

/**
 * Функция для вывода picture элемента
 * @param array $sources Массив с источниками изображения
 * @param array $attrs Атрибуты для тега img
 */
function the_picture_element($sources, $attrs = []) {
  if (empty($sources['original_1x'])) return;
  
  $default_attrs = [
    'width' => '',
    'height' => '',
    'alt' => $sources['alt'] ?? '', // Берем alt из sources по умолчанию
    'loading' => 'lazy',
    'decoding' => 'async',
    'fetchpriority' => 'auto',
    'class' => ''
  ];
  
  $attrs = wp_parse_args($attrs, $default_attrs);
  
  // Автоматически добавляем дополнительные атрибуты для eager загрузки
  if ($attrs['loading'] === 'eager') {
    $attrs['decoding'] = 'async';
    $attrs['fetchpriority'] = 'high';
  } else {
    // Для lazy убираем лишние атрибуты
    unset($attrs['fetchpriority']);
    $attrs['decoding'] = 'async';
  }
  
  // Проверяем есть ли 2x версии
  $has_2x = !empty($sources['original_2x']) && !empty($sources['webp_2x']);
  $format = $sources['format'] ?? 'jpg';
  $mime_type = $format === 'png' ? 'image/png' : 'image/jpeg';
  ?>
  
  <picture class="picture-element <?php echo esc_attr($attrs['class']); ?>">
    <!-- Webp версии (только если webp существует) -->
    <?php if (!empty($sources['webp_1x'])): ?>
        <source srcset="<?php echo esc_url($sources['webp_1x']); ?><?php echo $has_2x ? ', ' . esc_url($sources['webp_2x']) . ' 2x' : ''; ?>" type="image/webp">
    <?php endif; ?>
    
    <!-- Оригинальные версии (jpg, png) -->
    <source srcset="<?php echo esc_url($sources['original_1x']); ?><?php echo $has_2x ? ', ' . esc_url($sources['original_2x']) . ' 2x' : ''; ?>" type="<?php echo esc_attr($mime_type); ?>">
    
    <!-- Fallback изображение -->
    <img src="<?php echo esc_url($sources['original_1x']); ?>" 
      <?php if ($has_2x): ?>
      srcset="<?php echo esc_url($sources['original_2x']); ?> 2x"
      <?php endif; ?>
      width="<?php echo esc_attr($attrs['width']); ?>"
      height="<?php echo esc_attr($attrs['height']); ?>"
      alt="<?php echo esc_attr($attrs['alt']); ?>"
      loading="<?php echo esc_attr($attrs['loading']); ?>"
      <?php if ($attrs['loading'] === 'eager'): ?>
      decoding="<?php echo esc_attr($attrs['decoding']); ?>"
      fetchpriority="<?php echo esc_attr($attrs['fetchpriority']); ?>"
      <?php else: ?>
      decoding="<?php echo esc_attr($attrs['decoding']); ?>"
      <?php endif; ?>
      class="<?php echo esc_attr($attrs['class']); ?>">
  </picture>
  <?php
}