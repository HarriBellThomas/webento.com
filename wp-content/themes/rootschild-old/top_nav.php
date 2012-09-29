<div class=" container_12">
	<!-- Top Nav Starts -->
	<div class=" grid_12 topnav clearfix" >
		<div class="logo">
			<a href="/"><img src="<?php bloginfo('stylesheet_directory');?>/images/logo.png" width="214" height="65" alt="" /></a>
		</div>
		<div class="grid_9">
			<?php 
				$top_nav = wp_nav_menu(array('theme_location' => 'primary_navigation', 'walker' => new Roots_Navbar_Nav_Walker(), 'echo' => false)); 
				$top_nav = str_replace('href="/', 'href="http://webento.com/', $top_nav);
				echo $top_nav;
				// -- Also change in file header-buddypress.php
				if( !is_user_logged_in() ) { 
			?>
					<script type="text/javascript">
						$(".menu-login a").attr("id", "various1");
					</script>
			<?php
				} else {
					$logout_str		=	wp_logout_url(home_url());
					$logout_str		=	str_replace("&amp;","&",$logout_str);
					
					global $current_user;
					get_currentuserinfo();
					
					$profile_link 	= 	'/members/' . $current_user->user_login . '/profile/';
					$dashboard_link	=	"https://".get_user_meta($current_user->ID, "source_domain", true)."/wp-admin/";
			?>	
					<script type="text/javascript">
						var login_li = $(".menu-login");
						login_li.addClass('dropdown');
						login_li.attr('data-dropdown', 'dropdown');
						login_li.html('<a href="<?php echo $dashboard_link; ?>" class="dropdown-toggle" data-toggle="dropdown">Dashboard <b class="caret"></b></a><ul class="dropdown-menu"><li><a href="<?php echo $profile_link; ?>">Profile</a></li><li><a href="<?php echo $logout_str;?>">Logout</a></li></ul>');
					</script>
			<?php
				}
			?>
		</div>
	</div>
	<!-- Top Nav Ends -->
	   <script type="text/javascript">
		$(document).ready( function() {
			adjust_menu_for_res();
			var screen_width = $(window).width();
			if( screen_width > 600 ) {
				$('#various1').fancybox();
			} else {
				$('#various1').attr('href', '/wp-login.php');
			}
		});
		
		$(window).resize( function() {
			adjust_menu_for_res();
		});
		
		function adjust_menu_for_res() {
			var screen_width = $(window).width();
			if( screen_width <= 480 ) {
				$('.topnav').addClass('blockDropdown');
				$('.topnav .dropdown').each( function() {
					$(this).removeClass('dropdown');
					$('a', this).removeClass('dropdown-toggle');
					$(this).addClass('wasDropdown');
					$('a', this).addClass('was-dropdown-toggle');
					$('.caret').css('display', 'none');
				});
			} else {
				$('.topnav').removeClass('blockDropdown');
				$('.topnav .wasDropdown').each( function() {
					$(this).removeClass('wasDropdown');
					$('a', this).removeClass('was-dropdown-toggle');
					$(this).addClass('dropdown');
					$('a', this).addClass('dropdown-toggle');
					$('.caret').css('display', 'inline-block');
				});
			}
		}
		</script>
	<!-- Lighbox Start -->
	<div style="display: none;">
		<div id="lightboxLogin" style="width:100%;height:400px;overflow:auto;">
		 <?php $args = array(
			'echo' => true,
			'redirect' => site_url( '/wp-admin/' ), 
			'form_id' => 'loginform_lightbox',
			'label_username' => __( 'Username' ),
			'label_password' => __( 'Password' ),
			'label_remember' => __( 'Remember Me' ),
			'label_log_in' => __( 'Log In' ),
			'id_username' => 'user_login',
			'id_password' => 'user_pass',
			'id_remember' => 'rememberme',
			'id_submit' => 'wp-submit',
			'remember' => true,
			'value_username' => NULL,
			'value_remember' => false ); 
		?>
		<div style="text-align:center;">
			<h1><a href="/" title="Webento"><img src="<?php echo get_stylesheet_directory_uri();?>/images/logo_gray.png" /></a></h1>
		</div>
		<?php 
		$url_webento = site_url('/wp-login.php', 'https');
		$url_webento = str_replace("http://","https://", $url_webento);
		?>
		<div class="loginbox_lightbox">
			<form name="loginform_lightbox" id="loginform_lightbox" action=" <?php echo $url_webento; ?>" method="post">

			<div class="login-username lightbox_left">
			<label for="user_login">Username</label>
			<input type="text" name="log" id="user_login" class="input" value="" size="20" tabindex="10" />
			</div>
			
			<div class="login-password lightbox_left">
			<label for="user_pass">Password</label>
			<input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" />
			</div>
			
			<div class="login-submit lightbox_left">
			<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Login" tabindex="100" />
			<input type="hidden" name="redirect_to" value="/wp-admin/" />
			</div>
			
			<div style="clear:both;"></div>
			
			<div class="login-remember lightbox_left"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> Remember Me</label></div>
			
			
			<div class="lightbox_left forget_password" >
				<a href="/wp-login.php?action=lostpassword" title="Lost Password">Forget your Password</a>
			</div>
			<div style="clear:both;"></div>
			</form>
		</div>
		
		<div style="clear:both;"></div>
		<div style="margin:15px;">
			<div class="titleText demoText">Not a Member yet ?</div>
			
			<div class="lightbox_left centerText" style="margin-left: 0px;">
				<div class="register_lightbox">
					<a href="https://webento.com/register" title="Register" alt="A">Register</a>
				</div>
			</div>
			
			<div class="lightbox_left borderDiv" style="">or</div>
			
			<div class="lightbox_right">
				<div class="trynow_lightbox">
					<a style="background-color:#00749b" href="http://demo.webento.com" title="Try Now">Try a Demo</a>
				</div>
			</div>
			<div style="clear:both;"></div>
			
		</div>
		<style type="text/css">
			label input {
				display: inline;
			}
			.login-remember {
				font-size: 12px;
				margin-top: 5px;
			}
			
			#fancybox-outer {
				width: 550px !important;
			}

			#fancybox-content {
				margin: 0px auto;
			}
			
			#fancybox-wrap {
				width: 560px;
			}
		</style>
		</div>
	</div>
	<!-- Lighbox End -->
</div>