<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package konkord
 */

get_header();
?>

	<main class="main">
		<div class="page-search sec-light sec-offset">
			<div class="container">
				<?php get_template_part("template-parts/components/breadcrumbs"); ?>
				
				<h1 class="page-search__title sec-title">
					<?php printf( esc_html__( 'Результаты поиска: %s', 'konkord' ), '<span>' . get_search_query() . '</span>' ); ?>
				</h1>

				<?php if ( have_posts() ) : ?>
				<ul class="page-search__list">
					<?php while ( have_posts() ) : the_post();  ?>
						<li class="page-search__item">
							<?php get_template_part( 'template-parts/content', 'search' ); ?>
						</li>
					<?php endwhile; ?>
				</ul>
				<?php else : ?>
					<div class="page-search__none">
						<p>
							<?php esc_html_e( 'Похоже, мы не можем найти то, что вы ищете.', 'konkord' ); ?>
						</p>
						<a href="#" class="page-search__link ui-btn">На главную</a>
						<a href="#" class="page-search__link ui-btn">В каталог</a>
					</div>
				<?php endif; ?>
			</div>
	</main>

<?php
get_footer();
