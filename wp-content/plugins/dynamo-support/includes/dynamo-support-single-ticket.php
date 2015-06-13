<?php
/*
* Dynamo Support Front End View Single Ticket Functions
*/
function displayAttachmentForPost($post_id){	$image_mimetypes = array(		'image/jpeg',        'image/jpg',        'image/jp_',        'application/jpg',        'application/x-jpg',        'image/pjpeg',        'image/pipeg',        'image/vnd.swiftview-jpeg',        'image/x-xbitmap',        'image/gif',        'image/x-xbitmap',        'image/gi_',        'image/png',        'application/png',        'application/x-png'    );    	$attachments = get_post_meta($post_id, 'ticket_attachment_count', TRUE);	$content = '';                $contentBefore  = '<div class="attachmentFile"><p>Attachments: ';    $contentAfter   = '</p><div class="clear clearfix"></div></div>';    if(!empty($attachments) && $attachments > 0){		$attach_current = 1;        while ($attachments > 0) {            $attachmentId = get_post_meta($post_id, 'ticket_attachment_' . $attach_current, true);            $attachmentLink = wp_get_attachment_url($attachmentId);            $attachmentMeta = wp_get_attachment_metadata($attachmentId);            $attachmentName = basename(get_attached_file($attachmentId));            $attachmentType = get_post_mime_type($attachmentId);            $attachmentRel  = '';            if(is_admin()){                $contentInner = $attachmentName;            } else {                if(in_array($attachmentType, $image_mimetypes)){                    $attachmentRel = 'rel="lightbox"';                    $contentInner = wp_get_attachment_image($attachmentId);                } else {                    $contentInner = '&nbsp;<strong>' . $attachmentName . '</strong>';                }            }            if(is_admin()){                $contentInnerFinal = '<a '.$attachmentRel.' class="attachmentLink" target="_blank" href="'. $attachmentLink .'" title="Download: '. $attachmentName .'">';                $contentInnerFinal .= $contentInner;                $contentInnerFinal .= '</a>';            } else {				$contentInnerFinal = '<a '.$attachmentRel.' class="attachmentLink" target="_blank" href="'. $attachmentLink .'" title="'. $attachmentName .'">';                $contentInnerFinal .= $contentInner;                $contentInnerFinal .= '</a>';            }             $content .= $contentInnerFinal;                			$attach_current++;			$attachments--;		}				$content = $contentBefore . $content . $contentAfter;    }			    return $content;}
 /*
 * Only let author and admin look at ticket if allowed rel no index no follow on page
 */
 add_action('template_redirect', 'dynamo_support_ticket_auth');
 function dynamo_support_ticket_auth() {
	global $post, $current_user, $support_options;
	if($_GET['fdsjlkf'] == '1') {
		$ads = '?fdsjlkf=1';
	}
	$access = false;
	$invalid = false;
	$login = false;
	if(is_single()) {
		if($post->post_type === 'ticket') {
			if($support_options['open-support'] == '1') {
				//Open
				if(is_user_logged_in()) {
					//Logged in
					//Is Admin
					if(current_user_can('manage_options') || $current_user->ID == $post->post_author || current_user_can('access_tickets')) {
						$access = true;
					} else {
						$invalid = true;
					}
				} else {
					//Not Logged in
					$login = true;
				}
			} else {
				//Closed
				if(is_user_logged_in()) {
					//Logged in
					if(current_user_can('manage_options') || $current_user->ID == $post->post_author || current_user_can('access_tickets')) {
						$access = true;
					} else {
						$invalid = true;
					}
				} else {
					//Not Logged In
					$login = true;
				}
			}
			
			if($access === false && $invalid === true) {
				wp_redirect(get_bloginfo('home'));
				exit;
			}
			if($access === false && $login === true) {
				wp_redirect(wp_login_url( get_permalink() . $ads ));
				exit;
			}
		}
	}
 }
 
 function dynamo_support_single_ticket_head() {
	global $post;
	if(is_single()) {
		if($post->post_type === 'ticket') {
			echo "\r\n<meta name=\"robots\" content=\"noindex, nofollow\"/>\r\n\r\n";
		}
	}
	return;
 }
 add_action('wp_head','dynamo_support_single_ticket_head');
 /*
 * Send e-mail to ticket creator on admin replies
 */
 add_action('comment_post','dynamo_support_send_notification');
 function dynamo_support_send_notification($comment_id, $status = '') {
	global $support_options, $dslog, $plugin_folder, $current_user;
	$dslog->lwrite('--- START REPLY NOTIFICATION ---');
	$comment = get_comment($comment_id); 
	$post = get_post($comment->comment_post_ID );
	if($comment->user_id != $post->post_author) {
		//Admin Reply Notify Creator
		
		//Check Ticket Assignment
		$assign = get_post_meta($post->ID,'ticket_assigned',true);
		if(isset($assign) && is_numeric($assign)) {
                    
                    $lock = get_post_meta($id, 'ticket_assign_lock', true);
                    
                    if(empty($lock))
                        update_post_meta($id,'ticket_assigned',$current_user->ID);
                    
		} else {
			update_post_meta($post->ID,'ticket_assigned',$current_user->ID);
			delete_post_meta($post->ID, 'no_ticket_assigned','none');
		}
		$comment = get_comment($comment_id); 
		
		if($post->post_type === 'ticket') {
		
			$author = $post->post_author;
			$author = get_userdata($author);
			
			if(substr($author->user_email,0,6) == 'TEMP__') {
				$temp = true;
			}
			
			$to = str_replace('TEMP__','',$author->user_email);
			$subject = '=?UTF-8?B?'.base64_encode('[QUESTION UPDATE] '.$post->post_title).'?=';
			$comment_content = explode('<!-- ATTACHMENT SEPERATOR -->',$comment->comment_content);
			$comment_content = $comment_content[0].'......';
			
			$content = stripslashes($support_options['ticket-reply-email']);
			
			$content = str_replace('%title%',$post->post_title,$content);
			$content = str_replace('%link%',get_permalink($post->ID),$content);
			$content = str_replace('%days%',$support_options['close-tickets'],$content);
			$content = str_replace('%id%',$comment->comment_post_ID,$content);
			$content = str_replace('%content%', '<br/><br/>-----------------------------------------<br/><br/>'.$comment_content.'<br/><br/>-----------------------------------------<br/><br/>' ,$content);
			
			if($temp === true) {
				$content .= 'Please reply to this e-mail to continue with your question.';
			} else {
				$content .= '<a href="'.get_permalink($post->ID).'" title="View Your Question">Click Here</a> to log in and view your question or copy and paste this URL to your browser: '.get_permalink($post->ID).'';
			}
			
			$content = '##--Please reply above this line--##<br/><br/>'.$content;
			
			$headers .= 'From: '.$support_options['email-from-name'].' <'.$support_options['email-from'].'>' . "\r\n" .
						'Reply-To: '.$support_options['email-from'].'' . "\r\n";
			$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			$x = @mail($to, htmlspecialchars_decode($subject), $content, $headers);
			if($x === true) {
				$dslog->lwrite('Send reply notification to '.$to.'');
			} else {
				$dslog->lwrite('*** Failed to send reply notification to '.$to.'***');
			}
			//Update Ticket Status To Answered (2)
			update_post_meta($comment->comment_post_ID, 'ticket_status', '2');
			update_post_meta($comment->comment_post_ID, 'reply_timestamp',date('Y-m-d H:i:s',current_time('timestamp',1)));
		}
	} else {
		//Original Poster Reply = Ticket Author
		//Update Ticket Status To Open (1)
		$comment = get_comment($comment_id); 
		update_post_meta($comment->comment_post_ID, 'ticket_status', '1');
		
		if($support_options['email-admin-notice'] == '1') {
			$comment = get_comment($comment_id); 
			$comment_content = explode('<!-- ATTACHMENT SEPERATOR -->',$comment->comment_content);
			$comment_content = $comment_content[0].'......';
			$post = get_post($comment->comment_post_ID );
			
			if($post->post_type === 'ticket') {
				
				$author = $post->post_author;
				$author = get_userdata($author);
				
				$headers .= 'From: '.$support_options['email-from-name'].' <'.$support_options['email-from'].'>' . "\r\n" .
						'Reply-To: '.$support_options['email-from'].'' . "\r\n";
				$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
				$headers .= 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				
				
				$subject = '=?UTF-8?B?'.base64_encode('[QUESTION UPDATE] '.$post->post_title).'?=';
				$content = 'Update to question from <strong>'.$author->first_name.' '.$author->last_name.'</strong> ('.$author->display_name.')<br/>Topic: <strong>'.get_post_meta($post->ID,'ticket_topic',true).'</strong><br/><br/>-------------Support Ticket--------------<br/><br/>'.$comment_content.'<br/><br/>----------------------------------------------<br/><br/>';				
				$to = dynamo_support_email_to(trim(get_post_meta($post->ID,'ticket_topic',true)));
				foreach($to as $t) {
					$mail = @mail(trim($t), htmlspecialchars_decode($subject), $content, $headers);
					if($mail === true) {
						$dslog->lwrite('Reply notification sent to '.$t.'');
					} else {
						$dslog->lwrite('*** Failed to send reply notification to '.$t.' ***');
					}
				}	
			}
		}
	}
	$dslog->lwrite('--- END REPLY NOTIFICATION ---');
 }
 
 
 
 /*
 * Include before post content the user options
 */
 add_filter('the_content','dynamo_support_display_user_options_on_single');
 function dynamo_support_display_user_options_on_single($post_content) {
	global $post, $authordata, $support_options;
	if(!current_user_can('manage_options')) {
		if(is_single()) {
			if($post->post_type == 'ticket') {
				$status = get_post_meta($post->ID,'ticket_status',true); 
				$s = $status;
				
				if($status == '0') {
					$status = 'open';
					$current = 'Closed';
				} 
				if($status == '1') {
					$status = 'close';
					$current = 'Open';
				}
				if($status == '2') {
					$status = 'close';
					$current = 'Answered';
				}
				
				
global $current_user;

$content = '<menu id="ticket-bar">
	
				<ul>
					<li class="current-user"><a href="'.get_permalink($support_options['support-page']).'"><span class="gravatar">'.get_avatar($current_user->ID,'32').'</span><strong>Logged In As:</strong><br/><em>'.$current_user->display_name.'</em></a></li>';
		
		if($_GET['fdsjlkf'] != '1' && $current_user->ID == $post->post_author) {		$content .= ' <li class="user-menu"><a href="http://www.gedboard.com/tutoring/" style="color: white; background-color: #ed6124; height: 28px; padding: 4px 12px; font-size: 14px; font-weight: normal; line-height: 28px;" title="View Your Open Questions">All Questions</a></li>';		if (ucfirst($current) == 'Closed') {
		$content .=' <li class="user-menu last">This Question Is <strong id="ticket-current-status">Answered. </strong><a class="ticket-status" style="margin-left: 10px;" href="#'.$status.'-ticket" rel="'.$post->ID.'" title="'.ucfirst($status).' this question">I have a follow-up</a></li>';		} else {		$content .='<li class="user-menu last">This Question Is <strong id="ticket-current-status">'.$current.'. </strong><a class="ticket-trash" href="#trash-ticket" rel="'.$post->ID.'" title="Trash this ticket">I want to delete it</a></li>';		}
		}		
	$content .='</ul>
	
</menu>';$content .= displayAttachmentForPost($post->ID);$content .= '<input type="hidden" value="'.get_bloginfo('wpurl').'" id="ajax-url"/><input type="hidden" value="'.$status.'" id="ticket-status"/>';
		if($support_options['plugin_version'] != 'VZlWXRlVsNnUsR2MadEdaZVMaZVVB1TP') {
			if($_GET['fdsjlkf'] == '1' && $current_user->ID == $post->post_author && ($s == '1' || $s == '2')) {
				
				//Close email request
				$content .='<div id="ticket-close-request"><strong>Want to close this question?</strong><br/><a href="#" title="'.$post->ID.'" rel="close" class="close tick-close-action">Close</a> <a href="#" title="'.$post->ID.'" rel="leave" class="open tick-close-action">Leave Open</a></div>';
			} else if ($_GET['fdsjlkf'] == '1' && $current_user->ID == $post->post_author && $s == '0') {
				$content .= '<div id="ticket-close-request">'.dymamo_support_close_feedback($post->ID).'</div>';
			}
		}
				
				
			}
		}
	}
	$post_content = $content . $post_content;
	return $post_content;
 }
 
