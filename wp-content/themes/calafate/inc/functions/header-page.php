<?php
/**
 * Calafate header & page functions
 *
 * @package Calafate
*/

/** Outputs the logo
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_logo' ) ) :

	function calafate_logo() {

		global $post;
		
		if ( get_theme_mod( 'calafate_image_logo', 'disabled' ) == 'enabled' ) {

			$logo_img = get_theme_mod( 'calafate_logo', '' );

			if ( calafate_get_field( 'page-logo' ) && ! ( is_search() ) ) {
				$logo_img = calafate_get_field( 'page-logo' );
			}

			if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {

				if ( get_theme_mod( 'calafate_logo_woocommerce', '' ) != '' ){
					$logo_img = get_theme_mod( 'calafate_logo_woocommerce', '' );
				}

				if ( ! is_shop() && is_singular( 'product' ) && calafate_get_field( 'page-logo' ) ) {
					$logo_img = calafate_get_field( 'page-logo' );
				}

			}

			if ( get_theme_mod( 'calafate_logo_blog', '' ) != '' && ( is_home() || is_archive() || is_search() || is_singular( 'post' ) ) ) {
				if ( ! ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) ) {
					if ( ! ( is_singular( 'post' ) && calafate_get_field( 'page-logo' ) ) ) {
						$logo_img = get_theme_mod( 'calafate_logo_blog' );
					}
				}
			} 

			$logo_data = wp_get_attachment_metadata( attachment_url_to_postid( $logo_img ) );
			if ( isset( $logo_data['width'] ) && isset( $logo_data['height'] ) ) {
				$logo_size = 'width="' . esc_attr( $logo_data['width'] ) . '" height="' . esc_attr( $logo_data['height'] ) . '"';
			} else {
				$logo_size = '';
			}

			echo '<img src="' . esc_url( $logo_img ) . '" alt="' . get_bloginfo( 'name' ) . '" ' . $logo_size . ' itemprop="logo" style="max-height: ' . esc_attr( get_theme_mod( 'calafate_logo_height', 70 ) ) . 'px" />';

		} else {
			echo '<span itemprop="name">' . get_bloginfo( 'name' ) . '</span>';
		}

	}

endif;

/** Outputs the hero header's tagline (or simple page title)
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_hero_tagline' ) ) : 

	function calafate_hero_tagline( $postid ) {

		$output = '';

		if ( calafate_get_field( 'hero-enabled', $postid ) === true ) {

			$output .= '<div class="entry-hero-tagline hero-vertical-text-' . calafate_get_field( 'hero-vertical-text', $postid ) . '" data-gap="' . calafate_get_field( 'hero-gap-length', $postid ) . '"><div>';

			// Hide text if needed

			if ( calafate_get_field( 'hero-hide-text', $postid ) || calafate_get_field( 'hero-reduce-gap', $postid ) ) {
				
				$custom_css = "body.id-${postid} .entry-hero-tagline {
			    opacity: 0 !important;
			    visibility: hidden !important;
				}";

				if ( calafate_get_field( 'hero-reduce-gap', $postid ) ) {
					$custom_css .= "body.id-${postid} .entry-hero-tagline {
						display: none;
					}";
				}

				echo '<style type="text/css">' . $custom_css . '</style>';

			}
			
			if ( calafate_get_field( 'hero-tagline' ) && strlen( trim( str_replace( '<p>&nbsp;</p>', '', calafate_get_field( 'hero-tagline' ) ) ) ) > 0 ) {
				$output .= calafate_get_field( 'hero-tagline' );
			} else {
				if ( ! calafate_get_field( 'hero-hide-title' ) )
					$output .= '<h1 class="entry-title">' . get_the_title() . '</h1>';
			}

			$output .= '</div></div>';

			if ( calafate_get_field( 'hero-stick-it', $postid ) ) {
				echo '<style type="text/css"> .hero-header { position: absolute !important; } </style>';
			}

		} else if ( ! is_page_template( 'template-portfolio.php' ) ) {
			if ( ! calafate_get_field( 'hero-hide-title' ) )
				$output .= '<h1 class="entry-title">' . get_the_title() . '</h1>';
		}

		echo $output;

	}

endif; 


/** Outputs the hero header's tagline (for posts only!)
 *
 * @since 1.2.5
*/

if ( ! function_exists( 'calafate_hero_post_tagline' ) ) : 

	function calafate_hero_post_tagline( $postid ) {

		$output = '';

		if ( calafate_get_field( 'hero-enabled', $postid ) === true ) {

			$output .= '<div class="entry-hero-tagline hero-vertical-text-' . calafate_get_field( 'hero-vertical-text', $postid ) . '" data-gap="' . calafate_get_field( 'hero-gap-length', $postid ) . '"><div>';

			// Hide text if needed

			$output .= '<header class="entry-header">

				<h1 class="entry-title">' . get_the_title() . '</h1>

				<div class="entry-meta">

					<div class="time-author">
			
						<div class="time">
							<time datetime="' . get_the_time( 'c' ) . '" itemprop="datePublished">
								' . get_the_time( get_option( 'date_format' ) ) . '
							</time>
						</div>

						<!-- <div class="category palm--hide">
							' . calafate_categories( $postid, 'category', ',', 'name', false) . '
 						</div> -->

						<div class="author palm--hide">
							' . esc_html__( 'written by ', 'calafate' ) . '
							' . '<span itemprop="author">' . get_the_author_link() . '</span>
						</div>

					</div>

				</div>

			</header>';

			$output .= '</div></div>';

			if ( calafate_get_field( 'hero-stick-it', $postid ) ) {
				echo '<style type="text/css"> .hero-header { position: absolute !important; } </style>';
			}

		}

		echo $output;

	}

