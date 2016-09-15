<?php
/**
 * Ajax actions for the Courses plugin.
 */

/**
 * Registeres a user for a course.
 *
 * Anyone can register, so nonces are not used/checked here.
 */
function anndl_courses_register_student() {
	$course_id = absint( $_POST['course'] );

	$course = get_post( $course_id );
	if ( ! $course || is_wp_error( $course ) ) {
		wp_send_json_error( 'invalid_course' );
	}

	$students = get_post_meta( $course_id, '_students', true );
	if ( empty( $students ) ) {
		$students = array();
	} else {
		$students = $students;
	}

	// Validate and sanitize student's email and USC ID.
	if ( '@usc.edu' !== substr( $_POST['email'], -8 ) ) {
		wp_send_json_error( 'invalid_email' );
	} else {
		$email = sanitize_text_field( substr( $_POST['email'], 0, -8 ) );
	}
	if ( 10 !== strlen( $_POST['id'] ) ) {
		wp_send_json_error( 'invalid_id' );
	} else {
		$id = absint( $_POST['id'] );
	}
	$name = sanitize_text_field( $_POST['name'] );
	$major = sanitize_text_field( $_POST['major'] );

	// Make sure the student hasn't already registered for this course.
	if ( array_key_exists( $email, $students ) ) {
		wp_send_json_error( 'already_registered' );
	}

	// Make sure the student hasn't registered for any other courses this semester.
	$term = absint( get_the_terms( $course_id, 'semester' )[0]->term_id );
	$courses = anndl_courses_get_courses_in_term( $term );
	foreach ( $courses as $other_course ) {
		$other_students = get_post_meta( $other_course->ID, '_students', true );
		if ( empty( $students ) ) {
			$other_students = array();
		}
		if ( array_key_exists( $email, $other_students ) ) {
			wp_send_json_error( 'already_registered' );
		}		
	}

	$student = array(
		'name' => $name,
		'id' => $id,
		'major' => $major,
		'certified' => 'no',
	);
	
	$students[$email] = $student;
	$result = update_post_meta( $course->ID, '_students', $students ); // Automatically re-serializes the array. $course_id does not work here for some reason.
	if ( $result ) {
		$capacity = get_post_meta( $course_id, 'capacity', true );
		if ( ! $capacity ) {
			$capacity = 32;
		}
		if ( count( $students ) > $capacity ) {
			$result = anndl_courses_email_waitlisted( $email . '@usc.edu', $course_id, $student );
			if ( ! $result ) {
				wp_send_json_error( 'invalid_email' );
			} else {
				wp_send_json_success( 'waitlist' );
			}
		} else {
			$result = anndl_courses_email_registered( $email . '@usc.edu', $course_id, $student );
			if ( ! $result ) {
				wp_send_json_error( 'invalid_email' );
			} else {
				wp_send_json_success( 'registered' );
			}
		}
	} else {
		wp_send_json_error( 'error_updating_post_meta' . var_dump( $students ) . $course->ID );
	}

}
add_action( 'wp_ajax_anndl-course-registration', 'anndl_courses_register_student' );
add_action( 'wp_ajax_nopriv_anndl-course-registration', 'anndl_courses_register_student' );


/**
 * Delete a student's registration for a course.
 *
 * Only authenticated users can delete registrations.
 */
function anndl_courses_delete_registration() {
	check_ajax_referer( 'anndl_students_nonce', 'anndl-students-nonce' );

	$course_id = absint( $_POST['course'] );
	$course = get_post( $course_id );
	if ( ! $course || is_wp_error( $course ) ) {
		wp_send_json_error( 'invalid_course' );
	}

	$student = sanitize_text_field( $_POST['email'] );
	$students = get_post_meta( $course_id, '_students', true );
	if ( '' === $students || ! array_key_exists( $student, $students ) ) {
		wp_send_json_error( 'invalid_student' );
	} else {
		$position = array_search( $student, array_keys( $students ) );
		$student_info = $students[$student];
		unset( $students[$student] );
	}
	$result = update_post_meta( $course->ID, '_students', $students ); // Automatically re-serializes the array. $course_id does not work here for some reason.
	if ( $result ) {
		$result = anndl_courses_email_removed( $student . '@usc.edu', $course->ID, $student_info );
		// anndl_courses_maybe_promote_from_waitlist( $course->ID, $position ) ) {
		if ( ! $result ) {
			wp_send_json_error( 'email_error' );
		} else {
			wp_send_json_success( 'deleted' );
		}
	} else {
		wp_send_json_error( 'could_not_update_student_data' );
	}
}
add_action( 'wp_ajax_anndl-courses-delete-registration', 'anndl_courses_delete_registration' );

function anndl_courses_maybe_promote_from_waitlist( $course_id, $position ) {
	$capacity = absint( get_post_meta( $course_id, 'capacity', true ) );
	if ( $position < $capacity ) { // Note that position starts from 0.
		$students = get_post_meta( $course_id, '_students', true );
		$registered = count( $students );
		if ( $registered > $capacity ) {
			// Someone is coming off from the waitlist, so notify them by email.
			$keys = array_keys( $students );
			$email = $keys[$capacity - 1];
			return anndl_courses_email_off_waitlist( $email . '@usc.edu', $course_id, $students[$email] );
		}
	}
}


/**
 * Update a student's certification status.
 *
 * Only authenticated users can update certification statuses.
 */
function anndl_courses_change_certification_status() {
	check_ajax_referer( 'anndl_students_nonce', 'anndl-students-nonce' );

	$course_id = absint( $_POST['course'] );
	$course = get_post( $course_id );
	if ( ! $course || is_wp_error( $course ) ) {
		wp_send_json_error( 'invalid_course' );
	}

	$status = sanitize_text_field( $_POST['status'] );
	if ( ! array_key_exists( $status, anndl_courses_get_certification_statuses() ) ) {
		wp_send_json_error( 'invalid_status' );
	}

	$student = sanitize_text_field( $_POST['email'] );
	$students = get_post_meta( $course_id, '_students', true );
	if ( '' === $students || ! array_key_exists( $student, $students ) ) {
		wp_send_json_error( 'invalid_student' );
	} else {
		$student_info = $students[$student];
		$student_info['certified'] = $status;
		$students[$student] = $student_info;
	}
	$result = update_post_meta( $course->ID, '_students', $students ); // Automatically re-serializes the array. $course_id does not work here for some reason.
	if ( $result ) {
		wp_send_json_success( 'updated' );
	} else {
		wp_send_json_error( 'could_not_update_student_data' );
	}
}
add_action( 'wp_ajax_anndl-courses-change-certification-status', 'anndl_courses_change_certification_status' );
