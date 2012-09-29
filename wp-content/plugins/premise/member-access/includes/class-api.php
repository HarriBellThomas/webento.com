<?php
/**
 * AccessPress Payment Gateway API
 *
 * @package AccessPress
 */


/**
 * Abstract base class to configure and process payment gateways.
 *
 * This class is extended by subclasses that define specific payment gateways.
 *
 * @since 0.1.0
 */
abstract class AccessPress_Gateway {

	/**
	 * Flag indicating whether the gateway is properly configures
	 *
	 * @since 0.1.0
	 *
	 * @var boolean configured
	 */
	public $configured = false;

	/**
	 * Name of the payment method for this gateway when the form is shown/processed.
	 *
	 * @since 0.1.0
	 *
	 * @var string Payment method
	 */
	public $payment_method;

	/**
	 * Product & data posted from the checkout form.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	public $product = null;

	/**
	 * Call this method in a subclass constructor to create the gateway.
	 *
	 * @since 0.1.0
	 *
	 * @param string $action_hook unique ID of this payment gateway
	 * @return null Returns early if action hook is not set
	 */
	public function create( $payment_method = '' ) {

		$this->payment_method = $this->payment_method ? $this->payment_method : $payment_method;
	 	$this->configured = $this->configure();

	}
	/**
	 * Wrapper for processing order function _process_order
	 *
	 * @since 0.1.0
	 */
	function process_order( $args ) {

		if ( ! $this->configured )
			return new WP_Error( 'gateway', __( 'Gateway not configured!', 'premise' ) );

		return $this->_process_order( $args );

	}
	/**
	 * get the duration of a product subscription
	 *
	 * @since 2.0.3
	 */
	function _get_subscription_duration( $post_id ) {

		$duration = (int) get_post_meta( $post_id, '_acp_product_duration', true );
		if ( ! $duration )
			return 0;

		if ( ! accesspress_get_option( 'authorize_net_recurring' ) )
			return 0;

		if ( ! get_post_meta( $post_id, '_acp_product_subscription', true ) )
			return 0;

		return $duration;

	}
	/**
	 * Validate report back from the payment gateway.
	 *
	 * Default is no report back
	 *
	 * @since 0.1.0
	 */
	public function validate_reportback() {

		return false;

	}
	/**
	 * Complete a sale for the payment gateway.
	 *
	 * Default is no completion step
	 *
	 * @since 0.1.0
	 */
	public function complete_sale( $args ) {

		return false;

	}
	/**
	 * Initialize the payment gateway.
	 *
	 * This method must be re-defined in the extended classes, to configure
	 * the payment gateway.
	 *
	 * @since 0.1.0
	 */
	abstract public function configure();

	/**
	 * Handle the postback of the payment gateway form.
	 *
	 * This method must be re-defined in the extended classes, to process
	 * the payment gateway post back.
	 *
	 * @since 0.1.0
	 */
	abstract public function _process_order( $args );

}

/**
 * Authorize.net gateway class to configure and process payment gateways.
 *
 * This class uses the CIM api.
 *
 * @since 0.1.0
 */
class AccessPress_AuthorizeNet_Gateway extends AccessPress_Gateway {
	/**
	 * The Authorize.net merchant authentication block.
	 *
	 * @since 0.1.0
	 *
	 * @var string XML CIM merchant authentication block
	 */
	private $_merchant_login;

