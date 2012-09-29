<?php
$id = $_REQUEST['id'];
$info = $links[$id];
?>

<form method="post" action="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'id' => $id ), $menu_page_url ) ); ?>">
<?php wp_nonce_field( 'accesspress-action-edit-link' ); ?>
<table class="form-table">
	
	<tr class="form-field">
		<th scope="row" valign="top"><?php _e( 'Link URI', 'premise' ); ?></th>
		<td>
			<p class="description"><?php _e( 'Copy this URI to share with members.', 'premise'); ?></p>
			<p><?php echo esc_url( home_url( sprintf( '/?download_id=%s', $id ) ) ); ?>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top"><label for="edit_link[name]"><?php _e( 'Name', 'premise' ); ?></label></th>
		<td><input name="edit_link[name]" id="edit_link[name]" type="text" value="<?php echo esc_attr( $info['name'] ); ?>" size="40" />
		<p class="description"><?php _e( 'A recognizable name for your new link.', 'premise'); ?></p></td>
	</tr>
	
	<tr class="form-field">
		<th scope="row" valign="top"><label for="edit_link[filename]"><?php _e( 'Filename', 'premise' ); ?></label></th>
		<td>
			<input name="edit_link[filename]" id="edit_link[filename]" type="text" value="<?php echo esc_attr( $info['filename'] ); ?>" size="40" />
			<p class="description">
				<?php _e( 'Type the filename you wish to link to.', 'premise' ); ?><br />
				<?php
				$uploads = wp_upload_dir();
				printf( __( 'File must exist in the %s directory.', 'premise' ), $uploads['basedir'] . '/' .accesspress_get_option( 'uploads_dir' ) );
				?>
			</p>
		</td>
	</tr>
	
	<tr class="form-field">
		<th scope="row" valign="top"><label for="edit_link[delay]"><?php _e( 'Delay Access', 'premise' ); ?></label></th>
		<td><input name="edit_link[delay]" id="edit_link[delay]" type="text" value="<?php echo esc_attr( absint( $info['delay'] ) ); ?>" size="5" style="width: auto;" /> <?php _e( 'Days', 'premise' ); ?>
		<p class="description"><?php _e( 'Delay access to this file by X days after signup.', 'premise' ); ?></p></td>
	</tr>
	
	<tr class="form-field">
		<th scope="row" valign="top"><?php _e( 'Access Level(s)', 'premise' ); ?></th>
		<td>
			<p class="description"><?php _e( 'Choose the access level(s) the logged in member must have in order to access this file.', 'premise' ); ?></p>
			<p><?php
				echo accesspress_get_access_level_checklist( array( 'name' => 'edit_link[access-levels][]', 'selected' => $info['access-levels'], 'style' => 'style="width: auto;"' ) );
			?></p>
		</td>
	</tr>


</table>

<p class="submit"><input type="submit" class="button-primary" name="submit" value="<?php _e('Update', 'premise'); ?>" /></p>

</form>