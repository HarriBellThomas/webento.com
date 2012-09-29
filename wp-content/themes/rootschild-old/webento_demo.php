<?php 
/**
Template Name: Webento Demo Template
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
					<h3><b><a href="http://demo.webento.com">Webento Demo</a> allows users the opportunity to try-out Webento for free allowing you to experience how easy it is to create a website through Webento without spending a dime. </b></h3>
					</br>
					</div>

					<div class="grid_13 srvcsec floatleft"  >
					 <!-- <p><img src="http://webento.com/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p> -->   
					<h2>Why You Should Try Out Webento Demo:</h2>
						<ul class="list" style="font-size: 17px;">
						<li>Experience Webento's framework and all of our backend controls.</li>
						<li>Try out features that aren't as evident on our demo themes.</li>
						<li>Upload your very own content to see if a specific theme fits your requirements.</li>
						<li>After trying out Webento Demo -<b>for free</b>- decide whether Webento is the right fit for you.</li>
						</ul>
					
					</div>

				


	<a class="small button" href="#">Contact Us</a> 
	</div>
<?php get_footer(); ?> 