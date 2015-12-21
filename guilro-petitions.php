<?php
/*
Plugin Name: Petitions
Plugin URI: https://github.com/guilro/wp-petitions
Description: Create custom petition forms and include them on your site via shortcode or widget. Signatures are saved in the database and can be exported to CSV.
Version: 0.1.0
Author: Guillaume Royer
Author URI: http://github.com/guilro
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: guilro-petitions
Domain Path: /languages
*/

global $wpdb, $db_petitions, $db_signatures, $guilro_petitions_version;
$db_petitions  = $wpdb->prefix . 'guilro_petitions_petitions';
$db_signatures = $wpdb->prefix . 'guilro_petitions_signatures';
$guilro_petitions_version = '0.1.0';

// enable localizations
add_action( 'init', 'guilro_petitions_translate' );
function guilro_petitions_translate() {
	load_plugin_textdomain( 'guilro_petitions', false, __DIR__.'/languages' );
}

// load admin functions only on admin pages
if ( is_admin() ) {
	include_once( dirname( __FILE__ ) . '/includes/install.php' );
	include_once( dirname( __FILE__ ) . '/includes/admin.php' );
	include_once( dirname( __FILE__ ) . '/includes/petitions.php' );
	include_once( dirname( __FILE__ ) . '/includes/addnew.php' );
	include_once( dirname( __FILE__ ) . '/includes/signatures.php' );
	include_once( dirname( __FILE__ ) . '/includes/settings.php' );
	include_once( dirname( __FILE__ ) . '/includes/csv.php' );
	include_once( dirname( __FILE__ ) . '/includes/ajax.php' );

	if ( version_compare( get_bloginfo( 'version' ), '3.3', '>' ) == 1 ) {
		include_once( dirname( __FILE__ ) . '/includes/help.php' );
	}

	// enable plugin activation
	register_activation_hook( __FILE__, 'guilro_petitions_install' );
}
// public pages
else {
	include_once( dirname( __FILE__ ) . '/includes/emailpetition.php' );
	include_once( dirname( __FILE__ ) . '/includes/signaturelist.php' );
	include_once( dirname( __FILE__ ) . '/includes/confirmations.php' );
}

// load the widget (admin and public)
include_once( dirname( __FILE__ ) . '/includes/widget.php' );

// add Support and Donate links to the Plugins page
add_filter( 'plugin_row_meta', 'guilro_petitions_meta_links', 10, 2 );
function guilro_petitions_meta_links( $links, $file ) {
	$plugin = plugin_basename( __FILE__ );

	// create link
	if ( $file == $plugin ) {
		return array_merge(
			$links,
			array(
				sprintf( '<a href="http://wordpress.org/tags/speakout-email-petitions?forum_id=10">%s</a>', __( 'Support', 'guilro_petitions' ) ),
				sprintf( '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4PPYZ8K2KLXUJ">%s</a>', __( 'Donate', 'guilro_petitions' ) )
			)
		);
	}

	return $links;
}

?>