/*
 * Include before post content the admin options
 */
 add_filter('the_content','dynamo_support_display_admin_on_single');
 function dynamo_support_display_admin_on_single($content) {
	global $post, $authordata, $current_user, $support_options, $wpdb;
	if(current_user_can('manage_options')) {
		if(is_single()) {
			if($post->post_type === 'ticket') {
				$status = get_post_meta($post->ID,'ticket_status',true); 
				if($status == '0') {
					$status = 'open';
					$current = 'Closed';
				} 
				if($status == '1') {
					$status = 'close';
					$current = 'Open';
				}
				if($status == '2') {
					$status = 'close';
					$current = 'Answered';
				}
				$ds_content = '<menu id="ticket-bar">
	
							<ul>
							<li class="current-user"><a href="'.get_permalink($support_options['support-page']).'"><span class="gravatar">'.get_avatar($current_user->ID,'32').'</span><strong>Logged In As:</strong><br/><em>'.$current_user->display_name.'</em></a></li>';
		
									$ds_content .= '<li class="user-menu"><a href="http://www.gedboard.com/tutoring/" style="color: white; background-color: #ed6124; height: 28px; padding: 4px 12px; font-size: 14px; font-weight: normal; line-height: 28px;" title="View Your Open Questions">All Questions</a></li>							<li class="user-menu last">This Question Is <strong id="ticket-current-status">'.$current.'.</strong><a class="ticket-status" style="margin-left: 10px;" href="#'.$status.'-ticket" rel="'.$post->ID.'" title="'.ucfirst($status).' this question">'.ucfirst($status).' It </a> | <a class="ticket-trash" href="#trash-ticket" rel="'.$post->ID.'" title="Trash this ticket">Trash It</a> | <a class="email-ticket" href="'.get_bloginfo('wpurl').'/wp-content/plugins/dynamo-support/includes/dynamo-support-email-ticket.php?id='.$post->ID.'&amp;user='.$current_user->ID.'&amp;TB_iframe=true" rel="'.$post->ID.'" title="E-Mail this question">Email It</a></li>
							
							
							</ul>
	
							</menu>
							<div id="author-data">
							<strong class="title-details">Question Details</strong>
							<input type="hidden" value="'.get_bloginfo('wpurl').'" id="ajax-url"/><input type="hidden" value="'.$status.'" id="ticket-status"/>
							<strong>Question #'.$post->ID.' Created By:</strong> <a target="_blank" href="'.get_bloginfo('wpurl').'/wp-admin//user-edit.php?user_id='.$authordata->ID.'" title="Edit This User">'.$authordata->first_name.' '.$authordata->last_name.'</a> ('.$authordata->display_name.') <span class="ticket_count"><a href="'.get_bloginfo('wpurl').'/wp-admin/edit.php?post_type=ticket&author='.$authordata->ID.'" title="View All Questions By This User - Count (Current # of questions in system by user | Total # of questions ever submitted by user)">('.dynamo_support_get_user_total_tickets($authordata->ID).')</a></span>';
						if($support_options['plugin_version'] != 'VZlWXRlVsNnUsR2MadEdaZVMaZVVB1TP') {	
							$ds_content .= '<a href="#" class="user-datacard" rel="'.$authordata->ID.'"></a>';
						}
						if($support_options['plugin_version'] != 'VZlWXRlVsNnUsR2MadEdaZVMaZVVB1TP') {				
							if($support_options['integration'] != '') {
								$ds_content .= '<span class="user-spending" rel="'.$authordata->ID.'"></span>';
							}
						}
						
							$ds_content .='<br/>
							<strong>E-Mail:</strong> '.$authordata->user_email.'
							<br/>
							<strong>Member Since:</strong> '.date('M jS, Y',strtotime(substr($authordata->user_registered,0,10))).'
							<br/>
							<strong>Publish Date:</strong> '.date('m/d/Y - H:m:s',strtotime($post->post_date)).'
							<br/>
							<strong>Topic:</strong> '.get_post_meta($post->ID,'ticket_topic', true).'
							<a class="view-ticket-notes" style="margin-left:15px;" href="'.get_bloginfo('wpurl').'/wp-content/plugins/dynamo-support/includes/dynamo-support-notes.php?id='.$post->ID.'&amp;user='.$post->post_author.'&amp;TB_iframe=true" title="View or Add Question Notes" rel="'.$post->ID.'"/>View/Add Notes</a>';							$content .= displayAttachmentForPost($post->ID);
				$ds_content .='</div> '.$content;
				$content = $ds_content;
			}
		}
	}
	return $content;
 }
 /*
 * Front End Ajax To Open/Close/Trash Topics
 */
