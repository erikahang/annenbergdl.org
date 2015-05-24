<?php
/**
 * @package Digital Lounge
 */

global $post;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_post_thumbnail( 'digitallounge-full-width' ); ?>
	<header class="entry-header">
		<div class="entry-meta">
			<span class="cat-links"><?php echo get_the_term_list( get_the_ID(), 'tool', '', ', ', '' ); ?></span>
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
		<?php if ( get_previous_post( true, '', 'tutorial_tag' ) ) { ?>
			<?php previous_post_link( '&laquo; %link', '%title', true, '', 'tutorial_tag' ); ?>
			<span>Previous tutorial in <?php echo wp_get_post_terms( $post->ID, 'tutorial_tag' )[0]->name; ?></span>
		<?php } ?>
	</div>
	<div class="next-post-link">
		<?php if ( get_next_post( true, '', 'tutorial_tag' ) ) { ?>
			<?php next_post_link( '%link &raquo;', '%title', true, '', 'tutorial_tag' ); ?>
			<span>Next tutorial in <?php echo wp_get_post_terms( $post->ID, 'tutorial_tag' )[0]->name; ?></span>
		<?php } ?>
	</div>
</nav>