endif; 

/** Outputs the amazing hero header
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_hero_header' ) ) :

	function calafate_hero_header( $postid ) {

		global $post;
		$output = '';

		if ( ! ( is_search() || is_archive() || is_home() ) && calafate_get_field( 'hero-enabled', $postid ) === true ) {

			$output .= '<div class="hero-header">';

				if ( calafate_get_field( 'hero-video', $postid ) ) {

					$output .= '<div class="media video" data-src="' . calafate_get_field( 'hero-video', $postid ) . '" data-loop="' . calafate_get_field( 'hero-video-loop', $postid ) . '">
					</div>';

				}

				if ( calafate_get_field( 'hero-gallery', $postid ) ) {

					$hero_gallery = calafate_get_field( 'hero-gallery', $postid );

					if ( sizeof( $hero_gallery ) == 1 || calafate_get_field( 'hero-video', $postid ) ) {

						$output .= calafate_get_hero_header_image( $hero_gallery[0] );

					} else {

						$hero_gallery_autoplay = calafate_get_field( 'hero-gallery-autoplay', $postid );

						$output .= '<div class="carousel" data-autoplay="' . $hero_gallery_autoplay . '">';

						foreach ( $hero_gallery as $image ) {

							$output .= '<div class="carousel-cell">';

							$output .= calafate_get_hero_header_image( $image );

							if ( isset( $image['caption'] ) && $image['caption'] != '' ) {
								$output .= '<div class="caption">' . $image['caption'] . '</div>';
							}

							$output .= '</div>';

						}

						$output .= '</div>';

					}

				}

				$output .= '<div class="hero-helper-arrow"><div class="mouse"><div class="scroll"></div></div></div>';

			$output .= '</div>';

		} else {

			$output .= '';

		}

		if ( is_singular( 'post') && get_theme_mod( 'calafate_post_style', 'post-half' ) != 'post-full w-hero' ) {
			$output = '';
		}

		echo $output;

	}

endif; 

/** Returns a responsive image for the hero header
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_get_hero_header_image' ) ) :

	function calafate_get_hero_header_image( $image, $include_img_tag = true ) {

		$img = $image['url'];

		if ( strpos( $img, '.gif' ) === false ) {
			$img_small = aq_resize( $img, 960 );
			$img_medium = aq_resize( $img, 1380 );
			$img_large = aq_resize( $img, 1920 );
		} else {
			$img_small = $img;
			$img_medium = $img;
			$img_large = $img;
		}

		$output = '<div class="media image" data-bg-small="' . esc_url( $img_small ) . '" data-bg-medium="' . esc_url( $img_medium ) . '" data-bg-large="' . esc_url( $img_large ) . '" data-bg-full="' . esc_url( $img ) . '">';

			if ( $include_img_tag ) {
				$output .= '<img src="' . esc_url( $img_small ) . '" alt="' . $image['alt'] . '" title="' . $image['title'] . '" itemprop="image" srcset="data:image/gif;base64,R0lGODlhAQABAJH/AP///wAAAMDAwAAAACH5BAEAAAIALAAAAAABAAEAAAICVAEAOw==" />';
			}

		$output .= '</div>';

		return $output;

	}

endif;

if ( ! function_exists( 'calafate_output_hero_header_image_for_preload' ) ) :

	function calafate_output_hero_header_image_for_preload( $postid ) {

		if ( calafate_get_field( 'hero-gallery', $postid ) ) {

			$hero_gallery = calafate_get_field( 'hero-gallery', $postid );

			if ( sizeof( $hero_gallery ) > 0 ) {
				return '<span class="prefetch-hero" style="display:none">' . calafate_get_hero_header_image( $hero_gallery[0], false ) . '</span>';
			}

		}

	}

endif;

/** Enqueues general style
 *
 * @since 1.0.0
*/

