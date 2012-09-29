<?php 
/**
Template Name: Website Builder Template
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
						
      <div style="float:right; text-align:center;" class="grid_12">
<div style="float:right" class="grid_4">
<h2>Business</h2>
<p style="margin-bottom:20px"><span style="margin-right:130px;" class="dollar-sign">$</span><br>
<span class="prices">499</span><span class="per-month">/yr</span><br>
<a href="http://webento.mbt/register" class="button">Start free trial!</a></p>
<ul>
<li><strong>Free</strong> setup</li>
<li><span class="highgry">10 GB</span> of Storage</li>
<li>Custom Themes</li>
<li>Custom Plugins</li>
<li>SSL Certification</li>
<li>Super Admin Control</li>
</ul>
</div>
<div style="float:right" class="grid_4">
<h2 style="font-weight:bold;">Pro</h2>
<p style="margin-bottom:20px"><span class="dollar-sign">$</span><br>
<span class="prices">99</span><span class="per-month">/yr</span><br>
<a href="http://webento.mbt/register" class="button">Start free trial!</a></p>
<ul>
<li><strong>Free</strong> setup</li>
<li><span class="highgry">2 GB</span> of Storage</li>
<li>10+ Mobile Responsive Themes</li>
<li>20+ Pro Plugins</li>
<li>24/7/365 Email Support</li>
<li>Multiple Sites</li>
<li>Create Users</li>
<li>Integrated Statistics</li>
</ul>
</div>
<div style="float:right" class="grid_4">
<h2>Basic</h2>
<p style="margin-bottom:20px"><span class="dollar-sign">$</span><br>
<span class="prices">79</span><span class="per-month">/yr</span><br>
<a href="http://webento.mbt/register" class="button">Start free trial!</a></p>
<ul>
<li><strong>Free</strong> setup</li>
<li><span class="highgry">100 MB</span> of Storage</li>
<li>5 Mobile Responsive Themes</li>
<li>5 Plugins</li>
<li>Access to Forums</li>
</ul>
</div>
<div class="themes">
<h1 style="text-align:center; margin: 60px 60px 0px 0;">Try out a template!</h1>
<h2 style="text-align:center;">Choose from one of our templates to create a customized site in minutes. You can select from one of these themes to launch your very own site. </h2>
<div style="border: CCC solid; padding: 10px; width:29%;" class="grid_4 srvcsec">
					 <a alt="Blog Template" target="_blank" href="http://blog.webento.mbt"><br>
					 	<img width="260" height="105" style="border:#CCC solid" alt="Blog Template" src="http://webento.mbt/wp-content/themes/roots/img/blog.jpeg"></a>    <p></p>
<h2>Blog</h2>
<p></p></div>
<div style="border: CCC solid; padding: 10px; width:29%;" class="grid_4 srvcsec">
					 <a target="_blank" href="http://business.webento.mbt"><br>
					 	<img width="260" height="105" style="border:#CCC solid" alt="Business Template" src="http://webento.mbt/wp-content/themes/roots/img/business.jpeg"></a><p></p>
<h2>Business</h2>
<p></p></div>
<div style="border: CCC solid; padding: 10px; width:29%;" class="grid_4 srvcsec">
					 <a target="_blank" href="http://shop.webento.mbt"><br>
					 	<img width="260" height="105" style="border:#CCC solid" alt="Ecommerce Template" src="http://webento.mbt/wp-content/themes/roots/img/shop.jpeg"></a> <p></p>
<h2>Shop</h2>
<p></p></div>
<div style="border: CCC solid; padding: 10px; width:29%;" class="grid_4 srvcsec">
						<a target="_blank" href="http://news.webento.mbt"><br>
					    <img width="260" height="105" style="border:#CCC solid" alt="News Template" src="http://webento.mbt/wp-content/themes/roots/img/news.jpeg"></a>  <p></p>
<h2>News</h2>
<p></p></div>
<div tyle="border: CCC solid; padding: 10px; width:29%;" class="grid_4 srvcsec">
					 <a target="_blank" href="http://event.webento.mbt"><br>
					 	<img width="260" height="200" style="border:#CCC solid; height:245px" alt="Event Template" src="http://webento.mbt/wp-content/themes/roots/img/event.jpeg"></a> <p></p>
<h2>Event</h2>
<p></p></div>
<div style="border: CCC solid; padding: 10px; width:29%" class="grid_4 srvcsec">
					 <a target="_blank" href="http://education.webento.mbt"><br>
					 	<img width="260" height="105" style="border:#CCC solid" alt="Education Template" src="http://webento.mbt/wp-content/themes/roots/img/education.jpeg"></a> <p></p>
<h2>Education</h2>
<p></p></div>
</div></div>
<div style="text-align:center; margin-top: 60px; margin-bottom: 60px;" class="grid_12"><span class="expweb">Start your <strong>free</strong> trial today!</span> <a rel="nofollow" href="/register" class="button">Create your website now</a>
</div>
<div class="grid_12">
<div class="grid_4">
<h4 class="p5">Is there a setup fee?</h4>
<p class="pfaq">No. Webento does not charge any setup fees on any of our plans.</p>
<h4 class="p5">Can I cancel my account at any time?</h4>
<p class="pfaq">Yes! If you ever decide that Webento isn’t the best web platform for your business, simply cancel your account from your dashboard.</p>
<h4 class="p5">Can I change my plan later on?</h4>
<p class="pfaq">You sure can! You can upgrade or downgrade your plan at any time.</p>
</div>
<div class="grid_4">
<div class="gradient-box">
<h4 class="p5">Can I use my own domain name?</h4>
<p class="pfaq">Yes! Your website can use an existing domain name that you own.</p>
<h4 class="p5">Are custom plans available?</h4>
<p class="pfaq">Absolutely! Please <a href="http://webento.mbt/contact">contact us</a> and we will come up with the ultimate solution to best fit your website needs.</p>
<h4 class="p5">Are all plans annual?</h4>
<p class="pfaq">Yes but you can always contact us for a custom plan.</p>
</div>
</div>
<div class="grid_4">
<h4 class="p5">Do I need to enter my credit card details to sign up?</h4>
<p class="pfaq">No. You can sign up and use Webento for 14 days without entering any credit card details. When you decide to launch your website, you will need to pick a plan and enter your credit card details.</p>
<h4 class="p5">Do I need a web host or my own service provider?</h4>
<p class="pfaq">No. All Webento plans include secure hosting for your website. Webento uses the best servers and networks to ensure your website is reliable and fast.</p>
</div>
</div>
            
	
      
            <!--</div>
   </div>-->
 
 
   					</div>
		
		 
<?php get_footer(); ?> 