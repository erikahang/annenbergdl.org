<?php
/* 
 * Courses custom post type.
 */

// Set up custom post type for courses.
add_action( 'init', 'anndl_courses_posts_register', 10 );
function anndl_courses_posts_register() {
	$labels = array(
		'name' => _x( 'Courses', 'post type general name', 'anndl-courses' ),
		'singular_name' => _x( 'Course', 'post type singular name', 'anndl-courses' ),
		'add_new' => __( 'Add New Course', 'anndl-courses' ),
		'add_new_item' => __( 'Add New Course', 'anndl-courses' ),
		'edit_item' => __( 'Edit Course', 'anndl-courses' ),
		'new_item' => __( 'New Course', 'anndl-courses' ),
		'view_item' => __( 'View Course', 'anndl-courses' ),
		'search_items' => __( 'Search Courses', 'anndl-courses' ),
		'not_found' =>  __( 'Nothing found', 'anndl-courses' ),
		'not_found_in_trash' => __( 'Nothing found in trash', 'anndl-courses' ),
	);

	$supports = array(
		'title',
		'author',
		'editor',
		'revisions',
	);

	/**
	 * Filter the slug and query_var parameters of the course post type.
	 *
	 * @since AnnDL Courses 0.0
	 */
	$slug = apply_filters( 'anndl_courses_slug', 'course' );

	$rewrite = array(
		'slug'       => $slug,
		'with_front' => false,
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'menu_position'      => 10,
		'menu_icon'          => 'dashicons-awards',
		'supports'           => $supports,
		'hierarchical'       => false,
		'has_archive'        => true,
		'rewrite'            => $rewrite,
		'query_var'          => $slug,
		'capability_type'    => 'post',
	);

	register_post_type( 'course' , $args );
}

// Add tools taxonomy from the tutorials plugin to courses.
function anndl_courses_add_tools_taxonomy() {
	register_taxonomy_for_object_type( 'tool', 'course' );
}
add_action( 'init', 'anndl_courses_add_tools_taxonomy', 20 ); // Needs to run after tools taxonomy is set up.

// Flush permalinks on activation.
register_activation_hook( __FILE__, 'anndl_courses_post_type_activation' );
function anndl_courses_post_type_activation() {
	anndl_courses_posts_register();
	flush_rewrite_rules();
}