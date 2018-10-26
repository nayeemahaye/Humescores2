<?php

/**
 * Calafate functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Calafate
 */

if ( ! function_exists( 'calafate_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function calafate_setup() {
	
	load_theme_textdomain( 'calafate', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_post_type_support( 'page', 'excerpt' );

	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'calafate' ),
	) );

	if ( ! calafate_get_field( 'calafate_sharing_disable', 'option' ) ) {
		add_filter( 'wp_head', 'calafate_social_meta' );
	}

	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

}
endif;

add_action( 'after_setup_theme', 'calafate_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */

function calafate_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'calafate_content_width', 1440 );
}
add_action( 'after_setup_theme', 'calafate_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

function calafate_widgets_init() {

	register_sidebar( array(
		'name'          => esc_html__( 'Main sidebar', 'calafate' ),
		'id'            => 'sidebar-main',
		'description'   => esc_html__( 'Add your widgets here.', 'calafate' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer', 'calafate' ),
		'id'            => 'sidebar-footer',
		'description'   => esc_html__( 'Keep it mininmal', 'calafate' ),
		'before_widget' => '<div id="%1$s" class="widget one-third lap--two-thirds palm--one-whole %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title hidden">',
		'after_title'   => '</h4>',
	) );

}

add_action( 'widgets_init', 'calafate_widgets_init' );

/**
 * Enqueue scripts and styles.
 */

$calafate_js_debug = "true";

function calafate_scripts() {

	global $post;
	global $calafate_js_debug;

	//wp_deregister_style('wp-mediaelement');

	// Main stylesheet 

	wp_enqueue_style( 'calafate-style', get_stylesheet_uri() );

	if ( $calafate_js_debug === 'true' ) {

		// Enqueue third party scripts (handles are not prefixed!)

		wp_enqueue_script( 'isotope', get_template_directory_uri() . '/js/vendor/isotope.pkgd.min.js', array(), NULL, true );
		wp_enqueue_script( 'packery-mode', get_template_directory_uri() . '/js/vendor/packery-mode.pkgd.min.js', array(), NULL, true );
		wp_enqueue_script( 'fitvids', get_template_directory_uri() . '/js/vendor/jquery.fitvids.min.js', array(), NULL, true );
		wp_enqueue_script( 'imagesloaded', get_template_directory_uri() . '/js/vendor/imagesloaded.pkgd.min.js', array(), NULL, true );
		wp_enqueue_script( 'flickity', get_template_directory_uri() . '/js/vendor/flickity.pkgd.min.js', array(), NULL, true );
		wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/js/vendor/jquery.fancybox.pack.js', array(), NULL, true );

		// Enqueue theme (custom made) scripts

		wp_enqueue_script( 'calafate-f-helpers', get_template_directory_uri() . '/js/theme/helpers.js', array( 'jquery' ), NULL, true );
		wp_enqueue_script( 'calafate-f-ajax', get_template_directory_uri() . '/js/theme/ajax.js', array( 'jquery' ), NULL, true );
		wp_enqueue_script( 'calafate-f-hero', get_template_directory_uri() . '/js/theme/hero.js', array( 'jquery' ), NULL, true );
		wp_enqueue_script( 'calafate-f-page', get_template_directory_uri() . '/js/theme/page.js', array( 'jquery' ), NULL, true );
		wp_enqueue_script( 'calafate-f-post', get_template_directory_uri() . '/js/theme/post.js', array( 'jquery' ), NULL, true );
		wp_enqueue_script( 'calafate-f-portfolio', get_template_directory_uri() . '/js/theme/portfolio.js', array( 'jquery' ), NULL, true );
		wp_enqueue_script( 'calafate-f-blog', get_template_directory_uri() . '/js/theme/blog.js', array( 'jquery' ), NULL, true );
		wp_enqueue_script( 'calafate-f-woo', get_template_directory_uri() . '/js/theme/woocommerce.js', array( 'jquery' ), NULL, true );

		wp_enqueue_script( 'calafate-main', get_template_directory_uri() . '/js/main.js', array( 'jquery' ), NULL, true );

	} else {

		wp_enqueue_script( 'calafate-main-min', get_template_directory_uri() . '/js/main-min.js', array( 'jquery' ), NULL, true );

	}

	// Enqueue comment reply script

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	} 

	// Google maps

	if ( is_page_template( 'template-contact.php' ) && calafate_get_field( 'map_enable' ) ) {
		wp_enqueue_script( 'googlemaps', 'https://maps.googleapis.com/maps/api/js?v=3&key=' . calafate_get_field( 'gmaps_key', $post->ID ), array( 'jquery'), NULL, true ); // WPCS: XSS OK.
	}

	// Localize scripts

	wp_localize_script(
		( $calafate_js_debug === 'true' ? 'calafate-f-post' : 'calafate-main-min' ),
		'langObj',
		array(
			'post_comment'  => esc_html__( 'Post comment', 'calafate' ),
			'posted_comment'  => esc_html__( 'Your comment was posted and it is awaiting moderation.', 'calafate' ),
			'duplicate_comment'  => esc_html__( 'Duplicate content detected. It seems that you\'ve posted this before.', 'calafate' ),
			'posting_comment'  => esc_html__( 'Posting your comment, please wait...', 'calafate' ),
			'required_comment'  => esc_html__( 'Please complete all the required fields.', 'calafate' ),
		)
	);

	wp_localize_script(
		( $calafate_js_debug === 'true' ? 'calafate-f-page' : 'calafate-main-min' ),
		'svg',
		array(
			'arrow'  => calafate_svg( 'arrow-small', 'arrow' )
		)
	);

	wp_localize_script(
		( $calafate_js_debug === 'true' ? 'calafate-f-page' : 'calafate-main-min' ),
		'mediaScripts',
		array(
			'mediaelement'  => esc_url( includes_url() . 'js/mediaelement/mediaelement-and-player.min.js' ),
			'wp_mediaelement' => esc_url( includes_url() . 'js/mediaelement/wp-mediaelement.min.js' ),
			'google' => 'https://maps.googleapis.com/maps/api/js?v=3&key='
		)
	);

	wp_localize_script(
		( $calafate_js_debug === 'true' ? 'calafate-f-hero' : 'calafate-main-min' ),
		'heroSVG',
		array(
			'circle'  => calafate_svg( 'scroll-circle', 'circle' )
		)
	);

	wp_localize_script(
		( $calafate_js_debug === 'true' ? 'calafate-main' : 'calafate-main-min' ),
		'themeSettings',
		array(
			'ajax'  => esc_attr( get_theme_mod( 'calafate_site_ajax', 'enabled' ) ),
			'sticky' => esc_attr( get_theme_mod( 'calafate_site_sticky', 'enabled' ) ),
			'l10n_openSearch' => esc_html__( 'Search', 'calafate' ),
			'l10n_closeSearch' => esc_html__( 'Close', 'calafate' )
		)
	);

	wp_localize_script(
		( $calafate_js_debug === 'true' ? 'calafate-f-woo' : 'calafate-main-min' ),
		'wooSettings',
		array(
			'cart_redirect'  => esc_attr( get_option( 'woocommerce_cart_redirect_after_add' ) )
		)
	);

}

add_action( 'wp_enqueue_scripts', 'calafate_scripts' );

// Customizer additions

require get_template_directory() . '/inc/customizer.php';

// Load Jetpack compatibility file

require get_template_directory() . '/inc/jetpack.php';

// Load custom image resizing class

require get_template_directory() . '/inc/aq_resize.php';

// Load svg icon templates

require get_template_directory() . '/inc/calafate-svg.php';

// Load plugin requirements (TGMPA)

require get_template_directory() . '/inc/plugins/plugins.php';

// Load Krown Column Manager 

require get_template_directory() . '/inc/krown-column-manager/krown-column-manager.php';

// Load PCI scripts

require get_template_directory() . '/inc/portfolio-category-images.php';

// Custom functions for this theme

require get_template_directory() . '/inc/functions/portfolio.php';
require get_template_directory() . '/inc/functions/post.php';
require get_template_directory() . '/inc/functions/header-page.php';
require get_template_directory() . '/inc/functions/general.php';

// WooCommerce support

if ( function_exists( 'is_woocommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

/** Customizes TinyMCE 
 *
 * @since 1.0.0
*/

// Add custom font sizes

if ( ! function_exists( 'calafate_mce_text_sizes' ) ) {

	function calafate_mce_text_sizes( $initArray ){
		$initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px 40px 46px 52px";
		return $initArray;
	}

}

add_filter( 'tiny_mce_before_init', 'calafate_mce_text_sizes' );

// Add a few text styles

if ( ! function_exists( 'calafate_mce_custom_styles' ) ) {

	function calafate_mce_custom_styles($settings) {

	    $settings['theme_advanced_blockformats'] = 'p,h1,h2,h3,h4,h5';

	    $style_formats = array(
	        array('title' => 'Underlined heading', 'inline' => 'figure', 'classes' => 'underlined-heading'),
	        array('title' => 'Button', 'inline' => 'figure', 'classes' => 'button'),
	        array('title' => 'Cite', 'inline' => 'cite', 'classes' => '')
	    );

	    $settings['style_formats'] = json_encode( $style_formats );

	    return $settings;
	    
	}

}

add_filter('tiny_mce_before_init', 'calafate_mce_custom_styles');

// Add new buttons to the interface

if ( ! function_exists( 'calafate_mce_buttons' ) ) {

	function calafate_mce_buttons( $buttons ) {
		array_unshift( $buttons, 'fontsizeselect' );
  	array_unshift( $buttons, 'styleselect');
		return $buttons;
	}

}

add_filter( 'mce_buttons_2', 'calafate_mce_buttons' );

/** Turn HEX values into RGB
 *
 * @since 1.0.0
*/

function calafate_hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
  $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); 
  $rgbArray = array();
  if (strlen($hexStr) == 6) { 
      $colorVal = hexdec($hexStr);
      $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
      $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
      $rgbArray['blue'] = 0xFF & $colorVal;
  } elseif (strlen($hexStr) == 3) { 
      $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
      $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
      $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
  } else {
      return false; //Invalid hex color code
  }
  return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; 
}

/** Enqueue admin stylesheet
 *
 * @since 1.0.0
*/

function calafate_admin_style() {
	wp_enqueue_style( 'admin-style', get_template_directory_uri() . '/admin-style.css' );
}
add_action( 'admin_enqueue_scripts', 'calafate_admin_style' );

/** Add custom body class to admin
 *
 * @since 1.2.5
*/

if ( ! function_exists( 'calafate_admin_body_class' ) ) :
	function calafate_admin_body_class( $classes ) {
	  return $classes . ' ' . get_theme_mod( 'calafate_post_style', 'post-half' ) . ' admin-pt-' . str_replace( '.php', '', basename( get_page_template() ) );
	}
endif; 

add_filter( 'admin_body_class', 'calafate_admin_body_class' );

/** Custom Walker Menu
 *
 * @since 1.0.0
*/

class Calafate_Menu_Walker extends Walker_Nav_Menu {

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

	  global $wp_query;

	  $classes = empty( $item->classes ) ? array() : (array) $item->classes;
	  $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );
	  $class_names .= calafate_single_parent( $item->object_id );

	  $output .= '<li id="nav-menu-item-'. $item->ID . '" class="' . $class_names . '">';

	  $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
	  $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
	  $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
	  $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
	  $attributes .= ' class="ajax-link' . ( in_array( 'no-ajax-link', $item->classes ) ? ' no-ajax-link' : '' ) . '"';
	  
	  $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s%6$s</a>%7$s',
      $args->before,
      $attributes,
      $args->link_before,
      apply_filters( 'the_title', $item->title, $item->ID ),
      calafate_output_hero_header_image_for_preload( $item->object_id ),
      $args->link_after,
      $args->after
	  );
	  $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

	}

}

