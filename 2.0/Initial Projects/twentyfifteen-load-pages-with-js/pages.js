( function( $, wp ) {
	var template, region;
	
	$(document).ready( function() {
		bindEvents();
	} );
	
	function bindEvents() {
		template = wp.template( 'page-content-template' );
		region = $( '#primary' );
		$( '#site-navigation' ).on( 'click keydown', '.menu-item-object-page a', function( e ) {
			e.preventDefault();
			var id = this.closest( 'li' ).className;
			id = id.split( ' ' );
			id = id[(id.length - 1)].replace( 'page-id-', '' );
			loadPage( id );
		});
	}
	
	function loadPage( id ) {
		region.fadeOut( 'slow' );
		$.ajax({
		    url: 'http://localhost/develop/src/wp-json/posts/' + id,
		    dataType: 'json',
		    type: 'GET',
		    success: function( data ) {
				renderPage( data );
		    },
		    error: function() {
		        alert( 'Failed to load requested page.' );
		    }
		});	
	}
	
	function renderPage( data ) {
		region.html( template( data ) ).fadeIn( 'fast' ).scrollTop( 300 );
		// @todo update doc title and url
	}
	
} ) ( jQuery, wp );
