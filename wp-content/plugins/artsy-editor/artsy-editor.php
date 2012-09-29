<?php
/*
Plugin Name: Artsy Editor
Plugin URI: http://artsyeditor.com/
Description: Distraction-free editor.
Version: 1.2.4
Author: Stephen Ou and Sean Fisher
Author URI: http://artsyeditor.com/authors/

Copyright 2011 Stephen Ou and Sean Fisher

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

define('ARTSY_VERSION', '1.2.4');

if (! defined('ARTSY_USE_MINIMIZED_FILE'))
	define('ARTSY_USE_MINIMIZED_FILE', true);

/**
 * Setup the class
 *
 * @access private
**/
function setup_artsy()
{
	$artsy = new Artsy();
}
setup_artsy();

/**
 * The Artsy Interface
 *
 * It is a simple class that is very DRY.
 * We like to keep only the relevant things here.
 * Helper functions (things that you use to help you) can be kept outside the class.
 *
 * @since 0.6
**/
class Artsy
{
	/**
	 * The settings prefix
	 *
	 * @access private
	 * @global string
	**/
	var $prefix	=	'artsy_';
	var $func_id = NULL;
	
	/**
	 * The plugin slug/folder name
	 *
	 * @access public
	**/
	var $plugin_slug = NULL;
	
	/**
	 * The public plugin URL
	 *
	 * @access public
	 * @global string
	**/
	var $url_public = NULL;
	
	/**
	 * The file path plugin URL
	 *
	 * @access public
	 * @global string
	**/
	var $url_file = NULL;
	
	/**
	 * The file names of the CSS, Images, and JS folders.
	 *
	 * @access public
	**/
	var $js_folder = 'js';
	var $img_folder = 'images';
	var $css_folder = 'css';
	
	/**
	 * The filename of the upload callback
	 *
	 * @access public
	 * @global string
	**/
	var $upload_url = 'upload.php';
	
	/**
	 * The license data
	 *
	 * @access private
	 * @global array
	**/
	var $license_data = NULL;
	
	/**
	 * The remote server base URL
	 *
	 * @global string
	**/
	var $remote_check = 'http://artsyeditor.com/wp-content/plugins/artsymanager/';
	
	/**
	 * The constructor
	 * Automatically called
	 *
	 * @access private
	**/
	function __construct()
	{
		//	Setup the constants
		$slug = untrailingslashit ( end( explode( DIRECTORY_SEPARATOR, dirname( __FILE__ ) ) ) );
		$this->plugin_slug = apply_filters('artsy_plugin_slug', $slug );
		$this->url_public = untrailingslashit(plugins_url($this->plugin_slug));
		$this->url_file = dirname(__FILE__);
		
		//	Setup the actions.
		//	In PHP5, you don't have to pass by reference.
		//	It is by default.
		add_action('init', array($this, 'init'));
		add_action('admin_menu', array($this, 'create_admin_page'));
		register_activation_hook(__FILE__, array($this, 'activate'));
		//register_deactivation_hook(__FILE__, array($this, 'deactivate'));
		
		// The AJAX actions
		add_action('wp_ajax_getHTML', array($this, 'ajax_gethtml'));
		add_action('wp_ajax_submitSettings', array($this, 'artsy_submitSettings_callback'));
		
		// Add other filter and stuff
		add_filter('tiny_mce_before_init', 'tiny_mce_allowed_tags');
		//add_filter('the_content', 'artsy_smart_link');
		//add_filter('the_excerpt', 'artsy_smart_link');
	}
	
	/**
	 * The init function
	 *
	 * Called on 'init'.
	 *
	 * @see do_action() Called on the 'init' action
	 * @access private
	**/
	public function init()
	{
		
		if (isset($_GET['edit'])) {
			if (!empty($_SERVER['HTTPS'])) {
				$url = 'https://';
			} else {
				$url = 'http://';
			}
			$url .= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$url = get_edit_post_link(url_to_postid($url), '&');
			if ($url != FALSE) {
				wp_redirect($url);
				exit;
			}
		}
		
		//	Only do anything in the admin interface.
		if (! is_admin()) return;
		
		if (ARTSY_USE_MINIMIZED_FILE == true) $min = '.min';
		else $min = '';
	
		$file = end(explode(DIRECTORY_SEPARATOR, $_SERVER['SCRIPT_FILENAME']));
		
		//	If we are in the post edit or new post screen, disable the admin bar.
		if ($file == 'post.php' || $file == 'post-new.php')
		{
			$background = get_option($this->prefix.$this->format_setting('Background'), 'white');
			// First queue up our stylesheet
			wp_enqueue_style('artsy_general_style', $this->url_public.'/'. $this->css_folder.'/style'.$min.'.css');
			wp_enqueue_style('artsy_specific_style', $this->url_public.'/'. $this->css_folder.'/backgrounds/'.sanitize_title_with_dashes($background).''.$min.'.css');
			
			// First do jQuery script
			wp_enqueue_script('jquery');
			
			// Now the jquery file upload file
			wp_enqueue_script('artsy_file_upload_js', $this->url_public.'/'.$this->js_folder.'/fileupload'.$min.'.js');
			
			// Now the jquery file upload UI file
			wp_enqueue_script('artsy_file_upload_ui_js', $this->url_public.'/'.$this->js_folder.'/fileupload-ui'.$min.'.js');
			
			// Now the jquery shortcut file
			wp_enqueue_script('artsy_shortcut_js', $this->url_public.'/'.$this->js_folder.'/shortcut'.$min.'.js');
			
			// Now the main JS file
			//	We have to user create_function() because if we enqueue the script, it'll be loaded before TinyMCE. We don't want that!
			$this->func_id = create_function('', 'echo "<script type=\"text/javascript\" src=\"'.$this->url_public.'/'.$this->js_folder.'/main'.$min.'.js\"></script>";');
			
				add_filter( 'show_admin_bar', '__return_false' );
				add_action('admin_head', array($this, 'settings_in_wp_admin'));
				
				//	The 'after_wp_tiny_mce' action doesn't exist in 3.1.x!
				if ( function_exists( 'wp_http_supports' ) )
					add_action('after_wp_tiny_mce', $this->func_id);
				else
					add_action('admin_print_footer_scripts', $this->func_id);
		}
		
		//	Check for updates
		//	Performs license check
		$this->check_for_updates();
		
		//	Check if they are updating the settings
		$this->update_settings_check();
	}
	
