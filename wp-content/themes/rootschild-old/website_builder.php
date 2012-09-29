<?php 
/**
Template Name: Website Builder Template
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
					<h3><b>Webento is your one-stop shop for all your website needs.</b></h3>
	
					</div>
				
					<div class="grid_13 srvcsec floatleft"  >
					 <!-- <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> -->   
					<h2>All In One</h2>
						
					<p>Hosting, CMS, and optimization services all offered on one site.</p>
					</div>

					<div class="grid_13 srvcsec floatleft"  >
					 <!-- <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> -->
					<h2>Simple</h2> 

					<p>Your web site should be just as easy for you to use as it is for your customers to browse.  Create a live site in minutes.</p>
					</div>
					
					<div class="grid_13 srvcsec floatleft"  >
					<!-- <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> -->
					<h2>Adaptable</h2> 
						
					<p>All of our web site themes are mobile responsive and work just as well on mobile and touch devices as they do on a desktop. </p>
					</div>
					<div class="grid_13 srvcsec floatleft"  >
					<!-- <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p>  -->
					<h2>Scalable</h2> 

					<p>We provide solutions that address your needs today and the needs of your business tomorrow. Never run out of space by upgrading to our Unlimited Plan.</p>
					</div>
					
					<div class="grid_13 srvcsec floatleft"  >
					<!-- <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> -->  
					<h2>Affordable</h2> 
						
					<p>Starting at only $100/year.  That's less than two cups of coffee a month  Try our <a href="demo.webento.com"><b>free 14 day trial</b></a> today!</p> 
					</div>
					<div class="grid_13 srvcsec floatleft"  >
					<!-- <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> -->
					<h2>Solutions</h2>

					<p>For the best web presence your web site needs to be optimized. Webento offers affordable optimization services.</p>
					</div>
					<a class="small button" href="/contact">Contact Us</a> 
					</div>
					</div> 
<?php get_footer(); ?> 