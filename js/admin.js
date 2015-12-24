jQuery( document ).ready( function( $ ) {
	'use strict';

/* Add New page
------------------------------------------------------------------- */
	$( 'input#requires_confirmation' ).change( function () {
		if ( $( this ).attr( 'checked' ) ) {
			$( 'div.guilro-petitions-returnurl' ).slideDown();
			$( '#guilro-petitions input#return_url' ).focus();
		} else {
			$( 'div.guilro-petitions-returnurl' ).slideUp();
		}
	});

	// open or close signature goal settings
	$( 'input#has_goal' ).change( function () {
		if ( $( this ).attr( 'checked' ) ) {
			$( 'div.guilro-petitions-goal' ).slideDown();
			$( '#guilro-petitions input#goal' ).focus();
		} else {
			$( 'div.guilro-petitions-goal' ).slideUp();
		}
	});

	// open or close expiration date settings
	$( 'input#expires' ).change( function () {
		if ( $( this ).attr( 'checked' ) ) {
			$( 'div.guilro-petitions-date' ).slideDown();
		} else {
			$( 'div.guilro-petitions-date' ).slideUp();
		}
	});

	// open or close address fields settings
	$( 'input#display-address' ).change( function () {
		if ( $( this ).attr( 'checked' ) ) {
			$( 'div.guilro-petitions-address' ).slideDown();
		} else {
			$( 'div.guilro-petitions-address' ).slideUp();
		}
	});

	// open or close custom field settings
	$( 'input#displays-custom-field' ).change( function () {
		if ( $( this ).attr( 'checked' ) ) {
			$( 'div.guilro-petitions-custom-field' ).slideDown();
			$( '#guilro-petitions input#custom-field-label' ).focus();
		} else {
			$( 'div.guilro-petitions-custom-field' ).slideUp();
		}
	});

	// open or close email opt-in settings
	$( 'input#displays-optin' ).change( function () {
		if ( $( this ).attr( 'checked' ) ) {
			$( 'div.guilro-petitions-optin' ).slideDown();
			$( '#guilro-petitions input#optin-label' ).focus();
		} else {
			$( 'div.guilro-petitions-optin' ).slideUp();
		}
	});

	// open or close email header settings
	if ( $( 'input#sends_email' ).attr( 'checked' ) ) {
		$( 'div.guilro-petitions-email-headers' ).hide();
	}
	$( 'input#sends_email' ).change( function () {
		if ( $( this ).attr( 'checked' ) ) {
			$( 'div.guilro-petitions-email-headers' ).slideUp();
		} else {
			$( 'div.guilro-petitions-email-headers' ).slideDown();
		}
	});

	// auto-focus the title field on add/edit petitions form if empty
	if ( $( '#guilro-petitions input#title' ).val() === '' ) {
		$( '#guilro-petitions input#title' ).focus();
	}

	// validate form values before submitting
	$( '#guilro_petitions_submit' ).click( function() {

		$( '.guilro-petitions-error' ).removeClass( 'guilro-petitions-error' );

		var errors     = 0,
			emailRegEx = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,6})?$/,
			email      = $( '#guilro-petitions-edit-petition #target_email' ).val(),
			subject    = $( '#guilro-petitions-edit-petition #email_subject' ).val(),
			message    = $( '#guilro-petitions-edit-petition #petition_message' ).val(),
			goal       = $( '#guilro-petitions-edit-petition #goal' ).val(),
			day        = $( '#guilro-petitions-edit-petition #day' ).val(),
			year       = $( '#guilro-petitions-edit-petition #year' ).val(),
			hour       = $( '#guilro-petitions-edit-petition #hour' ).val(),
			minutes    = $( '#guilro-petitions-edit-petition #minutes' ).val();

		// if "Do not send email (only collect signatures)" checkbox is not checked
		if ( !$( 'input#sends_email' ).attr( 'checked' ) ) {
			// remove any spaces
			var emails = email.split( ',' );
			for ( var i=0; i < emails.length; i++ ) {
				if ( emails[i].trim() === '' || !emailRegEx.test( emails[i].trim() ) ) { // must include valid email address
					$( '#guilro-petitions-edit-petition #target_email' ).addClass( 'guilro-petitions-error' );
					errors ++;
				}
			}
			
			if ( subject === '' ) { // must include subject
				$( '#guilro-petitions-edit-petition #email_subject' ).addClass( 'guilro-petitions-error' );
				errors ++;
			}
		}
		if ( message === '' ) { // must include petition message
			$( '#guilro-petitions-edit-petition #petition_message' ).addClass( 'guilro-petitions-error' );
			errors ++;
		}

		// if "Set signature goal" checkbox is checked
		if ( $( 'input#has_goal' ).attr( 'checked' ) ) {
			if ( isNaN( goal ) ) { // only numbers are allowed
				$( '#guilro-petitions-edit-petition #goal' ).addClass( 'guilro-petitions-error' );
				errors ++;
			}
		}

		// if "Set expiration date" checkbox is checked
		if ( $( 'input#expires' ).attr( 'checked' ) ) {
			if ( isNaN( day ) ) { // only numbers are allowed
				$( '#guilro-petitions-edit-petition #day' ).addClass( 'guilro-petitions-error' );
				errors ++;
			}
			if ( isNaN( year ) ) { // only numbers are allowed
				$( '#guilro-petitions-edit-petition #year' ).addClass( 'guilro-petitions-error' );
				errors ++;
			}
			if ( isNaN( hour ) ) { // only numbers are allowed
				$( '#guilro-petitions-edit-petition #hour' ).addClass( 'guilro-petitions-error' );
				errors ++;
			}
			if ( isNaN( minutes ) ) { // only numbers are allowed
				$( '#guilro-petitions-edit-petition #minutes' ).addClass( 'guilro-petitions-error' );
				errors ++;
			}
		}

		// if no errors found, submit the form
		if ( errors === 0 ) {

			// uncheck all address fields if "Display address fields" is not checked
			if ( ! $( 'input#display-address' ).attr( 'checked' ) ) {
				$( '#street' ).removeAttr( 'checked' );
				$( '#city' ).removeAttr( 'checked' );
				$( '#state' ).removeAttr( 'checked' );
				$( '#postcode' ).removeAttr( 'checked' );
				$( '#country' ).removeAttr( 'checked' );
			}

			$( 'form#guilro-petitions-edit-petition' ).submit();
		}
		else {
			$( '.guilro-petitions-error-msg' ).fadeIn();
		}

		return false;

	});

	// display character count for for Twitter Message field
	// max characters is 120 to accomodate the shortnened URL provided by Twitter when submitted
	function dkSpeakoutTwitterCount() {
		var max_characters = 120;
		var text = $( '#twitter_message' ).val();
		var charcter_count = text.length;
		var charcters_left = max_characters - charcter_count;

		if ( charcter_count <= max_characters ) {
			$( '#twitter-counter' ).html( charcters_left ).css( 'color', '#000' );
		}
		else {
			$( '#twitter-counter' ).html( charcters_left ).css( 'color', '#c00' );
		}
	}
	if ( $( '#twitter_message' ).length > 0 ) {
		dkSpeakoutTwitterCount();
	}
	$( '#twitter_message' ).keyup( function() {
		dkSpeakoutTwitterCount();
	});

