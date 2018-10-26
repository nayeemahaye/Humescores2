<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
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
 * @version     3.3.0
 */
?>
	
<?php 

	// Get style variables 

	if ( ! ( is_singular( 'product' ) || is_cart() ) ) {

		// User defined style
		$columns = get_theme_mod( 'calafate_shop_columns', '3' ); 
		$gap = get_theme_mod( 'calafate_shop_gap', '10' ); 

	} else {

		// Related style
		$columns = 3; 
		$gap = 15; 

	}

?>

<div class="portfolio-grid woocommerce-grid main-grid" data-columns="<?php echo esc_attr( $columns ); ?>" data-gap="<?php echo esc_attr( $gap ); ?>"> 