<?php
/**
 * Template Name: Portfolio
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Calafate
 */

get_header(); ?>

	<?php if ( post_password_required() ) :
		the_content();
	else : ?>
	
		<?php calafate_hero_tagline( $post->ID ); ?>

		<?php if ( have_posts() ) while ( have_posts() ) : the_post();

			/* Set some variables */

			$columns = calafate_get_field( 'portfolio-columns' ); 
			$gap = calafate_get_field( 'portfolio-gap' ); 
			$portfolio_type = calafate_get_field( 'portfolio-type' );
			$portfolio_aspect_ratio = calafate_get_field( 'portfolio-aspect-ratio' );
			$portfolio_aspect_ratio = explode(':', $portfolio_aspect_ratio);
			$portfolio_style = calafate_get_field( 'portfolio-style' );
			$portfolio_caption = calafate_get_field( 'portfolio-caption' );

			$post_per_page = calafate_get_field( 'portfolio-page' );	

			$categories = calafate_get_field( 'portfolio-categories' );
			$query_filter = '';

			if ( ! empty ( $categories ) ) {

				foreach ( $categories as $cat ) {

					$filter = get_term_by( 'id', $cat, 'portfolio_category' ); 
					if ( ! empty( $filter ) ) {
						$query_filter .= $filter->slug . ',';
					}

				}

			}

			$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : ( get_query_var( 'page' ) ? get_query_var( 'page' ) : 1 );

		?>

			<?php if ( calafate_get_field( 'portfolio-extra' ) == '2' ) : ?>
				<div class="entry-content page-content up">
					<?php the_content();	?>
				</div>
			<?php endif; ?>

			<div class="main-grid grid__item one-whole portfolio-grid caption-style-<?php echo esc_attr( $portfolio_style ); ?> mobile-style-<?php echo esc_attr( $portfolio_caption ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" data-gap="<?php echo esc_attr( $gap ); ?>" data-id="999999"> 

				<?php /* Start the Loop */

					$args = array(
						'post_type' => 'portfolio',
						'portfolio_category' => $query_filter,
						'paged' => $paged,
						'posts_per_page' => $post_per_page
					);

					$all_posts = new WP_Query( $args ); 

					while ( $all_posts->have_posts() ) : $all_posts->the_post();

						get_template_part( 'template-parts/content', 'portfolio' );

					endwhile; 

				?>

			</div>

			<?php 

				if ( $post_per_page !== '-1' ) {
					calafate_posts_navigation( ' bigger poppins', $all_posts );
				}

				wp_reset_postdata();

			?>

			<?php if ( calafate_get_field( 'portfolio-extra' ) == '1' ) : ?>
				<div class="entry-content page-content">
					<?php the_content();	?>
				</div>
			<?php endif; ?>

		<?php endwhile;

	endif;
		
get_footer();
