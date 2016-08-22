<?php
/**
 * Register taxonomies for use with the course post type.
 */

add_action( 'init', 'anndl_courses_register_taxonomies', 11 );
function anndl_courses_register_taxonomies() {
	register_taxonomy( 'semester', 'course', array(
		'hierarchical'      => true, 
		'labels'            => array(
			'name'                  => __( 'Semesters', 'anndl-courses' ),
			'singular_name'         => __( 'Semester', 'anndl-courses' ),
			'all_items'             => __( 'All Semesters', 'anndl-courses' ),
			'edit_item'             => __( 'Edit Semester', 'anndl-courses' ),
			'update_item'           => __( 'Update Semester', 'anndl-courses' ),
			'add_new_item'          => __( 'Add New Semester', 'anndl-courses' ),
			'new_item_name'         => __( 'New Semester Name', 'anndl-courses' ),
			'search_items'          => __( 'Search Semesters', 'anndl-courses' ),
			'popular_items'         => __( 'Popular Semesters', 'anndl-courses' ),
			'separate_items_with_commas' => __( 'Separate multiple semesters with commas', 'anndl-courses' ),
			'choose_from_most_used' => __( 'Choose from most-used semesters', 'anndl-courses'),
		),
		'rewrite'           => array(
			'slug'         => 'semester',
			'hierarchical' => true,
			'with_front'   => false,
		),
		'show_admin_column' => true,
	) );

}