/** Checks if sidebar
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_check_sidebar' ) ) {

	function calafate_check_sidebar() {

		$sidebar = false;
		
		if ( function_exists( 'is_woocommerce' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
			$sidebar = false;
		} else if ( ( is_archive () || is_singular('post') || is_home() ) && get_theme_mod( 'calafate_site_sidebar', 'enabled' ) === 'enabled' ) {
			$sidebar = true;
		}

		return $sidebar;

	}

}

/** Gets page color for page revealer
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_page_color' ) ) {

	function calafate_page_color() {

		global $post;

		if ( isset( $post ) ) {

			if ( calafate_get_field( 'page-scheme' ) ) {

				$bg_color = calafate_get_field( 'page-bg-color') ? calafate_get_field( 'page-bg-color') : '#000';
				$txt_color = calafate_get_field( 'page-txt-color') ? calafate_get_field( 'page-txt-color') : '#fff';

			} else if ( function_exists( 'is_woocommerce') && ( is_shop() || is_singular( 'product' ) || is_cart() || is_checkout() || is_account_page() ) ) {

				$bg_color = get_theme_mod( 'calafate_shop_bg', '#000' );
				$txt_color = get_theme_mod( 'calafate_shop_txt', '#fff' );

			} else if ( is_home() || is_archive() || is_search() || is_singular( 'post' ) ) {

				$bg_color = get_theme_mod( 'calafate_blog_bg', '#000' );
				$txt_color = get_theme_mod( 'calafate_blog_txt', '#fff' );

			} else {

				$bg_color = get_theme_mod( 'calafate_bg_color', '#000' );
				$txt_color = get_theme_mod( 'calafate_txt_color', '#fff' );

			}

		} else {

			$bg_color = get_theme_mod( 'calafate_bg_color', '#000' );
			$txt_color = get_theme_mod( 'calafate_txt_color', '#fff' );

		}

		return array( $bg_color, $txt_color );

	}

}

/** Theme get_field (via ACF)
 *
 * @since 1.0.0
*/

