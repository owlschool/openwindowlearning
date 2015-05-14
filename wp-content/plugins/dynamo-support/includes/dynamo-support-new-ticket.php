<?php
    /*
    * Dymnamp Support Crate New Ticket Functions
    */
	
    /*
    * Get and return an array of created topics
    */
    function dynamo_support_get_topics($admin = '') {
        global $current_user, $wpdb, $support_options;
		$topics = array();
        if($admin === true) {
            //Show All Available Topics To Admin in Admin Section regardless of hidden or not or location
            foreach($support_options['topics'] as $k=>$v) {
                $topics[]->name = $v['name'];
            }
            return $topics;
        }

        if($support_options['integration'] == 'wishlist' && is_user_logged_in()) {
            //Integration With Wishlist is true use only valid wish list membership levels as topics
            //Only return user active subscribed levels that are not hidden
            $levels = dynamo_support_get_user_wlm_levels($current_user->ID);
            foreach($support_options['topics'] as $c => $b) {
				if(is_array($levels) && count($levels) > 0) {
					foreach($levels as $k => $v) {
						if($v['name'] === $b['name'] && $b['hide'] != 'true' && $b['source'] == 'wlm' && $v['status'] == 'Active') {
							$topics[]->name = $v['name'];
							break;
						} 
					}
				}
            }
        } else if($support_options['integration'] == 'memberwingx' && is_user_logged_in()) {
            //integration with MemberWing X is tru only valid memberwing levels as topics
            //only return when actively subscribed to level that are not hidden
            $levels = dynamo_support_get_user_mwx_levels($current_user->ID);
            foreach($support_options['topics'] as $c => $b) {
                foreach($levels as $k => $v) {
                    if($v['name'] === $b['name'] && $b['hide'] != 'true' && $b['source'] == 'mwx' && $v['status'] == 'active') {
                        $topics[]->name = $v['name'];
                        break;
                    }
                }
            }
        }
        //Regular Topics
        // Check topic availability
        if(is_array($support_options['topics']) && count($support_options['topics'])){
            foreach($support_options['topics'] as $k => $v) {
				if($v['hide'] != 'true' && $v['source'] == 'user' && $v['name'] != '') {
					@$topics[]->name = $v['name'];
				}
            }
        }

        return $topics;
    }
    function dynamo_support_upload_attachment($fileHandler, $postId)	{            require_once(ABSPATH . "wp-admin" . '/includes/image.php');            require_once(ABSPATH . "wp-admin" . '/includes/file.php');            require_once(ABSPATH . "wp-admin" . '/includes/media.php');            return media_handle_upload($fileHandler, $postId);	}
    /*
    * Process Submitted Form and create new topic
    */
    function dynamo_support_process_ticket() {
        global $support_options, $wpdb;
        $topic = $_POST['ticket-topic'];
        $title = esc_attr($_POST['ticket-title']);
        $content = esc_attr($_POST['ticket-content']);
        $author = $_POST['ticket-author'];				$book = $_POST['ticket-book'];				$book_details = esc_attr($_POST['ticket-book-detail']);
        if(!is_user_logged_in()) {
            $email = esc_attr($_POST['visitoremail']);
            $name = esc_attr($_POST['visitorname']);
            if($_POST['email-address'] != '' || $_POST['support-message'] != '') {
                wp_die('Whoops Spammer');
            }
        }
        if(!is_user_logged_in() && !is_email($email)) {
            $notice = 'Please enter a valid e-mail address';
        } else if (!is_user_logged_in() && strlen($name) < 3) {
                $notice = 'Please enter your full name';
            }else if ($author == '' && is_user_logged_in()) {
                    $notice = 'Sorry something went wrong with your question submission, please try again';
                } else if($topic == '') {
                        $notice = 'Please select a topic from the drop down list';
                    } else if($title == '' /*|| strlen($title) < 3*/) {
                            $notice = 'Please enter a title for your question';
                        } else if($content == '' /*|| strlen($content) < 10*/) {
                                $notice = 'Please enter a detailed description for your question';
                            }

                            if(is_array($support_options['input']) && $support_options['input'][1]['label'] != '') { 
            $content .= '<br/><br/>----------------------------<br/>';
            foreach($support_options['input'] as $k => $v) {
                if($v['label'] != '') {
                    if($v['required'] == 'true') {
                        $req = true;
                    } else {
                        $req = false;
                    }
                    $slug =  str_replace('-','_',sanitize_title($v['label']));	
                    if($req === true) {
                        if(strlen($_POST[$slug]) < 1) {
                            $notice = 'Please complete the '.$v['label'].' field and try again';
                        } else {
                            $content .= '<strong>'.ucfirst($v['label']).':</strong> '.esc_attr($_POST[$slug]).'<br/>';
                        }
                    } else {
                        $content .= '<strong>'.ucfirst($v['label']).':</strong> '.esc_attr($_POST[$slug]).'<br/>';
                    }
                }
            }
            $content .= '----------------------------';
        }				if (!empty($book)) {			$book_content .= '<br/><span style="font-size: 14px;"><strong>Book: </strong>'. $book . '</span><br/>';			$book_content .= '<span style="font-size: 14px;"><strong>Book Details: </strong>'. $book_details . '</span><br/>';			$book_content .= '-----------------------------------------<br/><br/>';						$content = $book_content . $content;		}
        if($_POST['bug-report'] === '1' || $support_options['capture-data'] == '1') {
            if($_POST['bug-report'] == '1') {
                if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $TheIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $TheIp = $_SERVER['REMOTE_ADDR'];
                }
                $TheIp = trim($TheIp);

                $os = dynamo_support_getOS($_SERVER['HTTP_USER_AGENT']);
                $browser = dynamo_support_getBrowser($_SERVER['HTTP_USER_AGENT']);
                $page = $_POST['pageurl'];
                $res = $_POST['browserwidth'].'x'.$_POST['browserheight'];
                $content .= '<br/><br/>----------------------------<br/><strong>Visitor IP:</strong> '.$TheIp.'<br/><strong>Visitor OS:</strong> '.$os.'<br/><strong>Visitor Browser:</strong> '.$browser.'<br/><strong>Resolution:</strong> '.$res.'<br/>----------------------------';
            }
            if($support_options['capture-data'] == '1') {

                $os = dynamo_support_getOS($_SERVER['HTTP_USER_AGENT']);

                $browser = dynamo_support_getBrowser($_SERVER['HTTP_USER_AGENT']);

                $page = $_POST['pageurl'];
                $content .= '<br/><br/>----------------------------<br/><strong>Visitor OS:</strong> '.$os.'<br/><strong>Visitor Browser:</strong> '.$browser.'<br/>----------------------------';

            }
        }



        if($notice != '') {
            return array('status' => false, 'notice' =>'<div class="support-error-notice">'.$notice.'</div>');
        } else {
            if(!is_user_logged_in()) {
                //Check for e-mail address and if is currently registered
                if ( preg_match('|[a-z0-9_.-]+@[a-z0-9_.-]+(?!.*<)|i', $email, $matches) )
                    $author = $matches[0];
                else
                    $author = trim($email);
                $author = sanitize_email($author);
                if ( is_email($author) ) {
                    $userdata = get_user_by_email($author);
                    if ( empty($userdata) ) {
                        $userdata = get_user_by_email($author);
                        if ( empty($userdata) ) {
                            $author_found = false;
                        } else {
                            $author = $userdata->ID;
                            $author_found = true;
                        }
                    } else {
                        $author = $userdata->ID;
                        $author_found = true;
                    }
                } else {
                    $author_found = false;
                }
                //If no user found by e-mail create the account
                if ( !$author_found ) {
                    $random_password = wp_generate_password( 12, false );

                    if($support_options['new_username'] == 'username') {
                        $user = $name;
                    } else if($support_options['new_username'] == 'email') {
                            $user = $email;
                        } else {
                            $user = $name;
                    }

                    $user_id = wp_create_user($name, $random_password, $email);
                    $userdata = array(
                    'ID' => $user_id,
                    'user_nicename' => $name,
                    'display_name' => $name,
                    'user_email' => $email
                    );
                    wp_update_user($userdata);
                    update_user_meta($user_id,'show_admin_bar_front','false');
                    $userdata = get_userdata($user_id);
                    $author = $userdata->ID;
                    dynamo_support_email_new_user_details($userdata, $random_password);
                }
            }


            $empty_data = array(
            'post_title' => 'dfisfjidsflsf',
            'post_content' => 'fjksdlfhslfjksldf',
            'post_author' => 1,
            'post_type' => 'ticket'
            );
            $post_id = wp_insert_post($empty_data);
            //Process Upload Attachment
            $fail = false;
            $isattach = false;
            if(is_user_logged_in() && $support_options['frontend_attachments'] == 1 && $_FILES['attachment']) {
                $filename = $_FILES['attachment']['name'];
                $file = $_FILES['attachment']['tmp_name'];
                $ext = strtolower(preg_replace("/.*\.(.{3,4})$/", "$1", $filename));
                //check allowed filetypes
                //$allowed_filetypes = array('jpg','gif','bmp','png','txt','doc','jpeg');
                $allowed_filetypes = $support_options['allowed_attachments'];
                if(!in_array($ext,$allowed_filetypes)) {
                    $fail = true;
                }
                //check max filesize
                if($_FILES['attachment']['size'] > $support_options['max_attachment_size']) {
                    $fail = true;
                }
                if($fail == false) {
                    //Passed all checks upload
					$attach_url = dynamo_support_upload_attachment('attachment', $post_id);					$isattach = true;
                }
            }
/*            if($isattach == true) {
                $content .= "\r\n".'Attachment: '. $attach_url."\r\n";
            }*/
            $title = '#'.$post_id .' - '. $title;

            $post = array(
            'ID' => $post_id,
            'comment_status' => 'open',
            'ping_status' => 'closed',
            'post_author' => $author,
            'post_content' => $content,
            'post_name' => sanitize_title($title),
            'post_status' => 'draft',
            'post_title' => $title,
            'post_type' => 'ticket'
            );
            wp_update_post($post);
            add_post_meta($post_id, 'ticket_topic', $topic, true);
            add_post_meta($post_id, 'ticket_status', '1', true);			            if($isattach == true) {				add_post_meta($post_id, 'ticket_attachment_count', '1', true);				add_post_meta($post_id, 'ticket_attachment_1', $attach_url, true);            }
            add_post_meta($post_id, 'no_ticket_assigned','none');
            if($_POST['bug-report'] === '1') {
                add_post_meta($post_id, 'ticket_bug', '1', true);
            }
            wp_publish_post($post_id);
            dynamo_support_update_user_count($author);

            return array('status' => true, 'notice' => '<div class="support-success-notice">Your question was submitted successfully. <a href="'.get_permalink($post_id).'" title="View your ticket">Click here to view your question &#187;</a></div>');
        }
    }

    function dynamo_support_email_new_user_details($userdata, $pass) {
        global $support_options, $dslog, $plugin_folder;
        $dslog->lwrite('--- START CREATED NEW USER ---');
        $subject = 'Account Created At - '.get_bloginfo('url').'';
        $to = $userdata->user_email;
        $to = str_replace('TEMP__','',$to);
        $headers .= 'From: '.$support_options['email-from-name'].' <'.$support_options['email-from'].'>' . "\r\n" .
        'Reply-To: '.$support_options['email-from'].'' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();

        $content ='In order to provide you with the best possible support, we have created an account for you at '.get_bloginfo('url').' so you can log-in and manage all of your support tickets. Your login details are below:<br/><br/><strong>Username:</strong>'.$userdata->user_login.'<br/><strong>Password:</strong>'.$pass.'<br/><strong>E-Mail:</strong>'.$userdata->user_email.'<br/><br/>';

        $x = @mail($to, htmlspecialchars_decode($subject), $content, $headers);
        if($x === true) {
            $dslog->lwrite('Successfully notified user of new account created to '.$to.' for user '.$userdata->user_nicename.'');
        } else {
            $dslog->lwrite('*** Failed to send new user notification email to '.$to.' ***');
        }
        $dslog->lwrite('--- END CREATED NEW USER ---');
    }

    function dynamo_support_email_to($topic) {
        global $support_options, $wp_roles, $plugin_directory;
        $notices = $support_options['email-notifications'];
        $admin = $support_options['email-admin'];
        if(is_array($notices) && !empty($notices)) {
            foreach($notices as $k => $v) {
                if($v['topic'] == $topic) {
                    $roles[] = $v['role'];
                }
            }
        }
        //Get users for role
        if(is_array($roles) && !empty($roles)) {
            $users = array();
            foreach($roles as $k => $r) {
                $users = array_merge($users,get_users( array( 'role' => $r ) ));
            }
        }
        if(is_array($users) && !empty($users)) {
            foreach($users as $k => $v) {
                $email[] = $v->user_email;
            }
        }
        $email[] = $admin;
        return $email;
    }

    /*
    * When New Ticker Is Created Shoot Notification E-Mail To Creator
    */
    add_action('publish_ticket','dynamo_support_new_ticket_notification');
    function dynamo_support_new_ticket_notification($post_id) {
        global $support_options, $plugin_folder, $plugin_directory, $dslog;
        $dslog->lwrite('--- START NEW TICKET PUBLISHED ---');
        //Check if is post revision
        if(!get_post_meta($post_id,'publish_email',true)) {
            $post = get_post($post_id);
            $author = $post->post_author;
            $author = get_userdata($author);
            if(substr($author->user_email,0,6) == 'TEMP__') {
                $temp = true;
            }
            $to = str_replace('TEMP__','',$author->user_email);
            $subject = '=?UTF-8?B?'.base64_encode('[NEW QUESTION] '.$post->post_title).'?=';

            $content = stripslashes($support_options['new-ticket-email']);

            $content = str_replace('%title%',$post->post_title, $content);
            $content = str_replace('%link%',get_permalink($post_id), $content);
            $content = str_replace('%days%',$support_options['close-tickets'], $content);
            $content = str_replace('%id%',$post_id,$content);
            $content = str_replace('%content%', '<br/><br/>-----------------------------------------<br/><br/>'.$post->post_content.'<br/><br/>-----------------------------------------<br/><br/>' ,$content);

            if($temp === true) {
                $content .= 'Please reply to this e-mail to continue with your support.';
            } else {
                $content .= '<a href="'.get_permalink($post_id).'" title="View Your Ticket">Click Here</a> to log in and view your ticket or copy and paste this URL to your browser: '.get_permalink($post_id).'';
            }
            $content = '##--Please reply above this line--##<br/><br/>'.$content;
            $headers .= 'From: '.$support_options['email-from-name'].' <'.$support_options['email-from'].'>' . "\r\n" .
            'Reply-To: '.$support_options['email-from'].'' . "\r\n";		
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();

            $x = mail($to, htmlspecialchars_decode($subject), $content, $headers);
            add_post_meta($post_id,'publish_email','1');

            if($x === true) {
                $dslog->lwrite('New ticket notification sent to '.$to.'');
            } else {
                $dslog->lwrite('*** Failed to send new ticket notification to '.$to.' ***');
            }


            if($support_options['email-admin-notice'] == '1') {
                $dslog->lwrite('Sending Admin + Role New Ticket Notifications');
                $content = 'You have a new ticket from <strong>'.$author->first_name.' '.$author->last_name.'</strong> ('.$author->display_name.') regarding: <strong>'.get_post_meta($post_id,'ticket_topic',true).'</strong><br/><br/>-------------Support Ticket--------------<br/><br/>'.$post->post_content.'<br/><br/>----------------------------------------------<br/><br/>';
                $to = dynamo_support_email_to(get_post_meta($post_id,'ticket_topic',true));
                foreach($to as $t) {
                    $x = mail(trim($t), htmlspecialchars_decode($subject), $content, $headers);
                    if($x === true) {
                        $dslog->lwrite('New ticket notification sent to '.$t.'');
                    } else {
                        $dslog->lwrite('***Failed to send new ticket notification to '.$t.' ***');
                    }
                }	
            }
        }
        $dslog->lwrite('--- END NEW TICKET PUBLISHED ---');
        return $post_id;
    }

    /*
    * Update Users Total Count on New Ticket Creation
    */
    function dynamo_support_update_user_count($user_id) {

        $count = get_user_meta($user_id,'ticket_count',true);
        if($count === '' || $count === 0) {
            $count = 1;
        } else {
            $count++;
        }
        update_user_meta($user_id,'ticket_count',$count);
        return $post_id;
    }

    /*
    * Get Visitor OS
    */
    function dynamo_support_getOS($userAgent) {
        // Create list of operating systems with operating system name as array key 
        $oses = array (
        'iPhone' => '(iPhone)',
        'Windows 3.11' => 'Win16',
        'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)', // Use regular expressions as value to identify operating system
        'Windows 98' => '(Windows 98)|(Win98)',
        'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
        'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
        'Windows 2003' => '(Windows NT 5.2)',
        'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
        'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
		'Windows 8' => '(Windows NT 6.3)|(Windows 8)',
        'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
        'Windows ME' => 'Windows ME',
        'Open BSD'=>'OpenBSD',
        'Sun OS'=>'SunOS',
        'Linux'=>'(Linux)|(X11)',
        'Safari' => '(Safari)',
        'Macintosh'=>'(Mac_PowerPC)|(Macintosh)',
        'QNX'=>'QNX',
        'BeOS'=>'BeOS',
        'OS/2'=>'OS/2',
        'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)'
        );

        foreach($oses as $os=>$pattern){ // Loop through $oses array
            // Use regular expressions to check operating system type
            if(preg_match("/$pattern/", $userAgent)) { // Check if a value in $oses array matches current user agent.
                return $os; // Operating system was matched so return $oses key
            }
        }
        return 'OS Unknown'; // Cannot find operating system so return Unknown
    }

    /*
    * Get Visitor Browser
    */
    function dynamo_support_getBrowser($userAgent) {
        // Create list of browsers with browser name as array key and user agent as value. 
        $browsers = array(
        'Opera' => 'Opera',
        'Mozilla Firefox'=> '(Firebird)|(Firefox)', // Use regular expressions as value to identify browser
        'Galeon' => 'Galeon',
        'Chrome'=>'Gecko',
        'MyIE'=>'MyIE',
        'Lynx' => 'Lynx',
        'Netscape' => '(Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79)',
        'Konqueror'=>'Konqueror',
        'SearchBot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)',
        'Internet Explorer 9' => '(MSIE 9\.[0-9]+)',
        'Internet Explorer 8' => '(MSIE 8\.[0-9]+)',
        'Internet Explorer 7' => '(MSIE 7\.[0-9]+)',
        'Internet Explorer 6' => '(MSIE 6\.[0-9]+)',
        'Internet Explorer 5' => '(MSIE 5\.[0-9]+)',
        'Internet Explorer 4' => '(MSIE 4\.[0-9]+)',
        'Internet Explorer' => 'MSIE',
        'Windows Mobile' => 'IEMobile',
        'Android Mobile' => 'Android',
        'iPhone Mobile' => 'iPhone'
        );

        foreach($browsers as $browser=>$pattern) { // Loop through $browsers array
            // Use regular expressions to check browser type
            if(!is_numeric($pattern)) {
                if(preg_match("@$pattern@", $userAgent)) { // Check if a value in $browsers array matches current user agent.
                    return $browser; // Browser was matched so return $browsers key
                }
            }
        }
        return 'Browser Unknown'; // Cannot find browser so return Unknown
    }

    /* New Post Manual ID Name */
    function sd_ticket_id($postID, $post) {
        global $wpdb;
        if($post->post_type == 'ticket') {
            $post->post_title = str_replace('#'.$postID.' - ','',$post->post_title);
            $title = '#'.$postID .' - '. $post->post_title;
            $name = sanitize_title($title);
            $wpdb->query("UPDATE $wpdb->posts SET post_title = '$title', post_name = '$name' WHERE ID = '$postID'");
        }
        return;
    }
    add_action('wp_insert_post','sd_ticket_id',10,2);


    /* Comment Notification Fix */
    if ( !function_exists('wp_notify_postauthor') ) {
        function wp_notify_postauthor( $comment_id, $comment_type = '' ) {
            $comment = get_comment( $comment_id );
            $post    = get_post( $comment->comment_post_ID );
            $author  = get_userdata( $post->post_author );

            if($post->post_type != 'ticket') {

                // The comment was left by the author
                if ( $comment->user_id == $post->post_author )
                    return false;

                // The author moderated a comment on his own post
                if ( $post->post_author == get_current_user_id() )
                    return false;

                // If there's no email to send the comment to
                if ( '' == $author->user_email )
                    return false;

                $comment_author_domain = @gethostbyaddr($comment->comment_author_IP);

                // The blogname option is escaped with esc_html on the way into the database in sanitize_option
                // we want to reverse this for the plain text arena of emails.
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

                if ( empty( $comment_type ) ) $comment_type = 'comment';

                if ('comment' == $comment_type) {
                    $notify_message  = sprintf( __( 'New comment on your post "%s"' ), $post->post_title ) . "\r\n";
                    /* translators: 1: comment author, 2: author IP, 3: author domain */
                    $notify_message .= sprintf( __('Author : %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
                    $notify_message .= sprintf( __('E-mail : %s'), $comment->comment_author_email ) . "\r\n";
                    $notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
                    $notify_message .= sprintf( __('Whois  : http://whois.arin.net/rest/ip/%s'), $comment->comment_author_IP ) . "\r\n";
                    $notify_message .= __('Comment: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
                    $notify_message .= __('You can see all comments on this post here: ') . "\r\n";
                    /* translators: 1: blog name, 2: post title */
                    $subject = sprintf( __('[%1$s] Comment: "%2$s"'), $blogname, $post->post_title );
                } elseif ('trackback' == $comment_type) {
                    $notify_message  = sprintf( __( 'New trackback on your post "%s"' ), $post->post_title ) . "\r\n";
                    /* translators: 1: website name, 2: author IP, 3: author domain */
                    $notify_message .= sprintf( __('Website: %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
                    $notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
                    $notify_message .= __('Excerpt: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
                    $notify_message .= __('You can see all trackbacks on this post here: ') . "\r\n";
                    /* translators: 1: blog name, 2: post title */
                    $subject = sprintf( __('[%1$s] Trackback: "%2$s"'), $blogname, $post->post_title );
                } elseif ('pingback' == $comment_type) {
                    $notify_message  = sprintf( __( 'New pingback on your post "%s"' ), $post->post_title ) . "\r\n";
                    /* translators: 1: comment author, 2: author IP, 3: author domain */
                    $notify_message .= sprintf( __('Website: %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
                    $notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
                    $notify_message .= __('Excerpt: ') . "\r\n" . sprintf('[...] %s [...]', $comment->comment_content ) . "\r\n\r\n";
                    $notify_message .= __('You can see all pingbacks on this post here: ') . "\r\n";
                    /* translators: 1: blog name, 2: post title */
                    $subject = sprintf( __('[%1$s] Pingback: "%2$s"'), $blogname, $post->post_title );
                }
                $notify_message .= get_permalink($comment->comment_post_ID) . "#comments\r\n\r\n";
                $notify_message .= sprintf( __('Permalink: %s'), get_permalink( $comment->comment_post_ID ) . '#comment-' . $comment_id ) . "\r\n";
                if ( EMPTY_TRASH_DAYS )
                    $notify_message .= sprintf( __('Trash it: %s'), admin_url("comment.php?action=trash&c=$comment_id") ) . "\r\n";
                else
                    $notify_message .= sprintf( __('Delete it: %s'), admin_url("comment.php?action=delete&c=$comment_id") ) . "\r\n";
                $notify_message .= sprintf( __('Spam it: %s'), admin_url("comment.php?action=spam&c=$comment_id") ) . "\r\n";

                $wp_email = 'wordpress@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));

                if ( '' == $comment->comment_author ) {
                    $from = "From: \"$blogname\" <$wp_email>";
                    if ( '' != $comment->comment_author_email )
                        $reply_to = "Reply-To: $comment->comment_author_email";
                } else {
                    $from = "From: \"$comment->comment_author\" <$wp_email>";
                    if ( '' != $comment->comment_author_email )
                        $reply_to = "Reply-To: \"$comment->comment_author_email\" <$comment->comment_author_email>";
                }

                $message_headers = "$from\n"
                . "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";

                if ( isset($reply_to) )
                    $message_headers .= $reply_to . "\n";

                $notify_message = apply_filters('comment_notification_text', $notify_message, $comment_id);
                $subject = apply_filters('comment_notification_subject', $subject, $comment_id);
                $message_headers = apply_filters('comment_notification_headers', $message_headers, $comment_id);

                @wp_mail( $author->user_email, $subject, $notify_message, $message_headers );

                return true;
            }
            return false;
        }
    }
    if ( !function_exists('wp_notify_moderator') ) {
        function wp_notify_moderator($comment_id) {
            global $wpdb;

            if ( 0 == get_option( 'moderation_notify' ) )
                return true;

            $comment = get_comment($comment_id);
            $post = get_post($comment->comment_post_ID);
            $user = get_userdata( $post->post_author );
            if($post->post_type != 'ticket') {
                // Send to the administration and to the post author if the author can modify the comment.
                $email_to = array( get_option('admin_email') );
                if ( user_can($user->ID, 'edit_comment', $comment_id) && !empty($user->user_email) && ( get_option('admin_email') != $user->user_email) )
                    $email_to[] = $user->user_email;

                $comment_author_domain = @gethostbyaddr($comment->comment_author_IP);
                $comments_waiting = $wpdb->get_var("SELECT count(comment_ID) FROM $wpdb->comments WHERE comment_approved = '0'");

                // The blogname option is escaped with esc_html on the way into the database in sanitize_option
                // we want to reverse this for the plain text arena of emails.
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

                switch ($comment->comment_type)
                {
                    case 'trackback':
                        $notify_message  = sprintf( __('A new trackback on the post "%s" is waiting for your approval'), $post->post_title ) . "\r\n";
                        $notify_message .= get_permalink($comment->comment_post_ID) . "\r\n\r\n";
                        $notify_message .= sprintf( __('Website : %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
                        $notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
                        $notify_message .= __('Trackback excerpt: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
                        break;
                    case 'pingback':
                        $notify_message  = sprintf( __('A new pingback on the post "%s" is waiting for your approval'), $post->post_title ) . "\r\n";
                        $notify_message .= get_permalink($comment->comment_post_ID) . "\r\n\r\n";
                        $notify_message .= sprintf( __('Website : %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
                        $notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
                        $notify_message .= __('Pingback excerpt: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
                        break;
                    default: //Comments
                        $notify_message  = sprintf( __('A new comment on the post "%s" is waiting for your approval'), $post->post_title ) . "\r\n";
                        $notify_message .= get_permalink($comment->comment_post_ID) . "\r\n\r\n";
                        $notify_message .= sprintf( __('Author : %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
                        $notify_message .= sprintf( __('E-mail : %s'), $comment->comment_author_email ) . "\r\n";
                        $notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
                        $notify_message .= sprintf( __('Whois  : http://whois.arin.net/rest/ip/%s'), $comment->comment_author_IP ) . "\r\n";
                        $notify_message .= __('Comment: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
                        break;
                }

                $notify_message .= sprintf( __('Approve it: %s'),  admin_url("comment.php?action=approve&c=$comment_id") ) . "\r\n";
                if ( EMPTY_TRASH_DAYS )
                    $notify_message .= sprintf( __('Trash it: %s'), admin_url("comment.php?action=trash&c=$comment_id") ) . "\r\n";
                else
                    $notify_message .= sprintf( __('Delete it: %s'), admin_url("comment.php?action=delete&c=$comment_id") ) . "\r\n";
                $notify_message .= sprintf( __('Spam it: %s'), admin_url("comment.php?action=spam&c=$comment_id") ) . "\r\n";

                $notify_message .= sprintf( _n('Currently %s comment is waiting for approval. Please visit the moderation panel:',
                'Currently %s comments are waiting for approval. Please visit the moderation panel:', $comments_waiting), number_format_i18n($comments_waiting) ) . "\r\n";
                $notify_message .= admin_url("edit-comments.php?comment_status=moderated") . "\r\n";

                $subject = sprintf( __('[%1$s] Please moderate: "%2$s"'), $blogname, $post->post_title );
                $message_headers = '';

                $notify_message = apply_filters('comment_moderation_text', $notify_message, $comment_id);
                $subject = apply_filters('comment_moderation_subject', $subject, $comment_id);
                $message_headers = apply_filters('comment_moderation_headers', $message_headers);

                foreach ( $email_to as $email )
                    @wp_mail($email, $subject, $notify_message, $message_headers);

                return true;
            }
            return false;
        }
    }
?>