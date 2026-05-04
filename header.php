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

$messanges = get_field('messengers_list', 'options'); /*-- Мессенджеры --*/

?>
<!doctype html>
<html <?php language_attributes(); ?> class="page">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?php bloginfo( 'name' ); ?> - <?php bloginfo('description'); ?>">


  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri();?>/favicon/apple-touch-icon.png">
	<meta name="apple-mobile-web-app-title" content="<?php bloginfo( 'name' ); ?>" />
	<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri();?>/favicon-96x96.png" sizes="96x96" />
	<link rel="icon" type="image/svg+xml" href="<?php echo get_template_directory_uri();?>/favicon.svg" />
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri();?>/favicon.ico" />
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri();?>/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri();?>/favicon/favicon-16x16.png">
  <link rel="manifest" href="<?php echo get_template_directory_uri();?>/favicon/site.webmanifest">

  <meta property="og:type" content="website">
  <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
  <meta property="og:title" content="<?php echo esc_attr(wp_title('|', false, 'right') . get_bloginfo('name')); ?>">
  <meta property="og:description" content="<?php bloginfo( 'name' ); ?> - <?php bloginfo('description'); ?>">
  <meta property="og:image" content="<?php echo get_template_directory_uri();?>/img/site-preview.jpg">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">
	<meta property="og:image:type" content="image/jpg">
	<meta property="og:image:alt" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
  <meta property="og:url" content="<?php echo esc_url( home_url( '/' ) ); ?>">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="<?php echo esc_url( home_url( '/' ) ); ?>">
  <meta name="twitter:title" content="<?php bloginfo( 'name' ); ?>">
  <meta name="twitter:description"
    content="<?php bloginfo( 'name' ); ?> - <?php bloginfo('description'); ?>">
  <meta name="twitter:image" content="<?php echo get_template_directory_uri();?>/img/site-preview.jpg">

  <link rel="preload" href="<?php echo get_template_directory_uri();?>/fonts/Manrope-Light.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="<?php echo get_template_directory_uri();?>/fonts/Manrope-Regular.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="<?php echo get_template_directory_uri();?>/fonts/Manrope-SemiBold.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="<?php echo get_template_directory_uri();?>/fonts/Manrope-Bold.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="<?php echo get_template_directory_uri();?>/fonts/Manrope-ExtraBold.woff2" as="font" type="font/woff2" crossorigin>

  <?php wp_head(); ?>
</head>

<body <?php body_class('page__body'); ?>>
	<?php wp_body_open(); ?>
	<div class="page__container">
		<header class="header">
			<div class="header__container container" >
				<?php /*-- Логотип --*/ ?>
				<a href="<?php bloginfo('url'); ?>" class="header__logo logo">
					<img class="logo__img" src="<?php echo get_field('site_logo', 'option') ?>" alt="Logo <?php bloginfo('name'); ?>" width="214" height="40">
				</a>
				<?php /*-- Адрес с переключением городов --*/ ?>
				<div class="header__address">
					<div class="header__address-text"></div>
					<div class="header__switcher">
						<span class="header__city is-active" data-city="dz">Дзержинск</span>
						<span class="header__city" data-city="nn">Нижний Новгород</span>
					</div>
				</div>

				<?php /*-- СТА --*/ ?>
				<?php
					$phone = get_field('company_tel', 'options');
					$phone = explode(PHP_EOL, $phone);
					$phone_href = preg_replace('![^0-9]+!', '', $phone);
				?>
				<div class="header__action">
					<?php /*-- Телефон --*/ ?>
					<?php if(!empty($phone[0]) && is_array($phone)) : ?>
					<a href="tel:<?php echo $phone_href[0]; ?>" class="header__phone" aria-label="Позвонить нам">
						<svg>
							<use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#icon-phone"></use>
						</svg>
						<span><?php echo $phone[0]; ?></span>
					</a>
					<?php endif; ?>

					<?php /*-- Мессенджеры --*/ ?>
					<?php if($messanges) : ?>
						<ul class="header__messanges messanges" title="messanges">
							<?php foreach($messanges as $li) : ?>
								<li class="messanges__item">
								<a href="<?php  echo get_field($li['value'], 'options'); ?>" target="_blank" class="messanges__link" aria-label="Свяжитесь с нами в <?php echo $li['label']; ?>">
									<img loading="lazy" src="<?php echo get_template_directory_uri();?>/img/icon/<?php echo esc_html__($li['value']); ?>.svg" class="messanges__icon" width="16" height="16" alt="иконка <?php  echo $li['label']; ?>" aria-hidden="true">
								</a>
							</li>
							<?php endforeach;	?>
						</ul>
					<?php endif; ?>

					
					<?php /*-- Кнопка бургер --*/ ?>
					<div class="header__burger">
						<button class="burger" aria-label="Открыть меню" aria-expanded="false" data-burger>
							<span class="burger__line"></span>
						</button>
					</div>
				</div>

				<?php /*-- Навигация --*/ ?>
				<div class="header__nav">
					<nav class="nav" title="main navigation" data-menu>
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