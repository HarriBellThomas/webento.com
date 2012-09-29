<?php
/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', 'Decor Theme' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/themes/decor' );

/** Create additional color style options */
add_theme_support( 'genesis-style-selector', array( 'decor-amethyst' => 'Amethyst', 'decor-copper' => 'Copper', 'decor-silver' => 'Silver' ) );

/** Add Viewport meta tag for mobile browsers */
add_action( 'genesis_meta', 'decor_add_viewport_meta_tag' );
function decor_add_viewport_meta_tag() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

/** Add new image sizes */
add_image_size( 'post-image', 730, 285, TRUE );

/** Add support for custom background */
add_custom_background();

/** Add support for custom header */
add_theme_support( 'genesis-custom-header', array( 'width' => 1140, 'height' => 140 ) );

/** Add support for 3-column footer widgets */
add_theme_support( 'genesis-footer-widgets', 3 );

/** Add new body class */
add_filter( 'body_class', 'decor_add_nav_body_class' );
function decor_add_nav_body_class($classes){
 	
 	if( genesis_get_option( 'nav' ) )
 		$classes[] = 'primary-nav';
 
    return $classes;
}

/** Add post/page wraps */
add_action( 'genesis_before_post_title', 'decor_start_post_wrap' );
add_action( 'genesis_after_post_content', 'decor_end_post_wrap' );

function decor_start_post_wrap() {
	?>
	<div class="wrap">
	<div class="left-corner"></div>
	<div class="right-corner"></div>
	<?php
}

function decor_end_post_wrap() {
	?>
	</div>
	<?php
}

/** Add post image above post title */
add_action( 'genesis_before_post_title', 'decor_post_image' );
function decor_post_image() {

	if ( is_page() ) return;

	if ( $image = genesis_get_image( 'format=url&size=post-image' ) ) {
		printf( '<a href="%s" rel="bookmark" class="post-photo"><div class="post-date">%s</div><img src="%s" alt="%s" /></a>', get_permalink(), do_shortcode( '<em>[post_date format="j"]</em>[post_date format="F Y"]' ), $image, the_title_attribute( 'echo=0' ) );
	}

}

/** Customize the post info function */
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter( $post_info ) {
	if ( !is_page() ) {
			$post_info = __( 'posted by', 'decor' ) . ' [post_author_posts_link] [post_comments] [post_edit]';
			return $post_info;
	}
}

/** Customize 'Read More' text */
add_filter( 'get_the_content_more_link', 'decor_read_more_link' );
add_filter( 'the_content_more_link', 'decor_read_more_link' );
function decor_read_more_link() {
	return '<a class="more-link" href="' . get_permalink() . '" rel="nofollow">' . __( 'Continue reading' ) . '</a>';
}

/** Modify comment author says text */
add_filter('comment_author_says_text', 'decor_comment_author_says_text');
function decor_comment_author_says_text() {
    return '';
}