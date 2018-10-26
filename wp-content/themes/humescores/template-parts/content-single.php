<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Humescores
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
           
            <div class="intro">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

	?>
            <div class="intro_para">
               <?php
               the_field('intro_paragraph');
               ?>
            </div>
             
                
            </div>
		 <?php humescores_the_category_list(); ?>
	</header><!-- .entry-header -->
        
        <hr>

	<?php humescores_post_thumbnail(); ?>
        <section class="post-content">
            
            <?php 
            if ( is_active_sidebar('sidebar-1') ) : ?>
            
            <div class="post-content__wrap">
			
                        
                        <div class="post-content__body">
		<?php endif; ?>
            
            
            
	<div class="entry-content">
            <div class="entry-meta">
				<?php
                                
                                
				humescores_posted_on();
                                humescores_posted_by();
				
				?>
			</div><!-- .entry-meta -->
		<?php
		the_content( sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'humescores' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			get_the_title()
		) );

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'humescores' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php humescores_entry_footer(); ?>
	</footer><!-- .entry-footer -->
        
        <?php 
        if (!is_active_sidebar('sidebar-1')) : ?>
        
                        </div><!-- post-content-body -->
            </div><!-- post-content-wrap -->
            
            <?php endif; ?>
        
        
        <?php
        humescores_post_navigation();
        // If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
        ?>
        
        </section><!--post-content -->
        
            <?php 
        get_sidebar();
        ?>
        
</article><!-- #post-<?php the_ID(); ?> -->
