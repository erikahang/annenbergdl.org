( function( $, wp ) {
	var template = {}, postsPerPage = 6, vWidth, totalItems;

	$(document).ready( function() {
		bindEvents();
//		template['post'] = wp.template( 'archive-post' );
//		template['tutorials'] = wp.template( 'archive-tutorial' );
//		template['default'] = wp.template( 'archive-grid-view' );
		template = wp.template( 'archive-grid-view' );
		vWidth = window.innerWidth;
//		totalItems = 0; @todo
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
				args.post_type = container.data( 'post_type' );
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
			case 'search':
				args.searchterm = container.data( 'searchterm' );
				break;
		}
		return args;
	}

	function bindEvents() {
		var container;
		// todo base on scrolling/visibility of last loaded item
		$( '.query-container' ).on( 'click', '.load-more', function( e ) {
			container = $( this ).closest( '.query-container' );
			loadNextPage( container );
		});
		
		$( '.query-container' ).on( 'click', '.arrow-previous', function( e ) {
			container = $( this ).closest( '.query-container' );
			setPage( container, container.data( 'page' ) - 1 );
		});
		
		$( '.query-container' ).on( 'click', '.arrow-next', function( e ) {
			container = $( this ).closest( '.query-container' );
			loadNextPage( container );
		});
		
	}

	function loadNextPage( container ) {
		var params, args = generateArgs( container );
		if ( container.length > args.page * 6 ) {
			// No need to add more items, change the visible page instead.
			setPage( container, args.page + 1 );
			return;
		}
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
					container.find( '.inner-container' ).append( template( post ) );
				});
				container.removeClass( 'loading' );
				totalItems = totalItems + data.length;
				setPage( container, args.page + 1 );
			} else {
		        alert( 'Failed to load requested page.' );				
			}
		});
	}

	function setPage( container, page ) {
		if ( page < 1 ) {
			return;
		}
		var left = Math.floor( vWidth / 288 ) * 288 * ( page - 1 );
		// Don't switch to an empty page.
		if ( 288 * totalItems < left ) {
			return;
		}
		container.find( '.inner-container' ).css( 'left', left * -1 );
		container.data( 'page', page );
	}
} ) ( jQuery, wp );
