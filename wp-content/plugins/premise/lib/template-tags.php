<?php
/**
 * This file contains all template tags necessary for the Premise landing pages plugin.
 * Template tags are generally used on the frontend or in the Landing Page templates.  There
 * are some template tags in this file designed for use on particular landing pages, as well
 * as some template tags needed for all the landing pages.
 *
 * Most of the names should be self explanatory.  For any template tag accepting a post ID, you can
 * pass nothing and it will automatically detect the global post.
 */

/**
 * This particular function is necessary because the callback to wp_iframe
 * must be a string or else warnings get thrown and you get crazy messages
 * in the WP admin.  It just delegates back to the Premise plugin object.
 * @return void
 */
function premise_thickbox() {
	global $Premise;
	return $Premise->displayPremiseResourcesThickboxOutput();
}

function premise_the_editor( $content, $id = 'content', $prev_id = 'title', $media_buttons = true, $tab_index = 2, $deprecated = '' ) {
	global $Premise;
	$Premise->theEditor( $content, $id, $prev_id, $media_buttons, $tab_index );
}

function premise_the_media_buttons() {
	ob_start();
	do_action('media_buttons');
	$buttons = ob_get_clean();
	$buttons = preg_replace('/id=(\'|").*?(\'|")/', '', $buttons);
	echo $buttons;
}

function premise_get_media_upload_src($type, $optional = array()) {
	global $post_ID, $temp_ID;
	$uploading_iframe_ID = (int) (0 == $post_ID ? $temp_ID : $post_ID);
	$upload_iframe_src = add_query_arg('post_id', $uploading_iframe_ID, 'media-upload.php');

	if ( 'media' != $type ) {
		$upload_iframe_src = add_query_arg('tab', $type, $upload_iframe_src);
		$upload_iframe_src = add_query_arg('type', $type, $upload_iframe_src);
	}
	if(!empty($optional) && is_array($optional)) {
		$upload_iframe_src = add_query_arg($optional, $upload_iframe_src);
	}

	$upload_iframe_src = apply_filters($type . '_upload_iframe_src', $upload_iframe_src);

	return add_query_arg('TB_iframe', true, $upload_iframe_src);
}

function premise_get_version() {
	return apply_filters( 'premise_get_version', PREMISE_VERSION );
}
function premise_the_version() {
	echo apply_filters( 'premise_the_version', premise_get_version() );
}

function premise_active_admin_tab($tab) {
	if($_GET['page'] == $tab) { echo 'nav-tab-active'; }
}

/// GENERAL TEMPLATE TAGS

function premise_get_header_copy($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_header_copy', $premise_base->getHeaderCopy($postId), $postId);
}
function premise_the_header_copy($postId = null) {
	echo apply_filters('premise_the_header_copy', premise_get_header_copy($postId), $postId);
}

function premise_should_have_header_image($postId = null) {
	global $premise_base;
	return apply_filters('premise_should_have_header_image', $premise_base->shouldHaveHeaderImage($postId));
}

function premise_get_header_image($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_header_image', $premise_base->getHeaderImage($postId), $postId);
}
function premise_the_header_image($postId = null) {
	echo apply_filters('premise_the_header_image', premise_get_header_image($postId), $postId);
}
function premise_get_header_image_url($postId = null) {
	global $premise_base;
	return apply_filters( 'premise_get_header_image_url', $premise_base->get_header_image_url( $postId ), $postId );
}
function premise_the_header_image_url( $postId = null ) {
	echo apply_filters( 'premise_the_header_image_url', premise_get_header_image_url( $postId ), $postId );
}

function premise_get_footer_copy($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_footer_copy', $premise_base->getFooterCopy($postId), $postId);
}
function premise_the_footer_copy($postId = null) {
	echo apply_filters('premise_the_footer_copy', premise_get_footer_copy($postId), $postId);
}

function premise_should_have_footer($postId = null) {
	global $premise_base;
	return apply_filters('premise_should_have_footer', $premise_base->shouldHaveFooter($postId), $postId);
}

