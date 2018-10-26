<?php
/**
 * The template for displaying all single portfolio types.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Calafate
 */

get_header(); ?>

		<?php if ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'one-whole grid__item' ); ?> itemscope itemtype="http://schema.org/Article">

				<div class="entry-content">

					<?php calafate_hero_tagline( $post->ID ); 

						the_content( sprintf(
							/* translators: %s: Name of current post. */
							wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'calafate' ), array( 'span' => array( 'class' => array() ) ) ),
							the_title( '<span class="screen-reader-text">"', '"</span>', false )
						) );

						wp_link_pages( array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'calafate' ),
							'after'  => '</div>',
						) );
										
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

					<?php calafate_single_portfolio_navigation( $post->ID ); ?>
					
				</div><!-- .entry-content -->

				<?php //calafate_entry_footer(); ?>

			</article><!-- #post-## -->

			<?php

			/* DISABLED - If comments are open or we have at least one comment, load up the comment template.
			if ( get_theme_mod( 'calafate_portfolio_comments' ) === 'enabled' && ( comments_open() || get_comments_number() ) ) :
				comments_template();
			endif;
			*/

		endif; // End of the loop.
		?>

<?php
get_footer();
