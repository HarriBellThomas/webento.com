<?php
$main = $settings['main'];
$seo = $settings['seo'];
$optin = $settings['optin'];
$tracking = $settings['tracking'];
$sharing = $settings['sharing'];

wp_nonce_field('save-premise-settings', 'save-premise-settings-nonce');

?>

<h3><?php 
	_e('General', 'premise' );
	submit_button( __( 'Save Changes', 'premise' ), 'button-primary premise-h3-button', 'save-premise-settings', false );
?></h3>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="premise-main-api-key"><?php _e('API Key', 'premise' ); ?></label></th>
			<td>
				<input class="regular-text" type="text" name="premise[main][api-key]" id="premise-main-api-key" value="<?php echo esc_attr($main['api-key']); ?>" />
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Landing Page URLs', 'premise' ); ?></th>
			<td>
				<label for="premise-main-rewrite-root">
					<input <?php checked(1, $main['rewrite-root']); ?> type="checkbox" name="premise[main][rewrite-root]" id="premise-main-rewrite-root" value="1" />
					<?php printf(__('Landing pages should have URLs off the site root (like <code>%slanding-page-slug/</code>)', 'premise' ), home_url('/')); ?>
				</label>
			</td>
		</tr>
		<tr id="premise-main-rewrite-container">
			<th scope="row"><label for="premise-main-rewrite"><?php _e('Rewrite Structure', 'premise' ); ?></label></th>
			<td>
				<code><?php echo esc_html( site_url( '/' ) ); ?></code><input type="text" class="code" name="premise[main][rewrite]" id="premise-main-rewrite" value="<?php echo esc_attr($main['rewrite']); ?>" /><code>/landing-page-title/</code>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Membership', 'premise' ); ?></th>
			<td>
<?php if ( $this->has_valid_premise_api_key() ) { ?>
				<label for="premise-member-access">
					<input <?php checked( 1, $main['member-access'] ); ?> type="checkbox" name="premise[main][member-access]" id="premise-main-member-access" value="1" />
					<?php _e( 'Enable the membership module', 'premise' ); ?>
				</label>
<?php } else { ?>
				<?php _e( 'Add your API key above to enable the membership module', 'premise' ); ?>
<?php } ?>
			</td>
		</tr>
	</tbody>
</table>

<h3><?php _e('Content', 'premise' ); ?></h3>
<p><?php _e('The content settings below are defaults and may be overridden per landing page.', 'premise' ); ?></p>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="premise-main-default-favicon"><?php _e('Default Favicon', 'premise' ); ?></label></th>
			<td>
				<input class="regular-text" type="text" name="premise[main][default-favicon]" id="premise-main-default-favicon" value="<?php echo esc_attr($main['default-favicon']); ?>" /> <a class="thickbox" href="<?php echo esc_attr(add_query_arg(array('send_to_premise_field_id'=>'premise-main-default-favicon', 'TB_iframe' => 1, 'width' => 640, 'height' => 459), add_query_arg('TB_iframe', null, get_upload_iframe_src('image')))); ?>"><?php _e('Upload', 'premise' ); ?></a><br />
				<small><?php _e('Enter the URL to a favicon you would like to use for your landing pages.  You can override this per landing page, but the value here will be used by default.', 'premise' ); ?></small>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="premise-main-default-header-image"><?php _e('Default Header Image', 'premise' ); ?></label></th>
			<td>
				<input class="regular-text" type="text" name="premise[main][default-header-image]" id="premise-main-default-header-image" value="<?php echo esc_attr($main['default-header-image']); ?>" /> <a class="thickbox" href="<?php echo esc_attr(add_query_arg(array('send_to_premise_field_id'=>'premise-main-default-header-image', 'TB_iframe' => 1, 'width' => 640, 'height' => 459), add_query_arg('TB_iframe', null, get_upload_iframe_src('image')))); ?>"><?php _e('Upload', 'premise' ); ?></a><br />
				<small><?php _e('Enter the URL to an image that you wish to use in the header of your landing pages by default.', 'premise' )?></small>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="premise-main-default-header-image-url"><?php _e('Default Header Image Link', 'premise' ); ?></label></th>
			<td>
				<input class="regular-text" type="text" name="premise[main][default-header-image-url]" id="premise-main-default-header-image-url" value="<?php echo esc_attr($main['default-header-image-url']); ?>" /><br />
				<small><?php _e('(optional) Enter the URL you would like the header image to link to.', 'premise' )?></small>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="premise-main-default-footer-text"><?php _e('Default Footer Text', 'premise' ); ?></label></th>
			<td>
				<input class="regular-text" type="text" name="premise[main][default-footer-text]" id="premise-main-default-footer-text" value="<?php echo esc_attr($main['default-footer-text']); ?>" />
			</td>
		</tr>
	</tbody>
