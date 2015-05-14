<?php
    /*
    * Dynamo Support Phone Functions
    */		    
	
	require_once("../../../../wp-load.php");
	
	function delete_url_from_twilio($url) {
		// Delete
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_USERPWD, "ACb306da4fc21fba789ef31df22738c427:c6f6c18f2b39deb81b39243524cafcae");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$result = curl_exec($ch);
		curl_close($ch);
	}
	
	function dynamo_sms_fetch_image($url) {
		if ( function_exists("curl_init") ) {
			return dyanmo_sms_curl_fetch_image($url);
		} elseif ( ini_get("allow_url_fopen") ) {
			return dyanmo_sms_fopen_fetch_image($url);
		}
	}
	function dyanmo_sms_curl_fetch_image($url) {
		global $dslog;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Charset: utf-8'));
		curl_setopt($ch, CURLOPT_USERPWD, "ACb306da4fc21fba789ef31df22738c427:c6f6c18f2b39deb81b39243524cafcae");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$image = curl_exec($ch);
		curl_close($ch);
		
		return $image;
	}
	function dyanmo_sms_fopen_fetch_image($url) {
		$image = file_get_contents($url, false, $context);
		return $image;
	}
	
	function mime_type_to_ext($mime) {
		$mapping = array('image/jpeg' => 'jpg',
						'image/jpg' => 'jpg',
						'image/jp_' => 'jpg',
						'application/jpg' => 'jpg',
						'application/x-jpg' => 'jpg',
						'image/gif' => 'gif',
						'image/png' => 'png',
						'application/png' => 'png',
						'application/x-png' => 'png',				
						'audio/mp4' => 'mp4',
						'audio/mpeg' => 'mpeg',
						'video/mp4' => 'mp4',
						'video/mpeg' => 'mpeg');
						
		return $mapping[$mime];
	}
	
    function dynamo_support_handle_sms() {
        global $wpdb, $dslog;

        //Is Pro Check

            $dslog->lwrite('---- GOT TEXT MESSAGE ----');	
			
			$from_number = $_REQUEST['From'];
			$message = $_REQUEST['Body'];
			$account_sid = $_REQUEST['AccountSid'];
			$attachments = $_REQUEST['NumMedia'];
			
			if ($account_sid != 'ACb306da4fc21fba789ef31df22738c427') {
				$dslog->lwrite('--WRONG ACCOUNT--');
				return false;
			}
			
			$user_data = new WP_User_Query( array( 'meta_key' => 'phone-number', 'meta_value' => substr($from_number, 2) ) );
			$user_data = $user_data->get_results();
			if ( empty($user_data) || count($user_data) <= 0) {
				$user_data = new WP_User_Query( array( 'meta_key' => 'phone-number', 'meta_value' => $from_number ) );
				$user_data = $user_data->get_results();
			}
			
			if ( empty($user_data) || count($user_data) <= 0) {
			    $dslog->lwrite('-- USER is not registered ' . $from_number . ' or ' . substr($from_number, 2) .' --');
				return '<Response><Message>Thanks for contacting GEDBoard. Please subscribe to our tutoring service and authorize your phone number for texting. Sign up at www.gedboard.com/tutoring/</Message></Response>';
			}
			
			if (count($user_data) > 1) {
			    $dslog->lwrite('-- Multiple users registered ' . $from_number . ' or ' . substr($from_number, 2) .' --');
				return '<Response><Message>Thanks for contacting GEDBoard. We encountered a problem because it seems multiple users have registered this phone number. Please contact us service@gedboard.com.</Message></Response>';
			}
				
			
			if (pmpro_hasMembershipLevel(4, $user_data[0]->ID) == false) {
			    $dslog->lwrite('-- USER ' . $user_data[0]->ID . ' is not active member ' . $from_number . '--');
				return '<Response><Message>Thanks for contacting GEDBoard. Please subscribe to our tutoring service and authorize your phone number for texting. Sign up at www.gedboard.com/tutoring/</Message></Response>';
			}
			
			$userdata = new WP_User($user_data[0]->ID);
            $post_status =  'publish';
            $dslog->lwrite('Existing user found for phone number '.$from_number.' - ID: '.$userdata->ID.'');

			$subject = substr($message, 0, 20);
            if($message != '' && $subject != '') {
                $empty_data = array(
                                'post_title' => 'dfisfjidsflsf',
                                'post_content' => 'fjksdlfhslfjksldf',
                                'post_author' => 1,
                                'post_type' => 'ticket'
                                );

                $post_ID = wp_insert_post($empty_data);
                $post_title = '#'.$post_ID.' - TEXT -'.$subject.'';
                $post_content = $message;

                $post_type = 'ticket';

                $comment_status = 'open';

                $ping_status = 'closed';

                $real_status = $post_status;

                $post_status = 'draft';
				$post_author = $userdata->ID;
                $post_name = sanitize_title($post_title);


                $post_data = compact('post_content','post_title','post_date','post_date_gmt','post_author','post_name', 'post_status','post_type', 'comment_status','ping_status');
                $post_data = add_magic_quotes($post_data);

                $post_data['ID'] = $post_ID;

                wp_update_post($post_data);

                if ( is_wp_error( $post_ID ) )
                    echo "\n" . $post_ID->get_error_message();
				
				/*
					* Parse Attachments If Any
				*/
				if(true) {
					$current_media = 0;
					$num_valid_attachments = 0;
					$dslog->lwrite('-- Attachment count ' . $attachments . ' --');
					for ($current_media = 0; $current_media < $attachments; $current_media++) {
						$media_url = $_REQUEST['MediaUrl' . $current_media];
						$media_type = $_REQUEST['MediaContentType' . $current_media];
						
						$dslog->lwrite('-- Attachment url ' . $_REQUEST['MediaUrl' . $current_media] . ' --');
						$dslog->lwrite('-- Attachment type ' . $media_type . ' --');
						if ( empty($media_url) )
							continue;
					
						$media_url = stripslashes($media_url);
						$uploads = wp_upload_dir();
						$ext = mime_type_to_ext($media_type);
						$newfilename = 'Attachment ' . $num_valid_attachments . '.' . $ext;
					
						if ( empty($ext) || $ext == '') {
							wp_delete_post($post_ID);
							delete_url_from_twilio($media_url);
							return '<Response><Message>Thanks for contacting GEDBoard. The message you sent had an attachment that we do not support. Please use a jpg, gif, png or mp4</Message></Response>';
						}
					
						$filename = wp_unique_filename( $uploads['path'], $newfilename, $unique_filename_callback = null );
						$wp_filetype = wp_check_filetype($filename, null );
						$fullpathfilename = $uploads['path'] . "/" . $filename;

						$image_string = dynamo_sms_fetch_image($media_url);
						$fileSaved = file_put_contents($fullpathfilename, $image_string);
							
						if ( !$fileSaved ) {
							delete_url_from_twilio($media_url);
							continue;
						}
				
						$attachment = array(
								'post_mime_type' => $media_type,
								'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
								'post_content' => '',
								'post_status' => 'inherit',
								'guid' => $uploads['url'] . "/" . $filename
						);
						
						$attach_id = wp_insert_attachment( $attachment, $fullpathfilename, $post_ID );
						if ( !$attach_id ) {
							delete_url_from_twilio($media_url);
							continue;
						}
						
						require_once("../../../../wp-admin/includes/image.php");
						$attach_data = wp_generate_attachment_metadata( $attach_id, $fullpathfilename );
						wp_update_attachment_metadata( $attach_id,  $attach_data );
						update_post_meta($post_ID, 'ticket_attachment_' . ($num_valid_attachments + 1), $attach_id);
						$num_valid_attachments++;
						
						delete_url_from_twilio($media_url);
					}
					
					update_post_meta($post_ID, 'ticket_attachment_count', $num_valid_attachments);
				}

                $ticket_topic = 'Other';
                
                add_post_meta($post_ID, 'ticket_topic', $ticket_topic, true);
                add_post_meta($post_ID, 'ticket_status', '1', true);
                add_post_meta($post_ID, 'ticket_source', 'email',true);
                add_post_meta($post_ID, 'email_account', '', true);
                add_post_meta($post_ID, 'author_email', $user_data[0]->email, true);
                add_post_meta($post_ID, 'no_ticket_assigned','none');								                                
								
				if($real_status == 'publish') {
                    wp_publish_post($post_ID);
                }
   
                $dslog->lwrite('Message is a new ticket, ticket ID#'.$post_ID.' created');
                dynamo_support_update_user_count($userdata->ID);
				
                if ( empty( $post_ID ) )
                    return '<Response><Message>The question you submitted did not have any content. Please try again or let us know at service@gedboard.com</Message></Response>';
                
				do_action('publish_phone', $post_ID);
				$dslog->lwrite('-- CP3 --');
				return '<Response><Message>Thanks for submitting your question to GEDBoard. Your question has been submitted with question number ' . $post_ID . '. We will email you when a tutor answers your question. Thanks for using GEDBoard.</Message></Response>';
			}	else {
				return '<Response><Message>The question you submitted did not have any content. Please try again or let us know at service@gedboard.com</Message></Response>';
			}
		}

echo dynamo_support_handle_sms();

?>