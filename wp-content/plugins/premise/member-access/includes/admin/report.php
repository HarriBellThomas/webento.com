<?php
class Memberaccess_Report_Settings {

	var $settings_field = 'premise-reports';

	function __construct() {

		add_action( 'admin_menu', array( $this, 'add_menu' ), 20 );

	}

	function add_menu() {

		$hook = add_submenu_page( 'premise-member', __( 'Reports', 'premise' ), __( 'Reports', 'premise' ), 'manage_options', 'premise-reports', array( $this, 'admin_page' ) );
		add_action("admin_print_styles-{$hook}", array( $this, 'enqueue_admin_css' ) );

	}

	function admin_page() {

		global $wpdb;

		$args = empty( $_POST ) ? array() : $_POST['premise-report'];
		$default_end = time();
		$default_start = $default_end - ( 86400 * 7 );

		$args = wp_parse_args( $args, array(
			'start-day' => date( 'j', $default_start ),
			'start-month' => date( 'n', $default_start ),
			'start-year' => date( 'Y', $default_start ),
			'end-day' => date( 'j', $default_end ),
			'end-month' => date( 'n', $default_end ),
			'end-year' => date( 'Y', $default_end ),
			'type' => 'sales',
		) );
	?>
		<div class="wrap">
			<form method="post" action="<?php menu_page_url( 'premise-reports' ); ?>">

			<?php 
			wp_nonce_field( $this->settings_field );
			screen_icon( $this->settings_field );
			?>
			<h2>
				<?php
				echo esc_html( get_admin_page_title() );
				submit_button( __( 'Submit', 'premise' ), 'button-primary accesspress-h2-button', 'submit', false );
				?>
			</h2>

			<div class="premise-date-range">
				<label for="premise-report-start-day"><?php _e( 'Start Date:', 'premise' ); ?></label>

				<input type="text" name="premise-report[start-day]" id="premise-report-start-day" size="2" value="<?php echo $args['start-day']; ?>" />

				<select name="premise-report[start-month]" id="premise-report-start-month">
					<?php
					foreach ( range( 1, 12 ) as $month ) {
						printf( '<option value="%d" %s>%d</option>', $month, selected( $month, $args['start-month'], false ), $month );
					}
					?>
				</select>

				<select name="premise-report[start-year]" id="premise-report-start-year">
					<?php
					$thisyear = (int) date('Y');
					foreach ( range( $args['start-year'] - 2, $args['end-year'] ) as $year ) {
						printf( '<option value="%d" %s>%d</option>', $year, selected( $year, $args['start-year'], false ), $year );
					}
					?>
				</select>
				<br />

				<label for="premise-report-end-day"><?php _e( 'End Date:', 'premise' ); ?></label>

				<input type="text" name="premise-report[end-day]" id="premise-report-end-day" size="2" value="<?php echo $args['end-day']; ?>" />

				<select name="premise-report[end-month]" id="premise-report-end-month">
					<?php
					foreach ( range( 1, 12 ) as $month ) {
						printf( '<option value="%d" %s>%d</option>', $month, selected( $month, $args['end-month'], false ), $month );
					}
					?>
				</select>

				<select name="premise-report[end-year]" id="premise-report-end-year">
					<?php
					$thisyear = (int) date('Y');
					foreach ( range( $args['start-year'] - 2, $args['end-year'] ) as $year ) {
						printf( '<option value="%d" %s>%d</option>', $year, selected( $year, $args['end-year'], false ), $year );
					}
					?>
				</select>
				<?php
				if ( accesspress_get_option( 'authorize_net_recurring' ) ) {
				?>
				<br /><br />

				<label for="premise-report-type"><?php _e( 'Select Report:', 'premise' ); ?></label>

				<select name="premise-report[type]" id="premise-report-type">
					<?php
					foreach ( array( 'sales' => __( 'Sales', 'premise' ), 'subscription' => __( 'Subscription Renewals', 'premise' ) ) as $type => $desc ) {
						printf( '<option value="%s" %s>%s</option>', $type, selected( $type, $args['type'], false ), $desc );
					}
					?>
				</select>
				<?php
				} else {
					echo '<input type="hidden" name="premise-report[type]" value="sales" />';
				}
				?>
			</div>
		</form>
			<hr />
	<?php

		if ( ! empty( $_POST ) && wp_verify_nonce( $_POST['_wpnonce'], $this->settings_field ) ) {

			$end_date_ts = strtotime( $args['end-year'] . '-' . $args['end-month'] . '-' . $args['end-day'] ) + 1;
			$start_date_ts = strtotime( $args['start-year'] . '-' . $args['start-month'] . '-' . $args['start-day'] );
			$end_date = date( 'Y-m-d', $end_date_ts );
			$start_date = $args['start-year'] . '-' . $args['start-month'] . '-' . $args['start-day'];

			if ( $args['type'] == 'subscription' )
				$order_rows = $this->build_renewal_table( $start_date_ts, $end_date_ts );
			else
				$order_rows = $this->build_order_table( $start_date, $end_date );

			if ( ! empty( $order_rows ) ) {

				echo '<table class="premise-report-table">';

				foreach( $order_rows as $key => $order ) {

					$class = '';
					if ( ! is_numeric( $key ) )
						$class = 'class="total"';
					elseif ( $key % 2 )
						$class = 'class="alt"';

					printf( '<tr %s><td>%s</td><tr>', $class, implode( '</td><td>', $order ) );

				}

				echo '</table>';

			}
		}
	?>
		</div>
	
	<?php
	}

