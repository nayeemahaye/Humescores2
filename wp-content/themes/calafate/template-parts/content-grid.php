<?php
/**
 * Template part for displaying grid article list in index.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Calafate
 */

global $blog_cols;

$columns = $blog_cols;
$project_size = calafate_get_field( 'post_feature' ) ? calafate_get_field( 'post_feature' ) : 1;
$portfolio_type = 'Flexible';

?>

	<?php if ( $project_size === 'banner' ) : ?>

		<?php calafate_post_stamp( $post->ID ); ?>

	<?php else : ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-portfolio uninit' ); ?> data-size="<?php echo $project_size; ?>" itemscope itemtype="http://schema.org/Article">

			<a class="ajax-link button" href="<?php the_permalink(); ?>" itemprop="url">

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
							$img_height = $img_width / $portfolio_aspect_ratio[0] * $portfolio_aspect_ratio[2];
							$crop = true;
						} else {
							$img_height = null;
						}

						$img = wp_get_attachment_url( get_post_thumbnail_id() );
						
						if ( strpos( $img, '.gif' ) === false ) {

							$img_regular = aq_resize( $img, $img_width, $img_height, $crop, false, true );
							$img_retina = aq_resize( $img, $img_width*2, $img_height == null ? null : $img_height*2, $crop, false, true );

							$img_data_width = $img_regular[1];
							$img_data_height = $img_regular[2];

							if ( isset( $img_regular[3] ) ) {
								$img_data = wp_get_attachment_metadata( get_post_thumbnail_id() );
								if ( ! empty( $img_data ) ) {
									$img_data_width = $img_data['width'];
									$img_data_height = $img_data['height'];
								}
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

					<img src="<?php echo esc_url( $img_regular ); ?>" data-src-retina="<?php echo esc_url( $img_retina ); ?>" srcset="data:image/gif;base64,R0lGODlhAQABAJH/AP///wAAAMDAwAAAACH5BAEAAAIALAAAAAABAAEAAAICVAEAOw==" itemprop="image" width="<?php echo esc_attr( $img_data_width ); ?>" height="<?php echo esc_attr( $img_data_height ); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" />
					
				</figure>

				<header class="entry-caption">

					<div class="entry-neta">

						<time class="entry-grid__time" datetime="<?php the_time( 'c' ); ?>">
							<?php the_time( get_option( 'date_format' ) ); 	?>
						</time>

						<span class="entry-grid__category">
							<?php calafate_categories( $post->ID, 'category' ); ?>
						</span>
				
					</div>
				
						<h3 class="entry-sitle poppins" itemprop="name"><?php the_title(); ?></h3>

					</header>

			</a>

		</article><!-- #post-## -->

	<?php endif; ?>
