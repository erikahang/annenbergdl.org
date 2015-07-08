<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digital Lounge
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<div class="news-title"><h2 class="section-title">News</h2> <a href="#">&gt;</a><a href="#">&lt;</a></div>
			<div class="news-collection animated slideInRight delay1-2sec paper-front">
				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" class="featured-image"><?php the_post_thumbnail(); ?></a>
							<header class="entry-header">
								<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

								<div class="entry-meta">
									<?php digitallounge_posted_on(); ?>
								</div><!-- .entry-meta -->
							</header><!-- .entry-header -->

							<div class="entry-excerpt">
								<?php the_excerpt(); ?>
							</div><!-- .entry-excerpt -->
						</article><!-- #post-## -->

					<?php endwhile; ?>

					<?php the_posts_navigation(); ?>

				<?php else : ?>

					<?php get_template_part( 'template-parts/content', 'none' ); ?>

				<?php endif; ?>
			</div><!-- .news-collection -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
