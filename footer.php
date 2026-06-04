<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package konkord
 */

?>

		<footer class="footer">
			<div class="footer__container container">

				<div class="footer__company">
					<a href="<?php bloginfo('url'); ?>" class="footer__logo footer__logo--mobile" itemprop="url">
						<img src="<?php echo get_field('site_logo', 'option') ?>" alt="Logo <?php bloginfo('name'); ?>" width="214" height="40" itemprop="logo image">
					</a>
					<p class="footer__descr">Полиграфия «под ключ» – от макета до доставки</p>
				</div>

				<div class="footer__contacts footer-contacts">
					<?php /*-- Адреса --*/ ?>
					<div class="footer-contacts__item">
						<?php /*-- Центральный офис --*/ ?>
						<?php 
						$main_office_address = get_field('company_main_office_address-local', 'option');
						$main_office_city = get_field('company_main_office_city', 'option');
						$main_address_parts = array();
						if(!empty($main_office_city)) $main_address_parts[] = $main_office_city;
						if(!empty($main_office_address)) $main_address_parts[] = $main_office_address;

						$full_main_office_address = implode(', ', $main_address_parts);
						?>
						<div class="contacts">
							<span class="contacts__title"><?php esc_html_e( 'Центральный офис:', 'konkord' ); ?></span>
							<p class="contacts__value"><?php echo esc_html($full_main_office_address) ; ?></p>
						</div>

						<?php /*-- Производство --*/ ?>
						<?php
						$manufacture_office_address = get_field('company_manufacture_address-local', 'option');
						$manufacture_office_city = get_field('company_manufacture_city', 'option');
						$manufacture_address_parts = array();
						if(!empty($manufacture_office_city)) $manufacture_address_parts[] = $manufacture_office_city;
						if(!empty($manufacture_office_address)) $manufacture_address_parts[] = $manufacture_office_address;

						$full_manufacture_office_address = implode(', ', $manufacture_address_parts);
						?>
						<div class="contacts">
							<span class="contacts__title"><?php esc_html_e( 'Производство:', 'konkord' ); ?></span>
							<p class="contacts__value"><?php echo esc_html($full_manufacture_office_address) ; ?></p>
						</div>
					</div>

					<?php /*-- Время работы --*/ ?>
					<div class="footer-contacts__item contacts">
						<span class="contacts__title"><?php esc_html_e( 'Режим работы:', 'konkord' ); ?></span>
						<p class="contacts__value"><?php echo get_field('company_main_office_timework', 'option') ?></p>
					</div>


					<?php /*-- Мессенджеры --*/ ?>
					<?php $messanges = get_field('messengers_list', 'options'); /*-- Мессенджеры --*/ ?>
					<?php if($messanges) : ?>
						<ul class="footer-contacts__messanges messanges" title="messanges">
							<?php foreach($messanges as $li) : ?>
								<li class="messanges__item">
								<a href="<?php  echo get_field($li['value'], 'options'); ?>" target="_blank" class="messanges__link" aria-label="Свяжитесь с нами в <?php echo $li['label']; ?>">
									<img loading="lazy" src="<?php echo get_template_directory_uri();?>/img/icon/<?php echo esc_html__($li['value']); ?>.svg" class="messanges__icon" width="16" height="16" alt="иконка <?php  echo $li['label']; ?>" aria-hidden="true">
								</a>
							</li>
							<?php endforeach;	?>
						</ul>
					<?php endif; ?>

					<a href="#" class="footer-contacts__linK">Яндекс Карты</a>
				</div>

				<?php /*-- Отдел продаж --*/ ?>

				<?php /*-- Отдел корпоративных продаж --*/ ?>

				<?php /*-- Меню --*/ ?>
				<div class="footer__menu">
					<?php    
						wp_nav_menu( [
							'theme_location'  => 'footer',
							'menu'            => 'footer',
							'container'       => false,
							'menu_class'      => false,
							'menu_id'         => '',
							'echo'            => true,
							'fallback_cb'     => 'wp_page_menu',
							'before'          => '',
							'after'           => '',
							'link_before'     => '',
							'link_after'      => '',
							'items_wrap'      => '<ul class="%2$s footer-menu">%3$s</ul>',
							'depth'           => 1,
							'walker'          => new Footer_Menu_Walker(),
						] );
					?>
				</div>

				<div class="footer__policies">
					<?php    
						wp_nav_menu( [
							'theme_location'  => 'footer_policies',
							'menu'            => 'footer_policies',
							'container'       => false,
							'menu_class'      => false,
							'menu_id'         => '',
							'echo'            => true,
							'fallback_cb'     => 'wp_page_menu',
							'before'          => '',
							'after'           => '',
							'link_before'     => '',
							'link_after'      => '',
							'items_wrap'      => '<ul class="%2$s footer-menu footer__policies">%3$s</ul>',
							'depth'           => 1,
							'walker'          => new Footer_Menu_Walker(),
						] );
					?>
				</div>
				
			</div>
		</footer>
		
  </div><!-- #site -->

	<?php wp_footer(); ?>

	<?php //get_template_part("template-parts/components/modal"); ?>
	<?php //get_template_part("template-parts/components/topbtn"); ?>
	<?php //get_template_part("template-parts/components/cookie-notice"); ?>

</body>
</html>
