<?php
/**
 * Ajax handler for loading user data to the staff page.
 *
 */
function anndl_load_userdata_ajax() {
	$id = absint( $_POST['user_id'] );

	$data = get_userdata( $id );
	if ( ! $data->background_image ) {
		$data->background_image = get_theme_mod( 'default_staff_background', '' );
	}

	// Output the data for this user.
	echo wp_json_encode( array(
		'id' => $id,
		'avatar' => get_avatar( $id, 384 ),
		'background_image' => $data->background_image,
		'name' => $data->display_name,
		'description' => $data->description,
	) );

	wp_die();
}
add_action( 'wp_ajax_anndl-load-userdata', 'anndl_load_userdata_ajax' );
add_action( 'wp_ajax_nopriv_anndl-load-userdata', 'anndl_load_userdata_ajax' );

/**
 * Ajax handler for loading posts on archive pages.
 *
 */
function anndl_load_archive_pages_ajax() {
	$page = absint( $_POST['page'] );

	$args = $_POST['args'];

	$args = array(
		'numberposts'    => 10,
		'offset'         => 10 * $page,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post_type'      => 'tutorials',
	);
	$posts = get_posts( $args );
	$items = array();
	foreach ( $posts as $post ) {
		$items[] = array(
			'id'         => $post->ID,
			'title'      => html_entity_decode( get_the_title( $post ), ENT_HTML401 | ENT_QUOTES, get_bloginfo( 'charset' ) ),
			'permalink'  => get_the_permalink( $post->ID ),
			'post_thumbnail' => get_the_post_thumbnail( $post->ID ),
			'excerpt'     => get_the_excerpt( $post->ID ),
		);
	}
	
	// Output the data.
	echo wp_json_encode( $items );

	wp_die();
}
add_action( 'wp_ajax_anndl-load-archive-pages', 'anndl_load_archive_pages_ajax' );
add_action( 'wp_ajax_nopriv_anndl-load-archive-pages', 'anndl_load_archive_pages_ajax' );
