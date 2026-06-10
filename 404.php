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



			<!-- <header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'konkord' ); ?></h1>
			</header> -->
			<!-- .page-header -->

			<!-- <div class="page-content">
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'konkord' ); ?></p>

					<?php
					get_search_form();

					the_widget( 'WP_Widget_Recent_Posts' );
					?>

					<div class="widget widget_categories">
						<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'konkord' ); ?></h2>
						<ul>
							<?php
							wp_list_categories(
								array(
									'orderby'    => 'count',
									'order'      => 'DESC',
									'show_count' => 1,
									'title_li'   => '',
									'number'     => 10,
								)
							);
							?>
						</ul> -->
					<!-- </div> -->
					<!-- .widget -->

					<!-- <?php
					/* translators: %1$s: smiley */
					$konkord_archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'konkord' ), convert_smilies( ':)' ) ) . '</p>';
					the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$konkord_archive_content" );

					the_widget( 'WP_Widget_Tag_Cloud' );
					?> -->

			<!-- </div> -->
			<!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
