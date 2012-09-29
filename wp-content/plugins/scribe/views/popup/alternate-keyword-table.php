<?php
if ($keywordAlternates != null) {
	$denom = $keywordAlternates[0]['AnnualSearchVolume'];
} else {
	$no_data = true;
	$no_data_text = __('No alternate keywords found');
	$denom = 1;
}
?>
<table class="widefat alternate-keywords-table">
	<thead>
		<tr>
			<th scope="col"><?php _e('Alternate Keyword Suggestions'); ?></th>
			<th scope="col"><?php _e('Relative Search Frequency'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th scope="col"><?php _e('Alternate Keyword Suggestions'); ?></th>
			<th scope="col"><?php _e('Relative Search Frequency'); ?></th>
		</tr>
	</tfoot>
	<tbody>
	<?php foreach((array)$keywordAlternates as $alternate) { ?>
		<?php
		$width = ceil($alternate['AnnualSearchVolume'] / $denom * 100);
		?>
		<tr>
			<?php if($no_data) { ?>
			<td colspan="2" style="text-align: center;"><?php esc_html_e($no_data_text); ?></td>
			<?php } else { ?>
			<td><?php esc_html_e($alternate['Term']); ?></td>
			<td class="search-volume">
				<div class="scribe-alternate-keyword-relative-search-volume" style="width: <?php echo $width; ?>%;"><?php esc_html_e($alternate['AnnualSearchVolume']); ?></div>
			</td>
			<?php } ?>
		</tr>
	<?php } ?>
	</tbody>
</table>