	/**
	 * The Authorize.net gateway URI.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	private $_gateway_uri;

	/**
	 * The Authorize.net gateway mode.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	private $_gateway_mode;

	/**
	 * Class constructor.
	 *
	 * @since 0.1.0
	 */
	function __construct() {

	 	$this->create( 'cc' );

	}
	/**
	 * Initialize the payment gateway.
	 *
	 * @since 0.1.0
	 */
	public function configure() {

		$api_login = accesspress_get_option( 'authorize_net_id' );
		$transaction_key = accesspress_get_option( 'authorize_net_key' );

		// we need both an id & key to use the gateway
		if ( empty( $api_login ) || empty( $transaction_key ) )
			return false;

		$this->_gateway_uri = 'https://' . ( '1' == accesspress_get_option( 'gateway_live_mode' ) ? 'api' : 'apitest' ) . '.authorize.net/xml/v1/request.api';
//		$this->_gateway_mode = '<validationMode>' . ( '1' == accesspress_get_option( 'gateway_live_mode' ) ? 'live' : 'test' ) . 'Mode</validationMode>';
		$this->_gateway_mode = '<validationMode>testMode</validationMode>';
		$this->_merchant_login = sprintf( '<merchantAuthentication><name>%s</name><transactionKey>%s</transactionKey></merchantAuthentication>', $api_login, $transaction_key );

		return true;

	}

	/**
	 * Handle the postback of the payment gateway form.
	 *
	 * @since 0.1.0
	 */
	public function _process_order( $args ) {

		// create local user
		$user_id = $args['order_details']['_acp_order_member_id'];
		$memberaccess_cc_profile_id = isset( $args['cc_profile_id'] ) ? $args['cc_profile_id'] : 0;
		$memberaccess_cc_payment_profile_id = isset( $args['cc_payment_profile_id'] ) ? $args['cc_payment_profile_id'] : 0;

		if ( empty( $memberaccess_cc_profile_id ) && is_user_logged_in() )
			$memberaccess_cc_profile_id = get_user_option( 'memberaccess_cc_profile_id' );

		/** for initial payment attempts only */
		if ( ! $memberaccess_cc_profile_id ) {

			if ( is_user_logged_in() && empty( $args['first-name'] ) && empty( $args['last-name'] ) ) {

				$user = get_user_by( 'id', $user_id );
				$args['first-name'] = $user->first_name;
				$args['last-name'] = $user->last_name;
				$args['email'] = $user->user_email;

			}

			// create member profile
			$customer_info = sprintf( '<merchantCustomerId>%d</merchantCustomerId><description>%s</description><email>%s</email>',
				$user_id,
				trim( $args['first-name'] . ' ' . $args['last-name'] ),
				$args['email']
			);

			if ( !( $response = $this->_send_request( 'createCustomerProfileRequest', '<profile>' . $customer_info . '</profile>' ) ) )
				return $this->response;

			$this->customer_response = $repsonse;
			$memberaccess_cc_profile_id = (string)$response->customerProfileId;

		}

		$customer = sprintf( '<customerProfileId>%d</customerProfileId>', $memberaccess_cc_profile_id );
		/** for new subscriptions only */
		if ( ! $memberaccess_cc_payment_profile_id ) {

			// profile created now send billing info
			$bill_to = sprintf( '<billTo><firstName>%s</firstName><lastName>%s</lastName><zip>%s</zip><country>%s</country></billTo>',
				$args['first-name'],
				$args['last-name'],
				$args['card-postal'],
				$args['card-country']
			);
			$payment = sprintf( '<payment><creditCard><cardNumber>%s</cardNumber><expirationDate>%04d-%02d</expirationDate><cardCode>%s</cardCode></creditCard></payment>',
				$args['card-number'],
				$args['card-year'],
				$args['card-month'],
				$args['card-security']
			);
			$profile = '<paymentProfile>' . $bill_to . $payment . '</paymentProfile>';

			if ( !( $response = $this->_send_request( 'createCustomerPaymentProfileRequest', $customer . $profile . $this->_gateway_mode ) ) )
				return $this->response;

			$this->profile_response = $repsonse;
			$memberaccess_cc_payment_profile_id = (string)$response->customerPaymentProfileId;

		}

		// payment profile created now charge the account
		$product_post = get_post( $args['product_id'] );

		$amount = sprintf( '<amount>%.2f</amount>', $args['order_details']['_acp_order_price'] );
		$duration = $this->_get_subscription_duration( $args['product_id'] );
		$recurring = $duration ? 'true' : 'false';
		$payment_profile = sprintf( '<customerPaymentProfileId>%d</customerPaymentProfileId><recurringBilling>%s</recurringBilling>', $memberaccess_cc_payment_profile_id, $recurring );
		$item = sprintf( '<lineItems><itemId>%s</itemId><name>%s</name><description>%s</description><quantity>1</quantity><unitPrice>%.2f</unitPrice><taxable>false</taxable></lineItems>',
			$args['product_id'] . '-' . time(),
			substr( $product_post->post_name, 0, 31 ),
			$product_post->post_title,
			$args['order_details']['_acp_order_price']
		);
		$transaction = '<transaction><profileTransAuthCapture>' . $amount . $item . $customer . $payment_profile . '</profileTransAuthCapture></transaction>';

		if ( !( $response = $this->_send_request( 'createCustomerProfileTransactionRequest', $transaction ) ) )
			return $this->response;

		// we made it - update the user meta
		if ( ! is_user_logged_in() )
			update_user_option( $user_id, 'memberaccess_cc_profile_id', $memberaccess_cc_profile_id );

		if ( $duration ) {

			$args['order_details']['_acp_order_renewal_time'] = ( ! empty( $args['order_details']['_acp_order_renewal_time'] ) ? $args['order_details']['_acp_order_renewal_time'] : $args['order_details']['_acp_order_time'] ) + ( $duration * 86400 );
			$args['order_details']['_acp_order_status'] = 'active';
			update_user_option( $user_id, 'memberaccess_cc_payment_' . $args['product_id'], $memberaccess_cc_payment_profile_id );

		}

		$direct_response = explode( ',', $response->directResponse );
		$sale_meta = $args['order_details'];
		$sale_meta['_acp_order_anet_transaction_id'] = $direct_response[6];

		return $sale_meta;

	}

