<?php
/**
 * AccessPress Shortcodes for displaying front-end content
 *
 * @package AccessPress
 */

add_shortcode( 'checkout_form', 'accesspress_checkout_form_shortcode' );
add_shortcode( 'checkout-form', 'accesspress_checkout_form_shortcode' );
/**
 * Shortcode function for the checkout form.
 */
function accesspress_checkout_form_shortcode( $atts ) {

	add_filter( 'comments_open', '__return_false' );

	ob_start();
	accesspress_checkout_form( $atts );
	$checkout_form = ob_get_clean(); 
	
	return $checkout_form;
	
}

add_shortcode( 'login-form', 'accesspress_login_form_shortcode' );
add_shortcode( 'login_form', 'accesspress_login_form_shortcode' );
/**
 * Shortcode function for the login form
 */
function accesspress_login_form_shortcode( $atts ) {
	
	$atts = shortcode_atts( array(
		'welcome_text'   => __( 'Welcome! Your are now logged in!', 'premise' ),
		'logged_in_text' => __( 'You are already logged in', 'premise' )
	), $atts );

	add_filter( 'comments_open', '__return_false' );

	if ( is_user_logged_in() ) {

		if ( isset( $_REQUEST['just-logged-in'] ) )
			return current_user_can( 'unfiltered_html' ) ? $atts['welcome_text'] : esc_html( $atts['welcome_text'] );
		else
			return current_user_can( 'unfiltered_html' ) ? $atts['logged_in_text'] : esc_html( $atts['logged_in_text'] );

	}

	$redirect = add_query_arg( 'just-logged-in', 'true', get_permalink( accesspress_get_option( 'login_page' ) ) );

	return wp_login_form( array( 'redirect' => esc_url_raw( $redirect ), 'echo' => false ) );

}

add_shortcode( 'logout-link', 'accesspress_logout_link_shortcode' );
add_shortcode( 'logout_link', 'accesspress_logout_link_shortcode' );
/**
 * Generate a logout link.
 */
function accesspress_logout_link_shortcode( $atts ) {
	
	$atts = shortcode_atts( array(
		'text' => __( 'Logout', 'premise' )
	), $atts );
	
	if ( ! is_user_logged_in() )
		return;
	
	$redirect = get_permalink( accesspress_get_option( 'login_page' ) );
	
	return sprintf( '<a href="%s">%s</a>', wp_logout_url( esc_url_raw( $redirect ) ), current_user_can( 'unfiltered_html' ) ? $atts['text'] : esc_html( $atts['text'] ) );
	
}

add_shortcode( 'password-recovery-link', 'accesspress_password_recovery_link_shortcode' );
add_shortcode( 'password_recovery_link', 'accesspress_password_recovery_link_shortcode' );
/**
 * Generate a password recovery link.
 *
 * @since 2.0.2
 */
function accesspress_password_recovery_link_shortcode( $atts ) {
	
	$atts = shortcode_atts( array(
		'text' => __( 'Lost Password?', 'premise' )
	), $atts );
	
	if ( is_user_logged_in() )
		return;

	$redirect = add_query_arg( 'just-logged-in', 'true', get_permalink( accesspress_get_option( 'login_page' ) ) );

	return sprintf( '<a href="%s">%s</a>', wp_lostpassword_url( esc_url_raw( $redirect ) ), current_user_can( 'unfiltered_html' ) ? $atts['text'] : esc_html( $atts['text'] ) );
	
}

add_shortcode( 'show-to', 'accesspress_show_segmented_content' );
add_shortcode( 'show_to', 'accesspress_show_segmented_content' );

function accesspress_show_segmented_content( $atts, $content = '' ) {

	return accesspress_segmented_content( $atts, $content, '', true );

}

add_shortcode( 'hide-from', 'accesspress_hide_segmented_content' );
add_shortcode( 'hide_from', 'accesspress_hide_segmented_content' );

function accesspress_hide_segmented_content( $atts, $content = '' ) {

	return accesspress_segmented_content( $atts, '', $content );

}

add_shortcode( 'product-title', 'accesspress_product_title_content' );
add_shortcode( 'product_title', 'accesspress_product_title_content' );

