<?php
    /*
    * Dynamo Support Mail Functions
    */		function dynamo_support_upload_attachment_email($file, $postId)	{		global $dslog;		$dslog->lwrite('Adding attachment ' . $file . ' --- ');		$uploads = wp_upload_dir();		$newfilename = basename($file);		$filename = wp_unique_filename( $uploads['path'], $newfilename, $unique_filename_callback = null );		$wp_filetype = wp_check_filetype($filename, null);		$fullpathfilename = $uploads['path'] . "/" . $filename;		$fileSaved = copy($file, $fullpathfilename);		if ( !$fileSaved ) {			return false;		}		$attachment = array(			'post_mime_type' => $wp_filetype['type'],			'post_title' => preg_replace('/\.[^.]+$/', '', $filename),			'post_content' => '',			'post_status' => 'inherit',			'guid' => $uploads['url'] . "/" . $filename		);		$attach_id = wp_insert_attachment( $attachment, $fullpathfilename, $postId);								require_once(ABSPATH . "/wp-admin/includes/image.php");				$attach_data = wp_generate_attachment_metadata( $attach_id, $fullpathfilename );		wp_update_attachment_metadata( $attach_id,  $attach_data );		return $attach_id;				        	}
    function dynamo_support_check_mail($server, $port, $username, $password, $account, $protocol = 'pop3', $ssl) {
        global $support_options, $wpdb, $dslog;

        if ($support_options['plugin_version'] == '==AUWVUeZhFZzFWMaNVTWJVU') {
            $dslog->lwrite('---- START TICKET EMAIL CHECK ----');	
            if($server == '' || $port == '' || $username == '' || $password == '') {
                $dslog->lwrite('*** No server details entered ***');
                $dslog->lwrite('---- END TICKET EMAIL CHECK ----');
                return false;
            }
            $dslog->lwrite('Starting '.$account.' at '.$server.':'.$port.'');	
            $last_checked = get_transient('support_dynamo_mailserver_'.sanitize_title($account).'_last_checked');	
            if ( $last_checked ) {
                $dslog->lwrite('Slow down, checking too soon after previous check');
                $dslog->lwrite('---- END TICKET EMAIL CHECK ----');
                return false;
            }			
            set_transient('support_dynamo_mailserver_'.sanitize_title($account).'_last_checked', '1', 300);
            $time_difference = get_option('gmt_offset') * 3600;			
            $phone_delim = '::';
            $mailserver = sprintf('{%s:%d/%s',$server,$port,strtolower($protocol));
            if($ssl == '1') {
                $mailserver .='/ssl/novalidate-cert}INBOX';
            } else {
                $mailserver .='/novalidate-cert}INBOX';
            }
            $charset = 'UTF-8';
            if(function_exists('imap_timeout')) {
                imap_timeout(IMAP_OPENTIMEOUT,20); //Open timeout.
            }

            if(!function_exists('imap_open')) {
                $dslog->lwrite('*** Server Does Not Have IMAP PHP Extension Enabled ***');
                $dslog->lwrite('---- END TICKET EMAIL CHECK ----');
                return false;
            }

            if($mbox = @imap_open($mailserver,$username,$password,NULL,1)){
                $mailCount = count(imap_headers($mbox));

                if( false === $mailCount ) {
                    $dslog->lwrite('*** Could not retreive # of new messages from server ***');
                    $dslog->lwrite('---- END TICKET EMAIL CHECK ----');
                    return false;
                }
                if(0 == $mailCount) {
                    $dslog->lwrite('*** No new mail to fetch ***');
                    $dslog->lwrite('---- END TICKET EMAIL CHECK ----');
                    return false;
                }

                $dslog->lwrite('Found '.$mailCount.' New Messages');

                for ( $i = 1; $i <= $mailCount; $i++) {

                    //Some General Vars
                    $dmonths = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
                    $comment = false;
                    $existingid = '';
                    $author_found = false;

                    //Start getting email message info
                    $headerinfo = imap_headerinfo($mbox,$i);
                    //print_r($headerinfo);
                    if($headerinfo->Recent == 'N' || $headerinfo->Unseen == 'U') {
                        $sender = $headerinfo->from[0];
                        //Get From 
                        //Check ReplyTo field first is none get From.
                        $replyTo = $headerinfo->reply_to[0];
                        if(is_array($replyTo) && $replyTo != '' && count($replyTo) == 3) {
                            $from = mime_decode($replyTo->personal);
                            $fromEmail = strtolower($replyTo->mailbox).'@'.$replyTo->host;
                        } else {
                            $from = mime_decode($sender->personal);
                            $fromEmail = strtolower($sender->mailbox).'@'.$sender->host;
                        }
                        $subject = mime_decode(@$headerinfo->subject);
                        $mid = $headerinfo->message_id;

                        /*
                        * Start To Decode Email Body
                        */
                        $body ='';
                        if(!($body = getPart($mbox, $i,'TEXT/PLAIN',$charset))) {
                            if(($body = getPart($mbox, $i,'TEXT/HTML',$charset))) {
                                //Convert tags of interest before we striptags
                                //$body = str_replace("</DIV><DIV>", "\n", $body);
                                // $body = str_replace(array("<br>", "<br />", "<BR>", "<BR />"), "\n", $body);
                                $body = strip_tags(html_entity_decode($body)); //Strip tags??
                            }
                        }
                        /*
                        * Strip Replys
                        */
                        $body =  preg_replace("/\n{3,}/", "\n", $body);
                        $tag = '##--Please reply above this line--##';
                        /*
                        if(strpos($body,$tag)) {
                        list($body)=split($tag,$body);
                        }
                        */
                        $body = extract_reply($body, $fromEmail);
                        $body = trim($body); //Remove empty lines before and after content;

                        $split = convert_line_breaks($body);
                        $split = explode('--BREAK----BREAK--',$split);
                        array_pop($split);
                        $split = implode("\n\n",$split);
                        $split = str_replace('--BREAK--',"\n",$split);
                        if($split != '') {
                            $body = $split;
                        }

                        $ddate = trim($headerinfo->date);
                        $ddate = str_replace('Date: ', '', $ddate);
                        if (strpos($ddate, ',')) {
                            $ddate = trim(substr($ddate, strpos($ddate, ',') + 1, strlen($ddate)));
                        }
                        $date_arr = explode(' ', $ddate);
                        $date_time = explode(':', $date_arr[3]);

                        $ddate_H = $date_time[0];
                        $ddate_i = $date_time[1];
                        $ddate_s = $date_time[2];

                        $ddate_m = $date_arr[1];
                        $ddate_d = $date_arr[0];
                        $ddate_Y = $date_arr[2];
                        for ( $j = 0; $j < 12; $j++ ) {
                            if ( $ddate_m == $dmonths[$j] ) {
                                $ddate_m = $j+1;
                            }
                        }

                        $time_zn = intval($date_arr[4]) * 36;
                        $ddate_U = gmmktime($ddate_H, $ddate_i, $ddate_s, $ddate_m, $ddate_d, $ddate_Y);
                        $ddate_U = $ddate_U - $time_zn;
                        $post_date = gmdate('Y-m-d H:i:s', $ddate_U + $time_difference);
                        $post_date_gmt = gmdate('Y-m-d H:i:s', $ddate_U);

                        /*
                        * Author
                        */
                        if ( preg_match('|[a-z0-9_.-]+@[a-z0-9_.-]+(?!.*<)|i', $fromEmail, $matches) )
                            $author = $matches[0];
                        else
                            $author = trim($fromEmail);
                        $author = sanitize_email($author);
                        if ( is_email($author) ) {
                            $userdata = get_user_by_email($author);
                            if ( empty($userdata) ) {
                                $userdata = get_user_by_email('TEMP__'.$author);
                                if ( empty($userdata) ) {
                                    $author_found = false;
                                } else {
                                    $post_author = $userdata->ID;
                                    $author_found = true;
                                }
                            } else {
                                $post_author = $userdata->ID;
                                $author_found = true;
                            }
                        } else {
                            $author_found = false;
                        }						if ($author_found == true) {							if (pmpro_hasMembershipLevel(4, $post_author) == false && user_can($post_author, 'access_tickets') == false) {								$author_found = false;							}						}

                        // Set $post_status based on $author_found
                        if ( $author_found ) {
                            $userdata = new WP_User($post_author);
                            $post_status =  'publish';
                            $dslog->lwrite('Exisiting user found for e-mail address '.$fromEmail.' - ID: '.$userdata->ID.'');
                        } else {
                            // Author not found in DB, set status to pending.  Author already set to admin.
                            if($support_options['open-support'] == '1') {
                                $post_status = 'pending';
                                $random_password = wp_generate_password( 12, false );

                                if($support_options['new_username'] == 'username') {
                                    $username = $from;
                                } else if($support_options['new_username'] == 'email') {
                                        $username = $fromEmail;
                                    } else {
                                        $username = $from;
                                }

                                $user_id = wp_create_user('TEMP__'.$username, $random_password, 'TEMP__'.$fromEmail);
                                if(!is_wp_error($user_id)) {
                                    $userdata = array(
                                    'ID' => $user_id,
                                    'user_nicename' => $from,
                                    'display_name' => $from,
                                    'user_email' => 'TEMP__'.$fromEmail
                                    );
                                    wp_update_user($userdata);
                                    $userdata = get_userdata($user_id);
                                    $post_author = $userdata->ID;
                                    dynamo_support_email_new_user_details($userdata, $random_password);
                                    $dslog->lwrite('Created new temp user for e-mail address '.$fromEmail.'');
                                } else {
                                    $post_author = '1';
                                }
                            } else {
                                $dslog->lwrite('Support Is Not Open To All Discarding Email');
                                //Delete and move to next message
                                imap_delete($mbox, $i);
                                imap_mail_move($mbox, $i, 'INBOX.Trash');
                                continue;
                            }
                        }

                        /*
                        * Parse Attachments If Any
                        */
                        if($support_options['allow_attachments'] == '1') {	
                            $attachments = array();
                            $struct = imap_fetchstructure($mbox,$i);
                            if(isset($struct->parts) && count($struct->parts)) {

                                foreach($struct->parts as $a => $s) {

                                    $attachments[$a] = array(
                                    'is_attachment' => false,
                                    'filename' => '',
                                    'name' => '',
                                    'bytes' => trim($s->bytes),
                                    'attachment' => ''
                                    );

                                    if($s->ifdparameters) {
                                        foreach($s->dparameters as $object) {
                                            if(strtolower($object->attribute) == 'filename') {
                                                $attachments[$a]['is_attachment'] = true;
                                                $attachments[$a]['filename'] = $object->value;
                                            }
                                        }
                                    }

                                    if($s->ifparameters) {
                                        foreach($s->parameters as $object) {
                                            if(strtolower($object->attribute) == 'name') {
                                                $attachments[$a]['is_attachment'] = true;
                                                $attachments[$a]['name'] = $object->value;
                                            }
                                        }
                                    }

                                    if($attachments[$a]['is_attachment']) {
                                        $attachments[$a]['attachment'] = imap_fetchbody($mbox, $i, $a+1);
                                        if($s->encoding == 3) { // 3 = BASE64
                                            $attachments[$a]['attachment'] = base64_decode($attachments[$a]['attachment']);
                                        }
                                        elseif($s->encoding == 4) { // 4 = QUOTED-PRINTABLE
                                            $attachments[$a]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                                        }
                                    }
                                }
                            }
                            $isattach = false;
                            if(is_array($attachments) && !empty($attachments)) {
                                foreach($attachments as $k => $attach) {
                                    $fail = false;
                                    if($attach['is_attachment'] == '1') {
                                        $filename = $attach['filename'];
                                        if(!isset($filename)) {
                                            $filename = $attach['name'];
                                        }
                                        $dslog->lwrite('Attachment Found: '.$filename.'');
                                        //Check If We Allow It In FileTypes
                                        $ext =  strtolower(substr(strrchr($filename,'.'),1));
                                        //$allowed_filetypes = array('jpg','gif','bmp','png','txt','doc','jpeg');
                                        $allowed_filetypes = $support_options['allowed_attachments'];

                                        foreach($allowed_filetypes as $x => $y) {
                                            if(trim($ext) == trim($y)) {
                                                $fail = false;
                                                break;
                                            } else {
                                                $fail = true;
                                            }
                                        }
                                        if($fail == true) {
                                            $dslog->lwrite('Attachment Rejected For Extension '.$ext.'');

                                        }
                                        //Check Max Size
                                        if($attach['bytes'] > $support_options['max_attachment_size']) {
                                            $dslog->lwrite('Attachment Rejected - FileSize Greater Than '.$support_options['max_attachment_size'].' bytes');
                                            $fail = true;
                                        }
                                        if($fail == false) {
                                            //Passed Attempt To Save
                                            $uploads = plugin_dir_path(dirname(__FILE__));
                                            $dir= $uploads.'attachments/';
                                            $rand=rand(0,16);
                                            $name=$attach['filename'];
                                            $data = $attach['attachment'];

                                            if(!file_exists(rtrim($dir,'/').'/') && @mkdir(rtrim($dir,'/').'/',0777)) {
                                                chmod(rtrim($dir,'/').'/',0777);
                                            }
                                            if(file_exists(rtrim($dir,'/')) && is_writable(rtrim($dir,'/'))) {
                                                $filename=sprintf("%s/%s_%s",rtrim($dir,'/'),$rand,str_replace(' ','-',$name));
                                            } else {
                                                $filename=rtrim($dir,'/').'/'.$rand.'_'.str_replace(' ','-',$name);
                                            }

                                            if(($fp=fopen($filename,'w'))) {
                                                fwrite($fp,$data);
                                                fclose($fp);
                                                $size=@filesize($filename);												$isattach = true;																								$url[] = $filename;
                                            }
                                        }
                                    }
                                }
                            } else {
                                $dslog->lwrite('No Attachments Found On Message');
                            }
                        } else {
                            $dslog->lwrite('Attachments Are Diabled - No Check');
                        }


                        /*
                        * Look if is a reply or a new ticket
                        */
                        if(preg_match ("[[#][0-9]{1,10}]",$subject, $regs)) {
                            $existingid= trim(preg_replace("/[^0-9]/", "", $regs[0]));
                            $dslog->lwrite('Is a Reply To A Current Ticket #'.$existingid.'');
                            if($author_found) {
                                $sql = "SELECT id FROM ".$wpdb->prefix."posts WHERE id = '$existingid' AND post_author = '$post_author'";
                                $update = $wpdb->get_var($sql);
                                if($update === $existingid) {
                                    $comment = true;
                                    $dslog->lwrite('Found parent ticket id');
                                }
                            } 
                        } 

                        /*
                        * Insert Comment
                        */

                        if($body != '' && $subject != '') {
                            if($comment === true && $existingid != '' && $existingid != '0') {
                                $postdata = get_post($update);
                                $data = array(
                                'comment_post_ID' => $update,
                                'comment_author' => $userdata->display_name,
                                'comment_author_email' => str_replace('TEMP__','',$userdata->user_email),
                                'comment_content' => nl2br(htmlspecialchars($body)),
                                'comment_parent' => '0',
                                'user_id' => $userdata->ID,
                                'comment_agent' => 'Support Dynamo (Ticket By Email)',
                                'comment_date' => $post_date,
                                'comment_approved' => 1,
                                );
                                add_action('comment_post', 'ds_comments_comment_post');
                                add_filter('preprocess_comment', 'ds_preprocess_comment');
                                $com_id = wp_new_comment($data);
                                $dslog->lwrite('Reply Inserted');
                                add_comment_meta( $com_id, 'reply_source', 'email', true );								                                if($isattach == true && is_array($url)) {                                    foreach($url as $u) {										add_comment_meta( $com_id, 'attachmentId', dynamo_support_upload_attachment_email($u, $postdata->ID), true);										@unlink($u);										break;                                    }                                }
                                if($postdata->post_author == $userdata->ID) {
                                    update_post_meta($update, 'ticket_status', '1');
                                }
                                $dslog->lwrite('Message is a reply to current ticket creating comment ID#'.$com_id.' to ticket ID#'.$update.'');
                                //Delete Email
                                imap_delete($mbox, $i);
                                imap_mail_move($mbox, $i, 'INBOX/Trash');
                            } else {
                                /*
                                * Insert New Post
                                */

                                $empty_data = array(
                                'post_title' => 'dfisfjidsflsf',
                                'post_content' => 'fjksdlfhslfjksldf',
                                'post_author' => 1,
                                'post_type' => 'ticket'
                                );

                                $post_ID = wp_insert_post($empty_data);
                                $post_title = '#'.$post_ID.' - '.$subject.'';
                                $post_content = $body;

                                $post_type = 'ticket';

                                $comment_status = 'open';

                                $ping_status = 'closed';

                                $real_status = $post_status;

                                $post_status = 'draft';

                                $post_name = sanitize_title($post_title);


                                $post_data = compact('post_content','post_title','post_date','post_date_gmt','post_author','post_name', 'post_status','post_type', 'comment_status','ping_status');
                                $post_data = add_magic_quotes($post_data);

                                $post_data['ID'] = $post_ID;

                                wp_update_post($post_data);

                                if ( is_wp_error( $post_ID ) )
                                    echo "\n" . $post_ID->get_error_message();

                                //******************* Get server info ********
                                /*  To get the topic name 
                                *   against account id from 
                                *   email setting page 
                                */  
                                //********************************************
                                $ticket_topic = 'Other';
                                $support_options_server = get_option('dynamo_support_options');
                                $support_server_arr = $support_options_server['servers'];
                                if(count($support_server_arr)){
                                    foreach($support_server_arr as $k => $servers){
                                        // Check whether the account name is to a topic from email setting page
                                         if($servers['name']==$account){
                                             $ticket_topic = $servers['email-topic-ticket'];
                                         }
                                    }
                                }
                                //add_post_meta($post_ID, 'ticket_topic', 'Other', true);
                                // Assign ticket topic by account setting
                                add_post_meta($post_ID, 'ticket_topic', $ticket_topic, true);
                                add_post_meta($post_ID, 'ticket_status', '1', true);
                                add_post_meta($post_ID, 'ticket_source', 'email',true);
                                add_post_meta($post_ID, 'email_account', $account, true);
                                add_post_meta($post_ID, 'author_email', $fromEmail, true);
                                add_post_meta($post_ID, 'no_ticket_assigned','none');								                                if($isattach == true && is_array($url)) {																		$attach_id = 1;									foreach($url as $u) {										$dslog->lwrite('Adding attachment ' . $u . ' --- ');										add_post_meta( $post_ID, 'ticket_attachment_' . $attach_id, dynamo_support_upload_attachment_email($u, $post_ID), true);										$attach_id++;										@unlink($u);                                    }                                    add_post_meta($post_ID, 'ticket_attachment_count', ($attach_id - 1), true);                                }								$dslog->lwrite('Added all attachments --- ');
                                if($real_status == 'publish') {
                                    wp_publish_post($post_ID);
                                }
                                $dslog->lwrite('Message is a new ticket, ticket ID#'.$post_ID.' created');
                                dynamo_support_update_user_count($post_author);
                                // We couldn't post, for whatever reason. Better move forward to the next email.
                                if ( empty( $post_ID ) )
                                    $dslog->lwrite('*** Failed to create post ***');
                                continue;

                                do_action('publish_phone', $post_ID);

                                imap_delete($mbox, $i);
                                imap_mail_move($mbox, $i, 'INBOX.Trash');
                            }
                        } else {
                            $dslog->lwrite('*** Failed to get mail content ***');
                        }
                    } else {
                        $dslog->lwrite('*** E-mail marked as "read" ***');
                    }
                }
                imap_delete($mbox,'1:*');
                imap_expunge($mbox);
                imap_close($mbox);	

            } else {
                $dslog->lwrite('*** Could not connect to server - Please Check Settings & Try Again ***');
                $errors = imap_errors();
                if(is_array($errors)) {
                    foreach($errors as $e) {
                        $dslog->lwrite('*** '.$e.' ***');
                    }
                }
            }
            $dslog->lwrite('---- END TICKET EMAIL CHECK ----');

            return true;
        } else {
            return false;
        }

    }

    function convert_line_breaks($string, $line_break='--BREAK--') {
        $patterns = array(   
        "/(<br>|<br \/>|<br\/>)\s*/i",
        "/(\r\n|\r|\n)/"
        );
        $replacements = array(   
        PHP_EOL,
        $line_break
        );
        @$string = preg_replace($patterns, $replacements, $string);
        return $string;
    }

    /*
    * Extract Replys
    */
    function extract_reply($body, $from) {
        $tag = '##--Please reply above this line--##';
        preg_match('/(.+?)>? ?' . $tag . '.+/is', $body, $matches);
        if (!empty($matches[1])) {
            $body = $matches[1];
        }
        /*
        $array = preg_split("/&gt;\s+##--Please.*?--##/i",$body);
        if(is_array($array) && !empty($array[1])) {
        return $array[0];
        }
        */
        return $body;
    }

    /*
    * Decode emails
    */
    function decode_mail($encoding,$text) {

        switch($encoding) {
            case 1:
                $text=imap_8bit($text);
                break;
            case 2:
                $text=imap_binary($text);
                break;
            case 3:
                $text=imap_base64($text);
                break;
            case 4:
                $text=imap_qprint($text);
                break;
            case 5:
            default:
                $text=$text;
        } 
        return $text;
    }

    /*
    * Convert text to desired encoding..defaults to utf8
    */
    function mime_encode($text,$charset=null,$enc='utf-8') {  

        $encodings=array('UTF-8','WINDOWS-1251', 'ISO-8859-5', 'ISO-8859-1','KOI8-R');
        if(function_exists("iconv") and $text) {
            if($charset)
                return iconv($charset,$enc.'//IGNORE',$text);
            elseif(function_exists("mb_detect_encoding"))
                return iconv(mb_detect_encoding($text,$encodings),$enc,$text);
        }
        return utf8_encode($text);
    }
    /*
    * Simple Mime decoder
    */
    function mime_decode($text) {

        $a = imap_mime_header_decode($text);
        $str = '';
        foreach ($a as $k => $part)
            $str.= $part->text;

        return $str?$str:imap_utf8($text);
    }
    /*
    * get Mime Type
    */
    function ds_getMimeType($struct) {
        $mimeType = array('TEXT', 'MULTIPART', 'MESSAGE', 'APPLICATION', 'AUDIO', 'IMAGE', 'VIDEO', 'OTHER');
        if(!$struct || !$struct->subtype) {
            return 'TEXT/PLAIN';
        }
        return $mimeType[$struct->type].'/'.$struct->subtype;
    }


    //search for specific mime type parts....encoding is the desired encoding.
    function getPart($mbox, $mid,$mimeType,$encoding=false,$struct=null,$partNumber=false){
        if(!$struct && $mid)
            $struct=@imap_fetchstructure($mbox, $mid);
        //Match the mime type.
        if($struct && !$struct->ifdparameters && strcasecmp($mimeType,ds_getMimeType($struct)) == 0){

            $partNumber=$partNumber?$partNumber:1;
            if(($text=imap_fetchbody($mbox, $mid, $partNumber))){
                if($struct->encoding==3 or $struct->encoding==4) //base64 and qp decode.
                    $text=decode_mail($struct->encoding,$text);

                $charset=null;
                if($encoding) { //Convert text to desired mime encoding...
                    if($struct->ifparameters){
                        if(!strcasecmp($struct->parameters[0]->attribute,'CHARSET') && strcasecmp($struct->parameters[0]->value,'US-ASCII'))
                            $charset=trim($struct->parameters[0]->value);
                    }
                    $text = mime_encode($text,$charset,$encoding);
                }
                return $text;
            }
        }
        //Do recursive search
        $text='';
        if($struct && $struct->parts){
            while(list($i, $substruct) = each($struct->parts)) {
                if($partNumber) 
                    $prefix = $partNumber . '.';
                if(($result=getPart($mbox, $mid,$mimeType,$encoding,$substruct,$prefix.($i+1))))
                    $text.=$result;
            }
        }
        return $text;
    }


    //Duplicate Comment Skipper
    function ds_preprocess_comment($comment_data)
    {
        //add some random content to comment to keep dupe checker from finding it
        $random = md5(time());	
        $comment_data['comment_content'] .= " disabledupes{" . $random . "}disabledupes";	

        return $comment_data;
    }

    function ds_comments_comment_post($comment_id)
    {
        global $wpdb;

        //remove the random content
        $comment_content = $wpdb->get_var("SELECT comment_content FROM $wpdb->comments WHERE comment_ID = '$comment_id' LIMIT 1");	
        $comment_content = preg_replace("/disabledupes\{.*\}disabledupes/", "", $comment_content);
        $wpdb->query("UPDATE $wpdb->comments SET comment_content = '" . $wpdb->escape($comment_content) . "' WHERE comment_ID = '$comment_id' LIMIT 1");

    }

?>