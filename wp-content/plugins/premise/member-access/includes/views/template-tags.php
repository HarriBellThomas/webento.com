<?php
/**
 * AccessPress Template Tags for displaying front-end content
 *
 * @package AccessPress
 */

function accesspress_checkout_form( $args = array() ) {

	$args = isset( $_POST['accesspress-checkout'] ) ? $_POST['accesspress-checkout'] : $args;
	$form_submitted = isset( $_POST['accesspress-checkout'] ) || isset( $_REQUEST['action'] );

	/** If form submitted */
	if ( $form_submitted ) {

		$checkout_complete = accesspress_checkout( $args );

		/** If there was an error in the submission show the error to the user */
		if ( is_wp_error( $checkout_complete ) ) {

			printf( '<div class="acp-error">%s</div>', $checkout_complete->get_error_message() );

		} else {

			/** Show the comlpete message when the transaction is complete */
			if ( $checkout_complete )
				_e( 'Congratulations! Please check your email for account details.', 'premise' );

			/** don't show the checkout form */
			return;
		}
		
	}
	
	/** don't show the checkout form unless there is a product or checkout is in progress */
	$product_id = isset( $_GET['product_id'] ) ? $_GET['product_id'] : '';
	if( ! $form_submitted && ! memberaccess_is_valid_product( $product_id ) )
		return;
	
	echo '<div class="premise-checkout-wrap"><form method="post" action="">';
	
		printf( '<input type="hidden" name="accesspress-checkout[product_id]" value="%s" />', $product_id );
		if ( isset( $_GET['renew'] ) && 'true' == $_GET['renew'] )
			echo '<input type="hidden" name="accesspress-checkout[renew]" value="true" />';
	
		accesspress_checkout_form_account( $args );
		accesspress_checkout_form_choose_payment( $args );
		accesspress_checkout_form_payment_cc( $args );
		
		printf( '<input type="submit" value="%s" class="input-submit" />', is_user_logged_in() ? __( 'Submit Order', 'premise' ) : __( 'Submit Order and Create My Account', 'premise' ) );
	
	echo '</form></div>';
	
}

