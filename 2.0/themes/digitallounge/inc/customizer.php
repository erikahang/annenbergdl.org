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
		'priority' => 140,
	) );
	
	$wp_customize->add_setting( 'footer_text', array(
		'sanitize_callback' => '',
	) );
	
	$wp_customize->add_control( 'footer_text', array(
		'label' => 'Footer text',
		'type' => 'textarea',
		'section' => 'footer',
	) );

	// Default images.
	$wp_customize->add_section( 'images', array( 
		'title' => 'Images',
		'priority' => 135,
	) );
	$wp_customize->add_setting( 'default_staff_background', array(
		'sanitize_callback' => 'esc_url',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'default_staff_background', array(
		'label' => 'Default Staff Background Image',
		'section' => 'images',
	) ) );
	$wp_customize->add_setting( 'default_image', array(
		'sanitize_callback' => 'esc_url',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'default_image', array(
		'label' => 'Default Featured Image',
		'section' => 'images',
	) ) );
}
add_action( 'customize_register', 'digitallounge_customize_register' );
