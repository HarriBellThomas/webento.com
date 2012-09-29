<?php
$settings = $this->getSettings();
/**
 * @var EcordiaUserAccount
 */
$userInfo = $this->getUserInfo(true);
?>
<div class="wrap">
	<?php screen_icon(); ?><h2><?php _e( 'Scribe Settings' ); ?></h2>
	<form method="post" action="<?php echo esc_url( admin_url( 'options-general.php?page=scribe' ) ); ?>">
		<p><?php _e( 'Use these settings to activate, upgrade, and configure your Scribe Content Optimizer plugin.' ); ?></p>
		<table class="form-table">
			<tbody>
				<?php if( is_wp_error( $userInfo ) && $userInfo->get_error_code() == 'ApiKeyInvalid' ) { ?>
				<tr>
					<td colspan="2">
						<strong class="ecordia-error"><?php _e( 'Your API Key is invalid.' ); ?></strong>
						<?php printf( __( 'Re-enter your API Key or <a target="_blank" href="%s">log into your account to retrieve your API Key</a>.' ), 'https://my.scribeseo.com' ); ?>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<th scope="row"><label for="current-seo-tool"><?php _e( 'Current SEO Tool' ); ?></label></th>
					<td>
						<?php
						$automatic = $this->getAutomaticDependency();
						$currently = $this->getAutomaticDependencyNiceName($automatic);
						?>
						<label>
							<input <?php checked(empty($settings['seo-tool-method']),true); ?> type="radio" name="ecordia-seo-tool-method" id="ecordia-seo-tool-method-basic" value="" />
							<?php printf(__('Allow the Scribe plugin to choose a supported tool based on your configuration (currently <strong>%s</strong>)'), $currently); ?>
						</label><br />
						<label>
							<input <?php checked($settings['seo-tool-method'],'1'); ?> type="radio" name="ecordia-seo-tool-method" id="ecordia-seo-tool-method-advanced" value="1" />
							<?php printf(__('Choose the SEO tool you wish to use.  There is no validation done on this selection, so please only choose an option you know is currently active and can be used immedatiately.')); ?>
						</label><br />

						<p id="ecordia-seo-tool-chooser-container">
							<select id="ecordia-seo-tool-chooser" name="ecordia-seo-tool-chooser">
							<?php
							$possible = $this->getPossibleDependencies();
							$currenttool = serialize($settings['seo-tool']);
							foreach($possible as $type => $items) {
								?>
								<optgroup label="<?php echo ucwords($type); ?>">
									<?php
									foreach($items as $item) {
										$serialized = serialize($item);
										?>
										<option <?php selected($currenttool,$serialized); ?> value="<?php echo esc_attr($serialized); ?>"><?php esc_html_e($item['name']); ?></option>
										<?php
									}
									?>
								</optgroup>
								<?php
							}
							?>
							</select>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="ecordia-api-key"><?php _e( 'Scribe API Key' ); ?></label></th>
					<td>
						<input type="text" class="regular-text" name="ecordia-api-key" id="ecordia-api-key" value="<?php echo esc_attr( $settings[ 'api-key' ] ); ?>" />
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="ecordia-connection-method"><?php _e( 'Security Method' ); ?></label></th>
					<td>
						<select name="ecordia-connection-method" id="ecordia-connection-method">
							<option <?php selected( false, $settings[ 'use-ssl' ] ); ?> value="http">Basic Non-SSL</option>
							<option <?php selected( true, $settings[ 'use-ssl' ] ); ?> value="https">Enhanced SSL</option>
						</select>
						<?php
						$faqUrl = 'https://my.scribeseo.com';
						?>
						<div id="ecordia-https-warning" <?php if( !$settings['use-ssl'] ) { echo 'style="display:none;"'; } ?>><p><?php printf( __( 'Use of <strong>Enhanced Security (with SSL)</strong> can cause problems on some shared hosts.  Only select this option if you are certain you need it.  See <a href="%s">the Suppport Site</a> for more information.' ), $faqUrl ); ?></p></div>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="ecordia-permissions-level"><?php _e('Permissions'); ?></label></th>
					<td>
						<p>
						<?php _e('Only allow access to the scribe content optimizer to users whose role is ' ); ?>
						<select id="ecordia-permissions-level" name="ecordia-permissions-level">
							<option <?php selected('manage_options',$settings['permissions-level']); ?> value="manage_options"><?php _e('Administrator'); ?></option>
							<option <?php selected('delete_others_posts',$settings['permissions-level']); ?> value="delete_others_posts"><?php _e('Editor'); ?></option>
							<option <?php selected('delete_published_posts',$settings['permissions-level']); ?> value="delete_published_posts"><?php _e('Author'); ?></option>
							<option <?php selected('edit_posts',$settings['permissions-level']); ?> value="edit_posts"><?php _e('Contributor'); ?></option>
						</select>
						<?php _e(' or higher.'); ?>
						</p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="ecordia-post-types"><?php _e('Post Types'); ?></label></th>
					<td>
						<p>
						<?php
						_e('Add Scribe SEO support for each of the following post types:');
						$registeredTypes = $this->getPostTypes();
						$supported = $this->getSupportedPostTypes();
						?>
						</p>
						<ul>
							<?php foreach($registeredTypes as $key => $type) { ?>
							<li>
								<label>
									<input <?php checked(in_array($key, $supported), true); ?> type="checkbox" name="ecordia-post-types[]" value="<?php esc_attr_e($key); ?>" />
									<?php esc_html_e($type->labels->name); ?>
								</label>
							</li>
							<?php } ?>
						</ul>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<?php wp_nonce_field( 'save-ecordia-api-key-information' ); ?>
			<input type="submit" class="button-primary" name="save-ecordia-api-key-information" id="save-ecordia-api-key-information" value="<?php _e( 'Save' ); ?>" />
		</p>
	</form>

	<h3><?php _e( 'Account Information' ); ?></h3>
	<div id="ecordia-account-information">
		<?php include( dirname( __FILE__ ) . '/account-info.php' ); ?>
	</div>

	<form action="https://my.scribeseo.com/login.aspx" method="post">
		<h3><?php _e( 'Account Login &amp; Support' ); ?></h3>
		<p><?php _e( 'Our online account center will help you manage your account and billing information.' ); ?></p>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for=""><?php _e( 'Email' ); ?></label></th>
					<td>
						<input type="text" class="regular-text" name="txtEmail" id="txtEmail" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for=""><?php _e( 'Password' ); ?></label></th>
					<td>
						<input type="password" class="regular-text" name="txtPassword" id="txtPassword" /> <a href="https://my.scribeseo.com/forgot-password.aspx/"><?php _e( 'Forgot Password?' ); ?></a>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" id="__EVENTTARGET" name="__EVENTTARGET" value="btnLogin" />
		<input type="hidden" id="__EVENTARGUMENT" name="__EVENTARGUMENT" value="" />
		<p class="submit">
			<input type="submit" name="account-history-login" id="account-history-login" value="<?php _e( 'Login to Account' ); ?>" />
		</p>
	</form>
</div>