if ( ! function_exists('calafate_general_style' ) ) :

	function calafate_general_style() {

		global $post;

		// Get general colors

		if ( is_home() || is_archive() || is_search() || is_singular( 'post' ) ) {
			$bg_color = get_theme_mod( 'calafate_blog_bg', '#000' );
			$txt_color = get_theme_mod( 'calafate_blog_txt', '#fff' );
		} else {
			$bg_color = get_theme_mod( 'calafate_bg_color', '#000' );
			$txt_color = get_theme_mod( 'calafate_txt_color', '#fff' );
		}
		$acc_color = get_theme_mod( 'calafate_acc_color', '#0FFFBE' );
		$acc_fgd_color = get_theme_mod( 'calafate_acc_fgd_color', '#111' );
		$comments_bg_color = get_theme_mod( 'calafate_comments_bg', '#fff' );
		$comments_txt_color = get_theme_mod( 'calafate_comments_txt', '#000' );
		$page_bg_color = get_theme_mod( 'calafate_bg_color', '#000' );
		$sidebar_bg_color = get_theme_mod( 'calafate_sidebar_bg', '#191919' );
		$sidebar_txt_color = get_theme_mod( 'calafate_sidebar_txt', '#fff' );
		$shop_cart_bg_color = get_theme_mod( 'calafate_shop_cart_bg', '#fff' );
		$shop_cart_txt_color = get_theme_mod( 'calafate_shop_cart_txt', '#000' );

		// Get fonts 

		$f_head = is_serialized( get_theme_mod( 'calafate_type_heading' ) ) ? unserialize( get_theme_mod( 'calafate_type_heading' ) ) : array( 'default' => true, 'font-family' => '"Helvetica Neue", Helvetica, Arial, sans-serif' );
		$f_menu = is_serialized( get_theme_mod( 'calafate_type_menu' ) ) ? unserialize( get_theme_mod( 'calafate_type_menu' ) ) : array( 'default' => true, 'font-family' => '"Helvetica Neue", Helvetica, Arial, sans-serif' );
		$f_body = is_serialized( get_theme_mod( 'calafate_type_body' ) ) ? unserialize( get_theme_mod( 'calafate_type_body' ) ) : array( 'default' => true, 'font-family' => '"Helvetica Neue", Helvetica, Arial, sans-serif' );
		$f_quote = is_serialized( get_theme_mod( 'calafate_type_quote' ) ) ? unserialize( get_theme_mod( 'calafate_type_quote' ) ) : array( 'default' => true, 'font-family' => '"Helvetica Neue", Helvetica, Arial, sans-serif' );
		$f_head_h1 = is_serialized( get_theme_mod( 'calafate_type_heading_main' ) ) ? unserialize( get_theme_mod( 'calafate_type_heading_main' ) ) : array( 'default' => true, 'font-family' => '"Helvetica Neue", Helvetica, Arial, sans-serif' );

		$protocol = is_ssl() ? 'https' : 'http';

		$subset = apply_filters( 'calafate_fonts_subset', '' );

		// Enqueue fonts (more complicated, because we don't want double or useless stylesheet loads)

		if ( ! isset( $f_head['default'] ) ) {
			wp_enqueue_style( 'calafate-font-head', esc_url( "$protocol://fonts.googleapis.com/css?family=" . $f_head['css-name'] . ":300,400,400italic,500,600,700,700italic$subset" ), array(), null );
		}

		if ( $f_body != $f_head && ! isset( $f_body['default'] ) ) {
			wp_enqueue_style( 'calafate-font-body', esc_url( "$protocol://fonts.googleapis.com/css?family=" . $f_body['css-name'] . ":300,400,400italic,500,600,700,700italic$subset" ), array(), null );
		}

		if ( $f_menu != $f_body && $f_menu != $f_head && ! isset( $f_menu['default'] ) ) {
			wp_enqueue_style( 'calafate-font-menu', esc_url( "$protocol://fonts.googleapis.com/css?family=" . $f_menu['css-name'] . ":300,400,400italic,500,600,700,700italic$subset" ), array(), null );
		}

		if ( $f_quote != $f_menu && $f_quote != $f_body && $f_quote != $f_head && ! isset( $f_quote['default'] ) ) {
			wp_enqueue_style( 'calafate-font-quote', esc_url( "$protocol://fonts.googleapis.com/css?family=" . $f_quote['css-name'] . ":400,400italic$subset" ), array(), null );
		}

		if ( $f_head_h1 != $f_quote && $f_head_h1 != $f_menu && $f_head_h1 != $f_body && $f_head_h1 != $f_head && ! isset( $f_head_h1['default'] ) ) {
			wp_enqueue_style( 'calafate-font-head-h1', esc_url( "$protocol://fonts.googleapis.com/css?family=" . $f_head_h1['css-name'] . ":400,700$subset" ), array(), null );
		}

		// Get custom CSS objects

		$user_css = calafate_get_field( 'calafate_custom_css', 'option' ) ? calafate_get_field( 'calafate_custom_css', 'option' ) : '';

		$woocommerce_css = function_exists( 'calafate_woocommerce_css' ) ? calafate_woocommerce_css() : '';

		// Build CSS object

		$custom_css = "

			/* Main color scheme */

			body:not(.hero-1), body.hero-1:not(.before), body.hero-1.very-first-init, .hero-header .overlay, #site-header.sticky, .single-product .images .overlay, .lazyload-container.ratio-enabled {
				background-color: {$bg_color};
			}
			#site-overlay {
				background-color: rgba(" . calafate_hex2RGB($bg_color, true) . ", 0.95);
			}
			.overlay-menu + .filters-images div:after {
				background-color: rgba(" . calafate_hex2RGB($bg_color, true) . ", 0.75);
			}

			body, body a, .comments-link:hover, body #site-header .image-logo-disabled span, body #site-header .image-logo-disabled span, #site-share a:not(:last-child):after, body:not(.hero-1) #preloader span, body.hero-1:not(.before) #preloader span, body.hero-1.very-first-init #preloader span, div.quantity input, input[type=\"submit\"], #site-overlay #searchform input, .comments-link span, .comments-link, .cf-7.mailchimp .wpcf7-form-control-wrap input {
				color: {$txt_color};
			}
			#content svg *, #site-actions svg *, #site-overlay *, #site-share svg path {
			  fill: {$txt_color};
			  stroke: {$txt_color}; 
			}
			.entry-navigation--double:after, .entry-meta div:not(:first-child):not(.desk--right):before, .single-portfolio .entry-navigation__info .meta:last-child:before, .overlay-menu li:after, hr, .dots-close-anim span, body .lines, body .lines:before, body .lines:after, .entry-navigation__item--prev:after, .post-navigation.bigger .no span:first-child:after, .hero-helper-arrow .scroll, .hero-carousel-paging li.dot.is-selected, .latest-blog .lb-content, .post-navigation .no span:first-child:after {
				background-color: {$txt_color};
			}
			body .responsive-bag svg *, body .responsive-search svg * {
			  fill: {$txt_color} !important;
			}
			table, table *, .hero-carousel-paging li.dot, div.quantity input, .calafate-tabs .tab-title.active, .entry-archive .searchform input, .hero-helper-arrow .mouse, .comments-link span {
				border-color: ${txt_color};
			}
			.post-password-form input[type=\"password\"] {
				border-color: ${txt_color} !important;
			}
			.grid-border, .grid-border .calafate-gallery--item, .calafate-tabs .tabs-titles, .calafate-toggle h5 {
				border-color: rgba(" . calafate_hex2RGB($txt_color, true) . ", 0.15);
			}
			.calafate-toggle h5:hover, .latest-blog .lb-image {
				background-color: rgba(" . calafate_hex2RGB($txt_color, true) . ", 0.15);
			}
			.calafate-toggle .content {
				background-color: rgba(" . calafate_hex2RGB($txt_color, true) . ", 0.06);
			}
			.latest-blog .lb-entry a {
				color: ${bg_color};
			}

			#site-header.sticky { 
				box-shadow: 0 10px 20px rgba(" . calafate_hex2RGB($bg_color, true) . ", 0.06);
			}
			.page-template-template-portfolio .post-navigation.bigger .no span:first-child:after {
				background-color: rgba(" . calafate_hex2RGB($txt_color, true) . ", 0.5);
			}

			#site-overlay #searchform input::-webkit-input-placeholder, .entry-archive .searchform input::-webkit-input-placeholder, .entry-archive .searchform input { color: ${txt_color}; opacity: 1 }
			#site-overlay #searchform input::-moz-placeholder, .entry-archive .searchform input::-moz-placeholder, .entry-archive .searchform input { color: ${txt_color}; opacity: 1 }
			#site-overlay #searchform input:-ms-input-placeholder, .entry-archive .searchform input:ms-input-placeholder, .entry-archive .searchform input { color: ${txt_color}; opacity: 1 }
			#site-overlay #searchform input::placeholder, .entry-archive .searchform input::placeholder, .entry-archive .searchform input { color: ${txt_color}; opacity: 1 }

			input::-webkit-input-placeholder, textarea::-webkit-input-placeholder { color: ${bg_color}; }
			input::-moz-placeholder, textarea::-moz-placeholder { color: ${bg_color}; }
			input:-ms-input-placeholder, textarea:-ms-input-placeholder { color: ${bg_color}; }
			input::placeholder, textarea::placeholder { color: ${bg_color}; }

			input, textarea {
				color: ${bg_color};
				background-color: ${txt_color};
			}

			.responsive-bag .woocommerce-cart-no {
				background-color: ${acc_color};
			}
			h3 .underlined-heading:after, #respond .form-submit #submit .underlined-heading:after, .entry-minimal__title span:after, .entry-archive .entry-read-link:after, .entry-meta a:after, .comment-reply-link:after, .hide-comments:after, .not-found a:after, .top-menu li a:after, #site-footer .widget a:after, .comments-link.opened span, .overlay-menu a span, .widget li a:hover:before, .fancybox-nav span:hover, .fancybox-close:hover, .entry-navigation__link:after, .page-content h3:after, blockquote:before, .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current, .mejs-controls .mejs-volume-button .mejs-volume-slider .mejs-volume-current, .mejs-overlay:hover .mejs-overlay-button, .summary .cart button[type=\"submit\"]:after, .blog .STAMP a:after, .comment-reply-link:hover:before, .comment-avatar .by-author, .entry-portfolio.hover-two h3:after, body .responsive-bag .woocommerce-cart-no, .entry-content a:after, span.button, #mini-cart .cart-checkout, .overlay-menu a span.no {
				background-color: ${acc_color};
			}
			.mejs-controls .mejs-time-rail .mejs-time-current {
				background-color: ${acc_color} !important;
			}
			.comments-link.opened span, .widget .tagcloud a:hover, .widget .calendar_wrap td#today:after {
				border-color: ${acc_color};
			}
			.comments-link.opened, .comment-meta .by-author, .overlay-menu a.selected, .overlay-menu a.selected span.no, .widget .calendar_wrap td#today, .widget .calendar_wrap td a, .entry-content a:not(.entry-navigation__item):not(.post-edit-link):not(.fancybox):not(.button):not(.image-text-link), .wpcf7 input[type=\"submit\"]:hover {
				color: ${acc_color};
			}
			a.no-link-style, .no-link-style a {
				color: ${txt_color} !important;
				font-weight: 500;
			}

			.blog .entry-portfolio .entry-sitle, .blog-posts-carousel .car-post .car-title {
				background-image: linear-gradient(${acc_color}, ${acc_color});
			}

			span.button a {
				color: ${acc_fgd_color} !important;
			}
			span.button:hover a, #mini-cart .cart-checkout:hover {
				color: ${acc_color} !important;
			}

			.comments-link.opened span, .overlay-menu a span, #mini-cart .cart-checkout, .overlay-menu a span.no {
				color: ${acc_fgd_color};
			}
			.overlay-menu a.selected span.no, span.button:hover, #mini-cart .cart-checkout:hover {
				background: ${acc_fgd_color} !important;
			}

			#comments .comments-wrapper, #respond .form-submit #submit {
				background: ${comments_bg_color};
			}
			#comments .comments-wrapper, #comments .comments-wrapper a, .comment-meta h6 a, #respond h3 small a:hover, #respond .form-submit #submit small a:hover, #respond .form-submit #submit:hover, #respond form input, #respond form textarea {
				color: ${comments_txt_color};
			}
			#respond h3, #respond .form-submit #submit {
				color: rgba(" . calafate_hex2RGB($comments_txt_color, true) . ", 0.07);
			}
			#respond form, #respond .form-comment, #respond .form-author, #respond .form-email {
				border-color: ${comments_txt_color};
			}
			.hide-comments:before, .comment-reply-link:before {
				background: ${comments_txt_color};
			}
			#comments svg * {
				fill: ${comments_txt_color};
				stroke: transparent;
			}

			#respond form input::-webkit-input-placeholder, #respond form textarea::-webkit-input-placeholder { color: ${comments_txt_color}; }
			#respond form input::-moz-placeholder, #respond form textarea::-moz-placeholder { color: ${comments_txt_color}; }
			#respond form input:-ms-input-placeholder, #respond form textarea:-ms-input-placeholder { color: ${comments_txt_color}; }
			#respond form input::placeholder, #respond form textarea::placeholder { color: ${comments_txt_color}; }

			.post-tags a {
				color: ${txt_color} !important;
			}

			/* Blog sidebar */

			#site-sidebar {
				background: ${sidebar_bg_color};
			}

			#site-sidebar, #site-sidebar a, #site-sidebar ul a:hover, #site-sidebar .cf-7.mailchimp .wpcf7-form-control-wrap input {
				color: ${sidebar_txt_color};
			}

			.widget input::-webkit-input-placeholder { color: ${txt_color}; }
			.widget input::-moz-placeholder{ color: ${txt_color}; }
			.widget input:-ms-input-placeholder { color: ${txt_color}; }
			.widget input::placeholder { color: ${txt_color}; }

			.widget li a:before, .widget .calendar_wrap tfoot a:before {
				color: ${sidebar_txt_color};
			}
			.widget .searchform button svg, .widget .searchform button svg *, #site-sidebar-opener svg path, #site-sidebar-closer span svg path {
				fill: ${sidebar_txt_color};
				stroke: ${sidebar_txt_color};
			}
			#site-sidebar a:hover {
				color: ${acc_color};
			}
			#site-sidebar-closer:hover svg path {
				fill: ${acc_color};
				stroke: ${acc_color};
			}
			.widget .searchform input  {
				border-color: ${sidebar_txt_color};
				color: ${sidebar_txt_color};
			}
			.widget .searchform input::-webkit-input-placeholder {
				color: ${sidebar_txt_color};
			}
			.widget .searchform input::-moz-placeholder {
				color: ${sidebar_txt_color};
			}
			.widget .searchform input:-ms-input-placeholder {
				color: ${sidebar_txt_color};
			}
			.widget .searchform input::placeholder {
				color: ${sidebar_txt_color};
			}
			.tagcloud a {
				border-color: rgba(" . calafate_hex2RGB($sidebar_txt_color, true) . ", 0.15);
			}
			.widget li a:before {
				background: ${sidebar_txt_color};
			}

			/* Typography */

			h1, .entry-minimal__title, .entry-navigation__item, .poppins {
				font-family: {$f_head_h1['font-family']};
			}
			h2, h3, h4, h5, h6, .single-portfolio .entry-breadcrumb, #respond .form-submit #submit {
				font-family: {$f_head['font-family']};
			}
			#site-logo.image-logo-disabled span, #site-navigation, .overlay-menu, .woocommerce-breadcrumb {
				font-family: {$f_menu['font-family']};
			}
			body, .top-menu .cart-item sup, input, textarea {
				font-family: {$f_body['font-family']};
			}
			.single-portfolio .entry-navigation__info .meta, .entry-caption .entry-meta, .page-template-template-cover .covers-title {
				font-family: {$f_quote['font-family']};
			}

			#mini-cart {
				background-color: ${shop_cart_bg_color};
			}
			#mini-cart, #mini-cart .cart_list li .remove {
				color: ${shop_cart_txt_color};
			}
			#mini-cart .remove-button {
				background-color: rgba(" . calafate_hex2RGB($shop_cart_txt_color, true) . ", 0.08);
			}
			#mini-cart .cart-totals {
				background-color: rgba(" . calafate_hex2RGB($shop_cart_txt_color, true) . ", 0.1);
			}
			#mini-cart .empty .cart-checkout {
				background-color: rgba(" . calafate_hex2RGB($shop_cart_txt_color, true) . ", 0.5);
			}
			#mini-cart .empty-cart svg path {
				fill: rgba(" . calafate_hex2RGB($shop_cart_txt_color, true) . ", 0.1);
			}
			body .top-menu .cart-item a {
				color: rgba(" . calafate_hex2RGB($txt_color, true) . ", 0.5) !important;
			}

			.form-columns .simple-select-cover .simple-select-inner {
				background-color: rgba(" . calafate_hex2RGB($txt_color, true) . ", 1);
				color: $bg_color;
			}

			.flickity-prev-next-button {
				background-color: {$bg_color} !important;
			}

			${woocommerce_css}

			/* Custom CSS */

			{$user_css}	

		";

		$custom_css = str_replace( array("\rn", "\r", "\n", "\t", '  ', '    ', '    '), '', $custom_css );

		wp_add_inline_style( 'calafate-style', $custom_css );

	}

