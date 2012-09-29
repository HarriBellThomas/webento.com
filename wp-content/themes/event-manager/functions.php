<?php
/**
 * Functions
 *
 * @package      Event Manager
 * @author       Bill Erickson <bill@billerickson.net>
 * @copyright    Copyright (c) 2011, Bill Erickson
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */

/**
 * Theme Setup
 *
 * This setup function attaches all of the site-wide functions 
 * to the correct hooks and filters. All the functions themselves
 * are defined below this setup function.
 *
 */

add_action('genesis_setup','child_theme_setup', 15);
function child_theme_setup() {
	
	// ** Backend **	
	
	// Translations
	load_child_theme_textdomain( 'social-coup', get_stylesheet_directory() . '/lib/languages');  
	
	// Image Sizes
	add_image_size ('sc_thumbnail', 405, 375, true );
	
	// Sidebars
	unregister_sidebar( 'sidebar-alt' );
	unregister_sidebar( 'header-right' );
	add_theme_support( 'genesis-footer-widgets', 3 );
	genesis_register_sidebar( array( 'name' => __( 'Home Left', 'social-coup' ), 'id' => 'home-left' ) );
	genesis_register_sidebar( array( 'name' => __( 'Home Middle', 'social-coup' ), 'id' => 'home-middle' ) );
	genesis_register_sidebar( array( 'name' => __( 'Home Right', 'social-coup' ), 'id' => 'home-right' ) );

	// Remove Unused Page Layouts
	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );
		
	// Setup Theme Settings
	include_once( CHILD_DIR . '/lib/admin/child-theme-settings.php');
	
	// Editor Stylesheet
	add_editor_style( 'editor-style.css' );
	
	// Hide Editor on Specific Template Pages
	add_action( 'admin_init', 'sc_hide_editor' );
	
	// Activate Required Plugins
	require_once( CHILD_DIR . '/lib/classes/class-tgm-plugin-activation.php' );
	add_action( 'tgmpa_register', 'cs_register_required_plugins' );
	
	// Add support for custom header 
	add_theme_support( 'genesis-custom-header', array( 'width' => 545, 'height' => 184, 'textcolor' => '333', 'admin_header_callback' => 'sc_admin_style', 'header_callback' => 'sc_custom_header_style' ) );	

	// ** Frontend **
	
	// Remove Edit Link
	add_filter( 'edit_post_link', '__return_false' );
	
	// Viewport Meta Tag for Mobile Browsers
	add_action( 'genesis_meta', 'cs_viewport_meta_tag' );
	
	// Structural Wraps
	add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'event-information', 'footer-widgets', 'footer' ) );
	
	// Remove text from search
	add_filter( 'genesis_search_text', '__return_false' );
	add_filter( 'genesis_search_button_text', '__return_false' );
	
	// Move navigation above header
	remove_action( 'genesis_after_header', 'genesis_do_nav' );
	add_action( 'genesis_before_header', 'genesis_do_nav' );
	
	// Remove site tagline from header
	add_filter( 'genesis_seo_description', '__return_false' );
	
	// Event Information
	add_action( 'genesis_after_header', 'cs_event_description' );
	
	// Footer Text
	add_filter( 'genesis_footer_backtotop_text', 'cs_footer_left' );
	add_filter( 'genesis_footer_creds_text', 'cs_footer_right' );
}

// ** Backend Functions ** //

/**
 * Hide Editor on Specific Template Pages
 *
 */
function sc_hide_editor() {
	// Get the Post ID
	if( isset( $_GET['post'] ) ) $post_id = $_GET['post'];
	elseif( isset( $_POST['post_ID'] ) ) $post_id = $_POST['post_ID'];
	if( !isset( $post_id ) ) return;

	// Get the Page Template
	$template_file = get_post_meta($post_id, '_wp_page_template', TRUE);
    
    if( 'template-schedule.php' == $template_file || 'template-speakers.php' == $template_file )
    	echo '<style>#postdivrich{display: none;}</style>';
}


/**
 * Register the required plugins for this theme.
 *
 * @link https://github.com/thomasgriffin/TGM-Plugin-Activation
 */
