<?php
/**
 * Calafate post functions
 *
 * @package Calafate
*/

/** Returns/echoes the categories of a certain taxonomy (used both by posts and portfolio items)
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_categories' ) ) :

	function calafate_categories( $post_id, $taxonomy, $delimiter = ', ', $get = 'name', $echo = true, $link = false, $portfolio_link = '' ){

		$tags = wp_get_post_terms( $post_id, $taxonomy );
		$list = '';
		foreach( $tags as $tag ){
			if ( $link ) {
				if ( is_singular( 'portfolio' ) ) {
					$category_link = $portfolio_link . '#' . $tag->slug;
				} else {
					$category_link = get_category_link( $tag->term_id );
				}
				$list .= '<a class="ajax-link" href="' . $category_link . '"> ' . $tag->$get . '</a>' . $delimiter;
			} else {
				$list .= $tag->$get . $delimiter;
			}
		}

		if ( $echo ) {
			echo substr( $list, 0, strlen( $delimiter ) * ( -1 ) );
		} else { 
			return substr( $list, 0, strlen( $delimiter ) * ( -1 ) );
		}

	}

endif;

/** Outputs post navigation
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_single_post_navigation' ) ) :

	function calafate_single_post_navigation( $postid, $style = 'post-half' ) {

		if ( $style === 'post-half' ) {

			$next_post = get_adjacent_post( false, '', true, 'category' );

			if ( empty( $next_post ) ) {

				$first_post = wp_get_recent_posts( array(
					'numberposts' => 1,
					'post_type' => 'post'
				) );

				$next_postid = $first_post[0]['ID'];

			} else {
				
				$next_postid = $next_post->ID;
				
			}

			$output = '<nav class="entry-navigation entry-navigation--post one-half old-breakpoint--whole"><div>';
				$output .= calafate_post_navigation_item( $next_postid, esc_html__( 'Next', 'calafate' ) );
			$output .= '</div></nav>';

			echo $output;

		} else {

			$next_post = get_adjacent_post( false, '', true, 'category' );
			$prev_post = get_adjacent_post( false, '', false, 'category' );

			$output = '<nav class="post-navigation bigger"><div>';

				$output .= '<a class="prev ajax-link" ' . ( ! empty( $prev_post ) ? ' href="' . get_the_permalink( $prev_post->ID ) . '"' : '' ) . '>'
					. calafate_svg( 'arrow-left' )
				. '</a>';

				$output .= '<span class="no">'
					. '<span>' . esc_html__( 'Prev', 'calafate' ) . '</span>'
					. '<span>' . esc_html__( 'Next', 'calafate' ) . '</span>'
				. '</span>';

				$output .= '<a class="next ajax-link" ' . ( ! empty( $next_post ) ? ' href="' . get_the_permalink( $next_post->ID ) . '"' : '' ) . '>'
					. calafate_svg( 'arrow-right' )
				. '</a>';

			$output .= '</div></nav>';

			echo $output;

		}

	}

endif;

/** Outputs the post navigation item (used both for posts and portfolios)
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_post_navigation_item' ) ) :

	function calafate_post_navigation_item( $postid, $link_label ) {

		global $post;

		$output = '';

		$output .= '<a class="entry-navigation__item ajax-link" href="' . get_permalink( $postid ) . '">';

			$output .= '<span class="entry-navigation__link">' . $link_label . '</span>';

			$output .= '<span class="entry-navigation__info">';

				$output .= '<span class="title">' . get_the_title( $postid ) . '</span>';

			$output .= '</span>';

			if ( is_singular( 'portfolio' ) || is_singular( 'product' ) ) {
				$output .= calafate_output_hero_header_image_for_preload( $postid );
			}

		$output .= '</a>';

		return $output; 

	}

endif;

/** Outputs the post's footer (comments link)
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_entry_footer' ) ) :

	function calafate_entry_footer() {

		global $post;

		if ( comments_open() || get_comments_number() ) {

			if ( is_single() || ( is_singular( 'portfolio' ) && get_theme_mod( 'calafate_portfolio_comments' ) === 'enabled' ) ) {

				echo '
					<footer class="entry-footer">
						<div class="comments-link">
							<span>' . get_comments_number( '0', '1', '%' ) . '</span>
							' . esc_html__( 'Comments', 'calafate' ) . '
						</div>
					</footer>
				';

			}

		}

	}

endif;

/** Individual comment structure
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_comment' ) ) :

	function calafate_comment( $comment, $args, $depth ) {
		
		$GLOBALS['comment'] = $comment;
		global $post;
		switch ( $comment->comment_type ) :
			case '' :
		?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>

			<article itemscope itemtype="http://schema.org/Comment">
			
				<div class="comment-avatar">
					<?php echo get_avatar( $comment, 160, $default='' ); ?>
					<?php if ( $post = get_post( $post->ID ) ) {
						if ( $comment->user_id === $post->post_author ) {
							echo '<span class="by-author">' . calafate_svg( 'krown', 'icon' ) . '</span>';
						}
					} ?>
					</div>

				<div class="comment-content">

					<div class="comment-meta clearfix">

						<h6 class="comment-title">

							<?php echo ( get_comment_author_url() != '' ? '<a itemprop="creator" href="' . get_comment_author_url() . '" target="blank">' . get_comment_author() . '</a>' : comment_author() ); ?>
							<?php esc_html_e( 'says:', 'calafate' ); ?>
						</h6>

						<span class="comment-date" itemprop="dateCreated">
							<?php
								/* translators: Single post date format, see https://codex.wordpress.org/Formatting_Date_and_Time */
								echo comment_date( esc_html__( 'M j \a\t h:i', 'calafate' ) ); 
							?>
						</span>

					</div>

					<div class="comment-text">

						<div itemprop="text">

							<?php if ( $comment->comment_approved == '0' ) : ?>
								<p><em class="await"><?php esc_html_e( 'Your comment is awaiting moderation.', 'calafate' ); ?></em></p>
							<?php endif; ?>

							<?php comment_text(); ?>

						</div>

						<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => 3, 'reply_text' => esc_html__( 'reply', 'calafate') ) ) ); ?>

					</div>

				</div>

			</article>

		<?php
			break;
			case 'pingback'  :
			case 'trackback' :
		?>
		
		<li class="post pingback">
			<p><?php esc_html_e( 'Pingback:', 'calafate' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( '(Edit)', 'calafate' ), ' ' ); ?></p></li>
		<?php
				break;
		endswitch;
	}

