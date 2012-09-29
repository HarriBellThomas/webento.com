<p>
	<input class="large-text" type="text" name="scribe-keyword-research-phrase" id="scribe-keyword-research-phrase" />
</p>
<p>
	<label for="scribe-keyword-research-type">
		<?php _e('Match Type'); ?>
		<select name="scribe-keyword-research-type" id="scribe-keyword-research-type">
			<option value="broad"><?php _e('Broad'); ?></option>
			<option value="phrase"><?php _e('Phrase'); ?></option>
			<option value="exact"><?php _e('Exact'); ?></option>
		</select>
	</label>
</p>
<div class="ecordia-analyze-action">
	<p style="text-align: center;">
		<?php _e('Evaluations left: '); ?>
		<strong>
			<span id="scribe-keyword-research-evaluations-left-number">
				<?php echo $this->getNumberKeywordEvaluationsRemaining(); ?>
			</span>
			<?php printf(__(' for %s'), date('F Y')); ?>
		</strong>
	</p>

	<div class="alignleft">
		<p>
			<a href="media-upload.php?tab=ecordia-keyword-research-review&type=ecordia-keyword-research-review&TB_iframe=true" id="scribe-keyword-research-review-button" class="thickbox button-secondary"><?php _e('Review'); ?></a>
			<img class="ajax-feedback" id="scribe-keyword-research-ajax-feedback" alt="" title="" src="images/wpspin_light.gif" />
		</p>
	</div>
	<div class="alignright">
		<p><a href="#" id="scribe-keyword-research-analyze-button" class="button-primary"><?php _e('Get Keyword Ideas'); ?></a></p>
	</div>
	<br class="clear" />
</div>
