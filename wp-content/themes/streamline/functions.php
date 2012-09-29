<?php
/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', 'Streamline Theme' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/themes/streamline' );

/** Add Viewport meta tag for mobile browsers */
add_action( 'genesis_meta', 'streamline_add_viewport_meta_tag' );
function streamline_add_viewport_meta_tag() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

/** Create additional color style options */
add_theme_support( 'genesis-style-selector', array( 'streamline-blue' => 'Blue', 'streamline-green' => 'Green' ) );

/** Add support for structural wraps */
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'inner', 'footer-widgets', 'footer' ) );

/** Add new image sizes */
add_image_size( 'home-featured', 255, 80, TRUE );
add_image_size( 'post-image', 642, 250, TRUE );

/** Unregister layout settings */
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

/** Unregister secondary sidebar */
unregister_sidebar( 'sidebar-alt' );

/** Add support for custom background */
add_custom_background();

/** Add support for custom header */
add_theme_support( 'genesis-custom-header', array( 'width' => 960, 'height' => 120 ) );

/** Add newsletter section after header */
add_action( 'genesis_before_content_sidebar_wrap', 'streamline_newsletter' );
function streamline_newsletter() {
   if ( ! is_home() )
       return;

   genesis_widget_area( 'newsletter', array(
       'before' => '<div class="newsletter widget-area">',
   ) );
}

/** Reposition the breadcrumbs */
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_before_content_sidebar_wrap', 'genesis_do_breadcrumbs' );

/** Customize breadcrumbs display */
add_filter( 'genesis_breadcrumb_args', 'streamline_breadcrumb_args' );
function streamline_breadcrumb_args( $args ) {
	$args['home'] = 'Home';
	$args['sep'] = ' ';
	$args['list_sep'] = ', '; // Genesis 1.5 and later
	$args['prefix'] = '<div class="breadcrumb"><div class="inner">';
	$args['suffix'] = '</div></div>';
	$args['labels']['prefix'] = '<span class="home"></span>';
	return $args;
}

/** Add post image above post title */
add_action( 'genesis_before_post', 'streamline_post_image' );
function streamline_post_image() {

	if ( is_page() ) return;

	if ( $image = genesis_get_image( 'format=url&size=post-image' ) ) {
		printf( '<a href="%s" rel="bookmark"><img class="post-photo" src="%s" alt="%s" /></a>', get_permalink(), $image, the_title_attribute( 'echo=0' ) );
	}

}

/** Relocate the post info function */
remove_action( 'genesis_before_post_content', 'genesis_post_info' );
add_action( 'genesis_before_post', 'genesis_post_info' );

/** Customize the post info function */
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter($post_info) {
if ( !is_page() ) {
    $post_info = '[post_author_posts_link] [post_date] [post_comments] [post_edit]';
    return $post_info;
}}

/** Add markup around post class */
add_action( 'genesis_before_post', 'streamline_post_markup' );
	function streamline_post_markup() { ?>
	<div class="post-wrap">
	<?php
}
add_action( 'genesis_after_post', 'streamline_post_markup_close' );
	function streamline_post_markup_close() { ?>
	</div>
	<?php
}

/** Modify the size of the Gravatar in the author box */
add_filter( 'genesis_author_box_gravatar_size', 'streamline_author_box_gravatar_size' );
function streamline_author_box_gravatar_size($size) {
    return '80';
}

/** Customize the post meta function */
add_filter( 'genesis_post_meta', 'post_meta_filter' );
function post_meta_filter($post_meta) {
if ( !is_page() ) {
    $post_meta = '[post_categories before="Filed Under: "] [post_tags before="Tagged: "]';
    return $post_meta;
}}

/** Add the after post section */
add_action( 'genesis_after_post_content', 'streamline_after_post' );
function streamline_after_post() {
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
	'id'				=> 'newsletter',
	'name'			=> __( 'Newsletter', 'streamline' ),
	'description'	=> __( 'This is the newsletter section below the navigation.', 'streamline' ),
) );
genesis_register_sidebar( array(
	'id'				=> 'home-featured-1',
	'name'			=> __( 'Home Featured #1', 'streamline' ),
	'description'	=> __( 'This is the featured #1 column on the homepage.', 'streamline' ),
) );
genesis_register_sidebar( array(
	'id'				=> 'home-featured-2',
	'name'			=> __( 'Home Featured #2', 'streamline' ),
	'description'	=> __( 'This is the featured #2 column on the homepage.', 'streamline' ),
) );
genesis_register_sidebar( array(
	'id'				=> 'home-featured-3',
	'name'			=> __( 'Home Featured #3', 'streamline' ),
	'description'	=> __( 'This is the featured #3 column on the homepage.', 'streamline' ),
) );
genesis_register_sidebar( array(
	'id'				=> 'after-post',
	'name'			=> __( 'After Post', 'streamline' ),
	'description'	=> __( 'This is the after post section.', 'streamline' ),
) );