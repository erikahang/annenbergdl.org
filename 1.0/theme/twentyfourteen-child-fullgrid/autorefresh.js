// Silently refresh the page via JS.
(function( wp, $ ){
	var timer;
	var duration = 3600; // 1 minute
	
	function startTimer() {
		this.timer = setTimeout( 'refresh', this.duration );
	}
	
	function refresh() {
		var request;
		request = $.ajax( window.location, {
			type: 'POST',
			data: {}
		} );

		request.fail( function() {
			// Try again at the next interval
			startTimer();
		});

		request.done( function( response ) {

			$('html').html(response);
/*			// Create the iframe and inject the html content.
			var body = $('body');
			var html = $('html');
			var iframe = $('<iframe />').appendTo( body );

			// Bind load event after the iframe has been added to the page;
			// otherwise it will fire when injected into the DOM.
			iframe.one( 'load', function() {
				html.html(  )

			});

			self.targetWindow( self.iframe[0].contentWindow );

			self.targetWindow().document.open();
			self.targetWindow().document.write( response );
			self.targetWindow().document.close();
*/		});
	}

	$(document).ready(function(){ startTimer(); });

})( window.wp, jQuery );