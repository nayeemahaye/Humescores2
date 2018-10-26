<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

	<div class="calafate-tabs woocommerce-tabs">

		<div class="tabs-titles">

			<?php foreach ( $tabs as $key => $tab ) : ?>

				<h4 class="tab-title" id="tab-title-<?php echo esc_attr( $key ); ?>">
					<?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?>
				</h4>

			<?php endforeach; ?>

		</div>

		<div class="tabs-contents">

			<?php foreach ( $tabs as $key => $tab ) : ?>

				<div class="tab-content" id="tab-content-<?php echo esc_attr( $key ); ?>">
					<?php call_user_func( $tab['callback'], $key, $tab ); ?>
				</div>

			<?php endforeach; ?>

		</div>

	</div>

<?php endif;
	
global $product; 
echo wc_get_product_tag_list( $product->get_id(), ', ', '<div class="post-tags-woo"><p>' . calafate_svg( 'tag', 'icon' ), '</p></div>');