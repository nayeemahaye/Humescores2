<?php
/**
 * Calafate WooCommerce functions
 *
 * @package Calafate
*/

/** Prep theme
 *
 * @since 1.0.0
*/

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
  add_theme_support( 'woocommerce' );
}

add_filter( 'woocommerce_enqueue_styles', '__return_false' );

remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);

/** Dequeue prettyPhoto
 *
 * @since 1.0.0
*/

function prettyphoto_dequeue_script() {
	wp_dequeue_script( 'prettyPhoto' );
	wp_dequeue_script( 'prettyPhoto-init' );
}
add_action('wp_print_scripts', 'prettyphoto_dequeue_script', 100);

function prettyphoto_dequeue_style() {
	wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
}
add_action('wp_footer', 'prettyphoto_dequeue_style', 100);

/** Breadrcumbs structure
 *
 * @since 1.0.0
*/

function calafate_woocommerce_breadcrumbs() {
	return array(
		'delimiter' => '<span class="sep">&nbsp;&#47;&nbsp;</span>',
		'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
		'wrap_after' => '</nav>',
		'before' => '<span class="item">',
		'after' => '</span>',
		'home' => __( 'Shop', 'calafate' ),
	);
}

function calafate_breadcrumb_home_url() {
	return get_permalink( wc_get_page_id( 'shop' ) );
}

add_filter( 'woocommerce_breadcrumb_home_url', 'calafate_breadcrumb_home_url' );
add_filter( 'woocommerce_breadcrumb_defaults', 'calafate_woocommerce_breadcrumbs' );

add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 9, 0 );

add_action( 'woocommerce_before_cart', 'woocommerce_breadcrumb' );
add_action( 'woocommerce_before_checkout_form', 'woocommerce_breadcrumb' );
add_action( 'woocommerce_account_navigation', 'woocommerce_breadcrumb', 1, 0 );


/** Reorder things on single product page
 * (actually, this is the build of the single product page using only WooCommerce actions)
 *
 * @since 1.0.0
*/

// move cart form below price
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 13 );

// move item ratins below cart
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 15 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// add short description title
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 19 );

// move images after summary
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_show_product_images', 1 );

// append page builder content
function calafate_add_the_content() {
	echo '<div class="content-holder entry-content">';
	the_content();
	echo '</div>';
}
add_action( 'woocommerce_after_single_product_summary', 'calafate_add_the_content', 5 );

// wrap main content
function calafate_wrap_main_content_start() {

	global $post;
	
	$imgs_display = 'show';
	if ( calafate_get_field( 'project-hide-img', $post->ID ) && in_array( 'hide', calafate_get_field( 'project-hide-img', $post->ID ) ) ) {
		$imgs_display = 'hide';
	}

	echo '<div class="grid__item one-whole imgs-' . $imgs_display . '">';

}
add_action( 'woocommerce_before_main_content', 'calafate_wrap_main_content_start', 1 );

function calafate_wrap_main_content_end() {
	echo '</div>';
}
add_action( 'woocommerce_after_main_content', 'calafate_wrap_main_content_end', 1 );

// navigation between single products
function calafate_single_product_navigation() {

	$next_post = get_adjacent_post( false, '', true, 'product_cat' );

	if ( empty( $next_post ) ) {

		$first_post = wp_get_recent_posts( array(
			'numberposts' => 1,
			'post_type' => 'product'
		) );

		$next_postid = $first_post[0]['ID'];

	} else {

		$next_postid = $next_post->ID;

	}

	$output = '<nav class="entry-navigation entry-navigation--portfolio one-half portable--one-whole"><div>';
		$output .= calafate_post_navigation_item( $next_postid, esc_html__( 'Next', 'calafate' ) );
	$output .= '</div></nav>';

	echo $output;

}

add_action( 'woocommerce_after_single_product_summary', 'calafate_single_product_navigation', 25 );

//

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

/** Single product ratings
 *
 * @since 1.0.0
*/

function calafate_woocommerce_rating() {

  global $product;
	$average = $product->get_average_rating();

  $output = '<div class="star-rating" title="' . sprintf( esc_html__( 'Rated %d out of 5', 'calafate' ), $average ) . '">';

    for ( $i = 1; $i <= 5; $i++ ) {
    	$output .= '<span class="star ' . ( $i < $average ? 'full' : 'empty') . '">' . calafate_svg( 'star' ) . '</span>';
    }

    $output .= '<span class="show-reviews">' . esc_html__( 'View reviews', 'calafate' ) . '</span>';

  $output .= '</div>';

	echo $output;
    
}

