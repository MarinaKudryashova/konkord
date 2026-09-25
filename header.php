<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package konkord
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> class="page">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( get_template_directory_uri() . '/favicon/favicon-32x32.png' ); ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( get_template_directory_uri() . '/favicon/favicon-16x16.png' ); ?>">
  <link rel="shortcut icon" href="<?php echo esc_url( get_template_directory_uri() . '/favicon/favicon.ico' ); ?>">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( get_template_directory_uri() . '/favicon/apple-touch-icon.png' ); ?>">
  <meta name="apple-mobile-web-app-title" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">

  <?php
	$promo_poster = function_exists( 'konkord_get_promo_poster_url' ) ? konkord_get_promo_poster_url() : '';
	if ( $promo_poster ) :
		$poster_type = ( substr( $promo_poster, -5 ) === '.webp' ) ? ' type="image/webp"' : '';
		?>
  <link rel="preload" as="image" href="<?php echo esc_url( $promo_poster ); ?>"<?php echo $poster_type; ?> fetchpriority="high">
	<?php endif; ?>

  <link rel="preload" href="<?php echo get_template_directory_uri();?>/fonts/Manrope-Regular.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="<?php echo get_template_directory_uri();?>/fonts/Manrope-Bold.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="<?php echo get_template_directory_uri();?>/fonts/Manrope-SemiBold.woff2" as="font" type="font/woff2" crossorigin>

  <?php wp_head(); ?>
</head>

<body <?php body_class('page__body'); ?>>
	<?php wp_body_open(); ?>
	<div class="page__container">
		<header class="header">
			<div class="header__container container" >
				<?php /*-- Логотип --*/ ?>
				<a href="<?php bloginfo('url'); ?>" class="header__logo logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<img class="logo__img" src="<?php echo get_field('site_logo', 'option') ?>" alt="Logo <?php bloginfo('name'); ?>" width="214" height="40">
				</a>
				<?php /*-- Адрес с переключением городов --*/ ?>
				<div class="header__address">
					<div class="header__address-text"><?php echo get_field('company_main_office_address-local', 'option') ?></div>
					<?php
						$geo_slug = '';
						if ( function_exists( 'belingoGeo_get_current_city' ) ) {
							$geo_city = belingoGeo_get_current_city();
							if ( $geo_city && is_object( $geo_city ) && method_exists( $geo_city, 'get_slug' ) ) {
								$geo_slug = (string) $geo_city->get_slug();
							}
						}
						$is_nn = ( 'nizhnij-novgorod' === $geo_slug );
					?>
					<div class="header__switcher">
						<a class="select_geo_city header__city<?php echo $is_nn ? '' : ' is-active'; ?>" data-name-orig="Дзержинск" data-name="dzerzhinsk-2">Дзержинск</a>
						<a class="select_geo_city header__city<?php echo $is_nn ? ' is-active' : ''; ?>" data-name-orig="Нижний Новгород" data-name="nizhnij-novgorod">Нижний Новгород</a>
					</div>
				</div>

				<?php /*-- СТА --*/ ?>
				<?php
					$header_phone = function_exists( 'konkord_get_header_phone' )
						? konkord_get_header_phone()
						: array( 'display' => '', 'href' => '' );
					$phone      = $header_phone['display'] ?? '';
					$phone_href = $header_phone['href'] ?? '';
				?>
				<div class="header__action">
					<?php /*-- Электронная почта --*/ ?>
					<div class="header__contacts header__contacts--email">
						<a class="header__link ui-link" href="mailto:<?php echo get_field('company_mail', 'option') ?>"><?php echo get_field('company_mail', 'option') ?></a>
					</div>
					<div class="header__contacts">
						<?php /*-- Телефон --*/ ?>
						<?php if ( ! empty( $phone ) ) : ?>
						<a href="tel:<?php echo esc_attr( $phone_href ); ?>" class="header__link header__phone ui-link" aria-label="Позвонить нам">
							<svg>
								<use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#phone"></use>
							</svg>
							<span><?php echo esc_html( $phone ); ?></span>
						</a>
						<?php endif; ?>
						
						<?php /*-- Время работы --*/ ?>
						<span class="header__timework"><?php echo get_field('company_main_office_timework', 'option') ?></span>
					</div>

					<?php /*-- Мессенджеры --*/ ?>
					<?php
					get_template_part( 'template-parts/components/messanges', null, array(
						'class' => 'header__messanges messanges',
						'field' => 'header_messengers_list',
					) );
					?>
				</div>
				
				<?php /*-- Кнопка бургер --*/ ?>
				<button class="header__burger" data-burger type="button" aria-label="открыть меню" aria-expanded="false" aria-controls="site-menu">
					<svg>
						<use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#burger"></use>
					</svg>
				</button>
				<?php /*-- Навигация --*/ ?>
				<div class="header__nav">
					<nav class="nav" data-menu id="site-menu">
						<?php
							wp_nav_menu( [
								'theme_location'  => 'header',
								'menu'            => 'header',
								'container'       => false,
								'menu_class'      => false,
								'menu_id'         => '',
								'echo'            => true,
								'fallback_cb'     => 'wp_page_menu',
								'before'          => '',
								'after'           => '',
								'link_before'     => '  ',
								'link_after'      => '',
								'items_wrap'      => '<ul>%3$s</ul>',
								'depth'           => 2,
								'walker'          => new BEM_Walker_Nav_Menu(),
							] );
						?>
						<button class="nav__close-btn" data-close-burger type="button">
							<svg class="ui-btn__icon">
								<use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#close"></use>
							</svg>
						</button>
					</nav>
				</div>
								
				<?php /*-- Поиск --*/ ?>
				<div class="header__searchbar searchbar">
					<button class="searchbar__btn searchbar__btn--open ui-btn ui-btn--icon" type="button" aria-label="открыть форму поиска">
						<svg class="ui-btn__icon">
							<use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#search"></use>
						</svg>
					</button>
					<?php get_search_form(); ?>
				</div>
  		</div>
		</header>