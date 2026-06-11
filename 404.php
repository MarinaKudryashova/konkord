<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package konkord
 */

get_header();
?>

	<main class="main">

		<section class="error-404 not-found sec-light sec-offset">
			<div class="container">
				<picture class="error-404__img">
					<img src="<?php echo get_template_directory_uri();?>/img/404.png" class="messanges__icon" width="466" height="260" alt="Ошибка 404" aria-hidden="true">
				</picture>
				<div class="error-404__content">
					<h1 class="error-404__title sec-title">Страница не найдена</h1>
					<a href="" class="error-404__link ui-btn">На главную</a>
				</div>
			</div>
		</section>

	</main>

<?php
get_footer();
