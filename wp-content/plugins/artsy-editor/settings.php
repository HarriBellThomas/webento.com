<?php
/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

/**
 * The settings screen
 *
 * @since 0.6
**/
?>

<div class="wrap">
	<div id="icon-options-general" class="icon32"><br>
	</div>
	<h2>Artsy Editor Settings</h2>
	<?php $check_updates = $this->check_for_updates();  ?>
	<?php if (!is_null( $this->license_data ) AND is_object($this->license_data) AND $this->license_data->license_status == 'valid') : ?>
	<?php
	if ( isset($_GET['do_update']) AND wp_verify_nonce($_GET['nonce'], 'artsy-update'))
	{
		$do_update = $this->perform_update();
		if (is_wp_error($do_update) OR !$do_update)
		{
			echo "<strong>Error updating.</strong>";
		}
	}
	endif;
	$selected_html = ' selected="selected"';
	$checked_html = ' checked="checked"';
	?>
	<div id="poststuff" class="metabox-holder has-right-sidebar">
		
		<?php if (!is_null( $this->license_data ) AND is_object($this->license_data) AND $this->license_data->license_status == 'valid') : ?>
		<!-- Update center! -->
		<div id="side-info-column" class="inner-sidebar">
			<div class="meta-box-sortables">
				<div id="about" class="postbox ">
					<h3 class="artsy_title">Update Center</h3>
					<div class="inside">
					<p><strong>Current Version:</strong> <?php echo ARTSY_VERSION; ?></p>
			<p><strong>Latest Release:</strong> <?php echo $check_updates['release_version']; ?></p>
			<p><strong>Release Track:</strong> <?php echo ucwords($check_updates['track']); ?></p>
			
			<?php
			//	Update Available!
			if ( is_array($check_updates) && $check_updates['needs_to_update'] == TRUE )
			{
				?>
			<p><a href="<?php echo admin_url('plugins.php?page=artsy_editor&do_update=true&nonce='.wp_create_nonce('artsy-update')); ?>" class="button-primary">Update Artsy Editor!</a></p>
			<?php
			}
			?>
			<h4>Change Log</h4>
			<div class="artsy_change_log_box"><?php echo $check_updates['change_log']; ?></div>
						<p>&copy; Copyright 2010 - <?php echo date('Y'); ?> <a href="http://artsyeditor.com">Artsy Editor</a></p>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<div id="post-body" class="has-sidebar">
		<div id="post-body-content" class="has-sidebar-content">
			<!-- Main content -->
			<p>Be sure to also follow us on Twitter at <a href="http://twitter.com/artsyeditor/" target="_blank">@artsyeditor</a> for all the latest news!</p>
			<p>You are using version <strong><?php echo ARTSY_VERSION; ?></strong>. Please stay updated!</p>
			<?php
//	Check for updates
if ( is_array($check_updates) && $check_updates['needs_to_update'] == TRUE )
{
	?>
			<div id="setting-error-settings_updated" class="updated settings-error">
				<p><strong>An update is available! Log into the <a href="http://artsyeditor.com/">Artsy Editor</a> website and download the latest version. For help, see our <a href="http://artsyeditor.com/faq/">FAQ</a> for instructions.</strong></p>
			</div>
			<?php
}
?>
			<?php if (isset($_GET['did_update'])) { ?>
				<div id="setting-error-settings_updated" class="updated settings-error">
					<p><strong>Settings saved.</strong></p>
				</div>
				<?php } ?>
			<form method="post" action="<?php echo admin_url('plugins.php?page=artsy_editor'); ?>">
				<?php
//	Nonces
wp_nonce_field('artsy-action', 'artsy-nonce', TRUE);
?>
				<table class="form-table">
					<?php
