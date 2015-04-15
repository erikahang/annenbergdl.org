var scrolled, pagewidth;
( function( $ ) {
	$(document).ready( function() {
		pagewidth = $( document ).width();
		$(window).scroll( function() {
			scrolled = $(document).scrollTop();
			if ( pagewidth > 782 ) {
				var percentage = scrolled/($(document).height()-$(window).height())*100;
				var thermometer = percentage / 100 * $('#masthead').width() - 48;
				$('#thermometer').width(thermometer);
				if ( percentage < 1 ) {
					$('#page-status-current-percentage').text('');
				} else {
					$('#page-status-current-percentage').text(parseInt(percentage) + '%');
				}
			}
		} );
	} );
} ) ( jQuery );