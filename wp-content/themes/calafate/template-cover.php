<?php
/**
 * Template Name: Cover
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Calafate
 */

get_header(); 

	$mobile = calafate_get_field('mobile');

	$output = '<div class="covers wrapper style--' . calafate_get_field('style') . '"' . ( isset( $mobile ) && isset( $mobile[0] ) && $mobile[0] == 'taponce' ? ' data-mobile-behavior="taponce"' : '' ) . '>

		<h2 class="covers-title">' . calafate_get_field('title') . '</h2>

		<div class="covers-holder">';

			$items = calafate_get_field('item');

			if ( isset( $items ) && sizeof( $items ) > 0 ) {
				foreach( $items as $key => $item ) {

					$output .= '<a id="c-' . $key . '" ' . ( isset( $item['target'] ) && isset( $item['target'][0] ) && $item['target'][0] == '_blank' ? ' target="_blank"' : '' ) . ' class="cover-item" href="' . esc_url( $item['url'] ) . '" data-id="' . $key . '">
						<h3 class="poppins">' . $item['title'] . '</h3>
					</a>';
						
					$output .= '<div class="bg" style="background-image:url(\'' . $item['image'] . '\');"></div>';

				}
			}

		$output .= '</div>

	</div>';

	if ( calafate_get_field('cta_label') ) {
		$output .= '<div class="covers-cta wrapper">
			<h4 class="covers-title">
				<a class="ajax-link" href="' . calafate_get_field('cta_url') . '">' . calafate_get_field('cta_label') . '</a>
			</h4>
		</div>';
	}

	if ( calafate_get_field( 'cover_bg' ) || calafate_get_field( 'cover_video' ) ) {

		$output .= '<div class="covers-background" data-image="' . esc_url( calafate_get_field( 'cover_bg' ) ) . '" data-video="' . esc_url( calafate_get_field( 'cover_video' ) ) . '"></div>';

	}

	echo $output;
			
get_footer();
