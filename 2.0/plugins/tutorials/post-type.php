<?php
/* 
 * Tutorials custom post type.
 */

//Set up custom post types for sheet music
add_action( 'init', 'tutorials_posts_register', 10 );
function tutorials_posts_register() {
	$labels = array(
		'name' => _x('Tutorials', 'post type general name'),
		'singular_name' => _x('Tutorial', 'post type singular name'),
		'add_new' => __('Add New Tutorial'),
		'add_new_item' => __('Add New Tutorial'),
		'edit_item' => __('Edit Tutorial'),
		'new_item' => __('New Tutorial'),
		'view_item' => __('View Turotial'),
		'search_items' => __('Search Tutorials'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
	);

	$supports = array(
		'title',
		'editor',
		'author',
		'thumbnail',
		'excerpt',
		'custom-fields',
		'comments',
		'revisions',
	);

	/**
	 * Filter the slug and query_var parameters of the tutorials post type.
	 *
	 * @since Sheet Music Library 0.0
	 */
	$slug = apply_filters( 'tutorials_slug', 'tutorials' );

	$rewrite = array(
		'slug'       => $slug,
		'with_front' => false,
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-welcome-learn-more',
		'supports'           => $supports,
		'hierarchical'       => false,
		'has_archive'        => true,
		'rewrite'            => $rewrite,
		'query_var'          => $slug,
		'capability_type'    => 'post',
	);

	register_post_type( 'tutorials' , $args );
}

// Flush permalinks on activation.
register_activation_hook( plugin_dir_path( __FILE__ ) . '/tutorials.php', 'tutorials_activation' );
function tutorials_activation() {
	tutorials_posts_register();
	flush_rewrite_rules();
}