<?php
/*
Plugin Name: Google Analytics
Plugin URI: http://premium.wpmudev.org/project/google-analytics-for-wordpress-mu-sitewide-and-single-blog-solution/
Description: It's great to offer your users Google Analytics, but it's even better to be able to offer it to individual bloggers AND at the same time capture sitewide stats for yourself!
Author: Ivan Shaovchev, Andrey Shipilov, Hakan Evin (Incsub)
Author URI: http://premium.wpmudev.org
Version: 2.1
WDP ID: 51
License: GNU General Public License (Version 2 - GPLv2)
*/

/*
Copyright 2007-20112 Incsub (http://incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

include_once 'class-google-analytics-async-reports.php';

/**
 * Google_Analytics_Async
 *
 * @package Google Analytics
 * @copyright Incsub 2007-2011 {@link http://incsub.com}
 * @author Ivan Shaovchev (Incsub) {@link http://ivan.sh}
 * @license GNU General Public License (Version 2 - GPLv2) {@link http://www.gnu.org/licenses/gpl-2.0.html}
 */
class Google_Analytics_Async extends Google_Analytics_Async_Reports {

    /** @var string $text_domain The text domain of the plugin */
    var $text_domain = 'ga_trans';
    /** @var string $plugin_dir The plugin directory path */
    var $plugin_dir;
    /** @var string $plugin_url The plugin directory URL */
    var $plugin_url;
    /** @var string $domain The plugin domain */
    var $domain;
    /** @var string $options_name The plugin options string */
    var $options_name = 'ga2_settings';
    /** @var array $settings The plugin options */
    var $settings;
    /** @var array  The plugin default feeds*/
    var $default_feeds = array( '_page_visits', '_page_views', '_avg_time_on_page', '_page_direct_traffic' );

    /**
     * Constructor.
     */
    function Google_Analytics_Async() {
        $this->init_vars();
		$this->init();
    }

    /**
     * Initiate plugin.
     *
     * @return void
     */
    function init() {

        //add JS
        add_action( 'admin_init', array( &$this, 'add_scripts' ) );

        add_action( 'init', array( &$this, 'load_plugin_textdomain' ), 0 );
        add_action( 'init', array( &$this, 'enable_admin_tracking' ) );
        add_action( 'init', array( &$this, 'handle_page_requests' ) );
        add_action( 'init', array( &$this, 'edit_feed' ) );
        add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
        add_action( 'network_admin_menu', array( &$this, 'network_admin_menu' ) );
        add_action( 'wp_head', array( &$this, 'tracking_code_output' ) );

        //display GA reports
        add_action( 'add_meta_boxes', array( &$this, 'add_some_meta_box' ) );
        add_action( 'the_content', array( &$this, 'display_ga_data' ) );

        //add CSS
        add_action( 'admin_print_styles', array( &$this, 'add_css' ) );
    }

    /**
     * Add scripts
     *
     * @return void
     */
    function add_scripts() {
        //including JS scripts
        if ( isset( $_REQUEST['page'] ) && 'google-analytics' == $_REQUEST['page'] ) {
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-tabs' );
        }
    }

    /**
     * Add CSS
     *
     * @return void
     */
    function add_css() {
        // Including CSS file
        wp_register_style( 'GoogleAnalyticsAsyncStyle', $this->plugin_url . 'ga-async.css' );
        wp_enqueue_style( 'GoogleAnalyticsAsyncStyle' );
    }

    /**
     * Initiate variables.
     *
     * @return void
     */
    function init_vars() {
        global $wpdb;

        if ( isset( $wpdb->site) )
            $this->domain = $wpdb->get_var( "SELECT domain FROM {$wpdb->site}" );

        $this->settings = $this->get_options();

        /* Set plugin directory path */
        $this->plugin_dir = WP_PLUGIN_DIR . '/' . str_replace( basename(__FILE__), '', plugin_basename(__FILE__) );
        /* Set plugin directory URL */
        $this->plugin_url = plugins_url( "google-analytics-async/" );
    }

