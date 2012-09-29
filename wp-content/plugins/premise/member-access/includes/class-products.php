<?php
/**
 * AccessPress Products registration and management.
 *
 * @package AccessPress
 */


/**
 * Handles the registration and management of products.
 *
 * This class handles the registration of the 'acp-products' Custom Post Type, which stores
 * all products created with AccessPress. It also allows you to manage, edit, and (if need be) delete
 * products.
 *
 * It uses the post meta API (custom fields) to store most of the product information, such as:
 * - Product Price
 * - Product Description
 * - Product Payment method(s)
 * - Product duration (length of time, in days, purchaser has access to this product)
 * - Product receipt email subject line
 * - Product receipt email intro text
 *
 * The Product Name is the post title.
 * The Product ID is the numerical post ID.
 * The Access Level(s) this product grants are stored as a custom taxonomy. Each Access Level is a term.
 *
 * @since 0.1.0
 *
 */
class AccessPress_Products {


	/** Constructor */
	function __construct() {

		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		
		add_filter( 'manage_edit-acp-products_columns', array( $this, 'columns_filter' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'columns_data' ) );
		add_action( 'save_post', array( $this, 'metabox_save' ), 1, 2 );
		add_action( 'premise_membership_create_order', array( $this, 'email_purchase_notification' ), 10, 2 );

	}

	/**
	 * Register the Products post type
	 */
	function register_post_type() {

		register_post_type( 'acp-products',
			array(
				'labels' => array(
					'name'               => __( 'Products', 'premise' ),
					'singular_name'      => __( 'Product', 'premise' ),
					'add_new'            => __( 'Create New Product', 'premise' ),
					'add_new_item'       => __( 'Create New Product', 'premise' ),
					'edit'               => __( 'Edit Product', 'premise' ),
					'edit_item'          => __( 'Edit Product', 'premise' ),
					'new_item'           => __( 'New Product', 'premise' ),
					'view'               => __( 'View Product', 'premise' ),
					'view_item'          => __( 'View Product', 'premise' ),
					'search_items'       => __( 'Search Products', 'premise' ),
					'not_found'          => __( 'No Products found', 'premise' ),
					'not_found_in_trash' => __( 'No Products found in Trash', 'premise' )
				),
				'show_in_menu'         => 'premise-member',
				'supports'             => array( 'title' ),
				'taxonomies'           => array( 'acp-access-level' ),
				'register_meta_box_cb' => array( $this, 'metaboxes' ),
				'public'               => false,
				'show_ui'              => true,
				'rewrite'              => false,
				'query_var'            => false
			)
		);

	}
	
	function register_taxonomy() {

		register_taxonomy( 'acp-access-level', array( 'acp-products' ), array(
			'label'        => __( 'Access Levels', 'premise' ),
			'labels'       => array(
				'name'                       => __( 'Access Levels', 'premise' ),
				'singular_name'              => __( 'Access Level', 'premise' ),
				'separate_items_with_commas' => __( 'Separate access levels with commas', 'premise' ),
				'choose_from_most_used'      => __( 'Choose from previously used access levels', 'premise' )
			),
			'public'       => false,
			'show_ui'      => true,
			'hierarchical' => false,
			'query_var'    => false,
			'rewrite'      => false
		) );

	}

	/**
	 * Register the metaboxes
	 */
	function metaboxes() {

		add_meta_box( 'accesspress-product-details-metabox', __( 'Product Details', 'premise' ), array( $this, 'details_metabox' ), 'acp-products', 'normal' );
		remove_meta_box( 'slugdiv', 'acp-products', 'normal' );

	}

