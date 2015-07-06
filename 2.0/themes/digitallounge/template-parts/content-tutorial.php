<?php
/**
 * @package Digital Lounge
 */

global $post;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) {
	echo the_post_thumbnail( 'digitallounge-full-width' ); 
	} else{ ?>
<img src="<?php bloginfo('template_directory'); ?>/img/missing.png" alt="<?php the_title(); ?>" />
<?php } ?>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-meta">
			<span class="cat-links"><?php echo get_the_term_list( get_the_ID(), 'tool', '', ', ', '' ); ?></span> w
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
