
 </div><!-- /#wrap -->
  
	<!--Footer Starts -->
		<div class="twtbox">
			<div id="twtbox_spread" style="width: 700px; margin: auto;">
				<img src="<?php bloginfo('stylesheet_directory');?>/images/twt-icon.png" width="68" height="55" alt="" />
			</div>
			<div id="twtbox_container">
				<?php dynamic_sidebar('social_media_footer'); 	?>
			</div>
			<div style="clear:both;"></div>
			<script>
				$(document).ready( function() {
					adjust_birdy();
				});
				
				$(window).resize( function() {
					//adjust_birdy();
				});
				
				function adjust_birdy() {
					var width = $('#twtbox_container').width();
					$('#twtbox_spread').css('width', width + 68);
				}
			</script>
		</div>
		
		<div class="footer">
			<div class="container_12 clearfix">
			
				<div class="grid_4">
					<h1>about webento</h1>
					<p>At Webento we are dedicated to expanding the capabilites of the web and making it a friendlier place for people to use.
					<a href="/about">read more</a>
					</p>
				</div>
				
				<div class="grid_4">
				<h1>resources</h1>
				<p>
				Please View Resources Below:
				<ul>
					<li><a href="/why-webento">Why Webento</a></li>
					<!-- <li><a href="/how-we-help">How We Help</a></li> -->
					<!-- <li><a href="/our-approach">Our Approach</a></li> -->
					<!-- <li><a href="/about">Our Team</a></li> -->
					<!-- <li><a href="/press">Press</a></li> -->
					<li><a href="http://support.webento.com/">Support</a></li>
					<li><a href="/support-policy">Support Policy</a></li>
					<li><a href="http://demo.webento.com/">Webento Demo</a></li>
				</ul>
				</p>
				</div>
				
				<div class="grid_4">
					<h1>follow webento</h1>
					<a target="_blank" href="https://twitter.com/#!/webento" id="twtter"></a>&nbsp;&nbsp;
					<a target="_blank" href="https://plus.google.com/u/0/b/105363437807858736991/105363437807858736991/posts" id="gplus"></a>&nbsp;&nbsp;
					<a target="_blank" href="https://www.facebook.com/pages/Webento/318072224904774" id="facebuk"></a>&nbsp;&nbsp;
					<a target="_blank" href="https://feeds.webento.com/webento" id="rssfeed"></a>

					<p>
				   
					<!-- Begin MailChimp Signup Form -->
					<div id="mc_embed_signup">
					<form action="http://webento.us4.list-manage.com/subscribe/post?u=d69b171f88a482f36dd997f37&amp;id=8effc6da48" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
						<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address">
						<button type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe">SUBMIT</button>
					</form>
					</div>
					<!--End mc_embed_signup-->
				</div>
			</div> 
			
			<div class="footend container_12">
				<a href="/terms">Terms</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/privacy-policy">Privacy Policy</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/contact/">Contact</a>
				<span style="float:right">&copy; 2012 Webento, LLC &nbsp;&nbsp;| &nbsp;&nbsp; Proudly powered by Wordpress </span>
			</div>
		</div>
	</body>
</html>
