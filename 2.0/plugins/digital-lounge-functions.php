<?php
/**
 * Plugin Name: Digital Lounge Functions
 * Description: Custom functionality for the Digital Lounge site.
 * Author: Nick Halsey
 */

/**
 * Rename "Posts" to "News"
 */
function digitallounge_change_post_label() {
	global $menu;
	global $submenu;
	$menu[5][0] = 'News';
	$submenu['edit.php'][5][0] = 'News';
	$submenu['edit.php'][10][0] = 'Add News';
	$submenu['edit.php'][16][0] = 'News Tags';
	echo '';
}

function digitallounge_change_post_object() {
	global $wp_post_types;
	$labels = &$wp_post_types['post']->labels;
	$labels->name = 'News';
	$labels->singular_name = 'News';
	$labels->add_new = 'Add News';
	$labels->add_new_item = 'Add News';
	$labels->edit_item = 'Edit News';
	$labels->new_item = 'News';
	$labels->view_item = 'View News';
	$labels->search_items = 'Search News';
	$labels->not_found = 'No News found';
	$labels->not_found_in_trash = 'No News found in Trash';
	$labels->all_items = 'All News';
	$labels->menu_name = 'News';
	$labels->name_admin_bar = 'News';
}

add_action( 'admin_menu', 'digitallounge_change_post_label' );
add_action( 'init', 'digitallounge_change_post_object' );


/**
 * Show additional post types on archives.
 */
function digitallounge_add_custom_types_to_archives( $query ) {
	if ( $query->is_author() && $query->is_main_query() ) {
		$query->set( 'post_type', array(
			'post', 'tutorials', 'course',
		));
	} elseif ( $query->is_search() && empty( $query->query_vars['supress_filters'] ) ) {
		$query->set( 'post_type', array(
			'post', 'page', 'tutorials', 'course', 'tribe_events'
		));
	}
	return $query;
}
add_filter( 'pre_get_posts', 'digitallounge_add_custom_types_to_archives' );


/**
 * Tweak user roles' capability assignments.
 *
 * Note that the plugin must be de/re-activated for this code to run, as the results are persisted in the database.
 */
function digitallounge_activate_adjust_roles() {
	// Editors.
	$editor = get_role( 'editor' );
	$editor->add_cap( 'list_users' ); // Allow editors to manage users.
	$editor->add_cap( 'edit_users' ); // Warning: editors can now promote themselves or others to administrators.
	$editor->add_cap( 'create_users' ); // Editors *must* be trusted like administrators in this environment.
	$editor->add_cap( 'promote_users' );
	$editor->add_cap( 'remove_users' );
	$editor->add_cap( 'delete_users' );
	$editor->add_cap( 'manage_options' ); // Allow editors to manage options and theme options.
	$editor->add_cap( 'edit_theme_options' ); // This is most relevant in their ability to make non-breaking changes via the Customizer.

	// Contributor.
	$contributor = get_role( 'contributor' );
	$contributor->add_cap( 'upload_files' ); // Allows media to be uploaded (which technically allows them to publish attachment posts).
}
register_activation_hook( __FILE__, 'digitallounge_activate_adjust_roles' );

function digitallounge_google_analytics_tracking_code() {
?>
	<script type="text/javascript">
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','__gaTracker');

		__gaTracker('create', 'UA-54263385-1', 'auto');
		__gaTracker('set', 'forceSSL', true);
		__gaTracker('require', 'displayfeatures');
		__gaTracker('send','pageview');
	</script>
<?php
}
add_action( 'wp_head', 'digitallounge_google_analytics_tracking_code' );

/**
 * Implement reuired publishing checklist items.
 *
 */
