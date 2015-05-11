( function( $, wp ) {
	var template, allStaff, loadedIds, currentId, container;
	
	$(document).ready( function() {
		bindEvents();
		allStaff = _anndlAllStaff;
		container = $( '.staff-members' );
	} );
	
	function bindEvents() {
		loadedIds = new Array();
		template = wp.template( 'single-staff-view' );
		$( '#primary' ).on( 'click keydown', '.staff-summary', function( e ) {
			e.preventDefault();
			var id = $( this ).data( 'staff-id' );
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
		container.addClass( 'open' );
		container.find( '.staff-member' ).removeClass( 'current' );
		container.find( '#staff-member-' + id ).addClass( 'current' );
	}

} ) ( jQuery, wp );
