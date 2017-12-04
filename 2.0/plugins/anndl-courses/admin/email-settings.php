<?php
/**
 * Courses: user lookup page.
 */

add_action( 'admin_init', 'anndl_courses_register_email_settings' );
function anndl_courses_register_email_settings() {
	register_setting(
		'anndl_courses_email_messages', // Option group
		'anndl_courses_email_messages', // Option name
		'anndl_courses_sanitize_email_settings' // Sanitize
    );
}

function anndl_courses_sanitize_email_settings( $emails ) {
	$saved_emails = anndl_courses_get_email_template( 'all' );
	$result = array_diff_key( $emails, $saved_emails );
	if ( empty( $result ) ) {
		return $emails;
	} else {
		return false;
	}
}

add_action( 'admin_menu', 'anndl_courses_add_email_settings_page' );
function anndl_courses_add_email_settings_page() {
 	add_submenu_page(
		'edit.php?post_type=course', // Parent page slug
		__( 'Course Emails' ), // Page title within page
		__( 'Emails' ), // Page title in menu
		'manage_options', // Capability
		'course-email-settings', // Page slug
		'anndl_courses_print_email_settings_page' // Page content callback.
	);
}

function anndl_courses_print_email_settings_page() {
	$emails = anndl_courses_get_email_template( 'all' );

	?>
<style type="text/css">
</style>
<div class="wrap">
	<?php wp_nonce_field( 'anndl_students_nonce', 'anndl_students_nonce' ); ?>

	<h1><?php _e( 'Course Emails' ); ?></h1>

	<form method="post" action="options.php">
	<p>The following placeholders are available: {Name}, {Course}, {Instructor}, {Date Time}, {Instructor with Email}.</p>
	<table class="form-table">
		<?php settings_fields( 'anndl_courses_email_messages' );
		foreach ( $emails as $type => $email ) : ?>
		<tr>
			<th scope="row"><?php echo $type; ?></th>
			<td>
				<textarea name="anndl_courses_email_messages[<?php echo $type; ?>]" class="widefat" rows="<?php echo substr_count( $email, PHP_EOL ) + 3; ?>"><?php echo $email; ?></textarea>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr>
			<td colspan="2">
				<?php submit_button( __( 'Save Changes' ) ); ?>
			</td>
		</tr>
	</table>
	</form>
</div>


	<?
}
