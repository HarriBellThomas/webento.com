<?php
/**
	Template Name: Usability Template

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
                
                <div style="margin-bottom:10px"  class="mainimg">
                	<img src="<?php echo home_url(); ?>/wp-content/themes/roots/img/Usability.png"  alt="usability testing san diego" />
                </div>
				<div>               
					<h3><b>Web Usability can help you define the needs of your target audience and develop a site that will meet those needs.  We can assess the user-focus of an existing site and sites under development to help you boost the strength of your internet presence.</b></h3>
					</br>
					<h2>User Interface Expert Evaluations</h2>
					<h3>We offer expert usability reviews which are a fast and efficient way to improve the usability of your web site.  Our team delivers insightful recommendations which are based on extensive Human-Computer Interaction (HCI) and User Experience (UX).</h3>
					</br>
					</br>
					<h2>Front-end Concept Evaluations</h2>
					<h3>User-centered design involves the use of up-front user analysis to make sure the navigational flow and organization of the site are designed to meet end-user expectations before the development of the site gets underway.</h3> 
					</br>
					</br>
					<h2>Benefits of Usability</h2> 
					<ul class="list" style="font-size: 17px">
						<li>Reduce development time and costs</li>
						<li>Reduce maintenance costs</li>
						<li>Reduce user errors</li>
						<li>Increase customer satisfaction</li>
						<li>Increase sales and revenues</li>
						<li>Return on investment</li>
					</ul>
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
			
			<?php include_once('page_nav.php'); ?>
			
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
