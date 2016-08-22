<?php
/**
 * @package Digital Lounge
 */

global $post;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-meta">
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
				<?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?>
			</a>
			Day & Time, Semester, # registered
		</div>
		<div class="entry-meta">
			<?php digitallounge_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
<nav class="entry-nav">
	<div class="previous-post-link">
		<?php if ( get_previous_post( true, '', 'semester' ) ) {
			$span = '<span>Previous course in ' . wp_get_post_terms( $post->ID, 'semester' )[0]->name . '</span>';
			previous_post_link( '%link', '&laquo; %title' . $span, true, '', 'semester' );
		} ?>
	</div>
	<div class="next-post-link">
		<?php if ( get_next_post( true, '', 'semester' ) ) {
			$span = '<span>Next tutorial in ' . wp_get_post_terms( $post->ID, 'semester' )[0]->name . '</span>';
			next_post_link( '%link', '%title &raquo;' . $span, true, '', 'semester' );
		} ?>
	</div>
</nav>