function accesspress_product_title_content( $atts, $content = '' ) {

	return accesspress_product_info_content( $atts, 'post_title' );

}

add_shortcode( 'product-description', 'accesspress_product_description_content' );
add_shortcode( 'product_description', 'accesspress_product_description_content' );

function accesspress_product_description_content( $atts, $content = '' ) {

	return accesspress_product_info_content( $atts, '_acp_product_description' );

}

add_shortcode( 'product-price', 'accesspress_product_price_content' );
add_shortcode( 'product_price', 'accesspress_product_price_content' );

function accesspress_product_price_content( $atts, $content = '' ) {

	if( empty( $atts['format'] ) )
		$atts['format'] = '$ %.2f';

	return accesspress_product_info_content( $atts, '_acp_product_price' );

}

add_shortcode( 'product-purchase', 'accesspress_product_purchase_content' );
add_shortcode( 'product_purchase', 'accesspress_product_purchase_content' );

function accesspress_product_purchase_content( $atts, $content = '' ) {

	return sprintf( accesspress_product_info_content( $atts, 'purchase_link' ), $content );

}

add_shortcode( 'member-first-name', 'accesspress_first_name_content' );
add_shortcode( 'member_first_name', 'accesspress_first_name_content' );

function accesspress_first_name_content( $atts, $content = '' ) {

	global $product_member;

	if ( empty( $product_member->ID ) )
		return '';

	$first_name = get_user_meta( $product_member->ID, 'first_name', true );
	if ( empty( $first_name ) )
		return '';

	return $first_name;

}

add_shortcode( 'member-last-name', 'accesspress_last_name_content' );
add_shortcode( 'member_last_name', 'accesspress_last_name_content' );

function accesspress_last_name_content( $atts, $content = '' ) {

	global $product_member;

	if ( empty( $product_member->ID ) )
		return '';

	$last_name = get_user_meta( $product_member->ID, 'last_name', true );
	if ( empty( $last_name ) )
		return '';

	return $last_name;

}

add_shortcode( 'member-profile', 'accesspress_profile_content' );
add_shortcode( 'member_profile', 'accesspress_profile_content' );

function accesspress_profile_content( $atts, $content = '' ) {

	add_filter( 'comments_open', '__return_false' );

	if ( ! is_user_logged_in() )
		return sprintf( __( 'Please <a href="%s">Log in</a> to view your account.', 'premise' ), memberaccess_login_redirect( get_permalink() ) );

	$user = wp_get_current_user();

	$args = array(
		'heading_text' => '',
		'first-name' => $user->first_name,
		'last-name' => $user->last_name,
		'show_email_address' => false,
		'show_username' => false,
		'label_separator' => ':',
	);

	ob_start();
	accesspress_checkout_form_account( $args );

	return '<div class="premise-checkout-wrap">' . ob_get_clean() . '</div>';

}

add_shortcode( 'member-products', 'memberaccess_member_products_content' );
add_shortcode( 'member_products', 'memberaccess_member_products_content' );