	private function _send_request( $tag, $content ) {

		$request_body = '<?xml version="1.0" encoding="utf-8"?><' . $tag . ' xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">' . $this->_merchant_login . $content . '</' . $tag . '>';
		$response = wp_remote_post( $this->_gateway_uri, array( 'headers' => array( 'content-type' => 'text/xml' ), 'body' => $request_body, 'timeout' => 30 ) );

		if ( is_wp_error( $response ) ) {
			$this->response = $response;
			return false;
		}

		if ( empty( $response['body'] ) ) {
			$this->response = new WP_Error( 'cc-server', __( 'Invalid response from payment processor', 'premise' ) );
			return false;
		}

		$response = simplexml_load_string( $response['body'], 'SimpleXMLElement', LIBXML_NOWARNING );
		if ( $response->messages->resultCode == 'Error' ) {
			$this->response = new WP_Error( 'cc-error', (string) $response->messages->message->text );
			return false;
		}

		return $response;

	}

	function test() {

		return $this->_send_request( 'getCustomerProfileRequest', '<customerProfileId>1000</customerProfileId>' );

	}
}

/**
 * Paypal gateway class to configure and process payment gateways.
 *
 * This class uses the Express Checkout api.
 *
 * @since 0.1.0
 */
class AccessPress_Paypal_Gateway extends AccessPress_Gateway {
	/**
	 * The Paypal merchant authentication block.
	 *
	 * @since 0.1.0
	 *
	 * @var string name-value pairs merchant authentication block
	 */
	private $_merchant_login;

	/**
	 * The Paypal gateway URI.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	private $_gateway_uri;

	/**
	 * The Paypal validation URI.
	 *
	 * @since 2.0.3
	 *
	 * @var string
	 */
	private $_validation_uri;

