<div id="scribe-link-building-results-list">
	<?php
	if($research) {
		if($type == 'social') {
			$links = $research['GetSocialMediaSearchResultsResult']['Entries']['SocialMediaUser'];
			if(is_array($links['Entries'])) {
				$links = array($links);
			}
		} else {
			$links = $research['GetSearchEngineResultsResult']['Links']['SearchEngineLink'];
		}
		if(is_array($links) && !empty($links)) {
			foreach($links as $link) {
				include('research-'.$type.'-results-list-item.php');
			}
		} else {
			?>
			<p style="text-align: center;"><?php _e('Could not find any appropriate items.'); ?></p>
			<?php
		}
	}
	?>
</div>