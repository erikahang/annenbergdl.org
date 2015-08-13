<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Digital Lounge
 */


if ( ! function_exists( 'digitallounge_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function digitallounge_posted_on() {
	echo digitallounge_get_posted_on();
}

function digitallounge_get_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	if ( is_singular() ) {
		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);
	} else {
		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date( 'n/j/Y' ) ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date( 'n/j/Y' ) )
		);
	}

	$posted_on = sprintf(
		_x( '&#44;  %s', 'post date', 'digitallounge' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		_x( 'By %s', 'post author', 'digitallounge' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	return '<span class="byline"> ' . $byline . '</span><span class="posted-on">' . $posted_on . '</span>';

}
endif;

if ( ! function_exists( 'digitallounge_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function digitallounge_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'digitallounge' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'digitallounge' ) . '</span>', $tags_list );
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'digitallounge' ), __( '1 Comment', 'digitallounge' ), __( '% Comments', 'digitallounge' ) );
		echo '</span>';
	}

	edit_post_link( __( 'Edit', 'digitallounge' ), '<span class="edit-link"> ', '</span>' );
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function digitallounge_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'digitallounge_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'digitallounge_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so digitallounge_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so digitallounge_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in digitallounge_categorized_blog.
 */
function digitallounge_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'digitallounge_categories' );
}
add_action( 'edit_category', 'digitallounge_category_transient_flusher' );
add_action( 'save_post',     'digitallounge_category_transient_flusher' );


/**
 * Return the post thumbnail if it exists, or otherwise a fallback.
 *
 * @since Digital Lounge 1.0
 */
function digitallounge_get_the_post_thumbnail( $size = 'post-thumbnail' ) {
	if ( has_post_thumbnail() ) {
		return get_the_post_thumbnail( null, $size );
	} else {
		return '<img src="' . digitallounge_get_post_image( $size ) . '" alt="" class="">';
	}
}

/**
 * Get the featured image (post thumbnail) URL, if it exists, or otherwise,
 * look for another image in the post to use as the featured image.
 *
 * Based on lucidus_get_post_image(), from the Lucidus theme by Nick Halsey.
 * Based on cxnh_quickshare_get_post_image(), from the QuickShare plugin by Nick Halsey.
 *
 * @param string $size WordPress image size to get.
 * @return string $url URL of the post image.
 *
 * @since Digital Lounge 1.0
 */
function digitallounge_get_post_image( $size = 'post-thumbnail' ) {
	global $post;
	$imgdata = array();

	// If there's a featured image, use it.
	if ( has_post_thumbnail() ) {
		$imgdata = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size );
	} elseif ( is_attachment() ) {
		$imgdata = wp_get_attachment_image_src( $post->ID, $size ); // Attachment post type, so post id is attachment id.
	} else {
		// Next, try grabbing first attached image.
		$args = array(
			'numberposts' => 1,
			'post_parent' => $post->ID,
			'post_type' => 'attachment',
			'post_mime_type' => 'image'
		);
		$attachments = get_children( $args ); // Array is keyed by attachment id.
		if ( ! empty( $attachments ) ) {
			$rekeyed_array = array_values( $attachments );
			$imgdata = wp_get_attachment_image_src( $rekeyed_array[0]->ID , 'post-thumbnail' );
		} else {
			// Finally, look for the first img tag brute-force. Presumably if there's a caption or it's a gallery or anything it should have come up as an attachment.
			$result = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches ); // Find img tags, grab srcs.
			if ( $result > 0 ) {
				return $matches[1][0]; // Grab the first img src, no way to select size if we've gotten this deep.
			}
		}
	}

	if ( ! empty( $imgdata ) ) {
		return $imgdata;
	} else {
		// Use the default/fallback post image, if it exists.
		if ( 'tribe_events' === get_post_type() ) {
			return get_stylesheet_directory_uri() . '/img/calendar_default_image.svg';
		} else {
			return get_theme_mod( 'default_image', get_stylesheet_directory_uri() . '/img/missing.png' );
		}
	}
}
