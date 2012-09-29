<?php
/*
Template Name: Opt In
Template Description: Gather emails for your mailing list using the targeted Opt In page type.
*/
get_header();

$landing_page_style = premise_get_landing_page_style();
$entryOptinWidth = intval( premise_get_fresh_design_option( 'wrap_width', $landing_page_style ) ) - 2 * intval( premise_get_fresh_design_option( 'wrap_padding', $landing_page_style ) ) - intval( premise_get_fresh_design_option( 'optin_holder_padding', $landing_page_style ) * 2 + 2 * premise_get_fresh_design_option( 'optin_holder_border', $landing_page_style ) );
?>
	<div id="content" class="hfeed">
		<?php the_post(); ?>
		<div class="hentry">
			<?php include('inc/headline.php'); ?>

			<div style="width: <?php echo $entryOptinWidth; ?>px;" class="entry-optin entry-optin-align-<?php premise_the_optin_align(); ?>">
				<?php premise_the_optin_form_code(); ?>
				<?php if(premise_get_optin_align() != 'center') { ?>
				<?php echo apply_filters('the_content', premise_get_optin_copy()); ?>
				<?php } ?>
				<span class="clear"></span>
			</div>
			<div class="entry-content"><?php echo apply_filters('the_content', premise_get_optin_below_copy()); ?></div>
		</div>
	</div><!-- end #content -->
	<?php
get_footer();