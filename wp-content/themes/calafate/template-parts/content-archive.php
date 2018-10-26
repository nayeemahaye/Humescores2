<?php
/**
 * Template part for displaying results in archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Calafate
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'grid entry-archive' ); ?>>

	<div class="entry-time grid__item desk--two-twelfths one-whole">
		<a href="<?php the_permalink(); ?>" class="ajax-link">
			<time class="entry-minimal__time" datetime="<?php the_time( 'c' ); ?>">
				<?php 
					the_time( get_option( 'date_format' ) ); 
				?>
			</time>
		</a>
	</div>

	<div class="grid__item desk--six-twelfths lap--six-twelfths palm--one-whole">
		<header><?php the_title( sprintf( '<h2 class="entry-title"><a class="ajax-link" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?></header>
		<section class="entry-excerpt"><?php echo calafate_excerpt( 'calafate_excerpt_length' ); ?></section>
	</div>


	<a href="<?php the_permalink(); ?>#comments" class="ajax-link entry-read-link right"><?php comments_number( esc_html__( '0 comments', 'calafate' ), esc_html__( '1 comment', 'calafate' ), esc_html__( '% comments', 'calafate' ) ); ?></a>

</article><!-- #post-## -->
