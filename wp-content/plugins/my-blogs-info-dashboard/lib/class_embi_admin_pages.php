<?php
/**
 * Admin pages handler.
 */
class Embi_AdminPages {
	const USERS_LIMIT = 6; // Limit +1
	
	private $_sections 			= array();
	private $_sections_default	= array();
	
	private $_options_key_user 	= "embi_right_now";
	private $_options_key_site 	= "embi_right_now";
	
	private function __construct () {
	}

	public static function serve () {

		$me = new Embi_AdminPages;
		
		/* We build an array of all the section complete with callback functions */
		$me->_sections_default = array();
			$me->_sections_default['my-account']	=	array(
				'active'	=>	true,
				'title'		=>	__('My Account', 'embi'),
				'callback'	=>	array($me, 'embi_my_account_widget_section')
			);

			$me->_sections_default['this-blog']		=	array(
				'active'	=>	true,
				'title'		=>	__('This Blog', 'embi'),
				'callback'	=>	array($me, 'embi_this_blog_widget_section')
			);
			
			$me->_sections_default['my-blogs']		=	array(
				'active'	=>	true,
				'title'		=>	__('My Blogs', 'embi'),
				'callback'	=>	array($me, 'embi_my_blogs_widget_section')
			);
			
			$me->_sections_default['storage-space']	=	array(
				'active'	=>	true,
				'title'		=>	__('Storage Space', 'embi'),
				'callback'	=>	array($me, 'embi_storage_section')
			);

			$me->_sections_default['pro-sites']	=	array(
				'active'	=>	true,
				'title'		=>	__('Pro Sites', 'embi'),
				'callback'	=>	array($me, 'embi_prosites_section')
			);
		
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('wp_dashboard_setup', array($this, 'add_dashboard_widgets'), 99);

		add_action('myblogs_allblogs_options', array($this, 'show_filter_links'));
		add_filter('myblogs_options', array($this, 'show_additional_blog_info'), 9, 2);
		
		add_action('admin_print_scripts', array($this, 'js_print_scripts'));
		add_action('admin_print_styles', array($this, 'css_print_styles'));
		
		add_action('wp_ajax_update_editable_field_value', array($this, 'json_update_editable_field_value'));
	}

	function embi_load_sections() {
		$this->_sections = $this->_sections_default;	
	}
	
