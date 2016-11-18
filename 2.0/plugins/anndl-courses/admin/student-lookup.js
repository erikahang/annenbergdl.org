/**
 * Student lookup page script. Loads student info and updates the banned students list via Ajax.
 */

var coursesStudents = {};
( function( $ ) {
	coursesStudents = {
		container: '',
		infoContainer: '',
		currentStudent: '',
		template: '',

		init: function() {
			coursesStudents.container = $( '.wrap' );
			coursesStudents.studentInput = $( '#student-email' );
			coursesStudents.bannedInput = $( '#banned-students' );
			coursesStudents.infoContainer = $( '#student-display' );

			// Set up template.
			coursesStudents.template = wp.template( 'student-info' );

			coursesStudents.container.on( 'click', '.load-info', function( e ) {
				coursesStudents.loadInfo();
			});

			coursesStudents.container.on( 'change', '#banned-students', function( e ) {
				coursesStudents.updateBanned();
			});
		},

		/**
		 * Remove a user from the course, via Ajax.
		 */
		loadInfo: function() {
			var query;

			if ( coursesStudents.studentInput.val() === coursesStudents.currentStudent ) {
				return;
			}

			// Pull data from the form.
			query = {
				'email': coursesStudents.studentInput.val(),
				'anndl-students-nonce': $( '#anndl_students_nonce' ).val()
			};

			// Show the form as loading.
			coursesStudents.studentInput.css( 'opacity', '.5' );
			coursesStudents.infoContainer.css( 'opacity', '.5' );

			// Send data to the server, and receive a response.
			wp.ajax.send( 'anndl-courses-load-student-info', {
				data: query
			} )
			.done( function( response ) {
				if ( response ) {
					coursesStudents.infoContainer.html( coursesStudents.template( response ) );
				}
			} )
			.fail( function( response ) {
				if ( 'invalid_student' === response ) {
					alert( 'Error: there are no records for this student.' );
				} else {
					alert( 'Error processing request. Please try again. Error code:' + response );
				}
			} )
			.always( function( response ) {
				coursesStudents.studentInput.css( 'opacity', 1 );
				coursesStudents.infoContainer.css( 'opacity', 1 );
			} );
		},

		/**
		 * Update a student's certification status, via Ajax.
		 */
		updateBanned: function( el ) {
			var data;

			// Pull data from the form.
			data = {
				'emails': coursesStudents.bannedInput.val(),
				'anndl-students-nonce': $( '#anndl_students_nonce' ).val()
			};

			// Show the form as loading.
			coursesStudents.bannedInput.css( 'opacity', '.5' );

			// Send data to the server, and receive a response.
			wp.ajax.send( 'anndl-courses-update-banned-emails', {
				data: data
			} )
			.done( function( response ) {
				if ( 'updated' === response ) {
					coursesStudents.bannedInput.css( 'opacity', '1' );
				} else {
					alert( 'Error processing request. Please try again. Error code: ' + response );
					coursesStudents.bannedInput.css( 'opacity', '1' );
				}
			} )
			.fail( function( response ) {
				alert( 'Error processing request. Please try again. Error code:' + response );
				coursesStudents.bannedInput.css( 'opacity', '1' );
			} );
		}

	}

	$( document ).ready( function() { coursesStudents.init(); } );

} )( jQuery );