if ( get_option( 'woocommerce_enable_review_rating' ) == 'yes' )
  add_filter( 'woocommerce_single_product_summary', 'calafate_woocommerce_rating', 20 );


/** Products filtering
 *
 * @since 1.0.0
*/

function fix_woocommerce_all_link() {

	if ( function_exists( 'wc_get_page_id' ) ) {
		echo '<script type="text/javascript">
			jQuery(\'select[name="product_cat"]\').on("change", function(){
				document.location.href = "' . get_permalink( wc_get_page_id( 'shop' ) ) . '";
			})
		</script>';
	}

	if ( function_exists( 'wc_get_template' ) ) {
		wc_get_template( 'single-product/add-to-cart/variation.php' );
	}

}
add_filter('wp_footer', 'fix_woocommerce_all_link');

/** Outputs shop filters
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_shop_filters' ) ) : 

	function calafate_shop_filters() {

		if ( is_shop() ) {

			$output = '';

			$categories = get_categories( array( 'taxonomy' => 'product_cat' ) );

			if ( ! empty ( $categories ) && sizeof( $categories ) > 1 ) {
				
				$output .= '<div id="portfolio-filters">
					<ul class="filters-list overlay-menu wrapper">';

					foreach ( $categories as $filter ) {

				    $thumbnail_id = get_woocommerce_term_meta( $filter->term_id, 'thumbnail_id', true );
				    $filter_image = wp_get_attachment_url( $thumbnail_id );

						$output .= '<li data-img="' . esc_url( $filter_image ) . '"><a href="#' . $filter->slug . '" data-filter=".' . $filter->slug . '">
							' . $filter->name . '
							<span class="no">' . $filter->count . '</span>
						</a></li>';

					}

					$count_posts = wp_count_posts( 'product' );
					$published_posts = $count_posts->publish;

					$output .= '<li><a href="#" data-filter="*" class="selected">
						' . esc_html__( 'All', 'calafate' ) . '
						<span class="no">' . $published_posts . '</span>
					</a></li>';

				$output .= '</ul></div>';

			}

			echo $output;

		}

	}

endif;

if ( ! function_exists( 'calafate_shop_filters_button' ) ) :

	function calafate_shop_filters_button() {

		global $post;

		if ( is_shop() ) {

			$output = '';

			$categories = get_categories( array( 'taxonomy' => 'product_cat' ) );

			if ( ! empty ( $categories ) && sizeof( $categories ) > 1 ) {

				$output .= '<a href="#" class="open-filters"><span class="dots-close-anim"><span class="d1"></span><span class="d2"></span><span class="d3"></span></span></a>';

			}

			echo $output;

		}

	}

endif;

/** Pagination
 *
 * @since 1.1.5
*/

add_action( 'woocommerce_product_query', 'calafate_woocommerce_product_query' );
function calafate_woocommerce_product_query( $q ) {
    if ( $q->is_main_query() && ( $q->get( 'wc_query' ) === 'product_query' ) ) {
    		$ppp = get_theme_mod( 'calafate_shop_page', '-1' );
        $q->set( 'posts_per_page', $ppp );
    }
}

remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
function calafate_woocommerce_pagination() {
	calafate_posts_navigation( ' bigger poppins' );	
}
add_action( 'woocommerce_after_shop_loop', 'calafate_woocommerce_pagination', 10);

/** Checkout form structure
 *
 * @since 1.0.0
*/

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );

add_filter( 'woocommerce_checkout_fields', 'calafate_woocommerce_checkout_fields' );

function calafate_woocommerce_checkout_fields( $fields ) {

	foreach ( $fields['billing'] as $key => &$field ) {

		if ( $key == 'billing_first_name' || $key == 'billing_phone' ) {
			$field['class'] = array( 'grid__item one-third palm--one-whole' );
		} else if ( $key == 'billing_last_name' || $key == 'billing_email' ) {
			$field['class'] = array( 'grid__item two-thirds palm--one-whole' );
		} else if ( $key == 'billing_state' || $key == 'billing_postcode' ) {
			$field['class'] = array( 'grid__item one-half palm--one-whole' );
		}else {
			$field['class'] = array( 'grid__item one-whole palm--one-whole' );
		}

	}

	foreach ( $fields['shipping'] as $key => &$field ) {

		if ( $key == 'shipping_first_name' || $key == 'shipping_phone' ) {
			$field['class'] = array( 'grid__item one-third palm--one-whole' );
		} else if ( $key == 'shipping_last_name' || $key == 'shipping_email' ) {
			$field['class'] = array( 'grid__item two-thirds palm--one-whole' );
		} else if ( $key == 'shipping_state' || $key == 'shipping_postcode' ) {
			$field['class'] = array( 'grid__item one-half palm--one-whole' );
		} else {
			$field['class'] = array( 'grid__item one-whole palm--one-whole' );
		}

	}

	$fields['order']['order_comments']['class'] = array( 'grid__item one-whole' );

	return $fields;

}

