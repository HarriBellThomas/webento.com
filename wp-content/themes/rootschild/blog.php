<?php
/*
	Template Name: Blog Templete

*/
	get_header(); 

	the_content();
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
  	$blog = array(
		'numberposts'	=> 5,
		'offset'		=> 0,
		'cat'			=> 50,
		'paged' 		=> $paged
	);	
	$blog_posts = query_posts( $blog );
?>
        <!-- catchbox -->
        <div class="catchbox">
			<?php catchBoxContent(); ?>
		</div>
        <!-- catchbox ends -->
        
        <!--content box start -->		
		<div class="clearfix container_12 innercont">
       	    <div class="blog_class">
				<?php 
				$checker=0;
				foreach( $blog_posts as $post ) :	setup_postdata($post);
				
				if( $checker == 0 ) {
				?>
				<div class="clearfix" style="border: 1px #CCC solid; padding: 20px;width:91.8%;">
					<h1>
						<span><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></span>
					</h1>
					<?php roots_entry_meta(); ?>
					<div style=" margin-bottom:10px"  class="mainimg">
						<a href="<?php echo get_permalink(); ?>"><?php echo get_the_post_thumbnail($post->ID,'large'); ?></a>
					</div>
					<p><?php $tcontent = strip_tags( get_the_content() ); if ( mb_strlen( $tcontent ) >= 300 ) echo mb_substr( $tcontent, 0, 300 ).'...'; else echo $tcontent; ?></p>
					<br/>
					<p><a href="<?php echo get_permalink(); ?>">Read More &rarr;</a></p>
				</div>
				<?php 
				}
				if( $checker == 1 || $checker == 2 || $checker == 3 || $checker == 4 ) {
				?>	
					<div class="grid_5 srvcsec smallpost_blog">
						<h2><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php roots_entry_meta(); ?>
						<div><a href="<?php echo get_permalink(); ?>"><?php echo get_the_post_thumbnail($post->ID,'medium'); ?></a></div>
						<p><?php $tcontent = strip_tags( get_the_content() ); if ( mb_strlen( $tcontent ) >= 300 ) echo mb_substr( $tcontent, 0, 300 ).'...'; else echo $tcontent; ?></p>
						<br/>
						<p><a href="<?php echo get_permalink(); ?>">Read More &rarr;</a></p>
					</div>
				<?php 
				}
				++$checker;
				endforeach;
				?>
				
				<div style="clear:both;"></div>
				
				<!-- Pagination Starts here -->
				<div style="padding-top: 15px;margin-left: 7px;">
				<?php if(function_exists('wp_paginate')) {
					wp_paginate();
				} 
				?>
				</div>
				<!-- Pagination Ends here -->
			</div>
			
			<div class="leftcol floatright blog_nav" >
				<ul>
					<li><?php get_search_form(); ?></li>
					<?php if ( !dynamic_sidebar('blog_loop_sidebar') ) : ?>
						<li><img src="<?php home_url()?>/wp-content/themes/roots/img/icon1.png" width="35" height="35" alt="" /><a href="#">Social Box with subscription</a></li>
						<li><img src="<?php home_url()?>/wp-content/themes/roots/img/icon2.png" width="35" height="35" alt="" /><a href="#">Archive Dropdown </a></li>
						<li><img src="<?php home_url()?>/wp-content/themes/roots/img/icon3.png" width="35" height="35" alt="" /><a href="#">Twitter Feed</a></li>
					<?php endif; ?>
				</ul>
			</div>
			<div style="clear:both;"></div>	
		</div>
        <!-- content box ends -->
		
 
<?php get_footer(); ?>