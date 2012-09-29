<?php
/**
 * Single Speaker
 *
 * @package      Event Manager
 * @author       Bill Erickson <bill@billerickson.net>
 * @copyright    Copyright (c) 2011, Bill Erickson
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */

// Remove Post Info and Meta
remove_action( 'genesis_before_post_content', 'genesis_post_info' );
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );

/**
 * Speaker Single Image
 *
 */
function sc_speaker_single_image() {
	global $post;
	if( has_post_thumbnail() )
		echo '<p>' . get_the_post_thumbnail( $post->ID, 'sc_thumbnail' ) . '</p>';
}
add_action( 'genesis_post_content', 'sc_speaker_single_image', 5 );

 
genesis();