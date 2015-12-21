<?php

// contextual help to Add New page
function guilro_petitions_help_addnew() {
	$tab_petitions = '
		<p><strong>' . __( "Title", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the title of your petition, which will appear at the top of the petition form.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Do not send email (only collect signatures)", "guilro_petitions" ) . '</strong>&mdash;' . __( "Use this option if do not wish to send petition emails to a target address.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Target Email", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the email address to which the petition will be sent. You may enter multiple email addresses, separated by commas.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Email Subject", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the subject of your petition email.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Greeting", "guilro_petitions" ) . '</strong>&mdash;' . __( "Include a greeting to the recipient of your petition, such as \"Dear Sir,\" which will appear as the first line of the email.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Petition Message", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the content of your petition email.", "guilro_petitions" ) . '</p>
	';
	$tab_twitter_message = '
		<p><strong>' . __( "Twitter Message", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter a prepared tweet that will be presented to users when the Twitter button is clicked.", "guilro_petitions" ) . '</p>
	';
	$tab_petition_options = '
		<p><strong>' . __( "Confirm signatures", "guilro_petitions" ) . '</strong>&mdash;' . __( "Use this option to cause an email to be sent to the signers of your petition. This email contains a special link must be clicked to confirm the signer's email address. Petition emails will not be sent until the signature is confirmed.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Allow custom messages", "guilro_petitions" ) . '</strong>&mdash;' . __( "Check this option to allow signatories to customize the text of their petition email.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Set signature goal", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the number of signatures you hope to collect. This number is used to calculate the progress bar display.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Set expiration date", "guilro_petitions" ) . '</strong>&mdash;' . __( "Use this option to stop collecting signatures on a specific date.", "guilro_petitions" ) . '</p>
	';
	$tab_display_options = '
		<p><strong>' . __( "Display address fields", "guilro_petitions" ) . '</strong>&mdash;' . __( "Select the address fields to display in the petition form.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Display custom field", "guilro_petitions" ) . '</strong>&mdash;' . __( "Add a custom field to the petition form for collecting additional data.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Display opt-in checkbox", "guilro_petitions" ) . '</strong>&mdash;' . __( "Include a checkbox that allows users to consent to receiving further email.", "guilro_petitions" ) . '</p>
	';

	// create the tabs
	$screen = get_current_screen();

	$screen->add_help_tab( array (
		'id'      => 'guilro_petitions_help_petition',
		'title'   => __( "Petition", "guilro_petitions" ),
		'content' => $tab_petitions
	));
	$screen->add_help_tab( array (
		'id'      => 'guilro_petitions_help_twitter_message',
		'title'   => __( "Twitter Message", "guilro_petitions" ),
		'content' => $tab_twitter_message
	));
	$screen->add_help_tab( array (
		'id'      => 'guilro_petitions_help_petition_options',
		'title'   => __( "Petition Options", "guilro_petitions" ),
		'content' => $tab_petition_options
	));
	$screen->add_help_tab( array (
		'id'      => 'guilro_petitions_help_display_options',
		'title'   => __( "Display Options", "guilro_petitions" ),
		'content' => $tab_display_options
	));
}

// contextual help for Settings page
function guilro_petitions_help_settings() {
	$tab_petition_form = '
		<p>' . __( "These settings control the display of the [emailpetition] shortcode and sidebar widget.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Petition Theme", "guilro_petitions" ) . '</strong>&mdash;' . __( "Select a CSS theme that will control the appearance of petition forms.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Widget Theme", "guilro_petitions" ) . '</strong>&mdash;' . __( "Select a CSS theme that will control the appearance of petition widgets.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Submit Button Text", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the text that displays in the orange submit button on petition forms.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Success Message", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the text that appears when a user successfully signs your petition with a unique email address.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Share Message", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the text that appears above the Twitter and Facebook buttons after the petition form has been submitted.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Expiration Message", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the text to display in place of the petition form when a petition is past its expiration date.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Already Signed Message", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the text to display when a petition is signed using an email address that has already been submitted.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Opt-in Default", "guilro_petitions" ) . '</strong>&mdash;' . __( "Choose whether the opt-in checkbox is checked or unchecked by default.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Display signature count", "guilro_petitions" ) . '</strong>&mdash;' . __( "Choose whether you wish to display the number of signatures that have been collected.", "guilro_petitions" ) . '</p>
	';
	$tab_confirmation_emails = '
		<p>' . __( "These settings control the content of the confirmation emails.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Email From", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the email address associated with your website. Confirmation emails will be sent from this address.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Email Subject", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the subject of the confirmation email.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Email Message", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the content of the confirmation email.", "guilro_petitions" ) . '</p>
	';
	$tab_signature_list = '
		<p>' . __( "These settings control the display of the [signaturelist] shortcode.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Title", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the text that appears above the signature list.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Theme", "guilro_petitions" ) . '</strong>&mdash;' . __( "Select a CSS theme that will control the appearance of signature lists.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Rows", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the number of signatures that will be displayed in the signature list.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Columns", "guilro_petitions" ) . '</strong>&mdash;' . __( "Select the columns that will appear in the signature list.", "guilro_petitions" ) . '</p>
	';
	$tab_admin_display = '
		<p>' . __( "These settings control the look of the plugin's options pages within the WordPress administrator.", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Petitions table shows", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the number of rows to display in the \"Email Petitions\" table", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "Signatures table shows", "guilro_petitions" ) . '</strong>&mdash;' . __( "Enter the number of rows to display in the \"Signatures\" table", "guilro_petitions" ) . '</p>
		<p><strong>' . __( "CSV file includes", "guilro_petitions" ) . '</strong>&mdash;' . __( "Select the subset of signatures that will be included in CSV file downloads", "guilro_petitions" ) . '</p>
	';

	// create the tabs
	$screen = get_current_screen();

	$screen->add_help_tab( array (
		'id'      => 'guilro_petitions_help_petition_form',
		'title'   => __( "Petition Form", "guilro_petitions" ),
		'content' => $tab_petition_form
	));
	$screen->add_help_tab( array (
		'id'      => 'guilro_petitions_help_signature_list',
		'title'   => __( "Signature List", "guilro_petitions" ),
		'content' => $tab_signature_list
	));
	$screen->add_help_tab( array (
		'id'      => 'guilro_petitions_help_confirmation_emails',
		'title'   => __( "Confirmation Emails", "guilro_petitions" ),
		'content' => $tab_confirmation_emails
	));
	$screen->add_help_tab( array (
		'id'      => 'guilro_petitions_help_admin_display',
		'title'   => __( "Admin Display", "guilro_petitions" ),
		'content' => $tab_admin_display
	));
}
?>