/** WooCommerce CSS
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_woocommerce_css' ) ) :

	function calafate_woocommerce_css() {

		global $post;

		$shop_txt_color = get_theme_mod( 'calafate_shop_txt', '#fff' );
		$shop_grid_bg_color = get_theme_mod( 'calafate_shop_grid_bg', '#fff' );
		$shop_grid_txt_color = get_theme_mod( 'calafate_shop_grid_txt', '#000' );

		$woocommerce_css = "

			/* WooCommerce General Style */

			.entry-portfolio.product .entry-title, .entry-portfolio.product .entry-buttons a:before {
				background-color: ${shop_grid_bg_color};
			}
			.entry-portfolio.product .entry-title, .entry-portfolio.product .price {
				color: ${shop_grid_txt_color} !important;
			}
			.entry-portfolio.product .entry-buttons a.add-to-cart-style svg *, .entry-portfolio.product .entry-buttons a.ajax-link svg * {
				fill: ${shop_grid_txt_color} !important;
			}

		";

		if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {

			$product_label = esc_html__( 'Product', 'calafate' );
			$price_label = esc_html__( 'Price', 'calafate' );
			$total_label = esc_html__( 'Total', 'calafate' );
			$remove_label = esc_html__( 'Remove', 'calafate' );
			$sale_label = esc_html__( 'Old Price', 'calafate' );
			$reviews_label = esc_html__( 'Reviews', 'calafate' );
			$score_1_label = esc_html__( 'Very bad', 'calafate' );
			$score_2_label = esc_html__( 'Not that bad', 'calafate' );
			$score_3_label = esc_html__( 'Average', 'calafate' );
			$score_4_label = esc_html__( 'Good', 'calafate' );
			$score_5_label = esc_html__( 'Perfect', 'calafate' );
			$form_label = esc_html__( 'Change your details', 'calafate' );

			$acc_color = get_theme_mod( 'calafate_acc_color', '#0FFFBE' );
			$acc_fgd_color = get_theme_mod( 'calafate_acc_fgd_color', '#111' );

			$shop_bg_color = get_theme_mod( 'calafate_shop_bg', '#000' );

			$woocommerce_css .= "

				/* WooCommerce Style */
		
				body.woocommerce-page:not(.color-scheme-1), body.woocommerce-page:not(.color-scheme-1) #site-header.sticky, div.quantity input {
					background-color: ${shop_bg_color};
					color: ${shop_txt_color};
				}			

				body, .hero-header .overlay, #site-header.sticky, .single-product .summary:before, .single-product .images .overlay {
					background-color: {$shop_bg_color};
				}

				.blockOverlay {
					background-color: ${shop_bg_color} !important;
				}
				body.woocommerce-page:not(.color-scheme-1) #site-header.sticky { 
					box-shadow: 0 10px 20px rgba(" . calafate_hex2RGB($shop_bg_color, true) . ", 0.06);
				}
				#site-overlay, .single-product .summary form .quantity:before {
					background-color: rgba(" . calafate_hex2RGB($shop_bg_color, true) . ", 0.95);
				}
				.overlay-menu + .filters-images div:after {
					background-color: rgba(" . calafate_hex2RGB($shop_bg_color, true) . ", 0.75);
				}
				td.actions, .shop_table .woocommerce-shipping-calculator a:after {
					background-color: rgba(" . calafate_hex2RGB($shop_txt_color, true) . ", 0.07);
				}
				.entry-navigation--double:after, .entry-meta li:not(:first-child):not(.desk--right):before, .single-portfolio .entry-navigation__info .meta:last-child:before, .overlay-menu li:after, hr, .dots-close-anim span, body .lines, body .lines:before, body .lines:after, .entry-navigation__item--prev:after, .single-product .summary form .quantity:before, .hero-carousel-paging li.dot.is-selected {
					background-color: ${shop_txt_color};
				}
				table, table *, .hero-carousel-paging li.dot, div.quantity input, .woocommerce-message a, .woocommerce-page .button, .woocommerce-page input[type=\"submit\"].button, .calafate-tabs .tab-title.active  {
					border-color: ${shop_txt_color};
				}
				body.woocommerce-page:not(.color-scheme-1) a, body.woocommerce-page:not(.color-scheme-1) #site-header .image-logo-disabled span, body.woocommerce-page:not(.color-scheme-1) #site-header .text-logo, body.woocommerce-page:not(.color-scheme-1) #site-share a:not(:last-child):after, body.woocommerce-page:not(.color-scheme-1) #preloader span, .single-product .summary .price del:before, #site-overlay #searchform input, .body.woocommerce-page:not(.color-scheme-1) .cf-7.mailchimp .wpcf7-form-control-wrap input, .select2-search input, .select2-container--default .select2-search--dropdown .select2-search__field {
					color: ${shop_txt_color};
				}

				#checkout_coupon input[type=\"submit\"], .woocommerce-account form input[type=\"submit\"].button {
					color: ${shop_txt_color} !important;
				}	
				#checkout_coupon input[type=\"submit\"]:hover, .woocommerce-MyAccount-content form input[type=\"submit\"].button {
					color: ${acc_color} !important;
				}	

				body.woocommerce-page:not(.color-scheme-1) #content svg *, body.woocommerce-page:not(.color-scheme-1), #site-actions svg *, body.woocommerce-page:not(.color-scheme-1), #site-overlay *, #site-share svg path {
				  fill: {$shop_txt_color};
				  stroke: {$shop_txt_color};
				}
				body .responsive-bag svg *, body .responsive-search svg * {
				  fill: {$shop_txt_color} !important;
				}

				input::-webkit-input-placeholder, textarea::-webkit-input-placeholder { color: ${shop_txt_color} !important; }
				input::-moz-placeholder, textarea::-moz-placeholder { color: ${shop_txt_color} !important; }
				input:-ms-input-placeholder, textarea:-ms-input-placeholder { color: ${shop_txt_color} !important; }
				input::placeholder, textarea::placeholder { color: ${shop_txt_color} !important; }

				.woocommerce-page input[type=\"submit\"].button, form.woocommerce-checkout label abbr[title], .woocommerce-MyAccount-content form label abbr[title], .woocommerce-page .wc-proceed-to-checkout .button:hover, #reviews #review_form_wrapper input[type=\"submit\"] {
					background-color: ${acc_color} !important;
					color: rgba(" . calafate_hex2RGB($acc_fgd_color, true) . ", 0.65) !important;
				}
				.single-product .summary .cart button[type=\"submit\"] {
					border-color: ${shop_txt_color};
					color: ${shop_txt_color};
				}
				.single-product .summary .cart button[type=\"submit\"]:hover {
					background-color: ${shop_txt_color} !important;
					color: ${shop_bg_color} !important;
				}
				.single-product form.cart button[type=\"submit\"] .loading svg circle {
					fill: ${shop_txt_color} !important;
				}
				.single-product form.cart button[type=\"submit\"]:hover .loading svg circle {
					fill: ${shop_bg_color} !important;
				}
				.woocommerce-page input[type=\"submit\"].button:hover, #reviews #review_form_wrapper input[type=\"submit\"]:hover {
					color: ${acc_color} !important;
					background-color: ${acc_fgd_color} !important;
				}

				body.woocommerce-page a.chck-link, .shop_table .order-total .woocommerce-Price-amount, .calafate-checkout-navigation li.done:before, .wc-proceed-to-checkout, .woocommerce-page .wc-proceed-to-checkout .button, .woocommerce-MyAccount-content mark {
					color: ${acc_color};
				}
				body a.chck-link:after, form.woocommerce-checkout input[type=\"checkbox\"]:checked + span:before, .woocommerce-MyAccount-content form input[type=\"checkbox\"]:checked + span:before, form.woocommerce-checkout input[type=\"radio\"]:checked + label:before, .woocommerce-MyAccount-content form input[type=\"radio\"]:checked + label:before, #shipping_method input[type=\"radio\"]:checked + label:before, .wc-proceed-to-checkout .button:hover, .woocommerce-page input[name=\"apply_coupon\"].button:hover, .entry-portfolio.product .entry-buttons a:hover:before, .woocommerce-cart .return-to-shop a:after, .woocommerce-MyAccount-navigation li a:after, .woocommerce-edit-address .woocommerce-MyAccount-content .woocommerce-Address-title a:after, .single-product .summary form .button[type=\"submit\"] .text:after {
					background-color: ${acc_color};
				}

				.wc-proceed-to-checkout .button, .woocommerce-page input[name=\"apply_coupon\"].button:hover, .shop_table td.actions input[type=\"submit\"]:hover, .woocommerce-account form input[type=\"submit\"].button:hover {
					border-color: ${acc_color};
				}
				.woocommerce-page input[name=\"apply_coupon\"].button {
				}


				form.woocommerce-checkout input:-webkit-autofill, form.woocommerce-checkout textarea:-webkit-autofill, .woocommerce-MyAccount-content form input:-webkit-autofill, .woocommerce-MyAccount-content form textarea:-webkit-autofill {
				    box-shadow: 0 0 0px 1000px rgba(" . calafate_hex2RGB($shop_txt_color, true) . ", 0.15) inset;
				}
				form.woocommerce-checkout input, form.woocommerce-checkout textarea, .woocommerce-MyAccount-content form input, .woocommerce-MyAccount-content form textarea, .select2-container .select2-choice, .select2-drop, .select2-results .select2-highlighted, .calafate-checkout-navigation li:before, .calafate-checkout-navigation li .line:before, .calafate-checkout-navigation li .line:after, .shop_table .shipping-calculator-form input[type=\"text\"], .woocommerce-account .calafate-checkout-navigation .icon, .select2-container--default .select2-selection--single, .select2-container--default .select2-results__option--highlighted[aria-selected], .select2-container--default .select2-results__option--highlighted[data-selected] {
					color: ${shop_txt_color};
					background-color: rgba(" . calafate_hex2RGB($shop_txt_color, true) . ", 0.15);
				}
				.select2-container--default .select2-results__option[aria-selected=true], .select2-container--default .select2-results__option[data-selected=true] {
					color: ${shop_txt_color};
					background-color: rgba(" . calafate_hex2RGB($shop_txt_color, true) . ", 0.45);
				}
				.calafate-checkout-navigation li:hover:before, .woocommerce-account .calafate-checkout-navigation li:hover .icon {
					background-color: rgba(" . calafate_hex2RGB($shop_txt_color, true) . ", 0.3);
				}
				.calafate-checkout-navigation li.active:before, .woocommerce-account .calafate-checkout-navigation li.active .icon {
					background-color: ${shop_txt_color};
					color: ${shop_bg_color};
				}
				.woocommerce-account .calafate-checkout-navigation li.active .icon * {
					fill: ${shop_bg_color} !important;
				}
				.select2-default, .select2-container--default .select2-selection--single .select2-selection__rendered {
					color: ${shop_txt_color} !important;
				}
				.shop_table.woocommerce-checkout-review-order-table tbody, .shop_table td.actions div.coupon input[type=\"text\"], .calafate-tabs .tabs-titles, .woocommerce-MyAccount-navigation {
					border-color: rgba(" . calafate_hex2RGB($shop_txt_color, true) . ", 0.15);
				}
				.select2-drop-active, .select2-drop.select2-drop-above.select2-drop-active, .select2-container--default .select2-search--dropdown .select2-search__field {
					border: 2px solid rgba(" . calafate_hex2RGB($shop_txt_color, true) . ", 0.1);
					background: ${shop_bg_color};
				}
				.select2-dropdown {
					background: ${shop_bg_color};
					border: 4px solid rgba(" . calafate_hex2RGB($shop_txt_color, true) . ", 0.1) !important;
				}
				.select2-container .select2-choice .select2-arrow b:after, .select2-container--default .select2-selection--single .select2-selection__arrow b {
					border-top-color: ${shop_txt_color};
				}
				.select2-dropdown-open .select2-choice .select2-arrow b:after, .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
					border-bottom-color: ${shop_txt_color};
					border-top-color: transparent;
				}

				.simple-select-cover .simple-select-inner, .woocommerce-order-received .woocommerce-MyAccount-content > p:first-child , .woocommerce-view-order .woocommerce-MyAccount-content > p:first-child {
					background-color: rgba(" . calafate_hex2RGB($shop_txt_color, true) . ", 0.1);
				}
				.single-product .summary .price del {
					color: rgba(" . calafate_hex2RGB($shop_txt_color, true) . ", 0.5);
					background-color: rgba(" . calafate_hex2RGB($shop_txt_color, true) . ", 0.1);
				}
				.single-product .summary .price del span.amount:first-child:before {
					border-left-color: rgba(" . calafate_hex2RGB($shop_txt_color, true) . ", 0.1);
				}
				body .top-menu .cart-item a, .shop_table div.coupon input[type=\"text\"] {
					color: rgba(" . calafate_hex2RGB($shop_txt_color, true) . ", 0.5) !important;
				}

				/* WooCommerce Text */

				.shop_table td.product-name > *:before, .order_item td.product-name:before, .woocommerce-checkout .cart_item td.product-name:before {
					content: '{$product_label}';
				}
				.shop_table td.product-price > *:before {
				 	content: '{$price_label}';
				}
				.shop_table td.product-subtotal > *:before, .woocommerce-checkout .cart_item td.product-total:before, .order_item td.product-total:before {
					content: '{$total_label}';
				}
				.shop_table td.product-remove > *:before {
					content: '{$remove_label}';
				}
				.price del:before {
					content: '{$sale_label}';
				}
				#reviews h2:before {
					/*content: '{$reviews_label}';*/
				}
				#reviews li.score-1 .comment_container:after {
					content: '{$score_1_label}';
				}
				#reviews li.score-2 .comment_container:after {
					content: '{$score_2_label}';
				}
				#reviews li.score-3 .comment_container:after {
					content: '{$score_3_label}';
				}
				#reviews li.score-4 .comment_container:after {
					content: '{$score_4_label}';
				}
				#reviews li.score-5 .comment_container:after {
					content: '{$score_5_label}';
				}
				.woocommerce-MyAccount-content form h3:after {
					content: '{$form_label}';
				}
				.flickity-prev-next-button {
					background-color: {$shop_bg_color} !important;
				}

			";

		}

		return $woocommerce_css;

	}

