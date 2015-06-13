<?php
global $plugin_folder;
//First Ever Activation Comes Straight To This Page, After That They Never Do...
if(!get_option('support_dynamo_first_activation')) {
	update_option('support_dynamo_first_activation','1');
}
?>
<script type="text/javascript">
jQuery(document).ready(function() {
//Auto Awebber
jQuery('#dyno_activate').click(function(e) {
	e.preventDefault();
	var checkemail = jQuery('input[name="dyno_email"]').val();
	
	if(checkemail.match(/activation_[^@]+@plugindynamo\.com/)) {
		var data = {
				action: 'support_dynamo_communicate',
				email: jQuery('input[name="dyno_email"]').val()
			}
		jQuery.post(ajaxurl, data, function(response) {
			window.location = window.location;
		});
		return false
	} else {
	
	var aw_form = jQuery('#aweber_form');
	aw_form.attr('target','frame');
	
	jQuery('input[name="email"]').val(jQuery('input[name="dyno_email"]').val());
	
	aw_form.get(0).submit();
	
	var data = {
			action: 'support_dynamo_communicate',
			email: jQuery('input[name="dyno_email"]').val()
		}
	jQuery.post(ajaxurl, data, function(response) {
		window.location = window.location;
	});

	return false
	}
});
});
</script>	
	<div class="postbox">
		<h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Support Dynamo - Account</span> - Current Plugin Status: Active</h3>
		<div class="inside" style="padding:0 5px 5px 5px;">
			<div id="dyno-step-header">
				<br/>
				<img src="<?php echo $plugin_folder; ?>img/support-dynamo.png" height="261" width="300" alt="Support Dynamo"/>
			</div>
			<div id="dyno-content-holder">
				<p><b>Thank you for purchasing Support Dynamo</b>, to receive your Support &amp; Update package please enter your PluginDynamo.com account e-mail address below.<br/><br/><b>* NOTE *</b> - This must be the same e-mail address you used when creating your account on PluginDynamo.com</p>
				<?php
					$error_check = get_option('support_dynamo_act_errors');
					if($error_check && $error_check != '') {
						foreach($error_check as $e) {?>
							<div class="updated settings-error" id="setting-error-settings_updated"> 
								<p><strong><?php echo $e; ?></strong></p>
							</div>
						<?php
						}
					}
				?>
				<form action="" method="POST" id="activate-form">
					<br/>
					<label style="font-size:18px;"><b>Enter Your E-Mail Address:</b></label>
					<br/><br/>
					<input type="text" name="dyno_email" value="<?php echo dyno_decode5t(get_option('support_dynamo_user_email'));?>" style="height:50px; width:500px; font-size:25px;"/>
					<br/><br/>
					<?php
						if(get_option('support_dynamo_plugin_activated') == 'activated') { 
					?>
					<input type="submit" name="dyno_activate" value="Update E-Mail &#187;" id="dyno_activate" class="button-primary"/>
					<?php
						} else {
					?>
					<input type="submit" name="dyno_activate" value="Activate Support Dynamo &#187;" id="dyno_activate" class="orange-button"/>
					<?php
						}
					?>
					<br/><br/>
				</form>
				
				<div id="aweber" style="display:none;">
					<iframe name="frame" id="frame" style="width:400px; height:200px; border:1px;"></iframe>

<form method="post" class="af-form-wrapper" id="aweber_form" action="http://www.aweber.com/scripts/addlead.pl"  >
<div style="display: none;">
<input type="hidden" name="meta_web_form_id" value="1507697387" />
<input type="hidden" name="meta_split_id" value="" />
<input type="hidden" name="listname" value="dynamoone" />
<input type="hidden" name="redirect" value="http://www.aweber.com/thankyou.htm?m=default" id="redirect_080ce8d8209a1026dd3ce8cdda0b1da3" />

<input type="hidden" name="meta_adtracking" value="My_Web_Form" />
<input type="hidden" name="meta_message" value="1" />
<input type="hidden" name="meta_required" value="email" />

<input type="hidden" name="meta_tooltip" value="" />
</div>
<div id="af-form-1507697387" class="af-form"><div id="af-body-1507697387" class="af-body af-standards">
<div class="af-element">
<label class="previewLabel" for="awf_field-19419222">Email: </label>
<div class="af-textWrap"><input class="text" id="awf_field-19419222" type="text" name="email" value="" tabindex="500"  />
</div><div class="af-clear"></div>
</div>
<div class="af-element buttonContainer">
<input name="aweb_submit" class="submit" type="submit" value="Submit" tabindex="501" />
<div class="af-clear"></div>
</div>
</div>
</div>
<div style="display: none;"><img src="http://forms.aweber.com/form/displays.htm?id=jKwM7Gyc7Mwc7A==" alt="" /></div>
</form>

				</div>
				
				<?php 
					if(get_option('support_dynamo_act_errors') == '' && get_option('support_dynamo_plugin_activated') == 'activated') {
					$membership = dyno_decode5t(get_option('support_dynamo_user_membership'));
						if($membership =='Success') {
							echo'<label style="font-size:18px;"><b>Thank You! Notifications Will Be Sent To This E-Mail Address</b></label><br/><br/>';
						}
						?>
						<br/><br/>
					<a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_" title="Continue To Support Dynamo" class="orange-button">Continue To Support Dynamo &#187;</a>
						<?php
					}
				?>
			</div>
		
		</div>
	</div>
</div>
<?php
?>