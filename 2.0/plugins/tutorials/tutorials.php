<?php
/**
 * Plugin Name: Tutorials
 * Plugin URI: http://annenbergdl.org/
 * Description: Adds a "Tutorials" custom post type and relevant taxonomies.
 * Version: 0.0
 * Author: Nick Halsey
 * Author URI: http://nick.halsey.co/
 * Tags: tutorials, custom post type, custom taxonomy
 * License: GPL

=====================================================================================
Copyright (C) 2015 Nick Halsey

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
require( plugin_dir_path( __FILE__ ) . 'post-type.php' );

// Set up taxonomies.
require( plugin_dir_path( __FILE__ ) . 'taxonomies.php' );

// Set up custom post meta.
//require( plugin_dir_path( __FILE__ ) . 'admin/post-meta.php' );

// Set up custom user meta.
//require( plugin_dir_path( __FILE__ ) . 'user-meta.php' );

// Load template-filtering functions.
//require( plugin_dir_path( __FILE__ ) . 'template/template-filters.php' );
