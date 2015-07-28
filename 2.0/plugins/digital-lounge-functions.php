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