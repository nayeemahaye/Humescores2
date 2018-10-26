<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Calafate
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'grid entry-archive' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>

		<div class="entry-image grid__item three-twelfths lap--hide palm--hide">

			<?php	$img = wp_get_attachment_url( get_post_thumbnail_id() );
				$img_regular = aq_resize( $img, 330, 220, true, true, true );
				$img_retina = aq_resize( $img, 660, 440, true, true, true ); ?>

			<a href="<?php the_permalink(); ?>" class="ajax-link"><img src="<?php echo esc_url( $img_regular ); ?>" srcset="<?php echo esc_url( $img_regular ); ?> 330w, <?php echo esc_url( $img_retina ); ?> 660w" sizes="330px" alt="<?php the_title(); ?>" /></a>

		</div>

	<?php endif; ?>

	<div class="entry-search-excerpt grid__item four-twelfths">
		<header><?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" class="ajax-link" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?></header>
		<section class="entry-excerpt"><?php echo calafate_excerpt( 'calafate_excerpt_length' ); ?></section>
		<a href="<?php the_permalink(); ?>" class="right entry-read-link ajax-link"><?php esc_html_e( 'read', 'calafate' ); ?></a>
	</div>

	<div class="entry-time grid__item two-twelfths lap--one-whole palm--one-whole right">
		<a href="<?php the_permalink(); ?>" class="ajax-link">
			<time class="entry-minimal__time" datetime="<?php the_time( 'c' ); ?>">
				<?php 
					the_time( get_option( 'date_format' ) ); 
				?>
			</time>
		</a>
	</div>

</article><!-- #post-## -->
