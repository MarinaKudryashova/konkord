<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package konkord
 */

get_header();

$error_img_url = get_field('error-404_img', 'option') ? get_field('error-404_img', 'option') : get_template_directory_uri() . '/img/404.png';
$error_img_title = get_field('error-404_title', 'option') ? get_field('error-404_title', 'option') : 'Страница не найдена';
$error_link_name = get_field('error-404_link_name', 'option') ? get_field('error-404_link_name', 'option') : 'На главную';
$error_link_url = get_field('error-404_link_url', 'option') ? get_field('error-404_link_url', 'option') : home_url();
?>

<main class="main">
	<section class="error-404 not-found sec-light sec-offset">
		<div class="container">
			<picture class="error-404__img">
				<img src="<?php echo $error_img_url; ?>" class="messanges__icon" width="466" height="260" alt="Ошибка 404" aria-hidden="true">
			</picture>
			<div class="error-404__content">
				<h1 class="error-404__title sec-title"><?php echo $error_img_title; ?></h1>
				<a href="<?php echo $error_link_url; ?>" class="error-404__link ui-btn"><?php echo $error_link_name; ?></a>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
