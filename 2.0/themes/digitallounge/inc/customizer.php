<?php
/**
 * Digital Lounge Theme Customizer
 *
 * @package Digital Lounge
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function digitallounge_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'footer', array(
		'title' => 'Footer',
		'priority' => 30,
	) );
	
	$wp_customize->add_setting( 'footer_text', array(
		'sanitize_callback' => '',
	) );
	
	$wp_customize->add_control( 'footer_text', array(
		'label' => 'Footer text',
		'type' => 'textarea',
		'section' => 'footer',
	) );
}
add_action( 'customize_register', 'digitallounge_customize_register' );