function digitallounge_publishing_checklist() {
	// Featured images.
	$args = array(
		'label'           => esc_html__( 'Featured Image', 'digitallounge' ),
		'callback'        => function ( $post_id, $id ) {
			return has_post_thumbnail( $post_id );
		},
		'explanation'     => esc_html__( 'A featured image is required.', 'digitallounge' ),
		'post_type'       => array( 'post', 'tutorials' ),
	);
	Publishing_Checklist()->register_task( 'digitallounge-featured-image', $args );

	// Post excertps for posts (news).
	$args = array(
		'label'           => esc_html__( 'Post Excerpt', 'digitallounge' ),
		'callback'        => function ( $post_id, $id ) {
			$obj = get_post( $post_id );
			if ( ! $obj ) {
				return false;
			}
			return ( '' !== $obj->post_excerpt );
		},
		'explanation'     => esc_html__( 'Posts should have hand-crafted excerpts for summary views, rather than displaying the first part of the post contents.', 'digitallounge' ),
		'post_type'       => array( 'post' ),
	);
	Publishing_Checklist()->register_task( 'digitallounge-has-post-excerpt', $args );

	// Post excerpts for tutorials.
	$args = array(
		'label'           => esc_html__( 'Tutorial Excerpt', 'digitallounge' ),
		'callback'        => function ( $post_id, $id ) {
			$obj = get_post( $post_id );
			if ( ! $obj ) {
				return false;
			}
			return ( '' !== $obj->post_excerpt );
		},
		'explanation'     => esc_html__( 'Tutorials should have hand-crafted excerpts for summary views, rather than displaying the first part of the post contents.', 'digitallounge' ),
		'post_type'       => array( 'tutorials' ),
	);
	Publishing_Checklist()->register_task( 'digitallounge-has-tutorial-excerpt', $args );

	// Tags for posts (news).
	$args = array(
		'label'           => esc_html__( 'Tags', 'digitallounge' ),
		'callback'        => function ( $post_id, $id ) {
			$obj = wp_get_post_terms( $post_id, 'post_tag', 'ids' );
			return ( ! empty( $obj ) );
		},
		'explanation'     => esc_html__( 'Every news post should have at least one tag.', 'digitallounge' ),
		'post_type'       => array( 'post' ),
	);
	Publishing_Checklist()->register_task( 'digitallounge-has-post-tag', $args );

	// Categories for posts (news).
	$args = array(
		'label'           => esc_html__( 'Category', 'digitallounge' ),
		'callback'        => function ( $post_id, $id ) {
			$obj = wp_get_post_terms( $post_id, 'category', 'names' );
			return ( ! empty( $obj ) && 'uncategorized' !== $obj[0] );
		},
		'explanation'     => esc_html__( 'Every news post must be in a category, and the "Uncategorized" category should not be used.', 'digitallounge' ),
		'post_type'       => array( 'post' ),
	);
	Publishing_Checklist()->register_task( 'digitallounge-has-category', $args );

	// Tags for tutorials.
	$args = array(
		'label'           => esc_html__( 'Tags', 'digitallounge' ),
		'callback'        => function ( $post_id, $id ) {
			$obj = wp_get_post_terms( $post_id, 'tutorial_tag', 'ids' );
			return ( ! empty( $obj ) );
		},
		'explanation'     => esc_html__( 'Every tutorial should have at least one tag.', 'digitallounge' ),
		'post_type'       => array( 'tutorials' ),
	);
	Publishing_Checklist()->register_task( 'digitallounge-has-tutorials-tag', $args );

	// Tutorial tools.
	$args = array(
		'label'           => esc_html__( 'Tools', 'digitallounge' ),
		'callback'        => function ( $post_id, $id ) {
			$obj = wp_get_post_terms( $post_id, 'tool', 'ids' );
			return ( ! empty( $obj ) );
		},
		'explanation'     => esc_html__( 'The Tool that this tutorial is about needs to be specified.', 'digitallounge' ),
		'post_type'       => array( 'tutorials' ),
	);
	Publishing_Checklist()->register_task( 'digitallounge-has-tutorials-tool', $args );

	// Tutorial difficulty.
	$args = array(
		'label'           => esc_html__( 'Difficulty', 'digitallounge' ),
		'callback'        => function ( $post_id, $id ) {
			$obj = wp_get_post_terms( $post_id, 'difficulty', 'ids' );
			return ( ! empty( $obj ) );
		},
		'explanation'     => esc_html__( 'Please set the difficulty level of this tutorial.', 'digitallounge' ),
		'post_type'       => array( 'tutorials' ),
	);
	Publishing_Checklist()->register_task( 'digitallounge-has-tutorials-difficulty', $args );
}
add_action( 'publishing_checklist_init', 'digitallounge_publishing_checklist' );


