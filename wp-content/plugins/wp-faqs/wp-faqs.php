<?php
/*
Plugin Name: WordPress FAQs
Plugin URI: http://ninetydegrees.co/
Description: Easily create FAQ pages within your WordPress blog
Version: 1.0.1
Author: Ninety Degrees
Author URI: http://ninetydegrees.co/
*/

###[ Init ]######################################################NINETY#DEGREES####

function nd_init_script() {
	if (!is_admin()) :
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'folding_faq', WP_PLUGIN_URL.'/wp-faqs/js/folding.js', 'jquery', '1.0', true );
	endif;
}
add_action('init', 'nd_init_script');
function nd_init_style() {
	wp_register_style('folding_css', WP_PLUGIN_URL.'/wp-faqs/css/faq.css');
	wp_enqueue_style( 'folding_css' );
}
add_action('wp_print_styles', 'nd_init_style');

###[ Localisation ]##############################################NINETY#DEGREES####

load_plugin_textdomain('ninety', WP_PLUGIN_URL.'/wp-faqs/langs/', 'wp-faqs/langs/');

###[ Shortcodes ]################################################NINETY#DEGREES####

function nd_faq_display( $atts ) {
	
	extract(shortcode_atts(array(
		'id' => '',
		'folding' => 'true',
		'show_index' => 'true',
		'name' => '',
		'slug' => '',
		'orderby' => 'title'
	), $atts));
	
	$group_args = array();
	$order_args = array();
	
	// Get slug from Name if set
	if (!empty($name)) :
		$term = get_term_by( 'name', $name, 'faq-group' );
		$slug = $term->slug;
	endif;
	
	// Get slug from ID if set
	if (!empty($id)) :
		$term = get_term_by( 'id', $id, 'faq-group' );
		$slug = $term->slug;
	endif;
	
	// Ordering
	if ($orderby=='order') :
		$order_args = array(
			'meta_key'	=> '_order',
			'orderby' 	=> 'meta_value',
			'order' 	=> 'asc'
		);
	else :
		$order_args = array(
			'orderby' 	=> 'title',
			'order' 	=> 'asc'
		);
	endif;
	
	// If we have a name then show this bad boy
	if ( !empty($slug) ) :
		$group_args = array(
			'faq-group' 		=> $slug,
		);
	endif;
		
	$output = '';
	$index = '';

	$args = array(
		'post_type'			=> 'faq-item',
		'post_status' 		=> 'publish',
		'caller_get_posts'	=> 1,
		'posts_per_page' 	=> -1
	);
	$args = array_merge( $args, $order_args );
	$args = array_merge( $args, $group_args );
	$my_query = new WP_Query($args);

	if ($my_query->have_posts()) :
	
		// List
		$output .= '<dl class="faq faq-list ';
		if ($folding=="true") $output .= 'folding-faq-list';
		$output .= '">';
		
		// Index
		$index .= '<ol id="index-'.$slug.'" class="faq-index">';
	
		while ($my_query->have_posts()) : $my_query->the_post();
			
			// Index
			$index .= '<li><a href="#faq-item-'.$my_query->post->ID.'">'.wptexturize($my_query->post->post_title).'</a></li>';
			
			// List
			$output .= '<dt id="faq-item-'.$my_query->post->ID.'">'.wptexturize($my_query->post->post_title).'</dt><dd>'.apply_filters('the_content', get_the_content()).'';
			
			if ($show_index=="true") $output .= '<p class="faq-top"><a href="#index-'.$slug.'">'.__('Top&nbsp;&uarr;','ninety').'</a></p>';
			
			$output .= '</dd>';
			
		endwhile;
		
		// List
		$output .= '</dl>';
		
		// Index
		$index .= '</ol>';
		
		if ($show_index=="true") :
			return wpautop($index.$output);
		else :
			return wpautop($output);
		endif;
	
	endif;	
	
	wp_reset_query();
	
	return __('[Invalid/Empty FAQ Group ID supplied]', 'ninety');
}

add_shortcode('faq','nd_faq_display');

###[ Custom post types ]#########################################NINETY#DEGREES####

