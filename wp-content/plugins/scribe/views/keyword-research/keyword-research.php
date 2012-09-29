<?php
$keywordIdeas = $info['GetKeywordIdeasResult']['KeywordIdeas']['KeywordIdea'];
?>
<form id="ecordia-keyword-research-table-container" method="post">
	<h3><?php _e('Keyword Ideas'); ?></h3>
	<p><a href="media-upload.php?tab=ecordia-keyword-research-review&type=ecordia-keyword-research-review"><?php _e('View Previous Research'); ?></a></p>
	<table id="ecordia-keyword-research-table" class="widefat">
		<thead>
			<tr>
				<th scope="col" class="ecordia-keyword-research-keyword"><?php _e('Keyword'); ?></th>
				<th scope="col" class="ecordia-keyword-research-annual-search-volume"><?php _e('Annual Search Volume'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th scope="col" class="ecordia-keyword-research-keyword"><?php _e('Keyword'); ?></th>
				<th scope="col" class="ecordia-keyword-research-annual-search-volume"><?php _e('Annual Search Volume'); ?></th>
			</tr>
		</tfoot>
		<tbody>
			<?php if(empty($keywordIdeas)) { ?>
				<tr><td colspan="2" style="text-align: center; font-weight: bold;"><?php _e('No related keywords found'); ?></td></tr>
			<?php } else { ?>
				<?php foreach((array)$keywordIdeas as $key => $keywordIdeaData) {
					if($keywordIdeaData['AnnualSearchVolume'] == 0) {
						continue;
					}
					if ($keywordIdeaData['Term'] == $phrase){
						$keywordIdeaData['Term'] = '<strong>'.$phrase.'</strong>';
     				}
					?>
				<tr>
					<td class="ecordia-keyword-research-keyword"><?php echo $keywordIdeaData['Term']; ?></td>
					<td class="ecordia-keyword-research-annual-search-volume"><?php esc_html_e(number_format_i18n($keywordIdeaData['AnnualSearchVolume'], 0)); ?></td>
				</tr>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</form>