endif;

/** Change related / upsells / cross-sells products number
 *
 * @since 1.0.0
*/

add_filter( 'woocommerce_output_related_products_args', 'calafate_related_products_args' );

if ( ! function_exists( 'calafate_related_products_args') ) :
	function calafate_related_products_args( $args ) {
		$args['posts_per_page'] = 3; 
		return $args;
	}
endif; 

//

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'calafate_output_upsells', 15 );

if ( ! function_exists( 'calafate_output_upsells' ) ) :
	function calafate_output_upsells() {
    woocommerce_upsell_display( 3 ); 
	}
endif;

//

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart_table', 'woocommerce_cross_sell_display' );
 
add_filter( 'woocommerce_cross_sells_total', 'calafate_change_cross_sells_product_no' );
  
if ( ! function_exists( 'calafate_change_cross_sells_product_no' ) ) :
	function calafate_change_cross_sells_product_no( $columns ) {
		return 3;
	}
endif; 

/** Styles handling
 *
 * @since 1.0.0
*/

function calafate_woocommerce_styles() {

	global $post;

	if ( is_cart() || is_checkout() || is_account_page() ) {
 		wp_enqueue_style( 'select2', get_template_directory_uri() . '/js/vendor/select2/select2.min.css' );
	}

}

add_action( 'wp_enqueue_scripts', 'calafate_woocommerce_styles', 1 );

