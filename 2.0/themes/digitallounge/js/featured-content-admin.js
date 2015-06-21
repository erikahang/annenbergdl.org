/**
 * Featured Content admin behavior: add a tag suggestion
 * when changing the tag.
 */
/* global ajaxurl:true */

jQuery( document ).ready( function( $ ) {
	$( '#customize-control-featured_tutorial_tags input' ).suggest( ajaxurl + '?action=ajax-tag-search&tax=tutorial_tag', { delay: 250, minchars: 1, multiple: true } );
});