	/**
	 * The callback to add an admin menu
	 *
	 * @access private
	**/
	public function create_admin_page()
	{
		add_plugins_page('Artsy Editor Options', 'Artsy Editor', 'administrator', 'artsy_editor', array($this, 'get_options_page'));
	}
	
	/**
	 * Admin page
	 *
	 * The callback for the admin page.
	 *
	 * @access private
	**/
	public function admin_page()
	{
		
	}
	
	/**
	 * Handle the updates from the admin page
	 *
	 * @access public
	**/
	public function admin_page_post()
	{
		
	}
	
	/**
	 * The on-activate function
	 *
	 * @access private
	**/
	public function activate()
	{
		$settings_array = $this->generic_settings();
		$settings_option_array = $this->get_default_options();
		$i = 0;
		foreach ($settings_array as $settings_row) {
			$option = add_option($this->prefix.$this->format_setting($settings_row), $settings_option_array[$i], '', 'yes');
			$i++;
		}
	}
	
	/**
	 * The on-deactivate function
	 *
	 * @access private
	**/
	public function deactivate()
	{
		$settings_array = $this->generic_settings();
		foreach ($settings_array as $settings_row)
			$option = delete_option($this->prefix.$this->format_setting($settings_row));
	}
	
	/**
	 * The image box callback
	 *
	 * @access private
	 * @return string
	**/
	public function imagebox_callback()
	{
	
		return '
		<div id="artsy-image-box" class="artsy-box '.artsy_get_current_browser_class().' '.artsy_get_current_os_class().'">
			<div class="artsy-box-row first" id="top">
				<div class="artsy-box-group right">
					<button class="artsy-button first" id="action-alignImageNone" title="Align None">
						<img src="'.$this->url_public.'/images/icons/justify.png" />
					</button>
					<button class="artsy-button" id="action-alignImageLeft" title="Align Left">
						<img src="'.$this->url_public.'/images/icons/left.png" />
					</button>
					<button class="artsy-button" id="action-alignImageMiddle" title="Align Middle">
						<img src="'.$this->url_public.'/images/icons/center.png" />
					</button>
					<button class="artsy-button last" id="action-alignImageRight" title="Align Right">
						<img src="'.$this->url_public.'/images/icons/right.png" />
					</button>
				</div>
				<div class="artsy-box-group left" title="Alignment">Align</div>
				<div class="clear"></div>
			</div>
			<div class="artsy-box-row" id="middle">
				<div class="artsy-box-group right">
					<form method="post" action="">
						<input type="text" class="artsy-input-text" name="image-title" id="input-image-title" value="Title" />
						<span class="clear-field" id="clear-input-image-title" title="Click to delete title">
							<img src="'.$this->url_public.'/images/icons/delete.png" />
						</span>
					</form>
				</div>
				<div class="artsy-box-group left" title="TItle">Title</div>
				<div class="clear"></div>
			</div>
			<div class="artsy-box-row" id="middle">
				<div class="artsy-box-group right">
					<form method="post" action="">
						<input type="text" class="artsy-input-text" name="image-alt" id="input-image-alt" value="Alt" />
						<span class="clear-field" id="clear-input-image-alt" title="Click to delete alt">
							<img src="'.$this->url_public.'/images/icons/delete.png" />
						</span>
					</form>
				</div>
				<div class="artsy-box-group left" title="Alternate Text">Alt</div>
				<div class="clear"></div>
			</div>
			<div class="artsy-box-row" id="middle">
				<div class="artsy-box-group right">
					<form method="post" action="">
						<input type="text" class="artsy-input-text" name="image-alt" id="input-image-cap" value="Cap" />
						<span class="clear-field" id="clear-input-image-cap" title="Click to delete caption">
							<img src="'.$this->url_public.'/images/icons/delete.png" />
						</span>
					</form>
				</div>
				<div class="artsy-box-group left" title="Caption">Cap</div>
				<div class="clear"></div>
			</div>
			<div class="artsy-box-row" id="middle">
				<div class="artsy-box-group right">
					<form method="post" action="">
						<input type="text" class="artsy-input-text" name="image-link" id="input-image-link" value="Link" />
						<span class="clear-field" id="clear-input-image-link" title="Click to delete link">
							<img src="'.$this->url_public.'/images/icons/delete.png" />
						</span>
					</form>
				</div>
				<div class="artsy-box-group left" title="Link">Link</div>
				<div class="clear"></div>
			</div>
			<div class="artsy-box-row" id="middle">
				<div class="artsy-box-group right">
					<form method="post" action="">
						W: <input type="text" class="artsy-input-text height-width" name="image-width" id="input-image-width" value="Width" />
						H: <input type="text" class="artsy-input-text height-width" name="image-height" id="input-image-height" value="Height" />
						<input type="submit" class="hidden-button" />
					</form>
				</div>
				<div class="artsy-box-group left" title="Size">Size</div>
				<div class="clear"></div>
			</div>
			<div class="artsy-box-row last" id="bottom">
				<div class="original-size buttons" id="original-size-button">Original</div>
				<div class="delete-image buttons" id="delete-image-button">Delete</div>
				<div class="clear"></div>
			</div>
		</div>
		';
		
	}