function calafate_get_field( $field, $postid = null ) {

	if ( function_exists( 'get_field' ) ) {
		if ( $postid !== null ) 
			return get_field( $field, $postid );
		else
			return get_field( $field );
	} else {
		return null;
	}

}

/** ACF Setup
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_show_acf_editor' ) ) {
	add_filter( 'acf/settings/show_admin', '__return_false' );
}

// Include theme options

if ( function_exists('acf_add_options_sub_page') ) {
	acf_add_options_sub_page( array(
		'page_title' => esc_html__( 'Theme Tools', 'calafate' ),
		'parent_slug' => 'options-general.php'
	) );
}

// Include meta fields

require get_template_directory() . '/inc/advanced-custom-fields-pro-config.php';

/** Password protected form
 *
 * @since 1.0.3
*/

if ( ! function_exists( 'calafate_password_form' ) ) : 

	function calafate_password_form() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o = '<div class="calafate-pwd"><form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
    	<div>
	    	<div class="head"><figure class="view-heading">' . esc_html__( 'This content is password protected', 'calafate' ) . '</figure>'
	    	. '<figure class="view-subheading">' . esc_html__( 'To view it please enter your password below', 'calafate' ) . '</figure></div>'
	    . '<div class="foot"><input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" placeholder="' . esc_html__( 'Password', 'calafate' ) . '" /><input type="submit" name="Submit" value="⟶" />
	    	<figure class="view-info" data-error="' . esc_html__( 'Wrong password. Please try again.', 'calafate' ) . '">' . esc_html__( 'Write your password and hit ENTER', 'calafate' ) . '</figure></div>'
				. '<p class="back"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'or Go back to the homepage', 'calafate' ) . '</a></p>' . calafate_svg( 'lock', 'bg' )
				. ' </div>
    	</form>
    </div><div id="pwd-down-content"></div>
    ';
    return $o;
	}