endif; 

/** Redefine navigation in blog/archive/search pages
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_posts_navigation' ) ) :

	function calafate_posts_navigation( $class = '', $query = null ) {

		if ( $query === null ) {
			global $wp_query;
			$query = $wp_query;
		}

		$page = $query->query_vars['paged'] === 0 ? 1 : $query->query_vars['paged'];
		$pages = $query->max_num_pages;

		$output = '';

		if( $pages > 1 ) {

			$output .= '<div class="post-navigation' . esc_attr( $class ) . '"><div class="portable--left right">';

				$output .= '<a class="prev ajax-link"' . ( $page - 1 >= 1 ? ' href="' . esc_url( get_pagenum_link( $page - 1 ) ) . '"' : '' ) . '>'
					. calafate_svg( 'arrow-left' )
				. '</a>';

				$output .= '<span class="no">'
					. '<span>' . $page . '</span>'
					. '<span>' . $pages . '</span>'
				. '</span>';

				$output .= '<a class="next ajax-link"' . ( $page + 1 <= $pages ? ' href="' . esc_url( get_pagenum_link( $page + 1 ) ) . '"' : '' ) . '>'
					. calafate_svg( 'arrow-right' )
				. '</a>';

			$output .= '</div></div>';

		}

		echo $output;

	}

endif;

/** Get the archive title 
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_archive_title' ) ) :

	function calafate_archive_title() {

		// We need such a function because the default WP one returns everything in a single string

    if ( is_category() ) {
      $title = esc_html__( 'Category', 'calafate' );
			$subtitle = single_cat_title( '', false );
    } elseif ( is_tag() ) {
      $title = esc_html__( 'Tag', 'calafate' );
			$subtitle = single_tag_title( '', false );
    } elseif ( is_author() ) {
      $title = esc_html__( 'Author', 'calafate' );
			$subtitle = get_the_author();
    } elseif ( is_year() ) {
      $title = esc_html__( 'Year', 'calafate' );
      $subtitle = get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'calafate' ) );
    } elseif ( is_month() ) {
      $subtitle = esc_html__( 'Month', 'calafate' );
      $title = get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'calafate' ) );
    } elseif ( is_day() ) {
      $title = esc_html__( 'Day', 'calafate' );
      $subtitle = get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'calafate' ) );
    } elseif ( is_post_type_archive() ) {
      $title =  esc_html__( 'Archives', 'calafate' );
      $subtitle = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
      $tax = get_taxonomy( get_queried_object()->taxonomy );
      $title = $tax->labels->singular_name;
      $subtitle = single_term_title( '', false );
    } else {
      $title = esc_html__( 'Archives', 'calafate' );
    }

    echo '<header class="archive-header">
    	<h1>' . $title . '</h1>
    	<h3>' . $subtitle . '</h3>
    </header>';

  }

endif;

/** Redefine the_excerpt
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_excerpt_length' ) ) :
	function calafate_excerpt_length() {
	  return 30;
	}
endif;

if ( ! function_exists( 'calafate_excerpt_length_small' ) ) :
	function calafate_excerpt_length_small() {
	  return 15;
	}
endif;

if ( ! function_exists( 'calafate_excerpt_more' ) ) :
	function calafate_excerpt_more() {
	  return ' ...';
	}
endif;

if ( ! function_exists( 'calafate_excerpt' ) ) :

	function calafate_excerpt( $length_callback = 'calafate_excerpt_length', $more_callback = 'calafate_excerpt_more', $postid = 'null' ) {

		if ( $postid === 'null') {
  	  global $post;
  	  $postid = $post->ID;
		}

    if ( function_exists( $length_callback ) ) {
			add_filter( 'excerpt_length', $length_callback );
    }
	
    if ( function_exists( $more_callback ) ){
			add_filter( 'excerpt_more', $more_callback );
    }
	
    $output = '';

    if ( has_excerpt() ) {

    	$output = get_the_excerpt( $postid );
	    $output = apply_filters( 'wptexturize', $output );
	    $output = apply_filters( 'convert_chars', $output );

    } else {

    	$output = '';

    	$page_object = get_page( $postid );
    	$content = $page_object->post_content;

    	if ( ! empty( $content ) ) {

	    	// If the excerpt is empty (on pages created 100% with shortcodes), we should take the content, strip shortcodes, remove all HTML tags, then return the correct number of words

	    	$output = strip_tags( preg_replace( "~(?:\[/?)[^\]]+/?\]~s", '', $content ) );
	    	$output = explode( ' ', $output, $length_callback() );
	    	array_pop( $output );
	    	$output = implode( ' ', $output ) . $more_callback();

	    }

    }
	
    return $output;
		
	}   

endif;

/** Categoriezed blog helpers
 *
 * @since 1.0.0
*/

