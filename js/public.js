jQuery( document ).ready( function( $ ) {
	'use strict';

	// display required asterisks
	$( '.guilro-petitions-petition label.required' ).append( '<span> *</span>');

/*
-------------------------------
	Form submission
-------------------------------
*/
	$( '.guilro-petitions-submit' ).click( function( e ) {
		e.preventDefault();

		var id             = $( this ).attr( 'name' ),
			lang           = $( '#guilro-petitions-lang-' + id ).val(),
			firstname      = $( '#guilro-petitions-first-name-' + id ).val(),
			lastname       = $( '#guilro-petitions-last-name-' + id ).val(),
			email          = $( '#guilro-petitions-email-' + id ).val(),
			email_confirm  = $( '#guilro-petitions-email-confirm-' + id ).val(),
			street         = $( '#guilro-petitions-street-' + id ).val(),
			city           = $( '#guilro-petitions-city-' + id ).val(),
			state          = $( '#guilro-petitions-state-' + id ).val(),
			postcode       = $( '#guilro-petitions-postcode-' + id ).val(),
			country        = $( '#guilro-petitions-country-' + id ).val(),
			custom_field   = $( '#guilro-petitions-custom-field-' + id ).val(),
			custom_message = $( '.guilro-petitions-message-' + id ).val(),
			optin          = '',
			ajaxloader     = $( '#guilro-petitions-ajaxloader-' + id );

		// toggle use of .text() / .val() to read from edited textarea properly on Firefox
		if ( $( '#guilro-petitions-textval-' + id ).val() === 'text' ) {
			custom_message = $( '.guilro-petitions-message-' + id ).text();
		}

		if ( $( '#guilro-petitions-optin-' + id ).attr( 'checked' ) ) {
			optin = 'on';
		}

		// make sure error notices are turned off before checking for new errors
		$( '#guilro-petitions-petition-' + id + ' input' ).removeClass( 'guilro-petitions-error' );

		// validate form values
		var errors = 0,
			emailRegEx = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,6})?$/;

		if ( email_confirm !== undefined ) {
			if ( email_confirm !== email ) {
				$( '#guilro-petitions-email-' + id ).addClass( 'guilro-petitions-error' );
				$( '#guilro-petitions-email-confirm-' + id ).addClass( 'guilro-petitions-error' );
				errors ++;
			}
		}
		if ( email === '' || ! emailRegEx.test( email ) ) {
			$( '#guilro-petitions-email-' + id ).addClass( 'guilro-petitions-error' );
			errors ++;
		}
		if ( firstname === '' ) {
			$( '#guilro-petitions-first-name-' + id ).addClass( 'guilro-petitions-error' );
			errors ++;
		}
		if ( lastname === '' ) {
			$( '#guilro-petitions-last-name-' + id ).addClass( 'guilro-petitions-error' );
			errors ++;
		}

		// if no errors found, submit the data via ajax
		if ( errors === 0 && $( this ).attr( 'rel' ) !== 'disabled' ) {

			// set rel to disabled as flag to block double clicks
			$( this ).attr( 'rel', 'disabled' );

			var data = {
				action:         'guilro_petitions_sendmail',
				id:             id,
				first_name:     firstname,
				last_name:      lastname,
				email:          email,
				street:         street,
				city:           city,
				state:          state,
				postcode:       postcode,
				country:        country,
				custom_field:   custom_field,
				custom_message: custom_message,
				optin:          optin,
				lang:           lang
			};

			// display AJAX loading animation
			ajaxloader.css({ 'visibility' : 'visible'});

			// submit form data and handle ajax response
			$.post( guilro_petitions_js.ajaxurl, data,
				function( response ) {
					var response_class = 'guilro-petitions-response-success';
					if ( response.status === 'error' ) {
						response_class = 'guilro-petitions-response-error';
					}
					$( '#guilro-petitions-petition-' + id + ' .guilro-petitions-petition' ).fadeTo( 400, 0.35 );
					$( '#guilro-petitions-petition-' + id + ' .guilro-petitions-response' ).addClass( response_class );
					$( '#guilro-petitions-petition-' + id + ' .guilro-petitions-response' ).fadeIn().html( response.message );
					ajaxloader.css({ 'visibility' : 'hidden'});
				}, 'json'
			);
		}
	});

	// launch Facebook sharing window
	$( '.guilro-petitions-facebook' ).click( function( e ) {
		e.preventDefault();

		var id           = $( this ).attr( 'rel' ),
			posttitle    = $( '#guilro-petitions-posttitle-' + id ).val(),
			share_url    = document.URL,
			facebook_url = 'http://www.facebook.com/sharer.php?u=' + share_url + '&amp;t=' + posttitle;

		window.open( facebook_url, 'facebook', 'height=400,width=550,left=100,top=100,resizable=yes,location=no,status=no,toolbar=no' );
	});

	// launch Twitter sharing window
	$( '.guilro-petitions-twitter' ).click( function( e ) {
		e.preventDefault();

		var id          = $( this ).attr( 'rel' ),
			tweet       = $( '#guilro-petitions-tweet-' + id ).val(),
			current_url = document.URL,
			share_url   = current_url.split('#')[0],
			twitter_url = 'http://twitter.com/share?url=' + share_url + '&text=' + tweet;

		window.open( twitter_url, 'twitter', 'height=400,width=550,left=100,top=100,resizable=yes,location=no,status=no,toolbar=no' );
	});

