( function( $, wp ) {
	var template = {}, page = {};

	$(document).ready( function() {
		bindEvents();
//		template['post'] = wp.template( 'archive-post' );
//		template['tutorials'] = wp.template( 'archive-tutorial' );
//		template['default'] = wp.template( 'archive-grid-view' );
		template = wp.template( 'archive-grid-view' );
	});

	function generateArgs( container ) {
		var args = {
			type: container.data( 'type' ),
			page: container.data( 'page' )
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
		return args;
	}

	function bindEvents() {
		// todo base on scrolling/visibility of last loaded item
		loadedIds = new Array();
		$( '#primary' ).on( 'click keydown', '.load-more', function( e ) {
		container = $( this ).closest( '.query-container' );
			container = $( this ).closest( '.query-container' );
			loadPage();
		});
		
		$( '.query-container' ).on( 'click keydown', '.prev', function( e ) {
			container = $( this ).closest( '.query-container' );
			loadPage( container );
			// @todo previous/next nav and history
		});
		
		$( '.query-container' ).on( 'click keydown', '.next', function( e ) {
			container = $( this ).closest( '.query-container' );
			loadPage( container );
			// @todo previous/next nav and history
		});
		
	}

	function loadPage( container ) {
		var params, args = generateArgs( container );
		container.addClass( 'loading' );
		params = {
			'action': 'anndl-load-archive-posts',
			'page': args.page + 1,
			'args': args
		}
		$.post( wp.ajax.settings.url, params, function( data ) {
			data = $.parseJSON( data );
			if ( data ) {
				var type = 'default';
				if ( typeof args.post_type !== undefined ) {
					type = args.post_type;
				}
				$.each( data, function ( id, post ) {
					///container.append( template[type]( post ) );
					container.append( template( post ) );
				});
				container.data( 'page', args.page + 1 );
				container.removeClass( 'loading' );
			} else {
		        alert( 'Failed to load requested page.' );				
			}
		});
	}

} ) ( jQuery, wp );