	function details_metabox() {
		global $post;
		
		if ( 'publish' == $post->post_status ) {

			$purchase_link = accesspress_get_checkout_link( $post->ID );
			if ( ! $purchase_link )
				$purchase_link = __( 'Checkout page has not been configured.', 'premise' );
			
			echo '<p><strong>' . __( 'Purchase link:', 'premise' ) . '</strong> ' . $purchase_link . '</p>';

		}
	?>

		<input type="hidden" name="accesspress-products-nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />

		<p>
			<label for="accesspress_product_meta[_acp_product_description]"><?php _e( 'Product Description', 'premise' ); ?>:</label>
			<br />
			<textarea class="large-text" rows="4" name="accesspress_product_meta[_acp_product_description]" id="accesspress_product_meta[_acp_product_description]"><?php echo esc_textarea( accesspress_get_custom_field( '_acp_product_description' ) ); ?></textarea>
		</p>

		<p>
			<label for="accesspress_product_meta[_acp_product_price]"><?php _e( 'Product Price', 'premise' ); ?>:
			<br />
			$</label><input class="small-text" type="text" name="accesspress_product_meta[_acp_product_price]" id="accesspress_product_meta[_acp_product_price]" value="<?php echo esc_attr( accesspress_get_custom_field( '_acp_product_price' ) ); ?>" />
		</p>

		<p>
			<label for="accesspress_product_meta[_acp_product_duration]"><?php _e( 'Product Duration', 'premise' ); ?> <span class="description"><?php _e( 'Enter 0 for lifetime', 'premise' ); ?></span>:
			<br />
			</label><input class="small-text" type="text" name="accesspress_product_meta[_acp_product_duration]" id="accesspress_product_meta[_acp_product_duration]" value="<?php echo esc_attr( accesspress_get_custom_field( '_acp_product_duration', 0 ) ); ?>" />
			<label for="accesspress_product_meta[_acp_product_duration]"><?php _e( 'days', 'premise' ); ?></label>
		</p>
	<?php
		if ( accesspress_get_option( 'authorize_net_recurring' ) ) {
	?>

		<p>
			<input type="checkbox" name="accesspress_product_meta[_acp_product_subscription]" id="accesspress_product_meta[_acp_product_subscription]" value="1" <?php checked( '1', accesspress_get_custom_field( '_acp_product_subscription' ) ); ?> />
			<label for="accesspress_product_meta[_acp_product_subscription]"><?php _e( 'This is a subscription', 'premise' ); ?></label>
		</p>
	<?php
		}
	?>
		<hr />
		<p>
			<input type="checkbox" name="accesspress_product_meta[_acp_product_email_enabled]" id="accesspress_product_meta[_acp_product_email_enabled]" value="1" <?php checked( '1', accesspress_get_custom_field( '_acp_product_email_enabled' ) ); ?> />
			<label for="accesspress_product_meta[_acp_product_email_enabled]"><strong><?php _e( 'Send an Email Receipt', 'premise' ); ?></strong></label>
		</p>

		<p>
			<label for="accesspress_product_meta[_acp_product_email_receipt_subject]"><?php _e( 'Email Receipt Subject Line', 'premise' ); ?>:
			<br />
			</label><input class="large-text" type="text" name="accesspress_product_meta[_acp_product_email_receipt_subject]" id="accesspress_product_meta[_acp_product_email_receipt_subject]" value="<?php echo esc_attr( accesspress_get_custom_field( '_acp_product_email_receipt_subject', sprintf( __( 'Receipt for purchase at %s', 'premise' ), get_bloginfo( 'name' ) ) ) ); ?>" />
		</p>

		<p>
			<label for="accesspress_product_meta[_acp_product_email_receipt_intro]"><?php _e( 'Email Receipt Intro Text', 'premise' ); ?>:</label>
			<br />
			<textarea class="large-text" rows="4" name="accesspress_product_meta[_acp_product_email_receipt_intro]" id="accesspress_product_meta[_acp_product_email_receipt_intro]"><?php echo esc_textarea( accesspress_get_custom_field( '_acp_product_email_receipt_intro' ) ); ?></textarea>
		</p>

	<?php
		if ( memberaccess_is_vbulletin_enabled() ) {
	?>
		<hr />
		<p>
			<label for="accesspress_product_meta[_acp_product_vbulletin_group]"><strong><?php _e( 'vBulletin User Group', 'premise' ); ?></strong>:
			<br />
			</label><input type="text" name="accesspress_product_meta[_acp_product_vbulletin_group]" id="accesspress_product_meta[_acp_product_vbulletin_group]" value="<?php echo esc_attr( accesspress_get_custom_field( '_acp_product_vbulletin_group' ) ); ?>" />
			<br />
			<span class="description"><?php _e( 'Choose the vBulletin user group that Premise Members will be added to for this product.', 'premise' ); ?></span>
		</p>
	<?php
		}
	}