function cs_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name' => __( 'Posts 2 Posts', 'social-coup' ),
			'slug' => 'posts-to-posts',
			'required' => true,
		),
		array(
			'name' => __( 'Event Manager Theme Functionality', 'social-coup' ),
			'slug' => 'event-manager-theme-functionality',
			'required' => true,
		)
	);

	/** Theme text domain, used for internationalising strings */
	$theme_text_domain = 'social-coup';

	/**
	 * Array of configuration settings. 
	 */
	$config = array(
		'domain'       => $theme_text_domain,         // Text domain - likely want to be the same as your theme. */
		/*'default_path' => '',                         // Default absolute path to pre-packaged plugins */
		/*'menu'         => 'install-required-plugins', // Menu slug */
		'notices'      => true,                       // Show admin notices or not */
		'strings'      => array(
			'page_title'             				=> __( 'Install Required Plugins', $theme_text_domain ), // */
			'menu_title'             				=> __( 'Install Plugins', $theme_text_domain ), // */
			'instructions_install'   				=> __( 'The %1$s plugin is required for this theme. Click on the big blue button below to install and activate %1$s.', $theme_text_domain ), // %1$s = plugin name */
			'instructions_install_recommended'	=> __( 'The %1$s plugin is recommended for this theme. Click on the big blue button below to install and activate %1$s.', $theme_text_domain ), // %1$s = plugin name, %2$s = plugins page URL */
			'instructions_activate'  				=> __( 'The %1$s plugin is installed but currently inactive. Please go to the <a href="%2$s">plugin administration page</a> page to activate it.', $theme_text_domain ), // %1$s = plugin name, %2$s = plugins page URL */
			'button'                 				=> __( 'Install %s Now', $theme_text_domain ), // %1$s = plugin name */
			'installing'             				=> __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name */
			'oops'                   				=> __( 'Something went wrong with the plugin API.', $theme_text_domain ), // */
			'notice_can_install_required'     	=> __( 'This theme requires the following plugins: %1$s.', $theme_text_domain ), // %1$s = plugin names */
			'notice_can_install_recommended'		=> __( 'This theme recommends the following plugins: %1$s.', $theme_text_domain ), // %1$s = plugin names */
			'notice_cannot_install'  				=> __( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', $theme_text_domain ), // %1$s = plugin name */
			'notice_can_activate_required'    	=> __( 'The following required plugins are currently inactive: %1$s.', $theme_text_domain ), // %1$s = plugin names */
			'notice_can_activate_recommended'		=> __( 'The following recommended plugins are currently inactive: %1$s.', $theme_text_domain ), // %1$s = plugin names */
			'notice_cannot_activate' 				=> __( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', $theme_text_domain ), // %1$s = plugin name */
			'return'                 				=> __( 'Return to Required Plugins Installer', $theme_text_domain ), // */
			'plugin_activated' 	   				=> __( 'Plugin activated successfully.', $theme_text_domain ) // */
		)
	);

	tgmpa( $plugins, $config );

}



function sc_admin_style() {

	$googlefont = '@import url(http://fonts.googleapis.com/css?family=Droid+Sans:400,700);';
	$headimg = sprintf( '.appearance_page_custom-header #headimg { background: url(%s) no-repeat;  min-height: %spx; }', get_header_image(), HEADER_IMAGE_HEIGHT );
	$h1 = sprintf( '#headimg h1, #headimg h1 a { color: #%s; font-family: Droid Sans, arial, serif; font-size: 100px; font-weight: bold; line-height: 120px; margin: 0; text-transform: uppercase; text-decoration: none; }', esc_html( get_header_textcolor() ) );
	$desc = '#headimg #desc { display: none;}';

	printf( '<style type="text/css">%1$s %2$s %3$s %4$s</style>', $googlefont, $headimg, $h1, $desc );

}

function sc_custom_header_style() {

	/** If there is a custom image, output css */
	$image = esc_url( get_header_image() );

	/** If no image is set, for some reason WP returns header.png, so this makes sure an actual image was uploaded */
	$default = get_stylesheet_directory_uri() . '/images/header.png';
	if ( !empty( $image ) && $default !== $image )
		printf( '<style type="text/css">#header #title-area #title { background: url(%s) no-repeat;  }</style>', $image );

	/** If there is a custom text color, output css */
	$text = esc_html( get_header_textcolor() );
	if( !empty( $text ) && 'blank' !== $text )
		printf( '<style type="text/css">#title a, #title a:hover, #description { color: #%s; }</style>', $text );
	else
		echo '<style type="text/css">.header-image #title, .header-image #title a {text-indent: -9999em;}</style>';

}

// ** Frontend Functions ** //

/**
 * Event Description
 *
 */
function cs_event_description() {

	$date = '<div class="one-third first"><div class="date">
	<span class="icon">' . genesis_get_option( 'event_date_icon', 'social-coup' ) . '</span>
	<span class="title">' . genesis_get_option( 'event_date_title', 'social-coup' ) . '</span>
	<span class="subtitle">' . genesis_get_option( 'event_date_subtitle', 'social-coup' ) . '</span>
	</div></div>';
	
	$location = '<div class="one-third"><div class="location">
	<span class="icon"></span>';
	$location_url = esc_url( genesis_get_option( 'event_location_url', 'social-coup' ) );
	if( !empty( $location_url ) )
		$location .= '<a class="title" href="' . $location_url . '">' . genesis_get_option( 'event_location_title', 'social-coup' ) . '</a>';
	else
		$location .= '<span class="title">' . genesis_get_option( 'event_location_title', 'social-coup' ) . '</span>';
	$location .= '
	<span class="subtitle">' . genesis_get_option( 'event_location_subtitle', 'social-coup' ) . '</span>
	</div></div>';
	
	$register = '<div class="one-third"><a class="register" href="' . esc_url( genesis_get_option( 'event_register_link', 'social-coup' ) ) . '">
	<span class="subtitle">' . genesis_get_option( 'event_register_subtitle', 'social-coup' ) . '</span>
	<span class="title">' . genesis_get_option( 'event_register_title', 'social-coup' ) . '</span>
	</a></div><div class="clearfix"></div>';
	

	$output = sprintf( '<div id="event-information">%2$s%1$s%3$s</div>', $date . $location . $register, genesis_structural_wrap( 'event-information', 'open', 0 ), genesis_structural_wrap( 'event-information', 'close', 0 ) );
	
	echo $output;

}

/* 
 * Viewport Meta Tag
 *
 */
function cs_viewport_meta_tag() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

/**
 * Footer Left
 *
 */
function cs_footer_left( $text ) {
	return genesis_get_option( 'footer_left', 'social-coup' );
}

/**
 * Footer Right
 *
 */
function cs_footer_right( $text ) {
	return wpautop( genesis_get_option( 'footer_right', 'social-coup' ) );
}