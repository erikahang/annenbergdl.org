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
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'digitallounge' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'digitallounge' ) . '</span>', $tags_list );
		}
	}

	edit_post_link( __( 'Edit', 'digitallounge' ), '<p class="edit-link">', '</p>' );
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


/**
 * Custom home page featured content bands.
 */

/**
 * Create HTML list of nav menu items.
 *
 * @since 3.0.0
 * @uses Walker
 */
class Featured_Bands_Walker extends Walker_Nav_Menu {
	
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= '<div class="band-container">';
	}
	
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= '</div>';
	}

	/**
	 * Start the element output.
	 *
	 * @see Nav_Menu_Walker::start_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	 public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		if ( 'taxonomy' !== $item->type && 'post_type_archive' !== $item->type ) {
			return;
		}

		$term_id = $item->object_id;
		$taxonomy = $item->object;

		/**
		 * Filter the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$term = get_term( $term_id, $taxonomy );
		if ( $term || 'post_type_archive' === $item->type ) {
			if ( 'post_type_archive' === $item->type ) {
				$posts = get_posts( array (
					'numberposts'      => 8,
					'suppress_filters' => false,
					'post_type'        => $item->object,
				) );
			} else {
				// Query for tutorials in this tag.
				$posts = get_posts( array(
					'numberposts'      => 8,
					'suppress_filters' => false,
					'post_type'        => 'any',
					'tax_query'        => array(
						array(
							'field'    => 'term_id',
							'taxonomy' => $taxonomy,
							'terms'    => $term->term_id,
						),
					),
				) );
			}

			if ( $posts ) {
				global $post; 
				ob_start();
				?>

				<section id="<?php echo $id; ?>" class="<?php echo $term->slug; ?> query-container collection animated slideInRight delay1-2sec paper-front" data-type="taxonomy" data-taxonomy="<?php echo $taxonomy; ?>" data-term="<?php echo $term->term_id; ?>" data-post_type="any" data-page="1" data-visible_page="1" data-content_size="1782">
					<div class="<?php echo $term->slug; ?> title">
						<h2 class="section-title"><a href="<?php echo get_term_link( $term, $taxonomy ); ?>"><?php echo $title; ?> <span class="see-more-button">See More</span></a></h2>
						<div class="arrow-container"><img src="/wp-includes/images/spinner.gif" class="spinner"/><button type="button" class="arrow-previous animated fadeIn "></button><button type="button" class="arrow-next animated fadeIn "></button>
						</div>
					</div>
					<div class="inner-container">
					<?php foreach( $posts as $post ) { ?>
						<?php setup_postdata( $post ); // Allows the_* functions to work without passing an ID. ?>
						<article class="collection-article" id="tutorial-<?php echo $post->ID; ?>" <?php post_class( null, $post->ID ); ?>>
							<a href="<?php the_permalink(); ?>" rel="bookmark" class="featured-image"><?php echo digitallounge_get_the_post_thumbnail(); ?></a>
							<?php if ( 'tutorials' === $post->post_type || 'course' === $post->post_type ) {
								$tool_id = absint( get_the_terms( $post->ID, 'tool' )[0]->term_id );
								if ( $tool_id ) {
									echo '<a href="' . get_term_link( $tool_id, 'tool' ) . '" class="tool-icon-link"><img src="' . get_term_meta( $tool_id, 'tool_icon', true ) . '" class="tool-icon"/>';
								}
							} ?>
							<header class="entry-header">
								<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
								<div class="entry-meta">
									<?php digitallounge_posted_on(); ?>
								</div><!-- .entry-meta -->
							</header><!-- .entry-header -->
							<div class="entry-excerpt">
								<?php echo get_the_excerpt(); ?>
							</div><!-- .entry-excerpt -->
						</article><!-- #tutorial-## -->
					<?php } ?>
					</div>
				</section>
				<?php
				
				$output .= ob_get_clean();
			}
		}
	}

	/**
	 * Ends the element output.
	 *
	 * @see Walker::end_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Page data object. Not used.
	 * @param int    $depth  Depth of page. Not Used.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( 'taxonomy' !== $item->type ) {
			return;
		}
		$output .= "</section>\n";
	}
}

