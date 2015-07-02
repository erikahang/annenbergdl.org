( function( $, wp ) {
	var template, page, container, args;

	$(document).ready( function() {
		bindEvents();
		container = $( '.query-container' );
		template = wp.template( 'archive-grid-view' );
		args = {
			type: container.data( 'type' )
		};
		switch ( container.data( 'type' ) ) {
			case 'post_type': 
				args.post_type = container.data( 'post_type' );
				break;
			case 'taxonomy': 
				args.taxonomy = container.data( 'taxonomy' );
				args.term = container.data( 'term' );
				break;
			case 'post_type': 
				args.post_type = container.data( 'post_type' );
				break;
			case 'author': 
				args.author_id = container.data( 'author_id' );
				break;
			case 'day':
				args.day = container.data( 'day' );
				args.month = container.data( 'month' );
				args.year = container.data( 'year' );
				break;
			case 'month':
				args.month = container.data( 'month' );
				args.year = container.data( 'year' );
				break;
			case 'year':
				args.year = container.data( 'year' );
				break;
		}
		page = 1;
	} );

	function bindEvents() {
		// todo base on scrolling/visibility of last loaded item
		loadedIds = new Array();
		$( '#primary' ).on( 'click keydown', '.load-more', function( e ) {
			e.preventDefault();
			loadPage();
		});
	}

	function loadPage() {
		var params;
		container.addClass( 'loading' );
		params = {
			'action': 'anndl-load-archive-posts',
			'page': page,
			'args': args
		}
		$.post( wp.ajax.settings.url, params, function( data ) {
			data = $.parseJSON( data );
			if ( data ) {
				$.each( data, function ( id, post ) {
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
