<div class="scribe-link-building-result">
	<div class="scribe-link-building-result-image">
		<img src="<?php esc_attr_e($link['WebSiteThumbNailSource']); ?>" alt="<?php esc_attr_e($link['Title']); ?>" height="90" width="120" />
	</div>
	<div class="scribe-link-building-result-content">
		<div><a href="<?php esc_attr_e($link['Url']); ?>" target="_blank"><?php echo(htmlentities($link['Title'])); ?></a></div>
		<div><?php echo(htmlentities($link['Description'])); ?></div>
	</div>
	<br class="clear" />
</div>