<?php

	/*

		Plugin Name: Microdata for SEO by Optimum7.com

		Plugin URI: http://www.optimum7.com/internet-marketing/web-development/microdata-for-wordpress.html?utm_source=MicrodataPlugin

		Description:This plugins allows you to add Microdata elements to your pages and posts on your blog.

		Version: 1.3.3

		Author: Optimum7

		Author URI: http://www.optimum7.com/

		Copyright 2011  Optimum7 Inc  (email : julian@optimum7.com)

		This program is free software; you can redistribute it and/or modify

		it under the terms of the GNU General Public License, version 2, as 

		published by the Free Software Foundation.

		This program is distributed in the hope that it will be useful,

		but WITHOUT ANY WARRANTY; without even the implied warranty of

		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License

		along with this program; if not, write to the Free Software

		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

	*/

	

	define('OPT7_MFW_PLUGINPATH', (DIRECTORY_SEPARATOR != '/') ? str_replace(DIRECTORY_SEPARATOR, '/', dirname(__FILE__)) : dirname(__FILE__));

	define('OPT7_MFW_PLUGINNAME', 'Microdata for SEO by Optimum7.com');

	define('OPT7_MFW_SOURCE_CODE', '?utm_source=MicrodataPlugin');

	define('OPT7_MFW_PLUGIN_PATH_SUPPORT', 'http://www.optimum7.com/internet-marketing/web-development/microdata-for-wordpress.html'.OPT7_MFW_SOURCE_CODE);

	define('OPT7_MFW_PLUGIN_PATH', get_bloginfo('wpurl').'/wp-content/plugins/microdata-for-seo-by-optimum7com');

	

	_microdata_for_wordpress::bootstrap(); 

	add_action('wp_head', '_microdata_for_wordpress_local_css');

	add_action( 'media_buttons', 'amd_microdata_add_button',30);

	

	if (strpos($_SERVER['REQUEST_URI'], 'media-upload.php') && strpos($_SERVER['REQUEST_URI'], '&type=amd_microdata') && !strpos($_SERVER['REQUEST_URI'], '&wrt=')){

		amd_microdata_iframe_content($_POST, $_REQUEST);

		exit;

	}

	

	function place_wp_microdata_for_wordpress_form($content){	

		$pattern ="/\[Opt7_.*\]/";

		$m = preg_match_all ($pattern, $content, $match);

		if ($m) { 

			$codes=$match[0];		

			for ($j=0;$j<$m;$j++) { 

				$codes[$j] = str_replace('[','',$codes[$j]);

				$codes[$j] = str_replace(']','',$codes[$j]);

				$content =str_replace($codes[$j],get_option($codes[$j]),$content); 

			}

			$content = str_replace('[','',$content);

			$content = str_replace(']','',$content);

		}

		return $content;

	}	 	

	

	function _microdata_for_wordpress_local_css() {

		wp_enqueue_script('jquery');

		echo '<link type="text/css" rel="stylesheet" href="' .OPT7_MFW_PLUGIN_PATH.'/microdata.css" />' . "\n";

	}

	

	function amd_microdata_add_button() {

		global $post_ID, $temp_ID;

		$uploading_iframe_ID = (int) (0 == $post_ID ? $temp_ID : $post_ID);

		$media_upload_iframe_src = get_option('siteurl').'/wp-admin/media-upload.php?post_id='.$uploading_iframe_ID;		

		$media_amd_microdata_iframe_src = apply_filters('media_amd_microdata_iframe_src', "$media_upload_iframe_src&amp;type=amd_microdata&amp;tab=amd_microdata");

		$media_amd_microdata_title = __(OPT7_MFW_PLUGINNAME, 'wp-media-amd_microdata');

		echo "<a class=\"thickbox\" href=\"{$media_amd_microdata_iframe_src}&amp;TB_iframe=true\" title=\"$media_amd_microdata_title\"><img src='" .OPT7_MFW_PLUGIN_PATH. "/images/icon.gif' alt='Optimum7 Icon' /></a>";

	}



	/********************************************************************************************************************/	

	class _microdata_for_wordpress{ 

		function bootstrap(){ 

			// Add the installation and uninstallation hooks 

			$file = OPT7_MFW_PLUGINPATH . '/' . basename(__FILE__);	

			register_deactivation_hook($file, array('_microdata_for_wordpress', 'uninstall'));

			add_filter('the_content', 'place_wp_microdata_for_wordpress_form', '7');

			add_filter('widget_text', 'place_wp_microdata_for_wordpress_form', '7');

		}

		function uninstall(){

			//Remove all options created by this plugin from DB

			global $wpdb;

			$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name like '%Opt7_Microdata%'" );

		} 

	}// class ends here

	

	/********************************************************************************************************************/		 

	function amd_microdata_iframe_content($post_info = null, $get_info = null) {

		$id = (int) $get_info["post_id"];

		$url = get_option('siteurl');

		$dirname = dirname(plugin_basename(__FILE__));

		echo '<script> var url = "'.get_bloginfo('wpurl').'/wp-content/plugins/microdata-for-seo-by-optimum7com/ajax.php"</script>';	

		echo <<< HTML

		<!DOCTYPE html>

		<head>

		<link rel="stylesheet" href="$url/wp-content/plugins/$dirname/microdata.css" type="text/css" media="all" />

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>

        

		<script type="text/javascript">//<!CDATA[

			var globalCount = 0;

			$(document).ready(function(){

				$('#middle-notify').hide();

				load_classes();

				$('#table #fields input').live('blur', function(e){

				var checklist='';

				checklist = checkvalues();

				checklist = checklist.substring(0, checklist.length-1);

				$.ajax({

					type: "POST",

					url: url,

					cache: false,

					data: "commit=preload_html5&class="+$('#type').val()+"&values="+checklist,

					success: function(html5){

						$('#_html5').val(html5);

					},

				});

			  });

		   });

		

			function checkvalues(){

				var checklist='';

				var all_values = $('#table #fields input');

				with(all_values){

					for(var i = 0; i < all_values.length; i++){

						checklist += all_values[i].value + ";";

					}

				}

				return checklist;

			}

					

			function load_classes(){

				$.ajax({

					type: "POST",

					url: url,

					cache: false,

					data: "commit=load_classes",

					success: function(ajaxResutl){

						$( "#Opt7-microdata-admin-selectbox" ).html(ajaxResutl);

						load_schema();

					},

				});

			}

		

			function load_schema(type){

				if (!type) var type = $("#Opt7-microdata-admin-selectbox option:selected").text();

				if (type){

					$('#middle-notify').show();

					$.ajax({

						type: "POST",

						url: url,

						cache: false,

						data: "commit=load&type="+type,

						success: function(ajaxResutl){

							$('#table').html(ajaxResutl); 

							$('#middle-notify').hide(); 

							$("#add-microdata-button").val('Save ' + $("#Opt7-microdata-admin-selectbox option:selected").text());

						},

					});

				}

			}

		

			function amdSubmitForm(microdata) {

				window.parent.amdMicrodataInsertIntoPostEditor(microdata);  

			}

		

			function add_microdata(){

				$.ajax({

					type: "POST",

					url: url,

					cache: false,

					data: "commit=save&class="+$('#type').val()+"&id="+$id+"&html5="+$('#_html5').val(),

					success: function(microdata){

						amdSubmitForm(microdata);

						window.parent.tb_remove();

					},

				});

			}

		</script>

</head><body id="mfw-microdata-uploader">

<div style="margin-top:20px;margin-left:20px;">

<div id='Opt7-microdata-admin-selectbox'></div>

<div id="middle-notify" class="" style="text-align:center">

<img src='images/loading.gif' alt="loading"/>

</div>

<div id="table" style="margin-top:0px;height:auto"></div>

</div>

</body>

HTML;

}

function amd_microdata_plugin_footer() {

$url = get_option('siteurl');

$dirname = dirname(plugin_basename(__FILE__));

echo <<< HTML



<style type="text/css" media="screen">

</style>

<script>//<![CDATA[

var baseurl = '$url';

var dirname = '$dirname';

function amdMicrodataInsertIntoPostEditor(microdata) {

var ed;

if ( typeof tinyMCE != 'undefined' && ( ed = tinyMCE.activeEditor ) && !ed.isHidden() ) {

ed.focus();

if ( tinymce.isIE )

ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);

ed.execCommand('mceInsertContent', false, '['+microdata+']');

} else if ( typeof edInsertContent == 'function' ) {

edInsertContent(edCanvas, '['+microdata+']');

} else {

jQuery( edCanvas ).val( jQuery( edCanvas ).val() +  '['+microdata+']' );

}

}

//]]></script>

HTML;

}

add_action('admin_footer', 'amd_microdata_plugin_footer');