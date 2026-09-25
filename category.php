<?php
/**
 * Архив рубрики новостей
 */

get_header();
$page_id = get_option( 'page_for_posts' );
?>
	<main class="main">
		<?php
			get_template_part( 'template-parts/components/sections', '', array(
				'page_id'             => $page_id,
				'include_breadcrumbs' => true,
				'content_template'    => 'news',
			) );
		?>
	</main>
<?php
get_footer();
