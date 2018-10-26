<?php
/**
 * Calafate general functions
 *
 * @package Calafate
*/

/** Outputs the social sharing options
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_social_sharing' ) ) :

	function calafate_social_sharing( $postid ) {

		$url = urlencode( get_permalink( $postid ) );
		$title = urlencode( get_the_title( $postid ) );
		$media = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'large' );

		$widget_title = '';
		$widget_content = '';

		if ( get_theme_mod( 'calafate_site_sharing', 'enabled' ) === 'enabled' ) {
			$t_tw = esc_html__( 'Tw', 'calafate' ) . '<small>.</small>';
			$t_fb = esc_html__( 'Fb', 'calafate' ) . '<small>.</small>';
			$t_pin = esc_html__( 'Pin', 'calafate' ) . '<small>.</small>';
			$t_500 = esc_html__( '500px', 'calafate' ) . '<small>.</small>';
			$t_drb = esc_html__( 'Dribb', 'calafate' ) . '<small>.</small>';
			$t_ins = esc_html__( 'Ins', 'calafate' ) . '<small>.</small>';
			$t_lkd = esc_html__( 'Lin', 'calafate' ) . '<small>.</small>';
			$t_snd = esc_html__( 'Sound', 'calafate' ) . '<small>.</small>';
		} else if ( get_theme_mod( 'calafate_site_sharing', 'enabled' ) === 'enabled-icons' ) {
			$t_tw = calafate_svg( 'social_twitter', 'icon' );
			$t_fb = calafate_svg( 'social_facebook', 'icon' );
			$t_pin = calafate_svg( 'social_pinterest', 'icon' );
			$t_500 = calafate_svg( 'social_500px', 'icon' );
			$t_drb = calafate_svg( 'social_dribbble', 'icon' );
			$t_ins = calafate_svg( 'social_instagram', 'icon' );
			$t_lkd = calafate_svg( 'social_linkedin', 'icon' );
			$t_snd = calafate_svg( 'social_soundcloud', 'icon' );
		}

		if ( get_theme_mod( 'calafate_site_social', 'sharing' ) === 'network' ) {

			$widget_title = esc_html__( 'Follow', 'calafate' );

			if ( get_theme_mod( 'calafate_social_500px' ) != '' ) {
				$widget_content .= '<span class="site-share"><a class="px" target="_blank" href="' .  esc_url( get_theme_mod( 'calafate_social_500px' ) ) . '">' . $t_500 . '</span></a>';
			}
			if ( get_theme_mod( 'calafate_social_dribbble' ) != '' ) {
				$widget_content .= '<span class="site-share"><a class="dr" target="_blank" href="' .  esc_url( get_theme_mod( 'calafate_social_dribbble' ) ) . '">' . $t_drb . '</span></a>';
			}
			if ( get_theme_mod( 'calafate_social_facebook' ) != '' ) {
				$widget_content .= '<span class="site-share"><a class="fb" target="_blank" href="' .  esc_url( get_theme_mod( 'calafate_social_facebook' ) ) . '">' . $t_fb . '</span></a>';
			}
			if ( get_theme_mod( 'calafate_social_instagram' ) != '' ) {
				$widget_content .= '<span class="site-share"><a class="in" target="_blank" href="' .  esc_url( get_theme_mod( 'calafate_social_instagram' ) ) . '">' . $t_ins . '</span></a>';
			}
			if ( get_theme_mod( 'calafate_social_linkedin' ) != '' ) {
				$widget_content .= '<span class="site-share"><a class="li" target="_blank" href="' .  esc_url( get_theme_mod( 'calafate_social_linkedin' ) ) . '">' . $t_lkd . '</span></a>';
			}
			if ( get_theme_mod( 'calafate_social_pinterest' ) != '' ) {
				$widget_content .= '<span class="site-share"><a class="pin" target="_blank" href="' .  esc_url( get_theme_mod( 'calafate_social_pinterest' ) ) . '">' . $t_pin . '</span></a>';
			}
			if ( get_theme_mod( 'calafate_social_soundcloud' ) != '' ) {
				$widget_content .= '<span class="site-share"><a class="snd" target="_blank" href="' .  esc_url( get_theme_mod( 'calafate_social_soundcloud' ) ) . '">' . $t_snd . '</span></a>';
			}
			if ( get_theme_mod( 'calafate_social_twitter' ) != '' ) {
				$widget_content .= '<span class="site-share"><a class="tw" target="_blank" href="' .  esc_url( get_theme_mod( 'calafate_social_twitter' ) ) . '">' . $t_tw . '</span></a>';
			}

		} else {

			$widget_title = esc_html__( 'Share', 'calafate' );

			$widget_content .= '<span class="site-share"><a class="tw" target="_blank" href="' .  esc_url( 'https://twitter.com/home?status=' . $title . '+' . $url ) . '">' . $t_tw . '</span></a>';
			$widget_content .= '<span class="site-share"><a class="fb" target="_blank" href="' .  esc_url( 'https://www.facebook.com/share.php?u=' . $url . '&title=' . $title ) . '">' . $t_fb . '</span></a>';
			$widget_content .= '<span class="site-share"><a class="pin" target="_blank" href="' .  esc_url( 'http://pinterest.com/pin/create/bookmarklet/?media=' . $media[0] . '&url=' . $url . '&is_video=false&description=' . $title ) . '">' . $t_pin . '</span></a>';

		}

		$output = '<div id="site-share" class="' . esc_attr( get_theme_mod( 'calafate_site_sharing' ) ) . '">';
			
			$output .= '<span class="site-share"><span class="info">' . $widget_title . '</span></span>';
			$output .= $widget_content;

		$output .= '</div>';

		echo $output;

	}

endif;

/** Outputs the site actions
 *
 * @since 1.0.0
*/


