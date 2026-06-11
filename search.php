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
		<div class="search sec-light sec-offset">
			<div class="container">
				<?php get_template_part("template-parts/components/breadcrumbs"); ?>
				
				<h1 class="search__title sec-title">
					<?php printf( esc_html__( 'Результаты поиска: %s', 'konkord' ), '<span>' . get_search_query() . '</span>' ); ?>
				</h1>

				<?php if ( have_posts() ) : ?>
				<ul class="search__list">
					<?php while ( have_posts() ) : the_post();  ?>
						<li class="search__item">
							<?php get_template_part( 'template-parts/content', 'search' ); ?>
						</li>
					<?php endwhile; ?>
				</ul>
				<?php else : ?>
					<div class="search__none">
						<p>
							<?php esc_html_e( 'Похоже, мы не можем найти то, что вы ищете.', 'konkord' ); ?>
						</p>
						<a href="#" class="search__link ui-btn">На главную</a>
						<a href="#" class="search__link ui-btn">В каталог</a>
					</div>
				<?php endif; ?>
			</div>
	</main>

<?php
get_footer();
