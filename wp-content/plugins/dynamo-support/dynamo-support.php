<?php
    /*
    Plugin Name: Support Dynamo
    Plugin URI: http://supportdynamo.com
    Description: Turns WordPress into a fully fledged support system with the power of Support Dynamo with integrated Customer Interaction Manager.
    Author: Plugin Dynamo
    Version: 1.11.73
    Author URI: http://plugindynamo.com
    */

    $plugin_folder = WP_PLUGIN_URL .'/'. dirname(plugin_basename(__FILE__)).'/';
    $plugin_directory = WP_PLUGIN_DIR .'/'. dirname(plugin_basename(__FILE__)).'/';

    date_default_timezone_set(get_option('timezone_string'));


    /*
    * Default Options - Added on activation if not already set.
    */
    function dynamo_support_defaults($key = '') {
        global $wpdb;
        //Create Attachments Table
        $attachments_table = $wpdb->prefix.'sd_attachments';
        if($wpdb->get_var("SHOW TABLES LIKE '$attachments_table'") != $attachments_table) {
            $attachments_structure = "CREATE TABLE $attachments_table (
            id INT(9) NOT NULL AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            size VARCHAR(30) NOT NULL,
            randkey VARCHAR(10) NOT NULL,
            url LONGTEXT NOT NULL,
            extension VARCHAR(5) NOT NULL,
            user_id INT(10) NOT NULL,
            UNIQUE KEY id (id)
            );";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($attachments_structure);	
        }
        /*
        * All Support Dynamo options are in an array saved in the options table under dynamo_support_options
        */
        $support_options['close-tickets'] = '0';
        $support_options['delete-tickets'] = '0';
        $support_options['integration'] = '';
        $support_options['email-from-name'] = '';
        $support_options['email-from'] = get_option('admin_email');
        $support_options['email-admin-notice'] = '1';
        $support_options['email-admin'] = get_option('admin_email');
        $support_options['ticket-reply-email'] = "Good News, there has been an update to your ".get_bloginfo('name')." support ticket \"%title%\". \r\n
        %content%";
        $support_options['new-ticket-email'] = "This e-mail is to confirm that we have received your ticket \"%title%\" and we try to respond to all support requests within 24hrs.\r\n
        %content%";
        $support_options['feedback_email_text'] = '<p>Hello, this is a friendly reminder that there has been a reply to your support request. If you are happy with the response you can click the link below to close this support request. You must be logged in to view and close your ticket, so please <a href="'.get_bloginfo('wpurl').'/wp-login" title="Login">login</a> and then click one of the links below:</p>';
        $support_options['knowledgebase-welcome'] = "<p>Welcome,<br/>
        We offer support through 2 channels, the first is our Knowledge Base with excellent troubleshooting guides and support topics. We advise you to browse through the Knowledge Base for the quickest possible resolution to your problem.</p>
        <p>If you can't find a solution in our Knowledge Base please open a support ticket and our support staff will be more than happy to help you.</p>
        ";
        $support_options['feedback-text'] = '<p><strong>WAIT!</strong> We could really use your help.  We would love for you to leave us your comments on our products.  It will won\'t take long and will really help us know what you like and what we can do better.<br/>If you would like to leave a testimonial we would love that as well.</p>';

        $support_options['allowed_attachments'] = array('jpg','jpeg','bmp','png','gif','txt','doc','pdf');

        $support_options['topics'][1]['name'] = 'Affiliates';
        $support_options['topics'][1]['hide'] = '';
        $support_options['topics'][1]['source'] = 'user';

        $support_options['topics'][2]['name'] = 'Billing';
        $support_options['topics'][2]['hide'] = '';
        $support_options['topics'][2]['source'] = 'user';

        $support_options['topics'][3]['name'] = 'Other';
        $support_options['topics'][3]['hide'] = '';
        $support_options['topics'][3]['source'] = 'user';
        //Default Roles
        $support_options['roles']['billing-dept']['name'] = 'Billing Dept';
        $support_options['roles']['billing-dept']['capabilities']['access_tickets'] = true;
        $support_options['roles']['billing-dept']['capabilities']['access_tickets_2'] = true;

        $support_options['roles']['affiliate-dept']['name'] = 'Affiliate Dept';
        $support_options['roles']['affiliate-dept']['capabilities']['access_tickets'] = true;
        $support_options['roles']['affiliate-dept']['capabilities']['access_tickets_1'] = true;

        if($key != '') {
            $current_options = get_option('dynamo_support_options');
            $current_options[$key] = $support_options[$key];
            update_option('dynamo_support_options',$current_options);
        }

        add_option('dynamo_support_options',$support_options);

        /*
        * Create default Support page with short code already on at activation
        */
        //Create Support Page With Shortcode
        if(!get_page_by_title('Support','OBJECT','page')) {
            $args = array(
            'post_title' => 'Support',
            'post_content' => '[sd-support-page]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'comment_status' => 'closed',
            'ping_status' => 'closed'
            );
            $id = wp_insert_post($args);
        }
		
		// Flush rules after install
		flush_rewrite_rules();
    }
    register_activation_hook(__FILE__, 'dynamo_support_defaults');

    $support_options = get_option('dynamo_support_options');

    register_activation_hook(__FILE__, 'dynamo_support_create_default_role');
	
	
	
    /*
    * Activation Add Capabilities and Create Support Ticket Super Admin Role
    */
    function dynamo_support_create_default_role() {
        global $wp_roles;
        if($wp_roles->get_role('administrator')) {
            $admin = $wp_roles->get_role('administrator');
            //Tickets
            $admin->add_cap('access_tickets');
            $admin->add_cap('publish_tickets');
            $admin->add_cap('edit_tickets');
            $admin->add_cap('delete_tickets');
            $admin->add_cap('delete_others_tickets');
            $admin->add_cap('edit_others_tickets');
            $admin->add_cap('read_private_tickets');
            $admin->add_cap('read_tickets');
            $admin->add_cap('read_ticket');
            $admin->add_cap('edit_ticket');
            $admin->add_cap('delete_ticket');
            //Articles
            $admin->add_cap('access_knowledgebase');
            $admin->add_cap('publish_articles');
            $admin->add_cap('edit_articles');
            $admin->add_cap('delete_articles');
            $admin->add_cap('delete_others_articles');
            $admin->add_cap('edit_others_articles');
            $admin->add_cap('read_others_articles');
            $admin->add_cap('read_article');
            $admin->add_cap('edit_article');
            $admin->add_cap('delete_article');
            $admin->add_cap('read_private_articles');
            //Feedback
            $admin->add_cap('access_feedback');
            //Assignment
            $admin->add_cap('view_all_tickets');
        }

        //Add Support Admin Role which allows full access to KB and Tickets but no posts/pages plugin setting etc...
        if(!$wp_roles->get_role('support_admin')) {
            $super_ticket = $wp_roles->add_role('support_admin','Support Admin', array(
            'access_tickets' => true,
            'access_knowledgebase' => true,
            'publish_tickets' => true,
            'publish_articles' => true,
            'edit_tickets' => true,
            'delete_tickets' => true,
            'delete_others_tickets' => true,
            'edit_others_tickets' => true,
            'read_private_tickets' => true,
            'read_tickets' => true,
            'publish_articles' => true,
            'edit_articles' => true,
            'delete_articles' => true,
            'delete_others_articles' => true,
            'edit_others_articles' => true,
            'read_private_articles' => true,
            'read_articles' => true,
            'read' => true,
            'read_article' => true,
            'userstatistics' => true,
            'access_feedback' => true
            )
            );
        }
        //Add 2 Standard Roles Billing & Affiliate dept (user can delete if needed)
        if(!$wp_roles->get_role('billing-dept')) {
            $super_ticket = $wp_roles->add_role('billing-dept','Billing Dept', array(
            'access_tickets' => true,
            'publish_tickets' => true,
            'edit_tickets' => true,
            'delete_tickets' => true,
            'delete_others_tickets' => true,
            'edit_others_tickets' => true,
            'read_private_tickets' => true,
            'read_tickets' => true,
            'read' => true,
            'userstatistics' => true,
            'access_tickets_1' => true,
            )
            );
        }
        if(!$wp_roles->get_role('affiliate-dept')) {
            $super_ticket = $wp_roles->add_role('affiliate-dept','Affiliate Dept', array(
            'access_tickets' => true,
            'publish_tickets' => true,
            'edit_tickets' => true,
            'delete_tickets' => true,
            'delete_others_tickets' => true,
            'edit_others_tickets' => true,
            'read_private_tickets' => true,
            'read_tickets' => true,
            'read' => true,
            'userstatistics' => true,
            'access_tickets_0' => true,
            )
            );
        }
    }

    /*
    * Admin Styles
    */
    if(is_admin()) {
        add_action('admin_print_styles', 'dynamo_support_admin_styles');
    }
    function dynamo_support_admin_styles() {
        global $plugin_folder, $plugin_directory;
        $colUrl = $plugin_folder.'css/ticket-columns.css';
        $colFile = $plugin_directory.'css/ticket-columns.css';
        if( file_exists($colFile) ) {
            wp_register_style('ticketColumns',$colUrl);
            wp_enqueue_style('ticketColumns');
        }
        wp_enqueue_style('thickbox');
    }

    /*
    * Public Styles
    */
    add_action('wp_print_styles','dynamo_support_styles');
    function dynamo_support_styles() {
        global $plugin_folder, $plugin_directory;
        $pubUrl = $plugin_folder.'css/support.css';
        $pubFile = $plugin_directory.'css/support.css';
        if( file_exists($pubFile) ) {
            wp_register_style('dynamoSupport',$pubUrl);
            wp_enqueue_style('dynamoSupport');
        }
        wp_enqueue_style('thickbox');
    }

    /*
    * Get Plugin Version
    */
    function dynamo_support_get_plugin_version() {
        if ( ! function_exists( 'get_plugins' ) )
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        $plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
        $plugin_file = basename( ( __FILE__ ) );
        return $plugin_folder[$plugin_file]['Version'];
    }
    require_once('includes/dynamoupdates.class.php');
    $sd_updates = new dynamoUpdateCheckerSup(
    'Support Dynamo',
    'support-dynamo',
    plugin_basename(__FILE__),
    'support_dynamo_plugin_activated',
    'support_dynamo_user_email',
    basename(dirname(__FILE__))
    );
    /*
    * Add To Admin Bar
    */
    add_action('admin_bar_init', 'dynamo_support_admin_bar_init');
    function dynamo_support_admin_bar_init() {
        // Is the user sufficiently leveled, or has the bar been disabled?
        if (!current_user_can('access_tickets') || !is_admin_bar_showing() )
            return;

        // Add admin bar links
        add_action('admin_bar_menu', 'dynamo_support_admin_bar_links', 500);
    }

    /*
    * Adds the links to the admin bar.
    */
    function dynamo_support_admin_bar_links() {
        global $wp_admin_bar;
        if(current_user_can('access_tickets')) {
            $links['View Tickets'] = get_bloginfo('wpurl').'/wp-admin/edit.php?post_type=ticket';
            $links['New Ticket'] = get_bloginfo('wpurl').'/wp-admin/post-new.php?post_type=ticket';
        }
        if(current_user_can('access_knowledgebase')) {
            $links['View Knowledgebase'] = get_bloginfo('wpurl').'/wp-admin/edit.php?post_type=knowledgebase';
            $links['New Knowledgebase Article'] = get_bloginfo('wpurl').'/wp-admin/post-new.php?post_type=knowledgebase';
        }
        $menu_item = array(
        'title' => 'Support',
        'href' => get_bloginfo('wpurl').'/wp-admin/admin.php?page=support-dashboard',
        'id' => 'sd_links'
        );
        if(dynamo_support_manual_count('open') != '0' && dynamo_support_manual_count('open') != '') {
            //$menu_item['title'] .=' <span id="ab-ticket-count" class="update-count">'.dynamo_support_manual_count('open').'</span>';
            $menu_item['title'] .=' <span id="" class="update-count">'.dynamo_support_manual_count('open').'</span>';
        }
        $wp_admin_bar->add_menu( $menu_item );

        foreach($links as $label => $url) {
            $wp_admin_bar->add_menu( array(
            'title' => $label,
            'href' => $url,
            'parent' => 'sd_links',
			'id' => 'sd_links'
            ));
        }
    }

    //First Activation Check - send to account page on first activation
    function support_dynamo_first_activation() {
        wp_redirect(get_bloginfo('wpurl').'/wp-admin/admin.php?page=dynamo_support_&view=account');
    }
    if(!get_option('support_dynamo_first_activation')) {
        if($_GET['activate-multi'] == 'true' || $_GET['activate'] == 'true') {
            //First Activation Go To Plugin Page
            add_action('init','support_dynamo_first_activation');
        }
    }
    /*
    * Activation server ajax communication
    */
    add_action('wp_ajax_support_dynamo_communicate', 'support_dynamo_communicate');
    function support_dynamo_communicate($email) {
        global $support_options;
        $email = dyno_cleanit($_POST['email']);
        $errors = array();
        $ecount = 0;
        $domain = dyno_encode5t(get_bloginfo('url'));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://plugindynamo.com/fsjlhf546gfdg21cx/commSD.php?email='.dyno_encode5t($email).'&prod='.dyno_encode5t('support-dynamo').'&domain='.$domain.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        add_option('support_dynamo_first_activation','1');
        if($output == 'UserID') {
            $errors[$ecount] = 'Sorry Your E-Mail Address Did Not Match Any Entries In Our System, Please Confirm You Entered The E-Mail Address Associated With Your PluginDynamo.com Account And Try Again.'; 
            $ecount = $ecount+1;
            update_option('support_dynamo_act_errors',$errors);
            update_option('support_dynamo_user_membership','');
            update_option('support_dynamo_user_email',dyno_encode5t($email));
            update_option('support_dynamo_plugin_activated','deactivated');
            return false;
        } else {
            $membership = dyno_cleanit($output);

            if($membership != '') {
                $membership = explode('|',$membership);
                $type = $membership[1];
                $ver = $membership[2];
                $membership = $membership[0];
                $support_options['plugin_version'] = dyno_encode5t($ver);
                update_option('dynamo_support_options',$support_options);
                update_option('support_dynamo_plugin_version',dyno_encode5t($ver));
                if($membership =='Success') {

                } else {
                    if($type != 'Full') {
                        $errors[$ecount] = 'Your Membership Level Does Not Give You Access To This Product Or Has Expired';
                        $ecount = $ecount+1;
                        update_option('support_dynamo_act_errors',$errors);
                        update_option('support_dynamo_user_membership','');
                        update_option('support_dynamo_user_email',dyno_encode5t($email));
                        update_option('support_dynamo_plugin_activated','deactivated');
                        return false;
                    } 
                }
            } else { 
                $errors[$ecount] ='Your Membership Level Could Not Be Retrieved.';
                $ecount = $ecount+1;
                update_option('support_dynamo_act_errors',$errors);
                update_option('support_dynamo_user_membership','');
                update_option('support_dynamo_user_email',dyno_encode5t($email));
                update_option('support_dynamo_plugin_activated','deactivated');
                return false;
            }
            if($ecount == '0') {
                update_option('support_dynamo_act_errors','');
                update_option('support_dynamo_user_membership',dyno_encode5t($membership));
                update_option('support_dynamo_user_email',dyno_encode5t($email));
                update_option('support_dynamo_plugin_activated','activated');
                return true;
            } else {
                update_option('support_dynamo_act_errors',$errors);
                update_option('support_dynamo_user_membership','');
                update_option('support_dynamo_user_email',dyno_encode5t($email));
                update_option('support_dynamo_plugin_activated','deactivated');
                return false;
            }
        }
        die();
    }

    /*
    * String Cleaning Function
    */
    if(!function_exists('dyno_cleanit')) {
        function dyno_cleanit($str) {
            $str = @trim($str);
            if(get_magic_quotes_gpc()) {
                $str = stripslashes($str);
            }
            return $str;
        }
    }
    /*
    * Encoding Function
    */
    if (!function_exists('dyno_encode5t')) {
        function dyno_encode5t($str)
        {
            for($i=0; $i<5;$i++)
            {
                $str=strrev(base64_encode($str)); 
            }
            return $str;
        }
    }
    /*
    * Decoding Function
    */
    if (!function_exists('dyno_decode5t')) {
        function dyno_decode5t($str)
        {
            for($i=0; $i<5;$i++)
            {
                $str=base64_decode(strrev($str)); 
            }
            return $str;
        }
    }
    /*
    * Tickets By E-Mail Cron If Plugin Version is Pro.
    */
    function dynamo_support_retrieve_tickets_via_mail() {
        global $support_options;
        if ($support_options['plugin_version'] == '==AUWVUeZhFZzFWMaNVTWJVU') {
            $servers = $support_options['servers'];
            if(is_array($servers) && count($servers) > 0) {
                foreach($servers as $k => $s) {

                    $mail = dynamo_support_check_mail($s['server'], $s['port'], $s['user'], $s['pass'], $s['name'], $s['protocol'], $s['ssl']);

                }
            }
        }
    }
    /*
    * Add email check cron job if version is pro
    */
    global $support_options;
    register_activation_hook(__FILE__, 'ds_add_email_cron');
    if ($support_options['plugin_version'] == '==AUWVUeZhFZzFWMaNVTWJVU') {
        add_action('ds_check_email', 'dynamo_support_retrieve_tickets_via_mail');
    } else {
        ds_remove_email_cron();
    }
    function ds_add_email_cron() {
        wp_schedule_event(current_time('timestamp',1), 'fifteen_minute', 'ds_check_email');
    }
    register_deactivation_hook(__FILE__, 'ds_remove_email_cron');

    function ds_remove_email_cron() {
        wp_clear_scheduled_hook('ds_check_email');
    }

    /*
    * Ticket By Email Cron Schedule 15 minutes
    */
    function ds_add_email_cron_schedules( $param ) {

        $param['fifteen_minute'] = array(
        'interval' => 900, // seconds* 900/60 = 15 mins
        'display' => __( 'Every Fifteen Minutes' )
        );

        return $param;

    }
    add_filter( 'cron_schedules', 'ds_add_email_cron_schedules' );

    /*
    * Creates accounts with TEMP_eail@domain.com for unregistered email
    * If email registeres later, it updates the account and removes TEMP_
    */
    //Remove Temps On Registration If Email Tries To Register
    function dynamo_support_remove_temp($user_id) {
        global $wpdb;
        $table = $wpdb->prefix.'users';
        $userdata = get_userdata($user_id);
        $email = 'TEMP__'.$userdata->user_email;
        $sql = "SELECT id FROM $table WHERE user_email = '$email'";
        $id = $wpdb->get_var($sql);
        if($id && $id != 0 && $id != 1) {
            $count = get_user_meta($id,'ticket_count',true);
            //Swap Ticket Count Stats
            update_user_meta($user_id,'ticket_count',$count);
            //Swap all created tickets to new user_id
            wp_delete_user($id, $user_id);
        }
        return $user_id;

    }
    add_action('user_register','dynamo_support_remove_temp',0,1);



    /*
    * Block Access to domain /ticket/ and re-direct to home
    */
    function dynamo_support_block_ticket_access() {
        $url = trim(substr($_SERVER['REQUEST_URI'],0,-1)); 
        $url = explode('/',$url);
        if(end($url) == 'ticket') {
            wp_redirect(get_bloginfo('home'));
        }
    }
    add_action('init','dynamo_support_block_ticket_access');

    $dslog = new ds_logging();
    $dslog->lfile(WP_PLUGIN_DIR .'/dynamo-support/logfile.txt');

    /**
    * Logging class:
    * - contains lfile, lopen and lwrite methods
    * - lfile sets path and name of log file
    * - lwrite will write message to the log file
    * - first call of the lwrite will open log file implicitly
    * - message is written with the following format: hh:mm:ss (script name) message
    */
    class ds_logging {
        // define default log file
        private $log_file = 'dynamo_support/logfile.txt';
        // define file pointer
        private $fp = null;
        // set log file (path and name)
        public function lfile($path) {
            $this->log_file = $path;
        }
        // write message to the log file
        public function lwrite($message){
            // if file pointer doesn't exist, then open log file
            if (!$this->fp) $this->lopen();
            // define script name
            $script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
            // define current time
            $time = date('H:i:s');
            // write current time, script name and message to the log file
            fwrite($this->fp, "$time ($script_name) $message\n");
        }
        // open log file
        private function lopen(){
            // define log file path and name
            $lfile = $this->log_file;
            // define the current date (it will be appended to the log file name)
            $today = date('Y-m-d');
            // open log file for writing only; place the file pointer at the end of the file
            // if the file does not exist, attempt to create it
            $this->fp = fopen($lfile . '_' . $today, 'a') or exit("Can't open $lfile!");
        }
    }

    /*
    * Redirection for attachments domain.com/view-attachments?a=KEY
    * Each attachments URL is saved with a random key in the databse
    */
    function ds_attach_redirect() {
        global $wpdb, $current_user;
        $attach = $wpdb->prefix.'sd_attachments';
        //Look if accessed url is 1 level i.e. domain/xxxxx
        if(preg_match('#/([a-z0-9_-]+)($|\?)#i', $_SERVER['REQUEST_URI'], $matches)) {
            //Get top level dir slug
            $slug = trim($matches[1]);
            //Check if slug = view attachments and $_GET a is set.
            if($slug == 'view-attachments' && $_GET['a']) {
                $randkey = $_GET['a'];
                $sql = "SELECT url, user_id FROM $attach WHERE randkey = '$randkey'";
                //Search database for random key
                $link = $wpdb->get_row($sql);
                if($link) {
                    $target = $link->url;
                    $author = $link->user_id;
                    //Is Logged
                    if(is_user_logged_in()) {
                        //Only redirect to attachment if is Admin or Post Author(ticket owner)
                        if(current_user_can('manage_options') || $current_user->ID == $author) {
                            wp_redirect($target);
                            die();
                        } else {
                            wp_die('You do not have permission to access this file');
                        }
                    } else {
                        wp_die('You do not have permission to access this file');
                    }
                }
            }
        }
        return;	

    }
    add_action('init','ds_attach_redirect');



    /*
    *  Include Admin View Ticket Functions
    */
    require_once('includes/dynamo-support-view-ticket.php');

    /*
    * Include Knowledgebase Functions
    */
    require_once('includes/dynamo-support-knowledgebase.php');
    /*
    * Include Dashboard Stats Box
    */
    require_once('includes/dynamo-support-dashboard-stats-box.php');
    /*
    * Include Admin New/Edit Ticket Meta Box
    */
    require_once('includes/dynamo-support-admin-meta-box.php');

    /*
    * Include Front End Ticket List Display Functions
    */
    require_once('includes/dynamo-support-ticket-display.php');

    /*
    * Include Front End Create New Ticket Functions
    */
    require_once('includes/dynamo-support-new-ticket.php');

    /*
    * Include Media Library Functions
    */
    require_once('includes/dynamo-support-media-library.php');

    /*
    * Include View Single Ticket Page Functions
    */
    require_once('includes/dynamo-support-single-ticket.php');

    /*
    * Include Dynamo Support Settings Page Functions
    */
    require_once('includes/dynamo-support-settings-page.php');

    /*
    * Include Dynamo Support Cron Functions
    */
    require_once('includes/dynamo-support-cron.php');
    register_deactivation_hook(__FILE__, 'dynamo_support_deactivate');
    /*
    * Include Dynamo Support Auto Response Functions
    */
    require_once('includes/dynamo-support-auto-response.php');

    /*
    * Include Dynamo Support Roles & Caps Manager
    */
    require_once('includes/dynamo-support-roles-caps.php');
    register_deactivation_hook( __FILE__ ,'dynamo_support_roles_deactivate');

    /*
    * Include Dynamo Support - Support Dashboard
    */
    require_once('includes/dynamo-support-support-dashboard.php');

    /*
    * Include Feedback Form
    */
    require_once('includes/dynamo-support-feedback.php');
    /*
    * Include Dynamo Support Edit Comments
    */
    require_once('includes/dynamo-support-edit-comments.php');

    /*
    * Include Dynamo Support Mail Stuff
    */
    require_once('includes/dynamo-support-mail.php');
    //Dash Widget
    require_once('includes/dashboard-widget.php');

    /*
    * Include Other Plugin Integration Files
    */
    // Wish List Member
    require_once('integrations/wlm/dynamo-support-wlm.php');
    //MemberWing X
    require_once('integrations/mwx/dynamo-support-mwx.php');
    //Cart 66
    require_once('integrations/c66/dynamo-support-c66.php');

    //******************************
    /*
    * Add a sidebar widget that if the visitor is logged out asks them to login 
    * to see the support center and create/view tickets. Once logged in, 
    * it lists the current Open tickets for that user, and links them to 
    * the ticket page to view ticket details and access the Support Center.  
    * This widget should include the Ticket Name and total # Of Replies
    */
    //******************************
    require_once('includes/dynamo-ticket-widget.php');


    /*    
    *  Add Support menu bar in the top of the page
    * 
    */

  /*  add_action( 'admin_bar_menu', 'toolbar_link_to_mypage', 999 );

    function toolbar_link_to_mypage( $wp_admin_bar ) { 
        global $support_options;
        $user_id = get_current_user_id();
        if ( 0 != $user_id ) {
            if ( current_user_can( 'manage_options' ) ) {
                // A user with admin privileges 
            } else {
                // A user without admin privileges  
                $support_page_link = '#';
                $support_page = $support_options['support-page'];
                if(isset($support_page) && !empty($support_page)){
                    $support_page_link = get_permalink($support_page); 
                }

                $menu_item = array(
                'title' => 'Support',
                'href' => $support_page_link,
                'id' => 'sd_links'
                );
                if(dynamo_support_manual_count('open') != '0' && dynamo_support_manual_count('open') != '') {
                    //$menu_item['title'] .=' <span id="ab-ticket-count" class="update-count">'.dynamo_support_manual_count('open').'</span>';
                    $menu_item['title'] .=' <span id="" class="update-count">'.dynamo_support_manual_count('open').'</span>';
                }

                $wp_admin_bar->add_node( $menu_item );
            }
        }
    }   */
?>