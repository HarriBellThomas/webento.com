<?php
/*
Plugin Name: Admin Bar Removal
Plugin URI: http://slangji.wordpress.com/wp-admin-bar-removal/
Description: Completely <code>Disable</code> new WordPress 3.1+ / 3.2+ / 3.3+ (only) <code>Admin Bar</code> Frontend and Backend. Work under GPLv2 License. Completely <code>Remove Code</code> for minimal memory load. | <a href="http://lcsn.net/donate/" title="Free Donation">Donate</a> | <a href="http://wordpress.org/extend/plugins/wp-overview-lite/" title="Show Dashboard Overview and Footer Memory Load Usage">WP Overview?</a> | <a href="http://wordpress.org/extend/plugins/wp-missed-schedule/" title="Fix Missed Scheduled Future Posts Cron Job">WP Missed Schedule?</a> | <a href="http://wordpress.org/extend/plugins/wp-admin-bar-removal/" title="Remove Admin Bar Frontend Backend User Profile and Code">Admin Bar Removal?</a> | <a href="http://wordpress.org/extend/plugins/wp-admin-bar-node-removal/" title="Remove Admin Bar Frontend and Backend Node">Admin Bar Node Removal?</a> | <a href="http://wordpress.org/extend/plugins/wp-toolbar-removal/" title="Remove ToolBar Frontend Backend User Profile and Code">ToolBar Removal?</a> | <a href="http://wordpress.org/extend/plugins/wp-toolbar-node-removal/" title="Remove ToolBar Frontend and Backend Node">ToolBar Node Removal?</a> | <a href="http://wordpress.org/extend/plugins/wp-login-deindexing/" title="Total DeIndexing WordPress LogIn from all Search Engines">LogIn DeIndexing?</a> | <a href="http://wordpress.org/extend/plugins/wp-total-deindexing/" title="Total DeIndexing WordPress from all Search Engines">WP DeIndexing?</a> | <a href="http://wordpress.org/extend/plugins/wp-ie-enhancer-and-modernizer/" title="Enhancer and Modernizer IE Surfing Expirience">Enhancer IE Surfing?</a>
Version: 2012.0320.2012
Author: sLa
Author URI: http://slangji.wordpress.com/
Requires at least: 3.1
Tested up to: 3.3.2
License: GPLv2
 *
 * Development Release: Version 2012 Build 0321-BUGFIX Revision 0000-DEVELOPMENTAL
 *
 * [Admin Bar Removal](http://wordpress.org/extend/plugins/wp-admin-bar-removal/) WordPress PlugIn
 *
 *  This program is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation; either version 2
 *  of the License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 *  The license for this software can be found @ http://www.gnu.org/licenses/gpl-2.0.html
 *
 * This uses code derived from
 * wp-admin-bar-node-removal.php by sLa <slangji[at]gmail[dot]com>
 * according to the terms of the GNU General Public License version 2 (or later)
 *
 * Copyright © 2010-2012 [sLa](http://wordpress.org/extend/plugins/profile/slangji) (slangji[at]gmail[dot]com)
 */
/**
 * @package Admin Bar Removal
 * @subpackage WordPress PlugIn
 * @since 3.1.0
 * @version 2012.0320.2012
 * @author sLa
 * @license GPLv2
 *
 * Completely Disable Admin Bar Frontend, Backend, and Remove Code for minimal memory load.
 */
