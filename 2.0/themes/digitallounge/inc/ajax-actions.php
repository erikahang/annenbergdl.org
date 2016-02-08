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
	$args = array(
		'author' => $id,
		'numberposts' => 1,
		'orderby' => 'date',
		'order' => 'DESC',
		'post_type' => 'post',
	);
	$post_query = get_posts( $args );
	$post_query ? $has_posts = true : $has_posts = false;
	$posts = array();
	if ( $has_posts ) {
		foreach( $post_query as $post ) {
			if ( 'post' !== $post->post_type ) {
				//continue;
			}
			if ( 'private' === get_post_status( $post->ID ) ) {
				continue;
			}
			$posts[] = array(
				'id' => $post->ID,
				'title' => $post->post_title,
				'date' => get_the_date( 'n/j/Y', $post->ID ),
				'url' => get_permalink( $post->ID ),
			);
		}
	}

	$args['numberposts'] = 3;
	$args['post_type'] = 'tutorials';
	$tutorials_query = get_posts( $args );
	$tutorials_query ? $has_tutorials = true : $has_tutorials = false;
	$tutorials = array();
	if ( $has_tutorials ) {
		foreach( $tutorials_query as $tutorial ) {
			if ( 'private' === get_post_status( $tutorial->ID ) ) {
				continue;
			}
			$terms = get_the_terms( $tutorial->ID, 'tutorial_tag' );
			if ( ! is_wp_error( $terms ) ) {
				$tags = 'in <a href="' . get_term_link( $terms[0]->term_id, 'tutorial_tag' ) . '">' . $terms[0]->name . '</a>';				
			} else {
				$tags = '';
			}
			$tutorials[] = array(
				'id' => $tutorial->ID,
				'title' => $tutorial->post_title,
				'tags' => $tags,
				'url' => get_permalink( $tutorial->ID ),
			);
		}
	}

	// Output the data for this user.
	echo wp_json_encode( array(
		'id' => $id,
		'avatar' => get_avatar( $id, 384 ),
		'background_image' => $data->background_image,
		'name' => $data->display_name,
		'info' => $info,
		'description' => $data->description,
		'hasPosts' => $has_posts,
		'hasTutorials' => $has_tutorials,
		'posts' => $posts,
		'tutorials' => $tutorials,
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
		if ( 'private' === get_post_status( $post->ID ) ) {
			continue;
		}
		$GLOBALS['post'] = $post;
		setup_postdata( $post );
		$items[$post->ID] = array(
			'id'         => $post->ID,
			'title'      => html_entity_decode( get_the_title( $post ), ENT_HTML401 | ENT_QUOTES, get_bloginfo( 'charset' ) ),
			'posted_on'  => digitallounge_get_posted_on(),
			'permalink'  => get_the_permalink(),
			'post_thumbnail' => digitallounge_get_the_post_thumbnail(),
			'excerpt'     => get_the_excerpt(),
			'icon'        => false,
		);
		if ( 'tutorials' === $post->post_type ) {
			$tool_id = absint( get_the_terms( $post->ID, 'tool' )[0]->term_id );
			$items[$post->ID]['icon'] = '<a href="' . get_term_link( $tool_id, 'tool' ) . '"><img src="' . get_term_meta( $tool_id, 'tool_icon', true ) . '" class="tool-icon"/>';
		}
	}

	// Output the data.
	echo wp_json_encode( $items );

	// @todo handle silent and non-silent failures, ex. when reaching the last post
	wp_die();
}
add_action( 'wp_ajax_anndl-load-archive-posts', 'anndl_load_archive_posts_ajax' );
add_action( 'wp_ajax_nopriv_anndl-load-archive-posts', 'anndl_load_archive_posts_ajax' );