function premise_should_have_header($postId = null) {
	global $premise_base;
	return apply_filters('premise_should_have_header', $premise_base->shouldHaveHeader($postId), $postId);
}

/// VIDEO TEMPLATE TAGS

function premise_get_video_embed_code($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_video_embed_code', $premise_base->getVideoEmbedCode($postId), $postId);
}
function premise_the_video_embed_code($postId = null) {
	echo apply_filters('premise_the_video_embed_code', premise_get_video_embed_code($postId), $postId);
}

function premise_get_video_copy($postId = null) {
	global $premise_base ;
	return apply_filters('premise_get_video_copy', $premise_base->getVideoCopy($postId), $postId);
}
function premise_the_video_copy($postId = null) {
	echo apply_filters('the_content', premise_get_video_copy($postId), $postId);
}

function premise_get_video_below_copy($postId = null) {
	global $premise_base ;
	return apply_filters('premise_get_video_below_copy', $premise_base->getVideoBelowCopy($postId), $postId);
}
function premise_the_video_below_copy($postId = null) {
	echo apply_filters('the_content', premise_get_video_below_copy($postId), $postId);
}

function premise_get_video_align($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_video_align', $premise_base->getVideoAlign($postId), $postId);
}
function premise_the_video_align($postId = null) {
	echo apply_filters('premise_the_video_align', premise_get_video_align($postId), $postId);
}

function premise_has_video_image($postId = null) {
	$value = trim(premise_get_video_image($postId));
	return apply_filters('premise_had_video_image', !empty($value), $postId);
}
function premise_get_video_image($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_video_image', $premise_base->getVideoImage($postId), $postId);
}
function premise_the_video_image($postId = null) {
	echo apply_filters('premise_the_video_image', premise_get_video_image($postId), $postId);
}

function premise_get_video_image_title($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_video_image_title', $premise_base->getVideoImageTitle($postId), $postId);
}
function premise_the_video_image_title($postId = null) {
	echo apply_filters('premise_the_video_image_title', premise_get_video_image_title($postId), $postId);
}

/// CONTENT SCROLLER

/**
 * This function returns an array of arrays.  Each inner array is associative
 * and has data 'title', and 'text'
 * @return array
 */
function premise_get_content_tabs($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_content_tabs', $premise_base->getContentScrollers($postId));
}
function premise_the_content_tabs($postId = null, $before = '', $after = '', $beforeTitle = '', $afterTitle = '', $beforeContent = '', $afterContent = '') {
	$tabs = premise_get_content_tabs($postId);

	$output = '';
	if(!empty($tabs)) {
		$output .= $before;
		foreach($tabs as $key => $tab) {
			$output .= $beforeTitle.$tab['title'].$afterTitle.$beforeContent.$tab['text'].$afterContent;
		}
		$output .= $after;
	}

	echo apply_filters('premise_the_content_tabs', $output);
}

function premise_should_show_content_scroller_tabs($postId = null) {
	global $premise_base;
	return apply_filters('premise_should_show_content_scroller_tabs', $premise_base->getContentScrollerShowTabs($postId), $postId);
}
function premise_should_show_content_scroller_arrows($postId = null) {
	global $premise_base;
	return apply_filters('premise_should_show_content_scroller_arrows', $premise_base->getContentScrollerShowArrows($postId), $postId);
}

/// PRICING

function premise_get_pricing_columns($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_pricing_columns', $premise_base->getPricingColumns($postId), $postId);
}

function premise_the_above_pricing_table_content($postId = null) {
	echo apply_filters('the_content', premise_get_above_pricing_table_content($postId), $postId);
}
function premise_get_above_pricing_table_content($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_above_pricing_table_content', $premise_base->getAbovePricingTableContent($postId), $postId);
}

function premise_the_below_pricing_table_content($postId = null) {
	echo apply_filters('the_content', premise_get_below_pricing_table_content($postId), $postId);
}
function premise_get_below_pricing_table_content($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_below_pricing_table_content', $premise_base->getBelowPricingTableContent($postId), $postId);
}

