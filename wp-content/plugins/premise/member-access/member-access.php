<?php

add_action( 'plugins_loaded', 'accesspress_init' );
/**
 * Initialize AccessPress.
 *
 * Include the libraries, define global variables, instantiate the classes.
 *
 * @since 0.1.0
 */
function accesspress_init() {

	global $memberaccess_products_object;

	define( 'MEMBER_ACCESS_SETTINGS_FIELD', 'member-access-settings' );

	/** Includes */
	require_once( PREMISE_MEMBER_INCLUDES_DIR . 'class-admin.php' );
	require_once( PREMISE_MEMBER_INCLUDES_DIR . 'class-api.php' );
	require_once( PREMISE_MEMBER_INCLUDES_DIR . 'class-products.php' );
	require_once( PREMISE_MEMBER_INCLUDES_DIR . 'class-orders.php' );
	require_once( PREMISE_MEMBER_INCLUDES_DIR . 'functions.php' );
	require_once( PREMISE_MEMBER_INCLUDES_DIR . 'members.php' );

	require_once( PREMISE_MEMBER_INCLUDES_DIR . 'admin/settings.php' );
	require_once( PREMISE_MEMBER_INCLUDES_DIR . 'admin/link-manager.php' );
	require_once( PREMISE_MEMBER_INCLUDES_DIR . 'admin/post-access-metabox.php' );
	require_once( PREMISE_MEMBER_INCLUDES_DIR . 'admin/report.php' );

	require_once( PREMISE_MEMBER_INCLUDES_DIR . 'views/template-tags.php' );
	require_once( PREMISE_MEMBER_INCLUDES_DIR . 'views/shortcodes.php' );
	
	$memberaccess_products_object = new AccessPress_Products;
	new AccessPress_Orders;
	// for mailchimp as payment
	new AccessPress_Optin_Gateway;
	// for recurring processing
	if ( accesspress_get_option( 'authorize_net_recurring' ) == '1' )
		require_once( PREMISE_LIB_DIR . 'cron/recurring-payments.php' );

	// for vBulletin
	if ( memberaccess_is_vbulletin_enabled() ) {

		require_once( PREMISE_MEMBER_INCLUDES_DIR . 'class-vbulletinbridge.php' );
		new Premise_vBulletin_Bridge;

	}

}
/**
 * This function runs on membership activation.
 *
 */	
add_action( 'premise_admin_init', 'accesspress_create_role' );