function calafate_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'calafate_categories' ) ) ) {
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			'number'     => 2
		) );
		$all_the_cool_cats = count( $all_the_cool_cats );
		set_transient( 'calafate_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		return true;
	} else {
		return false;
	}
}

function calafate_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	delete_transient( 'calafate_categories' );
}
add_action( 'edit_category', 'calafate_category_transient_flusher' );
add_action( 'save_post',     'calafate_category_transient_flusher' );

/** Posts carousel
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_posts_carousel' ) ) :

	function calafate_posts_carousel() {

		$posts_carousel = get_theme_mod( 'calafate_blog_carousel', 'none' ); 

		if ( $posts_carousel !== 'none' && ! empty( $posts_carousel ) && $posts_carousel[0] !== 'none' ) {

			$output = '<div class="blog-posts-carousel carousel">';

				$i = 0;

				$all_posts = new WP_Query( array(
					'post__in' => $posts_carousel,
					'ignore_sticky_posts' => 1
				) ); 

				while ( $all_posts->have_posts() ) : $all_posts->the_post();

					$output .= '<div class="car-post carousel-cell" data-index="' . $i++ . '">';

						$img = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

						if ( strpos( $img, '.gif' ) === false ) {
							$img_620 = aq_resize( $img, 620, 345, true, true, true );
							$img_900 = aq_resize( $img, 900, 500, true, true, true );
							$img_1300 = aq_resize( $img, 1300, 723, true, true, true );
							$img_1800 = aq_resize( $img, 1800, 1000, true, true, true );
						} else {
							$img_620 = $img;
							$img_900 = $img;
							$img_1300 = $img;
							$img_1800 = $img;
						}

						$output .= '<figure class="car-header" style="padding-top:55.555%">
							<img class="car-image" src="' . esc_url( $img_620 ) . '" srcset="' . esc_url( $img_620 ) . ' 620w, ' . esc_url( $img_900 ) . ' 900w, ' . esc_url( $img_1300 ) . ' 1300w, ' . esc_url( $img_1800 ) . ' 1800w" sizes="(min-width: 1024px) 47vw, 60vw" alt="' . get_the_title() . '" />
						</figure>';

						$output .= '<div class="car-content">';

							$output .= '<span class="car-meta poppins">
								<time datetime="' . get_the_time( 'c' ) . '">
									' . get_the_time( get_option( 'date_format' ) ) . '
								</time>
							</span>';

							$output .= '<a class="ajax-link" href="' . get_the_permalink() . '" title="' . get_the_title() . '">';

								$output .= '<h2 class="car-title poppins">' . get_the_title() . '</h2>';

								$post_excerpt = calafate_excerpt( 'calafate_excerpt_length', 'calafate_excerpt_more' );

								if ( ! empty( $post_excerpt ) ) {
									$output .= '<p class="car-excerpt">' . $post_excerpt . '</p>';
								}

							$output .= '</a>';

						$output .= '</div>';

					$output .= '</div>';

				endwhile;

				wp_reset_postdata();

			$output .= '</div>';

			echo $output;

		}

	}

endif;

/** Post stamp
 *
 * @since 1.0.0
*/

