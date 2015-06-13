<?php
session_start();
/**
 * Template Name: Contact Page 
 * Description: A Page Template to display contact form with captcha and jQuery validation.
 *
 * @package  WellThemes
 * @file     page-contact.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
 

	$name_error = '';
	$email_error = '';
	$message_error = '';
	$captcha_error = '';
	
	$wt_recaptcha_public_key = wt_get_option('wt_recaptcha_public_key');
	$wt_recaptcha_private_key = wt_get_option('wt_recaptcha_private_key');
	
	//include_once( get_stylesheet_directory() . '/framework/lib/recaptcha/recaptchalib.php' );							
	
	if(isset($_POST['wt_submit'])) {
		//validate sender name
		if(trim($_POST['sender_name']) === '') {
			$name_error = 'Please enter your name.';
			$has_error = true;
		} else {
			$sender_name = trim($_POST['sender_name']);
		}
		
		//validate sender email
		if(trim($_POST['sender_email']) === '')  {
			$email_error = 'Please enter your email address.';
			$has_error = true;
		} else if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", trim($_POST['sender_email']))){
			$email_error = 'Please enter a valid email address.';
			$has_error = true;
		} else {
			$sender_email = trim($_POST['sender_email']);
		}
			
		
		//validate message
		if(trim($_POST['message_text']) === '') {
			$message_error = 'Please enter a message.';
			$has_error = true;
		} else {
			if(function_exists('stripslashes')) {
				$message = stripslashes(trim($_POST['message_text']));
			} else {
				$message = trim($_POST['message_text']);
			}
		}
		
				
		# the response from reCAPTCHA
		$resp = null;
		# the error code from reCAPTCHA, if any
		$error = null;
		
		$resp = recaptcha_check_answer ($wt_recaptcha_private_key,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

		if (!$resp->is_valid) {                
			# set the error code so that we can display it				
			$captcha_error = __('Please enter code correctly.', 'wellthemes');
			$has_error = true;	
		}	
		
			
		
		//if no error, send email.
		if(!isset($has_error)) {
			$email_to = wt_get_option('wt_contact_email');		
			$subject = wt_get_option('wt_contact_subject');	
			
			if (!isset($email_to) || ($email_to == '') ){
				$email_to = get_option('admin_email');				
			}
			
			if (!isset($subject) || ($subject == '') ){
				$subject = 'Contact Message From '.$sender_name;			
			}
			
			$from_user = "=?UTF-8?B?".base64_encode($sender_name)."?=";
			$subject = "=?UTF-8?B?".base64_encode($subject)."?=";

			$headers = "From: $from_user <$sender_email>\r\n".
						"Reply-To: $sender_email" . "\r\n" .
               "MIME-Version: 1.0" . "\r\n" .
               "Content-type: text/html; charset=UTF-8" . "\r\n"; 
			
			$body = "Name: $sender_name <br />Email: $sender_email <br />Comments: $message";	
			
			mail($email_to, $subject, $body, $headers);
			$email_sent = true;
		}
	
	} 

?>
<?php get_header('verticalmenu'); ?>
	<script type="text/javascript">
	<!--//--><![CDATA[//><!--
		jQuery(document).ready(function() {
			jQuery('form#wt_contact_form').submit(function() {
			jQuery('form#wt_contact_form .error').remove();
			var hasError = false;
			jQuery('.requiredField').each(function() {
			if(jQuery.trim(jQuery(this).val()) == '') {
									
					if(jQuery(this).hasClass('name_field')) {
						jQuery(this).parent().append('<span class="error"><?php _e('Please enter your name.', 'wellthemes'); ?></span>');
					}
					
					if(jQuery(this).hasClass('title_field')) {
						jQuery(this).parent().append('<span class="error"><?php _e('Please enter message title.', 'wellthemes'); ?></span>');
					}
					
					if(jQuery(this).hasClass('email')) {
						jQuery(this).parent().append('<span class="error"><?php _e('Please enter your email.', 'wellthemes'); ?></span>');
					}
					
					if(jQuery(this).hasClass('message_field')) {
						jQuery(this).parent().append('<span class="error"><?php _e('Please enter your message.', 'wellthemes'); ?></span>');
					}
					
					if(jQuery(this).hasClass("captcha_field")) {
						jQuery(this).parent().append('<span class="error"><?php _e('Please enter the security code.', 'wellthemes'); ?></span>');
					}
				
					jQuery(this).addClass('inputError');
					hasError = true;
				} else if(jQuery(this).hasClass('email')) {
					var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
					if(!emailReg.test(jQuery.trim(jQuery(this).val()))) {
						jQuery(this).parent().append('<span class="error"><?php _e('Please enter valid email', 'wellthemes'); ?> </span>');
						jQuery(this).addClass('inputError');
						hasError = true;
					}
				}
			});
						
			if(hasError) {
				return false;
			} else{
				return true;
			}						
			});
		});
	//-->!]]>
	</script>	
	<?php $wt_contact_address = wt_get_option( 'wt_contact_address' );	?>			
	<?php if ( $wt_contact_address ) { ?>
	
		<div class="contact-map section">
			<iframe width="100%" scrolling="no" height="320" frameborder="0" src="		https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo urlencode($wt_contact_address); ?>&amp;z=14&amp;iwloc=near&amp;output=embed" marginwidth="0" marginheight="0"></iframe>
		</div><!--/map -->
	<?php } ?>
			
	<div id="content" class="contact-page">
			<header class="entry-header">
				<h1><?php the_title(); ?></h1>
			</header><!-- /entry-header -->
		
			<div class="contact-text">		
				<?php while ( have_posts() ) : the_post(); ?>			
					<?php the_content(); ?>			
				<?php endwhile; // end of the loop. ?>					
			</div><!-- /contact-text -->
		
			<div class="contact-wrap">
			<script type="text/javascript">
				 var RecaptchaOptions = {
					theme : 'custom',
					custom_theme_widget: 'recaptcha_widget'
				 };
			</script>
				
			<div class="contact-form">		
					
					<div class="col-header">
						<h4><?php _e('Send us a message!', 'wellthemes')?></h4>
					</div>
					
					<?php if(empty($wt_recaptcha_public_key) or (empty($wt_recaptcha_private_key))) { ?>				
						<div class="msgbox msgbox-warning"><?php _e('<strong>Important.</strong> You need to add reCAPTCHA keys in the theme options for contact form to work.', 'wellthemes') ?></div>	
					<?php } ?>
					
					<?php if(isset($email_sent) && $email_sent == true) { ?>				
						<div class="msgbox msgbox-success"><?php _e('<strong>Thank you.</strong> Your email was sent successfully.', 'wellthemes') ?></div>	
					<?php } else { ?>
	
					<?php if(isset($has_error)) { ?>
						<div class="msgbox msgbox-error"><?php _e('Please correct the following errors and try again.', 'wellthemes') ?></div>
						<?php } ?>
	
						<form action="<?php $_SERVER['PHP_SELF']; ?>" id="wt_contact_form" method="post">
						
							<div class="row-full">
								<div class="field">
									<input type="text" class="text name_field requiredField" name="sender_name" id="sender_name" placeholder="Your name &#42;" value="<?php if(isset($_POST['sender_name'])) echo $_POST['sender_name'];?>" />
									<?php if($name_error != '') { ?>
										<span class="error"><?php echo $name_error; ?></span>  
									<?php } ?>
								</div>
								
								<div class="field field-last">
									<input type="text" class="text requiredField email" name="sender_email" id="sender_email" placeholder="Email &#42;" value="<?php if(isset($_POST['sender_email']))  echo $_POST['sender_email'];?>" />
									<?php if($email_error != '') { ?>
										<span class="error"><?php echo $email_error; ?></span> 
									<?php } ?>	
								</div>
							</div>
							
							<div class="field message-field">
								<input type="text" class="text title_field requiredField" name="message_title" id="message_title" placeholder="Title &#42;" value="<?php if(isset($_POST['message_title'])) echo $_POST['message_title'];?>" />
								<?php if($name_error != '') { ?>
									<span class="error"><?php echo $message_error; ?></span>  
								<?php } ?>
							</div>
								
							<div class="field textarea-field">									
								<textarea class="textarea message_field requiredField" name="message_text" id="message_text" placeholder="Message &#42;"><?php if(isset($_POST['message_text'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['message_text']); } else { echo $_POST['message_text']; } } ?></textarea>
																
								<?php if($message_error != '') { ?>
									<span class="error"><?php echo $message_error; ?></span> 
								<?php } ?>				
							</div>
							
							<div id="recaptcha_widget" style="display:none">
							
								<div class="field">
									<div class="recaptcha_only_if_incorrect_sol" style="color:red"><?php _e('Incorrect please try again', 'wellthemes'); ?></div>
									<span class="recaptcha_only_if_image"><span class="enter-words"><?php _e('Enter the words:', 'wellthemes'); ?></span><span class="required">&#42;</span></span>
								    <span class="recaptcha_only_if_audio"><span class="enter-words"><?php _e('Enter the numbers you hear:', 'wellthemes'); ?></span><span class="required">&#42;</span></span>
								    <input type="text" id="recaptcha_response_field" class="text requiredField captcha_field" name="recaptcha_response_field" />
									<?php if($captcha_error != '') { ?>
										<span class="error"><?php echo $captcha_error; ?></span> 
									<?php } ?>										
								</div>
								 
								<div class="field recaptcha-image">
									<div id="recaptcha_image"></div>
									<div class="recaptcha_refresh"><i class="fa fa-refresh"></i><a href="javascript:Recaptcha.reload()"><?php _e('Refresh', 'wellthemes'); ?></a></div>
									<div class="recaptcha_only_if_image"><i class="fa fa-volume-up"></i><a href="javascript:Recaptcha.switch_type('audio')"><?php _e('Audio ', 'wellthemes'); ?></a></div>
									<div class="recaptcha_only_if_audio"><i class="fa fa-picture-o"></i><a href="javascript:Recaptcha.switch_type('image')"><?php _e('Image', 'wellthemes'); ?></a></div>
									<div class="recaptcha_help"><i class="fa fa-info-circle"></i><a href="javascript:Recaptcha.showhelp()"><?php _e('Help', 'wellthemes'); ?></a></div>
								</div>

								<script type="text/javascript"
									src="http://www.google.com/recaptcha/api/challenge?k=<?php echo $wt_recaptcha_public_key; ?>">
								</script>
								<noscript>
								   <iframe src="http://www.google.com/recaptcha/api/noscript?k=<?php echo $wt_recaptcha_public_key; ?>"
										height="300" width="500" frameborder="0"></iframe><br>
									<textarea name="recaptcha_challenge_field" rows="3" cols="40">
								   </textarea>
								   <input type="hidden" name="recaptcha_response_field"
										value="manual_challenge">
								</noscript>
							</div>
						
							<div class="field field-submit">
								<div class="submit-icon main-color-bg"><i class="fa fa-location-arrow"></i></div>
								<input type="submit" name="wt_submit" value="<?php _e('Send', 'wellthemes'); ?>" class="button main-color-bg" />
							</div>				
																
						</form>
	
				<?php } ?>
	
			</div><!-- /contact-form -->
			
	</div><!-- /contact-form-wrap -->
</div><!-- /content -->
<?php get_sidebar(); ?>
<?php get_footer('verticalmenu'); ?>