/** Scripts handling
 *
 * @since 1.0.0
*/

function calafate_woocommerce_scripts() {

	global $post;
	global $calafate_js_debug;

	// WooCommerce scripts localization

	wp_localize_script(
		( $calafate_js_debug === 'true' ? 'calafate-f-woo' : 'calafate-main-min' ),
		'wooSVG',
		array(
			'loading'  => calafate_svg( 'loading', 'loading' ),
			'plus' => calafate_svg( 'plus-circle', 'icon' ),
			'close' => calafate_svg( 'close-circle', 'icon' )
		)
	);

	wp_localize_script(
		( $calafate_js_debug === 'true' ? 'calafate-f-woo' : 'calafate-main-min' ),
		'wooLang',
		array(
			'write_review'  => esc_html__( 'Write a review', 'calafate' ),
			'close_review'  => esc_html__( 'Close', 'calafate' ),
			'posted_review'  => esc_html__( 'Your review was posted and it is awaiting moderation.', 'calafate' ),
			'duplicate_review'  => esc_html__( 'Duplicate content detected. It seems that you\'ve posted this before.', 'calafate' ),
			'posting_review'  => esc_html__( 'Posting your review, please wait...', 'calafate' ),
			'required_review'  => esc_html__( 'Please complete all the required fields.', 'calafate' ),
			'required_rating' => esc_html__( 'Please select a rating.', 'calafate' ),
			'added_to_cart' => esc_html__( 'has been added to your cart.', 'calafate' ),
			'view_cart_button' => sprintf(
				/* translators: %s: Cart url. */
				wp_kses( __( '<a class="button wc-forward" href="%s">View Cart</a>', 'calafate' ), array( 'a' => array( 'href' => array(), 'class' => array() ) ) ),
				wc_get_cart_url()
			) 
		)
	);

	wp_localize_script(
		( $calafate_js_debug === 'true' ? 'calafate-f-woo' : 'calafate-main-min' ),
		'wooScripts',
		array(
			'add_to_cart' => esc_url( WC()->plugin_url() . '/assets/js/frontend/add-to-cart.js' ),
			'add_to_cart_variation'  => esc_url( WC()->plugin_url() . '/assets/js/frontend/add-to-cart-variation.min.js' ),
			'underscore' => esc_url( includes_url() . 'js/underscore.min.js' ),
			'wp_util' => esc_url( includes_url() . 'js/wp-util.min.js' ),
			'blockUI' => esc_url( WC()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI.min.js' )
		)
	);

	wp_localize_script(
		( $calafate_js_debug === 'true' ? 'calafate-f-woo' : 'calafate-main-min' ),
		'wc_add_to_cart_variation_params',
		array(
			'i18n_no_matching_variations_text' => esc_html__( 'Sorry, no products matched your selection. Please choose a different combination.', 'calafate' ),
			'i18n_make_a_selection_text' => esc_html__( 'Please select some product options before adding this product to your cart.', 'calafate' ),
			'i18n_unavailable_text' => esc_html__( 'Sorry, this product is unavailable. Please choose a different combination.', 'calafate' )
		)
	);

	wp_localize_script(
		( $calafate_js_debug === 'true' ? 'calafate-f-woo' : 'calafate-main-min' ),
		'wc_add_to_cart_params',
		array(
			'ajax_url'                => WC()->ajax_url(),
			'wc_ajax_url'             => WC_AJAX::get_endpoint( "%%endpoint%%" ),
			'i18n_view_cart'          => esc_attr__( 'View Cart', 'calafate' ),
			'cart_url'                => apply_filters( 'woocommerce_add_to_cart_redirect', wc_get_cart_url() ),
			'is_cart'                 => is_cart(),
			'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' )
		)
	);

	// for more WooCommerce localizations look in the plugin, within the class-wc-frontend-scripts.php, get_script_data() function 

}

