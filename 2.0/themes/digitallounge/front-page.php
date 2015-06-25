<?php
/**
 * The home page template.
 *
 * Includes the default home page and the customized home page views.
 *
 * @package Digital Lounge
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		
			<section class="news-collection animated slideInRight delay1-2sec">
				<div class="news-title"><h2 class="section-title">News</h2> <a href="#">&gt;</a><a href="#">&lt;</a></div>
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
							'numberposts'      => 12, // @todo ability to load more via ajax
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
							<section class="<?php echo $term->slug; ?> collection animated slideInRight delay1-2sec">
								<div class="<?php echo $term->slug; ?> title"><h2 class="section-title"><?php echo $term->name; ?></h2> <a href="#">&gt;</a><a href="#">&lt;</a></div>
								<?php foreach( $posts as $post ) { ?>
									<article class="colection-article" id="tutorial-<?php echo $post->ID; ?>" <?php post_class( null, $post->ID ); ?>>
										<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" rel="bookmark" class="featured-image"><?php echo get_the_post_thumbnail( $post->ID ); ?></a>
										<header class="entry-header">
											<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink( $post->ID ) ) ), '</a></h1>' ); ?>
										</header><!-- .entry-header -->
										<div class="entry-excerpt">
											<?php $content = str_replace( ']]>', ']]&gt;', wpautop( get_post( $post->ID )->post_content ) );
											echo substr( $content, 0, strpos( $content, '</p>' ) + 4 ) . '</p>';
											?>
										</div><!-- .entry-excerpt -->
									</article><!-- #tutorial-## -->
								<?php } ?>
							</section>
							<?php
						}
					}
				}
			}
			?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