endif;

add_action( 'wp_enqueue_scripts', 'calafate_general_style', 10 );

/** Enqueues custom style for each page (background & text color) - overwrites general
 *
 * @since 1.0.0
*/

if ( ! function_exists('calafate_page_style' ) ) :

	function calafate_page_style() {

		global $post;

		$custom_css = "";

		if ( ! ( is_archive() || is_search() || is_home() ) && calafate_get_field( 'page-scheme' ) ) {

			$page_bg_color = calafate_get_field( 'page-bg-color') ? calafate_get_field( 'page-bg-color') : '#000';
			$page_txt_color = calafate_get_field( 'page-txt-color') ? calafate_get_field( 'page-txt-color') : '#fff';
			$page_acc_color = calafate_get_field( 'page-acc-color') ? calafate_get_field( 'page-acc-color') : '#0ff1b3';
			$acc_fgd_color = get_theme_mod( 'calafate_acc_fgd_color', '#111' );

			$custom_css .= "

				/* Page specific color scheme */

				body, body a, .comments-link:hover, body #site-header .image-logo-disabled span, #site-share a:not(:last-child):after, body:not(.hero-1) #preloader span, body.hero-1:not(.before) #preloader span, body.hero-1.very-first-init #preloader span, div.quantity input, .woocommerce-page button.single_add_to_cart_button:hover, .single-product .summary .price del:before, input[type=\"submit\"], #site-overlay #searchform input, .cf-7.mailchimp .wpcf7-form-control-wrap input, .comments-link span, .comments-link {
					color: {$page_txt_color};
				}
				#content svg *, #site-actions svg *, #site-overlay *, #site-share svg path {
				  fill: {$page_txt_color};
				  stroke: {$page_txt_color}; 
				}
				.entry-navigation--double:after, .entry-meta div:not(:first-child):not(.desk--right):before, .single-portfolio .entry-navigation__info .meta:last-child:before, .overlay-menu li:after, hr,  body .lines, body .lines:before, body .lines:after, .entry-navigation__item--prev:after, .dots-close-anim span, .woocommerce-page .button:hover, .woocommerce-page input[type=\"submit\"].button:hover, .single-product .summary form .quantity:before, .hero-helper-arrow .scroll, .hero-carousel-paging li.dot.is-selected, .latest-blog .lb-content {
					background-color: {$page_txt_color};
				}
				body .responsive-bag svg *, body .responsive-search svg * {
				  fill: {$page_txt_color} !important;
				}
				.woocommerce-page .button:hover, .woocommerce-page input[type=\"submit\"].button:hover {
					color: ${page_bg_color};
				}
				.grid-border, .grid-border .calafate-gallery--item, .calafate-tabs .tabs-titles, .calafate-toggle h5 {
					border-color: rgba(" . calafate_hex2RGB($page_txt_color, true) . ", 0.15);
				}
				.calafate-toggle h5:hover, .latest-blog .lb-image {
					background-color: rgba(" . calafate_hex2RGB($page_txt_color, true) . ", 0.15);
				}
				.calafate-toggle .content {
					background-color: rgba(" . calafate_hex2RGB($page_txt_color, true) . ", 0.06);
				}
				table, table *, .hero-carousel-paging li.dot, .woocommerce-message a, div.quantity input, .woocommerce-page .button, .woocommerce-page input[type=\"submit\"].button, .calafate-tabs .tab-title.active, .hero-helper-arrow .mouse, .comments-link span {
					border-color: {$page_txt_color};
				}
				.latest-blog .lb-entry a {
					color: ${page_bg_color};
				}

				body:not(.hero-1), body.hero-1:not(.before), body.hero-1.very-first-init, .hero-header .overlay, #site-header.sticky, .single-product .summary:before, .single-product .images .overlay, .lazyload-container.ratio-enabled {
					background-color: {$page_bg_color};
				}
				#site-overlay {
					background-color: rgba(" . calafate_hex2RGB($page_bg_color, true) . ", 0.95);
				}
				.overlay-menu + .filters-images div:after {
					background-color: rgba(" . calafate_hex2RGB($page_bg_color, true) . ", 0.75);
				}
				#site-header.sticky { 
					box-shadow: 0 10px 20px rgba(" . calafate_hex2RGB($page_bg_color, true) . ", 0.06);
				}

				#site-overlay #searchform input::-webkit-input-placeholder { color: ${page_txt_color} !important; opacity: 1; }
				#site-overlay #searchform input::-moz-placeholder { color: ${page_txt_color} !important; opacity: 1; }
				#site-overlay #searchform input:-ms-input-placeholder { color: ${page_txt_color} !important; opacity: 1; }
				#site-overlay #searchform input::placeholder { color: ${page_txt_color} !important; opacity: 1; }

				input::-webkit-input-placeholder, textarea::-webkit-input-placeholder { color: ${page_bg_color}; }
				input::-moz-placeholder, textarea::-moz-placeholder { color: ${page_bg_color}; }
				input:-ms-input-placeholder, textarea:-ms-input-placeholder { color: ${page_bg_color}; }
				input::placeholder, textarea::placeholder { color: ${page_bg_color}; }

				input, textarea {
					color: ${page_bg_color};
					background-color: ${page_txt_color};
				}
						
				h3 .underlined-heading:after, #respond .form-submit #submit .underlined-heading:after, .entry-minimal__title span:after, .entry-archive .entry-read-link:after, .entry-meta a:after, .edit-link:after, .comment-reply-link:after, .hide-comments:after, .not-found a:after, .top-menu li a:after, #site-footer .widget a:after, .comments-link.opened span, .overlay-menu a span, .widget li a:hover:before, .fancybox-nav span:hover, .fancybox-close:hover, .entry-navigation__link:after, .edit-link:after, .page-content h3:after, blockquote:before, .mejs-controls .mejs-time-rail .mejs-time-current, .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current, .mejs-controls .mejs-volume-button .mejs-volume-slider .mejs-volume-current, .mejs-overlay:hover .mejs-overlay-button, .summary .cart button[type=\"submit\"]:after, .single-product .summary form .button[type=\"submit\"] .text:after, .entry-portfolio.hover-two h3:after, body .responsive-bag .woocommerce-cart-no, span.button, .overlay-menu a span.no, .entry-content a:after {
					background-color: ${page_acc_color};
				}
				.mejs-controls .mejs-time-rail .mejs-time-current {
					background-color: ${page_acc_color} !important;
				}
				.comments-link.opened span, #site-sidebar-opener, .widget .tagcloud a:hover, .widget .calendar_wrap td#today:after {
					border-color: ${page_acc_color};
				}
				.comments-link, .comments-link.opened, .comment-meta .by-author, .overlay-menu a.selected, .overlay-menu a.selected span.no, .entry-content a:not(.entry-navigation__item):not(.post-edit-link):not(.fancybox):not(.button):not(.image-text-link), .wpcf7 input[type=\"submit\"]:hover {
					color: ${page_acc_color};
				}
				a.no-link-style, .no-link-style a, .post-tags a {
					color: ${page_txt_color} !important;
				}

				span.button:hover a {
					color: ${page_acc_color} !important;
				}


				#reviews #review_form_wrapper input[type=\"submit\"] {
					background-color: ${page_acc_color} !important;
					color: rgba(" . calafate_hex2RGB($acc_fgd_color, true) . ", 0.65) !important;
				}
				.single-product .summary .cart button[type=\"submit\"] {
					border-color: ${page_txt_color};
					color: ${page_txt_color};
				}
				.single-product .summary .cart button[type=\"submit\"]:hover {
					background-color: ${page_txt_color} !important;
					color: ${page_bg_color} !important;
				}
				.single-product form.cart button[type=\"submit\"] .loading svg circle {
					fill: ${page_txt_color} !important;
				}
				.single-product form.cart button[type=\"submit\"]:hover .loading svg circle {
					fill: ${page_bg_color} !important;
				}
				#reviews #review_form_wrapper input[type=\"submit\"]:hover {
					color: ${page_acc_color} !important;
					background-color: ${acc_fgd_color} !important;
				}
				body .top-menu .cart-item a {
					color: rgba(" . calafate_hex2RGB($page_txt_color, true) . ", 0.5) !important;
				}

				.simple-select-cover .simple-select-inner {
					background-color: rgba(" . calafate_hex2RGB($page_txt_color, true) . ", 0.15);
				}
				.form-columns .simple-select-cover .simple-select-inner {
					background-color: rgba(" . calafate_hex2RGB($page_txt_color, true) . ", 1);
					color: $page_bg_color;
				}
				.single-product .summary .price del {
					color: rgba(" . calafate_hex2RGB($page_txt_color, true) . ", 0.5);
					background-color: rgba(" . calafate_hex2RGB($page_txt_color, true) . ", 0.1);
				}
				.single-product .summary .price del span.amount:first-child:before {
					border-left-color: rgba(" . calafate_hex2RGB($page_txt_color, true) . ", 0.1);
				}
				.page-template-template-portfolio .post-navigation.bigger .no span:first-child:after {
					background-color: rgba(" . calafate_hex2RGB($page_txt_color, true) . ", 0.5);
				}

				.flickity-prev-next-button {
					background-color: {$page_bg_color} !important;
				}

			";

		}

		$page_bg_opacity = 1 - ( calafate_get_field( 'page-bg-opacity') ? calafate_get_field( 'page-bg-opacity' ) / 100 : .5 );
		$page_bg_opacity_s = 1 - ( calafate_get_field( 'page-bg-opacity-s') ? calafate_get_field( 'page-bg-opacity-s' ) / 100 : 0 );

		$custom_css .= "

			/* Hero header */

			body .hero-header .media.active {
				opacity: {$page_bg_opacity_s};
			}
			body .hero-header.active .media {
				opacity: {$page_bg_opacity};
			}

		";
		
		$custom_css = str_replace( array("\rn", "\r", "\n", "\t", '  ', '    ', '    '), '', $custom_css );

		wp_add_inline_style( 'calafate-style', wp_kses( wp_strip_all_tags( $custom_css ),  array( "\'", '\"' ) ) );

	}