function dynamo_support_edit_status() {
	global $wpdb;
	$status = $_POST['status'];
	$post_id = $_POST['post_id'];
	
	if($status === 'close') {
		update_post_meta($post_id, 'ticket_status', '0'); 
		$wpdb->query("UPDATE ".$wpdb->prefix."posts SET comment_status = 'closed' WHERE id = '$post_id'");
		update_post_meta($post_id, 'close_time', current_time('timestamp',1)); 
	} else {
		update_post_meta($post_id, 'ticket_status', '1'); 
		$wpdb->query("UPDATE ".$wpdb->prefix."posts SET comment_status = 'open' WHERE id = '$post_id'");
		delete_post_meta($post_id,'close_time');
	}
	die();
 }
add_action('wp_ajax_dynamo_support_edit_status', 'dynamo_support_edit_status');
add_action('wp_ajax_nopriv_dynamo_support_edit_status', 'dynamo_support_edit_status');
function dynamo_support_trash_ticket() {
	$post_id = $_POST['post_id'];
	wp_delete_post($post_id);		if (current_user_can('manage_options')) {		echo '/wp-admin/edit.php?post_type=ticket';	} else {		echo '/tutoring/';	}
	die();
 }
add_action('wp_ajax_dynamo_support_trash_ticket', 'dynamo_support_trash_ticket');
add_action('wp_ajax_nopriv_dynamo_support_trash_ticket', 'dynamo_support_trash_ticket');




