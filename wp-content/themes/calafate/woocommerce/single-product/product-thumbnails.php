<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
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

$attachment_ids = $product->get_gallery_image_ids();
$variations = new WC_Product_Variable( $product->get_id() );
$variations = $variations->get_available_variations();

if ( $attachment_ids || $variations ) : ?>

	<div class="thumbnails">
		<div class="holder"><div><?php

		// featured image
		if ( has_post_thumbnail() ) {
			if ( ! ( calafate_get_field( 'project-hide-img', $post->ID ) && in_array( 'hide-featured', calafate_get_field( 'project-hide-img', $post->ID ) ) ) ) {
				calafate_woocommerce_product_gallery_thumbnail( get_post_thumbnail_id() );
			}
		}

		// gallery
		foreach ( $attachment_ids as $attachment_id ) {
			calafate_woocommerce_product_gallery_thumbnail( $attachment_id );
		}

		// variations
		if ( ! empty( $variations ) ) {
			$variation_image_ids = array();
			array_push( $variation_image_ids, get_post_thumbnail_id() );
			foreach ( $variations as $variation ) {
				if ( isset( $variation['image_id'] ) && $variation['image_id'] !== '0' && $variation['image_id'] !== '' && ! in_array( $variation['image_id'], $variation_image_ids ) ) {
					calafate_woocommerce_product_gallery_thumbnail( $variation['image_id'] );
					array_push( $variation_image_ids, $variation['image_id'] );
				}
			}
		}

		?></div></div>
	</div>

<?php endif;