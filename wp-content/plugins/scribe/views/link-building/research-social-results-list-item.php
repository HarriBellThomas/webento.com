<div class="scribe-link-building-result">
	<div class="scribe-link-building-profile-result-image">
		<img src="<?php esc_attr_e($link['ProfileImage']); ?>" alt="<?php esc_attr_e($link['ScreenName']); ?>" height="48" width="48" />
	</div>
	<div class="scribe-link-building-social-result-content">
		<p>
			<a href="http://www.twitter.com/<?php esc_attr_e($link['ScreenName']); ?>" target="_blank"><?php esc_html_e($link['ScreenName']); ?></a><br />
			<strong><?php _e('Klout Score:'); ?></strong> <a href="http://klout.com/profile/summary/<?php esc_attr_e($link['ScreenName']); ?>" target="_blank"><?php esc_html_e($link['ReputationScore']); ?></a>
		</p>
		<?php
		if(isset($link['Entries']['SocialEntry']['EntryId'])) {
			$link['Entries']['SocialEntry'] = array($link['Entries']['SocialEntry']);
		}
		?>
		<?php foreach((array)$link['Entries']['SocialEntry'] as $socialEntry) { ?>
		<p><?php echo $socialEntry['Text']; echo '<br /><small>'; _e(' via twitter at '); esc_html_e($socialEntry['CreateDateTime']); echo '</small>'; ?></p>
		<?php } ?>
	</div>
	<br class="clear" />
</div>