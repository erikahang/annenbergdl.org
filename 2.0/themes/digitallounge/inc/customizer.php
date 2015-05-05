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

}
add_action( 'customize_register', 'digitallounge_customize_register' );