	/**
	 * Save the form data from the metaboxes
	 */
	function metabox_save( $post_id, $post ) {

		/**	Verify the nonce */
		if ( ! isset( $_POST['accesspress-products-nonce'] ) || ! wp_verify_nonce( $_POST['accesspress-products-nonce'], plugin_basename( __FILE__ ) ) )
			return $post->ID;

		/**	Don't try to save the data under autosave, ajax, or future post */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			return;
		if ( defined( 'DOING_CRON' ) && DOING_CRON )
			return;

		/**	Check if user is allowed to edit this */
		if ( ! current_user_can( 'edit_post', $post->ID ) )
			return $post->ID;

		/** Don't try to store data during revision save */
		if ( 'revision' == $post->post_type )
			return;

		/** Merge defaults with user submission */
		$values = wp_parse_args( $_POST['accesspress_product_meta'], array(
			'_acp_product_price'			=> 0,
			'_acp_product_description'		=> '',
			'_acp_product_access_method'		=> 'free',
			'_acp_product_payment_authorize_net'	=> 0,
			'_acp_product_subscription'		=> 0,
			'_acp_product_payment_paypal'		=> 0,
			'_acp_product_payment_dummycc'		=> 0,
			'_acp_product_duration'			=> 0,
			'_acp_product_email_enabled'		=> 0,
			'_acp_product_email_receipt_subject'	=> '',
			'_acp_product_email_receipt_intro'	=> '',
			'_acp_product_vbulletin_group'		=> '',
		) );

		/** Sanitize */
		$values = $this->sanitize( $values );

		/** Loop through values, to potentially store or delete as custom field */
		foreach ( (array) $values as $key => $value ) {
			/** Save, or delete if the value is empty */
			if ( $value )
				update_post_meta( $post->ID, $key, $value );
			else
				delete_post_meta( $post->ID, $key );
		}

	}

	function email_purchase_notification( $member, $order_details ) {

		global $product_post, $product_member;

		if ( empty( $order_details['_acp_order_product_id'] ) || ! get_post_meta( $order_details['_acp_order_product_id'], '_acp_product_email_enabled', true ) )
			return;

		$product_member = get_user_by( 'id', $member );
		if ( ! $product_member || ! is_email( $product_member->user_email ) )
			return;

		$product_post = get_post( $order_details['_acp_order_product_id'] );
		if ( empty( $product_post ) )
			return;

		$email_subject = get_post_meta( $product_post->ID, '_acp_product_email_receipt_subject', true );
		if( ! empty( $email_subject ) )
			$email_subject = do_shortcode( $email_subject );

		$email_body = get_post_meta( $product_post->ID, '_acp_product_email_receipt_intro', true );
		if( ! empty( $email_body ) )
			$email_body = do_shortcode( $email_body );

		$email_from = memberaccess_get_email_receipt_address();
		$from_description = accesspress_get_option( 'email_receipt_name' );

		wp_mail( $product_member->user_email, $email_subject, $email_body, "From: \"{$from_description}\" <{$email_from}>" );

	}

	/**
	 * Filter the columns in the "Orders" screen, define our own.
	 */
	function columns_filter ( $columns ) {

		$new_columns = array(
			'product_price'		=> __( 'Price', 'premise' ),
			'access_level'		=> __( 'Access Levels', 'premise' )
		);

		return array_merge( $columns, $new_columns );

	}

	/**
	 * Filter the data that shows up in the columns in the "Orders" screen, define our own.
	 */
	function columns_data( $column ) {

		global $post;

		if ( 'acp-products' != $post->post_type )
			return;

		switch( $column ) {
			case "product_price":
				$price = accesspress_get_custom_field( '_acp_product_price' );
				if ( ! $price )
					break;

				printf( __( '<p>%.2f</p>', 'premise' ), $price );
				break;
			case "access_level":
				echo get_the_term_list( $post->ID, 'acp-access-level', '', '<br />', '' );
				break;
		}

	}
	/**
	 * Use this function to sanitize an array of values before storing.
	 *
	 * @todo a bit more thorough sanitization
	 */
	function sanitize( $values = array() ) {

		return (array) $values;

	}
	
}