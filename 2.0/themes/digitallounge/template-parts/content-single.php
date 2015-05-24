<?php
/**
 * @package Digital Lounge
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_post_thumbnail( 'digitallounge-full-width' ); ?>
	<header class="entry-header">
		<div class="entry-meta">
			<span class="cat-links"><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'twentyfourteen' ) ); ?></span>
		</div>

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php digitallounge_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'digitallounge' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php digitallounge_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

<nav class="entry-nav">
	<div class="previous-post-link">
		<?php if ( get_previous_post() ) {
			$span = '<span>Previous post</span>';
			previous_post_link( '%link', '&laquo; %title' . $span );
		} ?>
	</div>
	<div class="next-post-link">
		<?php if ( get_next_post() ) {
			$span = '<span>Next post</span>';
			next_post_link( '%link', '%title &raquo;' . $span );
		} ?>
	</div>
</nav>
