



<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
  <?php roots_post_before(); ?>
    <?php roots_post_inside_before(); 
	
	if(is_page(110))
	{
	?>
    
    <div class="grid_8">
      	<h1><?php //the_title(); ?> How can we help?</h1>
        <div style=" margin-top:0" class="conttab">
             <a id="BP" class="seltab" href="javascript:void(null)" onclick="changeTab('bigProject')">BiG Porject</a> 
             <a id="SP" href="javascript:void(null)" onclick="changeTab('smallProject')">Small Project</a> 
        </div>
      
      
      <div style=" margin-bottom: 2em" class="contbox clearfix ">

    
      <?php 
	}
	
	  the_content(); ?>
            
	
      
      <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
    <?php roots_post_inside_after(); ?>
  <?php roots_post_after(); ?>
<?php endwhile; /* End loop */ ?>
<!--</div>
   </div>-->
 
 
<?php  
   if(is_page(635))
	{
		
	$args = array(
    'numberposts'     => 4,
    'offset'          => 0,
    'category'        => 'Services',
 );	
	$myposts = get_posts( $args );
?>
<div class="container_12 innercont">
<?php	
	$rotator = 0;
	foreach( $myposts as $post ) :	setup_postdata($post); 
	$logo_name = get_post_meta($post->ID, 'logo-name', true); 
	?>
    
    <div class="srvcseg">
          	<h1 style="line-height:65px"><img style="float:left; padding-right:15px" src="http://webento.mbt/wp-content/themes/roots/img/<?php echo $logo_name?>" width="65" height="65" alt="" /><strong><?php the_title(); ?></strong></h1>
    		<p>
            <?php
			if($rotator%2 == 0)
			{
			 ?>
            <span class="floatleft"><?php echo get_the_post_thumbnail( ); ?> </span>
            <?php
			}
			else
			{
			 ?>
            <span class="floatright"><?php echo get_the_post_thumbnail( ); ?> </span>
            <?php 
			}
			$rotator++;
			the_content(); ?>
            <span> 
<a class="small button" href="#">Contact Us</a> <em>&nbsp;&nbsp;or&nbsp;&nbsp;</em> <a href="#">Read More</a></span>
</p>
</div>
    
<?php endforeach; ?>	
		
	
                     <!--content box start -->
         
         
         	<!--<div class="srvcseg">
          	<h1 style="line-height:65px"><img style="float:left; padding-right:15px" src="http://webento.mbt/wp-content/themes/roots/img/icon4.png" width="65" height="65" alt="" /><strong>SEARCH ENGINE OPTIMIZATION</strong></h1>
             
            <p><span class="floatleft"><img src="http://webento.mbt/wp-content/themes/roots/img/imgthumb2.jpg" width="276" height="186" alt="" /></span>
How Stress keyword phrases – Use only one or two keyword phrases per page of your website. Bold, italics, and link them. Use only a couple of times throughout the page content. 
  Clean URL – Be smart URLs that include the keyword phrase (if possible) is highly recommended. Although not a requirement for a clean URL can help search engines determine the relevance of a particular page request for the search engines online.
 
 
Relevant content – It is very important that your content is relevant to them and they are using your site. I know most people do not want to read through pages and pages of content, but not enough content is impossible to improve your ranking. Also keep new and fresh content. <span><br /> 
<a class="small button" href="#">Contact Us</a> <em>&nbsp;&nbsp;or&nbsp;&nbsp;</em> <a href="#">Read More</a></span>
</p>
</div>

<div  class="srvcseg">
          	<h1 style="line-height:65px"><img style="float:left; padding-right:15px" src="http://webento.mbt/wp-content/themes/roots/img/icon2.png" width="65" height="65" alt="" /><strong>WEB OPTIMIZATION</strong></h1>
             
            <p><span class="floatright"><img src="http://webento.mbt/wp-content/themes/roots/img/imgthumb4.jpg" width="276" height="186" alt="" /></span>
How Stress keyword phrases – Use only one or two keyword phrases per page of your website. Bold, italics, and link them. Use only a couple of times throughout the page content. 
 
Clean URL – Be smart URLs that include the keyword phrase (if possible) is highly recommended. Although not a requirement for a clean URL can help search engines determine the relevance of a particular page request for the search engines online.
 
 
Relevant content – It is very important that your content is relevant to them and they are using your site. I know most people do not want to read through pages and pages of content, but not enough content is impossible to improve your ranking. Also keep new and fresh content. The more often you update the search engines most often visit your website.
When implementing keywords and phrases – Make sure your keyword phrases are the titles, content and Meta tags.<span><br /> 
<a class="small button" href="#">Contact Us</a> <em>&nbsp;&nbsp;or&nbsp;&nbsp;</em> <a href="#">Read More</a></span></p>
</div>

<div  class="srvcseg">
          	<h1 style="line-height:65px"><img style="float:left; padding-right:15px" src="http://webento.mbt/wp-content/themes/roots/img/icon3.png" width="65" height="65" alt="" /><strong>USABILITY</strong></h1>
             
            <p><span class="floatleft"><img src="http://webento.mbt/wp-content/themes/roots/img/imgthumb3.jpg" width="276" height="186" alt="" /></span>
How Stress keyword phrases – Use only one or two keyword phrases per page of your website. Bold, italics, and link them. Use only a couple of times throughout the page content. 
 
Clean URL – Be smart URLs that include the keyword phrase (if possible) is highly recommended. Although not a requirement for a clean URL can help search engines determine the relevance of a particular page request for the search engines online.
 
 
Relevant content – It is very important that your content is relevant to them and they are using your site. I know most people do not want to read through pages and pages of content, but not enough content is impossible to improve your ranking. Also keep new and fresh content. The more often you update the search engines most often visit your website.
 .<span><br /> 
<a class="small button" href="#">Contact Us</a> <em>&nbsp;&nbsp;or&nbsp;&nbsp;</em> <a href="#">Read More</a></span></p>
</div>

<div  class="srvcseg">
          	<h1 style="line-height:65px"><img style="float:left; padding-right:15px" src="http://webento.mbt/wp-content/themes/roots/img/icon1.png" width="65" height="65" alt="" /><strong>WEB DEVELOPMENT</strong></h1>
             
            <p><span class="floatright"><img src="http://webento.mbt/wp-content/themes/roots/img/imgthumb.jpg" width="276" height="186" alt="" /></span>
How Stress keyword phrases – Use only one or two keyword phrases per page of your website. Bold, italics, and link them. Use only a couple of times throughout the page content. 
 
Clean URL – Be smart URLs that include the keyword phrase (if possible) is highly recommended. Although not a requirement for a clean URL can help search engines determine the relevance of a particular page request for the search engines online.
 
 
Relevant content – It is very important that your content is relevant to them and they are using your site. I know most people do not want to read through pages and pages of content, but not enough content is impossible to improve your ranking. Also keep new and fresh content. The more often you update the search engines most often visit your website.
When implementing keywords and phrases – Make sure your keyword phrases are the titles, content and Meta tags.<span><br /> 
<a class="small button" href="#">Contact Us</a> <em>&nbsp;&nbsp;or&nbsp;&nbsp;</em> <a href="#">Read More</a></span></p>
</div>-->


          </div>
        <!-- content box ends -->
<?php	}

?>
   
   