endif;

add_action( 'wp_enqueue_scripts', 'calafate_page_style', 20 );

/** Enqueues custom style for admin bar
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_admin_bar_style' ) ) {

	// We cannot add this piece of style via wp_add_inline_style because it will always get overwritten by WordPress's own enqueuing. XCSS ok.

	function calafate_admin_bar_style() {

		echo '<style id="calafate-admin-bar-style" type="text/css">

			html, * html body {
				margin-top: 0 !important;
			}

			#wpadminbar {
				background: rgba(0, 0, 0, .4) !important;
				opacity: .7 !important;
				-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=70)" !important;
				filter: alpha(opacity=70) !important;
			}

		</style>';

	}

}

add_action( 'wp_head', 'calafate_admin_bar_style', 99 );

/** Adds custom classes to the body object
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_body_class' ) ) :

	function calafate_body_class( $classes ) {

		global $post;

		$page_classes = '';

		if ( isset( $post ) ) {

			$page_classes = 'id-' . $post->ID . ' hero-' . ( ! ( is_search() || is_archive() || is_home() ) && calafate_get_field( 'hero-enabled', $post->ID ) === true ? '1' : '0' ) . ( calafate_check_sidebar() ? ' sidebar-enabled' : '' ) . ' gap-' . calafate_get_field( 'hero-reduce-gap', $post->ID ) . ' scroll-' . calafate_get_field( 'hero-stick-it', $post->ID );

			if ( ! ( function_exists( 'is_woocommerce' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) ) {
				$page_classes .= ' color-scheme-' . calafate_get_field( 'page-scheme', $post->ID );
			} 

		}

		$classes[] = ' very-first-init no-js kill-overlay before ' . $page_classes . ' has-sticky-header-' . get_theme_mod( 'calafate_site_sticky', 'enabled' ) . ' has-hamburger-menu-' . get_theme_mod( 'calafate_site_menu', 'disabled' );
		
		return $classes;

	}

endif; 

add_filter( 'body_class', 'calafate_body_class' );

/** Adds custom classes to the menu item's object
 *
 * @since 1.0.0
*/

