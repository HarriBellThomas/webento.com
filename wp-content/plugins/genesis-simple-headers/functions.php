<?php
// deal with activation

// require Genesis 1.5 upon activation
register_activation_hook(__FILE__, 'simpleheaders_activation_check');
function simpleheaders_activation_check() {

	$latest = '1.5';

	$theme_info = get_theme_data(TEMPLATEPATH.'/style.css');

	if( basename(TEMPLATEPATH) != 'genesis' ) {
		deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself
		wp_die('Sorry, you can\'t activate unless you have installed <a href="http://www.studiopress.com/themes/genesis">Genesis</a>');
	}

	if( version_compare( $theme_info['Version'], $latest, '<' ) ) {
		deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself
		wp_die('Sorry, you can\'t activate without <a href="http://www.studiopress.com/support/showthread.php?t=19576">Genesis '.$latest.'</a> or greater');
	}
}

// Remove genesis version
remove_action( 'after_setup_theme', 'genesis_custom_header', 10 );

// gets included in the site header
function simpleheader_style() {
	global $ghs_extras, $ghs_extras_2;
	if (get_header_image()) {		
	?>
		<style type="text/css">
			<?php echo THEME_CSS_VALUE; ?> {
			width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
			height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
			<?php echo BACKGROUND_TYPE; ?>: url(<?php header_image(); ?>) no-repeat!important;
			<?php echo $gsh_extras; ?>
			}
			<?php echo $ghs_extras_2; ?>
		</style>
	<?php
	}
}

// gets included in the admin header
function admin_simpleheader_style() {
	?>
	<style type="text/css">
		#headimg {
		width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
		height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		}
	</style>
	<?php
}

add_action( 'genesis_meta', 'simpleheader_style' );
?>
