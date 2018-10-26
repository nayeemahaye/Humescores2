<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Calafate
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="grid__item">

	<div class="comments-wrapper">

		<div class="grid">

			<div id="stick-it" class="grid__item two-fifths right old-breakpoint--whole">
				<?php comment_form( array( 
					'fields' => apply_filters( 'comment_form_default_fields', array(
						'author' => '<div class="form-author"><input id="author" name="author" type="text" placeholder="' . esc_html__( 'Name', 'calafate' ) . '" /></div>',
						'email'  => '<div class="form-email"><input id="email" name="email" type="text" placeholder="' . esc_html__( 'Email', 'calafate' ) . '" /></div>',
						'url'    => '' 
					) ),
					'comment_field' => '<div class="form-comment"><textarea id="comment" name="comment" placeholder="' . esc_html__( 'Comment', 'calafate' ) . '"></textarea></div>',
					// translators: %s: login url
					'must_log_in' => '<p class="must-log-in">' .  sprintf( wp_kses( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'calafate' ), array( 'a' => array( 'href' => array() ) ) ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>', // WPCS: XSS OK.
					// translators: %1$s: account page url, %2$s: username, %3$s: log out url
					'logged_in_as' => '<p class="logged-in-as">' . sprintf( wp_kses( __( 'You are logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'calafate' ), array( 'a' => array( 'href' => array() ) ) ), admin_url( 'profile.php' ), isset( $user_identity ) ? $user_identity : '', wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>', // WPCS: XSS OK.
					'comment_notes_before' => '',
					'comment_notes_after' => '',
					'id_form' => 'comment-form',
					'id_submit' => 'submit',
					'title_reply' => esc_html__( 'Comment', 'calafate' ),
					'title_reply_to' => esc_html__( 'Reply', 'calafate' ),
					'cancel_reply_link' => esc_html__( 'Cancel', 'calafate' ),
					'label_submit' => esc_html__( 'Send', 'calafate' ),
				) ); ?>
			</div>

			<div class="grid__item one-half old-breakpoint--whole">

				<?php
				// You can start editing here -- including this comment!
				if ( have_comments() ) : ?>

					<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
					<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
						<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'calafate' ); ?></h2>
						<div class="nav-links">

							<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'calafate' ) ); ?></div>
							<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'calafate' ) ); ?></div>

						</div><!-- .nav-links -->
					</nav><!-- #comment-nav-above -->
					<?php endif; // Check for comment navigation. ?>

					<ul id="comments-list">
						<?php
							wp_list_comments( array(
								'style'      => 'ol',
								'short_ping' => true,
								'callback' => 'calafate_comment'
							) );
						?>
					</ul><!-- .comment-list -->

					<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
					<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
						<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'calafate' ); ?></h2>
						<div class="nav-links">

							<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'calafate' ) ); ?></div>
							<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'calafate' ) ); ?></div>

						</div><!-- .nav-links -->
					</nav><!-- #comment-nav-below -->
					<?php
					endif; // Check for comment navigation.

				else : ?>

					<p class="no-comments"><?php esc_html_e( 'This post doesn\'t have any comment. Be the first one!', 'calafate' ); ?></p>

				<?php endif; // Check for have_comments().

				// If comments are closed and there are comments, let's leave a little note, shall we?
				if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

					<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'calafate' ); ?></p>

				<?php endif;	?>

			</div>

		</div>

		<a class="hide-comments" href="#"><?php esc_html_e( 'hide comments', 'calafate' ); ?></a>

	</div>

</div><!-- #comments -->
