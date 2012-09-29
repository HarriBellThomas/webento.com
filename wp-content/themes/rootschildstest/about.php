<?php 
/**
Template Name: About Us Templete
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
 
            <div class="grid_12 floatright clearfix">
       	    
           	  	<h1><?php the_title('<span>', '</span>'); ?></h1>
                
                <div style=" margin-bottom:10px"  class="mainimg">
                	<img src="<?php echo home_url(); ?>/wp-content/themes/roots/img/Search_Engine_Optimization.png" height="70" width="970" alt="About Webento" />
                </div>
				<div>               
					<h3><b>At Webento, we believe nothing is more important than our clients' success.  Whether you're a first-time client or a loyal customer, our goal is always the same - to deliver effective, web-based solutions to help you succeed.</b></h3>
					</br>
					<h3>We provide all the tools necessary to help you create and manage an award winning website.  Our solutions are innovative and practical.  From design to implementation, Webento helps customers by offering expert advice and services.</h3>
					</br>
<!-- <div  class="srvcseg">
          	<h1 style="margin-bottom:0px; text-align:center;">Web-Based Services and Solutions 
			</br>That Work </h1>
<p>
<h2 style="text-align:center;"></br>Want a profit-generating website?  Webento offers the services and solutions you need to succeed.</h2>
</span></p>
</div> -->

<div style="text-align:center; font-size:2.5em; font-weight:bold">
</br>
Our Team
<hr>
</div>
<div class="grid_13 srvcsec floatleft"  >
<p><img src="http://webento.com/wp-content/themes/roots/img/Patrick_Thompson.jpg" width="260" height="105" alt="Patrick Thompson" /></p>    
<h2>Patrick Thompson</h2>
    
    <p style="line-height:25px">Patrick is a UX Designer & Usability Researcher dedicated to expanding user experience across all online avenues.  He makes sure that Webento clients' websites have a useful and intuitive experience for their users.  Prior to Webento, Patrick worked as a UX Designer at SkinIt and currently holds the same position at YouSendIt.  When not making the web a friendlier place, Patrick likes to teach people about the internet, work out, cook, and good whiskey.    </p>
</div>
<div class="grid_13 srvcsec floatleft">
<p><img src="http://webento.com/wp-content/themes/roots/img/Brandon_Moberg.jpg" width="260" height="105" alt="Brandon Moberg" /></p>
<h2>Brandon Moberg</h2>

<p style="line-height:25px">Brandon's experience ranges from small business websites to large-scale websites and he is constantly developing his skills.  Before coming to San Diego, Brandon raised bison on his family's farmin northern Minnesota.  He prefers San Diego weather but enjoys visiting Minnesota, even in the winter.  When he's not working, Brandon enjoys cosmology, brainstorming new ideas, and hunting and fishing.</p></div>
<div class="grid_13 srvcsec floatleft"  >

<p><img src="http://webento.com/wp-content/themes/roots/img/Kevin_Walker.jpg" width="260" height="105" alt="Kevin Walker" /></p>
<h2>Kevin Walker</h2>

<p style="line-height:25px">Webento is Kevin's second entrepreneurial adventure.  Having co-founded <a href="http://midwesttomatofest.com">Midwest Tomato Fest</a> Kevin is looking forward to meeting customers, learning every byte of our operations and growing his skill set.  When not negotiating contracts, you can find Kevin dominating any sport, riding his moped or playing poker. </p></div>

</div>
</div>
<a class="small button" href="/contact">Contact Us</a> 
<?php get_footer(); ?>  
