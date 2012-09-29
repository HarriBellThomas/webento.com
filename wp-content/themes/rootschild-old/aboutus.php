<?php
/**
	Template Name: About Template

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
					<h3><b>At Webento, we believe nothing is more important than our clients' success.  Whether you're a first-time client or a loyal customer, our goal is always the same - to deliver effective, web-based solutions to help you succeed.</b></h3>
					</br>
					<h3>We provide all the tools necessary to help you create and manage an award winning website.  Our solutions are innovative and practical.  From design to implementation, Webento helps customers by offering expert advice and services.</h3>
					</br>
				<!--	<h2>Enough About Us - Let's Talk About You</h2>
					<ul class="list" style="font-size: 17px">
					<li>You're a small local business owner or entrepreneur that wants to increase your web presence.</li>
					<li>You want an affordable and effective solution to all your web needs.</li>
					<li>You want more traffic, links, subscribers, and a profit-generating website.</li>
					<li>You know that Yellow Pages is irrelevant for getting customers.</li>
					</ul>
					</br> -->
					<h3>Whether you own a business or manage websites for clients, Webento is your one-stop-shop for all your web site needs.</h3>
					</div>
					
					<!-- <hr>
					<h1 style="text-align:center;">Why Choose Webento</h1>
					
					<div class="grid_6 srvcsec floatleft"  >
					 <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p>    
					<h2>All In One</h2>
						
					<p>Hosting, CMS, and optimization services all offered on one site.</p>
					</div>
					<div class="grid_6 srvcsec floatleft">
					 <<p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> 
					<h2>Simple</h2> 

					<p>Your web site should be just as easy for you to use as it is for your customers to browse.  Create a live site in minutes.</p>
					</div>
					
					<div class="grid_6 srvcsec floatleft"  >
					 <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> 
					<h2>Adaptable</h2> 
						
					<p>All of our web site themes are mobile responsive and work just as well on mobile and touch devices as they do on a desktop. </p>
					</div>
					<div class="grid_6 srvcsec floatleft">
					 <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p>  
					<h2>Scalable</h2> 

					<p>We provide solutions that address your needs today and the needs of your business tomorrow. Never run out of space by upgrading to our Unlimited Plan.</p>
					</div>
					
					<div class="grid_6 srvcsec floatleft"  >
					 <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p>   
					<h2>Affordable</h2> 
						
					<p>Starting at only $100/year.  That's less than two cups of coffee a month  Try our <a href="demo.webento.com"><b>free 30 day trial</b></a> today!</p> 
					</div>
					<div class="grid_6 srvcsec floatleft">
					 <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> 
					<h2>Solutions</h2>

					<p>For the best web presence your web site needs to be optimized. Webento offers affordable optimization services.  Contact us today!</p>
					</div> -->

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
			
		

		<?php roots_loop_after(); ?>
		<!-- content box ends -->	

	</div><!-- /#content --> 
	</div><!-- /#main -->

	<div align="center" id="smallProject" style="display:none">
		<h2 style="font-family:corbel; margin:0">Interested in a website, but on a tight budget? Use our website builder. Try now for free!</h2>
		<p><a href="#" class="button">TRY NOW</a></p>
	</div> 
	
<?php get_footer(); ?>   
