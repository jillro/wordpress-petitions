<?php

/**
 * Displays the Email Petitions table page.
 */
function guilro_petitions_petitions_page()
{
    global $guilro_petitions_settings;
    // check security: ensure user has authority
    if (!current_user_can('publish_posts')) {
        wp_die('Insufficient privileges: You need to be an editor to do that.');
    }

    include_once 'class.speakout.php';
    include_once 'class.petition.php';
    include_once 'class.wpml.php';
    $the_petitions = new guilro_petitions_Petition();
    $wpml = new guilro_petitions_WPML();
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
    $options = $guilro_petitions_settings->getAll();

    // set variables for paged record display and limit values in db query
    // request values may be submitted either by html links (pagination.php) or by javascript (admin.js)
    $paged = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : '1';
    $total_pages = isset($_REQUEST['total_pages']) ? $_REQUEST['total_pages'] : '1';
    $current_page = guilro_petitions_SpeakOut::current_paged($paged, $total_pages);
    $query_limit = $options['petitions_rows'];
    $query_start = ($current_page * $query_limit) - $query_limit;

    // link URL for "Add New" button in header
    $addnew_url = esc_url(site_url().'/wp-admin/admin.php?page=guilro_petitions_addnew');

    switch ($action) {

        case 'delete' :
            // security: ensure user has intention
            check_admin_referer('guilro_petitions-delete_petition'.$id);

            // delete the petition and its signatures
            $the_petitions->delete($id);
            $wpml->unregister_petition($id);

            // get petitions
            $petitions = $the_petitions->all($query_start, $query_limit);

            // set up page display variables
            $page_title = __('Email Petitions', 'guilro_petitions');
            $count = $the_petitions->count();
            $message_update = __('Petition deleted.', 'guilro_petitions');

            break;

        default :
            // get petitions
            $petitions = $the_petitions->all($query_start, $query_limit);

            // set up page display variables
            $page_title = __('Email Petitions', 'guilro_petitions');
            $count = $the_petitions->count();
            $message_update = '';
    }

    // display the Petitions table
    include_once 'petitions.view.php';
}
