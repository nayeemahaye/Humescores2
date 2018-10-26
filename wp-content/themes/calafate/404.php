<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Calafate
 */

get_header(); ?>

	<div class="not-found" style="background-image: url(<?php echo esc_url( get_theme_mod( 'calafate_404_bg' ) ); ?>)">
		<div class="display--table">
			<div class="display--table-cell">
				<h1><?php esc_html_e( '404', 'calafate' ); ?></h1>
				<a class="ajax-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return to the homepage', 'calafate' ); ?></a>
			</div>
		</div>
	</div>

<?php
get_footer();
