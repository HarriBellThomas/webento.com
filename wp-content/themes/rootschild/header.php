<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
  <meta charset="utf-8">

  <title><?php $title = wp_title('|', false, 'right'); 
  $title = str_replace('|  |', '|', $title); 
  echo $title; //bloginfo('name'); ?></title>

  <meta name="viewport" content="width=device-width">

  <?php roots_stylesheets(); ?>

  <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> Feed" href="<?php echo home_url(); ?>/feed/">

	<script src="<?php echo get_template_directory_uri(); ?>/js/libs/modernizr-2.5.3.min.js"></script>
	<script src="<?php home_url()?>/wp-content/themes/roots/js/Main.js" type="text/javascript"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo get_template_directory_uri(); ?>/js/libs/jquery-1.7.1.min.js"><\/script>')</script>
	<script charset="utf-8" src="//widgets.twimg.com/j/2/widget.js"></script>
	<!--<script type="text/javascript" src="<?php //echo get_stylesheet_directory_uri(); ?>/lightbox/js/jquery.lightbox-0.5.js"></script>
	
	<link rel="stylesheet" type="text/css" href="<?php //echo get_stylesheet_directory_uri(); ?>/lightbox/css/jquery.lightbox-0.5.css" media="screen" />-->
	<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/fancybox/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
	
	
  <?php roots_head(); ?>
  <?php wp_head(); ?>

</head>

<body <?php //body_class(roots_body_class()); ?>>

	<!--[if lt IE 7]><p class="chromeframe">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
	
	<?php roots_header_before(); ?>
    <header id="banner"  role="banner">
      <?php roots_header_inside(); ?>
		<!-- Header Starts -->
        	
            <div id="header" class=" clearfix">
				<?php 
				//INCLUDED SO HAVE TO CHANGE IN ONE FILE
				include_once('top_nav.php');
				?>
            	
            </div>
            
        <!-- Header Ends -->
		
    </header>
  <?php roots_header_after(); ?>
  <?php roots_wrap_before(); ?>
  