if ( ! function_exists( 'calafate_site_actions' ) ) :

	function calafate_site_actions( $postid ) {

		$output = '<div id="site-actions" class="site-actions">';

			$output .= '<div id="site-actions-holder">';

				$output .= '<a id="site-actions-up" href="#">' . calafate_svg( 'action-up', 'icon' ) . '<span class="tooltip">' . esc_html__( 'Go to Top', 'calafate' ) . '</span></a>';

				if ( is_singular( 'portfolio' ) ) {

					$portfolio_pageid = calafate_portfolio_pageid( $postid );
					$output .= '<a id="site-actions-back" class="ajax-link" href="' . get_the_permalink( $portfolio_pageid ) . '">' . calafate_svg( 'action-back', 'icon' ) . '<span class="tooltip">' . esc_html__( 'Back to Portfolio', 'calafate' ) . '</span></a>';

				} elseif ( is_singular( 'post' ) || is_archive() ) {

					if ( get_option('show_on_front') == 'page' ) {
						$blog_pageid = get_option( 'page_for_posts' );
						$blog_permalink = get_the_permalink( $blog_pageid );
					} else {
						$blog_permalink = home_url( '/' );
					}
					$output .= '<a id="site-actions-back" class="ajax-link" href="' . esc_url( $blog_permalink ) . '">' . calafate_svg( 'action-back', 'icon' ) . '<span class="tooltip">' . esc_html__( 'Back to Blog', 'calafate' ) . '</span></a>';

				} elseif ( function_exists( 'is_woocommerce') && ( is_woocommerce() ) ) {

					$shop_permalink = get_permalink( wc_get_page_id( 'shop' ) );
					$output .= '<a id="site-actions-back" class="ajax-link" href="' . esc_url( $shop_permalink ) . '">' . calafate_svg( 'action-back', 'icon' ) . '<span class="tooltip">' . esc_html__( 'Back to Shop', 'calafate' ) . '</span></a>';

				}

				$output .= '<a id="site-actions-search" href="#">' . calafate_svg( 'action-search', 'icon' ) . '<span class="tooltip">' . esc_html__( 'Search', 'calafate' ) . '</span></a>';

			$output .= '</div>';

		$output .= '</div>';

		echo $output;

	}

endif;