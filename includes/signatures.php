<?php

global $guilro_petitions_settings;

function guilro_petitions_signatures_page()
{
    // check security: ensure user has authority
    if (!current_user_can('publish_posts')) {
        wp_die(__('Insufficient privileges: You need to be an editor to do that.', 'guilro_petitions'));
    }

    include_once 'class.speakout.php';
    include_once 'class.signature.php';
    include_once 'class.petition.php';
    $the_signatures = new guilro_petitions_Signature();
    $the_petitions = new guilro_petitions_Petition();
    $options = $guilro_petitions_settings->getAll();

    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    $pid = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : ''; // petition id
    $sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : ''; // signature id

    // set variables for paged record display and for limit values in db query
    $paged = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : '1';
    $total_pages = isset($_REQUEST['total_pages']) ? $_REQUEST['total_pages'] : '1';
    $current_page = guilro_petitions_SpeakOut::current_paged($paged, $total_pages);
    $query_limit = $options['signatures_rows'];
    $query_start = ($current_page * $query_limit) - $query_limit;

    switch ($action) {
        case 'delete' :
            // security: ensure user has intention
            check_admin_referer('guilro_petitions-delete_signature'.$sid);

            // delete signature from the database
            $the_signatures->delete($sid);

            // count number of signatures in database
            $count = $the_signatures->count($pid);

            // get all signatures for display
            $signatures = $the_signatures->all($pid, $query_start, $query_limit);

            // set up display strings
            // set up values for the table label ie: All Signatures (36)
            if ($count == 0) {
                $petition = '';
            } elseif ($pid != '') {
                $petition = $signatures[0]->title;
            } else {
                $petition = __('All Signatures', 'guilro_petitions');
            }
            $table_label = esc_html($petition).' <span class="count">('.$count.')</span>';
            $base_url = site_url('wp-admin/admin.php?page=guilro_petitions_signatures&action=petition&pid='.$pid);
            $message_update = __('Signature deleted.', 'guilro_petitions');

        break;
        case 'petition' :
            // count number of signatures in database
            $count = $the_signatures->count($pid);

            // get all signatures for display
            $signatures = $the_signatures->all($pid, $query_start, $query_limit);

            // TODO: Make this always show petition title (maybe use join in query)
            // set up display strings
            // if signatures exist, show petition title, else show petition id number
            if (count($signatures) > 0) {
                $table_label = esc_html($signatures[0]->title).' <span class="count">('.$count.')</span>';
            } else {
                $table_label = __('Petition', 'guilro_petitions').' '.$pid.' <span class="count">(0)</span>';
            }
            $base_url = site_url('wp-admin/admin.php?page=guilro_petitions_signatures&action=petition&pid='.$pid);
            $message_update = '';
        break;
        case 'reconfirm' :
            check_admin_referer('guilro_petitions-resend_confirmations'.$pid);

            include_once 'class.mail.php';
            $petition_to_confirm = new guilro_petitions_Petition();

            // get unconfirmed signatures
            $unconfirmed = $the_signatures->unconfirmed($pid);

            foreach ($unconfirmed as $signature) {
                $unconfirmed_signature = new guilro_petitions_signature();
                $unconfirmed_signature->first_name = $signature->first_name;
                $unconfirmed_signature->last_name = $signature->last_name;
                $unconfirmed_signature->email = $signature->email;
                $unconfirmed_signature->confirmation_code = $signature->confirmation_code;
                guilro_petitions_Mail::send_confirmation($petition_to_confirm, $unconfirmed_signature, $options);

                // destroy temporary object so we can re-use the variable
                unset($unconfirmed_signature);
            }

            // count number of signatures in database
            $count = $the_signatures->count($pid);

            // get all signatures for display
            $signatures = $the_signatures->all($pid, $query_start, $query_limit);

            // set up display strings
            if (count($signatures) > 0) {
                $table_label = esc_html($signatures[0]->title).' <span class="count">('.$count.')</span>';
            } else {
                $table_label = __('Petition', 'guilro_petitions').' '.$pid.' <span class="count">(0)</span>';
            }
            $base_url = site_url('wp-admin/admin.php?page=guilro_petitions_signatures&action=petition&pid='.$pid);
            $message_update = __('Confirmation emails sent.', 'guilro_petitions');
        break;
        default :
            // count number of signatures in database
            $count = $the_signatures->count($pid);

            // get all signatures for display
            $signatures = $the_signatures->all($pid, $query_start, $query_limit);

            // set up display strings
            $table_label = __('All Signatures', 'guilro_petitions').' <span class="count">('.$count.')</span>';
            $base_url = site_url('wp-admin/admin.php?page=guilro_petitions_signatures');
            $message_update = '';
    }

    // get list of petitions to populate select box navigation
    $petitions_list = $the_petitions->quicklist();

    // Set up URLs for 'Download as CSV' and 'Resend confirmations' buttons
    // Show buttons only when we are viewing signatures from a single petition
    if (count($petitions_list) == 1 || $pid != '') {
        // if $pid (petition id) wasn't sent through the URL, then create it from the query
        if ($pid == '') {
            $pid = $petitions_list[0]->id;
        }
        $csv_url = site_url('wp-admin/admin.php?page=guilro_petitions_signatures&action=petition&pid='.$pid);
        $reconfirm_url = site_url('wp-admin/admin.php?page=guilro_petitions_signatures&action=reconfirm&pid='.$pid);
    }

    // display the Signatures table
    include_once __DIR__.'/signatures.view.php';
}
