<?php
/**
 * Template Name: Archive
 *
 * Copied from Genesis, and added Sessions/Speakers
 *
 * @package Genesis
 */

/** Remove standard post content output **/
remove_action( 'genesis_post_content', 'genesis_do_post_content' );

add_action( 'genesis_post_content', 'genesis_page_archive_content' );
/**
 * This function outputs sitemap-esque columns displaying all pages,
 * categories, authors, monthly archives, and recent posts.
 *
 * @since 1.6
 */
function genesis_page_archive_content() { ?>

	<div class="archive-page">

		<h4><?php _e( 'Pages:', 'social-coup' ); ?></h4>
		<ul>
			<?php wp_list_pages( 'title_li=' ); ?>
		</ul>
		
		<h4><?php _e( 'Posts:', 'social-coup' ); ?></h4>
		<ul>
			<?php wp_get_archives( 'type=postbypost&limit=100' ); ?>
		</ul>
		
	</div><!-- end .archive-page-->

	<div class="archive-page">

		<?php
		// Speakers
		$args = array(
			'post_type' => 'sc-speakers',
			'posts_per_page' => '-1',
			'orderby' => 'menu_order',
			'order' => 'ASC'
		);
		$loop = new WP_Query( $args );
		if( $loop->have_posts() ):
		
			echo '<h4>' . __( 'Speakers:', 'social-coup' ) . '</h4>';
			echo '<ul>';
			
			while( $loop->have_posts() ): $loop->the_post(); global $post;
			
				echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
			endwhile; 
			
			echo '</ul>';
			
		endif;
		wp_reset_query();
		
		
		// Sessions
		$args = array(
			'post_type' => 'sc-sessions',
			'posts_per_page' => '-1',
			'orderby' => 'meta_value_num',
			'order' => 'ASC',
			'meta_key' => 'sc_session_timestamp',
		);
		$loop = new WP_Query( $args );
		if( $loop->have_posts() ):
		
			echo '<h4>' . __( 'Sessions:', 'social-coup' ) . '</h4>';
			echo '<ul>';
			
			while( $loop->have_posts() ): $loop->the_post(); global $post;
			
				echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
			endwhile; 
			
			echo '</ul>';
			
		endif;
		wp_reset_query();
		
		?>


	</div><!-- end .archive-page-->

<?php
}

genesis();
