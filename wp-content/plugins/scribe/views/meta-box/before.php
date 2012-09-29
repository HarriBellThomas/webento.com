<?php include ( dirname( __FILE__ ) . '/validation-list.php' ); ?>
<div class="ecordia-analyze-action">
	<?php include('evaluations-remaining.php'); ?>
	<div class="alignleft">
		<p class="ajax-feedback" id="ecordia-ajax-feedback">
			<img alt="" title="" src="images/wpspin_light.gif" /> <?php _e( 'Analyzing...' ); ?>
		</p>
	</div>
	<div class="alignright">
		<p>
			<a href="#" id="ecordia-seo-analysis-analyze-button" class="button-primary"><?php _e( 'Analyze' ); ?></a>
		</p>
	</div>
	<br class="clear" />
</div>