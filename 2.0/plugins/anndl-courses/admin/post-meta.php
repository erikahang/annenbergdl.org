<?php
/**
 * Custom post meta for the Courses post type.
 */

// Add courses meta boxes.
function anndl_courses_add_meta_boxes() {
	add_meta_box( 'anndl_courses_students', __( 'Registered Students', 'anndl-courses' ), 'anndl_courses_students_meta_box', 'course', 'advanced', 'high' );
	add_meta_box( 'anndl_courses_meta', __( 'Course Information', 'anndl-courses' ), 'anndl_courses_meta_box', 'course', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'anndl_courses_add_meta_boxes' );

// Enqueue scripts & styles.
function anndl_courses_admin_scripts() {
    global $post_type, $post;
    if ( 'course' === $post_type ) {
    	// Enqueue scripts & styles.
		wp_enqueue_script( 'courses-admin', plugins_url( '/courses-admin.js', __FILE__), '', '20161209', true );

		// Load data into JS, including translated strings.
		wp_localize_script( 'courses-admin', 'coursesOptions', array(
			'l10n' => array(
				'select' => __( 'Select File', 'anndl-courses' ),
			),
		) );
	}
}
add_action( 'admin_print_scripts-post-new.php', 'anndl_courses_admin_scripts' );
add_action( 'admin_print_scripts-post.php', 'anndl_courses_admin_scripts' );


// Callback that renders the contents of the registered students metabox.
function anndl_courses_students_meta_box( $post ) {
	wp_nonce_field( 'anndl_students_nonce', 'anndl_students_nonce' );
	
	$students = get_post_meta( $post->ID, '_students', true );
	if ( empty( $students ) ) {
		_e( 'No students are registered yet.' );
	} else {
		?>
		
		<table class="wp-list-table widefat" id="courses-registered" data-courseid="<?php echo $post->ID; ?>"><thead><tr class="heading">
			<th><?php _e( 'Number' ); ?></th>
			<th><?php _e( 'Name' ); ?></th>
			<th><?php _e( 'Email' ); ?></th>
			<th><?php _e( 'USC ID' ); ?></th>
			<th><?php _e( 'Major' ); ?></th>
			<th><?php _e( 'Absences' ); ?></th>
			<th><?php _e( 'Certification Status' ); ?></th>
			<th><?php _e( 'Remove Student' ); ?></th>
		</tr></thead><tbody>
		<?php
		$i = 1;
		$majors = anndl_courses_major_options();
		foreach ( $students as $email => $info ) {
			// Set the default absences value, as it isn't set on registration.
			if ( ! array_key_exists( 'absences', $info ) ) {
				$info['absences'] = 0;
			}

			if ( $i - 1 == get_post_meta( $post->ID, 'capacity', true ) ) {
				// Make a separate waitlist list. ?>
				</tbody></table>
				<h4><?php _e( 'Waitlist', 'anndl-courses' ); ?></h4>
				<table class="wp-list-table widefat" id="courses-waitlist"><thead><tr class="heading">
					<th><?php _e( 'Number' ); ?></th>
					<th><?php _e( 'Name' ); ?></th>
					<th><?php _e( 'Email' ); ?></th>
					<th><?php _e( 'USC ID' ); ?></th>
					<th><?php _e( 'Major' ); ?></th>
					<th><?php _e( 'Absences' ); ?></th>
					<th><?php _e( 'Certification Status' ); ?></th>
					<th><?php _e( 'Remove Student' ); ?></th>
				</tr></thead><tbody>
				<?php
			}
			?>
			<tr class="student" data-email="<?php echo $email; ?>">
				<td class="number"><?php echo $i; ?></td>
				<td><?php echo $info['name']; ?></td>
				<td><?php echo $email . '@usc.edu'; ?></td>
				<td><?php echo $info['id']; ?></td>
				<td><?php echo $majors[$info['major']]; ?></td>
				<td style="min-width: 76px"><button type="button" class="button subtract-absence">-</button>
					<span class="num-absences"><?php echo $info['absences']; ?></span>
					<button type="button" class="button add-absence">+</button>
				</td>
				<td><select class="change-certification-status" data-email="<?php echo $email; ?>">
					<?php foreach ( anndl_courses_get_certification_statuses() as $code => $status ) {
						if ( $info['certified'] === $code ) {
							$selected = ' selected="selected"';
						} else {
							$selected = '';
						}
						echo '<option value="' . $code . '"' . $selected . '>' . $status . '</option>';
					} ?>
				</select></td>
				<td><button type="button" class="button-link remove-student" data-email="<?php echo $email; ?>"><?php _e( 'Remove' ); ?></button></td>
			</tr>
			<?php
			$i++;
		}
		?>
		</tbody></table>
		<?php
	}
}

// Callback that renders the contents of the course information metabox.
function anndl_courses_meta_box( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'anndl_courses_nonce' );
	$anndl_courses_stored_meta = get_post_meta( $post->ID );

	if ( isset ( $anndl_courses_stored_meta['capacity'] ) ) {
		$capacity = absint( $anndl_courses_stored_meta['capacity'][0] );
	} else {
		$capacity = 32;
	}

	if ( isset ( $anndl_courses_stored_meta['course-time'] ) ) {
		$course_time = sanitize_text_field( $anndl_courses_stored_meta['course-time'][0] );
	} else {
		$course_time = '';
	}
	?>
	<p>
		<label for="course-time"><?php _e( 'Course Days & Time', 'anndl-courses' ); ?></label>
		<input type="text" name="course-time" class="widefat" value="<?php echo $course_time; ?>" />
	</p>
	<p>
		<label for="course-capacity"><?php _e( 'Course Capacity', 'anndl-courses' ); ?></label>
		<input type="number" name="capacity" value="<?php echo $capacity; ?>" /> <?php _e( 'students', 'anndl-courses' ); ?>
	</p>
	<?php
}