endif;

add_filter( 'the_password_form', 'calafate_password_form' );

/** Second featured image
 *
 * @since 1.2.0
*/

include( get_template_directory() . '/inc/mpt/init.php' );

if ( class_exists( 'MultiPostThumbnails' ) ) {

	new MultiPostThumbnails( array(
		'label' => esc_html__( 'Secondary Featured Image', 'calafate' ),
		'id' => 'featured-thumbnail-secondary',
		'post_type' => 'portfolio'
	) );

	new MultiPostThumbnails( array(
		'label' => esc_html__( 'Secondary Featured Image', 'calafate' ),
		'id' => 'featured-thumbnail-secondary',
		'post_type' => 'product'
	) );

}

/** Added portfolio shortcode
 *
 * @since 1.2.0
*/

if ( ! function_exists( 'calafate_portfolio_shortcode' ) ) :

	function calafate_portfolio_shortcode( $atts ) {

    extract( shortcode_atts( array( 
      'page_id' => '',
      'no' => 9,
      'cat' => '',
      'tag' => '',
      'el_class' => '',
      'pagination' => ''
    ), $atts ) );

		global $columns, $gap, $portfolio_type, $portfolio_aspect_ratio, $portfolio_style;

		$columns = calafate_get_field( 'portfolio-columns', $page_id ); 
		$gap = calafate_get_field( 'portfolio-gap', $page_id ); 
		$portfolio_type = calafate_get_field( 'portfolio-type', $page_id );
		$portfolio_aspect_ratio = calafate_get_field( 'portfolio-aspect-ratio', $page_id );
		$portfolio_aspect_ratio = explode(':', $portfolio_aspect_ratio);
		$portfolio_style = calafate_get_field( 'portfolio-style', $page_id );
		$portfolio_caption = calafate_get_field( 'portfolio-caption', $page_id );

		if ( $cat == '' ) {

			$categories = calafate_get_field( 'portfolio-categories', $page_id );
			$query_filter = '';

			if ( ! empty ( $categories ) ) {

				foreach ( $categories as $cat ) {

					$filter = get_term_by( 'id', $cat, 'portfolio_category' ); 
					if ( ! empty( $filter ) ) {
						$query_filter .= $filter->slug . ',';
					}

				}

			}

		} else {
			$query_filter = $cat;
		}

		$output = calafate_portfolio_style_shortcode( $page_id );

		$output .= '<div class="portfolio-grid shortcode caption-style-' . esc_attr( $portfolio_style ) . ' mobile-style-' . esc_attr( $portfolio_caption ) . '" data-columns="' . esc_attr( $columns ) . '" data-gap="' . esc_attr( $gap ) . '" data-id="' . esc_attr( $page_id ) . '">';

			$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : ( get_query_var( 'page' ) ? get_query_var( 'page' ) : 1 );

			$args = array(
				'post_type' => 'portfolio',
				'portfolio_category' => $query_filter,
				'portfolio_tag' => $tag,
				'posts_per_page' => $no,
				'paged' => $paged
			);

			$all_posts = new WP_Query( $args ); 

			while ( $all_posts->have_posts() ) : $all_posts->the_post();

				ob_start();
				get_template_part( 'template-parts/content', 'portfolio' );
				$output .= ob_get_contents();
				ob_end_clean();

			endwhile; 

		$output .= '</div>';

    if ( $pagination == 'true' ) {
			ob_start();
			calafate_posts_navigation( ' bigger poppins', $all_posts );
			$output .= ob_get_clean();
		}

		wp_reset_postdata();

		return $output;

	}