if ( ! function_exists( 'calafate_post_stamp' ) ) :

	function calafate_post_stamp( $postid ) {

		$output = '<article class="entry-portfolio uninit STAMP">';
			
			$output .= '<div class="stamp-holder" style="background-image: url(' . wp_get_attachment_url( get_post_thumbnail_id( $postid ) ) . ');">';

				$output .= '<div class="stamp-content">';
					$output .= '<div class="stamp-wrapper">';

						$output .= '<span class="stamp-time poppins">' . get_the_time( get_option( 'date_format', $postid ) ) . '</span>';
						$output .= '<a class="ajax-link" href="' .get_the_permalink( $postid ) . '"><h2 class="stamp-title poppins">' . get_the_title( $postid ) . '</h2></a>';
						$output .= '<a class="stamp-link ajax-link" href="' . get_the_permalink( $postid ) . '">' . esc_html__( 'Read', 'calafate' ) . '</a>';

						$output .= '<img src="' . wp_get_attachment_url( get_post_thumbnail_id( $postid ) ) . '" data-src-retina="' . wp_get_attachment_url( get_post_thumbnail_id( $postid ) ) . '" srcset="data:image/gif;base64,R0lGODlhAQABAJH/AP///wAAAMDAwAAAACH5BAEAAAIALAAAAAABAAEAAAICVAEAOw==" alt="' . get_the_title( $postid ) . '" />';

					$output .= '</div>';
				$output .= '</div>';

			$output .= '</div>';

		$output .= '</article>';

		echo $output;

	}

endif;

/** WP navigation
 *
 * @since 1.0.0
*/

function calafate_post_navigation() {
	the_posts_navigation();
}