//	Loop though each setting
foreach ($settings_array as $settings_row) : ?>
					<?php $option = get_option($this->prefix.$this->format_setting($settings_row)); ?>
					<?php if ($settings_row !== 'Help Shown') { ?>
					<tr>
						<th scope="row"><?php echo $settings_row; ?><?php if ($settings_row == 'License Key') { ?><br /><?php } ?></th>
						<td><fieldset>
								<legend class="screen-reader-text"> <span><?php echo _e($settings_row); ?></span> </legend>
								<?php
			if ($settings_row == 'License Key')
			{
				?><textarea name="<?php echo $this->format_setting($settings_row); ?>" class="large-text"><?php echo $option; ?></textarea>
								<?php
			}
			
			//	The font's to use
			if ($settings_row == 'Font') {
				?>
								<select name="<?php echo $this->format_setting($settings_row); ?>" id="<?php echo $this->format_setting($settings_row); ?>">
									<?php foreach ($font_array as $font_row) : ?>
									<?php
					if ($option == $font_row)
						$select = $selected_html;
					else
						$select = '';
					?>
									<option value="<?php echo $font_row; ?>"<?php echo $select; ?>><?php echo $font_row; ?></option>
									<?php
				endforeach;
				?>
								</select>
								<?php
			}
			
			//	Font size
			if ($settings_row == 'Font Size')
			{
				?>
								<input name="<?php echo $this->format_setting($settings_row); ?>" type="range" min="8" max="24" value="<?php echo $option; ?>">
								<?php
			}
			
			//	The background options
			if ($settings_row == 'Background')
			{
				?>
								<select name="<?php echo $this->format_setting($settings_row); ?>" id="<?php echo $this->format_setting($settings_row); ?>">
									<?php
				foreach ($background_array as $background_row) :
					if ($option == $background_row)
						$select = $selected_html;
					else
						$select = '';
					?>
									<option value="<?php echo $background_row; ?>"<?php echo $select; ?>><?php echo $background_row; ?></option>
									<?php
				endforeach;
				?>
								</select>
								<?php
			}
			
			//	Should we Open Automatically?
			if ($settings_row == 'Open Automatically')
			{
				$count = count($open_automatically_array) - 1;
				foreach ($open_automatically_array as $open_automatically_row) :
					if ($option == $count)
						$check = $checked_html;
					else
						$check = '';
					?>
								<label>
									<input type="radio" name="<?php echo $this->format_setting($settings_row); ?>" value="<?php echo $count; ?>"<?php echo $check; ?> />
									<span><?php echo $open_automatically_row; ?></span></label>
								<br />
								<?php
					
					$count = $count - 1;
				endforeach;
			}
			
			
			
			//	Should we open in visual or HTML?
			if ($settings_row == 'Open In')
			{
				foreach ($open_in_array as $open_in_row) {
					if ($option == $this->format_setting($open_in_row)) $check = $checked_html;
					else $check = '';
					?>
								<label>
									<input type="radio" name="<?php echo $this->format_setting($settings_row); ?>" value="<?php echo $this->format_setting($open_in_row); ?>"<?php echo $check; ?> />
									<span><?php echo $open_in_row; ?></span></label>
								<br />
								<?php
					$count = $count - 1;
				}
			}
			
			//	Open in Visual mode or HTML mode
			if ($settings_row == 'Open in Visual or HTML Mode')
			{
				$visual_checked = $html_checked = FALSE;
				if($option == 'html') $html_checked = TRUE;
				if($option == 'visual') $visual_checked = TRUE;
				
				if (! $visual_checked AND ! $html_checked) $visual_checked = TRUE;	//	Default to this
				?>
					<label>
						<input type="radio" name="<?php echo $this->format_setting($settings_row); ?>" value="visual" <?php echo ($visual_checked) ? 'checked="checked"' : ''; ?> /> <span>Visual Mode</span><br />
					</label>
					
					<label>
						<input type="radio" name="<?php echo $this->format_setting($settings_row); ?>" value="html" <?php echo ($html_checked) ? 'checked="checked"' : ''; ?> /> <span>HTML Mode</span><br />
					</label>
				
		<?php } ?>
		</fieldset></td></tr>
		<?php } 
		endforeach;
		?>
				</table>
				<p class="submit">
					<input type="submit" name="<?php echo $this->prefix; ?>submit" id="submit" class="button-primary" value="<?php _e('Save Changes'); ?>"  />
				</p>
			</form>
			<em>
			<?php _e('Last Update Check Time:'); ?>
			<?php echo date_i18n('F j, Y g:i a', get_option($this->prefix.'last_update_time'), 1); ?></em> </div>
	</div>
	</div>
</div>

<!-- Code is poetry. -->