	/**
	 * The Paypal customer URI.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	private $_customer_uri;

	/**
	 * The Paypal gateway mode.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	private $_gateway_mode;

	/**
	 * The Paypal api version.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	private $_gateway_version;

	/**
	 * Class constructor.
	 *
	 * @since 0.1.0
	 */
	function __construct() {

	 	$this->create( 'paypal' );

	}
	/**
	 * Initialize the payment gateway.
	 *
	 * @since 0.1.0
	 */
	public function configure() {

		$api_username = urlencode( trim( accesspress_get_option( 'paypal_express_username' ) ) );
		$api_password = urlencode( trim( accesspress_get_option( 'paypal_express_password' ) ) );
		$api_signature = urlencode( trim( accesspress_get_option( 'paypal_express_signature' ) ) );

		// we need all three to use the gateway
		if ( empty( $api_username ) || empty( $api_password ) || empty( $api_signature ) )
			return false;

		$api_server = ( '1' == accesspress_get_option( 'gateway_live_mode' ) ? '' : '.sandbox' );
		$this->_gateway_uri = 'https://api-3t' . $api_server . '.paypal.com/nvp';
		$this->_customer_uri = 'https://www' . $api_server . '.paypal.com/webscr&cmd=_express-checkout&token=';
		$this->_validation_uri = 'https://www' . $api_server . '.paypal.com/cgi-bin/webscr';
		$this->_merchant_login = sprintf( '&USER=%s&PWD=%s&SIGNATURE=%s', $api_username, $api_password, $api_signature );
		$this->_gateway_version = '&VERSION=' . urlencode( '65.1' );

		return true;

	}

	/**
	 * Handle the postback of the payment gateway form.
	 *
	 * @since 0.1.0
	 */
	public function _process_order( $args ) {

		// create local user
		$user_id = $args['order_details']['_acp_order_member_id'];

		// we need a success & cancel url
		$base_url = add_query_arg( array( 'id' => $user_id, 'product_id' => $args['product_id'] ), get_permalink() );
		$success = add_query_arg( array( 'action' => 'complete' ), $base_url );
		$cancel = add_query_arg( array( 'action' => 'cancel' ), $base_url );

		$product_post = get_post( $args['product_id'] );
		$duration = $this->_get_subscription_duration( $args['product_id'] );

		// create authorization token
		$auth_request = sprintf( '&L_NAME0=%1$s&L_AMT0=%2$s&L_QTY0=1&AMT=%2$s&ReturnUrl=%3$s&CANCELURL=%4$s&CURRENCYCODE=USD&PAYMENTACTION=DoAuthorization',
			urlencode( $product_post->post_name ),
			urlencode( sprintf( '%.2f', $args['order_details']['_acp_order_price'] ) ),
			urlencode( $success ),
			urlencode( $cancel )
		);

		$profile_date = '';
		if ( $duration ) {

			$args['order_details']['_acp_order_renewal_time'] = $args['order_details']['_acp_order_time'] + ( $duration * 86400 );
			$profile_date = date( 'Y-m-d H:i:s', $args['order_details']['_acp_order_renewal_time'] ) . 'Z';

			$auth_request .= sprintf( '&L_BILLINGTYPE0=RecurringPayments&L_PROFILESTARTDATE0=%s&L_BILLINGAGREEMENTDESCRIPTION0=%s&L_BILLINGPERIOD0=Day&L_BILLINGFREQUENCY0=%d&L_TOTALBILLINGCYCLES0=0&PAYMENTREQUEST_0_AMT=%s',
				$profile_date,
				urlencode( $product_post->post_title ),
				$duration,
				urlencode( sprintf( '%.2f', $args['order_details']['_acp_order_price'] ) )
			);

		}

		if ( !( $response = $this->_send_request( 'SetExpressCheckout', $auth_request ) ) )
			return $this->response;

		// we have a token - update the user meta with the transaction info
		$sale_meta = $args['order_details'];
		$sale_meta['token'] = $response['TOKEN'];
		$sale_meta['profile_date'] = $profile_date;
		$accesspress_pp = array( $args['product_id'] => $sale_meta );
		update_user_option( $user_id, 'accesspress_pp', $accesspress_pp );

		// redirect the user to Paypal
		$url = $this->_customer_uri . urlencode( $response['TOKEN'] );

//@todo: translation support
?>
Redirecting to Paypal. Click this link if not redirected automatically:
<a href="<?php echo $url; ?>">Proceed to Paypal</a>
<script type="text/javascript">
//<!--
window.location = '<?php echo $url; ?>';
//-->
</script>
<?php
		return false;

	}