function calafate_nav_menu_css_class( $classes, $item ) {

	global $post;

	if ( $item->menu_item_parent == 0 ) {
		$classes[] = 'top-level-item';
	}
	if ( is_singular( 'portfolio' ) && calafate_portfolio_pageid( $post->ID ) == $item->object_id ) {
		$classes[] = 'current-menu-item';
	}

  return $classes;
}
add_filter( 'nav_menu_css_class' , 'calafate_nav_menu_css_class' , 10, 2 );

/** Changes the search bar structure
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_search_form' ) ) :

	function calafate_search_form( $form ) {

	    $form = '
	    	<form role="search" method="get" id="searchform" class="searchform" action="' . esc_url( home_url( '/' ) ) . '" >
					<input type="search" autocomplete="off" placeholder="' . esc_html__( 'Your search here', 'calafate' ) . '" name="s" id="s" />
					<button id="submit_s" type="submit">' . calafate_svg( 'search' ) . '</button>
		    </form>
		   ';

	    return $form;
		
	}

endif;

add_filter( 'get_search_form', 'calafate_search_form' );

/** Outputs the google maps instance
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_map' ) ) :

	function calafate_map() {

		if ( calafate_get_field( 'map_enable' ) ) { 

			echo '<div id="map-holder" data-key="' . calafate_get_field( 'gmaps_key' ) . '"><div id="map-contact" class="insert-map" data-map-lat="' . calafate_get_field( 'map_lat' ) . '" data-map-long="' . calafate_get_field( 'map_long' ) . '" data-marker-img="' . calafate_get_field( 'map_marker_img' ) . '" data-zoom="' . calafate_get_field( 'map_zoom' ) . '" data-greyscale="' . calafate_get_field( 'map_style' ) . '" data-marker="' . calafate_get_field( 'map_marker' ) . '"></div><button id="map-toggle">' . calafate_svg( 'plus' ) . '</button></div>';
		}

	}

endif;

/** Add search to menu 
 *
 * @deprecated 1.0.0
*/

