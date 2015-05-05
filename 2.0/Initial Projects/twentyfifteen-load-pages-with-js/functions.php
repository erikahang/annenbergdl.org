<?php


add_action( 'wp_enqueue_scripts', 'tfpages_enqueue' );
function tfpages_enqueue() {
	wp_enqueue_script( 'twentyfifteen-pages', get_stylesheet_directory_uri() . '/pages.js', array( 'jquery', 'wp-util' ), '20141212', true );
	wp_localize_script( 'twentyfifteen-pages', 'fifteenPagesData', array(
		'expand'   => '<span class="screen-reader-text">' . __( 'expand child menu', 'twentyfifteen' ) . '</span>',
		'collapse' => '<span class="screen-reader-text">' . __( 'collapse child menu', 'twentyfifteen' ) . '</span>',
	) );
}

add_action( 'wp_footer', 'tfpages_page_content_template' );
function tfpages_page_content_template() {
	?>
<script type="template/html" id="tmpl-page-content-template">
		<main id="main" class="site-main" role="main">

			<article id="post-{{ data.id }}" class="page type-page status-publish hentry">
				<!-- post thumbnail -->
				<header class="entry-header">
					<h1 class="entry-title">{{{ data.title }}}</h1>
				</header><!-- .entry-header -->

				<div class="entry-content">
					{{{ data.content }}}
				</div><!-- .entry-content -->

			</article><!-- #post-## -->

		</main><!-- .site-main -->

</script>
	<?php
}
	
add_filter( 'nav_menu_css_class' , 'tfpages_page_id_nav_class' , 10 , 2 );
function tfpages_page_id_nav_class( $classes, $item ) {
	if ( 'page' == $item->object ) {
		 $classes[] = 'page-id-' . $item->object_id;
	}
	return $classes;
}
?>