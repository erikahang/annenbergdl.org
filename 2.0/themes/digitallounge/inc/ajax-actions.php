<?php
/**
 * Ajax handler for loading user data to the staff page.
 *
 */
function anndl_load_userdata_ajax() {
	$id = absint( $_POST['user_id'] );

	$data = get_userdata( $id );

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
