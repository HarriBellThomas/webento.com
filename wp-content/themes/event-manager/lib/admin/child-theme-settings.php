<?php
/**
 * Event Manager Settings
 * Requires Genesis 1.8 or later
 *
 * This file registers all of this child theme's specific Theme Settings, accessible from
 * Genesis > Event Manager Settings.
 *
 * @package     Event Manager
 * @author      Bill Erickson <bill@billerickson.net>
 * @copyright   Copyright (c) 2011, Bill Erickson
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        https://github.com/billerickson/BE-Genesis-Child
 */ 
 
/**
 * Registers a new admin page, providing content and corresponding menu item
 * for the Child Theme Settings page.
 *
 * @package Event Manager
 * @subpackage Admin
 *
 * @since 1.0.0
 */
class Event_Manager_Settings extends Genesis_Admin_Boxes {
	
	/**
	 * Create an admin menu item and settings page.
	 * 
	 * @since 1.0.0
	 */
	function __construct() {
		
		// Specify a unique page ID. 
		$page_id = 'event-manager';
		
		// Set it as a child to genesis, and define the menu and page titles
		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'genesis',
				'page_title'  => __( 'Genesis - Event Manager Settings', 'social-coup' ),
				'menu_title'  => __( 'Event Manager Settings', 'social-coup' ),
			)
		);
		
		// Set up page options. These are optional, so only uncomment if you want to change the defaults
		$page_ops = array(
		//	'screen_icon'       => 'options-general',
		//	'save_button_text'  => 'Save Settings',
		//	'reset_button_text' => 'Reset Settings',
		//	'save_notice_text'  => 'Settings saved.',
		//	'reset_notice_text' => 'Settings reset.',
		);		
		
		// Give it a unique settings field. 
		// You'll access them from genesis_get_option( 'option_name', 'child-settings' );
		$settings_field = 'social-coup';
		
		// Set the default values
		$default_settings = array(
			'homepage_intro' => sprintf( __( '%s Description for the event will go here. Lorem ipsum dolor sit amet, coas nsectetur adipiscing elit. Maecenas congue aliquet pharetra. Integer tempor ante. Nulla mollis orci non sem bibendum iaculis. Vivamus fauc %s em ipsum dolor sit amet, consectetur adipiscing elit. Maecenas congue aliquet pharetra. Integer sed tempor ante. %s Continue reading %s', 'social-coup' ), '<div class="one-half first"><p>', '</p></div><div class="one-half"><p>', '<br /><a href="#">', '&raquo;</a></p></div>' ),
			'event_date_title' => __( 'November 23-26, 2011', 'social-coup' ),
			'event_date_subtitle' => __( 'Starting at 08:00am', 'social-coup' ),
			'event_date_icon' => __( '23', 'social-coup' ),
			'event_location_title' => __( 'San Francisco', 'social-coup' ),
			'event_location_url' => '',
			'event_location_subtitle' => __( 'Moscone Center, 603 Red River St', 'social-coup' ),
			'event_register_link' => trailingslashit( get_bloginfo( 'url' ) ) . 'registration',
			'event_register_title' => __( 'Register Today >', 'social-coup' ),
			'event_register_subtitle' => __( 'tickets from <strong>$120</strong>', 'social-coup' ),
			'footer_left' => sprintf( __( 'Powered by <a href="%s">Event Manager Blog</a> on the <a href="%s">Genesis Framework</a>', 'social-coup' ), 'http://www.eventmanagerblog.com', 'http://billerickson.net/go/genesis' ),
			'footer_right' => '&copy; ' . date('Y') . ' ' . get_bloginfo( 'name' ) . '. ' . __( 'All rights reserved', 'social-coup') . '.',
		);
		
		// Create the Admin Page
		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );

		// Initialize the Sanitization Filter
		add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitization_filters' ) );
			
	}

	/** 
	 * Set up Sanitization Filters
	 *
	 * See /lib/classes/sanitization.php for all available filters.
	 *
	 * @since 1.0.0
	 */	
	function sanitization_filters() {
	
		genesis_add_option_filter( 'safe_html', $this->settings_field,
			array(
				'homepgae_intro',
				'event_date_title',
				'event_date_subtitle',
				'event_date_icon',
				'event_location_title',
				'event_location_url',
				'event_location_subtitle',
				'event_register_link',
				'event_register_title',
				'event_register_subtitle',
				'footer_left',
				'footer_right',
			) );
	}
	
	/**
	 * Register metaboxes on Social Coup Settings page
	 *
	 * @since 1.0.0
	 *
	 * @see Social_Coup_Settings::event_information() Callback for event information
	 * @see Social_Coup_Settings::homepage_intro() Callback for homepage intro
	 * @see Social_Coup_Settings::footer_text() Callback for footer text
	 */
	function metaboxes() {
		
		add_meta_box( 'event-information', __( 'Event Information', 'social-coup' ), array( $this, 'event_information' ), $this->pagehook, 'main', 'high' );
		add_meta_box( 'homepage-intro', __( 'Homepage Introduction', 'social-coup' ), array( $this, 'homepage_intro' ), $this->pagehook, 'main', 'high' );
		add_meta_box( 'footer-text', __( 'Footer Text', 'social-coup' ), array( $this, 'footer_text' ), $this->pagehook, 'main', 'high' );	
	}

	/**
	 * Callback for Event Information metabox
	 *
	 * @since 1.0.0
	 *
	 * @see Social_Coup_Settings::metaboxes()
	 */
	function event_information() {
			
		echo '<p>' . __( 'Date Title:', 'social-coup' ) . '<br />';
		echo '<input type="text" name="' . $this->get_field_name( 'event_date_title' ) . '" id="' . $this->get_field_id( 'event_date_title' ) . '" value="' . esc_attr( $this->get_field_value( 'event_date_title' ) ) . '" size="50" />';
		echo '</p>';	

		echo '<p>' . __( 'Date Subtitle:', 'social-coup' ) . '<br />';
		echo '<input type="text" name="' . $this->get_field_name( 'event_date_subtitle' ) . '" id="' . $this->get_field_id( 'event_date_subtitle' ) . '" value="' . esc_attr( $this->get_field_value( 'event_date_subtitle' ) ) . '" size="50" />';
		echo '</p>';	
				
		echo '<p>' . __( 'Date Icon Text:', 'social-coup' ) . '<br />';
		echo '<input type="text" name="' . $this->get_field_name( 'event_date_icon' ) . '" id="' . $this->get_field_id( 'event_date_icon' ) . '" value="' . esc_attr( $this->get_field_value( 'event_date_icon' ) ) . '" size="2" />';
		echo '</p>';	

		echo '<p>' . __( 'Location Title:', 'social-coup' ) . '<br />';
		echo '<input type="text" name="' . $this->get_field_name( 'event_location_title' ) . '" id="' . $this->get_field_id( 'event_location_title' ) . '" value="' . esc_attr( $this->get_field_value( 'event_location_title' ) ) . '" size="50" />';
		echo '</p>';	
		
		echo '<p>' . __( 'Location URL:', 'social-coup' ) . '<br />';
		echo '<input type="text" name="' . $this->get_field_name( 'event_location_url' ) . '" id="' . $this->get_field_id( 'event_location_url' ) . '" value="' . esc_url( $this->get_field_value( 'event_location_url' ) ) . '" size="50" />';
		echo '</p>';

		echo '<p>' . __( 'Location Subtitle:', 'social-coup' ) . '<br />';
		echo '<input type="text" name="' . $this->get_field_name( 'event_location_subtitle' ) . '" id="' . $this->get_field_id( 'event_location_subtitle' ) . '" value="' . esc_attr( $this->get_field_value( 'event_location_subtitle' ) ) . '" size="50" />';
		echo '</p>';					

		echo '<p>' . __( 'Register Title:' , 'social-coup' ) . '<br />';
		echo '<input type="text" name="' . $this->get_field_name( 'event_register_title' ) . '" id="' . $this->get_field_id( 'event_register_title' ) . '" value="' . esc_attr( $this->get_field_value( 'event_register_title' ) ) . '" size="50" />';
		echo '</p>';	

		echo '<p>' . __( 'Register Subtitle:', 'social-coup' ) . '<br />';
		echo '<input type="text" name="' . $this->get_field_name( 'event_register_subtitle' ) . '" id="' . $this->get_field_id( 'event_register_subtitle' ) . '" value="' . esc_attr( $this->get_field_value( 'event_register_subtitle' ) ) . '" size="50" />';
		echo '</p>';	

		echo '<p>' . __( 'Register Link:', 'social-coup' ) . '<br />';
		echo '<input type="text" name="' . $this->get_field_name( 'event_register_link' ) . '" id="' . $this->get_field_id( 'event_register_link' ) . '" value="' . esc_url( $this->get_field_value( 'event_register_link' ) ) . '" size="50" />';
		echo '</p>';	

	}
	
	/**
	 * Callback for Homepage Intro metabox
	 *
	 * @since 1.0.0
	 *
	 * @see Social_Coup_Settings::metaboxes()
	 */
	function homepage_intro() {
		
		echo '<p>' . sprintf( __( 'This text is displayed below the site title on the homepage. Use <a href="%s" target="_blank">Column Classes</a> for multiple columns of text.', 'social-coup' ), 'http://www.studiopress.com/tutorials/genesis/content-column-classes' ) . '</p>';

		wp_editor( $this->get_field_value( 'homepage_intro' ), $this->get_field_id( 'homepage_intro'), array() );
	}
	

	/**
	 * Callback for Footer Text metabox
	 *
	 * @since 1.0.0
	 *
	 * @see Social_Coup_Settings::metaboxes()
	 */
	function footer_text() {
		
		echo '<p>' . __( 'Footer Left', 'social-coup' ) . '</p>';
		wp_editor( $this->get_field_value( 'footer_left' ), $this->get_field_id( 'footer_left'), array( 'textarea_rows' => 4 ) );

		echo '<p>' . __( 'Footer Right', 'social-coup' ) . '</p>';
		wp_editor( $this->get_field_value( 'footer_right' ), $this->get_field_id( 'footer_right'), array( 'textarea_rows' => 4 ) );
	}
		
}


/**
 * Add the Theme Settings Page
 *
 * @since 1.0.0
 */
function be_add_social_coup_settings() {
	global $_child_theme_settings;
	$_child_theme_settings = new Event_Manager_Settings;	 	
}
add_action( 'admin_menu', 'be_add_social_coup_settings', 5 );