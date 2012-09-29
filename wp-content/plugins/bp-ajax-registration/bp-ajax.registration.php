<?php
/*
Plugin Name: Ajax Buddypress Registration
Version: 0.1
Author: Hassan Usmani
Author URI: http://www.mindblazetech.com
Description: Registeration to buddypress using Ajax
*/

function bp_ajax_register_form_function() { 

ob_start();
?>

<style>


#ajax_signup_form { text-align:left; color:#cfcfcf; } 
#ajax_signup_form input[type=text], #ajax_signup_form input[type=password] {
	width:95% !important; height:1.7em !important; border-radius:4px !important;
}

#ajax_signup_form label, #ajax_signup_form span.label {
    display: block; font-weight: bold; margin: 10px 0 4px; text-align: left; color:#DDD;
}

#ajax_signup_form #blog-details-section, #ajax_signup_form #profile-details-section {
	width:auto;	float:none;
}

#ajax_signup_form.standard-form div div.error {
    background: none repeat scroll 0 0 #E41717; border-radius: 3px 3px 3px 3px; color: #FFFFFF; margin: 0 0 3px; padding: 1px 4px; font-size:12px; display:none;
}

.errorField {
    background: none repeat scroll 0 0 #FF9999; outline: 0 none;
}
div#si_refresh_reg { display:inline; } 

div.ctrlHolder {
	float: left; margin-left:30px; width: 44%;
}

.fullwidth { width:96% !important; } 

div.ctrlHolder span.help { font-style:italic; font-size:12px;} 
div#blog_template-selection { float:left; margin-left:30px; } 
div.blog_template-item { 
    margin-left: 2px !important; padding: 2px 2px 5px 2px !important; width: 80px !important;
 }
	
div.blog_template-item div.blog_template-description { display:none; } 
div.blog_template-selected {
   background: none repeat scroll 0 0 #DDDDDD !important; color: #000000 !important;
}
#ajax_signup_form div.submit { margin-left:30px; }  

div.register-section input#captcha_code { width:160px !important; } 

img#si_image_reg { margin-top:10px;border: 2px solid #222222; } 

div.success_message { background: none repeat scroll 0 0 #555555;
    border: 1px solid #ACACAC;
    border-radius: 8px 8px 8px 8px;
    box-shadow: 0 0px 2px 1px #CCCCCC inset;
    color: #FFFFFF;
    padding: 15px;
	margin-top:60px;
	display:none;
}

#signup_submit {
    background: none repeat scroll 0 0 #0099CC;
    border: medium none;
	border-radius:0 !important;
    color: #FFFFFF;
    cursor: pointer;
    display: inline-block;
    font-family: "Helvetica Neue","Helvetica",Arial,Verdana,sans-serif;
    font-size: 1.3rem;
    line-height: 1;
    margin: 1em 0 0;
    outline: medium none;
    padding: 0.5em 2em;
    position: relative;
    text-align: center;
    text-decoration: none;
}

#signup_submit:hover, #signup_submit:focus  { 
	background-color: #666666 !important; color: #FFFFFF;
}

.captchaSizeDivLarge { float:left; } 

#blog-details-section  { margin-bottom:10px; } 
</style>

<script type="text/javascript">
	jQuery(document).ready( function() {
		
		//jQuery('div#blog-details').show();

		//jQuery( 'input#signup_with_blog' ).removeAttr("checked");
		//if ( jQuery('div#blog-details').length && !jQuery('div#blog-details').hasClass('show') )
			//jQuery('div#blog-details').toggle();

		//jQuery( 'input#signup_with_blog' ).click( function() {
			//jQuery('div#blog-details').fadeOut().toggle();
		//});

		jQuery( '#ajax_signup_form input' ).focus(function(e) {
            var name = jQuery(this).attr('name');
			jQuery(this).removeClass('errorField');
			//jQuery('#'+name+"_error").fadeOut(400);
        });
		
		jQuery('#captcha_code').parent().find('label').append('<div id="captcha_code_error" class="error"></div>');
		jQuery('.captchaSizeDivLarge').parent().css('margin-left','30px');
	});
