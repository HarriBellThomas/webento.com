<?php
add_filter('show_admin_bar', '__return_false'); 

function remove_search_action() {
	remove_action( 'template_redirect', 'roots_nice_search_redirect');
}

add_action('template_redirect', 'remove_search_action',9);



if ( !function_exists( 'bp_dtheme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress and BuddyPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override bp_dtheme_setup() in a child theme, add your own bp_dtheme_setup to your child theme's
 * functions.php file.
 *
 * @global object $bp Global BuddyPress settings object
 * @since 1.5
 */
function bp_dtheme_setup() {
	global $bp;

	// Load the AJAX functions for the theme
	require( STYLESHEETPATH . '/_inc/ajax.php' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Add responsive layout support to bp-default without forcing child
	// themes to inherit it if they don't want to
	add_theme_support( 'bp-default-responsive' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'buddypress' ),
	) );

	// This theme allows users to set a custom background
	add_custom_background( 'bp_dtheme_custom_background_style' );

	// Add custom header support if allowed
	if ( !defined( 'BP_DTHEME_DISABLE_CUSTOM_HEADER' ) ) {
		define( 'HEADER_TEXTCOLOR', 'FFFFFF' );

		// The height and width of your custom header. You can hook into the theme's own filters to change these values.
		// Add a filter to bp_dtheme_header_image_width and bp_dtheme_header_image_height to change these values.
		define( 'HEADER_IMAGE_WIDTH',  apply_filters( 'bp_dtheme_header_image_width',  1250 ) );
		define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'bp_dtheme_header_image_height', 133  ) );

		// We'll be using post thumbnails for custom header images on posts and pages. We want them to be 1250 pixels wide by 133 pixels tall.
		// Larger images will be auto-cropped to fit, smaller ones will be ignored.
		set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

		// Add a way for the custom header to be styled in the admin panel that controls custom headers.
		add_custom_image_header( 'bp_dtheme_header_style', 'bp_dtheme_admin_header_style' );
	}

	if ( !is_admin() ) {
		// Register buttons for the relevant component templates
		// Friends button
		if ( bp_is_active( 'friends' ) )
			add_action( 'bp_member_header_actions',    'bp_add_friend_button' );

		// Activity button
		if ( bp_is_active( 'activity' ) )
			add_action( 'bp_member_header_actions',    'bp_send_public_message_button' );

		// Messages button
		if ( bp_is_active( 'messages' ) )
			add_action( 'bp_member_header_actions',    'bp_send_private_message_button' );

		// Group buttons
		if ( bp_is_active( 'groups' ) ) {
			add_action( 'bp_group_header_actions',     'bp_group_join_button' );
			add_action( 'bp_group_header_actions',     'bp_group_new_topic_button' );
			add_action( 'bp_directory_groups_actions', 'bp_group_join_button' );
		}

		// Blog button
		if ( bp_is_active( 'blogs' ) )
			add_action( 'bp_directory_blogs_actions',  'bp_blogs_visit_blog_button' );
	}
}
add_action( 'after_setup_theme', 'bp_dtheme_setup' );
endif;


if ( !function_exists( 'bp_dtheme_enqueue_scripts' ) ) :
/**
 * Enqueue theme javascript safely
 *
 * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_script
 * @since 1.5
 */
function bp_dtheme_enqueue_scripts() {
	// Bump this when changes are made to bust cache
	$version = '20120110';
	
	// Enqueue the global JS - Ajax will not work without it
	wp_enqueue_script( 'dtheme-ajax-js', get_stylesheet_directory_uri() . '/_inc/global.js', array( 'jquery' ), $version );

	// Add words that we need to use in JS to the end of the page so they can be translated and still used.
	$params = array(
		'my_favs'           => __( 'My Favorites', 'buddypress' ),
		'accepted'          => __( 'Accepted', 'buddypress' ),
		'rejected'          => __( 'Rejected', 'buddypress' ),
		'show_all_comments' => __( 'Show all comments for this thread', 'buddypress' ),
		'show_all'          => __( 'Show all', 'buddypress' ),
		'comments'          => __( 'comments', 'buddypress' ),
		'close'             => __( 'Close', 'buddypress' ),
		'view'              => __( 'View', 'buddypress' ),
		'mark_as_fav'	    => __( 'Favorite', 'buddypress' ),
		'remove_fav'	    => __( 'Remove Favorite', 'buddypress' )
	);

	wp_localize_script( 'dtheme-ajax-js', 'BP_DTheme', $params );
}
add_action( 'wp_enqueue_scripts', 'bp_dtheme_enqueue_scripts' );
endif;

if ( !function_exists( 'bp_dtheme_enqueue_styles' ) ) :
/**
 * Enqueue theme CSS safely
 *
 * For maximum flexibility, BuddyPress Default's stylesheet is enqueued, using wp_enqueue_style().
 * If you're building a child theme of bp-default, your stylesheet will also be enqueued,
 * automatically, as dependent on bp-default's CSS. For this reason, bp-default child themes are
 * not recommended to include bp-default's stylesheet using @import.
 *
 * If you would prefer to use @import, or would like to change the way in which stylesheets are
 * enqueued, you can override bp_dtheme_enqueue_styles() in your theme's functions.php file.
 *
 * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_style
 * @see http://codex.buddypress.org/releases/1-5-developer-and-designer-information/
 * @since 1.5
 */