if ( ! function_exists( 'calafate_wp_nav_menu_items' ) ) :

	function calafate_wp_nav_menu_items( $items, $args ) {
		if ( $args->theme_location === 'primary' ) {
			$items .= '<li class="search-item"><a href="#" class="open-search">' . esc_html__( 'Search', 'calafate' ) . '</a></li>';
		} 
		return $items;
	} 

endif;

//add_filter( 'wp_nav_menu_items', 'calafate_wp_nav_menu_items', 10, 2 );

/** Outputs social sharing meta tags
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_social_meta' ) ) :

	function calafate_social_meta() {

		global $post;

		if ( isset( $post ) ) {

			echo '<!-- social meta start -->';

	    echo '<meta id="meta-ogtitle" property="og:title" content="' . esc_attr( get_the_title() ) . '"/>';
	    echo '<meta id="meta-ogtype" property="og:type" content="article"/>';
	    echo '<meta id="meta-ogurl" property="og:url" content="' . esc_url( get_permalink() ) . '"/>';
	    echo '<meta id="meta-ogsitename" property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '"/>';
			echo '<meta id="meta-description" property="og:description" content="' . wp_strip_all_tags( calafate_excerpt() ) . '" />';
			echo '<meta id="meta-twittercard" name="twitter:card" value="summary">';

			$post_has_image = false;

			if ( ( is_single() || is_singular( 'portfolio') || is_singular( 'product') ) && has_post_thumbnail( $post->ID ) ) {

				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				calafate_social_meta_image( $thumb[0], $thumb[1], $thumb[2] );

			} else if ( calafate_get_field( 'hero-gallery', $post->ID ) && sizeof( calafate_get_field( 'hero-gallery', $post->ID ) ) > 0 ) {

				$thumb = calafate_get_field( 'hero-gallery', $post->ID );
				calafate_social_meta_image( $thumb[0]['url'], $thumb[0]['width'], $thumb[0]['height'] );

			} 

			echo '<!-- social meta end -->';

		}

	}

endif;

if ( ! function_exists( 'calafate_social_meta_image' ) ) :

	function calafate_social_meta_image( $img_url, $img_width, $img_height ) {

		echo '<meta id="meta-itempropimage" itemprop="image" content="' . esc_url( $img_url ) . '"> ';
		echo '<meta id="meta-twitterimg" name="twitter:image:src" content="' . esc_url( $img_url ) . '">';
		echo '<meta id="meta-ogimage" property="og:image" content="' . esc_url( $img_url ) . '" />';
		echo '<meta id="meta-ogimage" property="og:image:width" content="' . esc_attr( $img_width ) . '" />';
		echo '<meta id="meta-ogimage" property="og:image:height" content="' . esc_attr( $img_height ) . '" />';

	}

endif;

/** Checks single posts parent page in menu
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_single_parent' ) ) : 

	function calafate_single_parent( $menu_postid ) {

		global $post;

		$output = '';

		if ( isset( $post ) ) {
			
			if ( is_singular( 'portfolio' ) && $menu_postid == calafate_portfolio_pageid( $post->ID ) ) {
				$output = ' current-menu-item';
			} else if ( is_singular( 'post' ) && $menu_postid == get_option( 'page_for_posts' ) ) {
				$output = ' current-menu-item';
			} else if ( function_exists( 'wc_get_page_id' ) && is_singular( 'product' ) && $menu_postid == wc_get_page_id( 'shop' ) ) {
				$output = ' current-menu-item';
			}

		}

		return $output;

	}

endif;