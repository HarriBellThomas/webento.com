



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
    'category'        => 48,
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

          </div>
        <!-- content box ends -->
<?php	}
if(is_page(824))
{
$args = array(
    'numberposts'     => 4,
    'offset'          => 0,
    'category'        => 49,
 );	
	$myposts = get_posts( $args );	
	
	
?>
<div class="clearfix container_12 innercont">
        	<!--<div align="center" class="toptab"> 
            	<a class=" sel" href="#"><img src="images/icon4.png" width="65" height="65" alt="" /></a>&nbsp; 
                <a href="#"><img src="images/icon3.png" width="65" height="65" alt="" /></a>&nbsp; 
                <a href="#"><img src="images/icon2.png" width="65" height="65" alt="" /></a>&nbsp; 
                <a href="#"><img src="images/icon1.png" width="65" height="65" alt="" /></a>
            </div>-->
  <div class=" grid_3 leftcol">
            <ul>
            	<li><img src=" http://webento.mbt/wp-content/themes/roots/img/icon1.png" width="35" height="35" alt="" /><a href="#">Search Engine Optimization</a></li>
                <li><img src="http://webento.mbt/wp-content/themes/roots/img/icon2.png" width="35" height="35" alt="" /><a href="#">Website Optimization</a></li>
                <li><img src="http://webento.mbt/wp-content/themes/roots/img/icon3.png" width="35" height="35" alt="" /><a href="#">Usability</a></li>
                <li><img src="http://webento.mbt/wp-content/themes/roots/img/icon4.png" width="35" height="35" alt="" /><a href="#">Web Development</a></li>
            </ul>
            <div>
            	<a href="<?php echo home_url(); ?>/contact"><img src="http://webento.mbt/wp-content/themes/roots/img/contact.gif"  alt="" /></a>
            </div>
  </div>
            <div class="grid_8 floatright clearfix">
       	    
           	  	<h1>
                	<span><img class="floatleft" src="http://webento.mbt/wp-content/themes/roots/img/icon4.png" width="65" height="65" alt="" /></span>
                	<span>SEARCH ENGINE OPTIMIZATION</span>
                </h1>
                
                
                
                <div style=" margin-bottom:10px"  class="mainimg">
                	<img src="http://webento.mbt/wp-content/themes/roots/img/inner-img.jpg"  alt="" />
                </div>
                
                <p>
                Relevant content – It is very important that your content is relevant to them and they are using your site. I know most people do not want to read through pages and pages of content, but not enough content is impossible to improve your ranking. Also keep new and fresh content. The more often you update the search engines most often visit your website.
When implementing keywords and phrases – Make sure your keyword phrases are the titles, content and Meta tags.
                </p>
            	<?php 
				$rotator = 0;
				foreach( $myposts as $post ) :	setup_postdata($post); 
				
				$logo_name = get_post_meta($post->ID, 'logo-name', true);
				if($rotator%2 == 0)
				{
				?>
				<div class="grid_5 srvcsec" id=seo-<?php  echo $rotator?> >
				<?php 
				}
				else
				{
				?>
				<div class="grid_5 srvcsec floatright" id=seo-<?php  echo $rotator?> >
				<?php 
				}
				$rotator++;
				?> 
				<h2><?php the_title(); ?></h2>
				<p><img src="http://webento.mbt/wp-content/themes/roots/img/<?php echo $logo_name; ?>" width="260" height="105" alt="" /></p>
				<p>
				<?php the_content(); ?>
				</p>
				</div>
				<?php
				endforeach;
				?>
			<!--   <div class="grid_5 srvcsec"  >
                	<h2>SEO Audit</h2>
                     <p><img src="http://webento.mbt/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p>
                    <p>    Search Engine Optimization
    Website Optimization
    Usability
    Web Development

SEARCH ENGINE OPTIMIZATION

Relevant content – It is very important that your content is relevant to them and they are using your site. I know most people do not want to read through pages and pages of content, but not enough content is impossible to improve your ranking. </p>
                    <p>
                    
                    </p>
                </div>
                <div class="grid_5 srvcsec floatright">
                	<h2>SEO consulting</h2>
                    <p><img src="http://webento.mbt/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p>
                  <p>    Search Engine Optimization
    Website Optimization
    Usability
    Web Development

SEARCH ENGINE OPTIMIZATION

Relevant content – It is very important that your content is relevant to them and they are using your site. I know most people do not want to read through pages and pages of content, but not enough content is impossible to improve your ranking. </p>
                </div>
                
                
                                <div class="grid_5 srvcsec"  >
                	<h2>Link Building Strategy</h2>
                     <p><img src="http://webento.mbt/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p>
                    <p>    Search Engine Optimization
    Website Optimization
    Usability
    Web Development

SEARCH ENGINE OPTIMIZATION

Relevant content – It is very important that your content is relevant to them and they are using your site. I know most people do not want to read through pages and pages of content, but not enough content is impossible to improve your ranking. </p>
                    <p>
                    
                    </p>
              </div>
                <div class="grid_5 srvcsec floatright">
                	<h2>SEO Copy Writing</h2>
                    <p><img src="http://webento.mbt/wp-content/themes/roots/img/in-thumb.jpg" width="260" height="105" alt="" /></p>
                    <p>    Search Engine Optimization
    Website Optimization
    Usability
    Web Development

SEARCH ENGINE OPTIMIZATION

Relevant content – It is very important that your content is relevant to them and they are using your site. I know most people do not want to read through pages and pages of content, but not enough content is impossible to improve your ranking. </p>
              </div>-->
                
                
  </div>
        
        </div>

<?php 
}
?>   