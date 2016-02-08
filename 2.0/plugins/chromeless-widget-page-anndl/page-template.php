<?php
/*
 * Template that renders a widget area without theme chrome.
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<title><?php bloginfo( 'title' ); ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />
	<?php 
	/* 
	 * Action in the <head> of the page template, like `wp_head`, and calling `wp_head` by default.
	 */
	do_action( 'chromeless_widgets_page_head' );
?>
</head>
<body <?php body_class(); ?>>
	<section class="widgets-container">
		<?php dynamic_sidebar( 'chromeless-widgets-page' ); ?>
	</section>
<?php
	$i = 0;
	$count = 0;
	while ( $i < 5 ) {
		if ( get_option( 'chromless_widgets_page_slide_' . $i, false ) ) : ?>
			<section class="slide" id="slide-<?php echo $i; ?>" style="display: none;">
				<img class="slide-image" src="<?php echo get_option( 'chromless_widgets_page_slide_' . $i ); ?>" />
			</section>
		<?php
			$count++;
		endif;
		$i++;
	}

	/* 
	 * Action just before </body> of page template, like `wp_footer` and calling `wp_footer` by default.
	 */
	do_action( 'chromeless_widgets_page_footer' ); 
?>
<script type="text/javascript">
	var slide = 0;
	setInterval( nextImg, 8000 ); // 8 Seconds
	function nextImg() {
		if ( slide > 0 ) {
			document.getElementById( 'slide-' + ( slide - 1 ) ).style.display = 'none';
		}
		if ( slide !== <?php echo $count; ?> ) {
			document.getElementById( 'slide-' + slide ).style.display = 'block';
		}
		
		slide = slide + 1;
		if ( slide > <?php echo $count; ?> ) { // Allow extra slide - the background, which is the events display.
			slide = 0;
		}
	}
</script>
</body>
</html>