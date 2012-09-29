<?php $trackingSettings = $settings['tracking']; ?>

<div class="premise-option-box">
	<h4><?php _e('Duplicate Page', 'premise' ); ?></h4>
	<p><?php _e('Do you want to duplicate this page and all associated meta so that you can A/B test two versions?', 'premise' ); ?></p>
	<input class="button button-primary" type="submit" name="premise-duplicate-page" id="premise-duplicate-page" value="<?php _e('Duplicate!', 'premise' ); ?>" />
</div>

<h4><?php _e('Visual Website Optimizer', 'premise' ); ?></h4>
<p><?php printf(__('To use VWO on this page, make sure you have your Visual Website Optimizer Account ID entered on the <a target="_blank" href="%s">Main Settings</a> page, then log into your VWO account to set up and conduct your tests.', 'premise' ), admin_url('admin.php?page=premise-main')); ?>

<h4><?php _e('Google Website Optimizer', 'premise' ); ?></h4>
<?php if(empty($trackingSettings['account-id'])) { ?>

<p><?php printf(__('You must <a target="_blank" href="%s">setup</a> your Google Website Optimizer account ID before enabling tracking on this landing page.', 'premise' ), admin_url('admin.php?page=premise-main#premise-tracking')); ?></p>

<?php } else { ?>

<p><?php _e('You can use Google Website Optimizer to track your conversions and do A/B or multivariate testing for this landing page. You have to set up a few options before it is ready to go.', 'premise' ); ?></p>

<p><?php _e('<strong>Note:</strong> You must publish your page before Google can validate your page for testing. GWO will not be able to see your page when it is set as Draft or Private. once published, you can validate and set up your experiments in your GWO account.'); ?></p>

<div class="premise-option-box">
	<h4><?php _e('Enable Google Website Optimizer', 'premise' ); ?></h4>
	<p><?php _e('Google Website Optimizer support for this page is disabled by default.  To turn it on, simply check the box.', 'premise' ); ?></p>
	<ul>
		<li>
			<label>
				<input <?php checked(1, $tracking['enable-gwo']); ?> type="checkbox" name="premise-tracking[enable-gwo]" id="premise-tracking-enable-gwo" value="1" />
				<?php _e('Enable Google Website Optimizer for this page?', 'premise' ); ?>
			</label>
		</li>
	</ul>
</div>

<div id="premise-tracking-enabled-options">
	<div class="premise-option-box">
		<h4><?php _e('A/B Test Status', 'premise' ); ?></h4>
		<p><?php _e('Is this page part of an A/B test?  If so, check the box below.', 'premise' ); ?></p>
		<ul>
			<li>
				<label>
					<input <?php checked(1, $tracking['ab']); ?> type="checkbox" name="premise-tracking[ab]" id="premise-tracking-ab" value="1" />
					<?php _e('This page is part of an A/B test', 'premise' ); ?>
				</label>
			</li>
			<li id="premise-tracking-ab-original-container">
				<label>
					<input <?php checked(1, $tracking['ab-original']); ?> type="checkbox" name="premise-tracking[ab-original]" id="premise-tracking-ab-original" value="1" />
					<?php _e('This page is the original', 'premise' ); ?>
				</label>
			</li>
		</ul>
	</div>

	<div class="premise-option-box">
		<h4><label for="premise-tracking-test-id"><?php _e('Test ID', 'premise' ); ?></label></h4>
		<p><?php _e('Enter your experiment tracking ID or enter your test tracking code and we\'ll parse the ID for you.', 'premise' ); ?></p>
		<input class="large-text code" name="premise-tracking[test-id]" id="premise-tracking-test-id" value="<?php echo esc_attr($tracking['test-id']); ?>" />
		<p>
			<strong><?php _e('Recommended:', 'premise' ); ?></strong>
			<?php _e('If you\'re not sure how to find your test ID, just copy the entire test tracking code that Google gives you into the input above.  Premise will sort everything out.', 'premise' ); ?>
		</p>
	</div>

	<div class="premise-option-box">
		<h4><?php _e('Page Tracking Type', 'premise' ); ?></h4>
		<p><?php _e('What kind of page is this in light of your test?  Is it a test page or a goal page?', 'premise' ); ?></p>
		<ul>
			<li>
				<label>
					<input <?php checked(true, empty($tracking['page-type']) || 'test' == $tracking['page-type']); ?> type="radio" name="premise-tracking[page-type]" id="premise-tracking-page-type-test" value="test" />
					<?php _e('This is a test page', 'premise' ); ?>
				</label>
			</li>
			<li>
				<label>
					<input <?php checked('goal', $tracking['page-type']); ?> type="radio" name="premise-tracking[page-type]" id="premise-tracking-page-type-goal" value="goal" />
					<?php _e('This is a goal page', 'premise' ); ?>
				</label>
			</li>
			<li id="premise-tracking-link-click-conversion-container">
				<label>
					<input <?php checked(1, $tracking['link-click-conversion']); ?> type="checkbox" name="premise-tracking[link-click-conversion]" id="premise-tracking-link-click-conversion" value="1" />
					<?php _e('Treat link clicks as conversions (goal)', 'premise' ); ?>
				</label>
			</li>
		</ul>
	</div>
</div>
<?php 
	wp_nonce_field('save-premise-tracking-settings', 'save-premise-tracking-settings-nonce');
} ?>