	private function _send_request( $method, $content ) {

		$request_body = 'METHOD=' . urlencode( $method ) . $this->_merchant_login . $content . $this->_gateway_version;
		$response = wp_remote_post( $this->_gateway_uri, array( 'body' => $request_body, 'timeout' => 30 ) );

		if ( is_wp_error( $response ) || empty( $response['body'] ) ) {
			$this->response = $response;
			return false;
		}

		$response = wp_parse_args( $response['body'] );
		if ( strtolower( $response['ACK'] ) != 'success' ) {
			$this->response = new WP_Error( 'paypal-error', $response['L_LONGMESSAGE0'] );
			return false;
		}

		return $response;

	}
	/**
	 * Validate reportback from the payment gateway.
	 *
	 * @since 0.1.0
	 */
	public function validate_reportback() {

		// check for reportback
		if ( empty( $_REQUEST['id'] ) || empty( $_REQUEST['action'] ) )
			return false;

		// check for cancelled transaction
		if ( strtolower( $_REQUEST['action'] ) == 'cancel' || empty( $_REQUEST['token'] ) )
			return new WP_Error( 'cancelled', __( 'Transaction Cancelled.', 'premise' ) );

		// validate the transaction
		$user_id = (int) $_REQUEST['id'];
		$product_id = (int) $_REQUEST['product_id'];
		$meta = get_user_option( 'accesspress_pp', $user_id );
		if ( is_wp_error( $meta ) || empty( $meta ) || empty( $meta[$product_id] ) )
			return new WP_Error( 'invalid', __( 'Invalid Transaction.', 'premise' ) );

		$transaction = $meta[$product_id];
		if ( $transaction['token'] != $_REQUEST['token'] )
			return new WP_Error( 'invalid-key', __( 'Invalid Transaction Key.', 'premise' ) );

		// validated locally, now check Paypal & complete the transaction
		$get_details = sprintf( '&TOKEN=%s', urlencode( $transaction['token'] ) );
		if ( !( $response = $this->_send_request( 'GetExpressCheckoutDetails', $get_details ) ) )
			return $this->response;

		if ( $transaction['_acp_order_price'] != $response['AMT'] )
			return new WP_Error( 'invalid-amount', __( 'Amount does not match,', 'premise' ) );

		// validated, now send data back to the checkout
		$transaction['payer_id'] = $response['PAYERID'];
		$meta[$product_id] = $transaction;
		update_user_option( $user_id, 'accesspress_pp', $meta );
		return array(
			'member' => $user_id,
			'product_id' => $product_id,
			'order_details' => $transaction
		);

	}
	/**
	 * Show the confirmation form
	 *
	 * method is called by the checkout form when a sale is validated
	 *
	 * @since 0.1.0
	 */
	public function confirmation_form( $sale ) {

		// show the confirmation form
		echo '<form method="post" action="">';

			printf( '<input type="hidden" name="accesspress-checkout[product_id]" value="%s" />', $sale['product_id'] );
			printf( '<input type="hidden" name="accesspress-checkout[member]" value="%d" />', $sale['member'] );
			printf( '<input type="hidden" name="accesspress-checkout[payment-method]" value="%s" />', $this->payment_method );
			wp_nonce_field( $sale['order_details']['token'], 'accesspress-checkout[key]' );
			printf( '<input type="submit" value="%s" />', is_user_logged_in() ? __( 'Complete Order', 'premise' ) : __( 'Complete Order and Create My Account', 'premise' ) );

		echo '</form>';

		return false;
	}
	/**
	 * Complete a sale on the Paypal gateway
	 *
	 * method is called by the checkout form after a sale is validated
	 *
	 * @since 0.1.0
	 */
	public function complete_sale( $args ) {

		// validate based on the confirmation form
		if( empty( $args['product_id'] ) || empty( $args['member'] ) || empty( $args['key'] ) )
			return false;

		$meta = get_user_option( 'accesspress_pp', $args['member'] );
		if ( is_wp_error( $meta ) || empty( $meta ) || empty( $meta[$args['product_id']] ) )
			return new WP_Error( 'invalid', __( 'Invalid Transaction.', 'premise' ) );

		$transaction = $meta[$args['product_id']];
		if ( empty( $transaction['token'] ) || ! wp_verify_nonce( $args['key'], $transaction['token'] ) )
			return new WP_Error( 'invalid-key', __( 'Invalid Transaction Key.', 'premise' ) );

		// complete the transaction
		$complete = sprintf( '&TOKEN=%s&PAYERID=%s&PAYMENTREQUEST_0_AMT=%s&PAYMENTREQUEST_0_CURRENCYCODE=USD&PAYMENTREQUEST_0_PAYMENTACTION=Sale',
			urlencode( $transaction['token'] ),
			urlencode( $transaction['payer_id'] ),
			urlencode( sprintf( '%.2f', $transaction['_acp_order_price'] ) )
		);
		if ( !( $response = $this->_send_request( 'DoExpressCheckoutPayment', $complete ) ) )
			return $this->response;

		if ( $transaction['profile_date'] ) {

			$product_post = get_post( $args['product_id'] );
			$duration = $this->_get_subscription_duration( $args['product_id'] );

			$complete = sprintf( '&TOKEN=%s&PAYERID=%s&PAYMENTREQUEST_0_AMT=%s&PAYMENTREQUEST_0_CURRENCYCODE=USD&PAYMENTREQUEST_0_PAYMENTACTION=Sale&PROFILESTARTDATE=%s&DESC=%s&BILLINGPERIOD=Day&BILLINGFREQUENCY=%d&AMT=%3$s&L_PAYMENTREQUEST_0_ITEMCATEGORY0=Digital',
				urlencode( $transaction['token'] ),
				urlencode( $transaction['payer_id'] ),
				urlencode( sprintf( '%.2f', $transaction['_acp_order_price'] ) ),
				urlencode( $transaction['profile_date'] ),
				urlencode( $product_post->post_title ),
				$duration
			);

			if ( !( $response = $this->_send_request( 'CreateRecurringPaymentsProfile', $complete ) ) )
				return $this->response;

			if ( ! empty( $response['PROFILEID'] ) )
				update_user_option( $args['member'], 'memberaccess_paypal_profile_' . $args['product_id'], $response['PROFILEID'] );

			$args['order_details']['_acp_order_renewal_time'] = ( ! empty( $args['order_details']['_acp_order_renewal_time'] ) ? $args['order_details']['_acp_order_renewal_time'] : $args['order_details']['_acp_order_time'] ) + ( $duration * 86400 );

		}
		// cleanup & return data to allow transaction to be completed by checkout
		$transaction['_acp_order_paypal_transaction_id'] = $transaction['token'];
		unset( $transaction['token'] );
		delete_user_option( $args['member'], 'accesspress_pp' );

		return array(
			'member' => $args['member'],
			'order_details' => $transaction
		);

	}
	/**
	 * Test the Paypal gateway
	 *
	 * method is called by the member access settings page
	 *
	 * @since 0.1.0
	 */
	public function test() {

		$base_url = home_url( '/' );
		$success = add_query_arg( array( 'action' => 'complete' ), $base_url );
		$cancel = add_query_arg( array( 'action' => 'cancel' ), $base_url );

		// create authorization token
		$test_request = sprintf( '&L_NAME0=%1$s&L_AMT0=%2$s&L_QTY0=1&AMT=%2$s&ReturnUrl=%3$s&CANCELURL=%4$s&CURRENCYCODE=USD&PAYMENTACTION=DoAuthorization',
			urlencode( 'Test Product' ),
			urlencode( sprintf( '%.2f', 1 ) ),
			urlencode( $success ),
			urlencode( $cancel )
		);

		return $this->_send_request( 'SetExpressCheckout', $test_request );

	}
	/**
	 * Validate a Paypal IPN reportback
	 *
	 * method is called by the recurring payments process
	 *
	 * @since 0.1.0
	 */
	public function validate_IPN() {

		if ( ! $this->configured || empty( $_POST ) )
			return false;

		$body = 'cmd=_notify-validate';
		foreach( $_POST as $key => $value )
			$body .= '&' . $key . '=' . urlencode( stripslashes( $value ) );

		$response = wp_remote_post( $this->_validation_uri, array( 'body' => $body, 'timeout' => 30 ) );
		if ( empty( $response['body'] ) || strpos( $response['body'], 'VERIFIED' ) !== 0 )
			return false;

		return true;

	}
}

