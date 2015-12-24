jQuery( document ).ready( function( $ ) {
	'use strict';

	// next pagination button is clicked
	$( '.guilro-petitions-signaturelist-next' ).click( function( e ) {
		e.preventDefault();
		get_signaturelist( $( this ) );
	});

	// prev pagination button is clicked
	$( '.guilro-petitions-signaturelist-prev' ).click( function( e ) {
		e.preventDefault();
		get_signaturelist( $( this ) );
	});

	// pagination: query new signatures and display results
	function get_signaturelist( button, link ) {
		// change button appearance to disabled while ajax request is processing
		$( this ).addClass( 'guilro-petitions-signaturelist-disabled' );
		
		var link   = button.attr( 'rel' ).split( ',' ),
			id     = link[0],
			start  = link[1],
			limit  = link[2],
			total  = link[3],
			status = link[4],
			ajax   = {
				action: 'guilro_petitions_paginate_signaturelist',
				id:         id,
				start:      start,
				limit:      limit,
				dateformat: guilro_petitions_signaturelist_js.dateformat
			};

		if ( status === '1' ) {
			// submit data and handle ajax response
			$.post( guilro_petitions_signaturelist_js.ajaxurl, ajax,
				function( response ) {
					var next_link = get_next_link( id, start, limit, total );
					var prev_link = get_prev_link( id, start, limit, total );

					toggle_button_display( id, next_link, prev_link );

					$( '.guilro-petitions-signaturelist-' + id + ' tr:not(:last-child)' ).remove();
					$( '.guilro-petitions-signaturelist-' + id ).prepend( response );
					$( '.guilro-petitions-signaturelist-' + id + ' .guilro-petitions-signaturelist-next' ).attr( 'rel', next_link );
					$( '.guilro-petitions-signaturelist-' + id + ' .guilro-petitions-signaturelist-prev' ).attr( 'rel', prev_link );
				}
			);
		}
	}

	// format new link for next pagination button
	function get_next_link( id, start, limit, total ) {
		var start = parseInt( start ),
			limit = parseInt( limit ),
			total = parseInt( total ),
			new_start = '',
			status    = '',
			link      = '';

		if ( start + limit  < total ) {
			new_start = start + limit;
			status = '1';
		}
		else {
			new_start = total;
			status = '0';
		}

		link = id + ',' + new_start + ',' + limit + ',' + total + ',' + status;
		return link;
	}

	// format new link for prev pagination button
	function get_prev_link( id, start, limit, total ) {
		var start = parseInt( start ),
			limit = parseInt( limit ),
			total = parseInt( total ),
			new_start = '',
			status    = '',
			link      = '';

		if ( start - limit >= 0 ) {
			new_start = start - limit;
			status = '1';
		}
		else {
			new_start = total;
			status = '0';
		}

		link = id + ',' + new_start + ',' + limit + ',' + total + ',' + status;
		return link;
	}

	function toggle_button_display( id, next_link, prev_link ) {
		if ( next_link.split( ',' )[4] === '0' ) {
			$( '.guilro-petitions-signaturelist-' + id + ' .guilro-petitions-signaturelist-next' ).addClass( 'guilro-petitions-signaturelist-disabled' );
		}
		else {
			$( '.guilro-petitions-signaturelist-' + id + ' .guilro-petitions-signaturelist-next' ).removeClass( 'guilro-petitions-signaturelist-disabled' );
		}

		if ( prev_link.split( ',' )[4] === '0' ) {
			$( '.guilro-petitions-signaturelist-' + id + ' .guilro-petitions-signaturelist-prev' ).addClass( 'guilro-petitions-signaturelist-disabled' );
		}
		else {
			$( '.guilro-petitions-signaturelist-' + id + ' .guilro-petitions-signaturelist-prev' ).removeClass( 'guilro-petitions-signaturelist-disabled' );
		}
	}

});