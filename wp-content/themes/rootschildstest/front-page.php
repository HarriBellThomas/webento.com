<?php get_header(); ?>

  <?php roots_content_before(); ?>
    <div id="content" class="<?php //echo CONTAINER_CLASSES; ?>">
    <?php roots_main_before(); ?>

<div id="main" class="<?php //echo MAIN_CLASSES; ?>" role="main">
	  
  <div id="baner" class=" clearfix"> 	  
	<div class=" container_12">	  
	  <!-- Banner Starts -->
		<div class="grid_12 banner 	clearfix">
		  <div class="grid_6  bannertxt">
			<h1><span class="colorwhite">[web-en-to: </span><span class="colorblue">wuh-ben-toh!]</span></h1>
			<h2 class=" colorgry">An awesome place where memorable mobile responsive websites are created.</h2>	
			<div>
				<a class="button" href="<?php get_bloginfo('url');?>/register">Start Your Free Trial!</a>
			</div>
			</div>
			<div class=" grid_6" align="center" style="padding-bottom:1em;" >
				<div class="bannerslider">
					<div style=" text-align:center;   position: relative; top:150px ; left:0; z-index:1 " class="hidearrows">
						<span style="float:left; border:none">
							<a href="javascript:void(null)" onclick="return false" class="lof-previous" ><img alt="" src="<?php bloginfo('stylesheet_directory');?>/images/arrow-left.gif" style="border:none"></a>
						</span>
						<span style="float:right; border:none">
							<a href="javascript:void(null)" onclick="return false" class="lof-next" ><img alt="" src="<?php bloginfo('stylesheet_directory');?>/images/arrow-right.gif" style="border:none"></a>
						</span>
					</div>
					<div style="clear:both"></div>
					<?php if (function_exists('easing_slider')){ easing_slider(); }; ?>
				</div>
			</div>
		</div>
	 <!-- Banner Ends -->
	</div>	  
 </div>	  
	  
	  <!-- catchbox -->
        <div class="catchbox">
        <!-- <img src="<?php bloginfo('stylesheet_directory');?>/images/arrowpoint.png"   alt="" /> -->
        Check out our new <a href="http://webento.com/website-builder">Website Builder</a>, build your new site in under five minutes! </div>
        <!-- catchbox ends -->
        
        <!-- Slogan Box Starts -->
