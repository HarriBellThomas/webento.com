<?php
/**
 * This file controls the member access control system in AccessPress.
 *
 * AccessPress uses a custom user Role to segregate members from other users.
 * The custom role is assigned to members upon successful signup, and an access
 * level is assigned to their user meta, depending on what product they purchased.
 *
 * @package AccessPress
 */


/**
 * Add our master role, "AccessPress Member".
 *
 * @since 0.1.0
 */
function accesspress_create_role() {

	if ( get_role( 'premise_member' ) )
		return;

	add_role(
		'premise_member',
		__( 'Premise Member', 'premise' ),
		array(
			'access_membership' => true
		) 
	);
	
}

/**
 * Helper function to insert user into the Users table.
 *
 * Accepts same arguments as the WordPress function wp_insert_user()
 * @link http://xref.yoast.com/trunk/_functions/wp_insert_user.html
 *
 * @since 0.1.0
 *
 */
function accesspress_create_member( $userdata = array() ) {
	
	$userdata['role'] = 'premise_member';
	if ( ! isset( $userdata['show_admin_bar_front'] ) )
		$userdata['show_admin_bar_front'] = 'false';
	
	$result = wp_insert_user( $userdata );
	
	do_action( 'premise_create_member', $result, $userdata );
	
	return $result;
	
}

add_action( 'wp', 'accesspress_process_link' );
/**
 * 
 */
function accesspress_process_link() {
	
	if ( ! isset( $_REQUEST['download_id'] ) )
		return;
	
	$links = get_option( 'member-access-links' );
	
	if ( ! isset( $links[$_REQUEST['download_id']] ) )
		die( 'Not a valid Download ID.' );
	
	if ( ! is_user_logged_in() ) {

		wp_redirect( get_permalink( accesspress_get_option( 'login_page' ) ) );
		exit;

	}
		
	$link = $links[$_REQUEST['download_id']];
	$upload_dir = wp_upload_dir();
	
	$access = current_user_can( 'manage_options' );
	
	foreach ( (array) $link['access-levels'] as $access_level ) {
		if ( member_has_access_level( $access_level, 0, $link['delay'] ) )
			$access = true;
	}
	
	if ( ! $access ) {
		header('HTTP/1.1 403 Forbidden');
		die('You do not have access to that file.');
	}
	
	$file = trailingslashit( $upload_dir['basedir'] . '/' . trim( accesspress_get_option( 'uploads_dir' ), '/' ) ) . $link['filename'];

	if ( ! file_exists( $file ) )
		die( 'File not found' );
		
	header( 'Content-Type: application/octet-stream' );
	header( 'Content-Description: File Transfer' );
	header( 'Content-Disposition: attachment; filename="' . basename( $file ) . '"' );
	readfile( $file );
	exit;
	
}

add_action( 'init', 'premise_admin_redirect_member' );

function premise_admin_redirect_member() {

	if ( is_admin() && is_user_logged_in() && ! current_user_can( 'read' ) ) {
		wp_redirect( home_url() );
		exit;
	}

}

add_filter( 'manage_users_columns', 'memberaccess_manage_users_columns' );

function memberaccess_manage_users_columns( $columns ) {

	$columns['member-access'] = __( 'Access Levels', 'premise' );
	return $columns;

}

add_filter( 'manage_users_custom_column', 'memberaccess_manage_users_custom_column', 1, 3 );

function memberaccess_manage_users_custom_column( $content, $column_name, $user_id ) {

	if( $column_name != 'member-access' )
		return;

	$terms = get_terms( 'acp-access-level', array( 'hide_empty' => false ) );

	if ( ! $terms )
		return '';

	$output = '';

	foreach ( (array) $terms as $term ) {

		if ( ! member_has_access_level( $term->term_id, $user_id ) )
			continue;

		$output .= esc_html( $term->name ) . '<br />';

	}

	return $output;

}

add_action( 'template_redirect', 'memberaccess_location_check', 1 );

function memberaccess_location_check() {

	$checkout_page = accesspress_get_option( 'checkout_page' );
	/** check for ssl */
	if ( ! is_ssl() ) {

		if ( accesspress_get_option( 'ssl_everywhere' ) )
			memberaccess_ssl_redirect();

		if ( $checkout_page && accesspress_get_option( 'ssl_checkout' ) && is_page( $checkout_page ) )
			memberaccess_ssl_redirect();

		$login_page = accesspress_get_option( 'login_page' );
		if ( $login_page && force_ssl_login() && is_page( $login_page ) )
			memberaccess_ssl_redirect();

	}

	$member_page = accesspress_get_option( 'member_page' );
	if ( ( $checkout_page || $member_page ) && is_page( array( $checkout_page, $member_page ) ) )
		add_action( 'wp_head', 'memberaccess_checkout_css', 99 );

}

add_filter( 'user_row_actions', 'memberaccess_user_row_actions', 10, 2 );

function memberaccess_user_row_actions( $actions, $user ) {

	$actions['member_comp'] = sprintf( '<br /><a href="%s">%s</a>', wp_nonce_url( add_query_arg( array( 'post_type' => 'acp-orders', 'member' => $user->ID ), admin_url( 'post-new.php' ) ), 'comp-product-' . $user->ID, true, false ), __( 'Complimentary Product', 'premise' ) );
	return $actions;

}

function memberaccess_checkout_css() {
?>
<style type="text/css">
.premise-checkout-wrap .accesspress-checkout-form-account,
.premise-checkout-wrap .accesspress-checkout-form-payment-method,
.premise-checkout-wrap .accesspress-checkout-form-cc {
	margin-bottom: 40px;
}
.premise-checkout-wrap .accesspress-checkout-form-row {
	clear: both;
}
.premise-checkout-wrap .checkout-text-label {
	display: block;
	float: left;
	padding: 6px 0;
	width: 135px;
}
.premise-checkout-wrap .accesspress-checkout-form-row {
	margin-bottom: 10px;
}
.premise-checkout-wrap .input-text {
	background: #f5f5f5;
	border: 1px solid #ddd;
	padding: 5px;
}
.premise-checkout-wrap .checkout-radio {
	margin-left: 140px;
}
.accesspress-checkout-form-payment-method input[type=radio] {
	vertical-align: top;
}
.premise-checkout-wrap .input-submit {
	background-color: #666;
	border: 0;
	color: #fff;
	cursor: pointer;
	padding: 8px 10px;
}
.premise-checkout-wrap .input-submit:hover {
	background-color: #333;
}
</style>
<?php
}

/** from wp-login.php */
function memberaccess_ssl_redirect() {

	if ( 0 === strpos( $_SERVER['REQUEST_URI'], 'http' ) )
		wp_redirect( preg_replace( '|^http://|', 'https://', $_SERVER['REQUEST_URI'] ) );
	else
		wp_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );

	exit();

}