/* Petitions page
------------------------------------------------------------------- */
	// display confirmation box when user tries to delete a petition
	// warns that all signatures associated with the petition will also be deleted
	$( '.guilro-petitions-delete-petition' ).click( function( e ) {
		e.preventDefault();

		var delete_link = $( this ).attr( 'href' );
		// confirmation message is contained in a hidden div in the HTML
		// so that it is accessible to PHP translation methods
		var confirm_message = $( '#guilro-petitions-delete-confirmation' ).html();
		// add new line characters for nicer confirm msg display
		confirm_message = confirm_message.replace( '? ', '?\n\n' );
		// display confirmation box
		var confirm_delete = confirm( confirm_message );
		// if user presses OK, process delete link
		if ( confirm_delete === true ) {
			document.location = delete_link;
		}
	});

/* Signatures page
------------------------------------------------------------------- */
	// Select box navigation on Signatures page
	// to switch between different petitions
	$('#guilro-petitions-switch-petition').change( function() {
		var page    = 'guilro_petitions_signatures',
			action  = 'petition',
			pid     = $('#guilro-petitions-switch-petition option:selected').val(),
			baseurl = String( document.location ).split( '?' ),
			newurl  = baseurl[0] + '?page=' + page + '&action=' + action + '&pid=' + pid;
		document.location = newurl;
	});

	// display confirmation box when user tries to re-send confirmation emails
	// warns that a bunch of emails will be sent out if they hit OK
	$( 'a#guilro-petitions-reconfirm' ).click( function( e ) {
		e.preventDefault();

		var link = $( this ).attr( 'href' );
		// confirmation message is contained in a hidden div in the HTML
		// so that it is accessible to PHP translation methods
		var confirm_message = $( '#guilro-petitions-reconfirm-confirmation' ).html();
		// add new line characters for nicer confirm msg display
		confirm_message = confirm_message.replace( '? ', '?\n\n' );
		// display confirm box
		var confirm_delete = confirm( confirm_message );
		// if user presses OK, process delete link
		if ( confirm_delete === true ) {
			document.location = link;
		}
	});

	// stripe the table rows
	$( 'tr.guilro-petitions-tablerow:even' ).addClass( 'guilro-petitions-tablerow-even' );

/* Pagination for Signatures and Petitions pages
------------------------------------------------------------------- */
	// when new page number is entered using the form on paginated admin pages,
	// construct a new url string to pass along the variables needed to update page
	// and redirect to the new url
	$( '#guilro-petitions-pager' ).submit( function() {
		var page        = $( '#guilro-petitions-page' ).val(),
			paged       = $( '#guilro-petitions-paged' ).val(),
			total_pages = $( '#guilro-petitions-total-pages' ).val(),
			baseurl     = String( document.location ).split( '?' ),
			newurl      = baseurl[0] + '?page=' + page + '&paged=' + paged + '&total_pages=' + total_pages;
		document.location = newurl;
		return false;
	});

/* Settings page
------------------------------------------------------------------- */
	// make the correct tab active on page load
	var currentTab = $( 'input#guilro-petitions-tab' ).val();
	$( '#' + currentTab ).show();
	$( 'ul#guilro-petitions-tabbar li a.' + currentTab ).addClass( 'guilro-petitions-active' );

	// switch tabs when they are clicked
	$( 'ul#guilro-petitions-tabbar li a' ).click( function( e ) {
		e.preventDefault();

		// tab bar display
		$( 'ul#guilro-petitions-tabbar li a' ).removeClass( 'guilro-petitions-active' );
		$( this ).addClass( 'guilro-petitions-active' );

		// content sections display
		$( '.guilro-petitions-tabcontent' ).hide();

		var newTab = $( this ).attr( 'rel' );
		$( 'input#guilro-petitions-tab' ).val( newTab );

		$( '#' + newTab ).show();
	});

});