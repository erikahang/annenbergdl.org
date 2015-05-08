<?php
/*
 * Template Name: Staff Page
 */

/**
 * The template for displaying the staff page.
 *
 * @package Digital Lounge
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

	<?php 
	$contributor_ids = get_users( array(
		'fields'  => 'ID',
		'who'     => '',
	) );
	foreach ( $contributor_ids as $contributor_id ) :
		// Skip former staff members.
		if ( ! get_the_author_meta( 'active_staff', $contributor_id ) ) {
			continue;
		}
		?>

		<div class="staff-summary" tabindex="0">
			<div class="staff-avatar"><?php echo get_avatar( $contributor_id, 384 ); ?></div>
			<div class="staff-name"><?php the_author_meta( 'display_name', $contributor_id ); ?></div>
		</div>

	<?php
	endforeach;
?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