function memberaccess_member_products_content( $atts, $content = '' ) {

	add_filter( 'comments_open', '__return_false' );

	if ( ! is_user_logged_in() )
		return '';

	$user = wp_get_current_user();

	/** Pull all the orders the member has ever made */
	$orders = get_user_option( 'acp_orders', $user->ID );
	if ( empty( $orders ) )
		return '';

	/** check for cancel requests */
	if ( ! empty( $_GET['cancel'] ) && ! empty( $_GET['order_id'] ) && ! empty( $_GET['_wpnonce'] ) && $_GET['cancel'] == 'true' && wp_verify_nonce( $_GET['_wpnonce'], 'cancel-subscription-' . $_GET['order_id'] ) )
		memberaccess_cancel_subscription ( $_GET['order_id'] );

	$output = '';
	$date_format = get_option( 'date_format' );
	$order_format = '<li><span class="premise-member-product">%s</span> - <span class="premise-member-product-expiry">%s</span> <span class="premise-member-product-cancel">%s</span></li>';
	/** Cycle through $orders looking for active (non-expired) subscriptions */
	foreach ( $orders as $order ) {

		// get product
		$product = (int) get_post_meta( $order, '_acp_order_product_id', true );
		$product_post = get_post( $product );
		if ( ! $product_post )
			continue;

		// get expiry time
		$expiration = memberaccess_get_order_expiry( $order, $product, 0, true );

		if ( 0 == $expiration ) {

			$output .= sprintf( $order_format, esc_html( $product_post->post_title ), __( 'Lifetime', 'premise' ), '' );
			continue;

		}

		$payment_profile = get_user_option( 'memberaccess_cc_payment_' . $product );
		if ( $payment_profile ) {

			$output .= sprintf( $order_format, esc_html( $product_post->post_title ), date( $date_format, $expiration ), '' );
			continue;

		}

		$renew_url = add_query_arg( array( 'renew' => 'true', 'product_id' => $product ), get_permalink( accesspress_get_option( 'checkout_page' ) ) );

		$cancel_url = '';
		$cancel_status = __( 'cancel', 'premise' );
		$renewal_time = get_post_meta( $order, '_acp_order_renewal_time', true );
		$status = get_post_meta( $order, '_acp_order_status', true );
		if ( $payment_profile && $renewal_time > ( time() - 172800 ) && $status != $cancel_status )
			$cancel_url = sprintf( __( '<a href="%s" %s>Cancel</a>', 'premise' ), wp_nonce_url( add_query_arg( array( 'cancel' => 'true', 'order_id' => $order ), get_permalink( accesspress_get_option( 'member_page' ) ) ), 'cancel-subscription-' . $order ), '' );

		$output .= sprintf( $order_format, esc_html( $product_post->post_title ), date( $date_format, $expiration ) . ' - ' . sprintf( __( '<a href="%s" %s>Renew</a>', 'premise' ), $renew_url, '' ), $cancel_url );

	}

	return '<ul class="premise-member-products">' . $output . '</ul>';

}

function accesspress_segmented_content( $atts, $has_access = '', $no_access = '', $check_delay = false ) {

	$atts = shortcode_atts( array(
			'accesslevel' => '',
			'delay' => '0'
		),
		$atts
	);

	// show no access content unless we have an access level & the user is logged in
	if( empty( $atts['accesslevel'] ) || ! is_user_logged_in() )
		return '';

	$delay = $check_delay ? (int) $atts['delay'] : 0;
	if( member_has_access_level( $atts['accesslevel'], 0, $delay ) )
		return $has_access;

	return $no_access;

}

function accesspress_product_info_content( $atts, $field ) {

	global $product_post;

	$atts = shortcode_atts( array(
			'productid' => 0,
			'format' => '',
			'title' => '',
			'target' => '',
		),
		$atts
	);

	if ( ! $atts['productid'] && isset( $_REQUEST['product_id'] ) )
		$atts['productid'] = (int) $_REQUEST['product_id'];

	if ( ! $atts['productid'] && isset( $_POST['accesspress-checkout']['product_id'] ) )
		$atts['productid'] = (int) $_POST['accesspress-checkout']['product_id'];

	if ( ! $atts['productid'] && isset( $product_post->ID ) )
		$atts['productid'] = (int) $product_post->ID;

	if ( ! memberaccess_is_valid_product( $atts['productid'] ) )
		return '';

	if ( $field == 'post_title' ) {

		if ( ! empty( $product_post->post_title ) )
			return $product_post->post_title;

		$product = get_post( $atts['productid'] );
		if ( empty( $product->post_title ) )
			return '';

		return $product->post_title;

	}

	if ( $field == 'purchase_link' ) {

		$url = accesspress_get_checkout_link( $atts['productid'] );
		if( ! $url )
			return '%s';

		$target = $atts['target'] ? 'target="' . $atts['target'] .'"' : '';
		return sprintf( '<a href="%s" title="%s" %s>', $url, $atts['title'], $target ) . '%s</a>';

	}

	$meta = get_post_meta( $atts['productid'], $field, true );
	if ( empty( $meta ) )
		return '';

	return $atts['format'] ? sprintf( $atts['format'], $meta ) : $meta;
}