</table>

<h3><?php _e('SEO', 'premise' ); ?></h3>
<?php if ( defined( 'WPSEO_VERSION' ) ) { ?>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><?php _e('SEO Tool', 'premise' ); ?></th>
			<td>
				<ul>
					<li>
						<label>
							<input <?php checked(1, $seo['indicator']); ?> type="checkbox" name="premise[seo][indicator]" id="premise-seo-indicator" value="1" />
							<?php _e('Use Premise SEO', 'premise' ); ?>
						</label>
					</li>
				</ul>
			</td>
		</tr>
	</tbody>
</table>
<?php } ?>
<p><?php _e('The SEO settings below are defaults and will be overridden per landing page.', 'premise' ); ?></p>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><?php _e('Robots Meta Settings', 'premise' ); ?></th>
			<td>
				<ul>
					<li>
						<label>
							<input <?php checked(1, $seo['noindex']); ?> type="checkbox" name="premise[seo][noindex]" id="premise-seo-noindex" value="1" />
							<?php _e('Apply <code>noindex</code> to page', 'premise' ); ?>
						</label>
						<a href="#" class="premise-link-tip"><?php //_e('', 'premise' ); ?></a>
					</li>
					<li>
						<label>
							<input <?php checked(1, $seo['nofollow']); ?> type="checkbox" name="premise[seo][nofollow]" id="premise-seo-nofollow" value="1" />
							<?php _e('Apply <code>nofollow</code> to page', 'premise' ); ?>
						</label>
						<a href="#" class="premise-link-tip"><?php //_e('', 'premise' ); ?></a>
					</li>
					<li>
						<label>
							<input <?php checked(1, $seo['noarchive']); ?> type="checkbox" name="premise[seo][noarchive]" id="premise-seo-noarchive" value="1" />
							<?php _e('Apply <code>noarchive</code> to page', 'premise' ); ?>
						</label>
						<a href="#" class="premise-link-tip"><?php //_e('', 'premise' ); ?></a>
					</li>
				</ul>
				<small><?php _e( 'You can add these tags to tell robots not to index the content of a page, not scan it for links to follow, and/or remove your pages from the Google Cache.', 'premise' ); ?></small>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Feed Autodetect', 'premise' ); ?></th>
			<td>
				<ul>
					<li>
						<label>
							<input <?php checked(1, $seo['disable-feed']); ?> type="checkbox" name="premise[seo][disable-feed]" id="premise-seo-disable-feed" value="1" />
							<?php _e( 'Turn off Feed Autodetect for this page?', 'premise' ); ?>
						</label>
					</li>
				</ul>
				<small><?php _e( 'On by default &mdash; checking the box turns it off.  You may not want your feed autodetect to show up on a landing page, as it may deter from your call to action.  You can turn off the feed autodiscovery only for this page and not the rest of your site.', 'premise' ); ?></small>
			</td>
		</tr>
	</tbody>
</table>

