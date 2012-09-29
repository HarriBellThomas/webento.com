<?php
/*
Plugin Name: Wordpress clean URLs
Plugin URI: http://mindblazetech.com
Description: Convert URLS like /wp-admin => /admin  and /wp-signup => /signup etc
Version: 0.1
Author: <code>Mindblaze Tech</code>
Author URI: http://mindblazetech.com
License: This plugin is licensed under the GNU General Public License.
*/


class rewrite_wordpress_urls {

	function __construct() {
		add_action( 'init', array(&$this, '_init') );
		//add_filter( 'query_vars', array(&$this, '_query_vars') );
	}
	
	function _query_vars( $vars ) {
		$vars[] = 'agenda';
		$vars[] = 'agenda_team';
		$vars[] = 'agenda_page_num';
		$vars[] = 'agenda_action';
		return $vars;
	}
	
	function _init() {
		add_rewrite_rule( '^admin/?$', '/wp-admin.php', "top" );
		add_rewrite_rule( '^hassan', '/wp-login.php', "top" );
		add_rewrite_rule( '^signup/?$', '/wp-signup.php', "top" );
	}
}

new rewrite_wordpress_urls;