	/**
	 * The settings box callback
	 *
	 * @access public
	 * @return string
	**/
	public function settings_box_callback() {
	
		$faq_array = array(
					'Where are all the formatting options?' => 'Once any text in content area is selected, the formatting options will show up.',
					'How do I upload an image?' => 'You can drag in an image from your computer. It will be automatically uploaded and inserted for you.',
					'How do I resize an image?' => 'If you hover over an image, you will see a handle at the bottom. Drag that handle until the image size is appropriate.',
					'How do I switch header back to paragraph?' => 'If you apply h3 to some text, simply click h3 again to cancel the formatting.',
					'Where are H2 to H6?' => 'If you click on Blockquote or H1, they will show up under. Click on Blockquote or H1 again, and they will hide.',
					'How do I save a post as draft?' => 'Hover your mouse over + on the top right. Click on Save Draft when the menu shows up.',
					'How do I exit Artsy Editor and go back to normal interface?' => 'You can click the X on the top left or press Esc on your keyboard.'
		)
		;
		$shortcut_array = array(
					'Esc' => 'Open/Close editor',
					'Command+,' => 'Settings',
					'Command+b or Shift+Alt+b' => 'Bold',
					'Command+i or Shift+Alt+i' => 'Italicize',
					'Command+u' => 'Underline',
					'Command+k or Shift+Alt+a' => 'Create link',
					'Command+0 or Shift+Alt+p' => 'Paragraph',
					'Command+1-6 or Shift+Alt+1-6' => 'H1-6',
					'Shift+Alt+d' => 'Strike-through',
					'Shift+Alt+s' => ' Remove link',
					'Shift+Alt+u' => 'Unordered list',
					'Shift+Alt+o' => 'Ordered list',
					'Shift+Alt+q' => 'Blockquote',
					'Shift+Alt+l' => 'Align left',
					'Shift+Alt+c' => 'Align center',
					'Shift+Alt+r' => 'Align right',
					'Shift+Alt+j' => 'Align justify'
		);
	
		$html = '
		<div id="artsy-editor-settings" class="'.artsy_get_current_browser_class().' '.artsy_get_current_os_class().'">	
			<div class="top">
				Artsy Editor
				<div class="left">
					<a class="cancel_settings" href="#">x</a>
				</div>
				<div class="right">
					<a target="_blank" href="'.get_admin_url().'plugins.php?page=artsy_editor">+</a>
				</div>
				<div class="clear"></div>
			</div>
			<div class="bottom">';
		$html .=  '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">';
		$html .=  '<div class="action-links">
						<a href="#" id="view-settings" class="active">Settings</a> - <a href="#" id="view-faqs">FAQs</a> - <a href="#" id="view-shortcuts">Shortcuts</a>
					</div>
					<div class="group-settings">';
		$html .= $this->options_page();
		$html .= '
					</div>
					<div class="group-faqs">';
		
		$count_faq = count($faq_array);
		$count = 0;
		foreach ($faq_array as $faq_key => $faq_value)
		{
			$count++;
			
			if ( $count == $count_faq )
				$html .= '<div class="group bottom_row">';
			else
				$html .= '<div class="group">';
				
			$html .= '<div class="left">'.$faq_key.'</div>
						<div class="right">'.$faq_value.'</div>
						<div class="clear"></div>
					</div>';
		}
		$html .= '
					</div>
					<div class="group-shortcuts">';
		$count_short = count($shortcut_array);
		$count = 0;
		foreach ($shortcut_array as $shortcut_key => $shortcut_value)
		{
			$count++;
			if ( $count == $count_short )
				$html .= '<div class="group bottom_row">';
			else
				$html .= '<div class="group">';
			
			$html .= '<div class="left">'.$shortcut_value.'</div>
						<div class="right">'.$shortcut_key.'</div>
						<div class="clear"></div>
					</div>';
		}
		$html .= '
					</div>
				</form>
			</div>
		</div>';
		return $html;
		
	}
	/**
	 * The callback to the upload box
	 *
	 * @access public
	**/
	public function upload_cb()
	{
		return '';
		$temp = preg_split("/post=/", $_GET['postURL']);
	$temp = preg_split("/&action=/", $temp[1]);
	$args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $temp[0] ); 
	$attachments = get_posts( $args );
	$galleryHTML = "";
	if ($attachments) {
		foreach ( $attachments as $post ) {
			setup_postdata($post);
			$galleryHTML .= '<tr>';
			$galleryHTML .= '<td><img src="' . wp_get_attachment_url($post->ID) . '" alt="" title="" /></td>';
			$galleryHTML .= '<td><a title="'.$post->ID.'" href="' . wp_get_attachment_url($post->ID) . '" class="galleryImage">Insert Into Content</a></td>';
			$galleryHTML .= '</tr>';
		}
	}
	return '
	<div id="artsy-editor-upload" class="'.artsy_get_current_browser_class().' '.artsy_get_current_os_class().'">
		<div id="oeu-header"><a href="#" id="oeu-close">[x]</a> &nbsp; &nbsp; Image</div>
		<div id="tabs">
			<div class="aTab active" id="localImage">Local</div>
			<div class="aTab" id="urlImage">From URL</div>
			<div class="aTab" id="galleryImage">From Gallery</div>
		</div>
		<div id="oeu-content">
			<div class="oeuc-tab" id="urlImage">
				<table>
					<tr>
						<td>Input URL:</td><td><input type="text" name="input-url" id="input-link" value="" /></td>
					</tr>
					<tr>
						<td colspan="2"><input type="button" name="input-url-submit" id="input-link-submit" value="Insert Into Content" /></td>
					</tr>
				</table>
			</div>
			<div class="oeuc-tab" id="galleryImage">
				<table>'.$galleryHTML.'</table>
			</div>
			<div class="oeuc-tab" id="localImage">
				<iframe id="artsy-uploadIframe" src="/wordpress/wp-admin/admin-ajax.php?action=getUploadLocal&postID='.$temp[0].'" width="400" style="border: 0px"></iframe>
			</div>
		</div>
	</div>
	';
	}
	
	/**
	 * The editor callback
	 *
	 * @return string
	**/
	public function editor_callback()
	{
		return '
	<div id="artsy-editor-box" class="artsy-box '.artsy_get_current_browser_class().' '.artsy_get_current_os_class().'">
		<div class="artsy-box-row first" id="top">
			<div class="artsy-box-group left">
				<button class="artsy-button first" id="action-bold" title="Bold">
					<div></div>
				</button>
				<button class="artsy-button" id="action-italicize" title="Italicize">
					<div></div>
				</button>
				<button class="artsy-button" id="action-underline" title="Underline">
					<div></div>
				</button>
				<button class="artsy-button last" id="action-strikethrough" title="Strikethrough">
					<div></div>
				</button>
			</div>
			<div class="artsy-box-group right">
				<button class="artsy-button first" id="action-insertUnorderedList" title="Unordered List">
					<div></div>
				</button>
				<button class="artsy-button last" id="action-insertOrderedList" title="Ordered List">
					<div></div>
				</button>
			</div>
			<div class="clear"></div>
		</div>
		<div class="artsy-box-row" id="middle">
			<div class="artsy-box-group left">
				<button class="artsy-button first" id="action-alignLeft" title="Align Left">
					<div></div>
				</button>
				<button class="artsy-button" id="action-alignMiddle" title="Align Middle">
					<div></div>
				</button>
				<button class="artsy-button" id="action-alignRight" title="Align Right">
					<div></div>
				</button>
				<button class="artsy-button last" id="action-alignWrap" title="Align Justified">
					<div></div>
				</button>
			</div>
			<div class="artsy-box-group right">
				<button class="artsy-button show-hidden-row first" id="action-quotes" title="Blockquote">
					<div></div>
				</button>
				<button class="artsy-button heading-change show-hidden-row show-header last" id="action-h3" title="h3">
					<div></div>
				</button>
			</div>
			<div class="clear"></div>
		</div>
		<div class="artsy-box-row hidden-row" id="middle">
			<div class="artsy-box-group left">
				<button class="artsy-button heading-change first" id="action-h1" title="h1">
					<div></div>
				</button>
				<button class="artsy-button heading-change" id="action-h2" title="h2">
					<div></div>
				</button>
				<button class="artsy-button heading-change" id="action-h3" title="h3">
					<div></div>
				</button>
				<button class="artsy-button heading-change last" id="action-h4" title="h4">
					<div></div>
				</button>
			</div>
			<div class="artsy-box-group right">
				<button class="artsy-button heading-change first" id="action-h5" title="h5">
					<div></div>
				</button>
				<button class="artsy-button heading-change last" id="action-h6" title="h6">
					<div></div>
				</button>
			</div>
			<div class="clear"></div>
		</div>
		<div class="artsy-box-row last" id="bottom">
			<form method="post">
				<input type="text" class="artsy-input-text" name="url" id="input-link" value="http://stephenou.com" />
				<input type="checkbox" name="newWindow" id="action-newWindow" title="Check to open link in new window" value="1" />
				<span class="toolbar_removelink clear-field" title="Click to delete link">
					<img src="'.$this->url_public.'/images/icons/delete.png" id="clear-url" />
				</span>
			</form>
		</div>
	</div>
	';
	}
	
	/**
	 * Filter settings link
	 *
	 * @access public
	**/
	public function filter_settings_links($links, $file)
	{
		
	}
	
	/**
	 * If they submitted a settings form
	 *
	 * @access private
	 * @return void
	**/
	public function update_settings_check()
	{
		//	Submitted it!, But check the nonce.
		if ( isset( $_REQUEST[$this->prefix.'submit'] ) )
		{
			if ( check_admin_referer('artsy-action', 'artsy-nonce') ) :
				
				$settings_array = $this->generic_settings();
				foreach ($settings_array as $settings_row)
				{
					if ( isset( $_POST[$this->format_setting($settings_row)]))
						update_option($this->prefix.$this->format_setting($settings_row), $_POST[$this->format_setting($settings_row)]);
					
					if ($this->format_setting($settings_row) == 'license-key')
					{
						delete_transient('artsy_update_check');
						delete_transient('artsy_change_log');
						
						$this->check_for_updates(TRUE);
					}
				}
				
				//	Redirect to a page to tell them that we did an update!
				wp_redirect(admin_url('plugins.php?page=artsy_editor&did_update=true'));
			endif;
		}
	}
	
	/**
	 * The options page callback
	 *
	 * @access public
	**/
	public function get_options_page()
	{
		//	Current settings
		$settings_array = $this->generic_settings();
		$font_array = $this->get_fonts();
		$background_array = $this->get_backgrounds();
		$open_automatically_array = $this->get_auto_open();
		$open_in_array = $this->get_open_mode();
		
		$selected_html = ' selected="selected"';
		$checked_html = ' checked="checked"';
		$this->check_for_updates();
		
		include(dirname(__FILE__).'/settings.php');
		
	}
	
	/**
	 * Callback for a lot of the magic that runs in the browser
	 *
	 * @access public
	**/
	public function getEditor_callback($post_id)
	{
		//	Get user prefs.
		//	Font
		$font_size = (int) get_option($this->prefix.$this->format_setting('Font Size'));
		
		//	Default font size
		if ( $font_size == 0 )
			$font_size = 16;
		
		if ($font_size < 8 )
			$font_size = 10;
		$title_font_size = $font_size+4;
		$line_height = $font_size * 1.5;
		$font = trim(get_option($this->prefix.$this->format_setting('Font')));
		
		//	Default to Arial
		if ( empty( $font ) )
			$font = 'Arial';
		
		//	Page
		$background = get_option($this->prefix.$this->format_setting('Background'));
		$background_color = '#'.$this->determine_color($background);
		$text_color = '#'.$this->determine_color_text($background);
		
		$help_shown = get_option($this->prefix.$this->format_setting('Help Shown'));
		if (get_post_status($post_id) == 'auto-draft')
			update_option($this->prefix.$this->format_setting('Help Shown'), 1);
		
		//	The URL to post to with WP nonce
		$post_url = wp_nonce_url($this->url_public.'/'.$this->upload_url.'?post_id='.$post_id, 'artsy-action');
		
		return '
		<div id="artsy-style-block">
			<style type="text/css">
				#artsy-editor-container #artsy-editor-title, #artsy-editor-container #artsy-editor-content {
					font-family: '.$font.', sans-serif;
				}
				#artsy-editor-container #artsy-editor-title {
					font-size: '.$title_font_size.'px;
				}
				#artsy-editor-content, #artsy-editor-content p, #artsy-editor-content blockquote, #artsy-editor-content ul, #artsy-editor-content ol {
					font-size: '.$font_size.'px;
					line-height: '.$line_height.'px;
				}
			</style>
		</div>
		<script type="text/javascript">artsy.browser = \''.artsy_get_current_browser().'\'; artsy.operatingSystem = \''.artsy_get_current_os().'\';</script>
		<div id="artsy-editor-container" class="'.artsy_get_current_browser_class().' '.artsy_get_current_os_class().'">
			<div id="artsy-menu">
				<div id="artsy-close"><img src="'.$this->url_public.'/images/icons/close.png" alt="Close" title="Close" border="0" /></div>
				<div id="artsy-settings"><img src="'.$this->url_public.'/images/icons/settings.png" alt="Settings" title="Settings" border="0" /></div>
				<div id="artsy-status-control">
					<ul class="secondary-control">
						<li id="artsy-publish">Publish</li>
						<li id="artsy-save-draft">Save Draft</li>
						<li id="artsy-preview">Preview</li>
						<li id="artsy-move-to-trash">Move to Trash</li>
						<li id="artsy-html-toggle" onclick="artsy.toggleViewMode();">HTML Mode</li>
					</ul>
					<ul  id="artsy-publish" class="primary-control">
						<li>
							<span id="publish-control">Publish</span>
							<a class="toggle-secondary-control" href="#">+</a>
						</li>
					</ul>
				</div>
				<form id="artsy-file-upload" action="'.$post_url.'" method="post" enctype="multipart/form-data">
					<input type="file" name="artsy_file" id="artsy_file" value="Edit" multiple>
					<input type="submit" class="submit" value="Submit">
				</form>
			</div>
			<div id="artsy-wrapper">
				<input type="name" id="artsy-editor-title" placeholder="Enter title here" />
				<input type="hidden" id="artsy-help-shown" value="'.$help_shown.'" />
				<textarea id="artsy-editor-content-html"></textarea>
				<div id="artsy-editor-content" contentEditable="true"></div>
				<div id="artsy-word-count">0</div>
			</div>
		</div>
		<div id="artsy-background" class="'.artsy_get_current_browser_class().' '.artsy_get_current_os_class().'">
			<div class="tips-container">
				<div class="tips">Hello World</div>
			</div>
		</div>
		<div id="artsy-mask">
			<div class="artsy-preload-images" style="display:none">
				<img src="'.$this->url_public.'/images/icons/cancel.png" />
				<img src="'.$this->url_public.'/images/icons/submit.png" />
				<img src="'.$this->url_public.'/images/icons/bold.png" />
				<img src="'.$this->url_public.'/images/icons/italic.png" />
				<img src="'.$this->url_public.'/images/icons/underlined.png" />
				<img src="'.$this->url_public.'/images/icons/strikethrough.png" />
				<img src="'.$this->url_public.'/images/icons/unordered.png" />
				<img src="'.$this->url_public.'/images/icons/ordered.png" />
				<img src="'.$this->url_public.'/images/icons/left.png" />
				<img src="'.$this->url_public.'/images/icons/center.png" />
				<img src="'.$this->url_public.'/images/icons/right.png" />
				<img src="'.$this->url_public.'/images/icons/justify.png" />
				<img src="'.$this->url_public.'/images/icons/quote.png" />
				<img src="'.$this->url_public.'/images/icons/h1.png" />
				<img src="'.$this->url_public.'/images/icons/h2.png" />
				<img src="'.$this->url_public.'/images/icons/h3.png" />
				<img src="'.$this->url_public.'/images/icons/h4.png" />
				<img src="'.$this->url_public.'/images/icons/h5.png" />
				<img src="'.$this->url_public.'/images/icons/h6.png" />
			</div>
		</div>
		';
		
	}
	
	/**
	 * Getting the generic settings (the list of settings for the plugin,
	 * not the ones already set)
	 *
	 * @return array
	**/
	public function generic_settings()
	{
		return array('License Key', 'Font', 'Font Size', 'Background', 'Show Word Count', 'Open Automatically', 'Help Shown', 'Open In');
	}
	
	/**
	 * Get the default options
	 *
	 * @return array
	**/
	public function get_default_options()
	{
		return array('', 'Arial', '15', 'White', 1, 0, 0, 'Visual Mode');
	}
	
	/**
	 * Format a settings title
	 *
	 * @acess public
	 * @return string
	 * @param string
	**/
	public function format_setting($key)
	{
		return strtolower(sanitize_title_with_dashes($key));
	}
	
	/**
	 * Getting the background color
	 *
	 * It defaults to light yellow
	 *
	 * @access public
	 * @return string
	**/
	function determine_color($color) {
		$color = trim(strtolower($color));
		switch($color)
		{
			case('light yellow');
				return 'EEEEDE';
			break;
			
			case('light green');
				return 'F3FFEE';
			break;
			
			case('light blue');
				return 'EEF9FF';
			break;
			
			case('light grey');
				return 'F3F3F3';
			break;
			
			case('dark grey');
				return '333333';
			break;
			
			case('white');
				return 'FFFFFF';
			break;
			
			default;
				return 'FFFFFF';
			break;
		}
	}
	
	/**
	 * Get the color of the text based upon the color of the background
	 * It defaults to black.
	 *
	 * @access public
	 * @return string
	 * @param string
	**/
	function determine_color_text($color) {
		$color = trim(strtolower($color));
		
		if (empty($color))
			return '000000';
			
		if ( $color === 'white' || substr( $color, 0, 5 ) === 'light')
			return '000000';
		else
			return 'eeeeee';
	}
	
	/**
	 * Get the list of usable fonts
	 *
	 * @return array
	**/
	public function get_fonts()
	{
		return array('Arial', 'Georgia', 'Verdana', 'Helvetica', 'Lucida Grande');
	}
	
	/**
	 * Get the list of backgrounds to use
	 *
	 * @return array
	**/
	function get_backgrounds() {
		return array('White', 'Light Yellow', 'Light Green', 'Light Blue', 'Light Grey', 'Dark Grey');
	}
	
	/**
	 * Get the option to show word and character count or not
	 *
	 * @return array
	**/
	public function get_show_word_count()
	{
		return array('Yes, display them', 'No, don\'t display them');
	}
	
	/**
	 * Get the option to open automatically or not
	 *
	 * @return array
	**/
	public function get_auto_open()
	{
		return array('Open automatically', 'Do not open automatically');
	}
	
	/**
	 * Get the option to open in visual or HTML
	 *
	 * @return array
	**/
	public function get_open_mode()
	{
		return array('Visual Mode', 'HTML Mode');
	}
	
	//	---------------------------------------------------
	//	AJAX
	//	---------------------------------------------------
	/**
	 * AJAX callback for updating the settings
	 *
	 * @access public
	 * @todo NONCE
	**/
	public function artsy_submitSettings_callback()
	{
		foreach ($_POST['settings'] as $key => $value) {
			update_option($this->prefix.str_replace('_', '-', $key), $value);
			$return[$key] = $value;
		}
		echo json_encode($this->options_page());
		die();
		
	}
	
	/**
	 * It returns JSON data about the editor that they will use
	 *
	 * @access public
	**/
	public function ajax_gethtml() {
	
		$post_id = $_POST['postID'];
		if (!isset($post_id) || $post_id == '') $post_id = 0;
		$return['editor'] = $this->getEditor_callback($post_id);
		$return['editorBox'] = $this->editor_callback();
		$return['settingsBox'] = $this->settings_box_callback();
		$return['imageBox'] = $this->imagebox_callback();
		$return['uploadBox'] = $this->upload_cb();
		echo json_encode($return);
		die();
	
	}
	
	/**
	 * Get the license key
	 *
	 * @access public
	 * @return string
	**/
	public function get_key()
	{
		return get_option($this->prefix.'license-key');
	}
	
	/**
	 * Check for any updates
	 *
	 * @access public
	 * @todo Add transient
	**/
	public function check_for_updates($no_cache = FALSE)
	{
		//	The update and change log are saved as transients.
		$get_updates_trans = get_transient('artsy_update_check');
		$changelog_trans = get_transient('artsy_change_log');
		
		//	The update transient doesn't exist or it is invalid
		if (! $get_updates_trans || $no_cache )
		{
			$remote_get_updates = wp_remote_get($this->remote_check . 'updates.php?'.$this->remote_params());
			set_transient('artsy_update_check', $remote_get_updates, 60*60*24);
			
			update_option($this->prefix.'last_update_time', current_time('timestamp', 0));
		} else {
			$remote_get_updates = $get_updates_trans;
		}
		
		//	The changelog transient doesn't exist or is invalid.
		if (! $changelog_trans || $no_cache )
		{
			$remote_get_changelog = wp_remote_get($this->remote_check . 'changelog.php?'.$this->remote_params());
			set_transient('artsy_change_log', $remote_get_changelog, 60*60*24);
		} else {
			$remote_get_changelog = $changelog_trans;
		}
		
		//	There was an error with the request.
		//	It might now be an invalid license, it might be something else.
		if (is_wp_error($remote_get_updates) || is_wp_error($remote_get_changelog) || ! is_string($remote_get_updates['body']))
		{
			//	Invalid license
			$this->license_data = NULL;
			
			add_action('all_admin_notices', array($this, 'invalid_license_msg'));
			
			define('ARTSY_LICENSE_INVALID', TRUE);
			return;
		}
		
		//	The 'updates.php' file will check for updates as well as provide license information.
		//	It will be JSON encoded
		$decode_updates = json_decode($remote_get_updates['body']);
		$this->license_data = $decode_updates;
		
		if (is_null($decode_updates) || ! $decode_updates || ! is_object($decode_updates) || ! isset($decode_updates->license_status ) || $decode_updates->license_status == 'invalid')
		{
			//	Invalid license
			$this->license_data = NULL;
			
			add_action('all_admin_notices', array($this, 'invalid_license_msg'));
		}
		
		//	Now, is there an update available?
		$is_on_beta = apply_filters('artsy_on_beta', false);
		
		if ( $is_on_beta )
		{
			//	Beta release
			$active_release = $this->license_data->active_releases_beta;	
		}
		else
		{
			//	Stable release
			$active_release = $this->license_data->active_releases_stable;
		}
		
		//	Compare the versions of this artsy editor and the latest release.
		if (version_compare(ARTSY_VERSION, $active_release, '<' ) )
		{
			//	There is one available!
			return array(
					'track'	=>	($is_on_beta) ? 'beta' : 'stable',
					'current_version'	=>	ARTSY_VERSION,
					'release_version'	=>	$active_release,
					'change_log'		=>	$remote_get_changelog['body'],
					'needs_to_update'	=>	TRUE,
			);
		}
		
		//	Nope, none.
		return array(
				'track'	=>	($is_on_beta) ? 'beta' : 'stable',
				'current_version'	=>	ARTSY_VERSION,
				'release_version'	=>	$active_release,
				'change_log'		=>	$remote_get_changelog['body'],
				'needs_to_update'	=>	FALSE,
		);
	}
	
	/**
	 * Invalid license message
	 *
	 * @access private
	**/
	public function invalid_license_msg()
	{
		?><div id="setting-error-settings_updated" class="updated settings-error"><p>You haven't set your license key yet! Or it might be invalid. Either way, Artsy Editor <strong>will not</strong> be functional until you do so. <a href="<?php echo admin_url('plugins.php?page=artsy_editor'); ?>">Set it now.</a></p></div>
<?php
	}
	
	/**
	 * GET variables for a remote request
	 *
	 * @access public
	**/
	private function remote_params()
	{
		$array = array();
		$array['server'] = $_SERVER['HTTP_HOST'];
		$array['request_url'] = $_SERVER['REQUEST_URI'];
		$array['license'] = $this->get_key();
		$array['version'] = ARTSY_VERSION;
		
		//	Include a fresh copy because another plugin could have overwriteen it
		include(ABSPATH.WPINC.'/version.php');
		
		$array['wpversion'] = $wp_version;
		$array['useragent'] = $_SERVER['HTTP_USER_AGENT'];
		
		return http_build_query($array);
	}
	
	/**
	 * Settings HTML
	 *
	 * @access public
	 * @return string
	**/
	public function options_page()
	{
		$settings_array = $this->generic_settings();
		$font_array = $this->get_fonts();
		$background_array = $this->get_backgrounds();
		$show_word_count_array = $this->get_show_word_count();
		$open_automatically_array = $this->get_auto_open();
		$open_in_array = $this->get_open_mode();
		
		//	What we're gonna return.
		$html = '';
		
		//	If they updated the settings.
		if (isset($_POST[$this->prefix.'submit']))
		{
			//	Check the nonce
			if (! wp_verify_nonce('artsy-action', 'artsy-nonce'))
				wp_die('Invalid nonce/security issue. Please try again.');
			
			foreach ($settings_array as $settings_row)
			{
				if (isset($_POST[$this->format_setting($settings_row)]))
					update_option($this->prefix.$this->format_setting($settings_row), $_POST[$this->format_setting($settings_row)]);
			}
			
			$html .= '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Settings saved.</strong></p></div>';
		}
		$selected_html = ' selected="selected"';
		$checked_html = ' checked="checked"';
		
		//	Loop though each setting
		foreach ($settings_array as $settings_row) :
			if ($this->format_setting($settings_row) !== 'help-shown' && $this->format_setting($settings_row) !== 'license-key')
			{
				$option = get_option($this->prefix.$this->format_setting($settings_row), 1);
			
			
			
				$html .=  '
					<div class="group">
						<div class="left">'.$settings_row.'<span style="display:none" id="'.$this->prefix.$this->format_setting($settings_row).'">'.$option.'</span></div>
						<div class="right">';
			if ($settings_row == 'Font') {
				
				$html .=  '<select name="'.$this->format_setting($settings_row).'" id="'.$this->format_setting($settings_row).'">';
				foreach ($font_array as $font_row) {
					if ($option == $font_row) $select = $selected_html;
					else $select = '';
					$html .=  '<option value="'.$font_row.'"'.$select.'>'.$font_row.'</option>';
				}
				$html .=  '</select>';
			}
			
			
			//	Font size
			if ($settings_row == 'Font Size')
			{
				$html .=  '<input name="'.$this->format_setting($settings_row).'" id="'.$this->format_setting($settings_row).'" type="range" min="8" max="24" value="'.$option.'">';
			}
			
			//	Editor background
			if ($settings_row == 'Background')
			{
				$html .=  '<select name="'.$this->format_setting($settings_row).'" id="'.$this->format_setting($settings_row).'">';
				foreach ($background_array as $background_row) {
					if ($option == $background_row) $select = $selected_html;
					else $select = '';
					$html .=  '<option value="'.$background_row.'"'.$select.'>'.$background_row.'</option>';
				}
				$html .=  '</select>';
			}
			
			//	Should we show word count?
			if ($settings_row == 'Show Word Count')
			{
				$count = count($show_word_count_array) - 1;
				foreach ($show_word_count_array as $show_word_count_row) {
					if ($option == $count) $check = $checked_html;
					else $check = '';
					$html .=  '<label><input type="radio" name="'.$this->format_setting($settings_row).'" id="'.$this->format_setting($settings_row).'" value="'.$count.'"'.$check.' /> <span>'.$show_word_count_row.'</span></label><br />';
					$count = $count - 1;
				}
			}
			
			//	Should we Open Automatically?
			if ($settings_row == 'Open Automatically')
			{
				$count = count($open_automatically_array) - 1;
				foreach ($open_automatically_array as $open_automatically_row) {
					if ($option == $count) $check = $checked_html;
					else $check = '';
					$html .=  '<label><input type="radio" name="'.$this->format_setting($settings_row).'" id="'.$this->format_setting($settings_row).'" value="'.$count.'"'.$check.' /> <span>'.$open_automatically_row.'</span></label><br />';
					$count = $count - 1;
				}
			}
			
			//	Should we open in visual or HTML?
			if ($settings_row == 'Open In')
			{
				foreach ($open_in_array as $open_in_row) {
					if ($option == $this->format_setting($open_in_row)) $check = $checked_html;
					else $check = '';
					$html .=  '<label><input type="radio" name="'.$this->format_setting($settings_row).'" id="'.$this->format_setting($settings_row).'" value="'.$this->format_setting($open_in_row).'"'.$check.' /> <span>'.$open_in_row.'</span></label><br />';
					$count = $count - 1;
				}
			}
			$html .=  '
						</div>
						<div class="clear"></div>
					</div>';
					
	}
		endforeach;
		//	End loop
		
		$html .=  '<div class="artsy-cancel-submit">
						<button type="button" class="artsy-cancel" id="cancel-settings">
							<div>Cancel</div>
						</button>
						<button type="submit" name="'.$this->prefix.'submit" id="submit" class="artsy-submit">
							<div>Save!</div>
						</button>
					</div>';
		
		//	Nonce
		$nonce = wp_nonce_field('artsy-action', 'artsy-nonce', TRUE, FALSE);
		$html .= $nonce;
		return $html;
	}
	
	/**
	 * Add to WP Admin Head
	 *
	 * @access private
	 * @return void
	**/
	public function settings_in_wp_admin()
	{
		$show_word_count = get_option($this->prefix.$this->format_setting('Show Word Count'), 1);
		$background = get_option($this->prefix.$this->format_setting('Background'), 'white');
		$artsy_mode = get_option($this->prefix.$this->format_setting('Open In'), 'visual');
		?><script type="text/javascript">
		var artsy_plugin_path = '<?php echo $this->url_public; ?>';
		var artsy_show_word_count = '<?php echo $show_word_count; ?>';
		var artsy_background = '<?php echo sanitize_title_with_dashes ($background); ?>';
		var artsy_editor_mode = '<?php echo $artsy_mode; ?>';
		</script><?php
	}
	
	/**
	 * Perform an automatic update
	 *
	 * @access private
	 * @return
	**/
	public function perform_update()
	{
		//	We need to create a download session.
		$create_url = wp_remote_get($this->remote_check . 'create_download.php?'.$this->remote_params());
		$response_code = $create_url['response']['code'];
		
		//	An invalid license, maybe?
		if ($response_code !== 200)
			return FALSE;
		
		//	Now that we have a session, let's get the download.
		$create_url_array = json_decode($create_url['body']);
		
		$download_url = $create_url_array->download_url;
		$filename = $create_url_array->filename;
		
		//	Load the WordPress Plugin Updater
		include ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		
		//	Setup the strings
		$skin = new Artsy_Skin();
		$skin->strings['up_to_date'] = __('The plugin is at the latest version.');
		$skin->strings['no_package'] = __('Update package not available.');
		$skin->strings['downloading_package'] = __('Downloading update&#8230;');
		$skin->strings['unpack_package'] = __('Unpacking the update&#8230;');
		$skin->strings['installing_package'] = __('Installing the plugin.');
		$skin->strings['deactivate_plugin'] = __('Deactivating the plugin&#8230;');
		$skin->strings['remove_old'] = __('Removing the old version of the plugin&#8230;');
		$skin->strings['remove_old_failed'] = __('Could not remove the old plugin.');
		$skin->strings['process_failed'] = __('Plugin update failed.');
		$skin->strings['process_success'] = __('Plugin updated successfully.');
		
		//	Setting up the update class
		$upgrader = new Plugin_Upgrader($skin);
		$run = array(
			'package' => $download_url,
					'destination' => WP_PLUGIN_DIR,
					'clear_destination' => true,
					'clear_working' => true,
					'hook_extra' => array(
								//'plugin' => $plugin
					)
				);
				
		return $upgrader->run($run);
	}
}