endif; 

add_shortcode( 'calafate_portfolio', 'calafate_portfolio_shortcode' );

/** Added blog shortcode
 *
 * @since 1.2.1
*/

if ( ! function_exists( 'calafate_blog_shortcode' ) ) :

	function calafate_blog_shortcode( $atts ) {

    extract( shortcode_atts( array( 
      'style' => 'minimal',
      'posts_per_page' => '6',
      'category' => '',
      'tag' => '',
      'orderby' => 'date',
      'order' => 'DESC',
      'include' => '',
      'author' => '',
      'el_class' => '',
      'pagination' => ''
    ), $atts ) );

    global $blog_cols;

		$blog_cols = get_theme_mod( 'calafate_blog_cols', '3' );
		$blog_gap = $blog_cols == '3' ? 50 : 95;

		$output = '';

    if ( $style == 'minimal' ) {
    	$output .= '<div class="blog-shortcode entries--minimal two-thirds old-breakpoint--whole slide">';
    } else {
    	$output .= '<div class="blog shortcode entries--grid one-whole portfolio-grid" data-columns="' . $blog_cols . '" data-gap="' . $blog_gap . '">';
    }

		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : ( get_query_var( 'page' ) ? get_query_var( 'page' ) : 1 );

		$all_posts = new WP_Query( array(
			'posts_per_page' => $posts_per_page,
			'category_name' => $category,
			'author_name' => $author,
			'tag' => $tag,
			'orderby' => $orderby,
			'order' => $order,
			'post_type' => 'post',
			'paged' => $paged,
			'post__in' => $include != '' ? explode(',', $include) : ''
		) ); 

		if ( $all_posts->have_posts() ) {

			while ( $all_posts->have_posts() ) {

				$all_posts->the_post();
				ob_start();
				get_template_part( 'template-parts/content', $style );
				$output .= ob_get_clean();

			}

		}

    $output .= '</div>';

    if ( $pagination == 'true' ) {
			ob_start();
			calafate_posts_navigation( ' bigger poppins', $all_posts );
			$output .= ob_get_clean();
		}

		wp_reset_postdata();

    return $output;

  }