function bp_dtheme_enqueue_styles() {
	
	// Bump this when changes are made to bust cache
	$version = '20120110';

	// Register our main stylesheet
	wp_register_style( 'bp-default-main', get_stylesheet_directory_uri() . '/_inc/css/default.css', array(), $version );

	// If the current theme is a child of bp-default, enqueue its stylesheet
	if ( is_child_theme() && 'bp-default' == get_template() ) {
		wp_enqueue_style( get_stylesheet(), get_stylesheet_uri(), array( 'bp-default-main' ), $version );
	}

	// Enqueue the main stylesheet
	wp_enqueue_style( 'bp-default-main' );

	// Default CSS RTL
	if ( is_rtl() )
		wp_enqueue_style( 'bp-default-main-rtl',  get_stylesheet_directory_uri() . '/_inc/css/default-rtl.css', array( 'bp-default-main' ), $version );

	// Responsive layout
	if ( current_theme_supports( 'bp-default-responsive' ) ) {
		wp_enqueue_style( 'bp-default-responsive', get_stylesheet_directory_uri() . '/_inc/css/responsive.css', array( 'bp-default-main' ), $version );

		if ( is_rtl() )
			wp_enqueue_style( 'bp-default-responsive-rtl', get_stylesheet_directory_uri() . '/_inc/css/responsive-rtl.css', array( 'bp-default-responsive' ), $version );
	}
}
add_action( 'wp_enqueue_scripts', 'bp_dtheme_enqueue_styles' );
endif;
 
/************* Register Side bar code starts here ******************/
 function roots_child_sidebars_init() {
	if ( !function_exists('register_sidebars') )
        return;

	  register_sidebar(array(
		'name' 			=> 'Social Media - Box (Footer)',
		'id' 			=> 'social_media_footer',
		'description' 	=> 'Widgets in this area will show the Social Media Widgets above the footer below homepage content.',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>'
	));
	
	 register_sidebar(array(
		'name' 			=> 'Blog Page',
		'id' 			=> 'blog_loop_sidebar',
		'description' 	=> 'Widgets in this area will show the widgets in the blog sidebar.'
	));


}
add_action( 'init', 'roots_child_sidebars_init' );

/**************************  Registering widgets starts here *****************************************/
function roots_child_widget_init()
{

	register_widget('twitter_profile_Widget');

	do_action('widgets_init');
}

add_action( 'init', 'roots_child_widget_init' ); 
/**************************  Registering widgets ends here *****************************************/


/***************** Widget for Twitter Tweets starts here *****************************/
class twitter_profile_Widget extends WP_Widget {
	
	function twitter_profile_Widget() {
		parent::WP_Widget('twitterprofile_widget', 'Twitter Profile Widget', array('description' => 'Shows the latest tweets from the twitter profile in the sidebar area of website.'));	
	}
	
	/**
	 * display widget
	 */	 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		
		echo $before_widget;
		?>

		<script>
		new TWTR.Widget({
		  version: 2,
		  type: 'profile',
		  rpp: 5,
		  interval: 30000,
		  width: 275,
		  height: 215,
		  theme: {
			shell: {
			  background: '#ffffff',
			  color: '#ffffff'
			},
			tweets: {
			  background: '#ffffff',
			  color: '#000000',
			  links: '#3399ff'
			}
		  },
		  features: {
			scrollbar: false,
			loop: false,
			live: false,
			behavior: 'all'
		  }
		}).render().setUser('webento').start();
		</script>
		<!--<script>
		new TWTR.Widget({
		  version: 2,
		  type: 'profile',
		  rpp: 5,
		  interval: 30000,
		  width: 275,
		  height: 215,
		  theme: {
			shell: {
			  background: '#8ac7e6',
			  color: '#000000'
			},
			tweets: {
			  background: '#ffffff',
			  color: '#000000',
			  links: '#1986b5'
			}
		  },
		  features: {
			scrollbar: false,
			loop: true,
			live: true,
			behavior: 'default'
		  }
		}).render().setUser('webento').start();
		</script>-->
		<?php
		echo $after_widget;
	}
 
	/**
	 *	update/save function
	 */	 	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
 
	/**
	 *	admin control form
	 */	 	
		function form($instance) {
		$default = 	array( 'title' => __('Just Widget') );
		$instance = wp_parse_args( (array) $instance, $default );
		echo "There is no option available for this widget.";
	}
}
/***************** Widget for Twitter Tweets ends here *******************************/

function my_function_admin_bar(){
    return false;
}
add_filter( 'show_admin_bar' , 'my_function_admin_bar');

function catchBoxContent() {
	global $post;
	$title 		= 'OUR THOUGHTS';
	$message 	= 'We offer services that will help take your business to the next level';
	
	if( is_page() || is_single() ) {
		$title = $post->post_title;
		$temp_message = get_post_meta($post->ID, 'heading', true);		
		if( count($temp_message) > 0 ) {
			if ( $temp_message != '' ) {
				$message = $temp_message;
			}
		}
	} else if ( is_category() ) {
		$title = single_cat_title('', false);
		$category = get_the_category(); 
		$temp_message = strip_tags(category_description());
		if ( $temp_message != '' ) {
			$message = $temp_message;
		}
	}
	
	echo '<h3><strong>' . $title . '</strong></h3>';
	echo $message;
}


function store_force_ssl( $force_ssl, $post_id ) {
    if ( strpos($_SERVER['REQUEST_URI'], 'professional') !== false ) {
        $force_ssl = true;
    }
    return $force_ssl;
}

add_filter('force_ssl', 'store_force_ssl', 10, 2);
?>