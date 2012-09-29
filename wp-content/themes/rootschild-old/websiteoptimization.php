<?php
/**
	Template Name: Website Optimization Template

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
                
                <div style="margin-bottom:10px" class="mainimg">
                	<img src="<?php echo home_url(); ?>/wp-content/themes/roots/img/Web_Optimization.png" alt="web optimization san diego" />
                </div>
				<div>               
					<h3><b> We provide the search engine marketing services you need to climb the rankings and dominate your market.  Our SEO services can deliver the kind of ROI that keeps you growing and profitable.</b></h3>
					</br>
					<h2>Increase Speed</h2>
					<h3>Boost the download speed of your web pages and accelerate backend systems to retain your visitors increase usability.</h3>
					<ul class="list" style="font-size: 17px">
						<li>Cut file size and download times in half</li>
						<li>Speed up site load time to satisfy your customers</li>
						<li>Engage your web site users with fast response times and flow stimulus</li>
						<li>Increase your usability, boost profits, and slash bandwidth costs</li>
					</ul>
					</br>
					<h2>Raise Conversion Rates</h2>
					<h3>Maximize your web site's conversion rate and its ability to generate more leads and sales.</h3>
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
