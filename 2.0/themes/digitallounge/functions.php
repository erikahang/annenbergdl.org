<?php
/**
 * Digital Lounge functions and definitions
 *
 * @package Digital Lounge
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 934; /* pixels */
}

if ( ! function_exists( 'digitallounge_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function digitallounge_setup() {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts, tutorials, and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails', array( 'post', 'tutorials', 'page', 'tribe_events' ) );
	set_post_thumbnail_size( 576, 384, true );
	add_image_size( 'digitallounge-full-width', 934, 623, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'footer' => __( 'Footer Menu', 'digitallounge' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	// Add styles to the visual editor from editor-style.css.
	add_editor_style();
}
endif; // digitallounge_setup
add_action( 'after_setup_theme', 'digitallounge_setup' );


/**
 * Enqueue scripts and styles.
 */
function digitallounge_scripts() {
	wp_enqueue_style( 'digitallounge-style', get_stylesheet_uri() );
	

	wp_enqueue_script( 'digitallounge-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'digitallounge_scripts' );

/**
 * Customize the excerpt display.
 */
function digitallounge_excerpt_more( $more ) {
	global $post;
	return '&hellip;';
}
add_filter( 'excerpt_more', 'digitallounge_excerpt_more' );

function digitallounge_excerpt_length( $length ) {
	return 55;
}
add_filter( 'excerpt_length', 'digitallounge_excerpt_length' );

/**
 * Use a custom excerpt-trimming function, based on wp_trim_excerpt, to allow some html.
 */
function digitallounge_wp_trim_excerpt( $text ) {
	$raw_excerpt = $text;
	if ( '' == $text ) {
		$text = get_the_content( '' );

		$text = strip_shortcodes( $text );

		/** This filter is documented in wp-includes/post-template.php */
		$text = apply_filters( 'the_content', $text );
		$text = str_replace( ']]>', ']]&gt;', $text );

		/**
		 * Filter the number of words in an excerpt.
		 *
		 * @since 2.7.0
		 *
		 * @param int $number The number of words. Default 55.
		 */
		$excerpt_length = apply_filters( 'excerpt_length', 18 );

		/**
		 * Filter the string in the "more" link displayed after a trimmed excerpt.
		 *
		 * @since 2.9.0
		 *
		 * @param string $more_string The string shown within the more link.
		 */
		$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );

		$original_text = $text;
		$text = strip_tags( $text, '<a><b><strong><i><em>' );
		/* translators: If your word count is based on single characters (East Asian characters),
		   enter 'characters'. Otherwise, enter 'words'. Do not translate into your own language. */
		if ( 'characters' == _x( 'words', 'word count: words or characters?' ) && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
			$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
			preg_match_all( '/./u', $text, $words_array );
			$words_array = array_slice( $words_array[0], 0, $excerpt_length + 1 );
			$sep = '';
		} else {
			$words_array = preg_split( "/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY );
			$sep = ' ';
		}
		if ( count( $words_array ) > $excerpt_length ) {
			array_pop( $words_array );
			$text = implode( $sep, $words_array );
			$text = $text . $excerpt_more;
		} else {
			$text = implode( $sep, $words_array );
		}
		/**
		 * Filter the text content after words have been trimmed.
		 *
		 * @since 3.3.0
		 *
		 * @param string $text           The trimmed text.
		 * @param int    $excerpt_length The number of words to trim the text to. Default 5.
		 * @param string $more           An optional string to append to the end of the trimmed text, e.g. &hellip;.
		 * @param string $original_text  The text before it was trimmed.
		 */
		$text = apply_filters( 'wp_trim_words', $text, $excerpt_length, $excerpt_more, $original_text );
	}
	return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}
remove_filter( 'get_the_excerpt', 'wp_trim_excerpt' );
add_filter( 'get_the_excerpt', 'digitallounge_wp_trim_excerpt' );


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * AJAX Actions.
 */
require get_template_directory() . '/inc/ajax-actions.php';