</script>

<script language="javascript" type="text/javascript">
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?> ' ;
	var myinterval;

	function hideSuccessMessage() { 
		window.clearInterval(myinterval);
		jQuery('.success_message').fadeOut(300,function(){
			jQuery('#ajax_signup_form').fadeIn(400,function(){});
		});
	}
	
	function bp_ajax_submit_register_form()
	{
		jQuery('.error').hide();
		var serialized = jQuery('#ajax_signup_form').serialize();
		var data = serialized;
		jQuery.post(ajaxurl, data, function(response) {
			response = eval('('+response+')');
			if(response['status'] == 'error') {
				var errors = response['errors'];
				for(i in errors) {
					jQuery('#'+i+"_error").html(errors[i]);
					jQuery('#'+i+"_error").fadeIn(300);
				}
			} else {
				if(response['status'] == 'user-error') {
					alert(response['error-msg']);
				} else if (response['status'] == 'success') {
					document.getElementById('ajax_signup_form').reset();
					
					jQuery('#ajax_signup_form').fadeOut(300,function(){
						jQuery('.success_message').fadeIn(400,function(){
							myinterval = window.setInterval(hideSuccessMessage, 4000);
						});
					});
				} else {
					alert('Unknown Error Occured.');
				}
			}
			jQuery('#si_refresh_reg a').trigger('click');
		});
	}
</script>
<div class="success_message">
<?php if ( bp_registration_needs_activation() ) : ?>
					<p><?php _e( 'You have successfully created your account! To begin using this site you will need to activate your account via the email we have just sent to your address.', 'buddypress' ) ?></p>
				<?php else : ?>
					<p><?php _e( 'You have successfully created your account! Please log in using the username and password you have just created.', 'buddypress' ) ?></p>
				<?php endif; ?>
