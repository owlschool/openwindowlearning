<?php
/*
* Dynamo Support Auto Response/Canned Responses
*/
function dynamo_support_auto_response_dropdown() {
	global $support_options, $post;
	if ($support_options['plugin_version'] == '==AUWVUeZhFZzFWMaNVTWJVU') {
		if(is_single() && $post->post_type == 'ticket') {
			if(current_user_can('manage_options') || current_user_can('access_tickets')) {
				$responses = $support_options['response'];
				if(is_array($responses) && !empty($responses)){
					echo '<label for="response">Select Auto Response:</label><select name="response" id="auto-response"><option value="">-- Select Response --</option>';
						foreach($responses as $k => $array) {
							echo'<option value="'.$k.'">'.$array['title'].'</option>';
						}
					echo '</select>';
				}
			}
		}
	}
}
add_action('comment_form_top','dynamo_support_auto_response_dropdown');

function dynamo_support_insert_auto_response() {
	global $support_options;
	$k = trim($_POST['val']);
	echo stripslashes($support_options['response'][$k]['content']);
	die();	
}
add_action('wp_ajax_dynamo_support_insert_auto_response', 'dynamo_support_insert_auto_response');
add_action('wp_ajax_nopriv_dynamo_support_insert_auto_response', 'dynamo_support_insert_auto_response');
?>