/**
 * Save the custom fields on post save.
 */
function anndl_courses_post_meta_save( $post_id ) {
	// Bail if this isn't a valid time to save post meta.
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'anndl_courses_nonce' ] ) && wp_verify_nonce( $_POST[ 'anndl_courses_nonce' ], basename( __FILE__ ) ) ) ? true : false;
	if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
		return;
	}

	// Sanitize and save post meta.
	if ( isset( $_POST[ 'course-time' ] ) ) {
		update_post_meta( $post_id, 'course-time', sanitize_text_field( $_POST[ 'course-time' ] ) );
	}
	if ( isset( $_POST[ 'capacity' ] ) ) {
		update_post_meta( $post_id, 'capacity', absint( $_POST[ 'capacity' ] ) );
	}
}
add_action( 'save_post', 'anndl_courses_post_meta_save' );


/**
 * Add post meta information to the courses list table.
 */
function anndl_course_list_custom_meta_columns( $columns ) {
	// Add custom columns.
	$columns['time'] = __( 'Day & Time', 'anndl-courses' );
	$columns['registered'] = __( 'Registered', 'anndl-courses' );
	$columns['certified'] = __( 'Certified', 'anndl-courses' );

	// Rename author to instructor and semesters to semester.
	$columns['author'] = __( 'Instructor', 'anndl-courses' );
	$columns['taxonomy-semester'] = __( 'Semester', 'anndl-courses' );

	// Remove (post) date column.
	unset( $columns['date'] );

	return $columns;
}
add_filter( 'manage_edit-course_columns', 'anndl_course_list_custom_meta_columns' );

function anndl_course_list_custom_meta_contents( $column, $post_id ) {
	if ( 'time' === $column ) {
		echo get_post_meta( $post_id, 'course-time', true );
	} elseif ( 'registered' === $column ) {
		$registered = get_post_meta( $post_id, '_students', true );
		if ( empty( $registered ) ) {
			$registered = 0;
		} else {
			$registered = count( $registered );
		}
		$capacity = get_post_meta( $post_id, 'capacity', true );
		if ( ! $capacity ) {
			echo 'N/A';
		} else {
			echo $registered . ' / ' . $capacity;
		}
	} elseif ( 'certified' === $column ) {
		$registered = get_post_meta( $post_id, '_students', true );
		if ( empty( $registered ) ) {
			$registered = array();
		} else {
			$passed = 0;
			foreach( $registered as $student ) {
				if ( 'pass' === $student['certified'] ) {
					$passed++;
				}
			}
		}

		if ( empty( $registered ) ) {
			echo 'N/A';
		} else {
			echo $passed . ' / ' . count( $registered );
		}
	}
}
add_action( 'manage_course_posts_custom_column', 'anndl_course_list_custom_meta_contents', 10, 2 );

// Returns an array of certification statuses.
function anndl_courses_get_certification_statuses() {
	$statuses = array(
		'no' => __( 'Did Not Take Exam', 'anndl-courses' ),
		'pass' => __( 'Passed', 'anndl-courses' ),
		'fail' => __( 'Failed', 'anndl-courses' ),
		'drop' => __( 'Dropped', 'anndl-courses' ),
	);
	return $statuses;
}

// Move all "advanced" metaboxes above the editor.
function anndl_courses_move_meta_boxes() {
	global $post, $wp_meta_boxes;
	if ( 'course' === get_post_type( $post ) ) {
		do_meta_boxes( get_current_screen(), 'advanced', $post );
		unset( $wp_meta_boxes[get_post_type( $post )]['advanced']);
	}
}
add_action( 'edit_form_after_title', 'anndl_courses_move_meta_boxes' );
