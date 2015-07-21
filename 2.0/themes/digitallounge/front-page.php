<?php
/**
 * The home page template.
 *
 * Includes the default home page and the customized home page views.
 *
 * @package Digital Lounge
 */


wp_enqueue_script( 'digitallounge-infinitescroll', get_template_directory_uri() . '/js/infinite-scroll.js', array( 'jquery', 'wp-util' ), '20150614', true );

get_header(); ?>

	<div id="primary" class="content-area paper-back">
		<main id="main" class="site-main" role="main">
		<section class="query-container news-collection animated slideInLeft delay1-2sec paper-front" data-type="post_type" data-post_type="post" data-page="1">
				<div class="news-title"><h2 class="section-title">News</h2> <div class="arrow-container"><button type="button" class="arrow-next">&lt;</button><button type="button" class="arrow-previous">&gt;</button></div></div>
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
								<?php the_excerpt(); ?>
							</div><!-- .entry-excerpt -->
						</article><!-- #post-## -->

					<?php endwhile; ?>

					<?php the_posts_navigation(); ?>

				<?php else : ?>

					<?php get_template_part( 'template-parts/content', 'none' ); ?>

				<?php endif; ?>
				</div>
			</section><!-- .news-collection -->
			<?php /* @todo - this is completely untested pseudo-code, to be implemented when custom homepages are built out
			if ( is_logged_in_user() && get_usermeta( current_user_id(), 'custom_homepage_terms' ) ) {
				$terms = get_usermeta( current_user_id(), 'custom_homepage_terms' );
			} else { */
			$terms = explode( ',', get_theme_mod( 'featured_tutorial_tags', '' ) );
			if ( $terms ) {
				foreach ( $terms as $term ) {
					$term = get_term_by( 'name', trim( $term ), 'tutorial_tag' );
					if ( $term ) {
						// Query for tutorials in this tag.
						$posts = get_posts( array(
							'numberposts'      => 4, // @todo probably 10 by default
							'suppress_filters' => false,
							'post_type'        => 'tutorials',
							'tax_query'        => array(
								array(
									'field'    => 'term_id',
									'taxonomy' => 'tutorial_tag',
									'terms'    => $term->term_id,
								),
							),
						) );
						if ( $posts ) { ?>

							<section class="<?php echo $term->slug; ?> query-container collection animated slideInRight delay1-2sec paper-front" data-type="taxonomy" data-taxonomy="tutorial_tag" data-term="<?php echo $term->term_id; ?>" data-post_type="tutorials" data-page="1">
								<div class="<?php echo $term->slug; ?> title"><h2 class="section-title"><?php echo $term->name; ?></h2> <div class="arrow-container"><button type="button" class="arrow-next">&lt;</button><button type="button" class="arrow-previous">&gt;</button></div></div>
								<div class="inner-container">
								<?php foreach( $posts as $post ) { ?>
									<?php setup_postdata( $post ); // Allows the_* functions to work without passing an ID. ?>
									<article class="collection-article" id="tutorial-<?php echo $post->ID; ?>" <?php post_class( null, $post->ID ); ?>>
										<a href="<?php the_permalink(); ?>" rel="bookmark" class="featured-image"><?php echo digitallounge_get_the_post_thumbnail(); ?></a>
										<header class="entry-header">
											<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
											<div class="entry-meta">
												<?php digitallounge_posted_on(); ?>
											</div><!-- .entry-meta -->
										</header><!-- .entry-header -->
										<div class="entry-excerpt">
											<?php the_excerpt(); ?>
										</div><!-- .entry-excerpt -->
									</article><!-- #tutorial-## -->
								<?php } ?>
								</div>
							</section>
							<?php
						}
					}
				}
			}
			?>
		</main><!-- #main -->
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
	</div><!-- #primary -->

<?php get_footer(); ?>