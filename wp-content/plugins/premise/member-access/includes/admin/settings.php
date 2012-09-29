<?php
class AccessPress_Admin_Settings extends AccessPress_Admin_Boxes {

	function __construct() {

		$settings_field = MEMBER_ACCESS_SETTINGS_FIELD;
		$default_settings = array(
			'ssl_checkout' => 1,
			'ssl_everywhere' => 0,
			'vbulletin_bridge' => 0,
			'vbulletin_group' => '',
			'checkout_page' => 0,
			'login_page' => 0,
			'member_page' => 0,
			'default_country' => 'US',
			'gateway_live_mode' => 0,
			'paypal_express_username' => '',
			'paypal_express_password' => '',
			'paypal_express_signature' => '',
			'authorize_net_id' => '',
			'authorize_net_key' => '',
			'authorize_net_recurring' => 0,
			'email_receipt_name' => get_bloginfo( 'name' ),
			'email_receipt_address' => '',
			'uploads_dir' => 'member-access',
		);

		$menu_ops = array(
			'main_menu' => array(
				'page_title'	=> __( 'Member Access Settings', 'premise' ),
				'menu_title'	=> __( 'Member Access', 'premise' ),
				'capability'	=> 'manage_options',
				'icon_url'		=> PREMISE_RESOURCES_URL . 'images/icon-16x16-member.png',
				'position'		=> '56.501'
			),
			'first_submenu' => array( /** Do not use without 'main_menu' */
				'page_title'	=> __( 'Member Access Settings', 'premise' ),
				'menu_title'	=> __( 'Settings', 'premise' ),
				'capability'	=> 'manage_options'
			),
		);

		$page_ops = array(); /** Just use the defaults */

		$this->create( 'premise-member', $menu_ops, $page_ops, $settings_field, $default_settings );

		// enqueue CSS
		foreach( array( 'toplevel_page_premise-member', 'edit.php', 'post.php', 'post-new.php' ) as $hook )
			add_action("admin_print_styles-{$hook}", array( $this, 'enqueue_admin_css' ) );

	}

	function metaboxes() {

		add_meta_box( 'member-access-main-settings', __( 'Main Settings', 'premise' ), array( $this, 'main_settings_metabox' ), $this->pagehook, 'main' );
		add_meta_box( 'member-access-payment-settings', __( 'Payment Settings', 'premise' ), array( $this, 'payment_settings_metabox' ), $this->pagehook, 'main' );
		add_meta_box( 'member-access-email-settings', __( 'Email Settings', 'premise' ), array( $this, 'email_settings_metabox' ), $this->pagehook, 'main' );
		add_meta_box( 'member-access-file-settings', __( 'File Protection Settings', 'premise' ), array( $this, 'file_settings_metabox' ), $this->pagehook, 'main' );
		add_meta_box( 'member-access-forum-settings', __( 'Forum Settings', 'premise' ), array( $this, 'forum_settings_metabox' ), $this->pagehook, 'main' );

		#add_meta_box( 'accesspress-', __( '', 'premise' ), array( $this, '_metabox' ), $this->pagehook, 'main' );

	}

