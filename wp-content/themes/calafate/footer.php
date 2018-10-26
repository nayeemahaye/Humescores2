<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Calafate
 */

?>

			<?php if ( ! is_page_template( 'template-cover.php' ) ) : ?>

			</main><!-- #main -->
		</div><!-- #primary -->

		<?php endif; ?>

		<?php calafate_map(); ?>
		
	</div>

	<footer id="site-footer" class="site-footer wrapper">

		<?php dynamic_sidebar( 'sidebar-footer' ); ?>

	</footer><!-- #colophon -->

</div><!-- #content -->

<?php 

	global $post;

	if ( ( get_theme_mod( 'calafate_site_sharing', 'enabled' ) === 'enabled' || get_theme_mod( 'calafate_site_sharing', 'enabled' ) === 'enabled-icons' ) && isset( $post ) ) {
		if ( function_exists( 'is_woocommerce' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
			// nothing
		} else {
			calafate_social_sharing( $post->ID );
		}
	}

	if ( function_exists( 'is_woocommerce' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
		calafate_shop_actions();
	}

	if ( get_theme_mod( 'calafate_site_actions', 'enabled' ) === 'enabled' && isset( $post ) ) {
		calafate_site_actions( $post->ID );
	}

?>

	<div id="site-overlay">

		<?php get_search_form(); ?>

		<?php calafate_portfolio_filters(); ?>

		<?php if ( function_exists( 'calafate_shop_filters' ) )
			calafate_shop_filters(); ?>

		<div id="overlay-mouse"><?php echo calafate_svg( 'plus', 'close' ); ?></div>

	</div>

</div>

<?php if ( calafate_check_sidebar() ) : ?>

	<aside id="site-sidebar">

		<div id="site-sidebar-wrap">

			<div class="site-sidebar-holder">
				<div id="site-sidebar-closer"><?php echo calafate_svg( 'plus' ); ?></div>
				<div class="site-sidebar-content">
					<?php dynamic_sidebar( 'sidebar-main' ); ?>
				</div>
			</div>

			<div id="site-sidebar-opener"><?php echo calafate_svg( 'arrow' ); ?></div>

		</div>

	</aside>

<?php endif; ?>

<div id="preloader"><span>.</span><span>.</span><span>.</span></div>

<?php if ( function_exists( 'woocommerce_mini_cart' ) ) : ?>
	<div id="mini-cart">
		<div class="woocommerce widget_shopping_cart">
			<div class="widget_shopping_cart_content">
				<?php woocommerce_mini_cart(); ?>
			</div>
		</div><span id="regex-search-until-here"></span>
	</div>
<?php endif; ?>

<!-- IE 8 Message -->
<div id="oldie">
	<p><?php esc_html_e( 'This is a unique website which will require a more modern browser to work!', 'calafate' ); ?><br /><br />
	<a href="<?php echo esc_url( 'https://www.google.com/chrome/' ); ?>" target="_blank"><?php esc_html_e( 'Please upgrade today!', 'calafate' ); ?></a>
	</p>
</div>

<!-- No Scripts Message -->
<noscript id="scriptie">
	<div>
		<p><?php esc_html_e( 'This is a modern website which will require Javascript to work.', 'calafate' ); ?></p>
		<p><?php esc_html_e( 'Please turn it on!', 'calafate' ); ?></p>
	</div>
</noscript>

<?php wp_footer(); ?>

</body>
</html>