<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Calafate
 */

get_header(); ?>

	<div class="one-whole grid__item">

		<header class="archive-header">
			<h1><?php esc_html_e( 'Search', 'calafate' ); ?></h1>
			<h3><?php the_search_query(); ?></h3>
		</header><!-- .page-header -->

		<?php	if ( have_posts() ) :

			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'search' );

			endwhile;
		
			wp_reset_postdata();

			calafate_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

	</div>

<?php
get_footer();
