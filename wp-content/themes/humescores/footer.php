<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Humescores
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
                <nav id="secondary-menu" class="social-media-links">
                    
                    <?php
                        
			wp_nav_menu( array('theme_location' => 'secondary', 'menu_id' => 'secondary-menu',
			) );
			?>
                </nav>
            
		<div class="site-info">
                    <span class="site-info-container">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'humescores' ) ); ?>">
				<?php
				/* translators: %s: CMS name, i.e. WordPress. */
				printf( esc_html__( 'Proudly powered by %s', 'humescores' ), 'WordPress' );
				?>
			</a>
			<span class="sep"> | </span>
				<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'humescores' ), 'humescores', '<a href="http://underscores.me/"> Nayeema Haye</a>' );
				?>
                    </span>
		</div><!-- .site-info -->
            
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