/**
 * Optin gateway class to configure and process Optin gateways.
 *
 * This class allows payment via optin.
 *
 * @since 0.1.0
 */
class AccessPress_Optin_Gateway extends AccessPress_Gateway {
	/**
	 * The user data submitted with the optin form.
	 *
	 * @since 0.1.0
	 *
	 * @var array of user data
	 */
	private $_user_data;

	/**
	 * The Premise meta for the current landing page.
	 *
	 * @since 0.1.0
	 *
	 * @var array of landing page meta
	 */
	private $_premise_meta;

	/**
	 * Class constructor.
	 *
	 * @since 0.1.0
	 */
	function __construct() {

	 	$this->create( 'optin' );

	}
	/**
	 * Initialize the payment gateway.
	 *
	 * @since 0.1.0
	 */
	public function configure() {

		add_action( 'premise_optin_metabox_after_placement', array( $this, 'optin_metabox' ) );
		add_action( 'premise_optin_signup_extra_fields', array( $this, 'optin_extra_fields' ) );
		add_filter( 'premise_optin_extra_fields_errors', array( $this, 'validate_optin_extra_fields' ) );
		add_filter( 'premise_optin_subscribe_user', array( $this, 'register_user' ), 10, 2 );
		add_action( 'premise_optin_complete_order', array( $this, 'complete_order' ) );

	}