<div class="sloganbx">
			<div class="container_12">
        	<h1>What we do at <span class="colorblue"><a href="<?php get_bloginfo('url');?>/about"><b>webento</b></a></span></h1>
           <!-- <h2 class="colorgry">We Innovate.</h2> -->
            </div>
        </div>
        <!-- Slogam Ends -->
        
        <!--Tab box start -->
        <div class="container_12">
        	 
             <div class="conttab">
              <a id="OS" class="seltab" <href="javascript:void(null)" onclick="changeTab('ourServices')">Our Services </a>
			 <!-- <a id="OS" class="seltab" <a href="http://webento.com/products">Our Products</a> -->
              <a id="OP" href="javascript:void(null)" onclick="changeTab('ourProducts')">Our Products</a>
            <!-- <a class="seltab" href="#">Our Services</a> 
             <a href="#">Our Products</a> -->
             </div>
			 
			 
    <div class="contbox clearfix">
        <h2 style=" text-align:center">We offer services and products that will help take your business to the next level.</h2>
       
        <div id="ourServices" >        
            <div class="servicebx  icon1 grid_5">
            <a href="http://webento.com/search-engine-optimization"><h1>Search Engine Optimization (SEO)</h1></a>
            <h3 class="colorgry">Is the usability of your site affecting 
    your users ability to convert. Let us help 
    you find the sticking points.</h3>
        <ul >
            <li class="list"> <!--<a href="<?php echo home_url(); ?>/seo/#seo-0"><strong>SEO Audit</strong></a> -->SEO Audit</li>
            <li class="list"><!--<a href="<?php echo home_url(); ?>/seo/#seo-1"><strong>SEO Consulting</strong></a>SEO Consulting</li> -->
            <li class="list"><!--<a href="<?php echo home_url(); ?>/seo/#seo-2"><strong>Link Building Strategies</strong></a>-->Link Building Strategies</li>
            <li class="list"><!--<a href="<?php echo home_url(); ?>/seo/#seo-3"><strong>SEO Copywriting</strong></a>-->SEO Copywriting</li>
           <!-- <li class="colorblue"><a href="#"><strong>Blogging Strategies </strong></a></li>-->
        </ul>
        </div>
                   <div class="servicebx grid_5 icon2">
            <a href="http://webento.com/web-optimization"><h1>Website Optimization</h1></a>
            <h3 class="colorgry">At Webento.com we have an experienced 
    team with a track record of successful client projects.</h3>
        <ul >
            <li class="list"><strong><!--<a href="#">Compression</a>--></strong>Compression</li>
            <li class="list"><strong><!--<a href="#">Minification</a>--></strong>Minification</li>
            <li class="list"><!--<a href="#"><strong>Caching / Asset Management</strong></a>-->Caching / Asset Management</li>
        </ul>
        </div>
        
        
        <div class="servicebx grid_5 icon3" style="height:auto; clear:both;">
            <a href="http://webento.com/usability"><h1>Usability Testing</h1></a>
            <h3 class="colorgry">We offer user experience expertise so that you can better understand and ultimately deliver a great experience to your users.</h3>
        <ul >
            <li class="list"><!--<a href="#"><strong>Usability Testing</strong></a>-->Heuristic Analysis</li>
            <li class="list"><!--<a href="#"><strong>Heat Map Analysis</strong></a>-->Heat Map Analysis</li>
            <li class="list"><!--<a href="#"><strong>Actionable Insights</strong></a>-->Actionable Insights</li>
        </ul>
        </div>
        
        <div class="servicebx grid_5 icon4" style="height:auto">
            <a href="http://webento.com/web-development"><h1>Web Development</h1></a>
            <h3 class="colorgry">We provide white hat search engine 
    optimization services, including:</h3>
        <ul >
            <li class="list"><!--<a href="#"><strong>Custom Development</strong></a>-->Custom Development</li>
            <li class="list"><!--<strong><a href="#">Hosting</a></strong>-->Hosting</li>
			<li class="list"><!--<strong><a href="#">Hosting</a></strong>-->Mobile Responsive</li>
        </ul>
        </div>
		
		<div style="clear:both;"></div>
		<div align="center" class="botcontbx">
			<h2 class="colorgry" style="margin-bottom: 0px;color:#000 !important;">We are in the works creating more ways to innovate your business.
			And constantly striving to improve what we offer...</h2>
			<a href="http://webento.com/contact/" class="button">Contact Us</a>
		</div>
    
    </div>
    <!--       repeat text          -->
    <div id="ourProducts"  style="display:none"> 
    
  
    <div class="servicebx icon5  grid_5">
    	<h1>EASY TO USE</h1>
        <h3 class="colorgry">Wordpress is easily one of the most well documented CMS on the market and is only limited by your imagination.  We include tutorial videos as well as support for all clients.
		<ul >
		 <!-- <li class="colorblue"><a href="#"><strong>SEO Audit</strong></a></li>
			<li class="colorblue"><a href="#"><strong>SEO Consulting</strong></a></li>
			<li class="colorblue"><a href="#"><strong>Link Building Strategies</strong></a></li>
			<li class="colorblue"><a href="#"><strong>SEO Copywriting</strong></a></li>
		</ul> -->
    </div>
	
    <div class="servicebx icon6 grid_5 ">
    	<h1>BACKEND</h1>
        <h3 class="colorgry">Built on the trusted Wordpress Framework, but with enhanced performance and security.
		</h3>
		 <!-- <ul >
			<li class="colorblue"><strong><a href="#">Compression</a></strong></li>
			<li class="colorblue"><strong><a href="#">Minification</a></strong></li>
			<li class="colorblue"><a href="#"><strong>Caching / Asset Management</strong></a></li>
		</ul> -->
    </div>
       
    <div class="servicebx grid_5 icon7" style="height:auto; clear:both">
    	<h1>CUSTOMIZABLE</h1>
        <h3 class="colorgry">Customize your site with a plethora of built in settings.  You can choose from some of our professional themes and plugins to create a truly unique site.</h3>
		<ul >
			<!-- <li class="colorblue"><a href="#"><strong>Usability Testing</strong></a></li>
			<li class="colorblue"><a href="#"><strong>Heat Map Analysis</strong></a></li>
			<li class="colorblue"><a href="#"><strong>Actionable Insights</strong></a></li>
		</ul> -->
    </div>
    
    <div class="servicebx grid_5 icon8 " style="height:auto;">
    	<h1>AFFORDABLE</h1>
        <h3 class="colorgry">We built the Website Builder because we wanted an affordable option for our clients who needed hosting.  Starting at only $15/month.</h3>
		<ul >
			<!-- <li class="colorblue"><a href="#"><strong>Custom Development</strong></a></li>
			<li class="colorblue"><strong><a href="#">Hosting</a></strong></li>
		</ul> -->
    </div>
	
	<div style="clear:both;"></div>
	<div align="center" class="botcontbx">
		<h2 class="colorgry" style="margin-bottom: 0px; color:#000 !important;">We are in the works creating more ways to innovate your business.
		And constantly striving to improve what we offer...</h2>
		<a href="http://webento.com/register" class="button">Get Started</a>
	</div>
   </div> 
    <!-- repeat text ends -->

    </div>
           
</div>
        <!-- Tab box ends -->
		<div style="clear:both;margin:1em 0px 2em 0px;"></div>
        <?php //roots_loop_before(); ?>
        <?php //get_template_part('loop', 'page'); ?>
        <?php //roots_loop_after(); ?>
      </div><!-- /#main -->
    <?php roots_main_after(); ?>
    <?php roots_sidebar_before(); ?>
      <aside id="sidebar" class="<?php echo SIDEBAR_CLASSES; ?>" role="complementary">
      <?php roots_sidebar_inside_before(); ?>
        <?php //get_sidebar(); ?>
      <?php roots_sidebar_inside_after(); ?>
      </aside><!-- /#sidebar -->
    <?php roots_sidebar_after(); ?>
    </div><!-- /#content -->
  <?php roots_content_after(); ?>
<?php get_footer(); ?>