function premise_get_pricing_bullet_marker($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_pricing_bullet_marker', $premise_base->getPricingBulletMarker($postId), $postId);
}
function premise_get_pricing_bullet_color($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_pricing_bullet_color', $premise_base->getPricingBulletColor($postId), $postId);
}

/// OPT-IN TEMPLATE TAGS

function premise_get_optin_copy($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_optin_copy', $premise_base->getOptinCopy($postId), $postId);
}
function premise_the_optin_copy($postId = null) {
	echo apply_filters('premise_the_optin_copy', premise_get_optin_copy($postId), $postId);
}

function premise_get_optin_below_copy($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_optin_below_copy', $premise_base->getOptinBelowCopy($postId), $postId);
}
function premise_the_optin_below_copy($postId = null) {
	echo apply_filters('the_content', premise_get_optin_below_copy($postId), $postId);
}

function premise_get_optin_form_code($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_optin_form_code', $premise_base->getOptinFormCode($postId), $postId);
}
function premise_the_optin_form_code($postId = null) {
	echo apply_filters('premise_the_optin_form_code', premise_get_optin_form_code($postId), $postId);
}


function premise_get_optin_align($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_optin_align', $premise_base->getOptinAlign($postId), $postId);
}
function premise_the_optin_align($postId = null) {
	echo apply_filters('premise_the_optin_align', premise_get_optin_align($postId), $postId);
}

/// LONG COPY
function premise_get_subhead($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_subhead', $premise_base->getSubhead($postId), $postId);
}
function premise_the_subhead($postId = null) {
	echo apply_filters('premise_the_subhead', premise_get_subhead($postId), $postId);
}

/// SOCIAL SHARE
function premise_has_social_share_shared_page($postId = null) {
	global $premise_base;
	return apply_filters('premise_has_social_share_shared_page', $premise_base->hasSharedPage($postId), $postId);
}

function premise_get_social_share_share_message($postId = null) {
	global $premise_base;
	return apply_filters('the_content', apply_filters('premise_get_social_share_share_message', $premise_base->getSocialShareMessage($postId), $postId));
}
function premise_the_social_share_share_message($postId = null) {
	echo apply_filters('premise_the_social_share_share_message', premise_get_social_share_share_message($postId), $postId);
}

function premise_get_social_share_teaser_page($postId = null) {
	global $premise_base;
	return apply_filters('the_content', apply_filters('premise_get_social_share_teaser_page', $premise_base->getSocialShareTeaserPage($postId), $postId));
}
function premise_the_social_share_teaser_page($postId = null) {
	echo apply_filters('premise_the_social_share_teaser_page', premise_get_social_share_teaser_page($postId), $postId);
}

function premise_get_social_share_after_share_page($postId = null) {
	global $premise_base;
	return apply_filters('the_content', apply_filters('premise_get_social_share_after_share_page', $premise_base->getSocialShareAfterSharePage($postId), $postId));
}
function premise_the_social_share_after_share_page($postId = null) {
	echo apply_filters('premise_the_social_share_after_share_page', premise_get_social_share_after_share_page($postId), $postId);
}

function premise_get_social_share_twitter_text($postId = null, $link = false) {
	global $premise_base;
	return apply_filters('premise_get_social_share_twitter_text', $premise_base->getSocialShareTwitterText($postId, $link), $postId, $link);
}
function premise_the_social_share_twitter_text($postId = null, $link = false) {
	echo apply_filters('premise_the_social_share_twitter_text', premise_get_social_share_twitter_text($postId, $link), $postId, $link);
}

function premise_get_social_share_twitter_icon($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_social_share_twitter_icon', $premise_base->getSocialShareTwitterIcon($postId), $postId);
}
function premise_the_social_share_twitter_icon($postId = null) {
	echo apply_filters('premise_the_social_share_twitter_icon', premise_get_social_share_twitter_icon($postId), $postId);
}