	public function optin_metabox( $meta ) {

		$meta = wp_parse_args( $meta,
			array(
				'member-product' => 0,
				'member-merge-email' => 'EMAIL',
				'member-merge-first-name' => '',
				'member-merge-last-name' => '',
			)
		);
		$merge_format = '%1$s : <input class="regular-text" type="text" name="premise[%2$s]" id="premise-main-%2$s" value="%3$s" /> %4$s';

		$product = get_post( $meta['member-product'] );
		$title = ( empty( $product->post_title ) || empty( $product->post_type ) || $product->post_type != 'acp-products' ) ? '' : $product->post_title;
?>
<div class="premise-option-box">
		<h4><label for="premise-optin-member-access"><?php _e('Member Access', 'premise' ); ?></label></h4>
		<p><?php _e( 'Give access to a product to those who opt in by entering the Product ID.', 'premise' ); ?></p>
		<p>
			<?php printf( $merge_format, __( 'Product ID', 'premise' ), 'member-product', esc_attr( $meta['member-product'] ), $title ); ?></li>
		</p><br />
		<p><?php _e( 'Create a list of these customers in MailChimp by identifying the fields (other email services not yet suspported).', 'premise' ); ?></p>
		<p><?php _e( 'Mailchimp List Merge Field(s):', 'premise' ); ?></p>
		<p>
			<ul>
				<li><?php printf( $merge_format, __( 'Email Address', 'premise' ), 'member-merge-email', esc_attr( $meta['member-merge-email'] ), '' ); ?></li>
				<li><?php printf( $merge_format, __( 'First Name', 'premise' ), 'member-merge-first-name', esc_attr( $meta['member-merge-first-name'] ), '' ); ?></li>
				<li><?php printf( $merge_format, __( 'Last Name', 'premise' ), 'member-merge-last-name', esc_attr( $meta['member-merge-last-name'] ), '' ); ?></li>
			</ul>
		</p>
</div>
<?php

	}
	public function optin_extra_fields( $type = '' ) {

		global $premise_base, $post;
		$meta = $premise_base->get_premise_meta( $post->ID );

		if ( empty( $meta['member-product'] ) )
			return;

		$args = array(
			'heading_text' => false,
			'label_separator' => '*',
			'wrap_before' => '',
			'wrap_after' => '',
			'before_item' => '<li>',
			'after_item' => '</li>',
			'show_email_address' => empty( $meta['member-merge-email'] ),
			'show_first_name' => empty( $meta['member-merge-first-name'] ),
			'show_last_name' => empty( $meta['member-merge-last-name'] ),
		);

		accesspress_checkout_form_account( $args );

		if ( ! empty( $meta['member-product'] ) ) {

			printf( '<input type="hidden" name="premise-product-id" value="%d" />', $meta['member-product'] );
			printf( '<input type="hidden" name="premise-landing-id" value="%d" />', $post->ID );
			printf( '<input type="hidden" name="premise-product-key" value="%s" />', wp_create_nonce( 'premise-product-key-' . $meta['member-product'] . '-' . $post->ID ) );

		}
	}
	function validate_optin_extra_fields( $errors ) {

		global $premise_base;

		if ( empty( $_POST['premise-product-id'] ) || empty( $_POST['premise-product-key'] ) || empty( $_POST['premise-landing-id'] ) )
			return;

		if ( ! wp_verify_nonce( $_POST['premise-product-key'], 'premise-product-key-' . $_POST['premise-product-id'] . '-' . $_POST['premise-landing-id'] ) )
			return;

		$this->_product_id = (int) $_POST['premise-product-id'];

		$errors = array();
		$args = empty( $_POST['accesspress-checkout'] ) ? array() : $_POST['accesspress-checkout'];
		$args = wp_parse_args( $args, array(
				'username' => '',
				'first-name' => '',
				'last-name' => '',
				'password' => '',
				'password-repeat' => '',
			)
		);

		$this->_premise_meta = $premise_base->get_premise_meta( $_POST['premise-landing-id'] );

		if ( ( empty( $this->_premise_meta['member-merge-first-name'] ) && ! $args['first-name'] ) || ( empty( $this->_premise_meta['member-merge-last-name'] ) && ! $args['last-name'] ) || ! $args['username'] || ! $args['password'] || ! $args['password-repeat']  )
			$errors[] = __( 'The account information was not filled out.', 'premise' );

		/** If passwords do not match */
		if ( $args['password'] !== $args['password-repeat'] )
			$errors[] = __( 'The passwords do not match.', 'premise' );

		if ( empty( $errors ) )
			$this->_member_args = $args;

		return $errors;

	}
	public function register_user( $setting, $args ) {

		if ( empty( $this->_product_id ) || ! $this->_product_id )
			return;

		$product = get_post( $this->_product_id );
		if ( ! $product || empty( $product->post_type ) || $product->post_type != 'acp-products' )
			return new WP_Error( 'product_missing', __( 'Product information missing', 'premise' ) );

		$optin_vars = array();
		// eliminate case mismatches
		foreach( (array) $args as $key => $value )
			$optin_vars[strtolower( $key )] = $value;

		$userdata = array(
			'first_name' => empty( $this->_premise_meta['member-merge-first-name'] ) ? $this->_member_args['first-name'] : $optin_vars[strtolower( $this->_premise_meta['member-merge-first-name'] )],
			'last_name'  => empty( $this->_premise_meta['member-merge-last-name'] ) ? $this->_member_args['last-name'] : $optin_vars[strtolower( $this->_premise_meta['member-merge-last-name'] )],
			'user_email' => empty( $this->_premise_meta['member-merge-email'] ) ? '' : $optin_vars[strtolower( $this->_premise_meta['member-merge-email'] )],
			'user_login' => $this->_member_args['username'],
			'user_pass'  => $this->_member_args['password'],
		);

		return accesspress_create_member( $userdata );

	}
	public function complete_order( $member ) {

		if ( ! $this->_product_id || ! $member )
			return;

		$order_details = array(
			'_acp_order_time'       => time(),
			'_acp_order_status'     => 'complete',
			'_acp_order_product_id' => $this->_product_id,
			'_acp_order_member_id' => $member,
		);
		accesspress_create_order( $member, $order_details );

	}
	public function _process_order( $args ) {}

}