/**
 * Served no purpose to move this into the class. It's so that you add the valid elements.
 *
 * @access public
 * @param array
 * @return array
**/
function tiny_mce_allowed_tags($settings) {
	$settings['extended_valid_elements'] = 'iframe[id|class|title|style|align|frameborder|height|longdesc|marginheight|marginwidth|name|scrolling|src|width]';
	return $settings;
}
function artsy_smart_link($text) {
	
	$new_window_html = ' target="_blank"';
	$regular_link_new_window = $twitter_username_new_window = $twitter_hashtag_link_new_window = $email_address_link_new_window = '';
	if (get_option('regular_link_new_window' == 1)) $regular_link_new_window = $new_window_html;
	if (get_option('twitter_username_new_window' == 1)) $twitter_username_new_window = $new_window_html;
	if (get_option('twitter_hashtag_link_new_window' == 1)) $twitter_hashtag_link_new_window = $new_window_html;
	if (get_option('email_address_link_new_window' == 1)) $email_address_link_new_window = $new_window_html;
	$text = preg_replace('/((http)+(s)?:\/\/[^<>\s]+)/i', '<a "'.$new_window_html.'" href="\\0">\\0</a>', $text);
	$text = preg_replace('/[@]+([A-Za-z_0-9]+)/', '@<a "'.$new_window_html.'" href="http://twitter.com/#!/\\1">\\1</a>', $text);
	$text = preg_replace('/[#]+([A-Za-z_0-9]+)/', '<a "'.$new_window_html.'" href="http://twitter.com/#!/search/%23\\1">#\\1</a>', $text);
	$text = preg_replace('`([-_a-z0-9]+(\.[-_a-z0-9]+)*@[-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]{2,6})`i', '<a "'.$new_window_html.'" href="mailto:\\1">\\1</a>', $text);
	$text = preg_replace('#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i', "$1$3</a>", $text);
	return $text;
		
}

