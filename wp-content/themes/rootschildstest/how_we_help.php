<?php 
/**
Template Name: How We Help Template
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
					<h3><b>Webento takes care of your website allowing you more time to focus on your busines.</b></h3>
	
					</div>
				
					<div class="grid_13 srvcsec floatleft"  >
					 <!-- <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> -->   
					<h3>We offer powerful and effective services for you website.  </h3>
						
					<ul class="list" style="font-size: 17px;">
					<li>We take the guess-work out of establishing your business online</li>
					<li>We help you establish best practices that put you on track for increased profits</li>
					<li>We take the pain out of managing your website</li>
					</ul>

					</div>

					<div class="grid_13 srvcsec floatleft"  >
					 <!-- <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> -->
					<h3>We specialize in fixing under-performing websites.  Here's what we do:</h3> 

					<ul class="list" style="font-size: 17px;">
					<li>Perform a comprehensive website audit</li>
					<li>Analyze current traffic, sources, and visitor behavior</li>
					<li>Design a custom website renovation</li>
					</ul>

					</div>
					
					<div class="grid_13 srvcsec floatleft"  >
					<!-- <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> -->
					<h3>We help you get found online through Search Engines, Social Media and Local Search Optimization.  We help you:</h3> 
						
					<ul class="list" style="font-size: 17px;">
					<li>Determine the best search keywords to target</li>
					<li>Optimize your website on-page and behind the scenes</li>
					<li>Analyze competitor keywords for insights into broader strategies</li>
					</ul>

					</div>
					


	<a class="small button" href="#">Contact Us</a> 
	</div>
<?php get_footer(); ?> 