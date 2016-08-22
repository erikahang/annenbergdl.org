<?php
/**
 * The template for displaying a semester term, which contains courses offered and a registration form.
 *
 * @package Digital Lounge
 */

get_header(); ?>

	<section class="paper-front archive-header">
		<div class="archive-description-container">
			<h1 class="page-title"><?php
				global $wp_query;
				$term = $wp_query->get_queried_object();
				$title = $term->name;
				printf( __( 'Certification Courses for %s', 'anndl-courses' ), $title ); ?></h1>
			<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
		</div>
	</section>
	<div id="primary" class="content-area paper-front">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'course' ); ?>

		<?php endwhile; // end of the main loop. ?>
		</main><!-- #main -->
		<?php
		if ( is_user_logged_in() ) {
			// Show the course registration form.
			// @todo get the semester term being viewed.
			anndl_courses_registration_form( $term );
		}
		?>
	</div><!-- #primary -->

<?php get_footer(); ?>