add_action( 'wp_enqueue_scripts', 'calafate_woocommerce_scripts', 10 );

/** Add cart to menu 
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_wp_nav_menu_add_cart' ) ) :

	function calafate_wp_nav_menu_add_cart( $items, $args ) {
		$items .= '<li class="cart-item"><a href="#" class="open-cart">' . esc_html__( 'Cart', 'calafate' ) . '<sup class="woocommerce-cart-no">' . WC()->cart->get_cart_contents_count() . '</sup></a></li>';
		return $items;
	} 

endif;

add_filter( 'wp_nav_menu_items', 'calafate_wp_nav_menu_add_cart', 10, 2 );

/** Mini cart structure (prepend)
 *
 * @since 1.0.0
*/

function calafate_woocommerce_before_mini_cart() {

	$output = '<div class="remove-button"><div><span class="icon">→</span><span class="text">' . esc_html__( 'Back', 'calafate' ) . '</div></div>';
	$output .= '<div class="cart-header poppins">
		<a href="' . wc_get_cart_url() . '"></a>
		<h4>' . esc_html__( 'Your cart', 'calafate' ) . '</h4>
		<span class="woocommerce-cart-no">' . WC()->cart->get_cart_contents_count() . '</span>
	</div>';

	echo $output;

}

add_action( 'woocommerce_before_mini_cart', 'calafate_woocommerce_before_mini_cart', 10, 0 );

