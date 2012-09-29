<?php get_header(); ?>
	<!-- catchbox -->
	<div class="catchbox">
		<?php catchBoxContent(); ?>
	</div>
	<!-- catchbox ends -->
  <?php roots_content_before(); ?>
    <div id="content" class="clearfix container_12 innercont <?php //echo CONTAINER_CLASSES; ?>">
		<div class="blog_class">
			<?php roots_main_before(); ?>
			<div class="clearfix" style="border: 1px #CCC solid; padding: 20px;width:91.8%;">
				<?php roots_loop_before(); ?>
				<?php get_template_part('loop', 'single'); ?>
				<?php roots_loop_after(); ?>
			</div>
		</div>
		
		<div class="leftcol floatright blog_nav" >
            <ul>
            	<li><?php get_search_form(); ?></li>
				<?php //dynamic_sidebar( 'blog_loop_sidebar' ); ?>
				<?php if ( !dynamic_sidebar('blog_loop_sidebar') ) : ?>
				   <li><img src="<?php home_url()?>/wp-content/themes/roots/img/icon1.png" width="35" height="35" alt="" /><a href="#">Social Box with subscription</a></li>
                <li><img src="<?php home_url()?>/wp-content/themes/roots/img/icon2.png" width="35" height="35" alt="" /><a href="#">Archive Dropdown </a></li>
                <li><img src="<?php home_url()?>/wp-content/themes/roots/img/icon3.png" width="35" height="35" alt="" /><a href="#">Twitter Feed</a></li>
				<?php endif; ?>
            </ul>
		</div>
		<div style="clear:both;"></div>	
    </div><!-- /#content -->
  <?php roots_content_after(); ?>
<?php get_footer(); ?>