/*
* Ajax to add support tag to uploaded media from Single ticket admin
*/
function dynamo_support_update_attachment_meta() {
	global $wpdb;
	$imgurl = $_POST['imgurl'];
	$table = $wpdb->prefix.'posts';
	$post_id = $wpdb->get_var("SELECT id FROM $table WHERE guid = '$imgurl'");
	if($post_id != '') {
		update_post_meta($post_id,'ticket_attachment','1');
	}
	die();
}
add_action('wp_ajax_dynamo_support_update_attachment_meta', 'dynamo_support_update_attachment_meta');
add_action('wp_ajax_nopriv_dynamo_support_update_attachment_meta', 'dynamo_support_update_attachment_meta');


/*
* User Datacard
*/
function dynamo_support_user_datacard() {
	$userid = $_REQUEST['val'];
	$user = get_userdata($userid);
	$first = $user->user_firstname;
	$last = $user->user_lastname;
	echo '<a class="google" href="http://www.google.com/search?hl=en&q=%22'.$first.'+'.$last.'%22" title="Google Search User" target="_blank"></a>';
	echo '<a class="twitter" href="http://twitter.com/#!/search/users/'.$first.'%20'.$last.'" title="Twitter Search User" target="_blank"></a>';
	echo '<a class="facebook" href="http://www.facebook.com/search.php?q='.$first.'%20'.$last.'&init=quick" title="Facebook Search User" target="_blank"></a>';
	echo '<a class="linkedin" href="http://www.linkedin.com/pub/dir/?first='.$first.'+&last='.$last.'&search=Go" title="Linkedin Search User" target="_blank"></a>';
	echo '<a class="email" href="http://google.com/search?hl=en&q=%22'.$user->user_email.'%22" title="Google Search Users E-Mail" target="_blank"></a>';
	die();
}
global $support_options;
if($support_options['plugin_version'] != 'VZlWXRlVsNnUsR2MadEdaZVMaZVVB1TP') {
	add_action('wp_ajax_dynamo_support_user_datacard','dynamo_support_user_datacard');
	add_action('wp_ajax_nopriv_dynamo_support_user_datacard','dynamo_support_user_datacard');
}

/*
* Add Upload Image to Admin comment form on ticket single page
*/
//Pro Check
if($support_options['plugin_version'] != 'VZlWXRlVsNnUsR2MadEdaZVMaZVVB1TP') {
	//add_action('comment_form','dynamo_admin_upload_images');
	//add_filter( 'comment_form_field_comment', 'dynamo_support_upload_attachment_link',10, 2 );
}
/*
function dynamo_admin_upload_images() {
	global $post;
	if(current_user_can('manage_options')) {
		if(is_single()) {
			if($post->post_type === 'ticket') {
			echo '<input type="hidden" id="blog-url" value="'.get_bloginfo('wpurl'
				).'"/><a class="left" href="#" id="upload_image_attachment" title="Upload an attachment to this reply">Upload Attachment</a>';
			}
		}
	}
}
*/
/*
* REQUIRES THEME TO BE USING WP 3.0+ Comment form and calling comment_form(@args, @postid);
*/
//Add Attachments to ticket reply.
if(!function_exists('br2nl')) {
function br2nl($string){ 
  $return=eregi_replace('<br[[:space:]]*/?'. 
    '[[:space:]]*>',"\n",$string); 
  return $return; 
} 
}
?>