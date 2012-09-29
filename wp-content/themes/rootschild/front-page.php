<?php get_header( 'buddypress' ) ?>

<?php roots_content_before(); ?>
    
    <div id="content" class="<?php //echo CONTAINER_CLASSES; ?>">
    <?php roots_main_before(); ?>

<div id="main" class="<?php //echo MAIN_CLASSES; ?>" role="main">
      
  <div id="baner" class=" clearfix">      
    <div class=" container_12">   
      <!-- Banner Starts -->
        <div class="grid_12 banner  clearfix">

        <div><h2><span id="hometop">Create your website today with webento</span></h2>
            <!-- <div><a class="button" href="#freetrial" style="margin: 50px 0 0 8px !important;">Start Your Free Trial!</a><h2><span id="hometop">Create a website today with webento</span></h2></div> -->
            </div>

            

          <div class="grid_6  bannertxt" style="width:40%;">
			<h2 class="bannertxt_heading">Reasons to Choose Webento</h2>
            <ul style="list-style-position:inside; width:100%;">
            <li>Easy to use website builder</li> 
            <li>Free support provided</li> 
            <li>Mobile responsive themes</li>       
            <li>Secure shopping cart</li>
            <li style="border-bottom:none">Reliable hosting included</li>
            </ul>
            
            <div style="text-align:center; margin-top:10px;"><a style="float:left; margin-left:40px !important;" class="button" href="http://webento.com/register">Learn More</a></div>
            </div>


            <div class="registerform grid_6" align="center" style="float: right;
    margin-right: 20px;
    padding-bottom: 1em;
    width: 52%;" >
                <?php
				if ( is_user_logged_in() ) {
				?>
				<div class="bannerslider">
                    <div style=" text-align:center;   position: relative; top:150px ; left:0; z-index:1 " class="hidearrows">
                        <span style="float:left; border:none">
                            <a href="javascript:void(null)" onclick="return false" class="lof-previous" ><img alt="" src="<?php bloginfo('stylesheet_directory');?>/images/arrow-left.gif" style="border:none;margin-bottom: -30px;margin-left: 3px;"></a>
                        </span>
                        <span style="float:right; border:none">
                            <a href="javascript:void(null)" onclick="return false" class="lof-next" ><img alt="" src="<?php bloginfo('stylesheet_directory');?>/images/arrow-right.gif" style="border:none;margin-bottom: -30px;margin-right: 3px; "></a>
                        </span>
                    </div>
                    <div style="clear:both"></div>
                    <?php if (function_exists('easing_slider')){ easing_slider(); }; ?>
                </div>
                
                <?php
                } else {
					echo do_shortcode('[bp_ajax_register_form]');
				}
				?>
                
            </div>
			
            
            
        </div>
     <!-- Banner Ends -->
 </div>
       </div>  
 

  <div class="catchbox" style="background:url(images/catchbox-divider.png) center 26px no-repeat #E9E9E9">
        <!-- <img src="<?php bloginfo('stylesheet_directory');?>/images/arrowpoint.png"   alt="" /> -->
        Create your website <strong>for free!</strong> </div>
        <!-- catchbox ends -->



<div class="container_12">

<!-- <div class="grid_8" style="padding-bottom:40px;">

    <div style="margin-top:40px; margin-bottom:40px;"><h1 style="font-size 2.8em; font-weight:normal; margin-bottom:10px;">Webento offers a complete website solution</h1>
        <p style="margin-top: -5px; line-height: 25px">Webento is a hosted website solution that allows you to set up and run your own website in minutes. Pick a website template and put in your desired content. With Webento it's easy to create a website and there's no software to download or maintain.</p></div>


<div class="grid_6" style="padding-bottom:10px;">
      <h2>Responsive Themes</h2>
        <div class="midp">Increase mobile revenue and conversions with the help of our mobile responsive themes.</div>
    </div>    
      
  
<div class="grid_6" style="padding-left:5px; padding-bottom:25px;">
    <h2>Solid WordPress Framework</h2>
        <div class="midp">Everything runs on WordPress, which makes it easy to setup your theme.</div>
      
     </div> 

    <div class="grid_6"> 
    <h2>Search Optimized</h2> 
        <div class="midp">Every theme comes with state of the art code and smart design architecture.</div>
      
