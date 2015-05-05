<?php
/**
 * Register taxonomies for use with the tutorials post type.
 */

add_action( 'init', 'tutorials_register_taxonomies', 11 );
function tutorials_register_taxonomies() {
	register_taxonomy( 'tutorial_tag', 'tutorials', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                  => __( 'Tags' ),
			'singular_name'         => __( 'Tag' ),
			'all_items'             => __( 'All Tags' ),
			'edit_item'             => __( 'Edit Tag' ),
			'update_item'           => __( 'Update Tag' ),
			'add_new_item'          => __( 'Add New Tag' ),
			'new_item_name'         => __( 'New Tag Name' ),
			'parent_item'           => __( 'Parent Tag' ),
			'parent_item_colon'     => __( 'Parent Tag:' ),
			'search_items'          => __( 'Search Tags' ),
			'popular_items'         => __( 'Popular Tags' ),
			'choose_from_most_used' => __( 'Choose from most-used tags' ),
		),
		'rewrite'           => array(
			'slug'         => 'tag',
			'hierarchical' => false,
			'with_front'   => false,
		),
		'show_admin_column' => true,
	) );

	register_taxonomy( 'tool', 'tutorials', array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'                  => __( 'Tools' ),
			'singular_name'         => __( 'Tool' ),
			'all_items'             => __( 'All Tools' ),
			'edit_item'             => __( 'Edit Tool' ),
			'update_item'           => __( 'Update Tool' ),
			'add_new_item'          => __( 'Add New Tool' ),
			'new_item_name'         => __( 'New Tool Name' ),
			'parent_item'           => __( 'Parent Tool' ),
			'parent_item_colon'     => __( 'Parent Tool:' ),
			'search_items'          => __( 'Search Tools' ),
			'popular_items'         => __( 'Popular Tools' ),
			'choose_from_most_used' => __( 'Choose from most-used tools' ),
		),
		'rewrite'           => array(
			'slug'         => 'tool',
			'hierarchical' => true,
			'with_front'   => false,
		),
		'show_admin_column' => true,
	) );

	register_taxonomy( 'difficulty', 'tutorials', array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'                  => __( 'Difficulties' ),
			'singular_name'         => __( 'Difficulty' ),
			'all_items'             => __( 'All Difficulties' ),
			'edit_item'             => __( 'Edit Difficulty' ),
			'update_item'           => __( 'Update Difficulty' ),
			'add_new_item'          => __( 'Add New Difficulty' ),
			'new_item_name'         => __( 'New Difficulty Name' ),
			'parent_item'           => __( 'Parent Difficulty' ),
			'parent_item_colon'     => __( 'Parent Difficulty:' ),
			'search_items'          => __( 'Search Difficulties' ),
			'popular_items'         => __( 'Popular Difficulties' ),
			'choose_from_most_used' => __( 'Choose from most-used difficulties' ),
		),
		'rewrite'           => array(
			'slug'         => 'difficulty',
			'hierarchical' => true,
			'with_front'   => false,
		),
		'show_admin_column' => true,
	) );
}