/** Mini cart structure (append)
 *
 * @since 1.0.0
*/

function calafate_woocommerce_after_mini_cart() {

	$output = '<div class="cart-summary poppins">';

		$output .= '<div class="cart-totals"><div>';
			$output .= '<h5>' . esc_html__( 'Total', 'calafate' ) . '</h5>';
			$output .= '<span class="cart-total">' . WC()->cart->get_cart_subtotal() . '</span>';
		$output .= '</div></div>';

		$output .= '<a class="cart-checkout" href="' . WC()->cart->get_checkout_url() . '">' . esc_html__( 'Checkout', 'calafate' ) . '</a>';

	$output .= '</div>';

	$output .= '<div class="empty-cart">
		<span class="title">' . esc_html__( 'Empty', 'calafate' ) . '</span>
		' . calafate_svg( 'face', 'icon' ) . '
	</div>';

	echo $output;

}

add_action( 'woocommerce_after_mini_cart', 'calafate_woocommerce_after_mini_cart', 10, 0 );

/** Mini cart structure (empty)
 *
 * @since 1.0.0
*/

function calafate_woocommerce_cart_is_empty() {

	$output = '<div class="empty-cart">
		<span class="title">' . esc_html__( 'Empty', 'calafate' ) . '</span>
		' . calafate_svg( 'face', 'icon' ) . '
	</div>';

	echo $output;

}

add_action( 'woocommerce_cart_is_empty', 'calafate_woocommerce_cart_is_empty', 10, 0 );

// define the woocommerce_review_gravatar_size callback 

