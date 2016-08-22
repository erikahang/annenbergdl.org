<?php
/**
 * @package Digital Lounge
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php $tool_id = absint( get_the_terms( $post->ID, 'tool' )[0]->term_id ); ?>
		<a href="<?php echo get_term_link( $tool_id, 'tool' ) ?>">
			<img src="<?php echo get_term_meta( $tool_id, 'tool_icon', true ); ?>" class="tool-icon"/>
		</a>
		<h1 class="entry-title"><?php  echo get_the_title() . ', ' . get_post_meta( $post->ID, 'course-time', true ); ?></h1>
		<div class="entry-meta">
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
				<?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?>
			</a>
			<span class="author vcard byline"><?php printf(
				_x( 'With %s', 'course instructor', 'digitallounge' ),
				'<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
			);
			echo '<span class="registered byline">' . anndl_courses_registered_status( $post->ID ) . '</span>';
			?>
		</div>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
