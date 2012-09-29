<?php
        /* Get settings */
        $network_settings = $this->get_options( 'track_settings', 'network' );
?>

        <div class="wrap">
            <h2><?php _e( 'Google Analytics', $this->text_domain ) ?></h2>

            <?php
                //Display status message
                if ( isset( $_GET['dmsg'] ) ) {
                    ?><div id="message" class="updated fade"><p><?php echo urldecode( $_GET['dmsg'] ); ?></p></div><?php
                }
            ?>

            <form method="post" action="">
                <table class="form-table">

					<tr valign="top">
						<th scope="row"><?php _e( 'Network-wide Tracking Code', $this->text_domain ); ?></th>
						<td>
							<input type="text" name="tracking_code" class="regular-text" value="<?php if ( !empty( $network_settings['tracking_code'] ) ) { echo $network_settings['tracking_code']; } ?>" />
							<br />
							<span class="description"><?php _e( 'Your Google Analytics tracking code. Ex: UA-XXXXX-X. The Network-wide tracking code will track your entire network of sub-sites.', $this->text_domain ); ?></span>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Admin pages tracking', $this->text_domain ); ?></th>
						<td>
							<input type="radio" name="track_admin" value="1" <?php if ( !empty( $network_settings['track_admin'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Enable', $this->text_domain ); ?>
							<br />
							<input type="radio" name="track_admin" value="0" <?php if ( empty( $network_settings['track_admin'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Disable', $this->text_domain ); ?>
							<br />
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Subdomain tracking', $this->text_domain ); ?></th>
						<td>
							<input type="radio" name="track_subdomains" value="1" <?php if ( !empty( $network_settings['track_subdomains'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Enable', $this->text_domain ) ?>
							<br />
							<input type="radio" name="track_subdomains" value="0" <?php if ( empty( $network_settings['track_subdomains'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Disable', $this->text_domain ) ?>
							<br />
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Page load times tracking', $this->text_domain ); ?></th>
						<td>
							<input type="radio" name="track_pageload" value="1" <?php if ( !empty( $network_settings['track_pageload'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Enable', $this->text_domain ) ?>
							<br />
							<input type="radio" name="track_pageload" value="0" <?php if ( empty( $network_settings['track_pageload'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Disable', $this->text_domain ) ?>
							<br />
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e( 'Domain Mapping', $this->text_domain ); ?></th>
						<td>
							<input type="radio" name="domain_mapping" value="1" <?php if ( !empty( $network_settings['domain_mapping'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Enable', $this->text_domain ) ?>
							<br />
							<input type="radio" name="domain_mapping" value="0" <?php if ( empty( $network_settings['domain_mapping'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Disable', $this->text_domain ) ?>
							<br />
						</td>
					</tr>

					<?php if ( function_exists('is_supporter') ):  ?>
						<tr valign="top">
							<th scope="row">
								<?php _e( 'Google Analytics Supporter', $this->text_domain ); ?>
							</th>
							<td>
								<select name="supporter_only">
									<option value="1" <?php if ( !empty( $network_settings['supporter_only'] ) ) echo 'selected="selected"'; ?>><?php _e( 'Enable', $this->text_domain ); ?></option>
									<option value="0" <?php if ( empty( $network_settings['supporter_only'] ) ) echo 'selected="selected"'; ?>><?php _e( 'Disable', $this->text_domain ); ?></option>
								</select>
								<br />
								<?php _e( 'Enable Google Analytics for supporter blogs only.', $this->text_domain ); ?>
							</td>
						</tr>
					<?php endif; ?>

                </table>

                <p class="submit">
                    <?php wp_nonce_field('submit_settings_network'); ?>
                    <input type="hidden" name="key" value="track_settings" />
                    <input type="submit" name="submit" value="<?php _e( 'Save Changes', $this->text_domain ); ?>" />
                </p>

            </form>

        </div>