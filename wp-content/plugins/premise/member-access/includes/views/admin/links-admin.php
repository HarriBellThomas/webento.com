<div id="col-container">

<div id="col-right">
<div class="col-wrap">

<h3><?php _e( 'Current Links', 'premise' ); ?></h3>
<table class="widefat tag fixed" cellspacing="0">
	<thead>
	<tr>
	<th scope="col" id="name" class="manage-column column-name"><?php _e( 'Name', 'premise' ); ?></th>
	<th scope="col" class="manage-column column-slug"><?php _e( 'File', 'premise' ); ?></th>
	<th scope="col" id="description" class="manage-column column-description"><?php _e( 'URI', 'premise' ); ?></th>
	</tr>
	</thead>

	<tfoot>
	<tr>
	<th scope="col" class="manage-column column-name"><?php _e( 'Name', 'premise' ); ?></th>
	<th scope="col" class="manage-column column-slug"><?php _e( 'File', 'premise' ); ?></th>
	<th scope="col" class="manage-column column-description"><?php _e( 'URI', 'premise' ); ?></th>
	</tr>
	</tfoot>

	<tbody id="the-list" class="list:tag">

		<?php
		$alt = true;
		if ( $links ) :
		foreach ( (array) $links as $id => $info ) :
		?>
		<tr <?php if ( $alt ) { echo 'class="alternate"'; $alt = false; } else { $alt = true; } ?>>
			<td class="name column-name">
				<?php printf( '<a class="row-title" href="%s" title="Edit %s">%s</a>', esc_url( add_query_arg( array( 'view' => 'edit', 'id' => $id ), $menu_page_url ) ), esc_html( $info['name'] ), esc_html( $info['name'] ) ); ?>

				<br />
				<div class="row-actions">
					<span class="edit"><a href="<?php echo esc_url( add_query_arg( array( 'view' => 'edit', 'id' => $id ), $menu_page_url ) ); ?>"><?php _e( 'Edit', 'premise' ); ?></a> | </span>
					<span class="delete"><a class="delete-tag" href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'action' => 'delete', 'id' => $id ), $menu_page_url ), 'accesspress-action-delete-link' ) ); ?>"><?php _e( 'Delete', 'premise' ); ?></a></span>
				</div>

			</td>

			<td class="slug column-slug"><?php echo esc_html( $info['filename'] ); ?></td>
			<td class="description column-description"><?php echo esc_url( home_url( sprintf( '/?download_id=%s', $id ) ) ); ?></td>
		</tr>

		<?php endforeach; endif; ?>

	</tbody>
</table>

</div>
</div><!-- /col-right -->

<div id="col-left">
<div class="col-wrap">


<div class="form-wrap">
<h3><?php _e( 'Add New Link', 'premise' ); ?></h3>

<form method="post" action="<?php echo esc_url( add_query_arg( array( 'action' => 'create' ), $menu_page_url ) ); ?>">
<?php wp_nonce_field( 'accesspress-action-create-link' ); ?>

<div class="form-field form-required">
	<label for="link-name"><?php _e( 'Name', 'premise' ); ?></label>
	<input name="create_link[name]" id="link-name" type="text" value="" size="40" aria-required="true" />
	<p><?php _e( 'A recognizable name for your new link.', 'premise'); ?></p>
</div>

<div class="form-field">
	<label for="link-filename"><?php _e( 'Filename', 'premise' ); ?></label>
	<input name="create_link[filename]" id="link-filename" type="text" value="" size="40" />
	<p><?php _e( 'Type the filename (file must exist) you wish to link to.', 'premise' ); ?></p>
</div>

<div class="form-field">
	<label for="link-delay"><?php _e( 'Delay Access', 'premise' ); ?></label>
	<input name="create_link[id]" id="link-delay" type="text" value="0" size="5" style="width: auto;" /> <?php _e( 'Days', 'premise' ); ?>
	<p><?php _e( 'Delay access to this file by X days after signup.', 'premise' ); ?></p>
</div>

<div class="form-field">
	<label><?php _e( 'Access Level(s)', 'premise' ); ?></label>
	<p><?php _e( 'Choose the access level(s) the logged in member must have in order to access this file.', 'premise' ); ?></p>
	<p>
		<?php
		echo accesspress_get_access_level_checklist( array( 'name' => 'create_link[access-levels][]', 'style' => 'style="width: auto;"' ) );
		?>
	</p>
</div>

<p class="submit"><?php submit_button( __( 'Add New Link', 'premise' ), 'secondary', 'submit', false ); ?></p>
</form></div>

</div>
</div><!-- /col-left -->

</div><!-- /col-container -->