</div>
<form action="" onSubmit="bp_ajax_submit_register_form(); return false;" name="signup_form" id="ajax_signup_form" class="standard-form" method="post" enctype="multipart/form-data">
			
				<h2><?php _e( '&nbsp;', 'buddypress' ) ?></h2>

				<?php do_action( 'template_notices' ) ?>

				<?php do_action( 'bp_before_account_details_fields' ) ?>

				<div class="register-section ajax-register-section" id="basic-details-section">

					<?php /***** Basic Account Details ******/ ?>

					<div class="ctrlHolder">
					<label for="signup_username"><?php _e( 'Username', 'buddypress' ) ?> <?php _e( '', 'buddypress' ) ?></label>
					<div id="signup_username_error" class="error"></div>
					<input type="text" name="signup_username" id="signup_username" value="<?php bp_signup_username_value() ?>" />
                    </div>

					<div class="ctrlHolder">
					<label for="signup_email"><?php _e( 'Email Address', 'buddypress' ) ?> <?php _e( '', 'buddypress' ) ?></label>
					<div id="signup_email_error" class="error"></div>
					<input type="text" name="signup_email" id="signup_email" value="<?php bp_signup_email_value() ?>" />
					</div>
                    
                    <div class="ctrlHolder">
					<label for="signup_password"><?php _e( 'Choose a Password', 'buddypress' ) ?> <?php _e( '', 'buddypress' ) ?></label>
					<div id="signup_password_error" class="error"></div>
					<input type="password" name="signup_password" id="signup_password" value="" />
					</div>
                    
                    <div class="ctrlHolder">
					<label for="signup_password_confirm"><?php _e( 'Confirm Password', 'buddypress' ) ?> <?php _e( '', 'buddypress' ) ?></label>
					<div id="signup_password_confirm_error" class="error"></div>
					<input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" />
					</div>
				</div><!-- #basic-details-section -->

				<?php do_action( 'bp_after_account_details_fields' ) ?>

				<?php /***** Extra Profile Details ******/ ?>

				<?php if ( bp_is_active( 'xprofile' ) ) : ?>

					<?php do_action( 'bp_before_signup_profile_fields' ) ?>

					<div class="register-section ajax-register-section" id="profile-details-section">

						<?php /* Use the profile field loop to render input fields for the 'base' profile field group */ ?>
						<?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( 'profile_group_id=1&hide_empty_fields=0' ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

						<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

							<div class="ctrlHolder fullwidth">

								<?php if ( 'textbox' == bp_get_the_profile_field_type() ) : ?>

									<label for="<?php bp_the_profile_field_input_name() ?>"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '', 'buddypress' ) ?><?php endif; ?></label>
									<div id="<?php bp_the_profile_field_input_name() ?>_error" class="error"></div>
									<input type="text" name="<?php bp_the_profile_field_input_name() ?>" id="<?php bp_the_profile_field_input_name() ?>" value="<?php bp_the_profile_field_edit_value() ?>" />

								<?php endif; ?>

								<?php if ( 'textarea' == bp_get_the_profile_field_type() ) : ?>

									<label for="<?php bp_the_profile_field_input_name() ?>"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '', 'buddypress' ) ?><?php endif; ?></label>
									<div id="<?php bp_the_profile_field_input_name() ?>_error" class="error"></div>
									<textarea rows="5" cols="40" name="<?php bp_the_profile_field_input_name() ?>" id="<?php bp_the_profile_field_input_name() ?>"><?php bp_the_profile_field_edit_value() ?></textarea>

								<?php endif; ?>

								<?php if ( 'selectbox' == bp_get_the_profile_field_type() ) : ?>

									<label for="<?php bp_the_profile_field_input_name() ?>"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '', 'buddypress' ) ?><?php endif; ?></label>
									<div id="<?php bp_the_profile_field_input_name() ?>_error" class="error"></div>
									<select name="<?php bp_the_profile_field_input_name() ?>" id="<?php bp_the_profile_field_input_name() ?>">
										<?php bp_the_profile_field_options() ?>
									</select>

								<?php endif; ?>

								<?php if ( 'multiselectbox' == bp_get_the_profile_field_type() ) : ?>

									<label for="<?php bp_the_profile_field_input_name() ?>"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '', 'buddypress' ) ?><?php endif; ?></label>
									<div id="<?php bp_the_profile_field_input_name() ?>_error" class="error"></div>
									<select name="<?php bp_the_profile_field_input_name() ?>" id="<?php bp_the_profile_field_input_name() ?>" multiple="multiple">
										<?php bp_the_profile_field_options() ?>
									</select>

								<?php endif; ?>

								<?php if ( 'radio' == bp_get_the_profile_field_type() ) : ?>

									<div class="radio">
										<span class="label"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '', 'buddypress' ) ?><?php endif; ?></span>

										<div id="<?php bp_the_profile_field_input_name() ?>_error" class="error"></div>
										<?php bp_the_profile_field_options() ?>

										<?php if ( !bp_get_the_profile_field_is_required() ) : ?>
											<a class="clear-value" href="javascript:clear( '<?php bp_the_profile_field_input_name() ?>' );"><?php _e( 'Clear', 'buddypress' ) ?></a>
										<?php endif; ?>
									</div>

								<?php endif; ?>

								<?php if ( 'checkbox' == bp_get_the_profile_field_type() ) : ?>

									<div class="checkbox">
										<span class="label"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '', 'buddypress' ) ?><?php endif; ?></span>

										<div id="<?php bp_the_profile_field_input_name() ?>_error" class="error"></div>
										<?php bp_the_profile_field_options() ?>
									</div>

								<?php endif; ?>

								<?php if ( 'datebox' == bp_get_the_profile_field_type() ) : ?>

									<div class="datebox">
										<label for="<?php bp_the_profile_field_input_name() ?>_day"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '', 'buddypress' ) ?><?php endif; ?></label>
										<div id="<?php bp_the_profile_field_input_name() ?>_error" class="error"></div>

										<select name="<?php bp_the_profile_field_input_name() ?>_day" id="<?php bp_the_profile_field_input_name() ?>_day">
											<?php bp_the_profile_field_options( 'type=day' ) ?>
										</select>

										<select name="<?php bp_the_profile_field_input_name() ?>_month" id="<?php bp_the_profile_field_input_name() ?>_month">
											<?php bp_the_profile_field_options( 'type=month' ) ?>
										</select>

										<select name="<?php bp_the_profile_field_input_name() ?>_year" id="<?php bp_the_profile_field_input_name() ?>_year">
											<?php bp_the_profile_field_options( 'type=year' ) ?>
										</select>
									</div>

								<?php endif; ?>

								<?php do_action( 'bp_custom_profile_edit_fields' ) ?>

								<p class="description"><?php bp_the_profile_field_description() ?></p>

							</div>

						<?php endwhile; ?>

						<input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php bp_the_profile_group_field_ids() ?>" />

						<?php endwhile; endif; endif; ?>

					</div><!-- #profile-details-section -->

					<?php do_action( 'bp_after_signup_profile_fields' ) ?>


				<?php if ( bp_get_blog_signup_allowed() ) : ?>

					<?php do_action( 'bp_before_blog_details_fields' ) ?>

					<?php /***** Blog Creation Details ******/ ?>

					<div class="register-section clearfix" id="blog-details-section ">

						<div style="display:none" class="ctrlHolder">
						<p><input type="checkbox" name="signup_with_blog" id="signup_with_blog" value="1" checked="checked" /> <?php _e( 'Yes, I\'d like to create a new site', 'buddypress' ) ?></p>
						</div>
                        <div style="clear:both"></div>
						<div id="blog-details">
							<div class="ctrlHolder fullwidth">
							<label for="signup_blog_url"><?php _e( 'Choose Site Name', 'buddypress' ) ?> <?php _e( '', 'buddypress' ) ?></label>
							<div id="signup_blog_url_error" class="error"></div>

							<?php if ( is_subdomain_install() ) : ?>
								<input  type="text" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value() ?>" /> .<?php bp_blogs_subdomain_base() ?>
							<?php else : ?>
								<?php echo site_url() ?>/ <input type="text" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value() ?>" />
							<?php endif; ?>
                            <br/><span class="help">Example: NewBusiness.webento.com</span>
							</div>
                            
                            <div style="clear:both"></div>
                            
                            <div class="ctrlHolder fullwidth">
							<label for="signup_blog_title"><?php _e( 'Site Title', 'buddypress' ) ?> <?php _e( '', 'buddypress' ) ?></label>
							<div id="signup_blog_title_error" class="error"></div>
							<input type="text" name="signup_blog_title" id="signup_blog_title" value="<?php bp_signup_blog_title_value() ?>" />
                            <br/><span class="help">Example: New Business</span>
							</div>
                            
							<span style=" display: block;float: left;margin-left: 30px; color:#fff; background:none !important;" class="label"><?php _e( 'I would like my site to appear in search engines, and in public listings around this network.', 'buddypress' ) ?>:</span>
                            
							<div class="ctrlHolder">
							<div id="signup_blog_privacy_error" class="error"></div>
							<label style="display:inline-block; "><input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_public" value="public"<?php if ( 'public' == bp_get_signup_blog_privacy_value() || !bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Yes', 'buddypress' ) ?></label>
							<label style="display:inline-block; margin-left:10px;"><input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_private" value="private"<?php if ( 'private' == bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'No', 'buddypress' ) ?></label>
                            </div>
							
                            <?php do_action( 'bp_after_blog_details_fields' ) ?>
						</div>
						
					</div><!-- #blog-details-section -->
					
					

				<?php endif; ?>

				<?php do_action( 'bp_before_registration_submit_buttons' ) ?>

				<div class="submit">
					<input type="submit"  name="signup_submit" id="signup_submit" value="<?php _e( 'Sign Up', 'buddypress' ) ?>" />
                    <input type="hidden" value="bp_ajax_submit_register_form" name="action" />
				</div>

				<?php do_action( 'bp_after_registration_submit_buttons' ) ?>

				<?php wp_nonce_field( 'bp_new_signup' ) ?>

			<?php endif; // request-details signup step ?>

			<?php do_action( 'bp_custom_signup_steps' ) ?>

			</form>
