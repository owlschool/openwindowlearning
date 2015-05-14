<?php
    /*
    * Dynamo Support Functions To Display Current Users Tickets In Table of Open, Closed, All
    */

    function sd_display_support_page() {
        $r .= sd_page_header();
        $r .= sd_page_content();
        return $r;
    }
    add_shortcode('sd-support-page','sd_display_support_page');

    /*
    * Support Page header - menu bar
    */

    function sd_page_header() {
        global $current_user, $post;
        get_currentuserinfo();

        $r .= '<menu id="ticket-bar">

        <ul>';
        if(is_user_logged_in()) {
            $r .='	<li class="current-user"><a href="'.get_permalink().'"><span class="gravatar">'.get_avatar($current_user->ID,'32').'</span><strong>Logged In As:</strong><br/><em>'.$current_user->display_name.'</em></a></li>';
        }	
        if(get_posts(array('post_type' => 'knowledgebase'))) {
            $r .='<li class="knowledgebase"><a href="#knowledgebase" title="Browse Knowledge Base">Browse<br/>Knowledge Base</a></li>';
        }

        $r .='<li><a href="#new-ticket" class="new-ticket" style="height: 28px; background: #ED6124; color: #FFFFFF !important; cursor: pointer; font: inherit; font-size: 14px; display: inline-block; padding: 4px 12px; text-decoration: none; border: none;" title="Create a New Question" rel="'.$post->ID.'">New Question</a></li>';
        if(is_user_logged_in()) {	
            //$open = dynamo_get_user_tickets('open');
            //$closed = dynamo_get_user_tickets('closed');
            //$open = count($open);
            //$closed = count($closed);
            $r .='<li><a href="#open-tickets" class="open-ticket" style="display:none; height: 28px; margin-top: 10px; padding: 4px 12px; font-size: 14px; font-weight: normal; line-height: 28px;" title="View Your Open Questions">All Questions</a></li>';
            //<li class="closed-ticket"><a href="#closed-tickets" style="height: 28px; margin-top: 10px; padding: 4px 12px; font-size: 14px; font-weight: normal; line-height: 28px;" title="View Your Resolved Questions">Resolved Questions</a></li>';
        }
        $r .='</ul>

        </menu><input type="hidden" id="ajax-url" value="'.get_bloginfo('wpurl').'"/>';
        return $r;
    }
    /*
    * Support Page Content
    */
    function sd_page_content() {
        global $current_user, $support_options, $post;
        get_currentuserinfo();

        $r .='<div id="support-message">
        '.stripslashes($support_options['knowledgebase-welcome']).'
        </div>';
        if(get_posts(array('post_type' => 'knowledgebase'))) {
            $r .='<form id="search-knowledgebase">
            <input type="text" name="search" id="search" placeholder="Search entire Knowledge Base..."/>
            <input type="button" id="search-submit" value="Search"/>
            <input type="hidden" name="kb-active" id="kb-active" value="1"/>
            </form>';
        }
        $r .= '<div id="sd-recent-articles"'; 
        if(!get_posts(array('post_type' => 'knowledgebase')) && !$_POST['ticket-submit']) {
            //$r .=' class="hidden"';
        }
        $r .='>';
        if($_POST['ticket-submit']) {
            $r .= '<script type="text/javascript">
            /* <![CDATA[ */
            var $_POST = '.json_encode($_POST).'
            /* ]]> */
            </script>';
            $r .= dynamo_support_new_ticket('1');
        } 
        $r .='</div>';
        return $r;
    }
    /*
    * Pass post values on ticket submit to sava
    */
    function sd_header_js() {
        if(!$_POST['ticket-submit']) {
            echo '<script type="text/javascript">
            /* <![CDATA[ */
            var $_POST = false;
            /* ]]> */
            </script>';
        }
    }
    add_action('wp_head','sd_header_js');

    /*
    * Page content, if no KB show open tickets
    */
    function sd_page_ajax_content() {
        if(get_posts(array('post_type' => 'knowledgebase')) ) {
            $r .= sd_knowledgebase('1');
        } else {
            $r .= dynamo_support_all_tickets();	
        }
        echo $r;
        die();
    }

    add_action('wp_ajax_sd_page_content', 'sd_page_ajax_content');
    add_action('wp_ajax_nopriv_sd_page_content', 'sd_page_ajax_content');
    function sd_browse_knowledgebase() {
        global $post;
        $tmp_post = $post;
        $terms = get_terms('knowledge-base-topic', array('hide_empty' => 1, 'orderby' => 'name'));
        if(count($terms > 0)) {
            $x = 0;
            foreach($terms as $term) {
				$x++;
                if($x == '2') {
                    $style = "float:right; clear:right;";
                    $x = 0;
                } else {
                    $style = "float:left; clear:left;";
                }
                $r .='<ul class="topics" style="'.$style.'"><li><h3>'.$term->name.'</h3></li>';
                $args = array(
                'numberposts' => 5,
                'post_type' => 'knowledgebase',
                'tax_query' => array (
                array(
                'taxonomy' => 'knowledge-base-topic',
                'field' => 'id',
                'terms' => $term->term_id
                )
                )
                );
                $posts = get_posts($args);
                foreach($posts as $post) : setup_postdata($post);
                    $r .= '<li><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></li>';
                    endforeach;
                $post = $tmp_post;

                $r .= '<li><a href="'.get_bloginfo('home').'/knowledge-base-topic/'.$term->slug.'" title="View All '.$term->name.' Posts"><strong>View All '.$term->name.' Posts &#187</strong></a></li></ul>';
            }
        }
        $r .= '<div class="clear"></div>';
        echo $r;
        die();
    }
    add_action('wp_ajax_dynamo_support_browse_knowledgebase', 'sd_browse_knowledgebase');
    add_action('wp_ajax_nopriv_dynamo_support_browse_knowledgebase', 'sd_browse_knowledgebase');
    /*
    * Knowledge Base Display Recent Articles
    */
    function sd_knowledgebase($ajax) {
        global $post;
        $args = array (
        'numberposts' => 5,
        'post_type' => 'knowledgebase',
        'post_status' => 'publish',
        'orderby' => 'meta_key',
        'meta_key' => 'kb_features',
        'order' => 'ASC'
        );
        $tmp_post = $post;
        $posts = get_posts($args);
        if(is_array($posts)) { 
            foreach($posts as $k => $v) {
                $exclude[] = $v->ID;
            }
        }	
        if(count($posts) < 5) {
            $remaining = 5-count($posts);
            $args = array (
            'numberposts' => $remaining,
            'post_type' => 'knowledgebase',
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'post__not_in' => $exclude
            );
            $posts2 = get_posts($args);
            $posts = array_merge($posts, $posts2);
        }
        $r .= '<h2>Recent Knowledge Base Articles</h2>';
        foreach($posts as $post) {
            setup_postdata($post);
            $r .='<div class="article">
            <h3 class="knowledge-title">'.get_the_title().'</h3>
            <span class="knowledge-meta">Added By: <em>'.get_the_author().'</em> | On: <em>'.get_the_date().'</em> | Topic: <em>'; 
            $tax = wp_get_post_terms($post->ID,'knowledge-base-topic');
            foreach($tax as $t) { 
                $r .=''.$t->name.', '; 
            } 
            $r .='</em></span>
            <div class="article-content">
            '.get_the_excerpt().'
            <br/><br/>
            <a href="'.get_permalink().'" title="Continue Reading">Continue Reading &#0187;</a>
            </div>
            </div>';
        }
        $post = $tmp_post;
        if($ajax == '1') {
            return $r;
        } else {
            echo $r;
            die();
        }
    }
    add_action('wp_ajax_dynamo_support_knowledgebase', 'sd_knowledgebase');
    add_action('wp_ajax_nopriv_dynamo_support_knowledgebase', 'sd_knowledgebase');

    /*
    * Search Knowledgebase
    */
    function sd_knowledgebase_search() {
        global $wpdb, $support_options;
        $s = $_POST['search'];
        $s = htmlspecialchars(stripslashes($s));
        $s = str_ireplace("script", "blocked", $s);
        $s = mysql_escape_string($s);

        if($s == '') {
            $r .= '<h2>Please Enter A Search Term</h2><p>Please enter a search term to receive results from the knowledgebase.</p>';
            echo $r;
            die();
        }

        //Save Recent Search
        $array = $support_options['kb_searches'];
        $array[] = $s;
        $array = array_reverse($array);
        $array = array_slice($array,0,30);
        $array = array_reverse($array);
        $support_options['kb_searches'] = $array;
        update_option('dynamo_support_options',$support_options);


        $table = $wpdb->prefix.'posts';
        $query = "SELECT DISTINCT id, post_author FROM $table WHERE ( post_content LIKE '%$s%' OR post_title LIKE '%$s%' ) AND post_status = 'publish' AND post_parent = '0' AND post_type = 'knowledgebase' LIMIT 0,20";
        $results = $wpdb->get_results($query);
        if(is_array($results) && !empty($results)) {
            $r .='<h2>Search Results For "'.$s.'":</h2><p><small>*Limited to the top 20 most relevant results</small></p>';
            foreach($results as $re) {
                setup_postdata($re);
                $p = get_post($re->id);
                $r .='<div class="article"><h3 class="knowledge-title">'.$p->post_title.'</h3><span class="knowledge-meta">Added By: <em>'.get_the_author_meta('display_name',$re->post_author).'</em> | On: <em>'.get_the_time('F n, Y',$re->id).'</em> | Topic: <em>'; 
                $tax = wp_get_post_terms($re->id,'topic');
                foreach($tax as $t) { 
                    $r .=''.$t->name.', '; 
                } 
                $r .='</em></span>
                <div class="article-content">
                <a href="'.get_permalink($re->id).'" title="Continue Reading">Continue Reading &#0187;</a>
                </div>
                </div>';
            }
        } else {
            $r .='<h2>Search Results For "'.$s.'":</h2><p>Sorry no knowledgebase articles were found when searching for "'.$s.'", please re-phrase your query or open a support ticket.</p>';
        }
        echo $r;
        die();
    }
    add_action('wp_ajax_dynamo_support_knowledgebase_search','sd_knowledgebase_search');
    add_action('wp_ajax_nopriv_dynamo_support_knowledgebase_search','sd_knowledgebase_search');
    /*
    * New Ticket Form
    */
    function dynamo_support_new_ticket($noajax = '') {
        global $current_user, $support_options;
        get_currentuserinfo();
        $postid = $_REQUEST['post'];
        $bugreport = $_REQUEST['bug-report'];
        if($bugreport === '1') {
            $r .='<h2 class="new-ticket-head">Submit Page Error Report</h2>';
        } else {
            $r .='<h2 class="new-ticket-head">Submit A New Question</h2>';
        }
        if(is_user_logged_in() || $support_options['open-support'] == '1') {
            if($_POST['ticket-submit']) {
                $notice = dynamo_support_process_ticket();
                echo $notice['notice'];
                if($_REQUEST['submitted'] == '1' && $notice['status'] == false) {
                    $_POST = $_REQUEST['postdata'];
                } else {
                    unset($_REQUEST, $_POST);
                }
            }	
            $r .='<div id="create_new_ticket">';
            if(is_user_logged_in() && $bugreport != '1') {
                $r .='';
            } else if($bugreport === '1') {
                    $r .='<p>If you have found an error on this page, please use this form to bring it to our attention.</p>';
                } else {
                    $r .='<p>Hi stuck on a question? Fill out the form below and our tutors will get back to you</p>';
            }

            $r .='	<form id="new-ticket-form" method="post" action="'.get_permalink($postid).'#new-ticket" enctype="multipart/form-data">';

            if(!is_user_logged_in()) {
                $r .= '<label for="name"><b style="color:#cc0000;">*</b> <strong>Full Name:</strong></label><br/>
                <input type="text" name="visitorname" id="name" placeholder="Enter your full name" value="'.esc_attr($_POST['visitorname']).'"/>
                <br/><br/>

                <label for="email"><b style="color:#cc0000;">*</b> <strong>E-mail Address:</strong></label><br/>
                <input type="text" name="visitoremail" id="email" placeholder="Enter your e-mail address" value="'.esc_attr($_POST['visitoremail']).'"/>
                <br/><br/>
                <div style="display:none;">
                <input type="text" name="email-address" placeholder="enter your e-mail address" value="'.esc_attr($_POST['email-address']).'"/>
                <textarea name="support-message">'.esc_attr($_POST['support-message']).'</textarea>
                </div>
                ';
            }
            $r .= '	<label for="ticket-topic"><b style="color:#cc0000;">*</b> <strong style="font-size: 14px;">Subject:</strong></label><br/>';
            if($bugreport === '1') {
                $r .='<input type="hidden" name="ticket-topic" value="Bug Report"/><input type="text" style="font-weight:bold; color:#000;" disabled="disabled" value="Bug Report"/>';
            } else {
                $r .='<select id="ticket-topic" name="ticket-topic" style="font-size: 14px; padding: 10px 15px;">
                <option value="">--- Please Select A Topic ---</option>';

                $topics = dynamo_support_get_topics(); 
                if(count($topics)){
                    foreach($topics as $t) {

                        $r .= '<option value="'.$t->name.'"';
                        if($_POST['ticket-topic'] === $t->name) { $r .= ' selected="selected"'; }
                        $r .= '>'.$t->name.'</option>';
                    } 
                }

                $r .='</select>';
            }	
            $r .='<br/><br/>';

            if(is_array($support_options['input'])) { 
                foreach($support_options['input'] as $k => $v) {
                    if($v['label'] != '') {
                        if($v['required'] == 'true') {
                            $req = '<b style="color:#cc0000;">*</b>';
                        } else {
                            $req = '';
                        }
                        $slug =  str_replace('-','_',sanitize_title($v['label']));
                        $r .='<label for="ticket-topic">'.$req.' <strong>'.ucfirst($v['label']).':</strong></label><br/>
                        <input type="text" name="'.$slug.'" id="form_'.$slug.'" value="'.esc_attr($_POST[$slug]).'" style="width:90%;"/><br/><br/>';
                    }
                }
            }
			$r .= '<table style="width: 100%; border-collapse:collapse; border: 0px;"><tr>			    <td style="border: 0px;"><label for="ticket-book"><strong style="font-size: 14px;">Is this question from a book?</strong></label></td>								</tr><tr>';			$r .= '<td style="width: 50%; border: 0px;"><select id="ticket-book" name="ticket-book" style="font-size: 14px; padding: 10px 15px;">                       <option value="">--- Please Select A Book ---</option>					    <optgroup label="GED Books">							<option value="Kaplan GED 2014">Kaplan New GED Test Strategies, Practice, and Review with 2 Practice Tests</option>							<option value="Kaplan GED 2015">Kaplan GED Test 2015 Strategies, Practice, and Review with 2 Practice Tests</option>							<option value="Steck-Vaughn GED">Steck-Vaughn GED: Complete Preparation 2014</option>							<option value="McGraw-Hill GED">McGraw-Hill Education Preparation for the GED Test</option>							<option value="REA GED">GED Test, REAs Total Solution For the New 2014 GED Test </option>							<option value="Barrons GED">Barrons How to Prepare for the GED Test</option>							<option value="Princeton Review GED 2015">Princeton Review Cracking the GED Test with 2 Practice Tests, 2015 Edition</option>						</optgroup>						<optgroup label="TASC Books">							<option value="Kaplan TASC 2014">Kaplan New TASC Strategies, Practice, and Review 2014</option>							<option value="Kaplan TASC 2015">Kaplan New TASC Strategies, Practice, and Review 2015</option>							<option value="McGraw-Hill TASC 2014">McGraw-Hill Education TASC: The Official Guide to the Test</option>							<option value="McGraw-Hill TASC 2015">McGraw-Hill Education Preparation for the TASC Test 2nd Edition: The Official Guide to the Test</option>						</optgroup>						<optgroup label="HiSET Books">							<option value="HiSET Secrets">HiSET Secrets Study Guide</option>						</optgroup>						</select></td>';			$r	.= '	</tr><tr><td style="border: 0px;"><label for="ticket-book-detail"><strong style="font-size: 14px;">Page/Question of the book</strong></label></td></tr><tr><td style="border: 0px;"><input id="ticket-book-detail" name="ticket-book-detail" type="text" style="font-size: 14px; padding: 10px 15px; width: 70%;" value="'.esc_attr($_POST['ticket-book-detail']).'"/></td></tr></table>';
            $r .='
            <label for="ticket-title"><b style="color:#cc0000;">*</b> <strong style="font-size: 14px;">Title:</strong></label><br/> 
            <input id="ticket-title" name="ticket-title" type="text" style="font-size: 14px; padding: 10px 15px;" value="'.esc_attr($_POST['ticket-title']).'"/>
            <br/><br/>

            <label for="ticket-content"><b style="color:#cc0000;">*</b> <strong style="font-size: 14px;">Details:</strong></label><br/>
            <textarea id="ticket-content" name="ticket-content" style="font-size: 14px; padding: 10px 15px;" rows="10">'.esc_attr($_POST['ticket-content']).'</textarea>
            <br/><br/>
            <input type="hidden" name="ticket-author" value="'.$current_user->id.'"/>';
            if($bugreport === '1') {
                $r .='<input type="submit" name="ticket-submit" id="submit-bug" value="Submit &#187;"/><br/>';
                $r .= '<input type="hidden" name="pageurl" id="page-url" value="'.get_permalink($postid).'"/>';
            } else {
                if(is_user_logged_in() && $support_options['frontend_attachments'] == 1) {
                    $r .= '<label><strong style="font-size: 14px;">Attachment</strong></label><br/><input type="file" id="attachment" value="" name="attachment" style="font-size: 14px;" /><br/><br/>';
                }
                $r .='<input type="submit" name="ticket-submit" style="font-size: 14px; color: white; background-color:#ed6124; padding: 5px 10px; border: 0px;" value="Submit &#187;"/><br/>';
            }
            if($support_options['capture-data'] == '1') {
                $r .= '
                <input type="hidden" id="browserheight" name="browserheight" value=""/>
                <input type="hidden" id="browserwidth" name="browserwidth" value=""/>';
            }
            $r .='<small style="font-size:75%;">All fields marked with <b style="color:#cc0000;">*</b> are required.</small>
            </form>
            </div>';
        } else {
            if($bugreport === '1') {
                $r .= '<p>Sorry you need to be logged in to report an error</p>';
            } else {
                $r .= '<p>Sorry you need to be logged in to submit a new ticket</p>';
                $args = array(
                'echo' => false,
                'redirect' => get_permalink($support_options['support-page']), 
                'form_id' => 'loginform',
                'label_username' => __( 'Username' ),
                'label_password' => __( 'Password' ),
                'label_remember' => __( 'Remember Me' ),
                'label_log_in' => __( 'Log In' ),
                'id_username' => 'user_login',
                'id_password' => 'user_pass',
                'id_remember' => 'rememberme',
                'id_submit' => 'wp-submit',
                'remember' => true,
                'value_username' => NULL,
                'value_remember' => false );
                $r .= wp_login_form( $args );
            }
        }

        if($noajax == '') {
            echo $r;
            die();
        } else {
            return $r;
        }
    }
    add_action('wp_ajax_dynamo_support_new_ticket', 'dynamo_support_new_ticket');
    add_action('wp_ajax_nopriv_dynamo_support_new_ticket', 'dynamo_support_new_ticket');

    /*
    * View Open Tickets
    */
    function dynamo_support_open_tickets() {
        dynamo_support_display_tickets('open');
        die();
    }
    add_action('wp_ajax_dynamo_support_open_tickets','dynamo_support_open_tickets');
    add_action('wp_ajax_nopriv_dynamo_support_open_tickets','dynamo_support_open_tickets');
    /*
    * View Closed Tickets
    */
    function dynamo_support_closed_tickets() {
        dynamo_support_display_tickets('closed');
        die();
    }

    function dynamo_support_all_tickets() {
        dynamo_support_display_tickets('all');
        die();
    }
    
    add_action('wp_ajax_dynamo_support_closed_tickets','dynamo_support_closed_tickets');
    add_action('wp_ajax_nopriv_dynamo_support_closed_tickets','dynamo_support_closed_tickets');

    add_action('wp_ajax_dynamo_support_all_tickets','dynamo_support_all_tickets');
    add_action('wp_ajax_nopriv_dynamo_support_all_tickets','dynamo_support_all_tickets');

    /*
    * Display a list of users tickets to the user
    */
    function dynamo_support_display_tickets($view) {
        global $support_options;
        if(is_user_logged_in()) {		
            echo '<div id="support-tickets">
            <h2 id="ticket-view">'.ucfirst($view).' Questions</h2>
            <table id="user_tickets">
            <thead>
            <tr>
            <th class="col-date">Date</th>
            <th class="col-topic">Topic</th>
            <th class="col-title">Title</th>
            <th class="col-replys">Replies</th>
            <th class="col-status">Status</th>
            </tr>
            </thead>
            <tbody id="ticket-table-content">';
            $tickets = dynamo_get_user_tickets($view);
            dynamo_support_display_ticket($tickets, $view);
            echo '</tbody>
            <tfoot>
            <tr>
            <th class="col-date">Date</th>
            <th class="col-topic">Topic</th>
            <th class="col-title">Title</th>
            <th class="col-replys">Replies</th>
            <th class="col-status">Status</th>
            </tr>
            </tfoot>
            </table></div>';

        } else if($support_options['open-support'] == '1') {
                $page = get_page_by_path('support');
                if ($page) {
                    $link = get_permalink($page->ID);
                }

                echo '<p>You need to login to check your current tickets or select New Ticket to open a new support ticket.</p><h2 class="new-ticket-head">Log In To Check Your Current Tickets</h2>
            <form id="ticket-login-form" action="'.get_bloginfo('wpurl').'/wp-login.php" method="post">

            <label for="log"><strong>User:</strong></label><br/><input type="text" name="log" id="log" value="'. wp_specialchars(stripslashes($user_login), 1) .'" size="20" /> <br/><br/>

            <label for="pwd"><strong>Password:</strong></label><br/><input type="password" name="pwd" id="pwd" size="20" /><br/><br/>

            <p><input type="submit" name="submit" value="Login &#187;" class="button" /></p>

            <p>
            <input type="hidden" name="redirect_to" value="'. $link .'" />
            </p>
            </form>';
        } else {
            echo '<p>Sorry but you need to be logged in to see your support tickets</p>';
            $args = array(
            'echo' => true,
            'redirect' => get_permalink($support_options['support-page']), 
            'form_id' => 'loginform',
            'label_username' => __( 'Username' ),
            'label_password' => __( 'Password' ),
            'label_remember' => __( 'Remember Me' ),
            'label_log_in' => __( 'Log In' ),
            'id_username' => 'user_login',
            'id_password' => 'user_pass',
            'id_remember' => 'rememberme',
            'id_submit' => 'wp-submit',
            'remember' => true,
            'value_username' => NULL,
            'value_remember' => false );
            wp_login_form( $args );
        }
    }

    /*
    * Display Tickets
    */
    function dynamo_support_display_ticket($tickets, $view = '') {
        if($view === '' || $view === 'open') {
            $e = 'You currently have no open questions';
        } else if ($view === 'closed') {
            $e = 'You currently have no resolved questions';
        } else {
            $e = 'You currently have no questions';
        }
        if(!empty($tickets) && is_array($tickets)) {
            foreach($tickets as $t) {
                if($t->meta_value == '0') {
                    $status = 'Closed';
                } else if($t->meta_value == '1') {
                        $status = 'Open';
                    } else if ($t->meta_value == '2') {
                            $status = 'Answered';
                        } else {
                            $stat = get_post_meta($t->id,'ticket_status',true); if($stat == 0) { $status = 'Closed'; } else if($stat == 1) { $status = 'Open'; } else if($stat == 2) { $status = 'Answered'; }
                }
                echo '<tr id="ticket-'.$t->id.'" class="ticket-row">
                <td class="col-date">'.date('m/d/Y',strtotime($t->post_date)).'</td>
                <td class="col-topic">'; echo '<b>'.get_post_meta($t->id,'ticket_topic',true).'</b>';
                echo'<td class="col-title"><a href="'.get_permalink($t->id).'" title="'.$t->post_title.'">'.$t->post_title.'</a></td>
                <td class="col-replys">'.$t->comment_count.'</td>
                <td class="col-status">'.$status.'</td>
                </tr>';
                unset($status); unset($stat);
            }
        } else {
            echo '<tr><td colspan="5">'.$e.'</td></tr>';
        }
    }

    /*
    * Retreive Select Tickets Call Back
    */
    function dynamo_get_user_tickets($view = 'open') {
        global $wpdb, $current_user;
        get_currentuserinfo();
        if($view == 'open') {
            $status = " AND meta.meta_key = 'ticket_status'
            AND (meta.meta_value = '1' OR meta.meta_value = '2')";
        } else if ($view == 'closed') {
                $status = " AND meta.meta_key = 'ticket_status'
                AND meta.meta_value = '0'";
        } else {
                $status = '';
        }

        $query = "SELECT 
        posts.id,
        posts.post_title,
        posts.post_date,
        posts.comment_count"; 
        if($view != 'all') {
            $query .=", meta.meta_value";
        }
        $query .=" FROM 
        ".$wpdb->prefix."posts AS posts";
        if($view != 'all') {
            $query .=" LEFT JOIN 
            ".$wpdb->prefix."postmeta AS meta 
            ON posts.id = meta.post_id";
        }
        $query .=" WHERE 
        posts.post_author = '".$current_user->ID."' 
        AND posts.post_status = 'publish'
        AND posts.post_type = 'ticket'
        AND posts.post_parent = '0'";

        $query .= $status;

        $query.=" ORDER BY posts.post_date DESC";

        $tickets = $wpdb->get_results($query);
        return $tickets;
    }

    function dynamo_support_ajax_tickets() {
        if(!empty($_POST['view'])) {
            $view = $_POST['view'];
            $tickets = dynamo_get_user_tickets($view);
            dynamo_support_display_ticket($tickets, $view);
        }
        die();
    }
    add_action('wp_ajax_dynamo_support_ajax_tickets', 'dynamo_support_ajax_tickets');
    add_action('wp_ajax_nopriv_dynamo_support_ajax_tickets', 'dynamo_support_ajax_tickets');

    function dynamo_support_curPageURL() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

?>