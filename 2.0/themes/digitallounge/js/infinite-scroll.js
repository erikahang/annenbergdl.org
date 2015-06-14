( function( $, wp ) {
	var template, page, container, args;

	$(document).ready( function() {
		bindEvents();
		container = $( '#main' );
		template = wp.template( 'archive-grid-view' );
		args = {};
		args.type = container.data( 'type' ); // todo
		page = 1;
	} );
	
	function bindEvents() {
		// todo
		loadedIds = new Array();
		$( '#primary' ).on( 'click keydown', '.load-more', function( e ) {
			e.preventDefault();
			loadPage( page, args );
		});
	}

	function loadPage( page, args ) {
		var params;
		container.addClass( 'loading' );
		params = {
			'action': 'anndl-load-archive-posts',
			'args': args
		}
		$.post( wp.ajax.settings.url, params, function( data ) {
			data = $.parseJSON( data );
			if ( data ) {
				$.each( data, function ( post ) {
					container.append( template( post ) );
				});
				page = page + 1;
				container.removeClass( 'loading' );
			} else {
		        alert( 'Failed to load requested page.' );				
			}
		});
	}

} ) ( jQuery, wp );
