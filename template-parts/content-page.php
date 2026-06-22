<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package konkord
 */

?>

<div class="container">
	<h1 class="sec-title" data-aos="fade-up"><?php echo the_title(); ?></h1>
	<div class="textredactor"><?php the_content(); ?></div>
</div>


<!-- <article id="post-<?php //the_ID(); ?>" <?php //post_class(); ?>>
	<header class="entry-header">
		<?php //the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>

	<?php //konkord_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		//the_content();

		//wp_link_pages(
		// 	array(
		// 		'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'konkord' ),
		// 		'after'  => '</div>',
		// 	)
		// );
		?>
	</div>

	<?php //if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			// edit_post_link(
			// 	sprintf(
			// 		wp_kses(
			// 			__( 'Edit <span class="screen-reader-text">%s</span>', 'konkord' ),
			// 			array(
			// 				'span' => array(
			// 					'class' => array(),
			// 				),
			// 			)
			// 		),
			// 		wp_kses_post( get_the_title() )
			// 	),
			// 	'<span class="edit-link">',
			// 	'</span>'
			// );
			?>
		</footer>
	<?php //endif; ?>
</article> -->
