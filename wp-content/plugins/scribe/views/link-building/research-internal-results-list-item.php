<div class="scribe-link-building-result">
	<?php
	$linksFrom = add_query_arg(array('kwds'=>implode(',', $research['CurrentSelections']),'url'=>$link['Url']), 'https://my.scribeseo.com/optimizer/post-internal-links.aspx');
	?>
	<p><strong><?php _e('Get links from...'); ?></strong> <a target="_blank" href="<?php esc_attr_e($linksFrom); ?>"><?php echo(htmlentities($link['Title'])); ?></a></p>
	<small><?php _e('Found at '); ?><a target="_blank" href="<?php esc_attr_e($link['Url']); ?>"><?php esc_html_e($link['Url']); ?></a></small>
</div>