<h3><?php _e( 'Email Providers', 'premise' ); ?></h3>
<p><?php _e( 'Premise integrates with several opt in providers, including Aweber, Constant Contact, and MailChimp.  In order to activate these services, please enter your information below.', 'premise' ); ?></p>

<h4><?php _e( 'AWeber', 'premise' ); ?></h4>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="premise-optin-aweber-authorization]"><?php _e( 'AWeber Authorization Code', 'premise' ); ?></label></th>
			<td>
				<input type="text" class="code large-text" name="premise[optin][aweber-authorization]" id="premise-optin-aweber-authorization" value="<?php echo esc_attr($optin['aweber-authorization']); ?>" /><br />
				<a href="<?php echo $this->_optin_AweberAuthenticationUrl . $this->_optin_AweberApplicationId; ?>" target="_blank"><?php _e('Click here to get your authorization code.', 'premise' ); ?></a>
			</td>
		</tr>
	</tbody>
</table>

<h4><?php _e('Constant Contact', 'premise' ); ?></h4>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="premise-optin-constant-contact-username"><?php _e('Constant Contact Username', 'premise' ); ?></label></th>
			<td>
				<input type="text" class="code regular-text" name="premise[optin][constant-contact-username]" id="premise-optin-constant-contact-username" value="<?php echo esc_attr($optin['constant-contact-username']); ?>" /><br />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="premise-optin-constant-contact-password"><?php _e('Constant Contact Password', 'premise' ); ?></label></th>
			<td>
				<input type="text" class="code regular-text" name="premise[optin][constant-contact-password]" id="premise-optin-constant-contact-password" value="<?php echo esc_attr($optin['constant-contact-password']); ?>" /><br />
			</td>
		</tr>
	</tbody>
</table>

<h4><?php _e('MailChimp', 'premise' ); ?></h4>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="premise-optin-mailchimp-api"><?php _e('MailChimp API Key', 'premise' ); ?></label></th>
			<td>
				<input type="text" class="code large-text" name="premise[optin][mailchimp-api]" id="premise-optin-mailchimp-api" value="<?php echo esc_attr($optin['mailchimp-api']); ?>" /><br />
				<a href="http://admin.mailchimp.com/account/api-key-popup" target="_blank"><?php _e('Get your API key.', 'premise' ); ?></a>
			</td>
		</tr>
	</tbody>
</table>

<h3 id="premise-sharing"><?php _e('Sharing', 'premise' ); ?></h3>
<p><?php _e('Premise contains a landing page type that makes your visitors share your content before they can access the full page. You can pick from simple or enhanced sharing types below.', 'premise' ); ?></p>

