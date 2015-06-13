<?php
/*
* Dynamo Support Cron Jobs
*/

/*
* Schedule Cron Job For Twice Daily
*/	

//Register Cron Time
function dynamo_support_reccurences($schedules) {
	$schedules['sd_email'] = array('interval' => 604800, 'display' => __('Support Dynamo Email'));
	$schedules['four_hours'] = array('interval' => 14400, 'display' => __('Every 4 Hours'));
   return $schedules;
}
add_filter('cron_schedules', 'dynamo_support_reccurences');	

if ($support_options['plugin_version'] == '==AUWVUeZhFZzFWMaNVTWJVU') {
	$sd_mail_template = 'mail-template.php';
	if(!wp_next_scheduled('dynamo_support_twicedaily_check')) {
		wp_schedule_event(current_time('timestamp',1), 'twicedaily', 'dynamo_support_twicedaily_check');
	}
	add_action('dynamo_support_twicedaily_check', 'dynamo_support_check_tickets');
	

	if(!wp_next_scheduled('dynamo_support_four_hours_check')) {
		wp_schedule_event(current_time('timestamp',1), 'four_hours', 'dynamo_support_four_hours_check');
	}
	add_action('dynamo_support_four_hours_check', 'dynamo_support_4_check');
	
} else if($support_options['plugin_version'] == 'VZlWXRlVsNnUsR2MadEdaZVMaZVVB1TP') { 
	$sd_mail_template = 'mail-template2.php';
	dynamo_support_deactivate();
	
}
//Email Cron
if(!wp_next_scheduled('sd_email_cron')) {
	wp_schedule_event(time(), 'sd_email', 'sd_email_cron');
}
add_action('sd_email_cron', 'sd_mail_report');

/*
* Cron Job Function
*/
//20 Hour Check
function dynamo_support_4_check() {
	global $wpdb, $support_options, $dslog, $plugin_folder;
	$dslog->lwrite('---- START FEEDBACK EMAIL CHECK ----');
	$now = date('Y-m-d H:i:s',current_time('timestamp', 1));
	$t = strtotime($now);
	$twenty = date( 'Y-m-d H:i:s', strtotime( '-20 hours',$t ) );
	$twentyfour = date( 'Y-m-d H:i:s', strtotime( '-1 day',$t ) );
	
	$dslog->lwrite('Searching for replys between '.$twenty.' and '.$twentyfour.'');
	
	$post_table = $wpdb->prefix.'posts';
	$meta_table = $wpdb->prefix.'postmeta';
	$sql = "SELECT DISTINCT meta1.post_id as id FROM $meta_table meta1 INNER JOIN $meta_table meta2 WHERE meta1.post_id = meta2.post_id AND meta1.meta_key = 'ticket_status' AND meta1.meta_value = '2' AND meta2.meta_key = 'reply_timestamp' AND meta2.meta_value BETWEEN '$twentyfour' AND '$twenty'";
	$results = $wpdb->get_results($sql);
	if(is_array($results) && !empty($results)) {
		$dslog->lwrite('Found '.count($results).' tickets');
		foreach($results as $r) {
			$post = get_post($r->id);
			$user = get_userdata($post->post_author);
			$to = $user->user_email;
			$to = str_replace('TEMP__','',$to);
			$subject = '[TICKET UPDATE] '.$post->post_title.'';
			
			$dslog->lwrite('Generating e-mail for ticket ID# '.$r->id.' and e-mail '.$user->user_email.'');
			
			$content = ''.$support_options['feedback_email_text'].'
			<p><a href="'.get_permalink($post->ID).'?fdsjlkf=1" title="Close Your Support Request">Click here to close your support request and leave valuable feedback about our products and services</a></p>
			<p>If your feel your request is not yet resolved, click the link link below to update your support request.</p>
			<p><a href="'.get_permalink($post->ID).'" title="Update Your Support Request">Click here to update your support request to continue receiving support</a></p>';		
			$headers .= 'From: '.$support_options['email-from-name'].' <'.$support_options['email-from'].'>' . "\r\n" .
						'Reply-To: '.$support_options['email-from'].'' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			$x = mail($to, htmlspecialchars_decode($subject), $content, $headers);
			
			if($x === true) {
				$dslog->lwrite('Mail sent successfully');
			} else {
				$dslog->lwrite('*** Mail FAILED ***');
			}
			
		}
	} else {
		$dslog->lwrite('*** No users found to send feedback e-mail ***');
	}
	$dslog->lwrite('---- END FEEDBACK EMAIL CHECK ----');
}


function dynamo_support_check_tickets() {
	global $support_options, $wpdb;
	$close = $support_options['close-tickets'];
	$delete = $support_option['delete-tickets'];
	
	//Close Tickets
	if($close != '0' && $close >= '1' && is_numeric($close)) {
		$table = $wpdb->prefix.'postmeta';
		$query = "SELECT DISTINCT post_id FROM $table WHERE meta_key = 'ticket_status' AND meta_value = '2'";
		$posts = $wpdb->get_results($query);
		foreach($posts as $k => $post) {
			$date = str_replace('<br/>',' ',dynamo_support_get_last_reply_date($post->post_id));
			$close_date = strtotime('+'.$close.' day' , strtotime($date));
			if(current_time('timestamp',1) >= $close_date) {
				update_post_meta($post->post_id, 'ticket_status', '0'); 
				$wpdb->query("UPDATE ".$wpdb->prefix."posts SET comment_status = 'closed' WHERE id = '$post->post_id'");
				update_post_meta($post->post_id, 'close_time', current_time('timestamp',1)); 
			}
		}
	}
	
	//Delete Tickets
	if($delete != '0' && $delete >= '1' && is_numeric($delete)) {
		$table = $wpdb->prefix.'postmeta';
		$query = "SELECT DISTINCT post_id FROM $table WHERE meta_key = 'ticket_status' AND meta_value = '0'";
		$posts = $wpdb->get_results($query);
		foreach($posts as $k => $post) {
			$date = get_post_meta($post->post_id,'close_time',true);
			if($date == '') {
				update_post_meta($post->post_id,'close_time',current_time('timestamp',1));
				$date = current_time('timestamp',1);
			}
			$delete = strtotime('+'.$delete.' day', $date);
			if(current_time('timestamp',1) >= $delete) {
				wp_delete_post($post->post_id,true);
			}
		}
	}
}



/*
* Weekly Report
*/
function sd_mail_report() {
	global $support_options, $sd_mail_template;
	if($support_options['weekly-reports'] === '1') {
		require_once($sd_mail_template);
		$headers .= 'From: Support Dynamo <support-dynamo@'.str_replace('https://','',str_replace('http://','',str_replace('www.','',$_SERVER['HTTP_HOST']))).'>' . "\r\n" .
						'Reply-To: <no-reply@'.str_replace('https://','',str_replace('http://','',str_replace('www.','',$_SERVER['HTTP_HOST']))).'>  ' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		$to = $support_options['email-admin'];
		if(mail($to, 'Support Dynamo Report For '.get_bloginfo('site_url').' - '.date('F, jS, Y ').'', $content, $headers)) { } else {  }
	} else {
		return;
	}
}

 //De-Activation Function
function dynamo_support_deactivate() {
	wp_clear_scheduled_hook('dynamo_support_twicedaily_check');
	wp_clear_scheduled_hook('dynamo_support_twenty_hours_check');
	wp_clear_scheduled_hook('dynamo_support_four_hours_check');
	wp_clear_scheduled_hook('sd_email_cron');
}

 
?>