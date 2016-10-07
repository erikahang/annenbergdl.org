<?php
/**
 * Courses: user lookup page.
 */

add_action( 'admin_menu', 'anndl_courses_add_user_lookup_page' );
function anndl_courses_add_user_lookup_page() {
 	add_submenu_page( 
		'edit.php?post_type=course', // Parent page slug
		__( 'Student History Lookup' ), // Page title within page
		__( 'Student Lookup' ), // Page title in menu
		'edit_posts', // Capability
		'course-student-lookup', // Page slug
		'anndl_courses_print_user_lookup_page' // Page content callback.
	);
}

function anndl_courses_print_user_lookup_page() {
	?>
<div class="wrap">
	<h1><?php _e( 'Student History Lookup' ); ?></h1>
	<p>
		<label>
		Student email: 
		<input type="text" name="email" id="student-email">@usc.edu
		</label>
		<button type="button" class="button load-info"><?php _e( 'Load' ); ?></button>
	</p>
	<div id="student-display"></div>
</div>
	<?
}