<?php
/**
 * Calafate portfolio functions
 *
 * @package Calafate
*/

/** Add inline style for grid in portfolio page templates and single portfolio items
 *
 * @since 1.0.0
 * @changed 1.2.0
*/

if ( ! function_exists( 'calafate_portfolio_style' ) ) :

	function calafate_portfolio_style() {

		global $post;

		if ( is_page_template( 'template-portfolio.php' ) || is_singular( 'portfolio' ) ) {

			$postid = is_page_template( 'template-portfolio.php' ) ? $post->ID : calafate_portfolio_pageid( $post->ID );

			$caption_bg_color = calafate_get_field( 'portfolio-style-background', $postid ) ? calafate_get_field( 'portfolio-style-background', $postid ) : '#fff';
			$caption_bg_opacity = calafate_get_field( 'portfolio-style-opacity', $postid ) ? calafate_get_field( 'portfolio-style-opacity', $postid ) / 100 : '.9';
			$caption_txt_color = calafate_get_field( 'portfolio-style-text', $postid ) ? calafate_get_field( 'portfolio-style-text', $postid ) : '#000';

			$custom_css = "

				/* Portfolio grid style */

				.portfolio-grid[data-id=\"999999\"] .entry-caption h3, .portfolio-grid[data-id=\"999999\"] .entry-caption span, #js-caption[data-id=\"999999\"].entry-caption h3, #js-caption[data-id=\"999999\"].entry-caption span {
					color: {$caption_txt_color};
				}
				.portfolio-grid[data-id=\"999999\"] .entry-caption.Classic:before, #js-caption[data-id=\"999999\"].entry-caption.Minimal h3, #js-caption[data-id=\"999999\"].entry-caption.Minimal span {
					background: {$caption_bg_color};
					opacity: {$caption_bg_opacity};
				}
				
			";
				
			$custom_css = str_replace( array("\rn", "\r", "\n", "\t", '  ', '    ', '    '), '', $custom_css );

			wp_add_inline_style( 'calafate-style', wp_kses( wp_strip_all_tags( $custom_css ),  array( "\'", '\"' ) ) );

		}

	}

endif;

add_action( 'wp_enqueue_scripts', 'calafate_portfolio_style', 30 );

if ( ! function_exists( 'calafate_portfolio_style_shortcode' ) ) :

	function calafate_portfolio_style_shortcode( $postid ) {

		$caption_bg_color = calafate_get_field( 'portfolio-style-background', $postid ) ? calafate_get_field( 'portfolio-style-background', $postid ) : '#fff';
		$caption_bg_opacity = calafate_get_field( 'portfolio-style-opacity', $postid ) ? calafate_get_field( 'portfolio-style-opacity', $postid ) / 100 : '.9';
		$caption_txt_color = calafate_get_field( 'portfolio-style-text', $postid ) ? calafate_get_field( 'portfolio-style-text', $postid ) : '#000';

		$custom_css = "<style type=\"text/css\">

			/* Portfolio grid style */

			.portfolio-grid[data-id=\"{$postid}\"] .entry-caption h3, .portfolio-grid[data-id=\"{$postid}\"] .entry-caption span, #js-caption[data-id=\"{$postid}\"].entry-caption h3, #js-caption[data-id=\"{$postid}\"].entry-caption span {
				color: {$caption_txt_color};
			}
			.portfolio-grid[data-id=\"{$postid}\"] .entry-caption.Classic:before, #js-caption[data-id=\"{$postid}\"].entry-caption.Minimal h3, #js-caption[data-id=\"{$postid}\"].entry-caption.Minimal span {
				background: {$caption_bg_color};
				opacity: {$caption_bg_opacity};
			}
			
		</style>";

		return $custom_css;

	}

endif;

/** Gets the related portfolio page id from within a single portfolio item
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_portfolio_pageid') ) : 

	function calafate_portfolio_pageid( $postid ) {
		
		if ( calafate_get_field( 'portfolio-unique', $postid ) ) {
			return calafate_get_field( 'portfolio-unique', $postid );
		} else if ( get_theme_mod( 'calafate_portfolio_page' ) ) {
			return get_theme_mod( 'calafate_portfolio_page' );
		}

	}

endif; 

/** Outputs related portfolio items
 *
 * @deprecated 1.0.0
*/

