<?php
define('CHILDTHEME_URL', get_bloginfo('stylesheet_directory'));
/*-----------------------------------------------------------------------------------*/
/* You can add custom functions below */
/*-----------------------------------------------------------------------------------*/

// This global does not need to be within a function.
// The content width defines the width of the main content editor, which is applied to the editor in the admin and used to size embeds (video, autio, etc.) appropriately. With respect to responsive/fluid widths, the standard/desktop max width for the content area should be used.
// We need to define it here since we're using something different from Twenty Fourteen's 434.
$content_width = 934;
add_action( 'after_setup_theme', 'uscann_after_setup_theme' );function uscann_after_setup_theme() {	add_editor_style();}
add_action('wp_head', 'insert_parent_css',5);
function insert_parent_css(){
?>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/style.css" media="all" />
<link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('stylesheet_directory'); ?>/custom.css" media="all" />
<?php
}

add_action( 'wp_enqueue_scripts', 'uscann_enqueue_scripts' );
function uscann_enqueue_scripts() {
	wp_enqueue_script( 'thermometer', get_stylesheet_directory_uri() . '/thermometer.js', array( 'jquery' ), '20150326', true );
	wp_enqueue_script( 'sidebar-scrolling', get_stylesheet_directory_uri() . '/sidebar-scroll.js', array( 'jquery' ), '20150313', true );
}

add_action( 'after_setup_theme', 'uscann_remove_header_image_support', 20 );
function uscann_remove_header_image_support() {
	remove_theme_support( 'custom-header' );
}

add_action( 'chromeless_widgets_page_head', 'uscann_full_display_page_auto_refresh' );
function uscann_full_display_page_auto_refresh() {
	wp_enqueue_script( 'autorefresher', get_stylesheet_directory_uri() . '/autorefresh.js', array( 'jquery' ), '20141031', true );
	// Auto-refresh every 30 minutes.
	?>
<!--	<meta http-equiv="refresh" content="1800"> -->
	<?php
}


?>