jQuery( document ).ready( function( $ ) {
	'use strict';

	// display required asteriscs
	$( '.guilro-petitions-widget-popup-wrap label.required' ).append('<span> *</span>');

	// run only if widget is on the page
	if( $( '.guilro-petitions-widget-wrap' ).length ) {
		$( '.guilro-petitions-widget-button' ).click( function( e ) {
			var petition_form = '#' + $( this ).attr( 'rel' ),
				screenHeight  = $( document ).height(),
				screenWidth   = $( window ).width(),
				windowHeight  = $( window ).height(),
				windowWidth   = $( window ).width();

			$( '#guilro-petitions-widget-windowshade' ).css( {
				'width'  : screenWidth,
				'height' : screenHeight
			});
			$( '#guilro-petitions-widget-windowshade' ).fadeTo( 500, 0.8 );

			// center the pop-up window
			$( petition_form ).css( 'top',  ( ( windowHeight / 2 ) - ( $( petition_form ).height() / 2 ) ) );
			$( petition_form ).css( 'left', ( windowWidth / 2 ) - ( $( petition_form ).width() / 2 ) );

			// display the form
			$( petition_form ).fadeIn( 500 );
		});

		/* Close the pop-up petition form */
		// by clicking windowshade area
		$( '#guilro-petitions-widget-windowshade' ).click( function () {
			$( this ).fadeOut( 'slow' );
			$( '.guilro-petitions-widget-popup-wrap' ).hide();
		});
		// or by clicking the close button
		$( '.guilro-petitions-widget-close' ).click( function() {
			$( '#guilro-petitions-widget-windowshade' ).fadeOut( 'slow' );
			$( '.guilro-petitions-widget-popup-wrap' ).hide();
		});
		// or by pressing ESC
		$( document ).keyup( function( e ) {
			if ( e.keyCode === 27 ) {
				$( '#guilro-petitions-widget-windowshade' ).fadeOut( 'slow' );
				$( '.guilro-petitions-widget-popup-wrap' ).hide();
			}
		});

		// process petition form submissions
		$( '.guilro-petitions-widget-submit' ).click( function( e ) {
			e.preventDefault();

			var id             = $( this ).attr( 'name' ),
				current_url    = document.URL,
				share_url      = $( '#guilro-petitions-widget-shareurl-' + id ).val(),
				posttitle      = $( '#guilro-petitions-widget-posttitle-' + id ).val(),
				tweet          = $( '#guilro-petitions-widget-tweet-' + id ).val(),
				lang           = $( '#guilro-petitions-widget-lang-' + id ).val(),
				firstname      = $( '#guilro-petitions-widget-first-name-' + id ).val(),
				lastname       = $( '#guilro-petitions-widget-last-name-' + id ).val(),
				email          = $( '#guilro-petitions-widget-email-' + id ).val(),
				email_confirm  = $( '#guilro-petitions-widget-email-confirm-' + id ).val(),
				street         = $( '#guilro-petitions-widget-street-' + id ).val(),
				city           = $( '#guilro-petitions-widget-city-' + id ).val(),
				state          = $( '#guilro-petitions-widget-state-' + id ).val(),
				postcode       = $( '#guilro-petitions-widget-postcode-' + id ).val(),
				country        = $( '#guilro-petitions-widget-country-' + id ).val(),
				custom_field   = $( '#guilro-petitions-widget-custom-field-' + id ).val(),
				custom_message = $( 'textarea#guilro-petitions-widget-message-' + id ).val(),
				optin          = '',
				ajaxloader     = $( '#guilro-petitions-widget-ajaxloader-' + id );

			if ( share_url === '' ) {
				share_url = current_url.split('#')[0];
			}

			if ( $( '#guilro-petitions-widget-optin-' + id ).attr( 'checked' ) ) {
				optin = 'on';
			}

			// make sure error notices are turned off before checking for new errors
			$( '#guilro-petitions-widget-popup-wrap-' + id + ' input' ).removeClass( 'guilro-petitions-widget-error' );

			// validate form values
			var errors = 0,
				emailRegEx = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

			if ( email_confirm !== undefined && email_confirm !== email ) {
				if ( email_confirm !== email ) {
					$( '#guilro-petitions-widget-email-' + id ).addClass( 'guilro-petitions-widget-error' );
					$( '#guilro-petitions-widget-email-confirm-' + id ).addClass( 'guilro-petitions-widget-error' );
					errors ++;
				}
			}
			if ( email === '' || !emailRegEx.test( email ) ) {
				$( '#guilro-petitions-widget-email-' + id ).addClass( 'guilro-petitions-widget-error' );
				errors ++;
			}
			if ( firstname === '' ) {
				$( '#guilro-petitions-widget-first-name-' + id ).addClass( 'guilro-petitions-widget-error' );
				errors ++;
			}
			if ( lastname === '' ) {
				$( '#guilro-petitions-widget-last-name-' + id ).addClass( 'guilro-petitions-widget-error' );
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
				$.post( guilro_petitions_widget_js.ajaxurl, data,
					function( response ) {
						var response_class = 'guilro-petitions-widget-response-success';
						if ( response.status === 'error' ) {
							response_class = 'guilro-petitions-widget-response-error';
						}
						$( '#guilro-petitions-widget-popup-wrap-' + id + ' .guilro-petitions-widget-form' ).hide();
						$( '.guilro-petitions-widget-response' ).addClass( response_class );
						$( '#guilro-petitions-widget-popup-wrap-' + id + ' .guilro-petitions-widget-response' ).fadeIn().html( response.message );
						$( '#guilro-petitions-widget-popup-wrap-' + id + ' .guilro-petitions-widget-share' ).fadeIn();

						// launch Facebook sharing window
						$( '.guilro-petitions-widget-facebook' ).click( function() {
							var url = 'http://www.facebook.com/sharer.php?u=' + share_url + '&t=' + posttitle;
							window.open( url, 'facebook', 'height=420,width=550,left=100,top=100,resizable=yes,location=no,status=no,toolbar=no' );
						});
						// launch Twitter sharing window
						$( '.guilro-petitions-widget-twitter' ).click( function() {
							var url = 'http://twitter.com/share?url=' + share_url + '&text=' + tweet;
							window.open( url, 'twitter', 'height=420,width=550,left=100,top=100,resizable=yes,location=no,status=no,toolbar=no' );
							ajaxloader.css({ 'visibility' : 'hidden'});
						});
					}, 'json'
				);
			}
		});

	}

});