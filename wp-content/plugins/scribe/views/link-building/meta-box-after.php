<table class="form-table">
	<tr>
		<td><?php _e('External Links:'); ?></td>
		<td><?php esc_html_e($this->getNumberExternalLinkResearch($post->ID)); ?></td>
	</tr>
	<tr>
		<td><?php _e('Internal Links:'); ?></td>
		<td><?php esc_html_e($this->getNumberInternalLinkResearch($post->ID)); ?></td>
	</tr>
	<tr>
		<td><?php _e('Social Media Links:'); ?></td>
		<td><?php esc_html_e($this->getNumberSocialLinkResearch($post->ID)); ?></td>
	</tr>
</table>
<div class="ecordia-analyze-action">
	<div class="" style="text-align: center;">
		<p>
			<a title="<?php _e('Link Research'); ?>" href="media-upload.php?tab=ecordia-link-building-external&type=ecordia-link-building-external&post=<?php echo $post->ID; ?>&TB_iframe=true" class="thickbox button-primary" id="scribe-link-building-research"><?php _e('Research'); ?></a>
		</p>
	</div>
	<br class="clear" />
</div>