</div>

      <div class="grid_6" style="padding-left:5px;">  
        <h2>Under Two Minutes</h2>
        <div class="midp">Create your website and have it live in under two minutes, scout's honor!</div>
      
      
  </div>  

</div> -->

<div class="grid_9">

    <div style="" class="mid_content"><h2>Webento is your complete website solution</h2>
        <p style="line-height: 25px; font-size:20px">Pick a website template and replace the pre-made content with your own.</br> It's  that easy.</p></div>



<div class="servicebx grid_4 icon2">    
<h2>Responsive</h2>
    
    <p style="line-height:25px; font-size:17px;">Increase mobile revenue and conversion.</p>
</div>

<div class="servicebx grid_4 icon4">

<h2>Optimized</h2>

<p style="line-height:25px; font-size:17px;">Every theme is search engine optimized.</p></div> 


<div class="servicebx grid_4 icon1">
<h2>Solid</h2>

<p style="line-height:25px; font-size:17px;">Everything runs on WordPress framework.</p></div>


<div class="servicebx grid_4 icon3"  >
<h2>Support</h2>

<p style="line-height:25px; font-size:17px;">Professional videos and support 24/7.</p></div>

<div class="servicebx grid_4 icon6"  >
<h2>Unlimited</h2>

<p style="line-height:25px; font-size:17px;">Unlimited support.  Unlimited updates.</p></div>

<div class="servicebx grid_4 icon5"  >
<h2>Rapid</h2>

<p style="line-height:25px; font-size:17px;">Create a live website in two minutes!</p></div>

<div class="grid_11">
<span class="expweb">Start your <b>free</b> trial today!</span>
<a class="button" href="/register" rel="nofollow">Create your website now</a>
</div>

</div>

<div class="grid_3">
    <div class="registration_right">
                    <!-- <h1 style="text-align:center">Why Choose Webento</h1> -->
                                        <!-- <p>Fill out this one-step form and you'll be blogging seconds later.</p> -->
                      <div class="register-section" id="antisplog-multicheck">
      </div>
  
                    <span id="detailr">Webento Pro is perfect for entrepreneurs, individuals or businesses of any level.</span>

                    <ul>
                  <li>Free Setup</li>
                <li>2 GB of Storage</li>
                <li>10+ Mobile Responsive Themes</li>
                <li>20+ Pro Plugins</li>
                <li>24/7 Support</li>
                <li>All for the same price as one meal per month - $8.25!</li>
            <li class="nobull"><a href="http://webento.com/website-builder" target="_blank">See a Comparison of Free, Pro and Business »</a></li>
    </ul>
                    
            


                </div></div>


 <div id="container_12">
<div class="grid_12" style="margin-top:20px;">
    <hr>
    <div class="grid_4 testimonial">
      <div class="full_quote">
        <div class="blockquotes">"Webento is a must use for every entrepreneur. With this easy to use web solution, entrepreneurs save time and money, so they can focus on other aspects of their business."</div>
        <div  class="botquote"><span class="avatar jellyman"></span>Matt Jelmini<br>Entrepreneur, Founder of <a target="_blank" rel="nofollow" href="http://minutetillsix.com">MINUTETILLSIX.COM</a></div>
      
  </div>
    </div>

<div class="grid_4 testimonial">
      <div class="full_quote">
        <div class="blockquotes">"I've talked to a lot of people about website builders, and the near-unanimous response was that Webento offered the easiest-to-use full-service platform in existence."</div>
        <div  class="botquote"><span class="avatar fireman"></span>Kevin Walker<br>Entrepreneur, Founder of <a target="_blank" rel="nofollow" href="http://midwesttomatofest.com">Midwest Tomato Fest</a></div>
      
      </div>
    </div>

    <div class="grid_4 testimonial">
      <div class="full_quote">
        <div class="blockquotes">"I am only 48 hours into this website experiment but I am seriously happy about running a website on Webento. Powerful features and easy to set up, running a website shouldn't be this much fun!"</div>
        <div  class="botquote"><span class="avatar joey"></span>Joseph Bailey<br>Accountant, Founder of <a target="_blank" rel="nofollow" href="http://spnveconomy.com">South Point Economy</a></div>
      
      </div>
    </div>
</div>
</div>



</div>


<?php get_footer() ?>