function accesspress_checkout_form_account( $args = array() ) {

	global $accesspress_checkout_member;

	if ( is_user_logged_in() ) {

		$user = wp_get_current_user();
		$accesspress_checkout_member = $user->ID;
		$args['first-name']	= $user->first_name;
		$args['last-name']	= $user->last_name;
		$args['email']		= $user->user_email;
		$args['username']	= $user->user_login;

	}

	$disabled = '';
	if ( ! empty( $accesspress_checkout_member ) && $accesspress_checkout_member > 0 ) {

		$disabled = 'disabled="disabled" ';
		$args['heading_text'] = __( '1. Your Account?', 'premise' );
		printf( '<input type="hidden" name="accesspress-checkout[member]" value="%d" />', $accesspress_checkout_member );
		wp_nonce_field( 'checkout-member-' . $accesspress_checkout_member, 'accesspress-checkout[member-key]' );

	}

	$args = wp_parse_args( $args, array(
		'heading_text' => __( '1. Create Your Account', 'premise' ),
		'product_id' => isset( $_GET['product_id'] ) ? (int) $_GET['product_id'] : 0,
		'first-name' => '',
		'last-name' => '',
		'email' => '',
		'username' => '',
		'wrap_before' => '<div class="accesspress-checkout-form-account">',
		'wrap_after' => '</div>',
		'before_item' => '<div class="accesspress-checkout-form-row">',
		'after_item' => '</div>',
		'show_first_name' => true,
		'show_last_name' => true,
		'show_email_address' => true,
		'show_username' => true,
		'label_separator' => ':',
	) );

	echo $args['wrap_before'];
?>
		<?php if ( $args['heading_text'] ) : ?>
			<div class="accesspress-checkout-heading"><?php echo esc_html( $args['heading_text'] ); ?></div>
		<?php endif; ?>
		
		<?php if ( $args['show_first_name'] ) : ?>
			<?php echo $args['before_item']; ?>
				<label for="accesspress-checkout-first-name" class="checkout-text-label"><?php echo __( 'First Name', 'premise' ) . $args['label_separator']; ?></label>
				<input type="text" name="accesspress-checkout[first-name]" id="accesspress-checkout-first-name" class="input-text" value="<?php echo esc_attr( $args['first-name'] ); ?>" <?php echo $disabled; ?>/>
			<?php echo $args['after_item']; ?>
		<?php endif; ?>
		
		<?php if ( $args['show_last_name'] ) : ?>
			<?php echo $args['before_item']; ?>
				<label for="accesspress-checkout-last-name" class="checkout-text-label"><?php echo __( 'Last Name', 'premise' ). $args['label_separator']; ?></label>
				<input type="text" name="accesspress-checkout[last-name]" id="accesspress-checkout-last-name" class="input-text" value="<?php echo esc_attr( $args['last-name'] ); ?>" <?php echo $disabled; ?>/>
			<?php echo $args['after_item']; ?>
		<?php endif; ?>
		
		<?php if ( $args['show_email_address'] ) : ?>
			<?php echo $args['before_item']; ?>
				<label for="accesspress-checkout-email" class="checkout-text-label"><?php echo __( 'Email Address', 'premise' ). $args['label_separator']; ?></label>
				<input type="text" name="accesspress-checkout[email]" id="accesspress-checkout-email" class="input-text" value="<?php echo esc_attr( $args['email'] ); ?>" <?php echo $disabled; ?>/>
			<?php echo $args['after_item']; ?>
		<?php endif; ?>
		
		<?php if ( $args['show_username'] ) : ?>
			<?php echo $args['before_item']; ?>
				<label for="accesspress-checkout-username" class="checkout-text-label"><?php echo __( 'Username', 'premise' ). $args['label_separator']; ?></label>
				<input type="text" name="accesspress-checkout[username]" id="accesspress-checkout-username" class="input-text" value="<?php echo esc_attr( $args['username'] ); ?>" <?php echo $disabled; ?>/>
			<?php echo $args['after_item']; ?>
		<?php endif; ?>
		
		<?php if ( ! $disabled ) : ?>
			<?php echo $args['before_item']; ?>
				<label for="accesspress-checkout-password" class="checkout-text-label"><?php echo __( 'Password', 'premise' ). $args['label_separator']; ?></label>
				<input type="password" name="accesspress-checkout[password]" id="accesspress-checkout-password" class="input-text" value="" />
			<?php echo $args['after_item']; ?>

			<?php echo $args['before_item']; ?>
				<label for="accesspress-checkout-password-repeat" class="checkout-text-label"><?php echo __( 'Re-enter Password', 'premise' ). $args['label_separator']; ?></label>
				<input type="password" name="accesspress-checkout[password-repeat]" id="accesspress-checkout-password-repeat" class="input-text" value="" />
			<?php echo $args['after_item']; ?>
		<?php endif; ?>
<?php
	echo $args['wrap_after'];

}

function accesspress_checkout_form_choose_payment( $args = array() ) {

	$authorize = is_active_payment_method( 'authorize.net' );
	$paypal = is_active_payment_method( 'paypal' );
	$default_method = ( $authorize && $paypal ) ? '' : ( $authorize ? 'cc' : 'paypal' );

	$args = wp_parse_args( $args, array(
		'heading_text' => $default_method ? __( '2. Payment Method', 'premise' ) : __( '2. Choose Payment Method', 'premise' ),
		'product_id' => (int) $_GET['product_id'],
		'payment-method' => $default_method,
	) );

?>
	<div class="accesspress-checkout-form-payment-method">

		<?php if ( $args['heading_text'] ) : ?>
			<div class="accesspress-checkout-heading"><?php echo esc_html( $args['heading_text'] ); ?></div>
		<?php endif; ?>

		<?php if ( $authorize ) : ?>
		<div class="accesspress-checkout-form-row">
			<input type="radio" name="accesspress-checkout[payment-method]" id="accesspress-checkout-payment-method-cc" class="input-text checkout-radio" value="cc" <?php checked( 'cc', $args['payment-method'] ); ?> />
			<label for="accesspress-checkout-payment-method-cc"><?php _e( 'Credit Card', 'premise' ); ?></label>
		</div>
		<?php endif; ?>

		
		<?php if ( $paypal ) : ?>
		<div class="accesspress-checkout-form-row">
			<input type="radio" name="accesspress-checkout[payment-method]" id="accesspress-checkout-payment-method-paypal" class="input-text checkout-radio" value="paypal" <?php checked( 'paypal', $args['payment-method'] ); ?> />
			<label for="accesspress-checkout-payment-method-paypal"><!-- PayPal Logo --><a href="#" onclick="javascript:window.open('https://www.paypal.com/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside','olcwhatispaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350');"><img  src="https://www.paypal.com/en_US/i/logo/PayPal_mark_37x23.gif" border="0" alt="Acceptance Mark"></a><!-- PayPal Logo --></label>
		</div>
		<?php endif; ?>

	</div>
<?php	
	
}

