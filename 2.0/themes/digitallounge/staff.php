<?php
/*
 * Template Name: Staff Page
 */

/**
 * The template for displaying the staff page.
 *
 * @package Digital Lounge
 */

wp_enqueue_script( 'digitallounge-staff', get_template_directory_uri() . '/js/staff.js', array( 'jquery', 'wp-util' ), '20150511', true );

get_header(); ?>

	<div id="primary" class="content-area staff-page">
		<main id="main" class="site-main" role="main">
		<h1 class="entry-title">Our Staff</h1>

	<?php 
	$contributor_ids = get_users( array(
		'fields'  => 'ID',
		'who'     => '',
	) );
	$active_contributor_ids = array();
	foreach ( $contributor_ids as $contributor_id ) :
		// Skip former staff members.
		if ( ! get_the_author_meta( 'active_staff', $contributor_id ) ) {
			continue;
		}

		$active_contributor_ids[] = $contributor_id;
		?>

		<div class="staff-summary" tabindex="0" data-staff-id="<?php echo $contributor_id; ?>">
			<div class="staff-avatar"><?php echo get_avatar( $contributor_id, 384 ); ?></div>
			<div class="staff-name"><?php the_author_meta( 'display_name', $contributor_id ); ?></div>
		</div>

	<?php
	endforeach;
?>
			
			<div class="staff-members"></div>
			<button type="button" class="back-to-index">X</button>
			<script type="text/html" id="tmpl-single-staff-view">
				<article class="staff-member" id="staff-member-{{ data.id }}">
					<div class="staff-background" style="background-image: url('{{ data.background_image }}');"></div>
					{{{ data.avatar }}}
					<div class="staff-content">
						<button type="button" class="previous"><</button>
						<h2 class="staff-name">{{ data.name }}</h2>
						<div class="staff-bio">
							{{{ data.description }}}
						</div>
						<button type="button" class="next">></button>
					</div>
				</article>
			</script>
			<script type="text/javascript">
				var _anndlAllStaff = <?php echo wp_json_encode( $active_contributor_ids ); ?>;
			</script>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