function premise_get_social_share_facebook_icon($postId = null) {
	global $premise_base;
	return apply_filters('premise_get_social_share_facebook_icon', $premise_base->getSocialShareFacebookIcon($postId), $postId);
}
function premise_the_social_share_facebook_icon($postId = null) {
	echo apply_filters('premise_the_social_share_facebook_icon', premise_get_social_share_facebook_icon($postId), $postId);
}

function premise_get_social_share_enhanced_twitter_share_url($postId = null) {
	global $post;
	if( empty( $postId ) )
		$postId = $post->ID;
	
	return apply_filters('premise_get_social_share_enhanced_twitter_share_url', add_query_arg(array('social-share-ID' => $postId, 'social-share-type' => 'twitter'), get_permalink($postId)), $postId);
}
function premise_the_social_share_enhanced_twitter_share_url($postId = null) {
	echo apply_filters('premise_the_social_share_enhanced_twitter_share_url', premise_get_social_share_enhanced_twitter_share_url($postId), $postId);
}

function premise_get_social_share_twitter_share_url($postId = null) {
	$base = 'https://twitter.com/share/';
	$args = array(
		'url' => urlencode(get_permalink($postId)),
		'text' => urlencode(premise_get_social_share_twitter_text()),
	);
	
	return apply_filters('premise_get_social_share_twitter_share_url', add_query_arg($args, $base), $postId);
}
function premise_the_social_share_twitter_share_url($postId = null) {
	echo apply_filters('premise_the_social_share_twitter_share_url', premise_get_social_share_twitter_share_url($postId), $postId);
}

function premise_get_social_share_enhanced_facebook_share_url($postId = null) {
	if(empty($postId)) {
		global $post;
		$postId = $post->ID;
	}
	
	return apply_filters('premise_get_social_share_enhanced_facebook_share_url', add_query_arg(array('social-share-ID' => $postId, 'social-share-type' => 'facebook'), get_permalink($postId)), $postId);
}
function premise_the_social_share_enhanced_facebook_share_url($postId = null) {
	echo apply_filters('premise_the_social_share_enhanced_facebook_share_url', premise_get_social_share_enhanced_facebook_share_url($postId), $postId);
}

function premise_get_social_share_facebook_share_url($postId = null) {
	$base = 'http://www.facebook.com/sharer.php';
	$args = array(
		'u' => urlencode(get_permalink($postId)),
		't' => urlencode(get_the_title()),
	);
	
	return apply_filters('premise_get_social_share_facebook_share_url', add_query_arg($args, $base), $postId);
}
function premise_the_social_share_facebook_share_url($postId = null) {
	echo apply_filters('premise_the_social_share_facebook_share_url', premise_get_social_share_facebook_share_url($postId), $postId);
}

function premise_social_share_get_shared_page_url($postId = null) {
	if(empty($postId)) {
		global $post;
		$postId = $post->ID;
	}
	
	return apply_filters('premise_social_share_get_shared_page_url', wp_nonce_url(add_query_arg('social-share-ID', $postId, get_permalink($postId)), 'premise-shared-content-'.$postId), $postId);
}
function premise_social_share_the_shared_page_url($postId = null) {
	echo apply_filters('premise_social_share_the_shared_page_url', premise_social_share_get_shared_page_url($postId), $postId);
}

function premise_social_share_get_social_share_type() {
	global $premise_base;
	return apply_filters('premise_social_share_get_social_share_type', $premise_base->getSocialShareType());
}

function premise_footer() {
	do_action( 'premise_before_footer' );
	wp_footer();
	do_action( 'premise_after_footer' );
}

function premise_create_quicktags_script( $number ) {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	setTimeout(function(){ create_premise_quicktags('<?php echo $number; ?>'); }, 250 );
});

var premise_editor_canvas_<?php echo $number; ?> = document.getElementById('<?php echo $number; ?>');
</script>
<?php
}

function premise_get_landing_page_style() {
	global $premise_base;
	$meta = $premise_base->get_premise_meta( null );

	return isset( $meta['style'] ) ? $meta['style'] : 0;
}