<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package konkord
 */

?>

<a href="<?php echo esc_url(get_permalink()); ?>" class="search-result" rel="bookmark">
	<div class="search-result__content">
		<span class="search-result__title"><?php the_title(); ?></span>
		<p class="search-result__summary"><?php the_excerpt(); ?></p>
	</div>
	<svg class="search-result__icon">
		<use xlink:href="<?php echo get_template_directory_uri(); ?>/img/sprite.svg#arrow-right"></use>
	</svg>
</a>
