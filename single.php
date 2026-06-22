<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package konkord
 */

get_header();
?>
  <main class="main">
		<div class="sec-light sec-offset">
			<?php get_template_part("template-parts/components/breadcrumbs", '', array('page_id' => get_the_ID())); ?>
			<?php get_template_part("template-parts/content", 'single', array('page_id' => get_the_ID())); ?>
		</div>
  </main>
<?php
get_footer();