    /**
     * Loads the language file from the "languages" directory.
     *
     * @return void
     */
    function load_plugin_textdomain() {

        //Load default feeds
        $this->load_default_feeds();

//TODO:1 coment in future
//        if ( is_multisite() )
//            $this->load_default_feeds( 'network' );

        load_plugin_textdomain( $this->text_domain, null, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Add Google Analytics options page.
     *
     * @return void
     */
    function admin_menu() {
        $network_settings = $this->get_options( 'track_settings', 'network' );

        /* If Supporter enabled but specific option disabled, disable menu */
		if ( !is_super_admin()
			&& function_exists( 'is_supporter' )
			&& !empty( $network_settings['supporter_only'] )
			&& !is_supporter()
		) {
            return;
        } else {
            add_submenu_page( 'options-general.php', 'Google Analytics', 'Google Analytics', 'manage_options', 'google-analytics', array( &$this, 'output_site_settings_page' ) );
        }
    }

	/**
	 * Add network admin menu
	 *
	 * @access public
	 * @return void
	 */
	function network_admin_menu() {
        add_submenu_page( 'settings.php', 'Google Analytics', 'Google Analytics', 'manage_network', 'google-analytics', array( &$this, 'output_network_settings_page' ) );
	}

    /**
     * Enable admin tracking.
     *
     * @return void
     */
    function enable_admin_tracking() {
		$network_settings = $this->get_options( 'track_settings', 'network' );

		if ( !empty( $network_settings['track_admin'] ) )
            add_action( 'admin_head', array( &$this, 'tracking_code_output' ) );
    }


    /**
     * Google Analytics code output.
     *
     * @return void
     */
    function tracking_code_output() {
        $network_settings = $this->get_options( 'track_settings', 'network' );
        $site_settings    = $this->get_options( 'track_settings' );

        /* Unset tracking code if it matches the root site one */
		if ( isset( $network_settings['tracking_code'] )
			&& isset( $site_settings['tracking_code'] )
			&& $network_settings['tracking_code'] == $site_settings['tracking_code']
		) {
			unset( $site_settings['tracking_code'] );
		}
		
		// For domain mapping selection see: http://productforums.google.com/forum/#!topic/analytics/ZaK5zu8KIf8
        if ( !empty( $network_settings['tracking_code'] ) || !empty( $site_settings['tracking_code'] ) ): ?>

			<script type="text/javascript">
				var _gaq = _gaq || [];

				<?php if ( !empty( $network_settings['tracking_code'] ) ): ?>
					_gaq.push(['_setAccount', '<?php echo $network_settings['tracking_code']; ?>']);
					<?php if ( !empty( $network_settings['track_subdomains'] ) && is_multisite() ): ?>
						<?php if ( !empty( $network_settings['domain_mapping'] ) ): ?>
							_gaq.push(['_setDomainName', 'none']);
							_gaq.push(['_setAllowLinker', true]);
						<?php else : ?>
							_gaq.push(['_setDomainName', '.<?php echo $this->domain; ?>']);
						<?php endif; ?>
						_gaq.push(['_setAllowHash', false]);
					<?php endif; ?>
					_gaq.push(['_trackPageview']);
					<?php if ( !empty( $network_settings['track_pageload'] ) ): ?>
						_gaq.push(['_trackPageLoadTime']);
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( !empty( $site_settings['tracking_code'] ) ): ?>
					_gaq.push(['b._setAccount', '<?php echo $site_settings['tracking_code']; ?>']);
					_gaq.push(['b._trackPageview']);
					<?php if ( !empty( $site_settings['track_pageload'] ) ): ?>
						_gaq.push(['b._trackPageLoadTime']);
					<?php endif; ?>
				<?php endif; ?>

				(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();
			</script>

		<?php endif; ?><?php
    }

    /**
     * Update Google Analytics settings into DB.
     *
     * @return void
     */
    function handle_page_requests() {
        if ( isset( $_POST['submit'] ) ) {

			if ( wp_verify_nonce( $_POST['_wpnonce'], 'submit_settings_network' ) ) {
            //save network settings
                $this->save_options( $_POST, 'network' );

                wp_redirect( add_query_arg( array( 'page' => 'google-analytics', 'dmsg' => urlencode( __( 'Changes were saved!', $this->text_domain ) ) ), 'settings.php' ) );
                exit;
			}
			elseif ( wp_verify_nonce( $_POST['_wpnonce'], 'submit_settings' ) ) {
            //save settings

                $this->save_options( $_POST );

                wp_redirect( add_query_arg( array( 'page' => 'google-analytics', 'dmsg' => urlencode( __( 'Changes were saved!', $this->text_domain ) ) ), 'options-general.php' ) );
                exit;
			}
        }
    }

    /**
     * Save feed
     *
     * @access public
     * @return void
     */
    function edit_feed() {
        if ( isset( $_REQUEST['page'] ) && 'google-analytics' == $_REQUEST['page'] && isset( $_REQUEST['action'] ) && 'feed_edit' == $_REQUEST['action'] ) {

            //set file name for correct redirect
            $pos = strpos( $_SERVER['SCRIPT_NAME'], 'options-general.php' );
            if ( false != $pos ) {
                $script_name = 'options-general.php';
            } else {
                $network        = 'network';
                $script_name    = 'settings.php';
            }

            if ( isset( $_POST['feed_action'] ) && 'save' == $_POST['feed_action'] && wp_verify_nonce( $_POST['_wpnonce'], 'save_feeds' ) ) {
                //Save Feed

                if ( isset( $_POST['name'] ) && '' != $_POST['name'] ) {

                    $add_feeds                      = $this->get_options( 'ga_export_feeds' );
                    $add_feeds['key']               = 'ga_export_feeds';

                    $feed_name = $_POST['name'];

                    if ( !in_array( $feed_name, $this->default_feeds ) ) {
                        //user feed

                        //remove forbidden signs
                        $feed_name = str_replace( ' ', '_', preg_replace( "/  +/", " ",  trim( $feed_name ) ) );
                        $feed_name = strtolower( preg_replace( "/([^\w\d_])|(^_*)/", "", $feed_name ) );


                        $code = array( '' => '' );

                        if ( isset( $_POST['code'] ) ) {
                            foreach( $_POST['code'] as $tmp_code ) {
                                if ( '' != $tmp_code['type'] )
                                    $code[$tmp_code['type']] = $tmp_code['value'];
                            }
                        }

                        $feed_value = array (
                            'label'     => $_POST['label'],
                            'display'   => isset( $_POST['display'] ) ? '1' : '0',
                            'code'      => $code,
                            'res_field' => $_POST['res_field']
                        );

                        $add_feeds['feeds'][$feed_name] = $feed_value;


                    } else {
                        //default feed
                        $add_feeds['feeds'][$feed_name]['label']    = $_POST['label'];
                        $add_feeds['feeds'][$feed_name]['display']  = isset( $_POST['display'] ) ? '1' : '0';
                    }

                    $this->save_options( $add_feeds, $network );

                    wp_redirect( add_query_arg( array( 'page' => 'google-analytics', 'dmsg' => urlencode( __( 'Changes of feed were saved!', $this->text_domain ) ), 'ctab' => '3' ), $script_name ) );
                    exit;
                }
            } elseif( isset( $_POST['feed_action'] ) && 'cancel' == $_POST['feed_action']  ) {
                //cancel page of create\edit Feed
                wp_redirect( add_query_arg( array( 'page' => 'google-analytics', 'ctab' => '3' ), $script_name ) );
                exit;

            } elseif( isset( $_POST['feed_action'] ) && 'delete' == $_POST['feed_action'] && wp_verify_nonce( $_POST['_wpnonce'], 'save_feeds' ) ) {
                //Delete feed

                //remove sign _ on begin of feed name for excludes defaults feeds
                $feed_name = preg_replace( "/(^_*)/", "", $_POST['name'] );

                $feeds          = $this->get_options( 'ga_export_feeds' );
                $feeds['key']   = 'ga_export_feeds';

                if ( isset( $feeds['feeds'][$feed_name] ) ) {
                    //delete feed
                    unset( $feeds['feeds'][$feed_name] );

                    $this->save_options( $feeds );

                    wp_redirect( add_query_arg( array( 'page' => 'google-analytics', 'dmsg' => urlencode( __( 'Feed was deleted!', $this->text_domain ) ), 'ctab' => '3' ), $script_name ) );
                } else {
                    // wrong feed name
                    wp_redirect( add_query_arg( array( 'page' => 'google-analytics', 'dmsg' => urlencode( __( 'Wrong name of feed!', $this->text_domain ) ), 'ctab' => '3' ), $script_name ) );
                }

                exit;
            }
        }
    }


	/**
	 * Network settings page
	 *
	 * @access public
	 * @return void
	 */
	function output_network_settings_page() {
        /* Get Network settings */
        $this->output_site_settings_page( 'network' );
	}

    /**
     * Admin options page output
     *
     * @return void
     */
    function output_site_settings_page( $network = '' ) {
        /* Get settings */
        if ( isset( $_REQUEST['action'] ) && 'feed_edit' == $_REQUEST['action'] ) {
            require_once( $this->plugin_dir . "page-edit-feed.php" );
        } else {
            require_once( $this->plugin_dir . "page-settings.php" );
        }
    }

    /**
     * Diplay GA Export data on post/page
     *
     * @return string
     */
    function display_ga_data( $content ) {
        if ( isset( $this->settings['ga_export_settings']['export_enable'] ) && 1 ==  $this->settings['ga_export_settings']['export_enable'] )
            if ( is_single() || is_page() ) {
                global $post;

                $content .= $this->show_data( $post->ID );
                return $content;
            }

        return $content;
    }

    /**
     * Adds the meta box container with GA Export Data on page of edit post/page
     */
    function add_some_meta_box() {
        if ( isset( $this->settings['ga_export_settings']['export_enable'] ) && 1 ==  $this->settings['ga_export_settings']['export_enable'] ) {
            //metabox for post

            add_meta_box(
                'metabox_GA_data'
                ,__( 'Google Analistic Data', $this->text_domain )
                ,array( &$this, 'render_meta_box_content' )
                ,'post'
            );
            //metabox for page
            add_meta_box(
                'metabox_GA_data'
                ,__( 'Google Analistic Data', $this->text_domain )
                ,array( &$this, 'render_meta_box_content' )
                ,'page'
            );
        }
    }

    /**
     * Render meta box with GA Export Data on page of edit post/page
     */
    function render_meta_box_content() {
        global $post;
        echo $this->show_data( $post->ID );
    }


    /**
     * Constructing block of GA export data
     *
     * @return string
     */
    function show_data( $post_id ) {
        $ga_reports = get_post_meta( $post_id, "ga_reports", "ARRAY_A" );

        $post = get_post( $post_id );

        $update_delay = 60*60*1;

        if ( ! is_array( $ga_reports ) || $ga_reports['last_update'] < ( time() - $update_delay ) ) {
            $ga_reports = $this->update_ga_data( $post_id, $post->post_date );
        }

        if ( is_array( $ga_reports ) ) {



            $ga_report_content = '
                <div class="analytics-block">
                    <div class="analytics-dates">' . mysql2date( get_option( 'date_format' ), $post->post_date ) . ' - ' . mysql2date( get_option( 'date_format' ), time() ) . '</div>
                    <div class="analytics-reports">
                ';

            foreach ( $ga_reports['reports'] as $key => $value ) {
                if ( 1 == $this->settings['ga_export_feeds']['feeds'][$key]['display'] ) {
                    $ga_report_content .= '
                        <div class="report">
                            <div class="report-title">' . $this->settings['ga_export_feeds']['feeds'][$key]['label'] . '</div>
                            <div class="report-data">' . $value . '</div>
                        </div>
                        ';

                }
            }

            $ga_report_content .= '
                    </div>
                </div>';

            return $ga_report_content;
        }

        return '';
    }


    /**
     * Updating GA export data from Google
     *
     * @return void or false
     */
    function update_ga_data( $post_id, $post_date ) {

        if ( ! isset( $this->settings['ga_export_settings']['token'] ) || '' == $this->settings['ga_export_settings']['token'] ) {

            if ( isset( $this->settings['ga_export_settings']['export_login'] ) && '' != $this->settings['ga_export_settings']['export_login'] &&
                 isset( $this->settings['ga_export_settings']['export_pass'] ) && '' != $this->settings['ga_export_settings']['export_pass'] ) {

                $token = $this->_get_token( $this->settings['ga_export_settings']['export_login'], $this->_decrypt( $this->settings['ga_export_settings']['export_pass'] ) );

                if ( false != $token && '' != $token ) {
                    $this->settings['ga_export_settings']['token'] = $token;

                    $this->save_options( $this->settings['ga_export_settings'] );
                    $this->settings = $this->get_options();
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        try {

            // set the Google Analytics profile
            $this->setProfile( $this->settings['ga_export_settings']['tableid'] );

            // set the date interval for the report
            $this->setDateRange( mysql2date( 'Y-m-d', $post_date ),  date( 'Y-m-d' ) );

            foreach ( $this->settings['ga_export_feeds']['feeds'] as $key => $feed ) {
                $report = $this->getReport( $this->shortcode_parser( $feed['code'], $post_id ), $this->settings['ga_export_settings']['token'] );
                $report = array_values( $report );
                $reports['reports'][$key] = $report[0][$feed['res_field']];

            }
            $reports['last_update'] = time();

/*
            //search direct
            $report = $ga->getReport(
                array(
                    'dimensions' => urlencode( 'ga:pagePath' ),
                    'metrics' => urlencode( 'ga:visits' ),
                    'filters' => 'ga:pagePath=@/projects/dron/wptest/?p=1;ga:keyword!="(not set)"',
                    'sort' => 'ga:visits',
                    'max-results' => '1'
                    )
                );

*/

        update_post_meta( 1, "ga_reports", $reports );

        return $reports;

        } catch ( Exception $e ) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }

    }


    /**
     * Loading default feeds
     *
     * @return void
     */
    function load_default_feeds( $network = '' ) {
        $feeds = $this->get_options( null, $network );

        if ( ! is_array( $feeds['ga_export_feeds'] ) ) {
            $add_feeds['feeds']['_page_visits'] = array (
                'label'     => 'Page Visits',
                'display'   => '1',
                'code'      => array(
                                'metrics'       => 'ga:visits',
                                'filters'       => 'ga:pagePath=@{PAGE_URL}',
                                'sort'          => '-ga:visits',
                                'max-results'   => '1'
                ),
                'res_field' => 'ga:visits'
            );
            $add_feeds['feeds']['_page_views'] = array (
                'label'     => 'Page Views',
                'display'   => '1',
                'code'      => array(
                                'dimensions'    => 'ga:pagePath',
                                'metrics'       => 'ga:pageviews',
                                'filters'       => 'ga:pagePath=@{PAGE_URL}',
                                'sort'          => '-ga:pageviews',
                                'max-results'   => '1'
                ),
                'res_field' => 'ga:pageviews'
            );

            $add_feeds['feeds']['_avg_time_on_page'] = array (
                'label'     => 'AVG Time On Page',
                'display'   => '1',
                'code'      => array(
                                'dimensions'    => 'ga:pagePath',
                                'metrics'       => 'ga:avgTimeOnPage',
                                'filters'       => 'ga:pagePath=@{PAGE_URL}',
                                'sort'          => '-ga:avgTimeOnPage',
                                'max-results'   => '1'
                ),
                'res_field' => 'ga:avgTimeOnPage'
            );

            $add_feeds['feeds']['_page_direct_traffic'] = array (
                'label'     => 'Direct Traffic',
                'display'   => '1',
                'code'      => array(
                                'dimensions'    => 'ga:pagePath',
                                'metrics'       => 'ga:visits',
                                'filters'       => 'ga:pagePath=@{PAGE_URL},ga:source==(direct),ga:medium==(none)',
                                'sort'          => 'ga:visits',
                                'max-results'   => '1'
                ),
                'res_field' => 'ga:visits'
            );

            $add_feeds['key'] = 'ga_export_feeds';
            $this->save_options( $add_feeds, $network );
        }

    }


    /**
     * Change shortcode in code of feed.
     *
     * @return void or array
     */
    function shortcode_parser( $a, $post_id ) {
        if ( isset( $a ) && is_array( $a ) ) {

            $page_url = wp_make_link_relative( get_permalink( $post_id ) );

            foreach ( $a as $key => $value ) {
               $value = str_replace( '{PAGE_URL}', urlencode( $page_url ), $value );
               $b[$key] = $value;
            }
            return $b;
        }
        return '';
    }

    /**
     * Save plugin options.
     *
     * @param  array $params The $_POST array
     * @return void
     */
    function save_options( $params, $network = ''  ) {
        /* Remove unwanted parameters */
        unset( $params['_wpnonce'], $params['_wp_http_referer'], $params['submit'] );
        /* Update options by merging the old ones */

        $options = $this->get_options( null, $network );

        if ( 'ga_export_settings' == $params['key'] ) {
            //Encrypt password
            if ( isset( $params['export_pass'] ) && '********' == $params['export_pass'] )
                $params['export_pass'] = $options['ga_export_settings']['export_pass'];
            elseif( isset( $params['export_pass'] ) && '' != $params['export_pass'] )
                $params['export_pass'] = $this->_encrypt( $params['export_pass'] );
            else
                $params['export_pass'] = '';

            //set correct Table ID
            if ( isset( $params['tableid'] ) ) {
                $table_id = explode( ':', $params['tableid'] );
                $params['tableid'] = ( 1 < count( $table_id ) ) ? 'ga:' . trim( $table_id[1] ) : 'ga:' . trim( $table_id[0] );
            }
        }

        $options = array_merge( $options, array( $params['key'] => $params ) );

        if ( '' == $network )
            update_option( $this->options_name, $options );
        else
            update_site_option( $this->options_name, $options );

    }

    /**
     * Get plugin options.
     *
     * @param  string|NULL $key The key for that plugin option.
     * @return array $options Plugin options or empty array if no options are found
     */
    function get_options( $key = null, $network = '' ) {

        if ( '' == $network )
            $options = get_option( $this->options_name );
        else
            $options = get_site_option( $this->options_name );

        //set default or transition to new version of the plugin
        if ( !is_array( $options ) ) {
            if ( '' == $network )
                $old_options = get_option( 'ga_settings' );
            else
                $old_options = get_site_option( 'ga_settings' );

            //if old options exist change it on new version
            if ( is_array( $old_options ) ) {
                $old_options['key'] = 'track_settings';
                $options['track_settings'] = $old_options;
                //save new options
                if ( '' == $network ) {
                    update_option( $this->options_name, $options );
                    delete_option( 'ga_settings' );
                } else{
                    update_site_option( $this->options_name, $options );
                    delete_site_option( 'ga_settings' );
                }
            } else {
                //set default value
                $options = array();
            }

        }

        /* Check if specific plugin option is requested and return it */
        if ( isset( $key ) && array_key_exists( $key, $options ) )
            return $options[$key];
        else
            return $options;
    }



    /**
     * Encrypt text (SMTP password)
     **/
    private function _encrypt( $text ) {
        if  ( function_exists( 'mcrypt_encrypt' ) ) {
            return base64_encode( @mcrypt_encrypt( MCRYPT_RIJNDAEL_256, DB_PASSWORD, $text, MCRYPT_MODE_ECB ) );
        } else {
            return $text;
        }
    }

    /**
     * Decrypt password (SMTP password)
     **/
    private function _decrypt( $text ) {
        if ( function_exists( 'mcrypt_decrypt' ) ) {
            return trim( @mcrypt_decrypt( MCRYPT_RIJNDAEL_256, DB_PASSWORD, base64_decode( $text ), MCRYPT_MODE_ECB ) );
        } else {
            return $text;
        }
    }


}

/* Initiate plugin */
new Google_Analytics_Async();

///////////////////////////////////////////////////////////////////////////
/* -------------------- WPMU DEV Dashboard Notice -------------------- */
if ( !class_exists('WPMUDEV_Dashboard_Notice') ) {
	class WPMUDEV_Dashboard_Notice {
		
		var $version = '2.0';
		
		function WPMUDEV_Dashboard_Notice() {
			add_action( 'plugins_loaded', array( &$this, 'init' ) ); 
		}
		
		function init() {
			if ( !class_exists( 'WPMUDEV_Update_Notifications' ) && current_user_can( 'install_plugins' ) && is_admin() ) {
				remove_action( 'admin_notices', 'wdp_un_check', 5 );
				remove_action( 'network_admin_notices', 'wdp_un_check', 5 );
				if ( file_exists(WP_PLUGIN_DIR . '/wpmudev-updates/update-notifications.php') ) {
					add_action( 'all_admin_notices', array( &$this, 'activate_notice' ), 5 );
				} else {
					add_action( 'all_admin_notices', array( &$this, 'install_notice' ), 5 );
					add_filter( 'plugins_api', array( &$this, 'filter_plugin_info' ), 10, 3 );
				}
			}
		}
		
		function filter_plugin_info($res, $action, $args) {
			global $wp_version;
			$cur_wp_version = preg_replace('/-.*$/', '', $wp_version);
		
			if ( $action == 'plugin_information' && strpos($args->slug, 'install_wpmudev_dash') !== false ) {
				$res = new stdClass;
				$res->name = 'WPMU DEV Dashboard';
				$res->slug = 'wpmu-dev-dashboard';
				$res->version = '';
				$res->rating = 100;
				$res->homepage = 'http://premium.wpmudev.org/project/wpmu-dev-dashboard/';
				$res->download_link = "http://premium.wpmudev.org/wdp-un.php?action=install_wpmudev_dash";
				$res->tested = $cur_wp_version;
				
				return $res;
			}
	
			return false;
		}
	
		function auto_install_url() {
			$function = is_multisite() ? 'network_admin_url' : 'admin_url';
			return wp_nonce_url($function("update.php?action=install-plugin&plugin=install_wpmudev_dash"), "install-plugin_install_wpmudev_dash");
		}
		
		function activate_url() {
			$function = is_multisite() ? 'network_admin_url' : 'admin_url';
			return wp_nonce_url($function('plugins.php?action=activate&plugin=wpmudev-updates%2Fupdate-notifications.php'), 'activate-plugin_wpmudev-updates/update-notifications.php');
		}
		
		function install_notice() {
			echo '<div class="error fade"><p>' . sprintf(__('Easily get updates, support, and one-click WPMU DEV plugin/theme installations right from in your dashboard - <strong><a href="%s" title="Install Now &raquo;">install the free WPMU DEV Dashboard plugin</a></strong>. &nbsp;&nbsp;&nbsp;<small><a href="http://premium.wpmudev.org/wpmu-dev/update-notifications-plugin-information/">(find out more)</a></small>', 'wpmudev'), $this->auto_install_url()) . '</a></p></div>';
		}
		
		function activate_notice() {
			echo '<div class="updated fade"><p>' . sprintf(__('Updates, Support, Premium Plugins, Community - <strong><a href="%s" title="Activate Now &raquo;">activate the WPMU DEV Dashboard plugin now</a></strong>.', 'wpmudev'), $this->activate_url()) . '</a></p></div>';
		}
	
	}
	new WPMUDEV_Dashboard_Notice();
}