function artsy_get_current_browser() {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
	
	if ($is_lynx) $browser = 'lynx';
	elseif ($is_gecko) $browser = 'gecko';
	elseif ($is_opera) $browser = 'opera';
	elseif ($is_NS4) $browser = 'ns4';
	elseif ($is_safari) $browser = 'safari';
	elseif ($is_chrome) $browser = 'chrome';
	elseif ($is_IE) $browser = 'ie';
	elseif ($is_iphone) $browser = 'iphone';
	else $browser = 'unknown';
	return $browser;
	
}
function artsy_get_current_os() {
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Win') !== false) return 'windows';
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mac') !== false) return 'mac';
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Linux') !== false) return 'linux';
}
function artsy_get_current_browser_class() {
	return 'browser-'.artsy_get_current_browser();
}
function artsy_get_current_os_class() {
	return 'os-'.artsy_get_current_os();
}

/**
 * The class we use for strings with the updater
 *
 * @since 0.7.5
 */
class Artsy_Skin {

	var $upgrader;
	var $done_header = false;
	var $result = false;

	function __construct($args = array()) {
		$defaults = array( 'url' => '', 'nonce' => '', 'title' => '', 'context' => false );
		$this->options = wp_parse_args($args, $defaults);
	}

	function set_upgrader(&$upgrader) {
		if ( is_object($upgrader) )
			$this->upgrader =& $upgrader;
		$this->add_strings();
	}