endif;

add_shortcode( 'calafate_blog', 'calafate_blog_shortcode' );


/** Rewrite WP gallery shortcode
 *
 * @since 1.3.2
*/


function calafate_new_gallery_shortcode( $attr, $content ) {

	global $post;

	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
    if ( empty( $attr['orderby'] ) ) {
      $attr['orderby'] = 'post__in';
    }
    $attr['include'] = $attr['ids'];
	}

	$html = apply_filters( 'post_gallery', '', $attr );
	if ( $html != '' ) {
    return $html;
	}

	if ( isset( $attr['orderby'] ) ) {
    $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
    if ( !$attr['orderby'] ) {
      unset( $attr['orderby'] );
    }
	}

	extract( shortcode_atts( array(
    'order'          => 'ASC',
    'orderby'        => 'menu_order ID',
    'id'             => $post->ID,
    'include'        => '',
    'exclude'        => '',
    'type'           => 'thumbs',
    'columns'        => '3',
    'link'					 => 'none',
    'size'					 => 'full',
    'css_class'			 => ''
	), $attr ) );

	$id = intval( $id );
	if ( 'RAND' == $order ) {
    $orderby = 'none';
	}

	if ( ! empty( $include ) ) {

    $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

    $attachments = array();

    foreach ( $_attachments as $key => $val ) {
      $attachments[$val->ID] = $_attachments[$key];
    }

	} else if ( ! empty( $exclude ) ) {
    $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
    $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty( $attachments ) ) {
    return '';
	}

	if ( is_feed() ) {
    $html = "\n";
    foreach ( $attachments as $att_id => $attachment ) {
      $html .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
    }
    return $html;
	}

	$output = '';

	foreach ( $attachments as $id => $attachment ) {

    $img_url = isset( $attr['link'] ) && 'file' == $attr['link'] ? wp_get_attachment_image_src( $id, 'full', false, false ) : wp_get_attachment_image_src( $id, 'full', true, false );

    $img = wp_get_attachment_image_src( $id, $size, true, false );

    $caption = get_post( $id )->post_excerpt;
    $title = get_post( $id )->post_title;
  	$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );

    if ( $type == 'slider' ) {

    	$output .= '<div class="carousel-cell">';

	    	if ( $link !== 'none' ) {
	    		$output .= '<a class="fancybox fancybox-thumb" data-fancybox="gallery-' . $instance . '" data-caption="' . $caption . '" href="' . esc_url( $img_url[0] ) . '">';
	    	}

	    		$output .= '<img src="' . esc_url( $img[0] ) . '" title="' . esc_attr( $title ) . '" alt="' . esc_attr( $alt ) . '" />';
		    	
	        if ( isset( $caption ) && $caption != '' ) {
            $output .= '<p class="caption">'. $caption . '</p>';
	        }

	    	if ( $link !== 'none' ) {
	    		$output .= '</a>';
	    	}

	    $output .= '</div>';

    } else {

    	$output .= '<div class="calafate-gallery--item">';

	    	if ( $link !== 'none' ) {
	    		$output .= '<a class="fancybox fancybox-thumb" data-fancybox="gallery-' . $instance . '" data-caption="' . $caption . '" href="' . esc_url( $img_url[0] ) . '">';
	    	}

	    		$output .= '<img src="' . esc_url( $img[0] ) . '" title="' . esc_attr( $title ) . '" alt="' . esc_attr( $alt ) . '" />';

	    	if ( $link !== 'none' ) {
	    		$output .= '</a>';
	    	}

	    $output .= '</div>';

	    }

	}

	if ( $type == 'slider' ) {

    $html = '<div class="carousel calafate-slider ' . $css_class . '">' . $output . '</div>';

	} else {

    $html = '<div class="calafate-gallery columns-' . $columns . ' ' . $css_class . '">' . $output . '</div>';

	}

	return $html;

}