<?php
$output = ob_get_clean();
return $output;
} // bp_ajax_register_form endss......

add_shortcode( 'bp_ajax_register_form', 'bp_ajax_register_form_function' );


/* ajax functions to be called.*/
add_action('wp_ajax_bp_ajax_submit_register_form', 'bp_ajax_submit_register_form');
add_action('wp_ajax_nopriv_bp_ajax_submit_register_form', 'bp_ajax_submit_register_form');
function bp_ajax_submit_register_form()
{
		global $bp;
		// Check the nonce
		
		check_admin_referer( 'bp_new_signup' );

		// Check the base account details for problems
		$account_details = bp_core_validate_user_signup( $_POST['signup_username'], $_POST['signup_email'] );

		// If there are errors with account details, set them for display
		if ( !empty( $account_details['errors']->errors['user_name'] ) )
			$bp->signup->errors['signup_username'] = $account_details['errors']->errors['user_name'][0];

		if ( !empty( $account_details['errors']->errors['user_email'] ) )
			$bp->signup->errors['signup_email'] = $account_details['errors']->errors['user_email'][0];

		// Check that both password fields are filled in
		if ( empty( $_POST['signup_password'] ) || empty( $_POST['signup_password_confirm'] ) )
			$bp->signup->errors['signup_password'] = __( 'Please make sure you enter your password twice', 'buddypress' );

		// Check that the passwords match
		if ( ( !empty( $_POST['signup_password'] ) && !empty( $_POST['signup_password_confirm'] ) ) && $_POST['signup_password'] != $_POST['signup_password_confirm'] )
			$bp->signup->errors['signup_password'] = __( 'The passwords you entered do not match.', 'buddypress' );

		$bp->signup->username = $_POST['signup_username'];
		$bp->signup->email = $_POST['signup_email'];

		// Now we've checked account details, we can check profile information
		if ( bp_is_active( 'xprofile' ) ) {

			// Make sure hidden field is passed and populated
			if ( isset( $_POST['signup_profile_field_ids'] ) && !empty( $_POST['signup_profile_field_ids'] ) ) {

				// Let's compact any profile field info into an array
				$profile_field_ids = explode( ',', $_POST['signup_profile_field_ids'] );

				// Loop through the posted fields formatting any datebox values then validate the field
				foreach ( (array) $profile_field_ids as $field_id ) {
					if ( !isset( $_POST['field_' . $field_id] ) ) {
						if ( !empty( $_POST['field_' . $field_id . '_day'] ) && !empty( $_POST['field_' . $field_id . '_month'] ) && !empty( $_POST['field_' . $field_id . '_year'] ) )
							$_POST['field_' . $field_id] = date( 'Y-m-d H:i:s', strtotime( $_POST['field_' . $field_id . '_day'] . $_POST['field_' . $field_id . '_month'] . $_POST['field_' . $field_id . '_year'] ) );
					}

					// Create errors for required fields without values
					if ( xprofile_check_is_required_field( $field_id ) && empty( $_POST['field_' . $field_id] ) )
						$bp->signup->errors['field_' . $field_id] = __( 'This is a required field', 'buddypress' );
				}

			// This situation doesn't naturally occur so bounce to website root
			} else {
				bp_core_redirect( bp_get_root_domain() );
			}
		}

		// Finally, let's check the blog details, if the user wants a blog and blog creation is enabled
		if ( isset( $_POST['signup_with_blog'] ) ) {
			$active_signup = $bp->site_options['registration'];

			if ( 'blog' == $active_signup || 'all' == $active_signup ) {
				$blog_details = bp_core_validate_blog_signup( $_POST['signup_blog_url'], $_POST['signup_blog_title'] );

				// If there are errors with blog details, set them for display
				if ( !empty( $blog_details['errors']->errors['blogname'] ) )
					$bp->signup->errors['signup_blog_url'] = $blog_details['errors']->errors['blogname'][0];

				if ( !empty( $blog_details['errors']->errors['blog_title'] ) )
					$bp->signup->errors['signup_blog_title'] = $blog_details['errors']->errors['blog_title'][0];
			}
		}

		do_action( 'bp_signup_validate' );

		// Add any errors to the action for the field in the template for display.
		if ( !empty( $bp->signup->errors ) ) {
			
			$response['status'] = 'error';
			$response['errors'] = $bp->signup->errors;
			echo json_encode($response); exit;
			
			/*foreach ( (array) $bp->signup->errors as $fieldname => $error_message ) {
				// addslashes() and stripslashes() to avoid create_function()
				// syntax errors when the $error_message contains quotes
				add_action( 'bp_' . $fieldname . '_errors', create_function( '', 'echo apply_filters(\'bp_members_signup_error_message\', "<div class=\"error\">" . stripslashes( \'' . addslashes( $error_message ) . '\' ) . "</div>" );' ) );
			}*/
		} else {
			$bp->signup->step = 'save-details';

			// No errors! Let's register those deets.
			$active_signup = !empty( $bp->site_options['registration'] ) ? $bp->site_options['registration'] : '';

			if ( 'none' != $active_signup ) {

				// Let's compact any profile field info into usermeta
				$profile_field_ids = explode( ',', $_POST['signup_profile_field_ids'] );

				// Loop through the posted fields formatting any datebox values then add to usermeta
				foreach ( (array) $profile_field_ids as $field_id ) {
					if ( !isset( $_POST['field_' . $field_id] ) ) {
						if ( isset( $_POST['field_' . $field_id . '_day'] ) )
							$_POST['field_' . $field_id] = date( 'Y-m-d H:i:s', strtotime( $_POST['field_' . $field_id . '_day'] . $_POST['field_' . $field_id . '_month'] . $_POST['field_' . $field_id . '_year'] ) );
					}

					if ( !empty( $_POST['field_' . $field_id] ) )
						$usermeta['field_' . $field_id] = $_POST['field_' . $field_id];
				}

				// Store the profile field ID's in usermeta
				$usermeta['profile_field_ids'] = $_POST['signup_profile_field_ids'];

				// Hash and store the password
				$usermeta['password'] = wp_hash_password( $_POST['signup_password'] );

				// If the user decided to create a blog, save those details to usermeta
				if ( 'blog' == $active_signup || 'all' == $active_signup )
					$usermeta['public'] = ( isset( $_POST['signup_blog_privacy'] ) && 'public' == $_POST['signup_blog_privacy'] ) ? true : false;

				$usermeta = apply_filters( 'bp_signup_usermeta', $usermeta );

				// Finally, sign up the user and/or blog
				if ( isset( $_POST['signup_with_blog'] ) && is_multisite() )
					$wp_user_id = bp_core_signup_blog( $blog_details['domain'], $blog_details['path'], $blog_details['blog_title'], $_POST['signup_username'], $_POST['signup_email'], $usermeta );
				else
					$wp_user_id = bp_core_signup_user( $_POST['signup_username'], $_POST['signup_password'], $_POST['signup_email'], $usermeta );

				if ( is_wp_error( $wp_user_id ) ) {                                   
					$bp->signup->step = 'request-details';
					bp_core_add_message( strip_tags( $wp_user_id->get_error_message() ), 'error' );
					$response['status'] = 'user-error';
					$response['error-msg'] = strip_tags( $wp_user_id->get_error_message() );
					echo json_encode($response); exit;
					 
				} else {
					$bp->signup->step = 'completed-confirmation';
					$response['status'] = 'success';
					echo json_encode($response); exit;
				} 
			}

			do_action( 'bp_complete_signup' );
		}

	
	echo 'form is submitted successfully.';
	exit;
}


?>

