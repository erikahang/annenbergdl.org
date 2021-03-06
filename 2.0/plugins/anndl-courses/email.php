<?php
/* 
 * Send emails for course registration actions.
 */

// Replace paceholders with course and student info in the email.
// Returns the formatted message.
function anndl_courses_parse_email_data( $message, $course_id, $student ) {
	$course = get_post( $course_id );
	$author_id = $course->post_author;
	$instructor = get_the_author_meta( 'display_name', $author_id );
	$date_time = get_post_meta( $course_id, 'course-time', true );
	if ( 'Rick Miller' === $instructor ) {
		$instructor_email = 'Rick Miller - rickmillerphotography@gmail.com'; // @todo pull email from user account
	} else if ( 'Chana Messer' === $instructor ) {
		$instructor_email = 'Chana Messer - chana.messer@gmail.com';
	}

	$placeholders = array( '{Name}', '{Course}', '{Instructor}', '{Date Time}', '{Instructor with Email}' );
	$data = array( $student['name'], get_the_title( $course_id ), $instructor, $date_time, $instructor_email );

	return str_replace( $placeholders, $data, $message );
}

// Returns the email template for a given email type, based on either the email option or the default.
// Returns false if the specified $type is invalid.
// This is where the default templates are defined. They're customizable via an admin settings page.
function anndl_courses_get_email_template( $type ) {
	if ( ! $type ) {
		return false;
	}

	$emails = wp_parse_args( get_option( 'anndl_courses_email_messages' ), array(
		'registered' => 'Hi {Name},

Thank you for registering for the {Course} with {Instructor} on {Date Time} in the Annenberg Digital Lounge, ANN 301D.

Classes start the week of Monday, September 5th. However, Monday sections will start the following week on Monday, September 12th due to Labor Day on Sept 5th.

The textbook is optional, but if you would like to purchase it, they can be found on Amazon.com. Search for "Adobe Classroom in a Book" and look for the corresponding program book you need. You will want to purchase the 2015 release version.

Please be aware that if you miss 4 classes or more, you may be banned from taking certification courses with us in the future. If any issues or concerns arise with attendance, you can contact your instructor:

{Instructor with Email}

We look forward to seeing you next week!

Thanks,
Creative Media Team',
		'waitlisted' => 'Hi {Name},

Unfortunately, the {Course} with {Instructor} on {Date Time} is currently at capacity and you have been added to the waitlist.

You will be notified if a space opens up.

Thanks,
Creative Media Team',
		'dropped' => 'Hi {Name},

You have been removed from the {Course} with {Instructor} on {Date Time}.

If you are receiving this message by mistake, please reply to this email ASAP.

Thanks,
Creative Media Team',
		'off_waitlist' => 'Hi {Name},

A space has opened up in the {Course} with {Instructor} at {Date Time} in the Annenberg Digital Lounge (ANN 301D) and you are officially registered for the course!

Classes start the week of Monday, September 5th. However, Monday sections will start the following week on Monday, September 12th due to Labor Day on Sept 5th.

The textbook is optional, but if you would like to purchase it, they can be found on Amazon.com. Search for Adobe Classroom in a Book and look for the corresponding program book you need. You will want to purchase the 2015 release version.

Please be aware that if you miss 4 classes or more, you may be banned from taking certification courses with us in the future. If any issues or concerns arise with attendance, you can contact your instructor:

{Instructor with Email}

We look forward to seeing you next week!

Thanks,
Creative Media Team',
	) );

	if ( 'all' === $type ) {
		return $emails;
	} elseif ( ! array_key_exists( $type, $emails ) ) {
		return false;
	}

	return $emails[$type];
}

// Send an email for a successful registration.
function anndl_courses_email_registered( $email, $course_id, $student ) {
	$email = sanitize_email( $email );
	if ( ! is_email( $email ) ) {
		return;
	}

	$subject = 'Certification Course Registration Confirmation';
	$message = anndl_courses_get_email_template( 'registered' );

	$message = anndl_courses_parse_email_data( $message, $course_id, $student );

	$headers = array( 'Reply-To: creative@usc.edu', 'From: Creative Media Team <wordpress@annenbergdl.org>' );
	
	$success = wp_mail( $email, $subject, $message, $headers );

	return $success;
}

function anndl_courses_email_waitlisted( $email, $course_id, $student ) {
	$email = sanitize_email( $email );
	if ( ! is_email( $email ) ) {
		return;
	}

	$subject = 'Certication Couse Waitlist Confirmation';
	$message = anndl_courses_get_email_template( 'waitlisted' );

	$message = anndl_courses_parse_email_data( $message, $course_id, $student );

	$headers = array( 'Reply-To: creative@usc.edu', 'From: Creative Media Team <wordpress@annenbergdl.org>' );
	
	$success = wp_mail( $email, $subject, $message, $headers );

	return $success;
}

function anndl_courses_email_removed( $email, $course_id, $student ) {
	$email = sanitize_email( $email );
	if ( ! is_email( $email ) ) {
		return;
	}

	$subject = 'Certification Course Dropped';
	$message = anndl_courses_get_email_template( 'dropped' );

	$message = anndl_courses_parse_email_data( $message, $course_id, $student );

	$headers = array( 'Reply-To: creative@usc.edu', 'From: Creative Media Team <wordpress@annenbergdl.org>', 'CC: Creative Media Team <creative@usc.edu>' );
	
	$success = wp_mail( $email, $subject, $message, $headers );

	return $success;
}

function anndl_courses_email_off_waitlist( $email, $course_id ) {
	$email = sanitize_email( $email );
	if ( ! is_email( $email ) ) {
		return;
	}

	$subject = 'Cerification Course Registration Update';
	$message = anndl_courses_get_email_template( 'off_waitlist' );

	$message = anndl_courses_parse_email_data( $message, $course_id, $student );

	$headers = array( 'Reply-To: creative@usc.edu', 'From: Creative Media Team <wordpress@annenbergdl.org>', 'CC: Ceative Media Team <creative@usc.edu>' );
	
	$success = wp_mail( $email, $subject, $message, $headers );

	return $success;
}
