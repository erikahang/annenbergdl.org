<?php
/**
 * Template functions for courses.
 */

/**
 * Display a registration form for all of the courses offered in the current term.
 */
function anndl_courses_registration_form( $term ) {
	wp_enqueue_script( 'course-registration', plugins_url( '/course-registration.js', __FILE__), array ( 'jquery', 'wp-util' ), '20160825', true );
?>
<div id="course-registration-form">
<h2><?php _e( 'Register for a Course', 'anndl-courses' ); ?></h2>
<form id="registration-form">
	<label>
		<?php _e( 'Name', 'anndl-courses' ); ?><br>
		<input type="text" id="course-student-name" class="course-registration-input">
	</label>
	<label>
		<?php _e( 'USC Email', 'anndl-courses' ); ?><br>
		<input type="email" id="course-student-email" class="course-registration-input">
	</label>
	<label>
		<?php _e( 'Student ID Number', 'anndl-courses' ); ?><br>
		<input type="number" min="1000000000" max="9999999999" id="course-student-id" class="course-registration-input">
	</label>
	<label>
		<?php _e( 'Major, Minor, or Taking an Annenberg Course', 'anndl-courses' ); ?><br>
		<select id="course-student-major">
			<option value="0">— Select —</option>
			<?php foreach ( anndl_courses_major_options() as $code => $major ) {
				echo '<option value="' . $code . '">' . $major . '</option>';
			} ?>
		</select>
	</label>
	<label>
		<?php _e( 'Course', 'anndl-courses' ); ?><br>
		<select id="course-course">
			<option value="0">— Select —</option>
			<?php
			foreach ( anndl_courses_get_courses_in_term( $term ) as $course ) {
				$id = $course->ID;
				$title = get_the_title( $course );
				$time = get_post_meta( $id, 'course-time', true );
				$author_id = $course->post_author;
				$instructor = get_the_author_meta( 'display_name', $author_id );
				echo '<option value="' . $id . '">' . $title . ', ' . $time . __( ' with ', 'anndl-courses' ) . $instructor . ' (' . anndl_courses_registered_status( $id ) . ')</option>';
			} ?>
		</select>
	</label>
	<label>
		<input type="checkbox" id="course-policies" value="1">
		<?php _e( ' I have read the course policies and agree to attend all sessions and take the exam at the end of the semester.', 'anndl-courses' ); ?><br>
	</label>
	<button type="button" id="submit-registration" class="button"><?php _e( 'Register' ); ?></button>
</form>
<div id="registration-notices">
	<p class="notice error" id="input-error"><?php _e( 'Something looks wrong with your information, please double-check it.' ); ?></p>
	<p class="notice success" id="registered"><?php _e( 'You have successfully registered for this course.' ); ?></p>
	<p class="notice warning" id="waitlist"><?php _e( 'You have successfully been added to the waitlist for this course. Annenberg Digital Lounge staff will contact you if a space opens up.' ); ?></span>
	<p class="notice error" id="non-usc-email"><?php _e( 'You must register with your USC email address.' ); ?></p>
	<p class="notice error" id="invalid-email"><?php _e( 'Your email did not work.' ); ?></p>
	<p class="notice error" id="invalid-name"><?php _e( 'Please provide your first and last name.' ); ?></p>
	<p class="notice error" id="invalid-id"><?php _e( 'Please check your USC ID number.' ); ?></p>
	<p class="notice error" id="invalid-major"><?php _e( 'Please select your major, minor or Annenberg course.' ); ?></p>
	<p class="notice error" id="invalid-course"><?php _e( 'Please select a course to register for.' ); ?></p>
	<p class="notice error" id="invalid-policy"><?php _e( 'Please agree to the course policies.' ); ?></p>
	<p class="notice error" id="already-registered"><?php _e( 'You are already registered for a course this semester. If you would like to register for this course instead, ask the Digital Lounge helpdesk staff to remove you from the other course, then try again.' ); ?></p>
	<p class="notice error" id="banned-student"><?php _e( 'Sorry, you have been banned from registering for courses. This is likely due to excessive absensences for past courses that you registered for. If you have questions, please email creative@usc.edu or talk to the helpdesk staff in ANN 301.' ); ?></p>
	<p class="notice error" id="unknown"></p>
</div>
</div><?php
}

/**
 * Returns a keyed array of major codes and names.
 */
function anndl_courses_major_options() {
	$majors = array(
		'COMM' => __( 'Communication', 'anndl-courses' ),
		'COMM-PHD' => __( 'Communication PH.D', 'anndl-courses' ),
		'COMM-Global' => __( 'Global Communication', 'anndl-courses' ),
		'JOUR' => __( 'Journalism', 'anndl-courses' ),
		'JOUR-Grad' => __( 'Journalism Grad', 'anndl-courses' ),
		'CMGT' => __( 'Communication Management', 'anndl-courses' ),
		'DSM' => __( 'Digital Social Media', 'anndl-courses' ),
		'PUBD' => __( 'Public Diplomacy', 'anndl-courses' ),
		'PR' => __( 'Public Relations', 'anndl-courses' ),
		'SPR' => __( 'Strategic Public Relations', 'anndl-courses' ),
		'MINOR' => __( 'Annenberg Minor', 'anndl-courses' ),
		'COURSE' => __( 'Taking an Annenberg Course', 'anndl-courses' ),
	);
	return $majors;
}

/**
 * Returns text explaining the current registration status, including a waitlist if it's over capacity.
 */
function anndl_courses_registered_status( $course_id ) {
	$registered = get_post_meta( $course_id, '_students', true );
	if ( empty( $registered ) ) {
		$registered = 0;
	} else {
		$registered = count( $registered );
	}
	$capacity = get_post_meta( $course_id, 'capacity', true );
	if ( $registered > $capacity ) {
		/* Translators: %1$d is the number of students on the waitlist */
		$text = sprintf( __( 'Course full, %1$d on the waitlist', 'anndl-courses' ), ( $registered - $capacity ) );
	} else {
		$text = sprintf( __( '%1$d of %2$d registered', 'anndl-courses' ), $registered, $capacity );
	}
	return $text;
}

/**
 * Returns the post objects for each post in $term.
 */
function anndl_courses_get_courses_in_term( $term ) {
	$args = array(
		'numberposts' => 0,
		'post_type'   => 'course',
		'tax_query'   => array(
			array(
				'field'    => 'term_id',
				'taxonomy' => 'semester',
				'terms'    => $term,
			),
		),
	);
	$posts = get_posts( $args );
	if ( ! is_wp_error( $posts ) ) {
		return $posts;
	} else {
		return false;
	}
}
