<?php

function guilro_petitions_settings_page()
{

    // security check
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient privileges: You need to be an administrator to do that.');
    }

    include_once 'class.speakout.php';
    include_once 'class.settings.php';
    include_once 'class.wpml.php';
    $the_settings = new guilro_petitions_Settings();
    $wpml = new guilro_petitions_WPML();

    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    $tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : 'guilro-petitions-tab-01';

    switch ($action) {

        case 'update' :

            // security check
            check_admin_referer('guilro_petitions-update_settings');

            $the_settings->update();
            $the_settings->retrieve();

            // attempt to resgister strings for translation in WPML
            $options = get_option('guilro_petitions_options');
            $wpml->register_options($options);

            $message_update = __('Settings updated.', 'guilro_petitions');

            break;

        default :

            $the_settings->retrieve();

            $message_update = '';
    }

    $nonce = 'guilro_petitions-update_settings';
    $action = 'update';
    include_once __DIR__.'/settings.view.php';
}