	function build_order_table( $start_date, $end_date ) {

		global $wpdb;

		$orders = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'acp-orders' AND post_status = 'publish' AND post_date BETWEEN %s AND %s ORDER BY post_date DESC", $start_date, $end_date ) );
		$details = $wpdb->get_results( "SELECT * FROM {$wpdb->postmeta} WHERE post_id IN (" . implode( ',', $orders ) . ')' );

		if ( empty( $orders ) || empty( $details ) )
			return array();

		$order_details = array();
		$order_table = array( 'header' => array(
			__( 'Member', 'premise' ),
			__( 'Product', 'premise' ),
			__( 'Date', 'premise' ),
			__( 'Price', 'premise' )
		) );

		$date_format = get_option( 'date_format' );
		$order_total = 0;

		foreach( $details as $meta ) {

			if ( ! isset( $order_details[$meta->post_id] ) )
				$order_details[$meta->post_id] = array();

			$order_details[$meta->post_id][$meta->meta_key] = $meta->meta_value;
			if ( $meta->meta_key == '_acp_order_price' )
				$order_total += $meta->meta_value;

		}

		foreach( $orders as $order ) {

			$row = array();

			$user = isset( $order_details[$order]['_acp_order_member_id'] ) ? get_user_by( 'id', $order_details[$order]['_acp_order_member_id'] ) : null;
			$row[] = $user ? sprintf( '%s - %s %s', $user->user_login, $user->first_name, $user->last_name ) : '';

			$product = isset( $order_details[$order]['_acp_order_product_id'] ) ? get_post( $order_details[$order]['_acp_order_product_id'] ) : null;
			$row[] = $product ? $product->post_title : '';

			$row[] = isset( $order_details[$order]['_acp_order_time'] ) ? date( $date_format, $order_details[$order]['_acp_order_time'] ) : '';
			$row[] = isset( $order_details[$order]['_acp_order_price'] ) ? $order_details[$order]['_acp_order_price'] : '';

			$order_table[] = $row;

		}

		$order_table['total'] = array( __( 'Total Sales', 'premise' ), '', count( $orders ), $order_total );

		return $order_table;

	}

	function build_renewal_table( $start_date_ts, $end_date_ts ) {

		global $wpdb;

		$subscriptions = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_acp_order_renewal_time' AND meta_value BETWEEN %s AND %s", $start_date_ts, $end_date_ts ) );
		$orders = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'acp-orders' AND post_status = 'publish' AND ID IN (" . implode( ',', $subscriptions ) . ')' );
		$details = $wpdb->get_results( "SELECT * FROM {$wpdb->postmeta} WHERE post_id IN (" . implode( ',', $orders ) . ')' );

		if ( empty( $orders ) || empty( $details ) )
			return array();

		$order_details = array();
		$order_table = array( 'header' => array(
			__( 'Member', 'premise' ),
			__( 'Product', 'premise' ),
			__( 'Purchase Date', 'premise' ),
			__( 'Renewal Date', 'premise' ),
			__( 'Subscription Price', 'premise' ),
			__( 'Action', 'premise' ),
		) );

		$date_format = get_option( 'date_format' );
		$order_total = 0;

		foreach( $details as $meta ) {

			if ( ! isset( $order_details[$meta->post_id] ) )
				$order_details[$meta->post_id] = array();

			$order_details[$meta->post_id][$meta->meta_key] = $meta->meta_value;
			if ( $meta->meta_key == '_acp_order_price' )
				$order_total += $meta->meta_value;

		}

		if ( $raw )
			return $order_details;

		$order_sort = array();
		foreach( $orders as $order ) {

			$key = $order_details[$order->ID]['_acp_order_renewal_time'];
			while( isset( $order_sort[$key] ) )
				$key++;

			$order_sort[$key] = $order;

		}
		ksort( $order_sort );

		foreach( $order_sort as $order ) {

			$row = array();

			$user = isset( $order_details[$order]['_acp_order_member_id'] ) ? get_user_by( 'id', $order_details[$order]['_acp_order_member_id'] ) : null;
			$row[] = $user ? sprintf( '%s - %s %s', $user->user_login, $user->first_name, $user->last_name ) : '';

			$product = isset( $order_details[$order]['_acp_order_product_id'] ) ? get_post( $order_details[$order]['_acp_order_product_id'] ) : null;
			$row[] = $product ? $product->post_title : '';

			$row[] = isset( $order_details[$order]['_acp_order_time'] ) ? date( $date_format, $order_details[$order]['_acp_order_time'] ) : '';
			$row[] = isset( $order_details[$order]['_acp_order_renewal_time'] ) ? date( $date_format, $order_details[$order]['_acp_order_renewal_time'] ) : '';
			$row[] = isset( $order_details[$order]['_acp_order_price'] ) ? $order_details[$order]['_acp_order_price'] : '';

			$renew_url = add_query_arg( array( 'action' => 'renew', 'subscription' => $order, 'key' => wp_create_nonce( 'renew-subscription-' . $order ) ), menu_page_url( 'premise-reports', false ) );
			$row[] = sprintf( __( '<a href="%s" %s>Renew</a>', 'premise' ), $renew_url, 'target="_blank"' );

			$order_table[$order] = $row;

		}

		$order_table['total'] = array( __( 'Total Sales', 'premise' ), '', '', count( $orders ), $order_total );

		return $order_table;

	}

	function enqueue_admin_css() {

		wp_enqueue_style( 'premise-admin', PREMISE_RESOURCES_URL . 'premise-admin.css', array( 'thickbox' ), PREMISE_VERSION );

	}
}

add_action( 'init', 'memberaccess_report_settings_init' );
/**
 * 
 */
function memberaccess_report_settings_init() {

	new Memberaccess_Report_Settings;

}