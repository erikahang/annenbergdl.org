<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<title><?php bloginfo( 'title' ); ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />
	<script>
	// Automatically refresh the inner page.
	function refresh( node, address ) {
		( function startRefresh() {
			node.src = address;
			setTimeout(startRefresh, 1000 * 60 * 10); // Ten minutes, in miliseconds.
		})();
	}

	window.onload = function() {
		var node = document.getElementById( 'inner' );
		refresh( node, "<?php echo site_url() . '/' . get_option( 'chromeless_widgets_page_slug', 'custom-full-page' ) . '-inner'; ?>" );
	}
	</script>
</head>
<body style="margin:0; padding:0; overflow: hidden;">
<iframe id="inner" style="width: 100%; height: 100vh; border:0; box-shadow:0; padding: 0; margin: 0;" seamless="seamless" src=""></iframe>
</body>