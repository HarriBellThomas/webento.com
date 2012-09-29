<?php get_header(); ?>
  <?php roots_content_before(); ?>
    <div id="content" class="<?php echo CONTAINER_CLASSES; ?>">
    <?php roots_main_before(); ?>
		<!-- catchbox -->
        <div class="catchbox">
			<h3><strong><?php _e('Page Not Found', 'roots'); ?></strong></h3>
			We offer services that will help take your business to the next level
		</div>
        <!-- catchbox ends -->
    <div class="container_12">
    <div class="grid_12">
      <div id="main" class="innercont" role="main">
        <div class="alert alert-block fade in">
          <a class="close" data-dismiss="alert">&times;</a>
          <p><?php _e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'roots'); ?></p>
        </div>
        <div style="float:left;font-size: 16px;" class="404page">
			<p><?php _e('Please try the following:', 'roots'); ?></p>
			<ul>
			  <li style="list-style-type: circle ; margin-left: 25px;"><?php _e('Check your spelling', 'roots'); ?></li>
			  <li style="list-style-type: circle ; margin-left: 25px ;"><?php printf(__('Return to the <a href="%s">home page</a>', 'roots'), home_url()); ?></li>
			  <li style="list-style-type: circle ; 	margin-left: 25px ;"><?php _e('Click the <a href="javascript:history.back()">Back</a> button', 'roots'); ?></li>
			</ul>
		</div>
		<div style="float:right;">
			<a href="/" title="Webento"><img src="<?php echo get_stylesheet_directory_uri();?>/images/logo_gray.png"></a>
		</div>
		<div style="clear:both;padding-bottom: 15px;"></div>
        <?php get_search_form(); ?>
      </div><!-- /#main -->
    <?php roots_main_after(); ?>
    </div><!-- /#content -->
  <?php roots_content_after(); ?>
  </div><!-- /#row -->
  </div><!-- /#container -->
<?php get_footer(); ?>