	function add_dashboard_widgets () {
						
		if ((isset($_POST)) && isset($_POST['widget_id']) && ($_POST['widget_id'] == "embi_dashboard_right_now_widget")) {
			$this->embi_save_user_sections();
		}
		
		//wp_add_dashboard_widget('embi_dashboard_this_blog_widget', __('Right Now', 'embi'), array($this, 'dashboard_this_blog_widget'));
		//wp_add_dashboard_widget('embi_dashboard_my_account_widget', __('My Account', 'embi'), array($this, 'dashboard_my_account_widget'));
		if (current_user_can( 'edit_dashboard' )) {
			wp_add_dashboard_widget('embi_dashboard_right_now_widget', __('Right Now', 'embi'), 
				array($this, 'dashboard_right_now_widget'), array($this, 'dashboard_right_now_widget_controls'));

			// Kill Right Now widget
			//global $wp_meta_boxes;
			//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		}
	}
	
/* ----- Dashboard widgets ----- */
	
	function dashboard_right_now_widget() {

		$this->embi_load_sections();

		$user_right_now = get_user_meta(get_current_user_id(), $this->_options_key_user, true);
		if (isset($user_right_now['sections'])) {
			$sections_user = $user_right_now['sections'];
		} else {
			$sections_user = $this->_sections_default;
		}

		foreach($sections_user as $section_key => $section_selected) {
			if ($section_selected != true) 
				continue;
			
			if ((isset($this->_sections[$section_key])) && ($this->_sections[$section_key]['active'] == true)) {
				
				$section = 	$this->_sections[$section_key];
				if (isset($section['callback'])) {

					if (isset($section['title'])) 
						$title = $section['title'];
					else
						$title = '';
					call_user_func($section['callback'], $title);
				}

			}
		}		
	}


	function dashboard_right_now_widget_controls() {
		
		$this->embi_load_sections();
		?>		
		<div class="embi-configure">
			<p><?php _e("From the list below reorder the section to your preference. You can also hide sections by unsetting the 'Visible' checkbox. Changes made to the visibility and order are global to all your sites.", 'embi'); ?></p>
			<ul id="embi-configure-sortable">
			<?php
				$user_right_now = get_user_meta(get_current_user_id(), $this->_options_key_user, true);
				if (isset($user_right_now['sections'])) {
					$sections_user = $user_right_now['sections'];
				} else {
					$sections_user = array();
				}
				
				$sections_diff = array_diff(array_keys($this->_sections), array_keys($sections_user));
				if (($sections_diff) && (count($sections_diff))) {
					foreach($sections_diff as $section) {
						if (isset($this->_sections[$section])) {
							$sections_user[$section] = false;
						}						
					}
				}
					
				foreach($sections_user as $section_key => $section_selected) {
					if (!isset($this->_sections[$section_key]))
						continue;
					
					if ($this->_sections[$section_key]['active'] != true)
						continue;
					
					if ((isset($this->_sections[$section_key])) && ($this->_sections[$section_key]['active'] == true)) {
						$section = 	$this->_sections[$section_key];
					
						if (isset($section['title'])) 
							$title = $section['title'];
						else
							$title = '';


						if ($section_selected == true) {
							$visible_checked = ' checked="checked" ';
						} else {
							$visible_checked = '';
						}
						
						if ($this->_sections[$section_key]['active'] == true) {
							$active_checked = ' checked="checked" ';
						} else {
							$active_checked = '';							
						}
						?>
						<li class="ui-state-default">
							<span class="embi-section-title"><?php echo $title ?></span>
							<?php /* if (is_super_admin()) { ?>
								<span class="embi-section-active embi-section-sub">
									<input type="checkbox" <?php echo $active_checked; ?> 
										name="embi-section[active][<?php echo $section_key; ?>]" /> <?php _e('Active', 'embi'); ?>
								</span>
							<?php } */ ?>
							<span class="embi-section-visible embi-section-sub">
								<input type="checkbox" <?php echo $visible_checked; ?> 
									name="embi-section[sort][<?php echo $section_key; ?>]" /> <?php _e('Visible', 'embi'); ?>
							</span>
						</li>
						<?php
					}
				}
			
			?>
			</ul>
			<input type="hidden" name="embi-section-sort" id="embi-section-sort" value="" />
		</div>
		<script>
		jQuery(function() {
			jQuery( 'ul#embi-configure-sortable' ).sortable();
			jQuery( 'ul#embi-configure-sortable' ).disableSelection();

			/* We capture the configure form submit. We want to store the sort order or items and store into a hidden form field to save as user meta */
			jQuery('form.dashboard-widget-control-form').submit(function() {

				var embi_section_sort = '';
				jQuery('#embi-configure-sortable input').each(function() {
					var item_name = jQuery(this).attr('name');
					if ((item_name.length) && (item_name.substring(0, 18) == "embi-section[sort]")) {
						if (embi_section_sort.length) {
							embi_section_sort = embi_section_sort+",";
						}
						embi_section_sort = embi_section_sort+item_name;
					}
				});

				if (embi_section_sort.length) {
					jQuery('form.dashboard-widget-control-form input#embi-section-sort').val(embi_section_sort);
				}
			});
		});
		</script>
		
		<?php
	}	
	
	function embi_save_user_sections() {

		$user_data 	= array();
		$tmp_true 	= array();
		$tmp_false 	= array();
		
		if (isset($_POST['embi-section-sort'])) {
			$section_sort = explode(',', $_POST['embi-section-sort']);
			
			if ((is_array($section_sort)) && (count($section_sort))) {
				foreach($section_sort as $section) {

					$section = str_replace('embi-section[sort][', '', $section);
					$section = str_replace(']', '', $section);
					if ($section) {
						if ((isset($_POST['embi-section']['sort'][$section])) && ($_POST['embi-section']['sort'][$section] == "on")) {
							$tmp_true[$section] = true;
						} else {
							$tmp_false[$section] = false;							
						}
					}
				}
			}
		}

		// We setup the $tmp_true and $tmp_false arrays to merge them later. This will let the TRUE items 
		// bubble to the top and the FALSE items sink to the bottom.
		$user_data['sections'] = array_merge($tmp_true, $tmp_false);
		update_user_meta(get_current_user_id(), $this->_options_key_user, $user_data);
	}

	function  embi_my_account_widget_section($title="") {

		global $current_user;
		
		?>
		<div class="embi-rightnow-wrapper embi-rightnow-account-section">
			<div class="embi_dashboard">
				<h4 class="embi-section-title"><?php echo $title; ?> <a style="float: right" title="<?php _e('Manage your profile', 'embi'); ?>"
						href="<?php echo admin_url('profile.php'); ?>"><?php _e('Your profile', 'embi'); ?></a>
				</h4>
				<div class="embi-my_account-avatar">
					<?php
						if (function_exists('get_blog_avatar')) { 
							?><a title="<?php _e('Change Avatar', 'embi'); ?>" href="<?php echo admin_url('profile.php?page=user-avatar'); ?>"><?php 
						}
						echo get_avatar($current_user->ID);	
						
						if (function_exists('get_blog_avatar')) { 
							?></a><?php
						}
					?>
				</div>
				<div class="embi-my_account-content">

					<div class='embi_dashboard-my_account'>
						<ul>
							<li>
								<div class="embi-item_title"><?php _e('Username', 'embi'); ?></div>
								<div class="embi-item_value"><?php echo $current_user->user_login; ?> <a class="button" title="<?php _e('Change password', 'embi'); ?>" style="float:right" href="<?php echo  admin_url('profile.php#pass1'); ?>"><?php _e('Change password'); ?></a>
								</div>
							</li>
							<li>
								<div class="embi-item_title"><?php _e('Display name', 'embi'); ?></div>
								<div class="embi-item_value">
									<a href="<?php echo admin_url('profile.php#display_name'); ?>" title="<?php _e('Change display name', 'embi'); ?>"><?php echo $current_user->display_name; ?></a>
								</div>
							</li>
							<li>
								<div class="embi-item_title"><?php _e('Email', 'embi'); ?>:</div>
								<div class="embi-item_value">
										<a href="<?php echo admin_url('profile.php#email'); ?>" title="<?php _e('Change', 'embi'); ?>"><?php echo $current_user->user_email; ?></a>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php		
	}
	
	function embi_my_blogs_widget_section($title='') {

		$blogs = get_blogs_of_user(get_current_user_id());

		// This is potentially a choke point
		$total_pending = $pending = $moderated = $total_moderated = 0;
		foreach ($blogs as $blog) {
			switch_to_blog($blog->userblog_id);
			$posts = wp_count_posts('post');
			//echo "posts<pre>"; print_r($posts); echo "</pre>";
			$total_pending += $posts->pending;

			if ($posts->pending) $pending++;

			$comments = wp_count_comments();
			//echo "comments<pre>"; print_r($comments); echo "</pre>";
			$total_moderated += $comments->moderated;
			if ($comments->moderated) $moderated++;
			restore_current_blog();
		}
		?>
		<div class="embi-rightnow-wrapper embi-rightnow-my-blogs-section">
			<div class="embi_dashboard">
				<h4 class="embi-section-title"><?php echo $title; ?><?php /* ?><a 
					style="float:right" title="<?php _e('Manage your Blogs', 'embi'); ?>" href="<?php echo admin_url('my-sites.php'); ?>"><?php _e('Manage blogs', 'embi'); ?></a><?php */ ?></h4>
				<ul>
					<li>
						<div class="embi-item_title"><?php _e('Blogs', 'embi'); ?>:</div>
						<div class="embi-item_value"><?php _e('You are a member of', 'embi'); ?> <?php echo '<a href="'. admin_url('my-sites.php') .'">' . sprintf(__('%s blogs!', 'embi'), count($blogs)). '</a>'; ?><a class="button" style="float:right" href="<?php echo admin_url('my-sites.php'); ?>"><?php _e('Manage Blogs') ?></a></div>
					</li>
					<li>
						<div class="embi-item_title"><?php _e('Posts', 'embi'); ?>:</div>
						<div class="embi-item_value"><?php echo sprintf(__('Number of blogs needing posts reviewed: %d (%d total)', 'embi'), $pending, $total_pending); ?><a class="button" style="float:right" href="<?php echo admin_url('edit-comments.php'); ?>"><?php _e('Manage Posts') ?></a></div>
					</li>
					<li>
						<div class="embi-item_title"><?php _e('Comments', 'embi'); ?>:</div>
						<div class="embi-item_value"><?php echo sprintf(__('Number of blogs with pending comments: %d (%d total)', 'embi'), $moderated, $total_moderated); ?><a class="button" style="float:right" href="<?php echo admin_url('edit-comments.php'); ?>"><?php _e('Manage comments') ?></a></div>
					</li>
				</ul>
			</div>
		</div>
		<?php		
		
	}
	
	function embi_this_blog_widget_section($title='') {

		if ( ! current_user_can( 'manage_options' ) ) return;

		$blog_public = (int)get_option('blog_public');
		$privacy_strings = array(
			-4 	=> 	__('This blog is password protected.', 'embi'),
			-3 	=> 	__('Only administrators of this blog are able to view it.', 'embi'),
			-2 	=> 	__('Only registered users of this blog are able to view it.', 'embi'),
			-1 	=> 	__('Only logged in users are able to view this blog.', 'embi'),
			0 	=> 	__('Block search engines from this blogs, but allow normal visitors to see it.', 'embi'),
			1 	=> 	__('Blog is visible to everyone!', 'embi'),
		);
		$comments = wp_count_comments();

		?>
		<div class="embi-rightnow-wrapper embi-rightnow-myblog-section">
			<div class="embi_dashboard">
				<h4 class="embi-section-title"><?php echo $title; ?></h4>
				<ul>	
					<li>
						<div class="embi-item_title"><?php _e('Title', 'embi'); ?>:</div>
						<div class="embi-item_value"><a 
							href="<?php echo admin_url('options-general.php'); ?>"><?php echo get_bloginfo(); ?></a>
						</div>
					</li>
					<li>
						<div class="embi-item_title"><?php _e('Tagline', 'embi'); ?>:</div>
						<div class="embi-item_value"><a href="<?php echo admin_url('options-general.php'); ?>"><?php 
							echo get_bloginfo('description'); ?></a> 
						</div>
					</li>
					<li>
						<div class="embi-item_title"><?php _e('Theme', 'embi'); ?>:</div>
						<div class="embi-item_value"><a href="<?php echo admin_url('themes.php'); ?>"><?php echo get_current_theme(); ?></a></div>
					</li>
					<li>
						<div class="embi-item_title"><?php _e('Privacy', 'embi'); ?>:</div>
						<div class="embi-item_value"><a href="<?php echo admin_url('options-privacy.php'); ?>"><?php 
							echo $privacy_strings[$blog_public]; ?></a></div>
					</li>
				</ul>
			</div>
		</div>
		<?php
	}
	
	function embi_storage_section($title='') {
		global $wpdb;
		
		if ( ! current_user_can( 'manage_options' ) ) return;
		
		$storage_space = $this->_get_space();
		if (($storage_space) && (count($storage_space))) {
			//echo "storage_space<pre>"; print_r($storage_space); echo "</pre>";
			?>
			<div class="embi-rightnow-wrapper embi-rightnow-myblog-section">
				<div class="embi_dashboard">
					<h4 class="embi-section-title"><?php echo $title; ?><?php if (is_super_admin()) { ?> <a style="float: right" title="<?php _e('Manage your profile', 'embi'); ?>" href="<?php echo admin_url('network/site-settings.php?id='. $wpdb->blogid); ?>"><?php _e('Manage Blog Space', 'embi'); ?></a><?php } ?>
					</h4>
					<ul>	
						<li>
							<div class="embi-item_title"><?php _e('Max Allowed', 'embi'); ?>:</div>
							<div class="embi-item_value"><?php echo $storage_space['total']; ?></div>
						</li>
						<li>
							<div class="embi-item_title"><?php _e('Used', 'embi'); ?>:</div>
							<div class="embi-item_value"><?php echo $storage_space['used']; ?> (<?php echo $storage_space['percent']; ?>%)</div>
						</li>
<?php /* ?>
						<li>
							<div class="embi-item_title"><?php _e('Available', 'embi'); ?>:</div>
							<div class="embi-item_value"><?php echo $storage_space['available']; ?> (<?php echo 100 - $storage_space['percent']; ?>%)</div>
						</li>
<?pgp */ ?>
					</ul>
				</div>
			</div>
			<?php
		}
	}
	
	function embi_prosites_section($title='') {
		global $wpdb, $psts;

		if ( ! current_user_can( 'manage_options' ) )

		if (!function_exists('is_pro_user'))  return;
		
		// If not a Pro User (whatever that is). Then we do not show the Pro Site section
		if (!is_pro_user(get_current_user_id())) return;

		$levels = (array)get_site_option('psts_levels');

		$site_level = $psts->get_level($wpdb->blogid);
		if (isset($levels[intval($site_level)])) {
			$site_level_text = $levels[intval($site_level)]['name'];
		} else {
			$site_level_text = '<a class="button button-primary" href="' . admin_url('admin.php?page=psts-checkout') . '">' . 
				__('Upgrade to Pro!', 'embi') . '</a>';
		}

		if (is_pro_trial($wpdb->blogid))
			$site_level_text .= " (Trial)". ' <a style="float: right" class="button button-primary" href="' . admin_url('admin.php?page=psts-checkout') . '">' . 
				__('Upgrade to Pro!', 'embi') . '</a>';;

		$site_expire = $psts->get_expire($wpdb->blogid);
		//echo "site_expire=[". $site_expire ."]<br />";

		//$site_expire_text = '<strong>'. __('Expired', 'embi'). '</strong>';
		if (empty($site_expire))
			$site_expire_text = '';
		else if ($site_expire > 2147483647)
			$site_expire_text = __('<strong>Never</strong>', 'embi');
		else
    		$site_expire_text =  date_i18n(get_option('date_format'), $site_expire) ." (". intval(($site_expire-time()) / 86400) ." ". __('days', 'embi') .")";

		?>
		<div class="embi-rightnow-wrapper embi-rightnow-my-blogs-section">
			<div class="embi_dashboard">
				<h4 class="embi-section-title"><?php echo $title; ?><a 
					style="float:right" href="<?php echo admin_url('admin.php?page=psts-checkout'); ?>"><?php _e('Manage Pro Account', 'embi'); ?></a></h4>
				<ul>	
					<li>
						<div class="embi-item_title"><?php _e('Level'); ?>:</div>
						<div class="embi-item_value"><?php echo $site_level_text; ?></div>
					</li>
					<?php if (!empty($site_expire_text)) { ?>
					<li>
						<div class="embi-item_title"><?php _e('Expiration date'); ?>:</div>
						<div class="embi-item_value"><?php echo $site_expire_text; ?></div>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<?php
	}
	
	

	
/* ----- My Blogs enhancement ----- */
	
	function show_additional_blog_info ($html, $blog) {
		if (!is_object($blog)) return $html;
		$additional = ''; 
		$additional .= $this->_get_markup($blog); 
		
		return "{$html}{$additional}";
	}
	
	function _get_markup ($blog) {
		$blog_id = (int)$blog->userblog_id; 
		
		switch_to_blog($blog_id);
		$posts = wp_count_posts('post');
		$comments = wp_count_comments();
		$details = get_blog_details($blog_id);
		$users = get_users(array('blog_id' => $blog_id, 'number' => self::USERS_LIMIT));
		$info = get_bloginfo('description');
		
		$ret =
			"<div class='embi-my_blogs'>" .
				$this->_get_info_markup_fragment($info) .
				$this->_get_posts_markup_fragment($posts) .
				$this->_get_comments_markup_fragment($comments) .
			"</div>" .
			"<div class='embi-my_blogs embi-flat'>" .
				$this->_get_details_markup_fragment($details) .
				$this->_get_users_markup_fragment($users) .
			"</div>" .
		'';
		
		$ret = 
			"<div class='embi-item_actions'>" .
				$this->_get_posts_action_link($posts) .
				'&nbsp|&nbsp;' .
				$this->_get_comments_action_link($comments) .
				'&nbsp|&nbsp;' .
				'<a href="#" data-embi-msg="' . __('Close', 'embi') . '" class="embi-item_trigger">' . __('More info', 'embi') . '</a>' .
			"</div>" .
			"<div class='embi-item'>{$ret}</div>" .
		"";
		restore_current_blog();
		
		return $ret;
	}
	
	function _markup_fragment_wrap ($title, $markup) {
		return "<div class='embi-my_blogs-additional_item'><h4>{$title}</h4><table>{$markup}</table></div>";
	}
	
	function _get_info_markup_fragment ($data) {
		return "<p class='embi-description'>$data</p>";
	}
	
	function _get_posts_markup_fragment ($data) {
		return $this->_markup_fragment_wrap(__('Posts'),  
			"<tr>" .
				"<th>" . __('Published') . "</th>" .
				"<td>" . $data->publish . "</td>" .
			"</tr>" .
			"<tr>" .
				"<th>" . __('Pending') . "</th>" .
				"<td>" . $data->pending . "</td>" .
			"</tr>" .
			"<tr>" .
				"<th>" . __('Draft') . "</th>" .
				"<td>" . $data->draft . "</td>" .
			"</tr>" .
			"<tr><td><a href='" . admin_url('edit.php') . "'>" . __('Manage') . "</a></td></tr>"
		);
	}
	function _get_posts_action_link ($data) {
		$pending = $data->pending ? 'embi-has_pending' : '';
		return '<a href="' . admin_url('edit.php') . '" class="' . $pending . '">' . 
			sprintf(__('<b>%d</b> pending posts', 'embi'), $data->pending) .
		'</a>';
	}

	function _get_comments_markup_fragment ($data) {
		return $this->_markup_fragment_wrap(__('Comments'),
			"<tr>" .
				"<th>" . __('Approved') . "</th>" .
				"<td>" . $data->approved . "</td>" .
			"</tr>" .
			"<tr>" .
				"<th>" . __('Pending') . "</th>" .
				"<td>" . $data->moderated . "</td>" .
			"</tr>" .
			"<tr>" .
				"<th>" . __('Spam') . "</th>" .
				"<td>" . $data->spam . "</td>" .
			"</tr>" .
			"<tr><td><a href='" . admin_url('edit-comments.php') . "'>" . __('Manage') . "</a></td></tr>"
		);
	}
	function _get_comments_action_link ($data) {
		$pending = $data->moderated ? 'embi-has_pending' : '';
		return '<a href="' . admin_url('edit-comments.php') . '" class="' . $pending . '">' . 
			sprintf(__('<b>%d</b> pending comments', 'embi'), $data->moderated) .
		'</a>';
	}

	function _get_details_markup_fragment ($data) {
		return $this->_markup_fragment_wrap(__('Last Updated'),
			"<tr>" .
				"<td>" . 
					mysql2date(get_option('date_format'), $data->last_updated) .
					"&nbsp" . 
					mysql2date(get_option('time_format'), $data->last_updated) .
				"</td>" .
			"</tr>"
		);
	}

	function _get_users_markup_fragment ($data) {
		$users = '';
		$counter = 0;
		foreach ($data as $user) {
			if ($counter == self::USERS_LIMIT-1) break;
			$users .= '<a href="' . admin_url('/user-edit.php?user_id=' . $user->ID) . '">' . $user->user_login . '</a> (' . $user->user_email . ')</br />';
			$counter++;
		}
		if (count($data) > self::USERS_LIMIT-1) {
			$users .= '<a href="' . admin_url('users.php') . '">' . __('All users') . '</a>';
		}
		return $this->_markup_fragment_wrap(__('Users'), "<tr><td>{$users}</td></tr>");
	}

	function _get_space () {
		$space = get_space_allowed();
		//echo "space<pre>"; print_r($space); echo "</pre>";
		$_used_bytes = apply_filters('embi_upload_storage_used_bytes', get_dirsize( BLOGUPLOADDIR ));
		$used = apply_filters('embi_upload_storage_used_mb', $_used_bytes / 1024 / 1024);
		
		$available = $space - $used;
		
		$percentused = sprintf("%.2f", ( $used / $space ) * 100);
		
		$space = ( $space > 1000 ) ?
			sprintf("%.2f", ( $space / 1024 )) . __( 'GB' )
			:
			sprintf("%.2f", $space) . __( 'MB' )
		;
		
		$used = ( $used > 1000 ) ?
			sprintf("%.2f", ( $used / 1024 )) . __( 'GB' )
			:
			sprintf("%.2f", $used) . __( 'MB' )
		;


		$available = ( $available > 1000 ) ?
			sprintf("%.2f", ( $available / 1024 )) . __( 'GB' )
			:
			sprintf("%.2f", $available) . __( 'MB' )
		;
		
		return array(
			'total' => $space,
			'used' => $used,
			'percent' => $percentused,
			'available'	=> $available	
		);
	}
	
	function _get_editable_field ($type) {
		global $current_user;
		$value = esc_attr(@$current_user->$type);
		
		return "<div class='embi-editable_field'>" .
			"<input type='text' size='32' class='embi-editable_field-data' name='{$type}' value='{$value}' />" .
			"&nbsp;" .
			"<input type='button' class='button button-primary embi-editable_field-ok' value='" . __('OK') . "' />" .
			"<input type='button' class='button embi-editable_field-cancel' value='" . __('Cancel') . "' />" .
		"</div>";
	}
	
	function show_filter_links () {
		echo '<p>' .
			'<a href="#" class="embi-filter_link" id="embi-show_pending">' . 
				__('Show blogs with pending posts and comments', 'embi') . 
			'</a>' .
			'&nbsp;|&nbsp;' .
			'<a href="#" class="embi-filter_link" id="embi-show_all">' . 
				__('Show all blogs', 'embi') . 
			'</a>' .
		'</p>';
	}
	
	function js_print_scripts () {
		$screen = get_current_screen();
		if (in_array(@$screen->id, array('dashboard', 'my-sites'))) {
			wp_enqueue_script('embi-admin', EMBI_PLUGIN_URL . '/js/embi-admin.js');
		}
	}

	function css_print_styles () {
		$screen = get_current_screen();
		if (in_array(@$screen->id, array('dashboard', 'my-sites'))) {
			wp_enqueue_style('embi-admin', EMBI_PLUGIN_URL . '/css/embi-admin.css');
		}
	}
	
	function json_update_editable_field_value () {

		$allowed_names = array(
			'display_name', 'user_email'
		);
		$name = @$_POST['name'];
		$value = @$_POST['value'];
		
		if (!in_array($name, $allowed_names)) return false;	
		
		$value = ('user_email' == $name) ? is_email($value) : wp_filter_nohtml_kses($value);
		if ($name && $value) {
			update_user_meta(get_current_user_id(), $name, $value);
		}
		echo $value;
		die;
	}
	
}