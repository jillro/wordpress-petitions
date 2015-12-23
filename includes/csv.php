<?php

// generate CSV file for download
if (isset($_REQUEST['csv']) && $_REQUEST['csv'] == 'signatures') {
    // make sure it executes before headers are sent
    add_action('admin_menu', 'guilro_petitions_signatures_csv');
    function guilro_petitions_signatures_csv()
    {
        // check security: ensure user has authority and intention
        if (!current_user_can('publish_posts')) {
            wp_die(__('Insufficient privileges: You need to be an editor to do that.', 'guilro_petitions'));
        }
        check_admin_referer('guilro_petitions-download_signatures');

        include_once 'class.signature.php';
        $signatures = new guilro_petitions_Signature();

        $petition_id = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : ''; // petition id

        // retrieve signatures from the database
        $csv_data = $signatures->all($petition_id, 0, 0, 'csv');

        // display error message if query returns no results
        if (count($csv_data) < 1) {
            echo '<h1>'.__('No signatures found.', 'guilro_petitions').'</h1>';
            die();
        }

        // construct CSV filename
        $counter = 0;
        foreach ($csv_data as $file) {
            if ($counter < 1) {
                $filename_title = stripslashes(str_replace(' ', '-', $file->title));
                $filename_date = date('Y-m-d', strtotime(current_time('mysql', 0)));
                $filename = $filename_title.'_'.$filename_date.'.csv';
            }
            ++$counter;
        }

        // set up CSV file headers
        header('Content-Type: text/octet-stream; charset=UTF-8');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Pragma: public'); // supposed to make stuff work over https

        // get the column headers translated
        $firstname = __('First Name', 'guilro_petitions');
        $lastname = __('Last Name', 'guilro_petitions');
        $email = __('Email Address', 'guilro_petitions');
        $street = __('Street Address', 'guilro_petitions');
        $city = __('City', 'guilro_petitions');
        $state = __('State', 'guilro_petitions');
        $postcode = __('Post Code', 'guilro_petitions');
        $country = __('Country', 'guilro_petitions');
        $date = __('Date Signed', 'guilro_petitions');
        $confirmed = __('Confirmed', 'guilro_petitions');
        $petition_title = __('Petition Title', 'guilro_petitions');
        $petitions_id = __('Petition ID', 'guilro_petitions');
        $email_optin = __('Email Opt-in', 'guilro_petitions');
        $custom_message = __('Custom Message', 'guilro_petitions');
        $language = __('Language', 'guilro_petitions');

        // If set, use the custom field label as column header instead of "Custom Field"
        $counter = 0;
        foreach ($csv_data as $label) {
            if ($counter < 1) {
                if ($label->custom_field_label != '') {
                    $custom_field_label = stripslashes($label->custom_field_label);
                } else {
                    $custom_field_label = __('Custom Field', 'guilro_petitions');
                }
            }
            ++$counter;
        }

        // construct CSV file header row
        // must use double quotes and separate with tabs
        $csv = "$firstname	$lastname	$email	$street	$city	$state	$postcode	$country	$custom_field_label	$date	$confirmed	$petition_title	$petitions_id	$email_optin	$custom_message	$language";
        $csv .= "\n";

        // construct CSV file data rows
        foreach ($csv_data as $signature) {
            // convert the 1, 0, or '' values of confirmed to readable format
            $confirm = $signature->is_confirmed;
            if ($confirm == 1) {
                $confirm = __('confirmed', 'guilro_petitions');
            } elseif ($confirm == 0) {
                $confirm = __('unconfirmed', 'guilro_petitions');
            } else {
                $confirm = '...';
            }
            // convert the 1, 0, or '' values of optin to readable format
            $optin = $signature->optin;
            if ($optin == 1) {
                $optin = __('yes', 'guilro_petitions');
            } elseif ($optin == 0) {
                $optin = __('no', 'guilro_petitions');
            } else {
                $optin = '...';
            }
            $csv .=  stripslashes('"'.$signature->first_name.'"	"'.$signature->last_name.'"	"'.$signature->email.'"	"'.$signature->street_address.'"	"'.$signature->city.'"	"'.$signature->state.'"	"'.$signature->postcode.'"	"'.$signature->country.'"	"'.$signature->custom_field.'"	"'.$signature->date.'"	"'.$confirm.'"	"'.$signature->title.'"	"'.$signature->petitions_id.'"	"'.$optin.'"	"'.$signature->custom_message.'"	"'.$signature->language.'"');
            $csv .= "\n";
        }

        // output CSV file in a UTF-8 format that Excel can understand
        echo chr(255).chr(254).mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8');
        exit;
    }
}
