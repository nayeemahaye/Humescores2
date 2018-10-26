<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Calafate
 */

$blog_style = get_theme_mod( 'calafate_blog_style', 'minimal' );
$blog_cols = get_theme_mod( 'calafate_blog_cols', '3' );
$blog_gap = $blog_cols == '3' ? 50 : 95;

get_header(); ?>

	<?php if ( $blog_style == 'minimal' ) : ?>

		<div class="entries--minimal grid__item two-thirds old-breakpoint--whole slide"> 

	<?php else : ?>

		<?php if ( ! isset( $wp_query->query['paged'] ) ) {
			calafate_posts_carousel(); 
		} ?>

		<div class="entries--grid grid__item one-whole blog portfolio-grid" data-columns="<?php echo $blog_cols; ?>" data-gap="<?php echo $blog_gap; ?>">

	<?php endif; ?>

		<?php if ( have_posts() ) :

			/* Start the Loop */
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', $blog_style );

			endwhile;

			wp_reset_postdata();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

	</div>

	<?php calafate_posts_navigation(  $blog_style == 'minimal' ? '' : ' bigger poppins' );
		
get_footer();
