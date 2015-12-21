<?php
// create admin menus
add_action( 'admin_menu', 'guilro_petitions_create_menus' );
function guilro_petitions_create_menus() {

	// load sidebar menus
	$petitions = array(
		'page_title' => __( 'Email Petitions', 'guilro_petitions' ),
		'menu_title' => __( 'Email Petitions', 'guilro_petitions' ),
		'capability' => 'publish_posts',
		'menu_slug'  => 'guilro_petitions',
		'function'   => 'guilro_petitions_petitions_page',
		'icon_url'   => plugins_url( 'speakout/images/blank.png' )
	);
	$petitions_page = add_menu_page( $petitions['page_title'], $petitions['menu_title'], $petitions['capability'], $petitions['menu_slug'], $petitions['function'], $petitions['icon_url'] );

	$addnew = array(
		'parent_slug' => 'guilro_petitions',
		'page_title'  => __( 'Add New', 'guilro_petitions' ),
		'menu_title'  => __( 'Add New', 'guilro_petitions' ),
		'capability'  => 'publish_posts',
		'menu_slug'   => 'guilro_petitions_addnew',
		'function'    => 'guilro_petitions_addnew_page'
	);
	$addnew_page = add_submenu_page( $addnew['parent_slug'], $addnew['page_title'], $addnew['menu_title'], $addnew['capability'], $addnew['menu_slug'], $addnew['function'] );

	$signatures = array(
		'parent_slug' => 'guilro_petitions',
		'page_title'  => __( 'Signatures', 'guilro_petitions' ),
		'menu_title'  => __( 'Signatures', 'guilro_petitions' ),
		'capability'  => 'publish_posts',
		'menu_slug'   => 'guilro_petitions_signatures',
		'function'    => 'guilro_petitions_signatures_page'
	);
	$signatures_page = add_submenu_page( $signatures['parent_slug'], $signatures['page_title'], $signatures['menu_title'], $signatures['capability'], $signatures['menu_slug'], $signatures['function'] );

	$settings = array(
		'parent_slug' => 'guilro_petitions',
		'page_title'  => __( 'Email Petitions Settings', 'guilro_petitions' ),
		'menu_title'  => __( 'Settings', 'guilro_petitions' ),
		'capability'  => 'manage_options',
		'menu_slug'   => 'guilro_petitions_settings',
		'function'    => 'guilro_petitions_settings_page'
	);
	$settings_page = add_submenu_page( $settings['parent_slug'], $settings['page_title'], $settings['menu_title'], $settings['capability'], $settings['menu_slug'], $settings['function'] );

	// load contextual help tabs for newer WordPress installs (requires 3.3.1)
	if ( version_compare( get_bloginfo( 'version' ), '3.3', '>' ) == 1 ) {
		add_action( 'load-' . $addnew_page, 'guilro_petitions_help_addnew' );
		add_action( 'load-' . $settings_page, 'guilro_petitions_help_settings' );
	}
}

// display custom menu icon
add_action( 'admin_head', 'guilro_petitions_menu_icon' );
function guilro_petitions_menu_icon() {
	echo '
		<style type="text/css">
			#toplevel_page_guilro_petitions .wp-menu-image {
				background: url(' . plugins_url( "speakout/images/icon-emailpetitions-16.png" ) . ') no-repeat 6px 7px !important;
			}
			body.admin-color-classic #toplevel_page_guilro_petitions .wp-menu-image {
				background: url(' . plugins_url( "speakout/images/icon-emailpetitions-16.png" ) . ') no-repeat 6px -41px !important;
			}
			#toplevel_page_guilro_petitions:hover .wp-menu-image, #toplevel_page_guilro_petitions.wp-has-current-submenu .wp-menu-image {
				background-position: 6px -17px !important;
			}
			body.admin-color-classic #toplevel_page_guilro_petitions:hover .wp-menu-image, body.admin-color-classic #toplevel_page_guilro_petitions.wp-has-current-submenu .wp-menu-image {
				background-position: 6px -17px !important;
			}

		</style>
	';
}

// load JavaScript for use on admin pages
add_action( 'admin_print_scripts', 'guilro_petitions_admin_js' );
function guilro_petitions_admin_js() {
	global $parent_file;

	if ( $parent_file == 'guilro_petitions' ) {
		wp_enqueue_script( 'guilro_petitions_admin_js', plugins_url( 'speakout/js/admin.js' ), array( 'jquery' ) );
		wp_enqueue_script( 'post', admin_url( 'js/post.js' ), 'jquery' );
	}
}

// load CSS for use on admin pages
add_action( 'admin_print_styles', 'guilro_petitions_admin_css' );
function guilro_petitions_admin_css() {
	global $parent_file;

	if ( $parent_file == 'guilro_petitions' ) {
		wp_enqueue_style( 'guilro_petitions_admin_css', plugins_url( 'speakout/css/admin.css' ) );
	}
}

?>