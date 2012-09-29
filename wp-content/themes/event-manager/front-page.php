<?php
/**
 * Front Page
 *
 * @package      Event Manager
 * @author       Bill Erickson <bill@billerickson.net>
 * @copyright    Copyright (c) 2011, Bill Erickson
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */

// Full Width Layout
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

// Move Event Information inside #inner
remove_action( 'genesis_after_header', 'cs_event_description' );
add_action( 'genesis_before_content_sidebar_wrap', 'cs_event_description' );


/**
 * Add Homepage Intro if provided
 *
 */
function sc_homepage_intro( $default ) {
	$text = genesis_get_option( 'homepage_intro', 'social-coup' );
	return $text ? wpautop( $text ) : $default;
}
add_filter( 'genesis_seo_description', 'sc_homepage_intro' );

/**
 * Home Rotator Markup
 *
 */
// Javascript required for Home Rotator
function sc_home_rotator_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_style( 'home-rotator-css', get_stylesheet_directory_uri() . '/lib/css/jquery.thumbnailScroller.css' );

	wp_enqueue_script( 'home-rotator', get_stylesheet_directory_uri() . '/lib/js/home-rotator.js', array( 'jquery' ) );
		wp_enqueue_script( 'thumbscroll', get_stylesheet_directory_uri() . '/lib/js/jquery.thumbnailScroller.js', array( 'jquery' ) );
	
}
add_action( 'wp_enqueue_scripts', 'sc_home_rotator_scripts' );

function sc_home_rotator() {
	// Confirm this isn't a mobile device
	global $is_iphone;
	
	// Confirm there are images to rotate
	$args = array(
		'post_type' => 'sc-rotator', 
		'posts_per_page' => '-1',
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);
	$loop = new WP_Query( $args );
	
	if( !$is_iphone && $loop->have_posts() ) {
	?>

<div id="home-rotator">
	<!-- thumbnail scroller markup begin -->
	<div id="tS2" class="jThumbnailScroller">
		<div class="jTscrollerContainer">

			<div class="jTscroller">
				<?php while( $loop->have_posts() ): $loop->the_post(); global $post;
					echo get_the_post_thumbnail( $post->ID, 'sc-rotator' );
				endwhile;
				?>
			</div>

		</div>
		<a href="#" class="jTscrollerPrevButton"></a>
		<a href="#" class="jTscrollerNextButton"></a>
	</div>
	<!-- thumbnail scroller markup end -->
</div>
	
	<?php
	}
	wp_reset_query();
}

add_action( 'genesis_before_content_sidebar_wrap', 'sc_home_rotator' );




/**
 * Home Loop
 *
 */
function sc_home_loop() {
	echo '<div id="home-left" class="widget-area one-third first entry-content">';
	if( ! dynamic_sidebar( 'home-left' ) )
		sc_sample_widget( __( 'Home Left', 'social-coup' ) );
	echo '</div>';
	
	echo '<div id="home-middle" class="widget-area one-third entry-content">';
	if( ! dynamic_sidebar( 'home-middle' ) ) 
		sc_sample_widget( __( 'Home Middle', 'social-coup' ) );
	echo '</div>';
	
	echo '<div id="home-right" class="widget-area one-third entry-content">';
	if( ! dynamic_sidebar( 'home-right' ) )
		sc_sample_widget( __( 'Home Right', 'social-coup' ) );
	echo '</div>';
	
	echo '<div class="cl"></div>';
}
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'sc_home_loop' );

/**
 * Sample Widget
 * Displayed if no widgets assigned to home sidebars 
 *
 */
function sc_sample_widget( $label = '' ) {
	echo '<div class="widget widget-sample"><div class="widget-wrap"><h4 class="widgettitle">' . $label . '</h4><p>';
	echo sprintf( __( 'Go to <a href="%s">Widgets</a> %s %s to add widgets to this area.', 'social-coup' ), admin_url( 'widgets.php' ), '&raquo;', $label );
	echo '</p></div></div>';
}
 
genesis();