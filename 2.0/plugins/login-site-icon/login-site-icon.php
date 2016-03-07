<?php
/**
 * Plugin Name: Login Site Icon
 * Plugin URI: http://celloexpressions.com/plugins/login-site-icon
 * Description: Display your site icon instead of the WordPress icon on the login screen.
 * Version: 1.0
 * Author: Nick Halsey
 * Author URI: http://celloexpressions.com/
 * Tags: site icon, widget
 * License: GPL
 
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
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
=====================================================================================
*/


add_filter( 'login_headerurl', 'login_site_icon_url' );
function login_site_icon_url() {
	return get_site_url();
}

add_filter( 'login_headertitle', 'login_site_icon_title' );
function login_site_icon_title( $title ) {
	return get_bloginfo( 'name' );
}

add_action( 'login_head', 'login_site_icon_img_css' );
function login_site_icon_img_css() {
	if ( has_site_icon() ) { ?>
	<style type="text/css">
		.login h1 a {
			background-image: url('<?php site_icon_url( 180 ); ?>');
		}
	</style>
	<?php }
}