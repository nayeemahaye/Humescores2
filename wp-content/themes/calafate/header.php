<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Calafate
 */

?>

<!DOCTYPE html>
<!--[if lt IE 9]> <html <?php language_attributes(); ?> class="ie7" xmlns="http://www.w3.org/1999/xhtml"> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> xmlns="http://www.w3.org/1999/xhtml"> <!--<![endif]-->

<head id="head">

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="msapplication-tap-highlight" content="no" />
	
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>

</head>

<body id="body" <?php body_class(); ?>>

<div id="site" class="header-content-wrapper out animate">

	<div class="site-carry">

		<header id="site-header" class="site-header" itemscope itemtype="http://schema.org/Organization">

			<div class="site-header-holder wrapper">

				<a id="site-logo" class="site-logo ajax-link image-logo-<?php echo esc_attr( get_theme_mod( 'calafate_image_logo', 'disabled' ) ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url">

					<?php if ( is_home() ) : ?>
						<h1><?php calafate_logo(); ?></h1>
					<?php else : ?>
						<h2><?php calafate_logo(); ?></h2>
					<?php endif; ?>

				</a>

				<nav id="site-navigation" class="site-navigation">

					<?php if ( function_exists( 'calafate_responsive_cart_button' ) )
						calafate_responsive_cart_button(); ?>

					<a class="responsive-nav" href="#"><span class="lines"></span></a>

					<?php calafate_portfolio_filters_button(); ?>
					
					<?php if ( function_exists( 'calafate_shop_filters_button' ) )
						calafate_shop_filters_button(); ?>

					<?php if ( get_theme_mod( 'calafate_site_mobile_search', 'disabled' ) === 'enabled' ) : ?>
						<a class="responsive-search" href="#"><?php echo calafate_svg( 'action-search', 'icon' ); ?></a>
					<?php endif; ?>

					<?php if ( has_nav_menu( 'primary' ) ) :

						wp_nav_menu( array(
							'container' => false,
							'menu_class' => 'top-menu right',
							'echo' => true,
							'before' => '',
							'after' => '',
							'link_before' => '',
							'link_after' => '',
							'depth' => 2,
							'theme_location' => 'primary',
							'walker' => new Calafate_Menu_Walker()
							)
						);

					else : ?>

						<ul class="top-menu right">           
							<li class="menu-item"><a href="<?php echo esc_url( home_url('/') ); ?>"><?php esc_html_e( 'Homepage', 'calafate' ); ?></a></li>     
						    <li class="menu-item"><a href="<?php echo esc_url( admin_url('nav-menus.php') ); ?>"><?php esc_html_e( 'Set Up Your Menu', 'calafate' ); ?></a></li>
						</ul>

					<?php endif; ?>

				</nav>

			</div>

		</header>

		<?php global $post; if ( isset( $post ) ) calafate_hero_header( $post->ID ); ?>

		<div id="content" class="site-content">

			<?php if ( ! is_page_template( 'template-cover.php' ) ) : ?>

			<div id="primary" class="content-area wrapper">
			
				<main id="main" class="site-main grid">

				<?php endif; ?>