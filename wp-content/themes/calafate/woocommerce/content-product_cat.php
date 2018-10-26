<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
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
	exit;
}


// Grid variables

$columns = get_theme_mod( 'calafate_shop_columns', '3' ); 
$portfolio_type = get_theme_mod( 'calafate_shop_style', 'Flexible' );
$project_size = 1;

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'product_cat entry-portfolio uninit ' . $category->slug . ' hover-' . get_theme_mod( 'calafate_shop_hover', 'one' ) ); ?> data-size="<?php echo $project_size; ?>">

	<?php // We decided to remove all hooks because we don't want any plugin to play with our theme's design. ?>

	<div class="entry-info">

		<a class="entry-title poppins ajax-link" href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">
			<h3><?php
				echo $category->name; 
				echo apply_filters( 'woocommerce_subcategory_count_html', ' <span>(' . $category->count . ')</span>', $category );
			?></h3>
		</a>
		
		<div class="entry-buttons" style="margin-left: -40px;">

			<a class="ajax-link" href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>"><?php echo calafate_svg( 'eye' ); ?></a>

		</div>

	</div>

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
			
			$img_width += 100;
			if ( $img_height !== null ) {
				$img_height += 100;
			}

			$thumb = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );

			if ( $thumb != '' ) {
				$img = wp_get_attachment_image_src( $thumb, 'full' )[0];
			} else {
				$img = get_template_directory_uri() . '/images/blank-product.jpg';
			}

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

		<img src="<?php echo esc_url( $img_regular ); ?>" data-src-retina="<?php echo esc_url( $img_retina ); ?>" srcset="data:image/gif;base64,R0lGODlhAQABAJH/AP///wAAAMDAwAAAACH5BAEAAAIALAAAAAABAAEAAAICVAEAOw==" data-width="<?php echo esc_attr( $img_data_width ); ?>" data-height="<?php echo esc_attr( $img_data_height ); ?>" itemprop="image" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" />
	
	</figure>

</article>