/*
-------------------------------
	Petition reader popup
-------------------------------
 */
	$('a.guilro-petitions-readme').click( function( e ) {
		e.preventDefault();

		var id = $( this ).attr( 'rel' ),
			sourceOffset = $(this).offset(),
			sourceTop    = sourceOffset.top - $(window).scrollTop(),
			sourceLeft   = sourceOffset.left - $(window).scrollLeft(),
			screenHeight = $( document ).height(),
			screenWidth  = $( window ).width(),
			windowHeight = $( window ).height(),
			windowWidth  = $( window ).width(),
			readerHeight = 520,
			readerWidth  = 640,
			readerTop    = ( ( windowHeight / 2 ) - ( readerHeight / 2 ) ),
			readerLeft   = ( ( windowWidth / 2 ) - ( readerWidth / 2 ) ),
			petitionText = $( 'div#guilro-petitions-message-' + id ).html(),
			reader       = '<div id="guilro-petitions-reader"><div id="guilro-petitions-reader-close"></div><div id="guilro-petitions-reader-content"></div></div>';

		// set this to toggle use of .val() / .text() so that Firefox  will read from editable-message textarea as expected
		$( '#guilro-petitions-textval-' + id ).val('text');

		// use textarea for editable petition messages
		if ( petitionText === undefined ) {
			petitionText = $( '#guilro-petitions-message-editable-' + id ).html();
		}

		$( '#guilro-petitions-windowshade' ).css( {
				'width'  : screenWidth,
				'height' : screenHeight
			});
			$( '#guilro-petitions-windowshade' ).fadeTo( 500, 0.8 );

		if ( $( '#guilro-petitions-reader' ).length > 0 ) {
			$( '#guilro-petitions-reader' ).remove();
		}

		$( 'body' ).append( reader );

		$('#guilro-petitions-reader').css({
			position   : 'fixed',
			left       : sourceLeft,
			top        : sourceTop,
			zIndex     : 100002
		});

		$('#guilro-petitions-reader').animate({
			width  : readerWidth,
			height : readerHeight,
			top    : readerTop,
			left   : readerLeft
		}, 500, function() {
			$( '#guilro-petitions-reader-content' ).html( petitionText );
		});

		/* Close the pop-up petition reader */
		// by clicking windowshade area
		$( '#guilro-petitions-windowshade' ).click( function () {
			$( this ).fadeOut( 'slow' );
			// write edited text to form - using .text() because target textarea has display: none
			$( '.guilro-petitions-message-' + id ).text( $( '#guilro-petitions-reader textarea' ).val() );
			$( '#guilro-petitions-reader' ).remove();
		});
		// or by clicking the close button
		$( '#guilro-petitions-reader-close' ).live( 'click', function() {
			$( '#guilro-petitions-windowshade' ).fadeOut( 'slow' );
			// write edited text to form - using .text() because target textarea has display: none
			$( '.guilro-petitions-message-' + id ).text( $( '#guilro-petitions-reader textarea' ).val() );
			$( '#guilro-petitions-reader' ).remove();
		});
		// or by pressing ESC
		$( document ).keyup( function( e ) {
			if ( e.keyCode === 27 ) {
				$( '#guilro-petitions-windowshade' ).fadeOut( 'slow' );
				// write edited text to form - using .text() because target textarea has display: none
				$( '.guilro-petitions-message-' + id ).text( $( '#guilro-petitions-reader textarea' ).val() );
				$( '#guilro-petitions-reader' ).remove();
			}
		});

	});

/*
	Toggle form labels depending on input field focus
	Leaving this in for now to support older custom themes
	But it will be removed in future updates
 */

	$( '.guilro-petitions-petition-wrap input[type=text]' ).focus( function( e ) {
		var label = $( this ).siblings( 'label' );
		if ( $( this ).val() === '' ) {
			$( this ).siblings( 'label' ).addClass( 'guilro-petitions-focus' ).removeClass( 'guilro-petitions-blur' );
		}
		$( this ).blur( function(){
			if ( this.value === '' ) {
				label.addClass( 'guilro-petitions-blur' ).removeClass( 'guilro-petitions-focus' );
			}
		}).focus( function() {
			label.addClass( 'guilro-petitions-focus' ).removeClass( 'guilro-petitions-blur' );
		}).keydown( function( e ) {
			label.addClass( 'guilro-petitions-focus' ).removeClass( 'guilro-petitions-blur' );
			$( this ).unbind( e );
		});
	});

	// hide labels on filled input fields when page is reloaded
	$( '.guilro-petitions-petition-wrap input[type=text]' ).each( function() {
		if ( $( this ).val() !== '' ) {
			$( this ).siblings( 'label' ).addClass( 'guilro-petitions-focus' );
		}
	});

});