if(!function_exists('add_action')){header('Status 403 Forbidden');header('HTTP/1.0 403 Forbidden');header('HTTP/1.1 403 Forbidden');exit();};function wpabr_rac(){echo'<style type="text/css">body.admin-bar #wpcontent,body.admin-bar #adminmenu{padding-top:0px}</style>';};add_action('admin_print_styles','wpabr_rac',21);function wpabr_ruppoabpc(){echo'<style type="text/css">.show-admin-bar{display:none}</style>';};add_action('admin_print_styles-profile.php','wpabr_ruppoabpc');add_filter('init','wpabr_init');function wpabr_init(){add_filter('show_admin_bar','__return_false' );}wp_deregister_script('admin-bar');wp_deregister_style('admin-bar');remove_filter('wp_head','wp_admin_bar');remove_filter('wp_footer','wp_admin_bar');remove_filter('admin_head','wp_admin_bar');remove_filter('admin_footer','wp_admin_bar');remove_filter('wp_head','wp_admin_bar_class');remove_filter('wp_footer','wp_admin_bar_class');remove_filter('admin_head','wp_admin_bar_class');remove_filter('admin_footer','wp_admin_bar_class');remove_action('wp_head','wp_admin_bar_render',1000);remove_filter('wp_head','wp_admin_bar_render',1000);remove_action('wp_footer','wp_admin_bar_render',1000);remove_filter('wp_footer','wp_admin_bar_render',1000);remove_action('admin_head','wp_admin_bar_render',1000);remove_filter('admin_head','wp_admin_bar_render',1000);remove_action('admin_footer','wp_admin_bar_render',1000);remove_filter('admin_footer','wp_admin_bar_render',1000);remove_action('init','wp_admin_bar_init');remove_filter('init','wp_admin_bar_init');remove_action('wp_head','wp_admin_bar_css');remove_action('wp_head','wp_admin_bar_dev_css');remove_action('wp_head','wp_admin_bar_rtl_css');remove_action('wp_head','wp_admin_bar_rtl_dev_css');remove_action('admin_head','wp_admin_bar_css');remove_action('admin_head','wp_admin_bar_dev_css');remove_action('admin_head','wp_admin_bar_rtl_css');remove_action('admin_head','wp_admin_bar_rtl_dev_css');remove_action('wp_footer','wp_admin_bar_js');remove_action('wp_footer','wp_admin_bar_dev_js');remove_action('admin_footer','wp_admin_bar_js');remove_action('admin_footer','wp_admin_bar_dev_js');remove_action('wp_ajax_adminbar_render','wp_admin_bar_ajax_render');remove_filter('wp_ajax_adminbar_render','wp_admin_bar_ajax_render');remove_action('personal_options','_admin_bar_pref');remove_filter('personal_options','_admin_bar_pref');remove_action('personal_options','_get_admin_bar_pref');remove_filter('personal_options','_get_admin_bar_pref');remove_filter('locale','wp_admin_bar_lang');remove_filter('admin_footer','wp_admin_bar_render');if(!function_exists('hide_admin_bar_search')){function hide_admin_bar_search(){?><style type="text/css">#wpadminbar #adminbarsearch{display:none}</style><?php }add_action('admin_head', 'hide_admin_bar_search');add_action('wp_head', 'hide_admin_bar_search');}add_action('wp_before_admin_bar_render','wpabnr');function wpabnr(){global $wp_admin_bar;$wp_admin_bar->remove_menu('get-shortlink');$wp_admin_bar->remove_menu('dashboard');$wp_admin_bar->remove_menu('my-account-with-avatar');$wp_admin_bar->remove_menu('appearance');$wp_admin_bar->remove_menu('themes');$wp_admin_bar->remove_menu('widgets');$wp_admin_bar->remove_menu('menus');$wp_admin_bar->remove_menu('background');$wp_admin_bar->remove_menu('header');$wp_admin_bar->remove_menu('wrap');$wp_admin_bar->remove_menu('search');$wp_admin_bar->remove_menu('button');$wp_admin_bar->remove_menu('adminbarsearch');$wp_admin_bar->remove_menu('wp-logo');$wp_admin_bar->remove_menu('wp-logo-default');$wp_admin_bar->remove_menu('wp-logo-external');$wp_admin_bar->remove_menu('comments');$wp_admin_bar->remove_menu('about');$wp_admin_bar->remove_menu('wporg');$wp_admin_bar->remove_menu('documentation');$wp_admin_bar->remove_menu('support-forums');$wp_admin_bar->remove_menu('feedback');$wp_admin_bar->remove_menu('site-name');$wp_admin_bar->remove_menu('site-name-default');$wp_admin_bar->remove_menu('view-site');$wp_admin_bar->remove_menu('comments');$wp_admin_bar->remove_menu('new-content');$wp_admin_bar->remove_menu('new-content-default');$wp_admin_bar->remove_menu('new-post');$wp_admin_bar->remove_menu('new-media');$wp_admin_bar->remove_menu('new-link');$wp_admin_bar->remove_menu('new-page');$wp_admin_bar->remove_menu('new-user');$wp_admin_bar->remove_menu('updates');$wp_admin_bar->remove_menu('top-secondary');$wp_admin_bar->remove_menu('my-account');$wp_admin_bar->remove_menu('user-actions');$wp_admin_bar->remove_menu('user-info');$wp_admin_bar->remove_menu('edit-profile');$wp_admin_bar->remove_menu('logout');$wp_admin_bar->remove_menu('search');$wp_admin_bar->remove_menu('network-admin');$wp_admin_bar->remove_menu('w3tc');$wp_admin_bar->remove_menu('w3tc-default');$wp_admin_bar->remove_menu('w3tc-empty-caches');$wp_admin_bar->remove_menu('w3tc-faq');$wp_admin_bar->remove_menu('w3tc-support');$wp_admin_bar->remove_menu('cloudflare');$wp_admin_bar->remove_menu('cloudflare-default');$wp_admin_bar->remove_menu('cloudflare-my-websites');$wp_admin_bar->remove_menu('cloudflare-analytics');$wp_admin_bar->remove_menu('cloudflare-account');}function wpabr_footer_log(){echo"\n<!--Plugin Admin Bar Removal 2012.0320.2012 Active-->";};add_action('wp_head','wpabr_footer_log');add_action('wp_footer','wpabr_footer_log');?>