	function main_settings_metabox() {
//@todo: translation support on title attributes
	?>

		<p>
			<input type="checkbox" name="<?php echo $this->get_field_name( 'ssl_checkout' ); ?>" id="<?php echo $this->get_field_id( 'ssl_checkout' ); ?>" value="1"<?php checked( $this->get_field_value( 'ssl_checkout' ) ); ?> />
			<label for="<?php echo $this->get_field_id( 'ssl_checkout' ); ?>"><?php _e( 'Enable SSL on checkout page?', 'premise' ); ?></label>
			<br />
			<input type="checkbox" name="<?php echo $this->get_field_name( 'ssl_everywhere' ); ?>" id="<?php echo $this->get_field_id( 'ssl_everywhere' ); ?>" value="1"<?php checked( $this->get_field_value( 'ssl_everywhere' ) ); ?> />
			<label for="<?php echo $this->get_field_id( 'ssl_everywhere' ); ?>" title="Enable SSL on checkout pages"><?php _e( 'Enable SSL everywhere?', 'premise' ); ?></label>
		</p>

		<p>
			<?php _e( 'Checkout Page', 'premise' ); ?>:<br />
			<?php 
			wp_dropdown_pages( array( 
				'name' => $this->get_field_name( 'checkout_page' ),
				'id' => $this->get_field_id( 'checkout_page' ),
				'selected' => $this->get_field_value( 'checkout_page' ),
				'show_option_none' => __( 'Select Checkout Page', 'premise' )
			) );
			?>
		</p>

		<p>
			<?php _e( 'Login Page', 'premise' ); ?>:<br />
			<?php 
			wp_dropdown_pages( array( 
				'name' => $this->get_field_name( 'login_page' ),
				'id' => $this->get_field_id( 'login_page' ),
				'selected' => $this->get_field_value( 'login_page' ),
				'show_option_none' => __( 'Select Login Page', 'premise' )
			) );
			?>
		</p>

		<p>
			<?php _e( 'Member Page', 'premise' ); ?>:<br />
			<?php
			wp_dropdown_pages( array(
				'name' => $this->get_field_name( 'member_page' ),
				'id' => $this->get_field_id( 'member_page' ),
				'selected' => $this->get_field_value( 'member_page' ),
				'show_option_none' => __( 'Select Member Page', 'premise' )
			) );
			?>
		</p>

		<p>
			<?php _e( 'Default Country', 'premise' ); ?>:<br />
			<select name="<?php echo $this->get_field_name( 'default_country' ); ?>">
				<?php
				foreach ( (array) accesspress_get_countries( $this->get_field_value( 'default_country' ) ) as $code => $label ) {
					printf( '<option value="%s" %s>%s</option>', esc_attr( $code ), selected( $this->get_field_value( 'default_country' ), $code, 0 ), esc_html( $label ) );
				}
				?>
			</select>
		</p>
	
	<?php
	}

	function payment_settings_metabox() {
	?>

		<p>
			<strong><?php _e( 'Payment Gateway Mode', 'premise' ); ?></strong>:
			<select name="<?php echo $this->get_field_name( 'gateway_live_mode' ); ?>">
				<option value="0" <?php selected( '0' == $this->get_field_value( 'gateway_live_mode' ) ); ?>><?php _e( 'Test', 'premise' ); ?></option>
				<option value="1" <?php selected( '1', $this->get_field_value( 'gateway_live_mode' ) ); ?>><?php _e( 'Live', 'premise' ); ?></option>
			</select>
		</p>

		<p>
			<input type="checkbox" name="<?php echo $this->get_field_name( 'authorize_net_recurring' ); ?>" id="<?php echo $this->get_field_id( 'authorize_net_recurring' ); ?>" value="1" <?php checked( '1', $this->get_field_value( 'authorize_net_recurring' ) ); ?> />
			<label for="<?php echo $this->get_field_id( 'authorize_net_recurring' ); ?>"><?php _e( 'Enable Recurring Payment Option?', 'premise' ); ?></label>
			<br />
			<span class="description"><?php _e( 'Enable subscriptions on individual products.', 'premise' ) ?></span>
		</p>

		<p>
			<strong><?php _e( 'PayPal for Express Checkout and Website Payments Pro', 'premise' ); ?></strong>
			<a href="<?php echo add_query_arg( array( 'test-paypal' => 'true' ), menu_page_url( 'premise-member', false ) ); ?>" class="button secondary"><?php _e( 'Test', 'premise' ); ?></a>
		</p>

		<p>
			<?php _e( 'Username', 'premise' ); ?>:
			<br />
			<input type="text" name="<?php echo $this->get_field_name( 'paypal_express_username' ); ?>" id="<?php $this->get_field_id( 'paypal_express_username' ); ?>" value="<?php echo $this->get_field_value( 'paypal_express_username' ); ?>" style="min-width:50%" />
			<br />
			<?php _e( 'Password', 'premise' ); ?>:
			<br />
			<input type="password" name="<?php echo $this->get_field_name( 'paypal_express_password' ); ?>" id="<?php $this->get_field_id( 'paypal_express_password' ); ?>" value="<?php echo $this->get_field_value( 'paypal_express_password' ); ?>" style="min-width:50%" />
			<br />
			<?php _e( 'Signature', 'premise' ); ?>:
			<br />
			<input type="password" name="<?php echo $this->get_field_name( 'paypal_express_signature' ); ?>" id="<?php $this->get_field_id( 'paypal_express_signature' ); ?>" value="<?php echo $this->get_field_value( 'paypal_express_signature' ); ?>" style="min-width:50%" />
		</p>

		<?php
		if ( $this->get_field_value( 'authorize_net_recurring' ) ) {

			$ipn = add_query_arg( array( 'premiseipn' => 'paypal' ), site_url( '/' ) );
		?>
		<p>
			<?php _e( 'Recurring payments for Paypal require setting up Paypal IPN. Use the following URL for Paypal IPN:', 'premise' ); ?>
			<br />
			<textarea disabled="disabled" rows="2" cols="100"><?php echo esc_html( $ipn ); ?></textarea>
		</p>
		<?php } ?>

		<p>
			<strong><?php _e( 'Authorize.net', 'premise' ); ?></strong>
			<a href="<?php echo add_query_arg( array( 'test-cc' => 'true' ), menu_page_url( 'premise-member', false ) ); ?>" class="button secondary"><?php _e( 'Test', 'premise' ); ?></a>
		</p>

		<p>
			<?php _e( 'API Login ID', 'premise' ); ?>:<br /><input type="text" name="<?php echo $this->get_field_name( 'authorize_net_id' ); ?>" id="<?php $this->get_field_id( 'authorize_net_id' ); ?>" value="<?php echo $this->get_field_value( 'authorize_net_id' ); ?>" style="min-width:50%" />
			<br />
			<?php _e( 'Transaction Key', 'premise' ); ?>:<br /><input type="text" name="<?php echo $this->get_field_name( 'authorize_net_key' ); ?>" id="<?php $this->get_field_id( 'authorize_net_key' ); ?>" value="<?php echo $this->get_field_value( 'authorize_net_key' ); ?>" style="min-width:50%" />
		</p>

		<?php
		if ( $this->get_field_value( 'authorize_net_recurring' ) ) {

			$cron = sprintf( 'php -f %s \'key=%s&url=%s&path=%s\'', PREMISE_LIB_DIR . 'cron/recurring-cron-script.php', memberaccess_get_cron_key(), site_url( '/' ), ABSPATH );
		?>
		<p>
			<?php _e( 'Recurring payments for Authorize.net require Server cron. Use the following command for cron:', 'premise' ); ?>
			<br />
			<textarea disabled="disabled" rows="4" cols="100"><?php echo esc_html( $cron ); ?></textarea>
		</p>
		<?php } ?>

	<?php
	}

