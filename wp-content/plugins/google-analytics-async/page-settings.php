<?php
        /* Get settings */
        $site_settings = $this->get_options( null, $network );

?>

        <script type="text/javascript">

            jQuery( document ).ready( function() {

                //Creating tabs
                jQuery( function() {
                    jQuery( '#tabs' ).tabs();
                    jQuery( '#tabs' ).tabs( 'select', '<?php echo ( isset( $_REQUEST['ctab'] ) ) ? $_REQUEST['ctab'] : 0 ;?>' );
                });

                if ( false == jQuery( '#export_enable' ).prop( 'checked' ) ) {
                        jQuery( '#tabs-2 input' ).prop( 'readonly', true );
                    } else {
                        jQuery( '#tabs-2 input' ).prop( 'readonly', false );
                    }
                jQuery( '#export_enable' ).prop( 'readonly', false );

                jQuery( '#export_enable' ).change( function () {
                    if ( false == jQuery( '#export_enable' ).prop( 'checked' ) ) {
                        jQuery( '#tabs-2 input' ).prop( 'readonly', true );
                    } else {
                        jQuery( '#tabs-2 input' ).prop( 'readonly', false );
                    }
                    jQuery( '#export_enable' ).prop( 'readonly', false );
                });

            });

        </script>

        <div class="wrap">
            <h2><?php _e( 'Google Analytics', $this->text_domain ) ?></h2>

            <?php
                //Display status message
                if ( isset( $_GET['dmsg'] ) ) {
                    ?><div id="message" class="updated fade"><p><?php echo urldecode( $_GET['dmsg'] ); ?></p></div><?php
                }

            ?>
                <div id="google-analytics-async-tabs">
                    <div class="ui-tabs ui-widget ui-widget-content ui-corner-all" id="tabs">
                        <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
                            <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1"><?php _e( 'Tracking Settings', $this->text_domain ) ?></a></li>

                            <?php
                            //TODO:1 change this for network admin
                            if ( !( '' != $network ) ):
                            ?>
                            <li class="ui-state-default ui-corner-top"><a href="#tabs-2"><?php _e( 'GA Export', $this->text_domain ) ?> <span class="beta">Beta</span><?php _e( ': Settings', $this->text_domain ) ?></a></li>
                            <li class="ui-state-default ui-corner-top"><a href="#tabs-3"><?php _e( 'GA Export', $this->text_domain ) ?> <span class="beta">Beta</span><?php _e( ': Feeds', $this->text_domain ) ?></a></li>
                            <?php endif;?>
                        </ul>

                        <div class="ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">

                        <?php if ( 'network' == $network ): ?>
                            <h3><?php _e( 'Tracking Network Settings', $this->text_domain ) ?></h3>
                            <form method="post" action="">
                                <table class="form-table">

                                    <tr valign="top">
                                        <th scope="row"><?php _e( 'Network-wide Tracking Code', $this->text_domain ); ?></th>
                                        <td>
                                            <input type="text" name="tracking_code" class="regular-text" value="<?php if ( !empty( $site_settings['track_settings']['tracking_code'] ) ) { echo $site_settings['track_settings']['tracking_code']; } ?>" />
                                            <br />
                                            <span class="description"><?php _e( 'Your Google Analytics tracking code. Ex: UA-XXXXX-X. The Network-wide tracking code will track your entire network of sub-sites.', $this->text_domain ); ?></span>
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <th scope="row"><?php _e( 'Admin pages tracking', $this->text_domain ); ?></th>
                                        <td>
                                            <input type="radio" name="track_admin" value="1" <?php if ( !empty( $site_settings['track_settings']['track_admin'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Enable', $this->text_domain ); ?>
                                            <br />
                                            <input type="radio" name="track_admin" value="0" <?php if ( empty( $site_settings['track_settings']['track_admin'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Disable', $this->text_domain ); ?>
                                            <br />
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <th scope="row"><?php _e( 'Subdomain tracking', $this->text_domain ); ?></th>
                                        <td>
                                            <input type="radio" name="track_subdomains" value="1" <?php if ( !empty( $site_settings['track_settings']['track_subdomains'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Enable', $this->text_domain ) ?>
                                            <br />
                                            <input type="radio" name="track_subdomains" value="0" <?php if ( empty( $site_settings['track_settings']['track_subdomains'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Disable', $this->text_domain ) ?>
                                            <br />
                                        </td>
                                    </tr>
									
									<tr valign="top">
                                        <th scope="row"><?php _e( 'Page load times tracking', $this->text_domain ); ?></th>
                                        <td>
                                            <input type="radio" name="track_pageload" value="1" <?php if ( !empty( $site_settings['track_settings']['track_pageload'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Enable', $this->text_domain ) ?>
                                            <br />
                                            <input type="radio" name="track_pageload" value="0" <?php if ( empty( $site_settings['track_settings']['track_pageload'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Disable', $this->text_domain ) ?>
                                            <br />
                                        </td>
                                    </tr>
									
									<tr valign="top">
										<th scope="row"><?php _e( 'Domain Mapping', $this->text_domain ); ?></th>
										<td>
											<input type="radio" name="domain_mapping" value="1" <?php if ( !empty( $site_settings['track_settings']['domain_mapping'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Enable', $this->text_domain ) ?>
											<br />
											<input type="radio" name="domain_mapping" value="0" <?php if ( empty( $site_settings['track_settings']['domain_mapping'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Disable', $this->text_domain ) ?>
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
                                                    <option value="1" <?php if ( !empty( $site_settings['track_settings']['supporter_only'] ) ) echo 'selected="selected"'; ?>><?php _e( 'Enable', $this->text_domain ); ?></option>
                                                    <option value="0" <?php if ( empty( $site_settings['track_settings']['supporter_only'] ) ) echo 'selected="selected"'; ?>><?php _e( 'Disable', $this->text_domain ); ?></option>
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
                                    <input type="submit" name="submit" class="button-primary" value="<?php _e( 'Save Changes', $this->text_domain ); ?>" />
                                </p>

                            </form>

                        <?php else: ?>

                            <h3><?php _e( 'Tracking Settings', $this->text_domain ) ?></h3>
                            <form method="post" action="">
                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row"><?php _e( 'Site Tracking Code', $this->text_domain ); ?></th>
                                        <td>
                                            <input type="text" name="tracking_code" class="regular-text" value="<?php if ( !empty( $site_settings['track_settings']['tracking_code'] ) ) { echo $site_settings['track_settings']['tracking_code']; } ?>" />
                                            <br />
                                            <span class="description"><?php _e( 'Your Google Analytics tracking code. Ex: UA-XXXXX-X. The Site tracking code will track this site.', $this->text_domain ); ?></span>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?php _e( 'Page load times tracking', $this->text_domain ); ?></th>
                                        <td>
                                            <input type="radio" name="track_pageload" value="1" <?php if ( !empty( $site_settings['track_settings']['track_pageload'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Enable', $this->text_domain ) ?>
                                            <br />
                                            <input type="radio" name="track_pageload" value="0" <?php if ( empty( $site_settings['track_settings']['track_pageload'] ) ) echo 'checked="checked"'; ?> /> <?php _e( 'Disable', $this->text_domain ) ?>
                                            <br />
                                        </td>
                                    </tr>
                                </table>
                                <p class="submit">
                                    <?php wp_nonce_field('submit_settings'); ?>
                                    <input type="hidden" name="key" value="track_settings" />
                                    <input type="submit" name="submit" class="button-primary" value="<?php _e( 'Save Changes', $this->text_domain ); ?>" />
                                </p>
                            </form>
                        <?php endif; ?>

                            <h3><?php _e( 'What\'s Google Analytics ?', $this->text_domain ); ?></h3>
                            <p><?php  _e( 'Google Analytics is the enterprise-class web analytics solution that gives you rich insights into your website traffic and marketing effectiveness. Powerful, flexible and easy-to-use features now let you see and analyze your traffic data in an entirely new way. With Google Analytics, you\'re more prepared to write better-targeted ads, strengthen your marketing initiatives and create higher converting websites.', $this->text_domain ); ?></p>
                            <h3><?php _e( 'How do I set this up ?', $this->text_domain ); ?></h3>
                            <p><?php  _e( 'To get going, just <a href="http://www.google.com/analytics/sign_up.html">sign up for Analytics</a>, set up a new account and copy the tracking code you receive (it\'ll start with "UA-") into the box above and press "Save" - it can take several hours before you see any stats, but once it is you\'ve got access to one heck of a lot of data!', $this->text_domain ); ?></p>
                            <p><?php  _e( 'For more information on finding the tracking code, please visit <a href="http://www.google.com/support/analytics/bin/answer.py?hl=en&amp;answer=55603">this Google help site</a>.', $this->text_domain ); ?></p>
                        </div>

                        <?php
                        //TODO:1 change this for network admin
                        if ( !( '' != $network ) ):
                        ?>


                        <div class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-2">
                            <h3><?php _e( 'GA Export: Settings', $this->text_domain ) ?></span></h3>
                            <form method="post" name="">
                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row">
                                        <label>
                                            <input type="checkbox" name="export_enable" id="export_enable"  value="1" <?php echo ( 1 == $site_settings['ga_export_settings']['export_enable']  ) ? "checked='checked'" : "" ;?>  />
                                            <?php _e( 'Enable GA Data Export.', $this->text_domain ); ?>
                                        </label>
                                        </th>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?php _e( 'Login(email):', $this->text_domain ); ?></th>
                                        <td>
                                            <input type="text" name="export_login" class="regular-text" value="<?php if ( !empty( $site_settings['ga_export_settings']['export_login'] ) ) { echo $site_settings['ga_export_settings']['export_login']; } ?>" />
                                            <br />
                                            <span class="description"><?php _e( 'Your Google Analytics account login.', $this->text_domain ); ?></span>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?php _e( 'Password:', $this->text_domain ); ?></th>
                                        <td>
                                            <input type="password" name="export_pass" class="regular-text" value="<?php echo ( isset( $site_settings['ga_export_settings']['export_pass'] ) && '' != $site_settings['ga_export_settings']['export_pass'] ) ? '********' : ''; ?>" />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?php _e( 'TableID:', $this->text_domain ); ?></th>
                                        <td>
                                            <input type="text" name="tableid" class="regular-text" value="<?php if ( !empty( $site_settings['ga_export_settings']['tableid'] ) ) { echo $site_settings['ga_export_settings']['tableid']; } ?>" />
                                            <br />
                                            <span class="description"><?php _e( 'Set the Google Analytics profile you want to access - example: ga:123456 or 123456', $this->text_domain ); ?></span>
                                        </td>
                                    </tr>
                                </table>
                                <p class="submit">
                                    <?php wp_nonce_field('submit_settings'); ?>
                                    <input type="hidden" name="key" value="ga_export_settings" />
                                    <input type="submit" name="submit" value="<?php _e( 'Save Changes', $this->text_domain ); ?>" />
                                </p>
                            </form>

                            <h3><?php _e( 'What\'s Google Analytics report data?', $this->text_domain ); ?></h3>
                            <p><?php  _e( 'Report data consists of statistics derived from the data collected by the Google Analytics  tracking code, organized as dimensions and metrics. You use the Core Reporting API to query for dimensions and metrics, which in turn, return customized reports.<br />More <a href="http://code.google.com/intl/en/apis/analytics/docs/gdata/home.html" target="_blank">here</a>.', $this->text_domain ); ?></p>
                        </div>

                        <div class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-3">
                            <h3><?php _e( 'GA Export: Feeds', $this->text_domain ) ?></h3>
                            <form method="post" class="form_feeds" >
                                <table class="widefat post">
                                    <thead>
                                        <tr>
                                            <th><?php _e( 'Display', $this->text_domain ); ?></th>
                                            <th><?php _e( 'Feed Name ID', $this->text_domain ); ?></th>
                                            <th><?php _e( 'Label Name', $this->text_domain ); ?></th>
                                            <th><?php _e( 'Actions', $this->text_domain ); ?></th>
                                        </tr>
                                    </thead>
                                    <?php
                                    if ( isset ( $site_settings['ga_export_feeds']['feeds'] ) ):
                                        foreach ( $site_settings['ga_export_feeds']['feeds'] as $key => $feed ) :

                                    ?>
                                        <tr valign="top">
                                            <td>
                                                <input type="checkbox" name="display_feeds[<?php echo $key ?>]" <?php echo ( 1 == $feed['display'] ) ? 'checked' : ''; ?> disabled />
                                            </td>
                                            <td><?php echo ( '_' == $key[0] ) ? $key . " <span class='description'>(" . __( 'Default', $this->text_domain ) . ")</span>" : $key; ?></td>
                                            <td><?php echo $feed['label'] ?></td>
                                            <td>
                                                <a href="?page=google-analytics&action=feed_edit&name=<?php echo $key ?>">
                                                    <input type="button" value="<?php _e( 'Edit', $this->text_domain ) ?>">
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </table>
                                <br />
                                <p class="submit">
                                    <a href="?page=google-analytics&action=feed_edit" style="text-decoration: none;">
                                        <input type="button" value="<?php _e( 'Create New Feed', $this->text_domain ); ?>">
                                    </a>
                                </p>
                            </form>

                        </div>

                        <?php endif; ?>

                    </div><!--/#tabs-->

                </div><!--/#google-analytics-async-tabs-->
        </div>