/**
 * Course registration script. Validates registration input, sends via Ajax, and provides users a response.
 */

var courses = {};
( function( $, wp ) {
	courses = {
		container: '',

		init: function() {
			courses.container = $( '#course-registration-form' );
			courses.form = courses.container.find( '#registration-form' );
			courses.notices = courses.container.find( '#registration-notices' );
			
			courses.notices.find( '.notice' ).hide();

			// Bind events, with delegation to facilitate re-rendering.
			courses.form.on( 'click', '#submit-registration', courses.processRegistration );
		},

		/**
		 * Process course registration.
		 */
		processRegistration: function() {
			var data, valid = courses.validateInputs();
			if ( ! valid ) {
				return;
			}

			// Pull data from the form.
			data = {
				'name': courses.form.find( '#course-student-name' ).val(),
				'id': courses.form.find( '#course-student-id' ).val(),
				'email': courses.form.find( '#course-student-email' ).val(),
				'major': courses.form.find( '#course-student-major' ).val(),
				'course': courses.form.find( '#course-course' ).val(),
			};

			// Show the form as loading.
			courses.form.css( 'opacity', '.5' );
			// @todo show spinner
			
			// Send data to the server, and receive a response.
			wp.ajax.send( 'anndl-course-registration', {
				data: data
			} )
			.done( function( response ) {
				if ( response ) {
					if ( 'registered' === response ) {
						courses.notices.find( '#registered' ).show();
					} else if ( 'waitlist' === response ) {
						courses.notices.find( '#waitlist' ).show();
					}
					courses.form.hide();
				} else {
					courses.notices.find( '#unknown' ).html( 'Error processing request. Please try again.' );
					courses.form.css( 'opacity', 1 );
					// @todo hide spinner
				}
			} )
			.fail( function( response ) {
				// If it's a known response code, show the corresponding error.
				if ( 'invalid_email' === response ) {
					courses.notices.find( '#invalid-email' ).show();
				} else if ( 'invalid_id' === response ) {
					courses.notices.find( '#invalid-id' ).show();
				} else {
					courses.notices.find( '#unknown' ).show()
					                                  .text( response );
				}
			} );
		},

		/**
		 * Validate input data and show notices. Everything must also be validated and sanitized in PHP.
		 *
		 * Reuturns true if everything is valid, false otherwise.
		 */
		validateInputs: function() {
			var input, error = false;

			// Reset notices.
			courses.notices.find( 'p' ).hide();

			// Name, must contain at least 2 words (include a space)
			input = courses.form.find( '#course-student-name' ).val();
			if ( '' === input || ! /\s/.test( input ) ) {
				courses.notices.find( '#invalid-name' ).show();
				error = true;
			}
			// Email, must end with @usc.edu
			input = courses.form.find( '#course-student-email' ).val();
			if ( '' === input || '@usc.edu' !== input.substr( input.length - 8 ) ) {
				courses.notices.find( '#non-usc-email' ).show();
				error = true;
			}
			// Student ID, must be 10 digits long
			input = courses.form.find( '#course-student-id' ).val();
			if ( '' === input || 10 !== input.length ) {
				courses.notices.find( '#invalid-id' ).show();
				error = true;
			}
			// Major, must be selected from the list
			input = courses.form.find( '#course-student-major' ).val();
			if ( '0' === input ) {
				courses.notices.find( '#invalid-major' ).show();
				error = true;
			}
			// Course, must be selected from the list
			input = courses.form.find( '#course-course' ).val();
			if ( '0' === input ) {
				courses.notices.find( '#invalid-course' ).show();
				error = true;
			}
			// Policies must be agreed to
			input = document.getElementById( 'course-policies' ).checked;
			if ( ! input ) {
				courses.notices.find( '#invalid-policy' ).show();
				error = true;
			}
			
			return ! error;
		}
	}

	$( document ).ready( function() { courses.init(); } );

} )( jQuery, window.wp );