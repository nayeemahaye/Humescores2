<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
?>
<div class="images">

	<?php

		if ( ! ( calafate_get_field( 'project-hide-img', $post->ID ) && in_array( 'hide', calafate_get_field( 'project-hide-img', $post->ID ) ) ) ) {
				
			$attachment_ids = $product->get_gallery_image_ids();
			$variations = new WC_Product_Variable( $product->get_id() );
			$variations = $variations->get_available_variations();

			if ( $attachment_ids || $variations ) {

				echo '<div class="images-carousel images-view">';

					// featured image
					if ( has_post_thumbnail() ) {
						if ( ! ( calafate_get_field( 'project-hide-img', $post->ID ) && in_array( 'hide-featured', calafate_get_field( 'project-hide-img', $post->ID ) ) ) ) {
							calafate_woocommerce_product_gallery_image( get_post_thumbnail_id() );
						}
					}

					// gallery
					if ( ! empty( $attachment_ids ) ) {
						foreach ( $attachment_ids as $attachment_id ) {
							calafate_woocommerce_product_gallery_image( $attachment_id );
						}
					}

					// variations
					if ( ! empty( $variations ) ) {
						$variation_image_ids = array();
						array_push( $variation_image_ids, get_post_thumbnail_id() );
						foreach ( $variations as $variation ) {
							if ( isset( $variation['image_id'] ) && $variation['image_id'] !== '0' && $variation['image_id'] !== '' && ! in_array( $variation['image_id'], $variation_image_ids ) ) {
								calafate_woocommerce_product_gallery_image( $variation['image_id'], ' variation-' . $variation['variation_id'] );
								array_push( $variation_image_ids, $variation['image_id'] );
							}
						}
					}

				echo '</div>';

			} else if ( has_post_thumbnail() ) {

				echo '<div class="images-view">';
					calafate_woocommerce_product_gallery_image( get_post_thumbnail_id() );
				echo '</div>';

			} else {

				echo '<div class="images-view">';
					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_html__( 'Placeholder', 'calafate' ) ), $post->ID );
				echo '</div>';

			}

			do_action( 'woocommerce_product_thumbnails' );

		}

	?>

</div>