<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row"><?php _e('Sharing Type', 'premise' ); ?></th>
			<td>
				<ul>
					<li>
						<label>
							<input <?php checked(empty($sharing['type']), true); ?> type="radio" name="premise[sharing][type]" id="premise-sharing-type-simple" value="0" />
							<?php _e('Simple - Sharing based on the "honor system." No technical knowledge required, but does not ensure the share is completed and provides no tracking of page shares.', 'premise' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input <?php checked($sharing['type'], 1); ?> type="radio" name="premise[sharing][type]" id="premise-sharing-type-enhanced" value="1" />
							<?php _e('Enhanced', 'premise' ); ?> - 
							<?php printf( __( 'Only select this if you feel comfortable registering Twitter (<a href="%s" target="_blank">instructions</a>) and Facebook (<a href="%s" target="_blank">instructions</a>) applications.', 'premise' ), 'https://members.getpremise.com/help-social-share-twitter.aspx', 'https://members.getpremise.com/help-social-share-facebook.aspx' ); ?>
						</label>
					</li>
				</ul>
			</td>
		</tr>
		<tr valign="top" class="premise-sharing-type-enhanced-dependent">
			<th scope="row"><label for="premise-sharing-twitter-consumer-key"><?php _e('Twitter Consumer Key', 'premise' ); ?></label></th>
			<td>
				<input type="text" class="large-text" name="premise[sharing][twitter-consumer-key]" id="premise-sharing-twitter-consumer-key" value="<?php echo esc_attr($sharing['twitter-consumer-key']); ?>" />
			</td>
		</tr>
		<tr valign="top" class="premise-sharing-type-enhanced-dependent">
			<th scope="row"><label for="premise-sharing-twitter-consumer-secret"><?php _e('Twitter Consumer Secret', 'premise' ); ?></label></th>
			<td>
				<input type="text" class="large-text" name="premise[sharing][twitter-consumer-secret]" id="premise-sharing-twitter-consumer-secret" value="<?php echo esc_attr($sharing['twitter-consumer-secret']); ?>" />
			</td>
		</tr>
		<tr valign="top" class="premise-sharing-type-enhanced-dependent">
			<th scope="row"><label for="premise-sharing-facebook-app-id"><?php _e('Facebook App ID', 'premise' ); ?></label></th>
			<td>
				<input type="text" class="large-text" name="premise[sharing][facebook-app-id]" id="premise-sharing-facebook-app-id" value="<?php echo esc_attr($sharing['facebook-app-id']); ?>" />
			</td>
		</tr>
		<tr valign="top" class="premise-sharing-type-enhanced-dependent">
			<th scope="row"><label for="premise-sharing-facebook-app-secret"><?php _e('Facebook App Secret', 'premise' ); ?></label></th>
			<td>
				<input type="text" class="large-text" name="premise[sharing][facebook-app-secret]" id="premise-sharing-facebook-app-secret" value="<?php echo esc_attr($sharing['facebook-app-secret']); ?>" />
			</td>
		</tr>
	</tbody>
</table>

<h3 id="premise-tracking"><?php _e('Testing', 'premise' ); ?></h3>
<p><?php _e( 'Premise provides support for Google Website Optimizer or Visual Website Optimizer.  In order to use this functionality, you must configure your account IDs below.', 'premise' ); ?></p>


<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="premise-tracking-account-id"><?php _e('Google Website Optimizer Account ID', 'premise' ); ?></label></th>
			<td>
				<input class="regular-text" type="text" name="premise[tracking][account-id]" id="premise-tracking-account-id" value="<?php echo esc_attr($tracking['account-id']); ?>" /> <br />
				<?php _e( 'Please enter the entire account ID, including the UA string.  If you do not know your account ID, please just paste your entire control and tracking script and Premise will extract it.', 'premise' ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="premise-tracking-vwo-account-id"><?php _e('Visual Website Optimizer Account ID', 'premise' ); ?></label></th>
			<td>
				<input class="regular-text" type="text" name="premise[tracking][vwo-account-id]" id="premise-tracking-vwo-account-id" value="<?php echo esc_attr($tracking['vwo-account-id']); ?>" />
			</td>
		</tr>
	</tbody>
</table>

<h3 id="premise-scripts"><?php _e('Scripts', 'premise' ); ?></h3>
<p><?php _e('Premise allows you to add content to either the header or footer.  Insert some code into the textareas below to make it appear on all Premise landing pages.', 'premise' ); ?></p>

<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="premise-scripts-header"><?php _e('Header Scripts', 'premise' ); ?></label></th>
			<td>
				<textarea rows="6" class="large-text code" name="premise[scripts][header]" id="premise-scripts-header"><?php echo esc_html($settings['scripts']['header']); ?></textarea>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="premise-scripts-footer"><?php _e('Footer Scripts', 'premise' ); ?></label></th>
			<td>
				<textarea rows="6" class="large-text code" name="premise[scripts][footer]" id="premise-scripts-footer"><?php echo esc_html($settings['scripts']['footer']); ?></textarea>
			</td>
		</tr>
	</tbody>
</table>

<div class="bottom-buttons">
	<?php submit_button( __( 'Save Changes', 'premise' ), 'primary', 'save-premise-settings', false ); ?>
</div>
