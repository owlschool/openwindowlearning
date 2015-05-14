<?php
/*
* Dynamo Support Email Ticket
*/
//Require WP_Load Script

require_once('../../../../wp-load.php');
global $support_options;
$support_options = get_option('dynamo_support_options');
if(is_user_logged_in() && current_user_can('manage_options')) {
?>
<html>
<title>E-Mail Question</title>
<head>

</head>
<body>
<?php


$post = get_post($_GET['id']);
$user = $_GET['user'];

//Save Note Function
if($_POST['email-submit'] && !empty($_POST['email-submit'])) {
	$author = $post->post_author;
	$author = get_userdata($author);
	$to = trim($_POST['to']);
	$subject = '[FORWARDED TICKET] #'.$post->ID.' - '.$post->post_title.'';;
	$headers .= 'From: '.$support_options['email-from-name'].' <'.$support_options['email-from'].'>' . "\r\n" .
				'Reply-To: '.$support_options['email-from'].'' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'X-Mailer: PHP/' . phpversion();
	$content = 'A question from '.get_bloginfo('url').' has been forwarded to you, if you have received this e-mail by mistake please delete it.<br/><br/>MESSAGE<br/>---------------------------------------------------<br/>
		'.stripslashes($_POST['note']).'
		<br/>---------------------------------------------------<br/>
		AUTHOR DETAILS
		<br/>---------------------------------------------------<br/>
		<div id="author-data">
			<strong>Ticket #'.$post->ID.' Created By:</strong> '.$author->first_name.' '.$author->last_name.' ('.$author->display_name.') <span class="ticket_count">('.dynamo_support_get_user_total_tickets($author->ID).')
			<br/>
			<strong>E-Mail:</strong> '.$author->user_email.'
			<br/>
			<strong>Publish Date:</strong> '.date('m/d/Y - H:m:s',strtotime($post->post_date)).'
			<br/>
			<strong>Topic:</strong> '.get_post_meta($post->ID,'ticket_topic', true).'';
		
				
				if($support_options['integration'] != '') {
	if($support_options['integration'] == 'wishlist') {
		
		$content .= dynamo_support_wlm_price_calc($author);
	} else if($support_options['integration'] == 'memberwingx') {
		$content /= dynamo_support_mwx_price_calc($author);
	}
}
	if($support_options['integration'] != '' && $total != '') {
		$content .='<strong>Total Value:</strong> $'.$total.'';
	}
				
				
				$content .='</div> ';
		$content .= '</div>
		<br/>---------------------------------------------------<br/>
		QUESTION CONTENT
		<br/>---------------------------------------------------<br/>
		'.$post->post_content.'<br/>';
		
		$args = array(
					'post_id' => $post->ID,
					'order' => 'ASC'
					
					);
			$comments = get_comments($args);
			if(is_array($comments) && count($comments) > 0) {
				foreach($comments as $k => $v) {
					$content .= '<br/>---------------------------------------------------<br/>
									Reply By: '.$v->comment_author.' on '.$v->comment_date.'
								<br/>---------------------------------------------------<br/>
									'.$v->comment_content.'<br/>';
				}
			}
		
	mail($to, $subject, $content, $headers);
	?>
	<div style="background:#FFFFE0; border:1px solid #E6DB55; margin:5px 0 15px 0; boredr-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; padding:0 0.6em;">
		<p style="margin:1em 0; font-size:12px; padding:2px; line-height:140%;"><strong>Ticket Sent</strong></p>
	</div>
	<?php
}
echo '<h2>E-Mail Question: '.$post->post_title.'</h2>';
?>
<form method="post" action="">
<label for="to"><b>Enter Recipients E-Mail Address:</b></label><br/>
<input type="text" name="to" id="to" placeholder="Enter recipents e-mail address" style="width:100%;"/><br/><br/>
<label for="note"><b>Enter A Message To The Recipient:</b></label><br/>
<textarea style="width:100%; height:150px;" id="note" name="note"></textarea><br/>
<input type="hidden" name="user" value="<?php echo $user;?>"/>
<input type="hidden" name="date" value="<?php echo current_time('mysql',0); ?>"/>
<input type="submit" name="email-submit" value="Submit Question &#0187;"/>
</form>
</body>
</html>
<?php
} else {
die('Access Denied');
}
?>