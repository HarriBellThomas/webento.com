<?php
/*
Template Name: Long Copy
Template Description: A simple long copy landing page.
*/

$socialShareType = premise_social_share_get_social_share_type() == 1;
$enhancedFunc = $socialShareType ? '_enhanced' : '';
$facebookUrlFunc = "premise_the_social_share{$enhancedFunc}_facebook_share_url";
$twitterUrlFunc = "premise_the_social_share{$enhancedFunc}_twitter_share_url";
$blank = $enhancedFunc = $socialShareType ? '' : '_blank';
get_header();
	?>
	<div id="content" class="hfeed">
		<?php the_post(); ?>
		<div class="hentry">
			<?php include('inc/headline.php'); ?>
			<div class="entry-content">
				<?php if(premise_has_social_share_shared_page()) { ?>
					<?php premise_the_social_share_after_share_page(); ?>
				<?php } else { ?>
					<div class="teaser-content"><?php premise_the_social_share_teaser_page(); ?></div>
					<div class="teaser-share-box">
						<div class="teaser-share-box-inside">
							<div class="teaser-share-message"><?php premise_the_social_share_share_message(); ?></div>
							<div class="teaser-share-icons">
								<a target="<?php echo esc_attr($blank); ?>" id="twitter-share-icon" href="<?php $twitterUrlFunc(); ?>">
									<img src="<?php premise_the_social_share_twitter_icon(); ?>" alt="Twitter Share Icon" />
									<span><?php _e('Tweet This', 'premise' ); ?></span>
								</a>
								<a target="<?php echo esc_attr($blank); ?>" id="facebook-share-icon" href="<?php $facebookUrlFunc(); ?>">
									<img src="<?php premise_the_social_share_facebook_icon(); ?>" alt="Facebook Share Icon" />
									<span><?php _e('Share This', 'premise' ); ?></span>
								</a>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
							
							<?php if(!$socialShareType) { ?>
							<p class="teaser-share-clickthrough"><a href="<?php premise_social_share_the_shared_page_url(); ?>"><?php _e('Click here when you have shared this page.', 'premise' ); ?></a></p>
							<?php } ?>
						</div>
					</div>
					
					<?php if(!$socialShareType) { ?>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							$('.teaser-share-icons a').click(function(event) {
								event.preventDefault();
								var $this = $(this);
								var href = $this.attr('href');
								
								var height = 580;
								var width = 980;
								var left = (screen.width / 2) - (width / 2);
								var top = (screen.height / 2) - (height / 2);
								
								window.open(href, "premise_social_share", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+width+', height='+height+', top='+top+', left='+left);
								
								setTimeout('jQuery(".teaser-share-clickthrough").show(); jQuery(".teaser-share-message,.teaser-share-icons").hide();', 5000);
							});
						});
					</script>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div><!-- end #content -->
	<?php
get_footer();