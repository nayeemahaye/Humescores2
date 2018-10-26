<?php
/**
 * Template part for displaying minimal article list in index.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Calafate
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-minimal' ); ?> itemscope itemtype="http://schema.org/Article">

	<a class="ajax-link button" href="<?php the_permalink(); ?>" itemprop="url">

	<?php the_title( '<h3 class="entry-minimal__title" itemprop="name"><span>', '</span></h3>' ); ?>

		<time class="entry-minimal__time" datetime="<?php the_time( 'c' ); ?>" itemprop="datePublished">
			<?php 
				the_time( get_option( 'date_format' ) ); 
			?>
		</time>

		<?php if ( has_post_thumbnail() ) :

			$img = wp_get_attachment_url( get_post_thumbnail_id() );

			if ( strpos( $img, '.gif' ) === false ) {
				$img_regular = aq_resize( $img, 517, 330, true, true, true );
				$img_retina = aq_resize( $img, 940, 600, true, true, true  );
			} else {
				$img_regular = $img;
				$img_retina = $img;
			}

			$img_alt = get_post_meta( get_post_thumbnail_id() , '_wp_attachment_image_alt', true ) ?: get_the_title();

		?>

			<img class="entry-minimal__image" srcset="<?php echo esc_url( $img_regular ); ?> 517w, <?php echo esc_url( $img_retina ); ?> 940w" sizes="(min-width: 940px) 470px, 0" src="<?php echo $img_regular; ?>" alt="<?php echo $img_alt; ?>" />

		<?php endif; ?>

	</a>

</article><!-- #post-## -->
