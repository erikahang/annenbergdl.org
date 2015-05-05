<?php
/**
 * Template Name: Grid view
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 
get_header(); ?>
 
<div id="main-content" class="main-content">
 
    <div id="featured-content" class="featured-content">
     <div class="featured-content-inner">
 
        <?php
      // Fix for the WordPress 3.0 "paged" bug.
      $paged = 1;
      if ( get_query_var( 'paged' ) ) { $paged = get_query_var( 'paged' ); }
      if ( get_query_var( 'page' ) ) { $paged = get_query_var( 'page' ); }
      $paged = intval( $paged );
       
      $query_args = array(
                                    'post_type' => 'post', 
                                    'paged' => $paged,
                                    'tag' => 'featured'
                                    
                                );
      $query_args = apply_filters( 'home_query_args', $query_args );
      query_posts($query_args);
            if ( have_posts() ) :
                // Start the Loop.
                while ( have_posts() ) : the_post();
 
                    /*
                     * Include the post format-specific template for the content. If you want to
                     * use this in a child theme, then include a file called called content-___.php
                     * (where ___ is the post format) and that will be used instead.
                     */
                    get_template_part( 'content', 'featured-post' );
 
                endwhile;
        echo '<div class="clear"></div>';
                // Previous/next post navigation.
                twentyfourteen_paging_nav();  
        wp_reset_query();
            else :
                // If no content, include the "No posts found" template.
                get_template_part( 'content', 'none' );
 
            endif;
       
       
        ?>
 
          </div><!-- .featured-content-inner -->
    </div><!-- #featured-content .featured-content -->
    <?php //get_sidebar( 'content' ); ?>
</div><!-- #main-content -->
 
<?php
get_sidebar();
get_footer();
?>