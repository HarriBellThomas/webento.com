<?php

/* Get settings */
$site_settings = $this->settings;

if ( isset( $_GET['name'] ) && isset( $site_settings['ga_export_feeds']['feeds'][$_GET['name']] ) ) {
    $current_feed           = $site_settings['ga_export_feeds']['feeds'][$_GET['name']];
    $current_feed['name']   = $_GET['name'];

    if ( in_array( $current_feed['name'], $this->default_feeds ) )
        $defaul_feed = 1 ;
}
?>



<script type="text/javascript">

    jQuery( document ).ready( function() {

        var i = 100;

        //add table row
        jQuery( '#add_row' ).click( function() {
            i = i + 1;
            jQuery( '#code_table > tbody' ).append( '<tr id="ra' + i + '"><td> <span class="google-analytics-remove-row" onclick="jQuery(this).rem_row( ' + i + ');" >x <?php _e( 'remove', $this->text_domain ) ?><span> &raquo;</span></span></td><td><input type="text" name="code[' + i + '][type]" value="" /></td><td><input type="text" name="code[' + i + '][value]" value="" class="google-analytics-code-value" /><span class="description">(<?php _e( 'required', $this->text_domain ); ?>)</span></td></tr>');
        });

        //remove table row
        jQuery.fn.rem_row = function( id ) {
            if ( 3 < jQuery( '#code_table tr' ).length )
                jQuery( '#ra' + id ).remove();
        };

        //send form
        jQuery( "#save_feed" ).click( function() {

            var code        = jQuery( 'input[name*="code"]' );
            var code_error  = 0;
            var error       = 0;

            jQuery( '.form-invalid' ).attr( 'class', '' );

            jQuery( 'input[name^=code]' ).each( function() {
                if ( '' == jQuery( this ).val() )
                    code_error = 1;
            });

            if ( 1 == code_error ) {
                jQuery( '#code_table' ).parent( 'td' ).parent( 'tr' ).attr( 'class', 'form-invalid' );
                error = 1;
            }

            if ( 0 < jQuery( '#feed_name' ).length && '' == jQuery( '#feed_name' ).val() ) {
                jQuery( '#feed_name' ).parent( 'td' ).parent( 'tr' ).attr( 'class', 'form-invalid' );
                error = 1;
            }

            if ( 1 == error )
                return false;

            jQuery( "#feed_action" ).val( 'save' );
            jQuery( "#form_edit_feed" ).submit();
            return true;
        });

        jQuery( "#cancel_feed" ).click( function() {
            jQuery( "#feed_action" ).val( 'cancel' );
            jQuery( "#form_edit_feed" ).submit();
        });

        jQuery( "#delete_feed" ).click( function() {
            jQuery( "#feed_action" ).val( 'delete' );
            jQuery( "#form_edit_feed" ).submit();
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

    <div class="ga_edit_feed">
        <?php if ( isset( $current_feed['name'] ) ): ?>
            <h3><?php _e( 'Edit feed', $this->text_domain ) ?></h3>
        <?php else: ?>
            <h3><?php _e( 'Create feed', $this->text_domain ) ?></h3>
        <?php endif; ?>

        <?php if ( isset( $defaul_feed ) ): ?>
            <span><?php _e( 'Note: This is default feed - you can change only some settings for it!', $this->text_domain ) ?></span>
        <?php endif; ?>

        <form method="post" name="" id="form_edit_feed">
            <input type="hidden" name="feed_action" id="feed_action" value="" />
            <table class="form-table">
                <?php if ( isset( $current_feed['name'] ) ): ?>

                <tr valign="top">
                    <th scope="row"><?php _e( 'Name:', $this->text_domain ); ?></th>
                    <td>
                        <input type="hidden" name="name" value="<?php echo $current_feed['name']; ?>" />
                        <input type="text" class="regular-text" value="<?php echo $current_feed['name']; ?>" readonly />
                    </td>
                </tr>

                <?php else: ?>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Name:', $this->text_domain ); ?>
                        <span class="description">(<?php _e( 'required', $this->text_domain ); ?>)</span>
                    </th>
                    <td>
                        <input type="text" name="name" id="feed_name" class="regular-text" value="" />
                        <br />
                        <span class="description"><?php _e( "The name of feed isn't showed anywhere except at the feed's editing page. It can contain only letters, digits and sign _", $this->text_domain ); ?></span>
                    </td>
                </tr>

                <?php endif; ?>

                <tr valign="top">
                    <th scope="row"><?php _e( 'Lable:', $this->text_domain ); ?></th>
                    <td>
                        <input type="text" name="label" class="regular-text" value="<?php echo ( isset( $current_feed['label'] ) ) ? $current_feed['label'] : ''; ?>" />
                        <br />
                        <span class="description"><?php _e( 'Used as the title of result and showed on pages where reports are displayed .', $this->text_domain ); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e( 'Display:', $this->text_domain ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="display" id="display"  value="1" <?php echo ( isset( $current_feed['display'] ) && '1' == $current_feed['display'] ) ? 'checked' : ''; ?>/>
                            <?php _e( 'yes, display this report at pages', $this->text_domain ); ?>
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e( 'Code of report:', $this->text_domain ); ?></th>
                    <td>
                    <span class="description"><?php _e( 'This section should contain elements and parameters that make up the query data feed:', $this->text_domain ); ?></span>
                        <table id="code_table">
                            <tr>
                                <td>
                                </td>
                                <td align="center">
                                    <?php _e( 'Name of elements', $this->text_domain ); ?>
                                </td>
                                <td align="center">
                                    <?php _e( 'Value of elements', $this->text_domain ); ?>
                                </td>
                            </tr>

                            <?php if ( !isset( $defaul_feed ) ): ?>
                            <tr>
                                <td>
                                </td>
                                <td>
                                    <input type="text" value="metrics" disabled />
                                </td>
                                <td>
                                    <input type="text" value="ga:visits" disabled />
                                    <span class="description"><?php _e( 'This line is only for example', $this->text_domain ); ?></span>
                                </td>
                            </tr>
                            <?php endif; ?>

                            <?php
                            if ( isset( $current_feed['code'] ) && is_array( $current_feed['code'] )  ):
                                $ra = 1;
                                foreach ( $current_feed['code'] as $key => $value ):
                            ?>
                                <tr id="ra<?php echo $ra; ?>">
                                    <td>
                                        <?php if ( !isset( $defaul_feed ) ): ?>
                                            <span class="google-analytics-remove-row" onclick="jQuery( this ).rem_row( '<?php echo $ra; ?>' );" >x <?php _e( 'remove', $this->text_domain ) ?><span> &raquo;</span> </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <input type="text" name="code[<?php echo $ra; ?>][type]" value="<?php echo $key; ?>" <?php echo ( !isset( $defaul_feed )  )? '' : 'readonly' ?> />
                                    </td>
                                    <td>
                                        <input type="text" name="code[<?php echo $ra; ?>][value]" value="<?php echo $value; ?>" class="google-analytics-code-value" <?php echo ( !isset( $defaul_feed )  )? '' : 'readonly' ?> />
                                        <?php if ( !isset( $defaul_feed ) ): ?>
                                            <span class="description">(<?php _e( 'required', $this->text_domain ); ?>)</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                                $ra++;
                                endforeach;
                            else:
                            ?>
                                <?php if ( !isset( $defaul_feed ) ): ?>
                                <tr id="ra1">
                                    <td>
                                        <span class="google-analytics-remove-row" onclick="jQuery( this ).rem_row( '1' );" >x <?php _e( 'remove', $this->text_domain ) ?><span> &raquo;</span> </span>
                                    </td>
                                    <td>
                                        <input type="text" name="code[1][type]" value="" />
                                    </td>
                                    <td>
                                        <input type="text" name="code[1][value]" value="" class="google-analytics-code-value" />
                                        <span class="description">(<?php _e( 'required', $this->text_domain ); ?>)</span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            <?php
                             endif;
                            ?>

                        </table>

                        <p class="submit" id="ga_code_add_row">
                        <?php if ( !isset( $defaul_feed ) ): ?>
                            <input type="button" id="add_row" value="<?php _e( '+ add line', $this->text_domain ); ?>" />
                        <?php endif; ?>
                        </p>

                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e( 'Result field:', $this->text_domain ); ?></th>
                    <td>
                        <input type="text" name="res_field" class="regular-text" value="<?php echo ( isset( $current_feed['res_field'] ) ) ? $current_feed['res_field'] : ''; ?>" <?php echo ( !isset( $defaul_feed )  )? '' : 'readonly' ?> />
                    </td>
                </tr>
            </table>
            <p class="submit">
                <?php wp_nonce_field( 'save_feeds' ); ?>
                <input type="button" name="save_feed" id="save_feed" value="<?php echo ( isset( $current_feed['name'] ) ) ? __( 'Save feed', $this->text_domain ) : __( 'Create feed', $this->text_domain ); ?>" />

                <?php if ( !isset( $defaul_feed ) ): ?>
                    <input type="button" name="delete_feed" id="delete_feed" value="<?php _e( 'Delete feed', $this->text_domain ); ?>" />
                <?php endif; ?>

                <input type="button" name="cancel_feed" id="cancel_feed" value="<?php _e( 'Cancel', $this->text_domain ); ?>" />
            </p>
        </form>

        <h2><?php _e( 'Help to create your feed', $this->text_domain ); ?></h2>

        <h3><?php _e( 'List of some elements of the query data feed:', $this->text_domain ); ?></h3>
        <p>
            dimensions <br />
            metrics <span class="description">(<?php _e( '(require)', $this->text_domain ); ?>)</span><br />
            sort <br />
            filters <br />
            segment <br />
            start-index <br />
            max-results <br />
            v <br />
            prettyprint <br />
            <br />
            For more information you can read the Google's documentation <a href="http://code.google.com/intl/en/apis/analytics/docs/gdata/dimsmets/dimsmets.html" target="_blank" >here</a>.
        </p>

        <br />

        <h3><?php _e( 'Shortcodes for values of elements:', $this->text_domain ); ?></h3>
        <p>
            {PAGE_URL} - <span class="description"><?php _e( ' return URL on page;', $this->text_domain ); ?></span><br />

            <br />
            <?php _e( 'If you need more value for your feed - you can ask developer add SHORTCODE for it here (URL on topic will be added soon).', $this->text_domain ); ?>
        </p>

    </div>

</div>