<?php
/**
 * Plugin Name: Digital Lounge Staff
 * Plugin URI: http://annenbergdl.org/
 * Description: Manages custom fields and taxonomies for users.
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


/* Create custom columns for the users page. */
add_filter( 'manage_users_columns', 'anndl_manage_expertise_user_column' );

/**
 * Adds an 'expertise' column on the manage users admin page.
 *
 * @param array $columns An array of columns to be shown in the manage users table.
 */
function anndl_manage_expertise_user_column( $columns ) {

	$columns['expertise'] = __( 'Expertise' );

	return $columns;
}

/* Customize the output of the custom column on the manage users page. */
add_action( 'manage_users_custom_column', 'anndl_manage_user_expertise_column', 10, 3 );

/**
 * Displays content for custom columns on the manage users page in the admin.
 *
 * @param string $display WP just passes an empty string here.
 * @param string $column The name of the custom column.
 * @param int $user_id The ID of the user being displayed in the table.
 */
function anndl_manage_user_expertise_column( $display, $column, $user_id ) {

	if ( 'expertise' === $column ) {
		$expertise = get_usermeta( $user_id, 'expertise' );
		$expertise = explode( ',', $expertise );
		$expertise = implode( ', ', $expertise );
		return $expertise;
	}
}

/* Add section to the edit user page in the admin to select position. */
add_action( 'show_user_profile', 'anndl_edit_user_expertise_section' );
add_action( 'edit_user_profile', 'anndl_edit_user_expertise_section' );

/**
 * Adds an additional settings section on the edit user/profile page in the admin.  This section allows admins to 
 * select a position from a checkbox of terms from the tools taxonomy.  This is just one example of 
 * many ways this can be handled.
 *
 * @param object $user The user object currently being edited.
 */
function anndl_edit_user_expertise_section( $user ) {

	/* Get the terms of the 'tool' taxonomy. */
	$terms = get_terms( 'tool', array( 'hide_empty' => false, 'hierarchical' => false ) ); ?>

	<h3><?php _e( 'Digital Lounge' ); ?></h3>

	<table class="form-table">

		<tr>
			<th><label><?php _e( 'Expertise' ); ?></label></th>

			<td><?php
			$expertise = get_usermeta( $user->ID, 'expertise' );
			$expertise = explode( ',', $expertise );

			/* If there are any position terms, loop through them and display checkboxes. */
			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) { ?>
					<label><input type="checkbox" name="expertise[]" id="position-<?php echo esc_attr( $term->slug ); ?>" value="<?php echo esc_attr( $term->slug ); ?>" <?php checked( true, in_array( $term->slug, $expertise ) ); ?> ><?php echo $term->name; ?></label><br/>
				<?php }
			}

			/* If there are no expertise terms, display a message. */
			else {
				_e( 'There is no expertise available.' );
			}

			?></td>
		</tr>
		<tr>
			<th><label for="active"><?php _e( 'Status' ); ?></label></th>
			<td><label><input type="checkbox" name="active_staff" value="1" <?php echo checked( get_usermeta( $user->ID, 'active_staff' ) ); ?>> Active staff member</label></td>
		</tr>

	</table>
<?php }

/* Filter the 'sanitize_user' to disable username. */
add_filter( 'sanitize_user', 'anndl_disable_username' );

/**
 * Disables the 'position' username when someone registers.  This is to avoid any conflicts with the custom 
 * 'author/position' slug used for the 'rewrite' argument when registering the 'position' taxonomy.  This
 * will cause WordPress to output an error that the username is invalid if it matches 'position'.
 *
 * @param string $username The username of the user before registration is complete.
 */
function anndl_disable_username( $username ) {

	if ( 'expertise' === $username )
		$username = '';

	return $username;
}

/**
 * Save the taxonomy as a usermeta field.
 */

add_action( 'personal_options_update', 'anndl_save_expertise_meta_profile_fields' );
add_action( 'edit_user_profile_update', 'anndl_save_expertise_meta_profile_fields' );

function anndl_save_expertise_meta_profile_fields( $user_id ) {

	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	if ( array_key_exists( 'active_staff', $_POST ) ) {
		$active = true;
	} else {
		$active = false;
	}
	if ( array_key_exists( 'expertise', $_POST ) ) {
		$expertise = $_POST['expertise'];
		$expertise = implode( ',', $expertise );
		update_usermeta( $user_id, 'expertise', $expertise );
	}

	update_usermeta( $user_id, 'active_staff', $active );
}

