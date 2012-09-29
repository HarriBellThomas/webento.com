<?php
/**
 * Template Name: Schedule
 *
 * @package      Event Manager
 * @author       Bill Erickson <bill@billerickson.net>
 * @copyright    Copyright (c) 2011, Bill Erickson
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */

/**
 * Session Loop Wrapper
 *
 */
function sc_session_loop_wrapper() {
	$terms = get_terms( 'sc-session-grouping', apply_filters( 'sc_schedule_term_args', array( 'orderby' => 'name' ) ) );
	
	// If using the Session Grouping, break it down by grouping
	if( $terms ) {
		echo '<div class="sessions by-grouping">';
		foreach( $terms as $term ) {
			// Group intro (box on left)
			echo '<div class="group"><div class="group-info"><span class="name">' . $term->name . '</span><span class="desc">' . $term->description . '</span></div><!-- .group-info -->';
			$args = array( 
				'post_type' => 'sc-sessions',
				'posts_per_page' => '-1',
				'tax_query' => array(
					array(
						'taxonomy' => 'sc-session-grouping',
						'field' => 'id',
						'terms' => $term->term_id
					)
				),
				'orderby' => 'meta_value_num',
				'order' => 'ASC',
				'meta_key' => 'sc_session_timestamp',
			);
			sc_session_loop( $args );
			echo '</div><!-- .group -->';
		}
		echo '</div><!- .sessions -->';
	
	// If not using Session Grouping, list all sessions by time
	} else {
		echo '<div class="sessions no-grouping">';
		$args = array(
			'post_type' => 'sc-sessions',
			'posts_per_page' => '-1',
			'orderby' => 'meta_value_num',
			'order' => 'ASC',
			'meta_key' => 'sc_session_timestamp',
		);
		sc_session_loop( $args );
		echo '</div>';
	}
} 
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'sc_session_loop_wrapper' );


/**
 * Session Loop
 *
 */
function sc_session_loop( $args = array() ) {
	$loop = new WP_Query( $args );
	if( $loop->have_posts() ): while( $loop->have_posts() ): $loop->the_post(); global $post;

		echo '<div class="session">';
		echo '<div class="left">';
		echo '<h3>' . esc_attr( get_post_meta( $post->ID, 'sc_session_time', true ) ) . '</h3>';
		echo wpautop( get_post_meta( $post->ID, 'sc_session_location', true ) );
		echo '</div>';
		echo '<div class="right">';
		echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
		$content = get_the_content();
		if( function_exists( 'p2p_list_posts' ) ) {
			$connected = p2p_type( 'sessions_to_speakers')-> get_connected( $post->ID );
			p2p_list_posts( $connected, array(
				'before_list' => '<p>by ',
				'after_list' => '</p>',
				'before_item' => '',
				'after_item' => ' ',
			) );	
		}
		echo $content;
	
		echo '</div>';
		echo '</div>';
	endwhile; endif; wp_reset_query();
}
 
genesis();