<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Calafate
 */

get_header(); ?>

	<?php	while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'one-whole grid__item' ); ?>>

			<div class="entry-content page-content">

				<?php calafate_hero_tagline( $post->ID ); 

					the_content();

					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'calafate' ),
						'after'  => '</div>',
					) )
					;
				?>
			</div><!-- .entry-content -->

			<footer class="entry-footer">
				<?php
					edit_post_link(
						sprintf(
							/* translators: %s: Name of current post */
							esc_html__( 'Edit %s', 'calafate' ),
							the_title( '<span class="screen-reader-text">"', '"</span>', false )
						),
						'<span class="edit-link">',
						'</span>'
					);
				?>
			</footer><!-- .entry-footer -->
			
		</article><!-- #post-## -->

		<?php 

		endwhile; // End of the loop. ?>
			
<?php
get_footer();
