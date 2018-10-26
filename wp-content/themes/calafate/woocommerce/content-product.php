<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

// Grid variables

if ( ! is_singular('product') ) {

	// User defined style
	$columns = get_theme_mod( 'calafate_shop_columns', '3' ); 
	$portfolio_type = get_theme_mod( 'calafate_shop_style', 'Flexible' );
	$portfolio_aspect_ratio = get_theme_mod( 'calafate_shop_aspect_ratio', '4:3' );
	$portfolio_aspect_ratio = explode(':', $portfolio_aspect_ratio);

} else {

	// Related style
	$columns = 3; 
	$portfolio_type = 'Regular';
	$portfolio_aspect_ratio = array(1, 1);

}

$project_size = calafate_get_field( 'project-size' ) ? calafate_get_field( 'project-size' ) : 1;
if ( $project_size > $columns )
	$project_size = $columns;
if ( $portfolio_type != 'Flexible' ) 
	$project_size = 1;

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-portfolio uninit ' . calafate_categories( $post->ID, 'product_cat', ' ', 'slug', false ) . ' hover-' . get_theme_mod( 'calafate_shop_hover', 'one' ) ); ?> data-size="<?php echo $project_size; ?>">

	<?php // We decided to remove all hooks because we don't want any plugin to play with our theme's design. ?>

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

		<img src="<?php echo esc_url( $img_regular ); ?>" data-src-retina="<?php echo esc_url( $img_retina ); ?>"  srcset="data:image/gif;base64,R0lGODlhAQABAJH/AP///wAAAMDAwAAAACH5BAEAAAIALAAAAAABAAEAAAICVAEAOw==" itemprop="image" data-width="<?php echo esc_attr( $img_data_width ); ?>" data-height="<?php echo esc_attr( $img_data_height ); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" />

			<?php 

				$secondary_id = get_post_meta( $post->ID, 'product_featured-thumbnail-secondary_thumbnail_id', true );

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

						<img class="secondary" src="<?php echo esc_url( $img_regular ); ?>" data-src-retina="<?php echo esc_url( $img_retina ); ?>" alt="<?php the_title(); ?>" srcset="data:image/gif;base64,R0lGODlhAQABAJH/AP///wAAAMDAwAAAACH5BAEAAAIALAAAAAABAAEAAAICVAEAOw==" itemprop="image" data-width="<?php echo esc_attr( $img_data_width ); ?>" data-height="<?php echo esc_attr( $img_data_height ); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" />

						<?php

					}

				}

			?>
	
	</figure>

	<div class="entry-info">

		<a class="entry-title poppins ajax-link" href="<?php the_permalink(); ?>">
			<?php do_action( 'woocommerce_shop_loop_item_title' ); ?>
			<?php echo calafate_output_hero_header_image_for_preload( $post->ID ); ?>
		</a>

		<span><?php wc_get_template( 'loop/price.php' ); ?></span>
		
		<div class="entry-buttons">

			<?php echo apply_filters( 'woocommerce_loop_add_to_cart_link',
				sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="add-to-cart-style %s %s product_type_%s" data-product_title="%s">%s</a>',
					esc_url( $product->add_to_cart_url() ),
					esc_attr( $product->get_id() ),
					esc_attr( $product->get_sku() ),
					$product->is_purchasable() ? 'add_to_cart_button' : '',
					$product->is_type( 'variable' ) ? 'ajax-link' : 'ajax-add-to-cart ajax_add_to_cart',
					esc_attr( $product->get_type() ),
					esc_attr( get_the_title($product->get_id()) ),
					calafate_svg( 'cross', 'before_add' ) . calafate_svg( 'loading', 'while_add' ) . calafate_svg( 'check', 'after_add' )
				),
			$product ); ?>

			<a class="ajax-link" href="<?php the_permalink(); ?>"><?php echo calafate_svg( 'eye' ); ?></a>

		</div>

	</div>

</article>