function nd_post_type() {

	register_post_type( 'faq-item',
        array(
            'labels' => array(
                'name' => __( 'FAQ Items', 'ninety' ),
                'singular_name' => __( 'FAQ Item', 'ninety' ),
                'add_new' => __( 'Add New', 'ninety' ),
                'add_new_item' => __( 'Add New FAQ Item', 'ninety' ),
                'edit' => __( 'Edit', 'ninety' ),
                'edit_item' => __( 'Edit FAQ Item', 'ninety' ),
                'new_item' => __( 'New FAQ Item', 'ninety' ),
                'view' => __( 'View FAQ Items', 'ninety' ),
                'view_item' => __( 'View FAQ Item', 'ninety' ),
                'search_items' => __( 'Search FAQ Items', 'ninety' ),
                'not_found' => __( 'No FAQ Items found', 'ninety' ),
                'not_found_in_trash' => __( 'No FAQ Items found in trash', 'ninety' ),
                'parent' => __( 'Parent FAQ Item', 'ninety' ),
            ),
            'description' => __( 'This is where you can create new FAQ Items for your site.', 'ninety' ),
            'public' => true,
            'show_ui' => true,
            'capability_type' => 'post',
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'menu_position' => 8,
            'menu_icon' => WP_PLUGIN_URL.'/wp-faqs/img/faq-icon.png',
            'hierarchical' => false,
            'rewrite' => false,
            'query_var' => true,
            'supports' => array( 'title', 'editor' ),
        )
    );

	register_taxonomy( 'faq-group',
        array('faq-item'),
        array(
            'hierarchical' => true,
            'labels' => array(
                'name' => __( 'FAQ Groups', 'ninety'),
                'singular_name' => __( 'FAQ Group', 'ninety'),
                'search_items' =>  __( 'Search FAQ Groups', 'ninety'),
                'all_items' => __( 'All FAQ Groups', 'ninety'),
                'parent_item' => __( 'Parent FAQ Group', 'ninety'),
                'parent_item_colon' => __( 'Parent FAQ Group:', 'ninety'),
                'edit_item' => __( 'Edit FAQ Group', 'ninety'),
                'update_item' => __( 'Update FAQ Group', 'ninety'),
                'add_new_item' => __( 'Add New FAQ Group', 'ninety'),
                'new_item_name' => __( 'New FAQ Group Name', 'ninety')
            ),
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => false,
        )
    );
} 
add_action( 'init', 'nd_post_type', 0 );

function nd_edit_columns($columns){
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => __('Question', 'ninety'),	
		"answer" => __("Answer", 'ninety'),
		"faq-group" => __("Group", 'ninety'),
		"date" => __("Date", 'ninety'),
	);
	return $columns;
}
add_filter('manage_edit-faq-item_columns', 'nd_edit_columns');

function nd_custom_columns($column){
	global $post;
	$custom = get_post_custom();
	switch ($column) {
		case "faq-group" :
			echo get_the_term_list($post->ID, 'faq-group', '', ', ','');
		break;
		case "answer" :
			the_excerpt();
		break;
	}
}
add_action('manage_posts_custom_column',  'nd_custom_columns');

###[ Write panel ]#########################################NINETY#DEGREES####

$key = 'faq_meta';

function nd_create_meta_box() {
	add_meta_box( 'faq-meta-box', __('FAQ Item Meta', 'ninety'), 'nd_display_meta_box', 'faq-item', 'normal', 'high' );
}
function nd_display_meta_box() {
	global $post, $key;
	?>
	<div class="panel-wrap">	
		<div class="form-wrap">
		
			<?php 
			wp_nonce_field( plugin_basename( __FILE__ ), $key . '_wpnonce', false, true ); 
			$value = get_post_meta($post->ID, '_order', true);
			if (!$value || empty($value)) $value = 0;
			?>
				
			<div class="form-field form-required" style="margin:0; padding: 0 8px">
				<label for="_order" style="color: #666; display:inline; padding-right: 10px;"><?php _e('Order:','ninety'); ?></label>
				<input type="text" style="width:3em; padding-right: 10px; display:inline; vertical-align:middle;" name="_order" value="<?php echo $value; ?>" size="2" />
				<span class="description"><?php _e('The order in which this item appears in the FAQ Group (optional). Requires <code>orderby="order"</code> added to the display shortcode.','ninety'); ?></span>
				<div class="clear"></div>
			</div>
		
		</div>
	</div>	
	<?php
}
function nd_save_meta_box( $post_id ) {
	global $post, $key;
	
	if ( !isset($_POST[ $key . '_wpnonce' ] ) ) return $post_id;
	if ( !wp_verify_nonce( $_POST[ $key . '_wpnonce' ], plugin_basename(__FILE__) ) ) return $post_id;
	if ( !current_user_can( 'edit_post', $post_id )) return $post_id;

	if (isset($_POST['_order'])) update_post_meta( $post_id, '_order', stripslashes($_POST['_order']) );
}
add_action( 'admin_menu', 'nd_create_meta_box' );
add_action( 'save_post', 'nd_save_meta_box' );