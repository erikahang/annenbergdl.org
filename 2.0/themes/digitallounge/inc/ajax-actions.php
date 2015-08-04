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
	$info = '';
	if ( $data->user_url ) {
		$info = 'Website: <a href="' . $data->user_url . '">' . $data->user_url . '</a> | ';
	}
	$expertise = get_user_meta( $id, 'expertise', true );
	if ( ! empty( $expertise ) ) {
		$info .= 'Expertise: ';
		$expertises = explode( ',', $expertise );
		foreach ( $expertises as $term ) {
			$info .= '<a href="' . get_term_link( $term, 'tool' ) . '">' . get_term_by( 'slug', $term, 'tool' )->name . '</a>, ';
		}
		$info = substr( $info, 0, -2 ); // Remove trailing comma & space.
	}

	// Output the data for this user.
	echo wp_json_encode( array(
		'id' => $id,
		'avatar' => get_avatar( $id, 384 ),
		'background_image' => $data->background_image,
		'name' => $data->display_name,
		'info' => $info,
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
function anndl_load_archive_posts_ajax() {
	$page = absint( $_POST['page'] );

	$args = array(
		'numberposts'    => 6, // This MUST match what's initially rendered from the first pageload
		'offset'         => 6 * $page - 6,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post_type'      => array( 'tutorials', 'post' ),
	);

	$passed_args = $_POST['args'];
	switch ( $passed_args['type'] ) {
		case 'post_type' :
			$args['post_type'] = esc_html( $passed_args['post_type'] );
			break;
		case 'author' :
			$args['author'] = absint( $passed_args['author_id'] );
			break;
		case 'day' :
			$args['day'] = absint( $passed_args['day'] );
		case 'month' :
			$args['month'] = absint( $passed_args['month'] );
		case 'year' : 
			$args['year'] = absint( $passed_args['year'] );
			break;
		case 'taxonomy' :
			$args['post_type'] = esc_html( $passed_args['post_type'] );
			$args['tax_query'] = array(
				array(
					'field'    => 'term_id',
					'taxonomy' => esc_html( $passed_args['taxonomy'] ),
					'terms'    => esc_html( $passed_args['term'] ),
				),
			);
			break;
		case 'search' :
			$args = array(
				'numberposts' => 6, // This MUST match what's initially rendered from the first pageload
				'offset'      => 6 * $page - 6,
				'post_type'   => array( 'post', 'page', 'tutorials', 'tribe_events' ),
				's'           => $passed_args['searchterm'],
			);
			break;
	}

	$posts = get_posts( $args );
	$items = array();
	if ( is_wp_error( $posts ) ) {
		echo '-1';
	}

	foreach ( $posts as $post ) {
		$GLOBALS['post'] = $post;
		setup_postdata( $post );
		$items[] = array(
			'id'         => $post->ID,
			'title'      => html_entity_decode( get_the_title( $post ), ENT_HTML401 | ENT_QUOTES, get_bloginfo( 'charset' ) ),
			'posted_on'  => digitallounge_get_posted_on(),
			'permalink'  => get_the_permalink(),
			'post_thumbnail' => digitallounge_get_the_post_thumbnail(),
			'excerpt'     => get_the_excerpt(),
		);
	}
	
	// Output the data.
	echo wp_json_encode( $items );

	// @todo handle silent and non-silent failures, ex. when reaching the last post
	wp_die();
}
add_action( 'wp_ajax_anndl-load-archive-posts', 'anndl_load_archive_posts_ajax' );
add_action( 'wp_ajax_nopriv_anndl-load-archive-posts', 'anndl_load_archive_posts_ajax' );
