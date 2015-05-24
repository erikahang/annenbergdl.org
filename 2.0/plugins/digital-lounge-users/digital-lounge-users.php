<?php
/**
 * Plugin Name: Digital Lounge Users
 * Plugin URI: http://annenbergdl.org/
 * Description: Manages user registration and custom view data storage.
 * Version: 0.0
 * Author: Nick Halsey
 * Author URI: http://nick.halsey.co/
 * Tags: 
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

// @todo restrict emails to usc.edu domains.
// @todo functions for setting/getting user customization options.

/* Filter 'sanitize_user' to disable usernames. *
add_filter( 'sanitize_user', 'anndl_disable_username' );

/**
 * Disables the 'position' username when someone registers.  This is to avoid any conflicts with the custom 
 * 'author/position' slug used for the 'rewrite' argument when registering the 'position' taxonomy.  This
 * will cause WordPress to output an error that the username is invalid if it matches 'position'.
 *
 * @param string $username The username of the user before registration is complete.
 *
function anndl_disable_username( $username ) {

	if ( 'expertise' === $username )
		$username = '';

	return $username;
}
