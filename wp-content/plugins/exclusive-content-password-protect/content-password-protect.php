<?php
/*
Plugin Name: Exclusive Content Password Protect
Plugin URI: http://www.cliconomics.com/exclusive-content-password-protect/
Description: Exclusive Content Password Protect is a plugin that allows you to hide a section (or multiple sections) of content on your page or post. Use this to create exclusive content that you only want email or RSS subscribers to have access to. Once they enter in the global password once, they will have access to all content from then on. Plus you can use passwords for certain sections to have even more exclusive content.
Version: 1.1.0
Author: Cliconomics
Author URI: http://www.cliconomics.com/exclusive-content-password-protect/
*/

/*
Exclusive Content Password Protect (Wordpress Plugin)
Copyright (C) 2011 Cliconomics
Contact me at http://www.cliconomics.com/exclusive-content-password-protect/

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

session_start();

$default_config = parse_ini_file('default-config.ini');

$ecpp_border_color = get_option('ecpp_border_color', $default_config['border_color']);
$ecpp_background_color = get_option('ecpp_background_color', $default_config['background_color']);
$ecpp_background_image = get_option('ecpp_background_image',  plugin_dir_url( __FILE__ ) . 'images/bg.jpg');
$ecpp_heading_color = get_option('ecpp_heading_color', $default_config['heading_color']);
$ecpp_footnote_color = get_option('ecpp_footnote_color', $default_config['footnote_color']);
$ecpp_width = get_option('ecpp_width', $default_config['width']);
$ecpp_height = get_option('ecpp_height', $default_config['height']);
$ecpp_font = get_option('ecpp_font', $default_config['font']);
$ecpp_heading_fontsize = get_option('ecpp_heading_fontsize', $default_config['heading_fontsize']);
$ecpp_footnote_fontsize = get_option('ecpp_footnote_fontsize', $default_config['footnote_fontsize']);
$ecpp_padding = get_option('ecpp_padding', $default_config['padding']);

$ecpp_heading_text = get_option('ecpp_heading_text', $default_config['heading_text']);
$ecpp_footnote_text = get_option('ecpp_footnote_text', $default_config['footnote_text']);
$ecpp_button_text = get_option('ecpp_button_text', $default_config['button_text']);
$ecpp_global_password = get_option('ecpp_global_password', $default_config['global_password']);

$cpp_heading_html = '<div class="cpp_heading" style="{TPLCPP_HEADINGSTYLE}">{TPLCPP_HEADINGTEXT}</div>';
$cpp_footnote_html = '<div class="cpp_footenote" style="{TPLCPP_FOOTNOTESTYLE}">{TPLCPP_FOOTNOTETEXT}</div>';
$cpp_html = '
<div class="cpp" style="{TPLCPP_CPP}">
	<div class="pad" style="{TPLCPP_PADDINGSTYLE}">
		{TPLCPP_HEADING}
		<div class="cpp_form">
			<form action="" method="post" class="cppForm">
				<input type="hidden" name="perm_hash" value="{TPLCPP_PERMHASH}" />
				<input type="text" name="email_password" />
				<input type="submit" name="show_protected_content" class="cpp_unlock_button" value="{TPLCPP_BUTTONTEXT}" />
			</form>
		</div>
		{TPLCPP_FOOTNOTE}
	</div>
	<div class="engineered ' . (is_admin() ? 'hide-engineer' : '') . '"><a href="http://www.cliconomics.com" target="_blank">Engineered by Cliconomics</a></div>
</div>
';

add_action('init', 'cpp_init');

function cpp_init() {
	//We'll be needing this plugin in front-end only
	if (!is_admin()) {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
		wp_enqueue_script( 'jquery' );
	}
}
 
add_action('wp_head', 'cpp_head_includes');

function cpp_head_includes(){	
	echo '<link rel="stylesheet" type="text/css" media="all" href="' .  plugin_dir_url( __FILE__ ) . 'css/style.css" />' . "\n";
}
 
add_action('wp_footer', 'cpp_footer_includes');

function cpp_footer_includes(){	
	echo '
<script type="text/javascript">
var $j = jQuery.noConflict();
	
jQuery(function(){
	$j(".cppForm").submit(function(event){
		//Will this prevent the form from being submitted? Since we want the form to submit using ajax.
		event.preventDefault();
		
		// organize the data properly. Conver all form fields to url query format
		var data = $j(this).serialize();
		
		$j.ajax({
		    url: "' . plugin_dir_url( __FILE__ ) . 'ajax.php", 
		     
		    type: "POST",

		    //pass the data         
		    data: data,     
		     
		    //Do not cache the page
		    cache: false,
		     
		    //success
		    success: function (responseText) {
				if(responseText.ecpp_auth == 1)
					location.reload(true);
				else
					alert("Incorrect password. Please try again.");   
		    },
		    
		    dataType: "json"
		});
		
		return false;
	});
});
</script>
	';
}

add_action('admin_head', 'cpp_adminhead_includes');

function cpp_adminhead_includes(){
	echo '<link rel="stylesheet" type="text/css" media="all" href="' .  plugin_dir_url( __FILE__ ) . 'css/style.css" />' . "\n";
	echo '<link rel="stylesheet" type="text/css" media="all" href="' .  plugin_dir_url( __FILE__ ) . 'css/admin_style.css" />' . "\n";
	echo '<link rel="stylesheet" media="screen" type="text/css" href="' .  plugin_dir_url( __FILE__ ) . 'css/colorpicker.css" />';
	echo '<script type="text/javascript" src="' .  plugin_dir_url( __FILE__ ) . 'js/colorpicker.js"></script>';
	echo '
	<script type="text/javascript">
		var $j = jQuery.noConflict();
			
		jQuery(function(){
			$j(".cpicker").ColorPicker({
				onShow: function (colpkr) {
					$j(this).ColorPickerSetColor($(this).val());
					$j(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					$j(colpkr).fadeOut(500);
					return false;
				},
				onSubmit: function(hsb, hex, rgb, el) {
					$j(el).val("#"+hex);
					$j(el).ColorPickerHide();
				}
			});
			
			$j("#cpp_upload_image").click(function(){
				$j("#cpp_bg_upload_wrap").slideToggle();
			});
		});
	</script>
	';
}

// create custom plugin settings menu
add_action('admin_menu', 'cpp_create_menu');

function cpp_create_menu() {
	//create new top-level menu
	add_menu_page('Content Password Protect Plugin Settings', 'Content Protect', 'administrator', __FILE__, 'cpp_settings_page', plugins_url('/images/icon-small.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'register_cppsettings' );
}


function register_cppsettings() {
	//register our settings
	register_setting( 'cpp-settings-group', 'ecpp_border_color' );
	register_setting( 'cpp-settings-group', 'ecpp_background_color' );
	register_setting( 'cpp-settings-group', 'ecpp_background_image' );
	register_setting( 'cpp-settings-group', 'ecpp_heading_color' );
	register_setting( 'cpp-settings-group', 'ecpp_footnote_color' );
	register_setting( 'cpp-settings-group', 'ecpp_width' );
	register_setting( 'cpp-settings-group', 'ecpp_height' );
	register_setting( 'cpp-settings-group', 'ecpp_font' );
	register_setting( 'cpp-settings-group', 'ecpp_heading_fontsize' );
	register_setting( 'cpp-settings-group', 'ecpp_footnote_fontsize' );
	register_setting( 'cpp-settings-group', 'ecpp_padding' );
	
	register_setting( 'cpp-settings-group', 'ecpp_heading_text' );
	register_setting( 'cpp-settings-group', 'ecpp_footnote_text' );
	register_setting( 'cpp-settings-group', 'ecpp_button_text' );
	register_setting( 'cpp-settings-group', 'ecpp_global_password' );
}

function parse_template($cpp_html){
	global $ecpp_border_color,
			$ecpp_background_color,
			$ecpp_background_image,
			$ecpp_heading_color,
			$ecpp_footnote_color,
			$ecpp_width,
			$ecpp_height,
			$ecpp_font,
			$ecpp_heading_fontsize,
			$ecpp_footnote_fontsize,
			$ecpp_heading_text,
			$ecpp_footnote_text,
			$ecpp_button_text,
			$cpp_heading_html,
			$cpp_footnote_html,
			$ecpp_global_password,
			$ecpp_padding;	
	
	$cpp_style = array();
	$cpp_style[] = "background: url($ecpp_background_image)";
	$cpp_style[] = "width: {$ecpp_width}px";
	$cpp_style[] = "height: {$ecpp_height}px";
	$cpp_style[] = "font: $ecpp_font";
	if(!empty($ecpp_background_color)) $cpp_style[] = "background-color: $ecpp_background_color";
	if(!empty($ecpp_border_color)) $cpp_style[] = "border: 1px solid $ecpp_border_color";

	$heading_style = array(
		"font-size: {$ecpp_heading_fontsize}px",
		"color: $ecpp_heading_color"
	);
	
	$footnote_style = array(
		"font-size: {$ecpp_footnote_fontsize}px",
		"color: $ecpp_footnote_color"
	);
	
	$cpp_html = str_replace('{TPLCPP_CPP}', implode(";", $cpp_style), $cpp_html);
	$cpp_heading_html = str_replace('{TPLCPP_HEADINGSTYLE}', implode(";", $heading_style), $cpp_heading_html);
	$cpp_heading_html = str_replace('{TPLCPP_HEADINGTEXT}', $ecpp_heading_text, $cpp_heading_html);
	$cpp_footnote_html = str_replace('{TPLCPP_FOOTNOTESTYLE}', implode(";", $footnote_style), $cpp_footnote_html);
	$cpp_footnote_html = str_replace('{TPLCPP_FOOTNOTETEXT}', $ecpp_footnote_text, $cpp_footnote_html);
	$cpp_html = str_replace('{TPLCPP_HEADING}', $cpp_heading_html, $cpp_html);
	$cpp_html = str_replace('{TPLCPP_FOOTNOTE}', $cpp_footnote_html, $cpp_html);
	$cpp_html = str_replace('{TPLCPP_BUTTONTEXT}', $ecpp_button_text, $cpp_html);
	$cpp_html = str_replace('{TPLCPP_PADDINGSTYLE}', 'padding: ' . $ecpp_padding . 'px', $cpp_html);
	
	return $cpp_html;
}

function cpp_settings_page() {
	global $ecpp_border_color,
			$ecpp_background_color,
			$ecpp_background_image,
			$ecpp_heading_color,
			$ecpp_footnote_color,
			$ecpp_width,
			$ecpp_height,
			$ecpp_font,
			$ecpp_heading_fontsize,
			$ecpp_footnote_fontsize,
			$ecpp_heading_text,
			$ecpp_footnote_text,
			$ecpp_button_text,
			$cpp_html,
			$cpp_heading_html,
			$cpp_footnote_html,
			$ecpp_padding,
			$ecpp_global_password;
?>
<div class="wrap">
<div id="icon-cpp" class="icon32"><br></div>
<h2>Exclusive Content Password Protect Settings</h2>

<h3>Preview</h3>
<?php echo parse_template($cpp_html) ?>

<?php
if(!empty($_FILES['userfile'])){
	$uploads_dir = '../wp-content/uploads/';
	
	if(!is_writable($uploads_dir))
		echo '<p class="error">Error uploading file. "/wp-content/uploads/" folder is not writable. Please change the permission to 777.</p>';
	else
		move_uploaded_file($_FILES['userfile']['tmp_name'], $uploads_dir.$_FILES['userfile']['name']);
}
?>
<div class="hide" id="cpp_bg_upload_wrap">
	<form enctype="multipart/form-data" action="" method="post">
		Upload background: <input name="userfile" type="file" />
		<input type="submit" value="Upload" />
	</form>
</div>

<form method="post" action="options.php">
    <table class="">
        <tr valign="top">
		    <td colspan="2"><h3>Design</h3></td>
        </tr>
        
        <tr valign="top">
		    <td scope="row">Border color:</td>
		    <td>
		    	<input type="text" name="ecpp_border_color" id="border_color" value="<?php echo $ecpp_border_color ?>" class="cpicker" />
	    	</td>
        </tr>
         
        <tr valign="top">
		    <td scope="row">Background color:</td>
		    <td><input type="text" name="ecpp_background_color" value="<?php echo $ecpp_background_color ?>" class="cpicker" /></td>
        </tr>
        
        <tr valign="top">
		    <td scope="row">Background image:</td>
		    <td>
		    	<div class="left"><input type="text" name="ecpp_background_image" value="<?php echo !empty($_FILES['userfile']) ? get_bloginfo('wpurl') . '/wp-content/uploads/' . $_FILES['userfile']['name'] : $ecpp_background_image ?>" /></div>
	    		<div id="cpp_upload_image" title="Upload image"></div>
	    		<div class="clear"></div>
    		</td>
        </tr>
         
        <tr valign="top">
		    <td scope="row">Heading color:</td>
		    <td><input type="text" name="ecpp_heading_color" value="<?php echo $ecpp_heading_color ?>" class="cpicker" /></td>
        </tr>
        
        <tr valign="top">
		    <td scope="row">Footnote color:</td>
		    <td><input type="text" name="ecpp_footnote_color" value="<?php echo $ecpp_footnote_color ?>" class="cpicker" /></td>
        </tr>
         
        <tr valign="top">
		    <td scope="row">Width (px):</td>
		    <td><input type="text" name="ecpp_width" value="<?php echo $ecpp_width ?>" class="input-xsmall" /></td>
        </tr>
        
        <tr valign="top">
		    <td scope="row">Height (px):</td>
		    <td><input type="text" name="ecpp_height" value="<?php echo $ecpp_height ?>" class="input-xsmall" /></td>
        </tr>
        
        <tr valign="top">
		    <td scope="row">Padding (px):</td>
		    <td><input type="text" name="ecpp_padding" value="<?php echo $ecpp_padding ?>" class="input-xsmall" /></td>
        </tr>
         
        <tr valign="top">
		    <td scope="row">Font:</td>
		    <td>
		    	<select name="ecpp_font" id="font">
		    		<option value="Arial" <?php echo $ecpp_font == 'Arial' ? 'selected="selected"' : '' ?>>Arial</option>
		    		<option value="Tahoma" <?php echo $ecpp_font == 'Tahoma' ? 'selected="selected"' : '' ?>>Tahoma</option>
		    		<option value="Verdana" <?php echo $ecpp_font == 'Verdana' ? 'selected="selected"' : '' ?>>Verdana</option>
		    		<option value="'Lucida Grande'" <?php echo $ecpp_font == "'Lucida Grande'" ? 'selected="selected"' : '' ?>>Lucida Grande</option>
		    		<option value="sans-serif" <?php echo $ecpp_font == 'sans-serif' ? 'selected="selected"' : '' ?>>sans-serif</option>
		    		<option value="'Bitstream Vera Sans'" <?php echo $ecpp_font == "'Bitstream Vera Sans'" ? 'selected="selected"' : '' ?>>Bitstream Vera Sans</option>
		    	</select>
	    	</td>
        </tr>
        
        <tr valign="top">
		    <td scope="row">Heading font size (px):</td>
		    <td><input type="text" name="ecpp_heading_fontsize" value="<?php echo $ecpp_heading_fontsize ?>" class="input-xsmall" /></td>
        </tr>
        
        <tr valign="top">
		    <td scope="row">Footnote font size (px):</td>
		    <td><input type="text" name="ecpp_footnote_fontsize" value="<?php echo $ecpp_footnote_fontsize ?>" class="input-xsmall" /></td>
        </tr>
        
        <tr valign="top">
		    <td colspan="2"><h3>Text</h3></td>
        </tr>
        
        <tr valign="top">
		    <td scope="row">Heading text:</td>
		    <td><input type="text" name="ecpp_heading_text" value="<?php echo $ecpp_heading_text ?>" class="input-large" /></td>
        </tr>
         
        <tr valign="top">
		    <td scope="row">Footnote text:</td>
		    <td><input type="text" name="ecpp_footnote_text" value="<?php echo $ecpp_footnote_text ?>" class="input-large" /></td>
        </tr>
        
        <tr valign="top">
		    <td scope="row">Button text:</td>
		    <td><input type="text" name="ecpp_button_text" value="<?php echo $ecpp_button_text ?>" /></td>
        </tr>
        
        <tr valign="top">
		    <td scope="row">Global Password:</td>
		    <td><input type="text" name="ecpp_global_password" value="<?php echo $ecpp_global_password ?>" /></td>
        </tr>
    </table>
    
    <?php settings_fields( 'cpp-settings-group' ); ?>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php } 

add_shortcode("password-protect", "ecpp_handler");

function ecpp_handler($atts, $content = null) {
	global $cpp_html, $ecpp_global_password;

	extract(
		shortcode_atts(
			array(
				"password" => $ecpp_global_password
			),
			$atts
		)
	);
	
	/*
	 * Hash the permalink of current page and use it as unique variable for the password and store it in session. 
	 * We're doing this because each page that is password protetected will have to store the password of that page to session.
	 * Then we'll retrieve this password in ajax call and compare it with the password entered by user once the 
	 * Show Content button is pressed.
	 */
	$perm_hash = md5(get_permalink()) . md5($content);
	$_SESSION[$perm_hash]['password'] = $password;
	$_SESSION['global_password'] = $ecpp_global_password;

	/*
	 * If cookie is not set for this page or the current cookie set for this page is not the same as what is specified 
	 * in shortcode then we show the form to enter password.
	 */
	if(!isset($_COOKIE["cpp-global-password"])){
		$content = str_replace('{TPLCPP_PERMHASH}', $perm_hash, parse_template($cpp_html));
	}

	return $content;
}
?>
