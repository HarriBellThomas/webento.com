<?php
/**
	Template Name: Demo Template

/**/
 get_header(); ?>
  <?php //roots_content_before(); ?>
    <div id="content" class="<?php //echo CONTAINER_CLASSES; ?>">
    <?php //roots_main_before(); ?>
    <?php 
	global $pagename;
	query_posts(array( 'post_type' => 'page', 'pagename' => $pagename ));
	while ( have_posts() ) : the_post();
		ob_start();
		the_title();
		$webento_title = ob_get_contents();
		ob_end_clean();
	endwhile;
	wp_reset_query();			
	?>

	<div id="main" class="<?php //echo MAIN_CLASSES; ?>" role="main">
	
        <!-- catchbox -->
		<div class="catchbox">
			<?php catchBoxContent(); ?>
		</div>
		<!-- catchbox ends -->
		<div class="container_12 innercont clearfix">
		<?php		 
			$args = array(
				'numberposts'     => 4,
				'offset'          => 0,
				'category'        => 49,
			);	
			$myposts = get_posts( $args );	
		?>
		<div class="clearfix container_12 innercont">
 
            <div class="grid_9 floatright clearfix">
       	    
           	  	<h1><?php the_title('<span>', '</span>'); ?></h1>
                
                <div style=" margin-bottom:10px"  class="mainimg">
                	<img src="<?php echo home_url(); ?>/wp-content/themes/roots/img/Search_Engine_Optimization.png"  alt="About Webento" />
                </div>
				<div>               
					<h3><b>Webento offers users the ability to try out our mobile responsive themes before choosing a plan</b></h3>
					</br>
					</div>
					
				<div>
					<h3><a href="http://demo.webento.com">Webento Demo</a> allows you to experience the wonders of Webento without spending a dime.  This gives you the opportunity to try out all of our themes <b>free for 30 days</b> and then decide which plan you'd like to try.<h3>
					</div>
					
					<hr>
					<h2 style="text-align:center;">Reasons to try Webento Demo</h2>
					
					<div class="grid_6 srvcsec floatleft"  >
					<!-- <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> -->    
					<h2>Experience Webento</h2>
						
						<p>Test out the Webento Framework and backend conrols without spending one dollar.</p>
						</div>
						
						<div class="grid_6 srvcsec floatleft">
					<!-- <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> -->
					<h2>Features</h2>

					<p>Try out all of our other features that aren't accessible on the Webento site.</p>
					</div>
					
					<div class="grid_6 srvcsec floatleft">
					<!-- <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> -->
					<h2>Upload Content</h2>

					<p>Upload your vey own content to see how it would look on a specific theme.</p>
					</div>
					
					
					<div class="grid_6 srvcsec floatleft">
					<!-- <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> -->
					<h2>It's Free</h2>

					<p>When you're done trying Webento Demo choose a plan that best fits your needs.</p>
					</div>
					

				<?php 
					$rotator = 0;
					$post = $SEO_post;
					foreach( $myposts as $post ) :	setup_postdata($post); 
					
					$logo_name = get_post_meta($post->ID, 'logo-name', true);
					if($rotator % 2 == 0) {
				?>
					<div class="grid_5 srvcsec" id=seo-<?php  echo $rotator?> >
				<?php 
				} else {
				?>
					<div class="grid_5 srvcsec floatright" id=seo-<?php  echo $rotator?> >
				<?php 
				}
				$rotator++;
				?> 
					<h2><?php the_title(); ?></h2>
					<p><img src="<?php echo home_url(); ?>/wp-content/themes/roots/img/<?php echo $logo_name; ?>" width="260" height="105" alt="" /></p>
					<p><?php the_content(); ?></p>
				</div>
				<?php
				endforeach;
				?>
			</div>
				
			<div>
		<a href="<?php echo home_url(); ?>/contact"><img src="<?php echo home_url(); ?>/wp-content/themes/roots/img/contact.gif"  alt="Webento Contact" /></a>
			</div>
			
		</div>

		<?php roots_loop_after(); ?>
		<!-- content box ends -->	

	</div><!-- /#content --> 
	</div><!-- /#main -->

	<div align="center" id="smallProject" style="display:none">
		<h2 style="font-family:corbel; margin:0">Interested in a website, but on a tight budget? Use our website builder. Try now for free!</h2>
		<p><a href="#" class="button">TRY NOW</a></p>
	</div> 
	
<?php get_footer(); ?>   
