<?php
/**
 * Plugin Name: Courses
 * Plugin URI: http://annenbergdl.org/
 * Description: Course registration system built for the Annenberg Digital Lounge.
 * Version: 1.0
 * Author: Nick Halsey
 * Author URI: http://nick.halsey.co/
 * Tags: courses, registration, class
 * License: GPL
 * Text Domain: anndl-courses

=====================================================================================
Copyright (C) 2016 Nick Halsey

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WordPress; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
=====================================================================================
*/

// Register Custom Post Type.
require( plugin_dir_path( __FILE__ ) . '/post-type.php' );

// Set up taxonomies.
require( plugin_dir_path( __FILE__ ) . '/taxonomies.php' );

// Set up custom post meta.
require( plugin_dir_path( __FILE__ ) . '/admin/post-meta.php' );

// Ajax actions.
require( plugin_dir_path( __FILE__ ) . '/ajax-actions.php' );

// Load template functions.
require( plugin_dir_path( __FILE__ ) . '/template/template.php' );

// Load email functions.
require( plugin_dir_path( __FILE__ ) . '/email.php' );

// Load email options page.
require( plugin_dir_path( __FILE__ ) . '/admin/email-settings.php' );

// Load student lookup page.
require( plugin_dir_path( __FILE__ ) . '/admin/student-lookup.php' );


// Load Translations
add_action( 'plugins_loaded', 'anndl_courses_load_textdomain' );
function anndl_courses_load_textdomain() {
	load_plugin_textdomain( 'anndl-courses' );
}

