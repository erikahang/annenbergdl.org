/**
 * navigation.js
 *
 * Handles navigation-related UI for the Digital Lounge site.
 */
( function() {

	var _window = $( window );
	
	/* Makes "skip to content" link work correctly in IE9 and Chrome for better
	 * accessibility.
	 *
	 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
	 */
	_window.on( 'hashchange.twentyfourteen', function() {
		var hash = location.hash.substring( 1 ), element;

		if ( ! hash ) {
			return;
		}

		element = document.getElementById( hash );

		if ( element ) {
			if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) {
				element.tabIndex = -1;
			}

			element.focus();

			// Repositions the window on jump-to-anchor to account for header height, and accounts for the admin bar.
			window.scrollBy( 0, -64 - $( '#wpadminbar' ).height() );
		}
	} );


} )();
