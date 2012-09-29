<?php

add_action( 'genesis_meta', 'streamline_home_genesis_meta' );
/**
 * Add widget support for homepage.
 *
 */
function streamline_home_genesis_meta() {

	if ( is_active_sidebar( 'home-featured-1' ) || is_active_sidebar( 'home-featured-2' ) || is_active_sidebar( 'home-featured-3' ) ) {
	
		add_action( 'genesis_before_content_sidebar_wrap', 'streamline_home_loop_helper' );

	}
}

/**
 * Display widget content for home featured sections.
 *
 */
function streamline_home_loop_helper() {

	if ( is_active_sidebar( 'home-featured-1' ) || is_active_sidebar( 'home-featured-2' ) || is_active_sidebar( 'home-featured-3' ) ) {

		echo '<div id="home-featured">';

		echo '<div class="home-featured-1">';
		dynamic_sidebar( 'home-featured-1' );
		echo '</div><!-- end .home-featured-1 -->';	

		echo '<div class="home-featured-2">';
		dynamic_sidebar( 'home-featured-2' );
		echo '</div><!-- end .home-featured-2 -->';

		echo '<div class="home-featured-3">';
		dynamic_sidebar( 'home-featured-3' );
		echo '</div><!-- end .home-featured-3 -->';

		echo '</div><!-- end #home-featured -->';	

	}

}

genesis();