<?php

// Add Customizer settings and controls.
add_action( 'customize_register', 'chromeless_widgets_page_customize_register' );
function chromeless_widgets_page_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'chromeless_widgets_page', array(
		'title'    => __( 'Chromeless Widgets Page' ),
	) );

	// Slides.
	$i = 0;
	while( $i < 5 ) {
		$wp_customize->add_setting( 'chromless_widgets_page_slide_' . $i, array(
			'sanitize_callback' => 'esc_url',
			'type' => 'option',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'chromless_widgets_page_slide_' . $i, array(
			'label' => 'Slide Image #' . ($i + 1),
			'section' => 'chromeless_widgets_page',
		) ) );
		$i++;
	}

	// Page slug option.
	$wp_customize->add_setting( 'chromeless_widgets_page_slug', array(
		'default'           => 'custom-full-page',
		'type'              => 'option',
		'capability'        => 'manage_options',
		'sanitize_callback' => 'sanitize_title', // This function is designed for satitizing a post slug.
	) );

	$wp_customize->add_control( 'chromeless_widgets_page_slug', array(
		'type'        => 'text',
		'label'       => 'Page Slug',
		'description' => __( 'This is the url extension where the page will be located. If it matches a page that has actual content, that content will no longer be accessible.' ),
		'section'     => 'chromeless_widgets_page',
	) );

	// CSS
	$wp_customize->add_setting( 'chromeless_widgets_page_css', array( 
		'type'      => 'option',
		'capablity' => 'manage_options',
		'transport' => 'postMessage',
	) );

	$wp_customize->add_control( 'chromeless_widgets_page_css', array(
		'type'        => 'textarea',
		'label'       => 'Page CSS',
		'description' => 'Add styling rules here for quick customization.',
		'section'     => 'chromeless_widgets_page',
	) );
}

// CSS Output.
add_action( 'chromeless_widgets_page_head', 'chromeless_widgets_page_css' );
function chromeless_widgets_page_css() {
	$css = get_option( 'chromeless_widgets_page_css', '' );
	echo '<style type="text/css" id="chromeless_widgets_page_css">';
	echo $css;
	echo '</style>';
}

// Enqueue live-previewing script.
add_action( 'customize_preview_init', 'chromeless_widgets_page_customize_preview_scripts' );
function chromeless_widgets_page_customize_preview_scripts() {
	wp_enqueue_script( 'chromeless_widgets_page_customize_preview', plugin_dir_url( __FILE__ ) . 'customize-preview.js', array( 'customize-preview' ) );
}
