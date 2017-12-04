<?php
/**
 * The home page template.
 *
 * Includes the default home page and the customized home page views.
 *
 * @package Digital Lounge
 */

wp_enqueue_script( 'digitallounge-jquery-visible', get_template_directory_uri() . '/js/jquery.visible.js', array( 'jquery' ), '20151103', true );
wp_enqueue_script( 'digitallounge-infinitescroll', get_template_directory_uri() . '/js/infinite-scroll.js', array( 'digitallounge-jquery-visible', 'jquery', 'wp-util' ), '20150614', true );

get_header(); ?>

	<div id="primary" class="content-area paper-back">
		<main id="main" class="site-main" role="main">
		<section class="query-container news-collection animated slideInLeft delay1-2sec paper-front" data-type="post_type" data-post_type="post" data-page="1" data-visible_page="1" data-content_size="1744">
				<div class="news-title">
					<h2 class="section-title">News</h2>
				<div class="inner-container">
				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" class="featured-image"><?php echo digitallounge_get_the_post_thumbnail(); ?></a>
							<header class="entry-header">
								<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

								<div class="entry-meta">
									<?php digitallounge_posted_on(); ?>
								</div><!-- .entry-meta -->
							</header><!-- .entry-header -->

							<div class="entry-excerpt">
								<?php echo get_the_excerpt(); ?>
							</div><!-- .entry-excerpt -->
						</article><!-- #post-## -->

					<?php endwhile; ?>

				<?php else : ?>

					<?php get_template_part( 'template-parts/content', 'none' ); ?>

				<?php endif; ?>
				</div>
			</section><!-- .news-collection -->

			<?php
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
	</div><!-- #primary -->

<?php get_footer(); ?>