<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package konkord
 */

$page_id = get_the_ID();

get_header();
?>
	<main class="main">
		<?php
      get_template_part('template-parts/components/sections', '', array(
          'page_id' => $page_id,
          'include_breadcrumbs' => true,
          'content_template' => 'page' // content-page.php
      ));
		?>
	</main><!-- #main -->
<?php
get_footer();
