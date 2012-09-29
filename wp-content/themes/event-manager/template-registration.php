<?php
/**
 * Template Name: Registration
 *
 * @package      Event Manager
 * @author       Bill Erickson <bill@billerickson.net>
 * @copyright    Copyright (c) 2011, Bill Erickson
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */

/**
 * Registration Iframe
 *
 */
function sc_registration_iframe() {
	global $post;
	$iframe = get_post_meta( $post->ID, 'sc_registration_iframe', true );
	if( $iframe ) echo $iframe;
}
add_action( 'genesis_post_content', 'sc_registration_iframe', 15 );

genesis();