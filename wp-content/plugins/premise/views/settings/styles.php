<h3><?php _e('Your Styles', 'premise' ); ?> <a href="<?php echo esc_url(admin_url('admin.php?page=premise-style-settings')); ?>" class="button button-secondary"><?php _e('Add New', 'premise' ); ?></a></h3>

<table class="widefat">
	<thead>
		<tr>
			<th scope="col"><?php _e('Title', 'premise' ); ?></th>
			<th scope="col"><?php _e('Last Saved', 'premise' ); ?></th>
			<th scope="col"><?php _e('Actions', 'premise' ); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th scope="col"><?php _e('Title', 'premise' ); ?></th>
			<th scope="col"><?php _e('Last Saved', 'premise' ); ?></th>
			<th scope="col"><?php _e('Actions', 'premise' ); ?></th>
		</tr>
	</tfoot>
	<tbody>
		<?php $settings = $this->getDesignSettings(); ?>
		<?php foreach($this->getDesignSettings() as $key => $style) { if(!is_array($style)) { $style = array(); } ?>
		<tr>
			<td><a href="<?php echo esc_url(add_query_arg(array('premise-design-key' => $key), admin_url('admin.php?page=premise-style-settings'))); ?>"><?php echo esc_html($style['premise_style_title']); ?></a></td>
			<td>
			<?php 
			$time = empty( $style['premise_style_timesaved'] ) ? current_time( 'timestamp' ) : $style['premise_style_timesaved'];
			echo esc_html( date( __( 'F j, Y \a\t g:iA', 'premise' ), $time ) );
			?>
			</td>
			<td>
				<a href="<?php echo esc_url(add_query_arg(array('premise-design-key' => $key), admin_url('admin.php?page=premise-style-settings'))); ?>"><?php _e('Edit', 'premise' ); ?></a>
				| <a href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('premise-design-key' => $key, 'premise-duplicate-style' => 'true'), admin_url('admin.php?page=premise-style-settings')), 'premise-duplicate-style')); ?>"><?php _e('Duplicate', 'premise' ); ?></a>
				<?php if($key !== 0) { ?> 
				| <a href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('premise-design-key' => $key, 'premise-delete-style' => 'true'), admin_url('admin.php?page=premise-styles')), 'premise-delete-style')); ?>"><?php _e('Delete', 'premise' ); ?></a>
				<?php } ?>
			</td>
		</td>
		<?php } ?>
	</tbody> 
</table>

<h3 id="your-buttons"><?php _e('Your Buttons', 'premise' ); ?> <a href="<?php echo add_query_arg(array('height' => 700), get_upload_iframe_src('premise-button-create')); ?>" class="thickbox button button-secondary"><?php _e('Add New', 'premise' ); ?></a></h3>
<table class="widefat">
	<thead>
		<tr>
			<th scope="col"><?php _e('Title', 'premise' ); ?></th>
			<th scope="col"><?php _e('Last Saved', 'premise' ); ?></th>
			<th scope="col"><?php _e('Example Button', 'premise' ); ?></th>
			<th scope="col"><?php _e('Actions', 'premise' ); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th scope="col"><?php _e('Title', 'premise' ); ?></th>
			<th scope="col"><?php _e('Last Saved', 'premise' ); ?></th>
			<th scope="col"><?php _e('Example Button', 'premise' ); ?></th>
			<th scope="col"><?php _e('Actions', 'premise' ); ?></th>
		</tr>
	</tfoot>
	<tbody>
		<?php foreach($this->getConfiguredButtons() as $key => $button) { ?>
		<tr>
			<td><?php echo esc_html($button['title']); ?></td>
			<td><?php echo esc_html(date('F j, Y \a\t g:iA', $button['lastsaved']));  ?></td>
			<td><a href="#" onclick="return false;" style="display:block; float: left; margin: 5px 0;" class="premise-button-<?php echo $key; ?>"><?php echo esc_html($button['title']); ?></a></td>
			<td>
				<a class="thickbox" href="<?php echo esc_url(add_query_arg(array('height' => 500), premise_get_media_upload_src('premise-button-create', array('premise-button-id' => $key)))); ?>"><?php _e('Edit', 'premise' ); ?></a>
				| <a href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('premise-button-id' => urlencode($key), 'premise-duplicate-button' => 'true'), admin_url('admin.php?page=premise-style-settings')), 'premise-duplicate-button')); ?>"><?php _e('Duplicate', 'premise' ); ?></a>
				| <a href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('premise-button-id' => urlencode($key), 'premise-delete-button' => 'true'), admin_url('admin.php?page=premise-styles')), 'premise-delete-button')); ?>"><?php _e('Delete', 'premise' ); ?></a>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>



