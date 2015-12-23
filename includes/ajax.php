<?php

/**
 * Handle public petition form submissions.
 */
add_action('wp_ajax_guilro_petitions_sendmail', 'guilro_petitions_sendmail');
add_action('wp_ajax_nopriv_guilro_petitions_sendmail', 'guilro_petitions_sendmail');
function guilro_petitions_sendmail()
{

    // set WPML language
    global $sitepress;
    $lang = isset($_POST['lang']) ? $_POST['lang'] : '';
    if (isset($sitepress)) {
        $sitepress->switch_lang($lang, true);
    }

    include_once 'class.signature.php';
    include_once 'class.petition.php';
    include_once 'class.mail.php';
    include_once 'class.wpml.php';
    $the_signature = new guilro_petitions_Signature();
    $the_petition = new guilro_petitions_Petition();
    $wpml = new guilro_petitions_WPML();
    $options = get_option('guilro_petitions_options');

    // clean posted signature fields
    $the_signature->poppulate_from_post();

    // get petition data
    $the_petition->retrieve($the_signature->petitions_id);
    $wpml->translate_petition($the_petition);
    $options = $wpml->translate_options($options);

    // check if submitted email address is already in use for this petition
    if ($the_signature->has_unique_email($the_signature->email, $the_signature->petitions_id)) {

        // handle custom petition messages
        $original_message = str_replace("\r", '', $the_petition->petition_message);
        if ($the_petition->is_editable && $the_signature->submitted_message != $original_message) {
            $the_signature->custom_message = trim($the_signature->submitted_message);
        }

        // does petition require email confirmation?
        if ($the_petition->requires_confirmation) {
            $the_signature->is_confirmed = 0;
            $the_signature->create_confirmation_code();
            guilro_petitions_Mail::send_confirmation($the_petition, $the_signature, $options);
        } else {
            if ($the_petition->sends_email) {
                guilro_petitions_Mail::send_petition($the_petition, $the_signature);
            }
        }

        // add signature to database
        $the_signature->create($the_signature->petitions_id);

        // display success message
        $success_message = $options['success_message'];
        $success_message = str_replace('%first_name%', $the_signature->first_name, $success_message);
        $success_message = str_replace('%last_name%', $the_signature->last_name, $success_message);

        $json_response = array(
            'status' => 'success',
            'message' => $success_message,
        );
        $json_response = json_encode($json_response);

        echo $json_response;
    } else {
        $json_response = array(
            'status' => 'error',
            'message' => $options['already_signed_message'],
        );
        $json_response = json_encode($json_response);

        echo $json_response;
    }

    // end AJAX processing
    die();
}

add_action('wp_ajax_guilro_petitions_paginate_signaturelist', 'guilro_petitions_paginate_signaturelist');
add_action('wp_ajax_nopriv_guilro_petitions_paginate_signaturelist', 'guilro_petitions_paginate_signaturelist');
function guilro_petitions_paginate_signaturelist()
{
    include_once 'class.signaturelist.php';
    $list = new guilro_petitions_Signaturelist();
    $table = $list->table($_POST['id'], $_POST['start'], $_POST['limit'], 'ajax', $_POST['dateformat']);
    echo $table;
    // end AJAX processing
    die();
}
