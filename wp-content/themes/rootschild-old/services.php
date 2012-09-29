<?php
/**
	Template Name: Services Templete

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
			<?php //roots_loop_before(); ?>
   
 <?php 	
	$service = array(
    'numberposts'     => 4,
    'offset'          => 0,
    'category'        => 51,
 );	
	$service_posts = get_posts( $service );
?>
<div class="container_12 innercont">
<?php	
	$rotator = 0;
	$SEO_post = $post;
	foreach( $service_posts as $post ) :	setup_postdata($post); 
	$post_logo = get_post_meta($post->ID, 'logo-name', true); 
	?>
    <div style=" padding-top: 10px; padding-bottom: 10px;" class="srvcseg">
          	<h1 style="line-height:65px"><img style="float:left; padding-right:15px" src="<?php home_url()?>/wp-content/themes/roots/img/<?php echo $post_logo?>" width="65" height="65" alt="" /><strong><?php the_title(); ?></strong></h1>
    		<p>
			<?php
			if($rotator%2 == 0)
			{
			 ?>
            <div class="grid_4 floatleft"><?php echo get_the_post_thumbnail( $post->ID, "medium"); ?> </div>
            <?php
			}
			else
			{
			 ?>
            <div class="grid_4 floatright"><?php echo get_the_post_thumbnail( $post->ID, "medium"); ?> </div>
            <?php 
			}
			$rotator++;
			?>
			<div class="grid_8">
			<?php $tcontent = get_the_content() ; if ( mb_strlen( $tcontent ) >= 700 ) echo mb_substr( $tcontent, 0, 700 ).'...'; else echo $tcontent; ?>
            <p> 
			<a class="small services_button" href="<?php echo get_bloginfo('url'); ?>/contact/">Contact Us</a> <em>&nbsp;&nbsp;or&nbsp;&nbsp;</em> <a style="font-size: 16px;    font-weight: bold;" href="<?php echo get_permalink();?>">Read More &rarr;</a></p></p>
			</div>
	</div>
    
<?php endforeach; ?>	

</div>
        <!-- content box ends -->
<?php roots_loop_after(); ?>
      </div><!-- /#main -->
    <?php roots_main_after(); ?>
    <?php roots_sidebar_before(); ?>
   <!--   <aside id="sidebar" class="<?php echo SIDEBAR_CLASSES; ?>" role="complementary">
      <?php roots_sidebar_inside_before(); ?>
        <?php get_sidebar(); ?>
      <?php roots_sidebar_inside_after(); ?>
      </aside><!-- /#sidebar -->
    <?php roots_sidebar_after(); ?>
    </div><!-- /#content -->
  <?php roots_content_after(); ?>
<?php get_footer(); ?> 