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
					<a href="<?php bloginfo('url'); ?>" class="footer__logo footer__logo--mobile">
						<img src="<?php echo get_field('site_logo', 'option') ?>" alt="Logo <?php bloginfo('name'); ?>" width="214" height="40">
					</a>
					<?php if(get_field('site_logo_text', 'option')):?>
					<p class="footer__descr"><?php echo get_field('site_logo_text', 'option'); ?></p>
					<?php endif; ?>
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
					<?php
					get_template_part( 'template-parts/components/messanges', null, array(
						'class' => 'footer-contacts__messanges messanges',
					) );
					?>

					<?php $company_link_map = get_field('company_map_link', 'option') ? get_field('company_map_link', 'option') : '#';?>
					<a href="<?php echo esc_url($company_link_map); ?>" class="footer-contacts__link" target="_blank" rel="noopener noreferrer">Яндекс Карты</a>
				</div>

				<?php /*-- Отдел продаж --*/ ?>
				<?php 
					$department_sales = get_field('department_sales', 'option');
					$department_phones = $department_sales["phone"];
					$department_tel_arr = konkord_split_phone_list( $department_phones );
					$department_tel_arr_href = array_map( 'konkord_phone_href', $department_tel_arr );
				?>
				<div class="department">
					<?php if($department_sales["name"]): ?>
					<span class="department__title"><?php echo esc_html($department_sales["name"]); ?></span>
					<?php endif; ?>
 
          <?php if($department_tel_arr && is_array($department_tel_arr)) : ?>
					<ul class="department__list">
						<?php foreach($department_tel_arr as $ind => $tel) : ?>
						<li class="department__item">
							<a href="tel:<?php echo $department_tel_arr_href[$ind] ?>" class="department__phone">
								<svg>
									<use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#phone"></use>
								</svg>
								<span><?php echo esc_html($tel); ?></span>
							</a>
						</li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>

					<?php if($department_sales["email"]): ?>
					<a href="mailto:<?php echo $department_sales["email"]; ?>" class="department__email"><?php echo $department_sales["email"]; ?></a>
					<?php endif; ?>
				</div>

				<?php /*-- Отдел корпоративных продаж --*/ ?>
				<?php 
					$department_corporate = get_field('department_corporate', 'option');
					$department_corporate_employees_ids = $department_corporate["employees"];
				?>
				<?php if($department_corporate_employees_ids && is_array($department_corporate_employees_ids)): ?>
				<div class="department department--corporate">
					<?php if($department_corporate["name"]): ?>
					<span class="department__title"><?php echo esc_html($department_corporate["name"]); ?></span>
					<?php endif; ?>

					<?php foreach($department_corporate_employees_ids as $employee_id) :
						$employee_name = get_the_title($employee_id);

						$employee_email = get_field('employee_email', $employee_id);
						
						$employee_phones = get_field('employee_phone', $employee_id);
						$employee_phone_arr = konkord_split_phone_list( $employee_phones );
						$employee_phone_href = array_map( 'konkord_phone_href', $employee_phone_arr );
						?>
					<div class="department__worker">
						<span class="department__name"><?php echo esc_html($employee_name); ?></span>

						<?php if($employee_phone_arr && $employee_email && is_array($employee_phone_arr)) : ?>
						<ul class="department__list">
							<?php foreach($employee_phone_arr as $ind => $tel) : ?>
							<li class="department__item">
								<a href="tel:<?php echo esc_html($employee_phone_href[$ind]); ?>" class="department__phone">
									<svg>
										<use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#phone"></use>
									</svg>
									<span><?php echo esc_html($tel); ?></span>
								</a>
							</li>
							<?php endforeach; ?>
							<li class="department__item">
								<a href="mailto:<?php echo esc_html($employee_email); ?>" class="department__phone">
									<svg>
										<use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#email"></use>
									</svg>
									<span><?php echo esc_html($employee_email); ?></span>
								</a>
							</li>
						</ul>
						<?php endif; ?>
					</div>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

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

	<?php get_template_part("template-parts/components/modal"); ?>
	<?php get_template_part("template-parts/components/cookie-notice"); ?>

</body>
</html>
