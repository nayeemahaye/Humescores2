<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Calafate
 */

get_header(); ?>

	<div class="one-whole grid__item ajax-children">

		<?php
		if ( have_posts() ) : ?>

			<?php calafate_archive_title(); ?>

			<?php

			/* Start the Loop */
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'archive' );

			endwhile;
			
			wp_reset_postdata();

			calafate_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>


	</div>


<?php
get_footer();
