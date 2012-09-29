<form id="ecordia-keyword-research-table-container" method="post">
	<h3><?php _e('Keyword Research Review'); ?></h3>
	<table id="ecordia-keyword-research-table" class="widefat">
		<thead>
			<tr>
				<th scope="col" class="ecordia-keyword-research-keyword"><?php _e('Keyword'); ?></th>
				<th scope="col" class="ecordia-keyword-research-match-type"><?php _e('Match Type'); ?></th>
				<th scope="col" class="ecordia-keyword-research-actions"><?php _e('Actions'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th scope="col" class="ecordia-keyword-research-keyword"><?php _e('Keyword'); ?></th>
				<th scope="col" class="ecordia-keyword-research-match-type"><?php _e('Match Type'); ?></th>
				<th scope="col" class="ecordia-keyword-research-actions"><?php _e('Actions'); ?></th>
			</tr>
		</tfoot>
		<tbody>
			<?php
			$history = $this->getKeywordResearchHistory();
			if(empty($history)) {
				?>
				<tr>
					<th scope="row" colspan="3" style="text-align: center;"><?php _e('No Keyword Research Found'); ?></th>
				</tr>
				<?php
			} else {
				foreach($history as $key => $item) {
				?>
				<tr>
					<th scope="row"><?php esc_html_e($item['phrase']); ?></th>
					<td><?php esc_html_e(ucwords($item['type'])); ?></td>
					<td><a href="media-upload.php?tab=ecordia-keyword-research&type=ecordia-keyword-research&phrase=<?php echo urlencode($item['phrase']); ?>&match-type=<?php echo $item['type']; ?>"><?php _e('View'); ?></a></td>
				</tr>
				<?php
				}
			}
			?>
		</tbody>
	</table>
</form>