function accesspress_checkout_form_payment_cc( $args = array() ) {
	
	$args = wp_parse_args( $args, array(
		'heading_text' => __( '3. Enter Credit Card Information', 'premise' ),
		'product_id' => (int) $_GET['product_id'],
		'card-name' => '',
		'card-month' => '',
		'card-year' => '',
		'card-country' => '',
		'card-postal' => '',
	) );
	
	/** Bail if this isn't a valid payment method */
	if ( ! is_active_payment_method( 'authorize.net' ) )
		return;

?>
	<div class="accesspress-checkout-form-cc">

		<?php if ( $args['heading_text'] ) : ?>
			<div class="accesspress-checkout-heading"><?php echo esc_html( $args['heading_text'] ); ?></div>
		<?php endif; ?>
		
		<div class="accesspress-checkout-form-row">
			<label for="accesspress-checkout-card-name" class="checkout-text-label"><?php _e( 'Name on Card', 'premise' ); ?>:</label>
			<input type="text" name="accesspress-checkout[card-name]" id="accesspress-checkout-card-name" class="input-text" value="<?php echo esc_attr( $args['card-name'] ); ?>" />
		</div>
		
		<div class="accesspress-checkout-form-row">
			<label for="accesspress-checkout-card-number" class="checkout-text-label"><?php _e( 'Card Number', 'premise' ); ?>:</label>
			<input type="text" name="accesspress-checkout[card-number]" id="accesspress-checkout-card-number" class="input-text" value="" />
		</div>
		
		<div class="accesspress-checkout-form-row">
			<label for="accesspress-checkout-card-month" class="checkout-text-label"><?php _e( 'Expiration Date', 'premise' ); ?></label>
			
			<select name="accesspress-checkout[card-month]" id="accesspress-checkout-card-month">
				<?php
				foreach ( range( 1, 12 ) as $month ) {
					printf( '<option value="%d" %s>%d</option>', $month, selected( $month, $args['card-month'], false ), $month );
				}
				?>
			</select>
			
			<select name="accesspress-checkout[card-year]" id="accesspress-checkout-card-year">
				<?php
				$thisyear = (int) date('Y');
				foreach ( range( $thisyear, $thisyear + 10 ) as $year ) {
					printf( '<option value="%d" %s>%d</option>', $year, selected( $year, $args['card-year'], false ), $year );
				}
				?>
			</select>
		</div>
		
		<div class="accesspress-checkout-form-row">
			<label for="accesspress-checkout-card-security" class="checkout-text-label"><?php _e( 'Security Code', 'premise' ); ?>:</label>
			<input type="password" name="accesspress-checkout[card-security]" id="accesspress-checkout-card-security" class="input-text input-text-short" size="3" value="" />
			<p><span class="description"><?php _e( 'The security code should be located on the back of your card.', 'premise' ) ?></span></p>
		</div>
		
		<div class="accesspress-checkout-form-row">
			<label for="accesspress-checkout-card-country" class="checkout-text-label"><?php _e( 'Country', 'premise' ); ?>:</label>
			<select name="accesspress-checkout[card-country]" id="accesspress-checkout-card-country">
				<?php
				foreach ( (array) accesspress_get_countries() as $code => $label ) {
					printf( '<option value="%s" %s>%s</option>', esc_attr( $code ), selected( $code, $args['card-country'], false ), esc_html( $label ) );
				}
				?>
			</select>
		</div>
		
		<div class="accesspress-checkout-form-row">
			<label for="accesspress-checkout-card-postal" class="checkout-text-label"><?php _e( 'ZIP/Postal Code', 'premise' ); ?>:</label>
			<input type="text" name="accesspress-checkout[card-postal]" id="accesspress-checkout-card-postal" class="input-text input-text-short" size="12" value="<?php echo esc_attr( $args['card-postal'] ); ?>" />
		</div>
		

	</div>
<?php	
	
}