function calafate_woocommerce_review_gravatar_size() {
	return 120;
}
add_filter( 'woocommerce_review_gravatar_size', 'calafate_woocommerce_review_gravatar_size', 10, 1 );

/** Add shop actions
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_shop_actions' ) ) :

	function calafate_shop_actions() {

		$output = '<div id="site-share">';
			$output .= '<span class="site-share">';
				$output .= '<a href="' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '">';

				if ( is_user_logged_in() ) {
					$current_user = wp_get_current_user();
					$output .= sprintf( esc_html__( 'Hello %s', 'calafate' ), $current_user->display_name );
				} else {
					$output .= esc_html__( 'Login / Register', 'calafate' );
				}

				$output .= '</a>';
			$output .= '</span>';
		$output .= '</div>';

		echo $output;

	}

endif; 

/** Description tabs restructure
 *
 * @since 1.0.0
*/


function woo_custom_description_tab( $tabs ) {

	global $product;

	ob_start();
	woocommerce_template_single_excerpt();
	$description_tab_content = ob_get_clean();

	if ( empty( $description_tab_content ) ) {
		unset( $tabs['description'] ); 
	} else {
		$tabs['description']['callback'] = 'calafate_description_tab_content';	
		$tabs['description']['title'] = esc_html__( 'Description', 'calafate' );	
	}

	return $tabs;
}

function calafate_description_tab_content() {
	woocommerce_template_single_excerpt();
}

add_filter( 'woocommerce_product_tabs', 'woo_custom_description_tab', 98 );

/** Responsive cart button
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_responsive_cart_button') ) :

	function calafate_responsive_cart_button() {
		echo '<a class="responsive-bag" href="#">' . calafate_svg( 'bag' ) . '<span class="woocommerce-cart-no">' . WC()->cart->get_cart_contents_count() . '</span></a>';
	}

endif; 

/** Outputs product thumbnail image
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_woocommerce_product_gallery_thumbnail' ) ) :

	function calafate_woocommerce_product_gallery_thumbnail( $id ) {

		$img = wp_get_attachment_url( $id );
		$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );

		$img_regular = aq_resize( $img, 90, 90, true, true, true );
		$img_retina = aq_resize( $img, 180, 180, true, true, true );
			
		echo '<a href="#" class="carousel-nav">
			<img src="' . esc_url( $img_regular ) . '" srcset="' . esc_url( $img_regular ) . ' 90w, ' . esc_url( $img_retina ) . ' 180w" sizes="90px" alt="' . esc_attr( $alt ) . '" />
		</a>';

	}

endif;

/** Outputs product gallery image
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_woocommerce_product_gallery_image' ) ) :

	function calafate_woocommerce_product_gallery_image( $id, $class = '' ) {

		$img = wp_get_attachment_url( $id );
		$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );

		$img_1440 = aq_resize( $img, 1440, null, false, true, false );
		$img_1024 = aq_resize( $img, 1024, null, false, true, false );
		$img_720 = aq_resize( $img, 720, null, false, true, false );
		$img_480 = aq_resize( $img, 480, null, false, true, false );
		$img_360 = aq_resize( $img, 360, null, false, true, false );
			
		echo '<div class="carousel-cell' . $class . '" data-full="' . $img . '">
			<img src="' . esc_url( $img_720 ) . '" srcset="' . esc_url( $img_1440 ) . ' 1440w, ' . esc_url( $img_1024 ) . ' 1024w, ' . esc_url( $img_720 ) . ' 720w, ' . esc_url( $img_480 ) . ' 480w, ' . esc_url( $img_360 ) . ' 360w" sizes="(min-width: 1640px) 720px, (min-width: 1366px) 50w, (min-width: 1023px) 35w, 100w" alt="' . esc_attr( $alt ) . '" />
		</div>';

	}

endif;

/** Simple product AJAX fix (WC 3.0.0)
 *
 * @since 1.2.9
*/

function calafate_woocommerce_after_add_to_cart_quantity() {
	global $product;
	echo '<input type="hidden" name="add-to-cart" value="' . esc_attr( $product->get_id() ) . '" />';
}

add_action( 'woocommerce_after_add_to_cart_quantity', 'calafate_woocommerce_after_add_to_cart_quantity' );

/** Remove WooCommerce Customizer Panels
 *
 * @since 1.3.9.4
*/

function calafate_customize_woo_register() {     
	global $wp_customize;
	$wp_customize->remove_section( 'woocommerce_product_images' );  
	$wp_customize->remove_control( 'woocommerce_catalog_columns' );
	$wp_customize->remove_control( 'woocommerce_catalog_rows' );
} 

add_action( 'customize_register', 'calafate_customize_woo_register', 11 );

