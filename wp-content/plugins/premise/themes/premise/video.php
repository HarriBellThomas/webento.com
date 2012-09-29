<?php
/*
Template Name: Video
Template Description: Embed a video that makes it easier to sell your product or subscription to potential customers.
*/

add_action( 'premise_immediately_after_head', 'colorbox_scripts' );
function colorbox_scripts() {
	
?>
	
<link rel="stylesheet" href="<?php echo plugins_url('/js/colorbox/colorbox.css', __FILE__); ?>" />
<script src="<?php echo plugins_url('/js/colorbox/jquery.colorbox-min.js', __FILE__); ?>"></script>
<script type="text/javascript" charset="utf-8">

	$jQuery = jQuery.noConflict();
	$jQuery(document).ready(function(){

		$jQuery("#inline").colorbox({ inline:true });

	});

</script>
	
<?php
	
}

get_header();

$landing_page_style = premise_get_landing_page_style();
$entryVideoWidth = intval( premise_get_fresh_design_option( 'wrap_width', $landing_page_style ) ) - intval( premise_get_fresh_design_option( 'video_holder_padding', $landing_page_style ) * 2 + 2 * premise_get_fresh_design_option( 'video_holder_border', $landing_page_style ) );
?>
	<div id="content" class="hfeed">
		<?php the_post(); ?>
		<div class="hentry">
			<?php include('inc/headline.php'); ?>
			
			<div class="entry-video entry-video-align-<?php premise_the_video_align(); ?>">
				<div class="container-border">
					<div class="entry-video-video">
						<?php if(premise_has_video_image()) { ?>
						<a id="inline" href="#entry-video-video-embed"><img src="<?php premise_the_video_image(); ?>" alt="<?php premise_the_video_image_title(); ?>" /></a>
						<?php } else { premise_the_video_embed_code(); } ?>
					</div>
					<?php if(premise_has_video_image()) { ?><div style="display:none"><div id="entry-video-video-embed"><?php premise_the_video_embed_code(); ?></div></div><?php } ?>
					<?php if(premise_get_video_align() != 'center') { ?>
					<div class="entry-video-content"><?php echo apply_filters('the_content', premise_get_video_copy()); ?></div>
					<?php } ?>
					<span class="clear"></span>
				</div>
				<span class="clear"></span>
			</div>
			<div class="entry-content"><?php echo apply_filters('the_content', premise_get_video_below_copy()); ?></div>
		</div>
	</div><!-- end #content -->
	<?php
get_footer();