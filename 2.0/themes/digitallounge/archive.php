<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digital Lounge
 */

wp_enqueue_script( 'digitallounge-jquery-visible', get_template_directory_uri() . '/js/jquery.visible.js', array( 'jquery' ), '20151103', true );
wp_enqueue_script( 'digitallounge-infinitescroll', get_template_directory_uri() . '/js/infinite-scroll.js', array( 'jquery', 'digitallounge-jquery-visible', 'wp-util' ), '20151103', true );

// Build data to replicate this query in JS/infinite scroll
$data = array(
	'page' => 1,
);
if ( is_tax() ) {
	$data['type'] = 'taxonomy';
	$data['taxonomy'] = get_taxonomy( get_queried_object()->taxonomy )->name;
	$data['term'] = absint( get_queried_object()->term_id );
	$data['post_type'] = esc_attr( get_query_var( 'post_type' ) );
} elseif ( is_tag() ) {
	$data['type'] = 'taxonomy';
	$data['taxonomy'] = 'tag';
	$data['term'] = absint( get_queried_object()->term_id );
	$data['post_type'] = 'post';
} elseif ( is_category() ) {
	$data['type'] = 'taxonomy';
	$data['taxonomy'] = 'category';
	$data['term'] = absint( get_queried_object()->term_id );
	$data['post_type'] = 'post';
} elseif ( is_post_type_archive() ) {
	$data['type'] = 'post_type';
	$data['post_type'] = esc_attr( get_query_var( 'post_type' ) );
} elseif ( is_author() ) {
	$data['type'] = 'author';
	$data['author_id'] = absint( get_the_author_meta( 'ID' ) );
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
			<?php if ( is_tax() && 'tool' === get_taxonomy( get_queried_object()->taxonomy )->name ) : ?>
				<section class="paper-front archive-header">
					<img src="<?php echo get_term_meta( absint( get_queried_object()->term_id ), 'tool_icon', true ); ?>" class="tool-icon"/>
					<div class="archive-description-container">
						<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
						<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
						<div class="tool-experts">
							<?php $contributor_ids = get_users( array(
								'fields'  => 'ID',
								'who'     => '',
							) );
							$first = true;
							foreach ( $contributor_ids as $contributor_id ) {
								// Skip former staff members.
								if ( ! get_the_author_meta( 'active_staff', $contributor_id ) ) {
									continue;
								}
								if ( false === strpos( get_user_meta( $contributor_id, 'expertise', true ), get_queried_object()->slug ) ) {
									continue;
								}
								if ( $first ) {
									echo '<h4>Our Experts:</h4>';
									$first = false;
								} ?>
								<?php if ( count_user_posts( $contributor_id, 'tutorials' ) || count_user_posts( $contributor_id, 'course' ) || count_user_posts( $contributor_id ) ) { ?>
									<a href="<?php echo get_author_posts_url( $contributor_id ); ?>">
										<?php echo get_avatar( $contributor_id ); ?></a>
									</a>
								<?php } else {
									echo get_avatar( $contributor_id );
								}
							} ?>
						</div>
					</div>
				</section>
			<?php elseif ( is_author() ) :
				$data = get_userdata( absint( get_the_author_meta( 'ID' ) ) );
				$info = '';
				if ( $data->user_url ) {
					$info = 'Website: <a href="' . $data->user_url . '">' . $data->user_url . '</a> | ';
				}
				$expertise = get_user_meta( absint( get_the_author_meta( 'ID' ) ), 'expertise', true );
				if ( ! empty( $expertise ) ) {
					$info .= 'Expertise: ';
					$expertises = explode( ',', $expertise );
					foreach ( $expertises as $term ) {
						$info .= '<a href="' . get_term_link( $term, 'tool' ) . '">' . get_term_by( 'slug', $term, 'tool' )->name . '</a>, ';
					}
					$info = substr( $info, 0, -2 ); // Remove trailing comma & space.
				}
				?>
				<section class="paper-front archive-header">
					<?php echo get_avatar( absint( get_the_author_meta( 'ID' ) ), 384 ); ?>
					<div class="archive-description-container">
						<h1 class="page-title"><?php echo $data->display_name; ?></h1>
						<h5 class="staff-info"><?php echo $info; ?></h5>
						<div class="staff-bio">
							<?php echo $data->description; ?>
						</div>
					</div>
				</section>
			<?php else : ?>
				<header class="page-header">
					<?php
						the_archive_title( '<h1 class="section-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->
			<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<section class="query-container paper-front" <?php echo $alldata; ?>>
			<div class="inner-container">
			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" class="featured-image"><?php echo digitallounge_get_the_post_thumbnail(); ?></a>
					<?php if ( 'tutorials' === $post->post_type || 'course' === $post->post_type ) {
						$tool_id = absint( get_the_terms( $post->ID, 'tool' )[0]->term_id );
						if ( $tool_id ) {
							echo '<a href="' . get_term_link( $tool_id, 'tool' ) . '" class="tool-icon-link"><img src="' . get_term_meta( $tool_id, 'tool_icon', true ) . '" class="tool-icon"/>';
						}
					} ?>
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
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
