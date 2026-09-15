<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package konkord
 */

get_header();

$error_img_field = get_field('error-404_img', 'option');
$error_img = $error_img_field
	? get_image_versions( $error_img_field )
	: array(
		'original_1x' => get_template_directory_uri() . '/img/404.png',
		'webp_1x'     => '',
	);
$error_img_mobile = ! empty( $error_img_field ) ? konkord_resolve_mobile_sources( $error_img ) : null;
$error_img_title = get_field('error-404_title', 'option') ? get_field('error-404_title', 'option') : 'Страница не найдена';
$error_link_name = get_field('error-404_link_name', 'option') ? get_field('error-404_link_name', 'option') : 'На главную';
$error_link_url = get_field('error-404_link_url', 'option') ? get_field('error-404_link_url', 'option') : home_url();
?>

<main class="main">
	<section class="error-404 not-found sec-light sec-offset">
		<div class="container">
			<picture class="error-404__img">
				<?php konkord_picture_mobile_sources( $error_img_mobile ); ?>
				<?php if ( ! empty( $error_img['webp_1x'] ) ) : ?>
				<source srcset="<?php echo esc_url( $error_img['webp_1x'] ); ?>" type="image/webp">
				<?php endif; ?>
				<img src="<?php echo esc_url( $error_img['original_1x'] ); ?>" class="messanges__icon" width="466" height="260" alt="Ошибка 404" aria-hidden="true">
			</picture>
			<div class="error-404__content">
				<h1 class="error-404__title sec-title"><?php echo esc_html( $error_img_title ); ?></h1>
				<a href="<?php echo esc_url( $error_link_url ); ?>" class="error-404__link ui-btn"><?php echo esc_html( $error_link_name ); ?></a>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
