<?php
/**
 * Template Name: Speakers
 *
 * @package      Event Manager
 * @author       Bill Erickson <bill@billerickson.net>
 * @copyright    Copyright (c) 2011, Bill Erickson
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
 
/**
 * Speaker Loop
 *
 */
function sc_speaker_loop() {
	$args = array( 
		'post_type' => 'sc-speakers',
		'posts_per_page' => '-1',
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);	
	
	$loop = new WP_Query( $args );
	if( $loop->have_posts() ): while( $loop->have_posts() ): $loop->the_post(); global $post;
		echo '<div class="speaker"><div class="one-half first">';
		
		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'sc_thumbnail' );
		if( $image ) echo '<p><img src="' . $image[0] . '" class="border" alt="' . get_the_title() . '" /></p>';
		
		$url = esc_url( get_post_meta( $post->ID, 'sc_speaker_url', true ) );
		if( !empty( $url ) ) echo '<h4><a href="' . $url . '" target="_blank">' . $url . '</a></h4>';
		
		$twitter = esc_attr( get_post_meta( $post->ID, 'sc_speaker_twitter', true ) );
		if( !empty( $twitter ) ) echo '<p><a href="http://www.twitter.com/' . $twitter . '" target="_blank">@' . $twitter . '</a></p>';
		
		echo '</div><div class="one-half">';
		
		echo '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
		the_content();
		
		echo '</div></div>';
	endwhile; endif; wp_reset_query();
}
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'sc_speaker_loop' );


genesis();