	function add_strings() {
	}

	function set_result($result) {
		$this->result = $result;
	}

	function request_filesystem_credentials($error = false) {
		$url = $this->options['url'];
		$context = $this->options['context'];
		if ( !empty($this->options['nonce']) )
			$url = wp_nonce_url($url, $this->options['nonce']);
		return request_filesystem_credentials($url, '', $error, $context); //Possible to bring inline, Leaving as is for now.
	}

	function header() {
		if ( $this->done_header )
			return;
		$this->done_header = true;
		echo '<div class="artsy_update_wrap_box">';
		//echo screen_icon();
		//echo '<h2>' . $this->options['title'] . '</h2>';
	}
	function footer() {
		echo '<a href="'.admin_url('plugins.php?page=artsy_editor').'">Go to Settings</a></div>';
	}

	function error($errors) {
		if ( ! $this->done_header )
			$this->header();
		if ( is_string($errors) ) {
			$this->feedback($errors);
		} elseif ( is_wp_error($errors) && $errors->get_error_code() ) {
			foreach ( $errors->get_error_messages() as $message ) {
				if ( $errors->get_error_data() )
					$this->feedback($message . ' ' . $errors->get_error_data() );
				else
					$this->feedback($message);
			}
		}
	}

	function feedback($string) {
		if ( isset( $this->strings[$string] ) )
			$string = $this->strings[$string];

		if ( strpos($string, '%') !== false ) {
			$args = func_get_args();
			$args = array_splice($args, 1);
			if ( !empty($args) )
				$string = vsprintf($string, $args);
		}
		if ( empty($string) )
			return;
		show_message($string);
	}
	function before() {}
	function after() {}

}

?>