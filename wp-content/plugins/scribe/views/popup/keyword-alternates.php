<?php
$keywordInfo = $info['GetAnalysisResult']['Analysis']['PrimaryKeywords'];
$keywords = $keywordInfo['Keywords']['Keyword'];
$properKeywords = array();
foreach($keywords as $keyword) {
	if(in_array($keyword['Rank'],array('Primary', 'Important', 'Significant'))) {
		$properKeywords[] = $keyword;
	}
}

$keywordAlternatesInfo = $info['GetAnalysisResult']['Analysis']['KeywordAlternates'];
if(!is_array($keywordAlternatesInfo)) {
	if(!empty($properKeywords)) {
		$first = $properKeywords[0]['Term'];
		$keywords = $this->getKeywordAlternatesInfo($first);
		$keywords = $keywords->getRawResults();

		$info['GetAnalysisResult']['Analysis']['KeywordAlternates'] = array('Keywords' => array('Keyword' => $keywords['GetKeywordAlternatesResult']['AlternateKeywords']['Keyword']));
		$serialized = base64_encode(serialize($info));
		update_post_meta($_GET['post'], $this->_meta_seoInfo, $serialized);

		$keywordAlternatesInfo = $info['GetAnalysisResult']['Analysis']['KeywordAlternates'];
	}
}
$keywordAlternates = $keywordAlternatesInfo['Keywords']['Keyword'];
?>
<form method="post">
<?php
if(empty($properKeywords)) {
	?>
	<p>
		<?php _e('No Primary, Important, or Significant keywords found.'); ?>
	</p>
	<?php
} else {
	?>
	<p>
		<?php esc_html_e($keywordAlternatesInfo['Description']); ?>
	</p>
	<div id="scribe-content-keywords-container">
		<p><strong><?php _e('Click a keyword below to see alternate suggestions.'); ?></strong></p>
		<ul id="scribe-content-keywords">
			<?php foreach((array)$properKeywords as $number => $keyword) { ?>
			<li><a href="#" id="scribe-content-keyword-<?php echo sanitize_title_with_dashes($keyword['Term']); ?>" class="scribe-content-keyword <?php echo $number == 0 ? 'active' : ''; ?>"><?php esc_html_e($keyword['Term']); ?></a></li>
			<?php } ?>
		</ul>
	</div>

	<div id="scribe-alternate-keywords">
		<table class="widefat" style="display:none;" id="fetching-alternate-keywords-message">
			<thead>
				<tr>
					<th scope="col"><?php _e('Fetching alternate keywords...'); ?></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
		<?php include('alternate-keyword-table.php'); ?>
	</div>
	<br class="clear" />

	<div id="previously-fetched-alternates" style="display: none;">

	</div>
	<?php
}
?>
</form>