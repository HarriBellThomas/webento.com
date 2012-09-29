<?php

/*
 * Plugin Name: vBulletin Bridge
 * Plugin URI: http://www.aaronforgue.com
 * Description: vBulletin Bridge Proof of Concept
 * Author: Aaron Forgue
 * Version: 1
 * Author URI: http://www.aaronforgue.com
 */

/*
 * modified by Copyblogger Media to
 * - convert to class
 * - initalize vBulletin in wp-config.php
 * - hook user creation into membership
 */

class Premise_vBulletin_Bridge {

	function __construct() {

		// Create user hooks
		add_action( 'premise_create_member', array( $this, 'member_create' ), 10, 2 );
		add_action( 'premise_membership_create_order', array( $this, 'add_member_to_group' ), 10, 3 );

		// Auto-login hook
		add_action( 'wp_login', array( $this, 'member_login' ) );

	}

	function member_create( $user_id, $user_data ) {

		if ( is_wp_error( $user_id ) || ! $user_id )
			return;

		if ( empty( $user_data['user_login'] ) || empty( $user_data['user_email'] ) || empty( $user_data['user_pass'] ) )
			return;

		$vb_user_data = datamanager_init( 'User', $GLOBALS['vbulletin'], ERRTYPE_ARRAY );
		$vb_user_data->set( 'email', $user_data['user_email'] );
		$vb_user_data->set( 'username', $user_data['user_login'] );
		$vb_user_data->set( 'password', $user_data['user_pass'] );

		$vb_user_data->pre_save();

		if ( !empty( $vb_user_data->errors ) ) {

			$to = get_option( 'admin_email' );
			$from = memberaccess_get_email_receipt_address();
			$from_description = accesspress_get_option( 'email_receipt_name' );
			$subject = __( 'Error create vBulletin account', 'premise' );
			$body = sprintf( __( "Username: %s\nEmail Address: %s\nError: %s\n\n%s", 'premise' ), $user_data['user_login'], $user_data['user_email'], current( $vb_user_data->errors ), get_option( 'blogname' ) );
			wp_mail( $to, $subject, $body, "From: \"{$from_description}\" <{$email_from}>" );

		} else {

			$vb_user_id = $vb_user_data->save();
			update_user_meta( $user_id, 'vbulletin_user_id', $vb_user_id );

		}

	}

	function member_login( $user_login ) {

		$wp_user_data = get_user_by( 'login', $user_login );
		$vb_user_id = get_user_meta( $wp_user_data->ID, 'vbulletin_user_id', true );

		if ( empty( $vb_user_id ) )
			return;

		include( VBULLETIN_PATH . '/includes/functions_login.php' );
		$GLOBALS['vbulletin']->userinfo = verify_id( 'user', $vb_user_id, true, true, 0 );
		process_new_login( null, 0, null );
		$GLOBALS['vbulletin']->session->save();

	}

	function add_member_to_group( $member, $order_details, $renewal ) {

		if ( $renewal || is_wp_error( $member ) || ! $member )
			return;

		if ( ! empty( $order_details['_acp_order_product_id'] ) )
			$member_group = get_post_meta( $order_details['_acp_order_product_id'], '_acp_product_vbulletin_group', true );

		if ( empty( $member_group ) )
			$member_group = accesspress_get_option( 'vbulletin_group' );

		if ( ! $member_group )
			return;

		$vb_user_id = get_user_meta( $member, 'vbulletin_user_id', true );
		if ( empty( $vb_user_id ) )
			return;

		// get the vBulletin user
		$vb_user_data = datamanager_init( 'User', $GLOBALS['vbulletin'], ERRTYPE_ARRAY );

		$user_info = fetch_userinfo( $vb_user_id );
		$vb_user_data->set_existing( $user_info );

		// check for existing user
		$vb_primary_group = $vb_user_data->fetch_field( 'usergroupid' );
		if ( empty( $vb_primary_group ) || ! is_numeric( $vb_primary_group ) ) {

			$vb_user_data->set( 'usergroupid', $member_group );

		// user already has this primary group
		} elseif ( $vb_primary_group == $member_group ) {

			return;

		// add to secondary group
		} else {

			$secondary_groups = array( $member_group );
			$groups = $vb_user_data->fetch_field( 'membergroupids' );
			if ( ! empty( $groups ) ) {

				$secondary_groups = explode( ',', $groups );
				if ( in_array( $member_group, $secondary_groups ) )
					return;

				$secondary_groups[] = $member_group;
				sort( $secondary_groups );

			}

			$vb_user_data->set('membergroupids', implode( ',', $secondary_groups ) );
		}

		$vb_user_data->pre_save();
		if ( empty( $vb_user_data->errors ) )
			$vb_user_id = $vb_user_data->save();

	}
}