<?php
/**
	Template Name: Team Template

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
					<h3><b>The Webento team is made up of highly motivated individuals striving to provide the best web solutions.  We are a team of designers, developers and strategists with an incredible passion for implementing our web services.</b></h3>
					</br>
					</div>
					
				<div>
					<h3>Being surrounded by the great city of San Diego has inspired us to continually strive for excellence.  Each member of the Webento Team comes from a unique background and brings a diverse set of skills to the team.<h3>
					</div>
					
					<!-- <hr>
					<h2 style="text-align:center;">Our Team</h2>
					
					<div class="grid_6 srvcsec floatleft"  >
					<div id="skills">
				<ul>
					<li>
						<p class="bar photoshop">Photoshop</p>
						<p class="percent">95%</p>
					</li>
					<li>
						<p class="bar dance">Dance Moves</p>
						<p class="percent">60%</p>
					</li>
					<li>
						<p class="bar html">HTML/CSS</p>
						<p class="percent">95%</p>
					</li>
					<li>
						<p class="bar javascript">Javascript (jQuery)</p>
						<p class="percent">85%</p>
					</li>
					<li>
						<p class="bar pool">Pool Shark</p>
						<p class="percent">90%</p>
					</li>
				</ul>
				<!-- <p class="measure">Level of skill</p> 
			</div>
			
						<h2>Patrick Thompson</h2>
						<p>Patrick is a UX Designer & Usability Researcher dedicated to expanding user experience to help the web become a friendlier place to use.</p>
						</div>
					<div class="grid_6 srvcsec floatleft">
					<div id="skills">
				<ul>
					<li>
						<p class="bar mophotoshop">Photoshop</p>
						<p class="percent">80%</p>
					</li>
					<li>
						<p class="bar modance">Dance Moves</p>
						<p class="percent">90%</p>
					</li>
					<li>
						<p class="bar mohtml">HTML/CSS</p>
						<p class="percent">85%</p>
					</li>
					<li>
						<p class="bar mojavascript">Javascript (jQuery)</p>
						<p class="percent">80%</p>
					</li>
					<li>
						<p class="bar mopool">Pool Shark</p>
						<p class="percent">95%</p>
					</li>
				</ul>
				<!-- <p class="measure">Level of skill (measured in awesome)</p> 
			</div>
					<h2>Brandon Moberg</h2>
					
					<p>Brandon has experience working with both small business websites and large business websites. </p>
				</div>

				<div class="grid_6 srvcsec floatleft">
					<div id="skills">
				<ul>
					<li>
						<p class="bar kwphotoshop">Photoshop</p>
						<p class="percent">90%</p>
					</li>
					<li>
						<p class="bar kwdance">Dance Moves</p>
						<p class="percent">95%</p>
					</li>
					<li>
						<p class="bar kwhtml">HTML/CSS</p>
						<p class="percent">65%</p>
					</li>
					<li>
						<p class="bar kwjavascript">Javascript (jQuery)</p>
						<p class="percent">60%</p>
					</li>
					<li>
						<p class="bar kwpool">Pool Shark</p>
						<p class="percent">95%</p>
					</li>
				</ul>
				<!-- <p class="measure">Level of skill (measured in awesome)</p> 
			</div>
					<h2>Kevin Walker</h2>

					<p>Kevin graduated from the University of St. Thomas and is co-founder of <a href="http://midwesttomatofest.com">Midwest Tomato Fest</a></p>
					</div>  -->

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
				
			<?php include_once('about_nav.php'); ?>
			
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
