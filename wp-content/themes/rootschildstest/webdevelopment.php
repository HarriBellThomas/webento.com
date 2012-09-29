<?php
/**
	Template Name: Web Development Template

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
                	<img src="<?php echo home_url(); ?>/wp-content/themes/roots/img/Web_Development.png"  alt="web development san diego" />
                </div>
				<div>               
					<h3><b>A website is a reflection of your business.  If it is well-developed, it will take your business to new levels of growth and success.</b></h3>
					</br>
					<h2>What We Do:</h2>
					<h3>Webento provides end-to-end web development services to fulfill all website requirements.  We maintain a highly-skilled team of programming and design experts who specialize in custom web development.</h3>
					</br>
					</br>
					<h2>Why Choose Our Web Development Services</h2>
					<h3>Whether you're a startup or an established firm, your business is sure to benefit from Webento's effective web development services.  Webento's goals:</h3>
					<ul class="list" style="font-size: 17px">
						<li>Make your sales and revenues increase substantially</li>
						<li>Cut down costs and eliminate operational headaches</li>
						<li>Create innovative solutions</li>
					</ul>
					</br>
					<h3>Let us know of your website development requirements, and Webento will create a site to meet your business needs.<h3>
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
