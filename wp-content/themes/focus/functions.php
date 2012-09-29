<?php
/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', 'Focus Child Theme' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/themes/focus' );

/** Add Viewport meta tag for mobile browsers */
add_action( 'genesis_meta', 'focus_add_viewport_meta_tag' );
function focus_add_viewport_meta_tag() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

/** Create additional color style options */
add_theme_support( 'genesis-style-selector', array( 'focus-brown' => 'Brown', 'focus-gray' => 'Gray' ) );

/** Add support for structural wraps */
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'inner', 'footer-widgets', 'footer' ) );

/** Add new image sizes */
add_image_size( 'grid-thumbnail', 280, 100, TRUE );

/** Add support for custom background */
add_custom_background();

/** Add support for custom header */
add_theme_support( 'genesis-custom-header', array( 'width' => 1060, 'height' => 120 ) );

/** Reposition the primary navigation */
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_header', 'genesis_do_nav' );

/** Reposition the breadcrumbs */
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_after_header', 'genesis_do_breadcrumbs' );

/** Customize breadcrumbs display */
add_filter( 'genesis_breadcrumb_args', 'streamline_breadcrumb_args' );
function streamline_breadcrumb_args( $args ) {
	$args['home'] = 'Home';
	$args['sep'] = ' ';
	$args['list_sep'] = ', '; // Genesis 1.5 and later
	$args['prefix'] = '<div class="breadcrumb"><div class="inner">';
	$args['suffix'] = '</div></div>';
	$args['labels']['prefix'] = '';
	return $args;
}

/** Customize the post info function */
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter($post_info) {
if (!is_page()) {
    $post_info = 'Posted on [post_date] Written by [post_author_posts_link] [post_comments] [post_edit]';
    return $post_info;
}}

/** Customize the post meta function */
add_filter( 'genesis_post_meta', 'post_meta_filter' );
function post_meta_filter($post_meta) {
if ( !is_page() ) {
    $post_meta = '[post_categories before="Filed Under: "] [post_tags before="Tagged: "]';
    return $post_meta;
}}

/** Modify the size of the Gravatar in the author box */
add_filter( 'genesis_author_box_gravatar_size', 'focus_author_box_gravatar_size' );
function focus_author_box_gravatar_size($size) {
    return '80';
}

/** Add the after post section */
add_action( 'genesis_after_post_content', 'focus_after_post' );
function focus_after_post() {
	if ( ! is_singular( 'post' ) )
	return;
	genesis_widget_area( 'after-post', array(
		'before' => '<div class="after-post widget-area">',
   ) );
}

/** Add support for 3-column footer widgets */
add_theme_support( 'genesis-footer-widgets', 3 );

/** Register widget areas */
genesis_register_sidebar( array(
	'id'				=> 'after-post',
	'name'			=> __( 'After Post', 'focus' ),
	'description'	=> __( 'This is the after post section.', 'focus' ),
) );