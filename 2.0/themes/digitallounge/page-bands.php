<?php
/**
 * Template Name: Content Bands
 *
 * This template shows content based on featured bands selected with a menu.
 *
 * @package Digital Lounge
 */

wp_enqueue_script( 'digitallounge-jquery-visible', get_template_directory_uri() . '/js/jquery.visible.js', array( 'jquery' ), '20151103', true );
wp_enqueue_script( 'digitallounge-infinitescroll', get_template_directory_uri() . '/js/infinite-scroll.js', array( 'digitallounge-jquery-visible', 'jquery', 'wp-util' ), '20150614', true );

get_header(); ?>
	
<div id="primary" class="content-area paper-back">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post();
		
		// Don't do anything in the loop - all of the content comes from the menu.

		endwhile; // end of the loop.

		// Featured content bands.
		$walker = new Featured_Bands_Walker();
		wp_nav_menu( array(
			'depth' => 0,
			'walker' => $walker,
			'theme_location' => 'featured',
			'items_wrap' => '<div id="%1$s" class="%2$s">%3$s</div>',
		));
		?>
	</main><!-- #main -->
</div><!-- #primary -->
<script type="text/html" id="tmpl-archive-grid-view">
	<article id="post-{{ data.id }}" <?php post_class(); ?>>
		<a href="{{ data.permalink }}" rel="bookmark" class="featured-image">{{{ data.post_thumbnail }}}</a>
		<# if ( data.icon ) { #> {{{ data.icon }}} <# } #>
		<header class="entry-header">
			<h1 class="entry-title"><a href="{{ data.permalink }}" rel="bookmark">{{ data.title }}</a></h1>
			<div class="entry-meta">
				{{{ data.posted_on }}}
			</div>
		</header>
		<div class="entry-excerpt">
			{{{ data.excerpt }}}
		</div>
	</article>
</script>

<?php get_footer(); ?>
