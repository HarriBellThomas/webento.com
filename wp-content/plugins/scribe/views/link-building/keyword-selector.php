<div id="scribe-link-building-keyword-form">
	<h4 style="margin-top: 0px;"><?php _e('Select Keywords'); ?></h4>
	<p id="scribe-link-building-keyword-term-container">
		<select name="scribe-link-building-keyword-term" id="scribe-link-building-keyword-term">
			<?php foreach($properKeywords as $keyword) {
			
if ($keyword['Rank'] != 'Primary'){
     continue;
    }

				if(is_array($research['CurrentSelections']) && in_array($keyword['Term'], (array)$research['CurrentSelections'])) { continue; }
				?>
				<option value="<?php esc_attr_e($keyword['Term']); ?>"><?php esc_html_e($keyword['Term']); ?></option>
			<?php } ?>
		</select>
		<a class="button-primary" id="scribe-link-building-add-keyword" href="#"><?php _e('Add'); ?></a>
	</p>
	<ul id="scribe-link-building-keyword-terms-selected">
		<li id="scribe-link-building-keyword-terms-selected-template"><span></span> &mdash; <a href="#" class="scribe-link-building-remove-keyword"><?php _e('Remove'); ?></a></li>
		<?php foreach((array)$research['CurrentSelections'] as $already) { ?>
		<li><span><?php esc_html_e($already); ?></span> &mdash; <a href="#" class="scribe-link-building-remove-keyword"><?php _e('Remove'); ?></a></li>
		<?php } ?>
	</ul>
	<p class="submit">
		<a href="#" id="scribe-link-building-get-topics" class="button-primary" rel=""><?php _e('Get Topics'); ?></a>
		<img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif" id="scribe-link-building-ajax-indicator" />
	</p>
</div>