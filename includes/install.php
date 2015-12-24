<?php

// plugin installation routine
function guilro_petitions_install()
{
    global $wpdb, $guilro_petitions_db_petitions, $guilro_petitions_db_signatures, $guilro_petitions_version, $guilro_petitions_db_version;

    guilro_petitions_translate();

    $sql_create_tables = 'CREATE TABLE `'.$guilro_petitions_db_petitions.'` (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			title TEXT CHARACTER SET utf8 NOT NULL,
			target_email VARCHAR(300) CHARACTER SET utf8 NOT NULL,
			email_subject VARCHAR(200) CHARACTER SET utf8 NOT NULL,
			greeting VARCHAR(200) CHARACTER SET utf8 NOT NULL,
			petition_message LONGTEXT CHARACTER SET utf8 NOT NULL,
			address_fields VARCHAR(200) CHARACTER SET utf8 NOT NULL,
			expires CHAR(1) BINARY NOT NULL,
			expiration_date DATETIME NOT NULL,
			created_date DATETIME NOT NULL,
			goal INT(11) NOT NULL,
			sends_email CHAR(1) BINARY NOT NULL,
			twitter_message VARCHAR(120) CHARACTER SET utf8 NOT NULL,
			requires_confirmation CHAR(1) BINARY NOT NULL,
			return_url VARCHAR(200) CHARACTER SET utf8 NOT NULL,
			displays_custom_field CHAR(1) BINARY NOT NULL,
			custom_field_label VARCHAR(200) CHARACTER SET utf8 NOT NULL,
			displays_optin CHAR(1) BINARY NOT NULL,
			optin_label VARCHAR(200) CHARACTER SET utf8 NOT NULL,
			is_editable CHAR(1) BINARY NOT NULL,
			UNIQUE KEY (id)
		);
		CREATE TABLE `'.$guilro_petitions_db_signatures.'` (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			petitions_id BIGINT(20) NOT NULL,
			first_name VARCHAR(200) CHARACTER SET utf8 NOT NULL,
			last_name VARCHAR(200) CHARACTER SET utf8 NOT NULL,
			email VARCHAR(200) CHARACTER SET utf8 NOT NULL,
			street_address VARCHAR(200) CHARACTER SET utf8 NOT NULL,
			city VARCHAR(200) CHARACTER SET utf8 NOT NULL,
			state VARCHAR(200) CHARACTER SET utf8 NOT NULL,
			postcode VARCHAR(200) CHARACTER SET utf8 NOT NULL,
			country VARCHAR(200) CHARACTER SET utf8 NOT NULL,
			custom_field VARCHAR(400) CHARACTER SET utf8 NOT NULL,
			optin CHAR(1) BINARY NOT NULL,
			date DATETIME NOT NULL,
			confirmation_code VARCHAR(32) NOT NULL,
			is_confirmed CHAR(1) BINARY NOT NULL,
			custom_message LONGTEXT CHARACTER SET utf8 NOT NULL,
			language VARCHAR(10) CHARACTER SET utf8 NOT NULL,
			UNIQUE KEY (id)
		);';

    // create database tables
    require_once ABSPATH.'wp-admin/includes/upgrade.php';
    dbDelta($sql_create_tables);

    // set default options
    add_option('guilro_petitions_petitions_rows', '20');
    add_option('guilro_petitions_signatures_rows', '50');
    add_option('guilro_petitions_petition_theme', 'default');
    add_option('guilro_petitions_button_text', __('Sign Now', 'guilro_petitions'));
    add_option('guilro_petitions_expiration_message', __('This petition is now closed.', 'guilro_petitions'));
    add_option('guilro_petitions_success_message', '<strong>'.__('Thank you', 'guilro_petitions').", %first_name%.</strong>\r\n<p>".__('Your signature has been added.', 'guilro_petitions').'</p>');
    add_option('guilro_petitions_already_signed_message', __('This petition has already been signed using your email address.', 'guilro_petitions'));
    add_option('guilro_petitions_share_message', __('Share this with your friends:', 'guilro_petitions'));
    add_option('guilro_petitions_confirm_subject', __('Please confirm your email address', 'guilro_petitions'));
    add_option('guilro_petitions_confirm_message', __('Hello', 'guilro_petitions')." %first_name%\r\n\r\n".__('Thank you for singing our petition', 'guilro_petitions').'. '.__('Please confirm your email address by clicking the link below:', 'guilro_petitions')."\r\n%confirmation_link%\r\n\r\n".get_bloginfo('name'));
    add_option('guilro_petitions_confirm_email', get_bloginfo('name').' <'.get_bloginfo('admin_email').'>');
    add_option('guilro_petitions_optin_default', 'unchecked');
    add_option('guilro_petitions_display_count', '1');
    add_option('guilro_petitions_signaturelist_theme', 'default');
    add_option('guilro_petitions_signaturelist_header', __('Latest Signatures', 'guilro_petitions'));
    add_option('guilro_petitions_signaturelist_rows', '50');
    add_option('guilro_petitions_signaturelist_columns', serialize(array('sig_date')));
    add_option('guilro_petitions_widget_theme', 'default');
    add_option('guilro_petitions_csv_signatures', 'all');
    add_option('guilro_petitions_signaturelist_privacy', 'enabled');
    update_option('guilro_petitions_version', $guilro_petitions_version);
    add_option('guilro_petitions_db_version', $guilro_petitions_db_version);

    guilro_petitions_maybe_update();
}

function guilro_petitions_maybe_update()
{
    global $guilro_petitions_db_version;
    set_time_limit(0);

    $current_db_version = get_option('guilro_petitions_db_version');
    $target_db_version = $guilro_petitions_db_version;

    while ($current_db_version < $target_db_version) {
        $current_db_version++;
        $func = 'guilro_petitions_update_routine_'.$current_db_version;
        if (function_exists($func)) {
            call_user_func($func);
        }
    }

    update_option('guilro_petitions_db_version', $current_db_version);
}