	function email_settings_metabox() {
	?>

		<p><strong><?php _e( 'Signup Receipt', 'premise' ); ?></strong></p>

		<p><span class="description"><?php _e( 'When a user activates a membership, they will be emailed notifying them of their new account. Use the settings below to control how that email looks.', 'premise' ); ?></span></p>

		<p>
			<?php _e( 'From Name', 'premise' ); ?>:<br /><input type="text" name="<?php echo $this->get_field_name( 'email_receipt_name' ); ?>" id="<?php $this->get_field_id( 'email_receipt_name' ); ?>" value="<?php echo $this->get_field_value( 'email_receipt_name' ); ?>" />
			<br />
			<?php _e( 'From Address', 'premise' ); ?>:<br /><input type="text" name="<?php echo $this->get_field_name( 'email_receipt_address' ); ?>" id="<?php $this->get_field_id( 'email_receipt_address' ); ?>" value="<?php echo $this->get_field_value( 'email_receipt_address' ); ?>" /> <span class="description"><?php _e( 'Default is wordpress@{yourdomain.com}', 'premise' ); ?>
		</p>

		<p><span class="description"><?php _e( 'You can create a custom subject line and email intro for each product by editing it.', 'premise' ); ?></span></p>

	<?php	
	}

	function file_settings_metabox() {

		$uploads = wp_upload_dir();
		$member_dir = trailingslashit( $uploads['basedir'] ) . $this->get_field_value( 'uploads_dir' );
		$member_htaccess = trailingslashit( $member_dir ) . '.htaccess';
		$member_dir_message = '';

		if ( ! is_dir( $member_dir ) ) {

			if ( ! wp_mkdir_p( $member_dir ) )
				$member_dir_message = sprintf( __( 'Error creating the <em>%s</em> folder.', 'premise' ), $member_dir );

		}

		if ( is_dir( $member_dir ) && ! is_file( $member_htaccess ) || ! filesize( $member_htaccess ) ) {

			$fh = fopen( $member_htaccess, 'w' );
			if ( $fh ) {

				if ( ! fwrite( $fh, "Order deny,allow\ndeny from all\n" ) )
					$member_dir_message = sprintf( __( 'Error creating the <em>%s</em> file.', 'premise' ), $member_htaccess );

				fclose( $fh );

			} else {

				$member_dir_message = sprintf( __( 'Error creating the <em>%s</em> file.', 'premise' ), $member_htaccess );

			}
		}
	?>

		<p><span class="description"><?php _e( 'Choose the folder you will use to store protected files. (relative to your uploads directory)', 'premise' ); ?></span></p>

		<p><?php echo trailingslashit( $uploads['basedir'] ); ?><input type="text" name="<?php echo $this->get_field_name( 'uploads_dir' ); ?>" id="<?php $this->get_field_id( 'uploads_dir' ); ?>" value="<?php echo $this->get_field_value( 'uploads_dir' ); ?>" /></p>

	<?php
		if ( ! empty( $member_dir_message ) ) {
	?>
		<p><span class="description"><?php echo $member_dir_message; ?></span></p>
	<?php
		}
	}

