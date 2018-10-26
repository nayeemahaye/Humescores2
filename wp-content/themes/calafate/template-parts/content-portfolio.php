<?php
/**
 * Template part for displaying portfolio items.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Calafate
 */

global $columns, $gap, $portfolio_type, $portfolio_aspect_ratio, $portfolio_style;

$project_size = calafate_get_field( 'project-size' ) ? calafate_get_field( 'project-size' ) : 1;
if ( $project_size > $columns )
	$project_size = $columns;
if ( $portfolio_type != 'Flexible' ) 
	$project_size = 1;

$project_class = 'ajax-link';
$project_href = get_the_permalink();

// Since 1.0.2

$project_lightbox = calafate_get_field( 'portfolio-lightbox-type' );

$project_target = '';

if ( $project_lightbox ) {

	if ( $project_lightbox === 'img' ) {
		$project_class = 'fancybox fancybox-group';
		$project_href = calafate_get_field( 'portfolio-lightbox-url' );
	} else if ( $project_lightbox === 'iframe' ) {
		$project_class = 'fancybox fancybox-iframe fancybox-group';
		$project_href = calafate_get_field( 'portfolio-lightbox-url' );
	} else if ( $project_lightbox === 'url' ) {
		$project_class = 'no-ajax-link';
		$project_href = calafate_get_field( 'portfolio-lightbox-url' );
		$project_target = ' target="_self"';
	}

}

?>

<?php if ( has_post_thumbnail() ) : ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-portfolio uninit ' . calafate_categories( $post->ID, 'portfolio_category', ' ', 'slug', false ) . ' ' . $portfolio_style ); ?> data-size="<?php echo $project_size; ?>" itemscope itemtype="http://schema.org/CreativeWork">

		<a class="<?php echo $project_class; ?>" href="<?php echo esc_url( $project_href ); ?>" itemprop="url"<?php echo $project_target; ?>>

			<figure class="entry-thumbnail">

				<?php 

					// 1440 is the grid's width

					$crop = false;

					if ( $portfolio_type == 'Flexible' ) {
						$img_width = 1440 / $columns * $project_size; 
					} else {
						$img_width = 1440 / $columns;
					}

					if ( $portfolio_type == 'Regular' ) {
						$img_height = $img_width / $portfolio_aspect_ratio[0] * $portfolio_aspect_ratio[1];
						$crop = true;
					} else {
						$img_height = null;
					}
					
					$img_width += 100;
					if ( $img_height !== null ) {
						$img_height += 100;
					}

					$img = wp_get_attachment_url( get_post_thumbnail_id() );

					if ( strpos( $img, '.gif' ) === false ) {

						$img_regular = aq_resize( $img, $img_width, $img_height, $crop, false, true );
						$img_retina = aq_resize( $img, $img_width*2, $img_height == null ? null : $img_height*2, $crop, false, true );

						$img_data_width = $img_regular[1];
						$img_data_height = $img_regular[2];

						if ( isset( $img_regular[3] ) ) {
							$img_data = wp_get_attachment_metadata( get_post_thumbnail_id() );
							$img_data_width = $img_data['width'];
							$img_data_height = $img_data['height'];
						}

						$img_regular = $img_regular[0];
						$img_retina = $img_retina[0];

					} else {

						$img_regular = $img;
						$img_retina = $img;

						$img_data = wp_get_attachment_metadata( get_post_thumbnail_id() );
						$img_data_width = $img_data['width'];
						$img_data_height = $img_data['height'];

					}

				?>

				<img src="<?php echo esc_url( $img_regular ); ?>" data-src-retina="<?php echo esc_url( $img_retina ); ?>" srcset="data:image/gif;base64,R0lGODlhAQABAJH/AP///wAAAMDAwAAAACH5BAEAAAIALAAAAAABAAEAAAICVAEAOw==" itemprop="image" data-width="<?php echo esc_attr( $img_data_width ); ?>" data-height="<?php echo esc_attr( $img_data_height ); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" />

				<?php 

					$secondary_id = get_post_meta( $post->ID, 'portfolio_featured-thumbnail-secondary_thumbnail_id', true );

					if ( isset( $secondary_id ) && $secondary_id != '' ) {

						$img = wp_get_attachment_url( $secondary_id );

						if ( isset( $img ) ) {

							if ( strpos( $img, '.gif' ) === false ) {

								$img_regular = aq_resize( $img, $img_width, $img_height, $crop, false, true );
								$img_retina = aq_resize( $img, $img_width*2, $img_height == null ? null : $img_height*2, $crop, false, true );

								$img_data_width = $img_regular[1];
								$img_data_height = $img_regular[2];

								if ( isset( $img_regular[3] ) ) {
									$img_data = wp_get_attachment_metadata( get_post_thumbnail_id() );
									$img_data_width = $img_data['width'];
									$img_data_height = $img_data['height'];
								}

								$img_regular = $img_regular[0];
								$img_retina = $img_retina[0];

							} else {

								$img_regular = $img;
								$img_retina = $img;

								$img_data = wp_get_attachment_metadata( get_post_thumbnail_id() );
								$img_data_width = $img_data['width'];
								$img_data_height = $img_data['height'];

							}

							?> 

							<img class="secondary" src="<?php echo esc_url( $img_regular ); ?>" data-src-retina="<?php echo esc_url( $img_retina ); ?>" srcset="data:image/gif;base64,R0lGODlhAQABAJH/AP///wAAAMDAwAAAACH5BAEAAAIALAAAAAABAAEAAAICVAEAOw==" itemprop="image" data-width="<?php echo esc_attr( $img_data_width ); ?>" data-height="<?php echo esc_attr( $img_data_height ); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" />

							<?php

						}

					}

				?>
				
			</figure>

			<header class="entry-caption <?php echo esc_attr( $portfolio_style ); ?>">
				<div class="display--table">
					<div class="entry-caption-content display--table-cell">
						<div class="entry-caption-text">
							<span class="entry-meta"><?php calafate_categories( $post->ID, 'portfolio_category' ); ?></span>
							<h3 class="entry-title poppins" itemprop="name"><?php the_title(); ?></h3>
						</div>
					</div>
				</div>
			</header>

			<?php echo calafate_output_hero_header_image_for_preload( $post->ID ); ?>

		</a>

	</article><!-- #post-## -->

<?php endif; ?>