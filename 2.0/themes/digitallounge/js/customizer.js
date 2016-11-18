/**
 * Customizer preview scripts.
 *
 * Contains handlers to make the customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Footer text.
	wp.customize( 'footer_text', function( value ) {
		value.bind( function( to ) {
			$( '.site-info' ).text( to );
		} );
	} );

} )( jQuery );
