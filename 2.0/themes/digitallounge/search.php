<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digital Lounge
 */

wp_enqueue_script( 'digitallounge-infinitescroll', get_template_directory_uri() . '/js/infinite-scroll.js', array( 'jquery', 'wp-util' ), '20150614', true );

// Build data to replicate this query in JS/infinite scroll
$data = array(
	'page' => 1,
	'type' => 'search',
	'searchterm' => get_search_query(),
);

$alldata = '';
foreach ( $data as $attr => $value ) {
	$alldata .= 'data-' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '" ';
}

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main" data-type="tutorials">

		<?php if ( have_posts() ) : ?>
			<section class="query-container" <?php echo $alldata; ?>>
			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'digitallounge' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->
				<div class="inner-container">

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

				</div><!-- .inner-container -->
				<button class="load-more">Load More</button>
			</section>
		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>
		<script type="text/html" id="tmpl-archive-post">
			<article id="post-{{ data.id }}" class="post">
				<a href="{{ data.permalink }}" rel="bookmark" class="featured-image">{{{ data.post_thumbnail }}}</a>
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
		<script type="text/html" id="tmpl-archive-tutorial">
			<article id="post-{{ data.id }}" class="tutorials">
				<a href="{{ data.permalink }}" rel="bookmark" class="featured-image">{{{ data.post_thumbnail }}}</a>
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
		<script type="text/html" id="tmpl-archive-grid-view">
			<article id="post-{{ data.id }}" <?php post_class(); ?>>
				<a href="{{ data.permalink }}" rel="bookmark" class="featured-image">{{{ data.post_thumbnail }}}</a>
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
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