remove_shortcode( 'gallery', 'calafate_gallery_shortcode' );
add_shortcode( 'gallery', 'calafate_new_gallery_shortcode' );


/** Lazy loading (via lazysizes.js)
 *
 * @since 1.4.0
*/

function lazy_load_responsive_images ( $content ) {

   if ( empty( $content ) ) {
      return $content;
   }
  
   $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
   $dom = new DOMDocument();
   libxml_use_internal_errors( true );
   $dom->loadHTML( utf8_decode($content) );
   libxml_clear_errors();

   $figure = $dom->createElement( 'figure' );
   $figure->setAttribute( 'class', 'lazyload-container' );

   foreach ( $dom->getElementsByTagName( 'img' ) as $img ) {

      if ( $img->hasAttribute( 'sizes' ) && $img->hasAttribute( 'srcset' ) ) {

				$sizes_attr = $img->getAttribute( 'sizes' );
				$srcset     = $img->getAttribute( 'srcset' );
				$img->setAttribute( 'data-sizes', $sizes_attr );
				$img->setAttribute( 'data-srcset', $srcset );
				$img->removeAttribute( 'sizes' );
				$img->removeAttribute( 'srcset' );
				$src = $img->getAttribute( 'src' );
				if ( ! $src ) {
				  $src = $img->getAttribute( 'data-noscript' );
				}

      } else {

         $src = $img->getAttribute( 'src' );
         if ( ! $src ) {
            $src = $img->getAttribute( 'data-noscript' );
         }
         $img->setAttribute( 'data-src', $src );

      }

      if ( $img->parentNode->tagName == 'a' && $img->parentNode->parentNode->tagName == 'figure' ) {
      	$figure_clone = $img->parentNode->parentNode;
      } else if ( $img->parentNode->tagName == 'figure' ) {
      	$figure_clone = $img->parentNode;
      } else {
	      $figure_clone = $figure->cloneNode();
	      $img->parentNode->replaceChild( $figure_clone, $img );
	      $figure_clone->appendChild( $img );
      }

      $classes = $img->getAttribute( 'class' );
      if ( strpos($classes, 'alignnone') !== false ) {
  			$figure_clone->setAttribute( 'data-align', 'none' );
      }

      preg_match('/wp-image-(.*) |wp-image-(.*)/', $classes, $matches);

      if ( isset( $matches ) ) {

      	$att_id = null;

      	if ( isset( $matches[2] ) && is_int( intval( $matches[2] ) ) ) {
      		$att_id = intval( $matches[2] );
      	} else if ( isset( $matches[1] ) && is_int( intval( $matches[1] ) ) ) {
      		$att_id = intval( $matches[1] );
      	} 

      	if ( $att_id !== null ) {
      		$meta = wp_get_attachment_metadata( intval( $att_id ) );
      		if ( $meta ) {
      			if ( isset( $meta['width'] ) && isset( $meta['height'] ) ) {
	      			$ratio = ( $meta['height'] / $meta['width'] ) * 100;
	      			$figure_clone->setAttribute( 'style', 'padding-top:' . $ratio . '%');
	      			$figure_clone->setAttribute( 'class', 'lazyload-container ratio-enabled' );
      			}
      		}
      	}
      }

      $classes .= ' lazyload ll';
      $img->setAttribute( 'class', $classes );
      $img->removeAttribute( 'src' );
      $noscript      = $dom->createElement( 'noscript' );
      $noscript_node = $img->parentNode->insertBefore( $noscript, $img );
      $noscript_img  = $dom->createElement( 'IMG' );
      $noscript_img->setAttribute( 'class', $classes );
      $new_img = $noscript_node->appendChild( $noscript_img );
      $new_img->setAttribute( 'src', $src );
      $content = $dom->saveHTML();

  }

  return $content;

}

if ( get_theme_mod( 'calafate_site_lazy', 'disabled' ) === 'enabled' ) {
	//add_filter( 'the_content', 'lazy_load_responsive_images', 20 );
}
