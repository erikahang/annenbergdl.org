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
   	// Enqueue scripts & styles.
	wp_enqueue_script( 'student-lookup', plugins_url( '/student-lookup.js', __FILE__), array( 'jquery', 'wp-util' ), '20161117', true );

	?>
<style type="text/css">
dt {
	width: 120px;
	float: left;
	font-weight: 600;
	padding-right: 2em;
}

h4 {
	font-size: 1.2em;
}

dl {
	width: auto;
	background: #fff;
	padding: .6em 1em;
	margin-right: 50%;
	overflow: hidden;
}
</style>
<div class="wrap">
	<?php wp_nonce_field( 'anndl_students_nonce', 'anndl_students_nonce' ); ?>

	<h1><?php _e( 'Student Information Lookup' ); ?></h1>
	<p>
		<label>
		Student email: 
		<input type="text" name="email" id="student-email">@usc.edu
		</label>
		<button type="button" class="button load-info"><?php _e( 'Load' ); ?></button>
	</p>
	<div id="student-display"></div>
	<h2><?php _e( 'Banned Students' ); ?></h2>
	<p><?php _e( 'Students with these emails are not allowed to register for any courses.' ); ?></p>
	<textarea name="banned-students" id="banned-students"><?php esc_attr_e( get_option( 'anndl_courses_banned_students' ) ); ?></textarea>
	<script type="text/html" id="tmpl-student-info">
		<h3>{{ data.name }}</h3>
		<dl>
			<dt><?php _e( 'Email' ); ?></dt>
			<dd>{{ data.email }}</dd>
			<dt><?php _e( 'USC ID' ); ?></dt>
			<dd>{{ data.uscid }}</dd>
			<dt><?php _e( 'Major' ); ?></dt>
			<dd>{{ data.major }}</dd>
		</dl>
		<h4><?php _e( 'Courses' ); ?></h4>
		<dl>
			<# data.courses.forEach( function ( course ) { #>
				<dt>{{ course.title }}</dt>
				<dd>{{ course.info }}. <strong>{{ course.result }}</strong></dd>
			<# } ); #>
		</dl>
	</script>
</div>
	<?
}
