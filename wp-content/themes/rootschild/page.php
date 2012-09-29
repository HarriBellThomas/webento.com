<?php get_header(); ?>
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
		
        <div class="catchbox">
			<?php catchBoxContent(); ?>
		</div>
		<!--content box start -->
        <div class="container_12 innercont clearfix">
			<?php //roots_loop_before(); ?>
			<?php get_template_part('loop', 'page'); ?>
			<?php roots_loop_after(); ?>
		</div><!-- /#content --> 
        <!-- content box ends -->
    </div><!-- /#main -->
	
	<div align="center" id="smallProject" style="display:none">
		<h2 style="font-family:corbel; margin:0">Interested in a website, but on a tight budget? Use our website builder. Try now for free!</h2>
		<p><a href="#" class="button">TRY NOW</a></p>
	</div> 
	
 
  <?php 
  if(is_page(110)) {  
  //get_sidebar("contacts"); 
  }
  ?>
<?php get_footer(); ?>