	function forum_settings_metabox() {
	?>

		<p>
			<input type="checkbox" name="<?php echo $this->get_field_name( 'vbulletin_bridge' ); ?>" id="<?php echo $this->get_field_id( 'vbulletin_bridge' ); ?>" value="1"<?php checked( $this->get_field_value( 'vbulletin_bridge' ) ); ?> />
			<label for="<?php echo $this->get_field_id( 'vbulletin_bridge' ); ?>" title="Enable vBulletin Bridge"><?php _e( 'Enable vBulletin Bridge?', 'premise' ); ?></label>
			<?php if ( $this->get_field_value( 'vbulletin_bridge' ) && ! defined( 'VBULLETIN_PATH' ) ) : ?>
			<br />
			<strong><?php _e( 'The vBulletin Bridge requires that you define VBULLETIN_PATH in your wp-config.php', 'premise' ); ?></strong>
			<?php endif; ?>
		</p>

		<p>
			<?php _e( 'vBulletin User Group', 'premise' ); ?>:<br /><input type="text" name="<?php echo $this->get_field_name( 'vbulletin_group' ); ?>" id="<?php $this->get_field_id( 'vbulletin_group' ); ?>" value="<?php echo $this->get_field_value( 'vbulletin_group' ); ?>" /> <br />
			<span class="description"><?php _e( 'Choose the vBulletin user group that Premise Members will be added to.', 'premise' ); ?></span>
		</p>


	<?php
	}

	function enqueue_admin_css() {

		global $pagenow, $post;
		if( in_array( $pagenow, array( 'post.php', 'post-new.php', 'edit.php' ) ) && ! in_array( $post->post_type, array( 'acp-products', 'acp-orders' ) ) )
			return;

		wp_enqueue_style( 'premise-admin', PREMISE_RESOURCES_URL . 'premise-admin.css', array( 'thickbox' ), PREMISE_VERSION );

	}

	/**
	 * Display notices on the save or reset of settings.
	 *
	 * @since 0.1.0
	 *
	 * @return type
	 */
	public function notices() {

		if ( ! accesspress_is_menu_page( $this->page_id ) )
			return;

		if ( isset( $_REQUEST['test-paypal'] ) && 'true' == $_REQUEST['test-paypal'] ) {

			$gateway = new AccessPress_Paypal_Gateway;
			$gateway->test();
			if ( is_wp_error( $gateway->response ) )
				$message = $gateway->response->get_error_message();
			else
				$message = __( 'Paypal Gateway test passed.', 'premise' );

			echo '<div id="message" class="updated"><p><strong>' . $message . '</strong></p></div>';
			return;

		}

		/** test the gateway by requesting info on a non-existent customer */
		if ( isset( $_REQUEST['test-cc'] ) && 'true' == $_REQUEST['test-cc'] ) {

			$gateway = new AccessPress_AuthorizeNet_Gateway;
			$result = $gateway->test();
			if ( ! $result && is_wp_error( $gateway->response ) && 'cc-error' == $gateway->response->get_error_code() )
				$message = __( 'Authorize.Net Gateway test passed.', 'premise' );
			elseif ( is_wp_error( $gateway->response ) )
				$message = $gateway->response->get_error_message();
			else
				$message = __( 'Authorize.Net Gateway test failed.', 'premise' );

			echo '<div id="message" class="updated"><p><strong>' . $message . '</strong></p></div>';
			return;

		}

		return parent::notices();
	}
}

add_action( 'init', 'accesspress_admin_settings_init' );
/**
 * 
 */
function accesspress_admin_settings_init() {

	new AccessPress_Admin_Settings;

}