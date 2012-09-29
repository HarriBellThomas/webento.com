<?php
/*
Template Name: Pricing Table
Template Description: The pricing table landing page allows you to break out your product or subscription cost in an easy to understand manner.
*/

get_header();
	?>
	<div id="content" class="hfeed">
		<?php the_post(); ?>
		<div class="hentry">
			<?php include('inc/headline.php'); ?>

			<div class="entry-content">
				<div class="premise-above-pricing-table-content">
					<?php premise_the_above_pricing_table_content(); ?>
				</div>
				<?php include STYLESHEETPATH . '/inc/pricing-table.php'; ?>
				<div class="premise-above-pricing-table-content">
					<?php premise_the_below_pricing_table_content(); ?>
				</div>
			</div>
		</div>
	</div><!-- end #content -->
	
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		var columnHeight = 0;
		$('.pricing-table-column-properties').each(function() {
			var height = $(this).height();
			if(height > columnHeight) {
				columnHeight = height;
			}
		});
		
		$('.pricing-table-column-properties').css('height', columnHeight+'px');
	});
	</script>
	<?php
get_footer();