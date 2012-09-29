<form method="post">
	<div>
		<p><?php _e('Extend the reach of your content with social media. Listed below are people who are talking about topics related to your keywords right now. Check their Klout authority, visit their twitter account and reach out to them.'); ?></p>
	</div>
	<?php include('keyword-selector.php'); ?>
	<div id="link-building-research-results-container">
		<?php include('research-results-list.php'); ?>
	</div>
	<br class="clear" />
	<div class="alignright" style="margin-top: 10px;">
		<a title="<?php _e('Klout Powered'); ?>" href="http://klout.com" target="_blank"><img src="<?php esc_attr_e($klout_logo_url); ?>" alt="Klout Logo" /></a>
	</div>
</form>