if ( ! function_exists( 'calafate_related_portfolio' ) ) :

	function calafate_related_portfolio( $postid ) {

		$portfolio_pageid = calafate_portfolio_pageid( $postid );

		if ( $portfolio_pageid && calafate_get_field( 'portfolio-related', $portfolio_pageid ) ) {

			// get portfolio page's variables

			global $columns, $gap, $portfolio_type, $portfolio_aspect_ratio, $portfolio_style;

			$columns = calafate_get_field( 'portfolio-columns', $portfolio_pageid ); 
			$gap = calafate_get_field( 'portfolio-gap', $portfolio_pageid ); 
			$portfolio_type = calafate_get_field( 'portfolio-type', $portfolio_pageid );
			$portfolio_aspect_ratio = calafate_get_field( 'portfolio-aspect-ratio', $portfolio_pageid );
			$portfolio_style = calafate_get_field( 'portfolio-style', $portfolio_pageid );
			$portfolio_caption = calafate_get_field( 'portfolio-caption', $portfolio_pageid );

			// start the output and build the query

			$output = '';

			$tags = get_the_terms( $postid, 'portfolio_tag' );
			$tag_arr = array();
			$exclude_arr = array( $postid );

			if ( $tags ) {

				foreach ( $tags as $tag ) {
					array_push( $tag_arr, $tag->slug );
				}

	      $args = array(
	      	'offset' => 0,
	        'post_type' => 'portfolio',
				  'orderby' => 'rand',
	        'posts_per_page' => 5,
	        'post__not_in' => $exclude_arr,
	        'tax_query' => array(
	        	array(
	        		'taxonomy' => 'portfolio_tag',
	        		'field' => 'slug',
	        		'terms' => $tag_arr
	        	)
	        )
	      );

	      $related_posts = get_posts( $args );

	      if ( $related_posts ) {

	      	$output .= '<div class="grid__item one-whole portfolio-grid related caption-style-' . esc_attr( $portfolio_style ) . ' mobile-style-' . esc_attr( $portfolio_caption ) . '" data-columns="' . esc_attr( $columns ) . '" data-gap="' . esc_attr( $gap ) . '">';

		      	foreach ( $related_posts as $post ) {
		      		setup_postdata( $post );
		      		global $post;
		      		ob_start();
							get_template_part( 'template-parts/content', 'portfolio' );
							$output .= ob_get_clean();
		      	}

		      $output .= '</div>';

		      wp_reset_postdata();

	      }

			}

			echo $output;

		}

	}

endif; 

/** Outputs portfolio filters
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_portfolio_filters' ) ) : 

	function calafate_portfolio_filters() {

		global $post;

		if ( is_page_template( 'template-portfolio.php' ) ) {

			$output = '';

			$categories = calafate_get_field( 'portfolio-categories' );

			if ( ! empty ( $categories ) && sizeof( $categories ) > 1 ) {
				
				$output .= '<div id="portfolio-filters">
					<ul class="filters-list overlay-menu wrapper">';

					foreach ( $categories as $cat ) {

						$filter = get_term_by( 'id', $cat, 'portfolio_category' ); 

						if ( ! empty( $filter ) ) {

					    $filter_image = calafate_pci_taxonomy_image_url( $filter->term_id );

							$output .= '<li data-img="' . esc_url( $filter_image ) . '"><a href="#' . $filter->slug . '" data-filter=".' . $filter->slug . '">
								' . $filter->name . '
								<span class="no">' . $filter->count . '</span>
							</a></li>';

						}

					}

					$count_posts = wp_count_posts( 'portfolio' );
					$published_posts = $count_posts->publish;

					$output .= '<li><a href="#" data-filter="*" class="selected">
						' . esc_html__( 'All', 'calafate' ) . '
						<span class="no">' . $published_posts . '</span>
					</a></li>';

				$output .= '</ul></div>';

			} else {
				
				$categories = get_categories( array( 'taxonomy'=>'portfolio_category' ) );
				
				$output .= '<div id="portfolio-filters">
					<ul class="filters-list overlay-menu wrapper">';

					foreach ( $categories as $filter ) {

						$output .= '<li><a href="#' . $filter->slug . '" data-filter=".' . $filter->slug . '">
							' . $filter->name . '
							<span class="no">' . $filter->count . '</span>
						</a></li>';

					}

					$count_posts = wp_count_posts( 'portfolio' );
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

if ( ! function_exists( 'calafate_portfolio_filters_button' ) ) :

	function calafate_portfolio_filters_button() {

		global $post;

		if ( is_page_template( 'template-portfolio.php' ) ) {

			$output = '';

			$categories = calafate_get_field( 'portfolio-categories' );

			if ( ! empty ( $categories ) && sizeof( $categories ) > 1 ) {

				$output .= '<a href="#" class="open-filters"><span class="dots-close-anim"><span class="d1"></span><span class="d2"></span><span class="d3"></span></span></a>';

			}

			echo $output;

		}

	}

endif;

/** Outputs post navigation arrows (used both by posts and portfolio items)
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_single_portfolio_navigation' ) ) :

	function calafate_single_portfolio_navigation( $postid ) {

		if ( calafate_get_field( 'portfolio-unique', $postid ) ) {

			$args = array(
				'post_type' => 'portfolio',
				'meta_key' => 'portfolio-unique',
				'meta_value' => calafate_get_field( 'portfolio-unique', $postid ),
				'posts_per_page' => -1
			);

		} else {

			$args = array(
				'post_type' => 'portfolio',
				'posts_per_page' => -1
			);

		}

		$all_posts = new WP_Query( $args );

		$portfolio_array = array();
			
			while ( $all_posts->have_posts() ) : $all_posts->the_post();

				global $post;

				$project_lightbox = calafate_get_field( 'portfolio-lightbox-type', $post->ID );

				if ( ! ( $project_lightbox && ( $project_lightbox === 'img' || $project_lightbox === 'iframe' || $project_lightbox === 'url' ) ) ) {
					array_push( $portfolio_array, $post->ID);
				} 

			endwhile;

		wp_reset_postdata();

		$current_index = array_search( $postid, $portfolio_array );

		if ( $current_index + 1 < sizeof( $portfolio_array ) ) {
			$next_postid = $portfolio_array[$current_index+1];
		}

		if ( ! isset( $next_postid ) ) {
			$next_postid = $portfolio_array[0];
		}

		$output = '<nav class="entry-navigation entry-navigation--portfolio one-half portable--one-whole"><div>';
			$output .= calafate_post_navigation_item( $next_postid, esc_html__( 'Next', 'calafate' ) );
		$output .= '</div></nav>';

		echo $output;

	}

endif;

/** Portfolio category images
 *
 * @since 1.0.0
*/
