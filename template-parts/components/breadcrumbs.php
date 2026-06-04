<?php
/**
 * Шаблон "хлебных крошек"
*/

if (function_exists('yoast_breadcrumb')) {
  yoast_breadcrumb();
} else {
  $page_main_id = get_option('page_on_front');
  $page_main_url = get_permalink($page_main_id);
  $page_current_id = get_the_ID();
?>
  <ul class="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">
    <li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
      <a class="breadcrumbs__link" href="<?php echo esc_url($page_main_url); ?>" title="Главная" itemprop="item">
        <span itemprop="name"><?php echo esc_html(get_the_title($page_main_id)); ?></span>
        <meta itemprop="position" content="1">
      </a>
    </li>
    <li class="breadcrumbs__item breadcrumbs__item--current" aria-current="page" itemprop="itemListElement"
      itemscope itemtype="https://schema.org/ListItem">
      <span itemprop="name"><?php echo esc_html(get_the_title($page_current_id)); ?></span>
      <meta itemprop="position" content="2">
    </li>
  </ul>
<?php
}