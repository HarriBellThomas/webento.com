<?php
/**
	Template Name: Contact Templete

*/
 ?>
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
        <h3><strong><?php echo $webento_title; ?></strong></h3>Contact us with any questions you have about Webento</div>
		 <!--content box start -->
         <div class="container_12 innercont clearfix">
			<?php //roots_loop_before(); ?>
 
 
 
 
 
<?php while (have_posts()) : the_post(); ?>
  <?php roots_post_before(); ?>
    <?php roots_post_inside_before(); 
	
	
	?>
    
    <div class="grid_8">
      	<h1 style="text-align:center"><?php //the_title(); ?> How can we help?</h1>
        <div style=" margin-top:0" class="conttab">
             <a id="BP" class="seltab" href="javascript:void(null)" onclick="changeTab('bigProject')">Big Project</a> 
             <a id="SP" href="javascript:void(null)" onclick="changeTab('smallProject')">Small Project</a> 
        </div>
      <div style=" margin-bottom: 2em" class="contbox clearfix ">
      <?php 
	  the_content(); ?>
      <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
    <?php roots_post_inside_after(); ?>
  <?php roots_post_after(); ?>
<?php endwhile; /* End loop */ ?>
<?php roots_loop_after(); ?>
        
        
        <!-- content box ends -->
	
   </div><!-- /#content --> 
    </div><!-- /#main -->
	
<div align="center" id="smallProject" style="display:none">
	<h2 style="font-family:corbel; margin:0">Interested in a website, but on a tight budget? Use our website builder. Try now for free!</h2>
	<p><a href="https://webento.com/register" class="button">TRY NOW</a></p>
</div> 
	
 
  <?php 
    
  get_sidebar("contacts"); 
  
  get_footer(); ?>
