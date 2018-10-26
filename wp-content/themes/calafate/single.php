<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Calafate
 */

get_header(); ?>

		<?php if ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'one-half old-breakpoint--whole grid__item ' . get_theme_mod( 'calafate_post_style', 'post-half' ) ); ?> itemscope itemtype="http://schema.org/Article">

				<?php if ( get_theme_mod( 'calafate_post_style', 'post-half' ) != 'post-full w-hero' || calafate_get_field( 'hero-enabled', $post->ID ) == false ) : ?>

					<header class="entry-header">

						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

						<div class="entry-meta">

							<div class="time-author">
					
								<div class="time">
									<time datetime="<?php the_time( 'c' ); ?>" itemprop="datePublished">
										<?php
											the_time( get_option( 'date_format' ) ); 
										?>
									</time>
								</div>

								<!-- <div class="category palm--hide">
									<?php calafate_categories( $post->ID, 'category' ); ?>
								</div> -->

								<div class="author palm--hide">
									<?php esc_html_e( 'written by ', 'calafate' ); ?> 
									<span itemprop="author"><?php the_author_link(); ?></span>
								</div>

							</div>

							<?php if ( comments_open() ) : ?>
								<div class="comments desk--right palm--hide">
									<a href="<?php the_permalink(); ?>#comments" itemProp="commentCount"><?php comments_number( esc_html__( '0 comments', 'calafate' ), esc_html__( '1 comment', 'calafate' ), esc_html__( '% comments', 'calafate' ) ); ?></a>
								</div>
							<?php endif; ?>

						</div>

					</header><!-- .entry-header -->

				<?php endif; ?>

				<div class="entry-content">

					<?php

						if ( get_theme_mod( 'calafate_post_style', 'post-half' ) == 'post-full w-hero' && calafate_get_field( 'hero-enabled', $post->ID ) == true ) {
							calafate_hero_post_tagline( $post->ID ); 
						}

						the_content( sprintf(
							/* translators: %s: Name of current post. */
							wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'calafate' ), array( 'span' => array( 'class' => array() ) ) ),
							the_title( '<span class="screen-reader-text">"', '"</span>', false )
						) );

						wp_link_pages( array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages: ', 'calafate' ),
							'after'  => '</div>',
						) );

						the_tags( '<div class="post-tags"><p>' . calafate_svg( 'tag', 'icon' ), ', ', '</p></div>' ); 
										
						edit_post_link(
							sprintf(
								/* translators: %s: Name of current post */
								esc_html__( 'Edit %s', 'calafate' ),
								the_title( '<span class="screen-reader-text">"', '"</span>', false )
							),
							'<span><span class="edit-link">',
							'</span></span>'
						);

					?>

				</div><!-- .entry-content -->

				<?php calafate_entry_footer(); ?>

				<?php calafate_single_post_navigation( $post->ID, 'post-half' ); ?>

			</article><!-- #post-## -->

			<?php

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endif; // End of the loop.
		?>

<?php
get_footer();
