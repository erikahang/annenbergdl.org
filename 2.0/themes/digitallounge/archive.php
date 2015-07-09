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
$data = array();
if ( is_tax() ) {
	$data['type'] = 'taxonomy';
	$data['taxonomy'] = absint( get_taxonomy( get_queried_object()->taxonomy )->name );
	$data['term'] = absint( get_queried_object()->term_id );
}if ( is_tag() ) {
	$data['type'] = 'taxonomy';
	$data['taxonomy'] = 'tag';
	$data['term'] = absint( get_queried_object()->term_id );
} elseif ( is_category() ) {
	$data['type'] = 'taxonomy';
	$data['taxonomy'] = 'category';
	$data['term'] = absint( get_queried_object()->term_id );
} elseif ( is_post_type_archive() ) {
	$data['type'] = 'post_type';
	$data['post_type'] = esc_attr( get_query_var( 'post_type' ) );
} elseif ( is_author() ) {
	$data['type'] = 'author';
	$data['author_id'] = absint( get_queried_object()->term_id );
} elseif ( is_year() ) {
	$data['type'] = 'year';
	$data['year'] = get_the_date( 'Y' );
} elseif ( is_month() ) {
	$data['type'] = 'month';
	$data['month'] = get_the_date( 'm' );
	$data['year'] = get_the_date( 'Y' );
} elseif ( is_day() ) {
	$data['type'] = 'year';
	$data['day'] = get_the_date( 'd' );
	$data['month'] = get_the_date( 'm' );
	$data['year'] = get_the_date( 'Y' );
}

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
				<?php
					the_archive_title( '<h1 class="section-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->

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
						<?php the_excerpt(); ?>
					</div><!-- .entry-excerpt -->
				</article><!-- #post-## -->

			<?php endwhile; ?>

			</section>
			<button class="load-more">Load More</button>
		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>
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
