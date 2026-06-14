<?php
/**
 * Шаблон архива услуг
 * Этот шаблон автоматически используется для страницы выбранной как "Страница записей услуг"
 */

get_header();

// Получаем ID страницы услуг
$services_page_id = get_services_page_id();
?>

<main class="main">
  <?php get_template_part('template-parts/content-services', '', array('page_id' => $services_page_id)); ?>
</main>

<?php get_footer(); ?>