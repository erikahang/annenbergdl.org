var scrolled;
( function( $ ) {
	$(document).ready( function() {
		$.fn.fixscroll = function(e) {
			scrolled = $(document).scrollTop();
			if ( e && ( e=="scroll" || e=="resize" ) ) {
		//		if ( pagewidth > 910 ) {
					thermometer = scrolled / ( $(document).height() - $(window).height() );
					$('#thermometer').css({'width': 'calc(' + thermometer + '% - 48px);'});
		//		}
			}
		}
	} );
} ) ( jQuery );