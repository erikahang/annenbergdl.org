( function( $, wp ) {
	var template, allStaff, loadedIds, currentId, container;
	
	$(document).ready( function() {
		bindEvents();
		allStaff = _anndlAllStaff;
		container = $( '.staff-members' );
	} );
	
	function bindEvents() {
		var $primary = $( '#primary' );
		loadedIds = new Array();
		template = wp.template( 'single-staff-view' );
		$primary.on( 'click keydown', '.staff-summary', function( e ) {
			e.preventDefault();
			var id = $( this ).data( 'staff-id' );
			if ( loadedIds.indexOf( id ) > -1 ) {
				showUser( id );
			} else {
				loadUser( id );
			}
		});

		$primary.on( 'click', '.back-to-index', function( e ) {
			container.removeClass( 'open' );
			container.find( '.staff-member' ).removeClass( 'current' );
		});

		$primary.on( 'click', '.previous', function( e ) {
			var id, i = allStaff.indexOf( currentId.toString() );
			if ( i == 0 ) {
				i = allStaff.length - 1;
			} else {
				i = i - 1;
			}
			id = allStaff[i];
			if ( loadedIds.indexOf( id ) > -1 ) {
				showUser( id );
			} else {
				loadUser( id );
			}
		});

		$primary.on( 'click', '.next', function( e ) {
			var id, i = allStaff.indexOf( currentId );
			if ( i == allStaff.length - 1 ) {
				i = 0;
			} else {
				i = i + 1;
			}
			id = allStaff[i];
			if ( loadedIds.indexOf( id ) > -1 ) {
				showUser( id );
			} else {
				loadUser( id );
			}
		});
	}

	function loadUser( id ) {
		var params;
		container.addClass( 'open loading-region' );
		params = {
			'action': 'anndl-load-userdata',
			'user_id': id
		}
		$.post( wp.ajax.settings.url, params, function( data ) {
			data = $.parseJSON( data );
			if ( data ) {
				container.append( template( data ) );
				loadedIds.push( id );
				showUser( id );
			} else {
		        alert( 'Failed to load requested page.' );				
			}
		});
	}
	
	function showUser( id ) {
		currentId = id;
		container.addClass( 'open' );
		container.find( '.staff-member' ).removeClass( 'current' );
		container.find( '#staff-member-' + id ).addClass( 'current' );
	}

} ) ( jQuery, wp );
