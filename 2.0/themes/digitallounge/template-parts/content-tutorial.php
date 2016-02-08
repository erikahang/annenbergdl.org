<?php
/**
 * @package Digital Lounge
 */

global $post;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php echo digitallounge_get_the_post_thumbnail( 'digitallounge-full-width' ); ?>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-meta">
			<?php $tool_id = absint( get_the_terms( $post->ID, 'tool' )[0]->term_id ); ?>
			<a href="<?php echo get_term_link( $tool_id, 'tool' ) ?>">
				<img src="<?php echo get_term_meta( $tool_id, 'tool_icon', true ); ?>" class="tool-icon"/>
			</a>
			<span class="cat-links"><?php echo get_the_term_list( get_the_ID(), 'tool', '', ', ', '' ); ?></span>
		</div>
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
		<?php if ( get_previous_post( true, '', 'tutorial_tag' ) ) {
			$span = '<span>Previous tutorial in ' . wp_get_post_terms( $post->ID, 'tutorial_tag' )[0]->name . '</span>';
			previous_post_link( '%link', '&laquo; %title' . $span, true, '', 'tutorial_tag' );
		} ?>
	</div>
	<div class="next-post-link">
		<?php if ( get_next_post( true, '', 'tutorial_tag' ) ) {
			$span = '<span>Next tutorial in ' . wp_get_post_terms( $post->ID, 'tutorial_tag' )[0]->name . '</span>';
			next_post_link( '%link', '%title &raquo;' . $span, true, '', 'tutorial_tag' );
		} ?>
	</div>
</nav>
