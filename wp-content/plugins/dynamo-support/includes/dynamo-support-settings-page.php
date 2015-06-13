<?php
    /*
    * Dynamo Support Settings Page
    */

    //Add Page To Menu
    add_action('admin_menu','dynamo_support_admin_menu');
    function dynamo_support_admin_menu() {
        global $plugin_folder, $support_options;
        remove_menu_page('edit.php?post_type=knowledgebase');
        remove_submenu_page('edit.php?post_type=ticket', 'post-new.php?post_type=ticket');
        remove_submenu_page('edit.php?post_type=ticket','edit.php?post_type=ticket');
        remove_menu_page('edit.php?post_type=ticket');
        if(dynamo_support_manual_count('open') != '' && dynamo_support_manual_count('open') != 0) {
            $count = '<span class="update-plugins"><span class="ticket-count">' . number_format_i18n(dynamo_support_manual_count('open')) . '</span></span>';
        }

        $dash = add_menu_page('Support Dynamo',sprintf( __('Support %s'), $count ),'access_tickets','support','support_dash', $plugin_folder.'img/support-icon.png',35);
        $dboard = add_submenu_page('support','Dashboard', 'Dashboard','manage_options','support-dashboard','support_dash');
        if(get_option('support_dynamo_plugin_activated') == 'activated') {
            $t = add_submenu_page('support','View Tickets', 'Tickets','access_tickets','edit.php?post_type=ticket','');
            $t_new = add_submenu_page('support', 'Add New Ticket', '&nbsp;&nbsp;&nbsp;Add New', 'access_tickets', 'post-new.php?post_type=ticket','');
            $kb_page = add_submenu_page('support', 'Knowledge base', 'Knowledge base','access_knowledgebase', 'edit.php?post_type=knowledgebase','');
            $kb_new = add_submenu_page('support', 'Add New Knowledge base Article', '&nbsp;&nbsp;&nbsp;Add New', 'access_knowledgebase', 'post-new.php?post_type=knowledgebase','');
            $kb_topic = add_submenu_page('support', 'Add/Edit Knowledge Base Topics', '&nbsp;&nbsp;&nbsp;Topics', 'access_knowledgebase','edit-tags.php?taxonomy=knowledge-base-topic','');
            $feedback = add_submenu_page('support', 'Review Feedback', 'Feedback', 'access_feedback', 'support-feedback','support_feedback');
            //$page = add_submenu_page('edit.php?post_type=ticket', 'Settings', 'Settings', 'manage_options', 'dynamo_support_settings', 'dynamo_support_settings_page');
        }
        $menu2 = add_menu_page( 'Dynamo', 'Dynamo', 'manage_options', 'dynamo', 'dynamo_overview', $plugin_folder.'/img/icon.png', 999);
        $page = add_submenu_page('dynamo', 'Support Dynamo', 'Support Dynamo', 'manage_options', 'dynamo_support_', 'dynamo_support_settings_page');

        add_action('admin_print_scripts-'.$dboard, 'ds_dash_load_scripts');

        if(function_exists('remove_submenu_page')) {
            remove_submenu_page( 'dynamo', 'dynamo' );
            remove_submenu_page( 'support', 'support' );
        }
        add_filter( 'custom_menu_order', 'dynamo_enable_menu_order'  );
        add_filter( 'menu_order', 'dynamo_menu_order' );

    }

    //Load Javascripts
    function ds_dash_load_scripts() {
        global $plugin_folder;
        wp_register_script('jflot',$plugin_folder.'js/jquery.flot.js',array('jquery'),'1.0',false);
        wp_register_script('jflot-section',$plugin_folder.'js/jquery.flot.selection.js',array('jquery','jflot'),'1.0',false);
        wp_enqueue_script('jflot');
        wp_enqueue_script('jflot-section');
        echo '<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="'.$plugin_folder.'js/excanvas.min.js"></script><![endif]-->';
    }


    if(!function_exists('dynamo_enable_menu_order')) {
        function dynamo_enable_menu_order() {
            return true;
        }
    }
    if(!function_exists('dynamo_menu_order')) {
        function dynamo_menu_order($menu_order) {
            $d_menu_order = array();
            foreach( $menu_order as $index => $item) {
                if ($item != 'dynamo') {
                    $d_menu_order[] = $item;
                }
                if($index == 0) {
                    $d_menu_order[] = 'dynamo';
                }
            }
            return $d_menu_order;
        }
    }
    //Remove Dynamo submenu in v < 3.1
    if(!function_exists('dynamo_remove_submenu')) {
        function dynamo_remove_submenu() {
            global $submenu;
            unset($submenu['dynamo'][0]);
        }
        add_action('admin_head', 'dynamo_remove_submenu');
    }
    /*
    * The Settings Page
    */
    function dynamo_support_settings_page() {
        global $support_options, $wpdb, $wp_roles, $plugin_folder;

        if($support_options['plugin_version'] == 'VZlWXRlVsNnUsR2MadEdaZVMaZVVB1TP') {
            $d = 'disabled="disabled';
            $f = '<span style="color:#cc0000; font-weight:bold; font-size:12px; margin-left:10px;">This option is included with the Customer Interaction Manager and is enabled for Support Dynamo Pro users only. <a href="http://plugindynamo.com/cim-upgrade" title="Upgrade To Support Dynamo Pro Today" target="_blank">Click Here To Upgrade Now</a></span>';
            $c = 'checked="checked"';
            $v = 'B';
        } else if ($support_options['plugin_version'] == '==AUWVUeZhFZzFWMaNVTWJVU') {
                $d = '';
                $f = '';
                $c = '';
                $v = 'P';
            } else {
                $d = 'disabled="disabled';
                $f = '<span style="color:#cc0000; font-weight:bold; font-size:12px; margin-left:10px;">This option is included with the Customer Interaction Manager and is enabled for Support Dynamo Pro users only. <a href="http://plugindynamo.com/cim-upgrade" title="Upgrade To Support Dynamo Pro Today" target="_blank">Click Here To Upgrade Now</a></span>';
                $c = 'checked="checked"';
                $v = '';
        }

    ?>
    <div class="wrap">
        <?php require_once('dynamo_support_header.php'); ?>
        <?php
            $view = $_GET['view'];
            switch($view) {
                default:

                ?>
                <div class="postbox">
                    <h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Support Dynamo - Overview</span></h3>
                    <div class="inside" style="padding:0 5px 5px 5px;">

                        <?php 
                            @$topAd = file_get_contents('http://cdn.plugindynamo.com/plugins/support-dynamo/top-ad.html');
                            if($topAd !== false) {
                                echo $topAd;
                            }
                        ?>
                        <img src="<?php echo $plugin_folder; ?>img/support-dynamo.png" height="261" width="300" alt="Support Dynamo" class="imgleft"/>
                        <p>
                            Support Dynamo is the all in ONE total Support System for WordPress that is powered through a very powerful but easy to learn Plugin all built to work with your current theme. No longer is there a need to login to multiple applications to manage your business/website. Support Dynamo creates a new and secure custom post type so you can easily handle all of your customer's support issues right from inside your WordPress dashboard.  
                        </p>
                        <img src="<?php echo $plugin_folder; ?>img/sd-image2.png" height="192" width="220" alt="Support Dynamo" class="imgright"/>
                        <p>
                            Support Dynamo also integrates with popular WordPress membership and Shopping Cart Plugins, to allow you to provide the best premium support to paying customers and know when communicating with expired and non-paying customers. Support Dynamo also comes with a CVC (Customer Value Calculator) which calculates how much a customer has spent with you and then displays this for you so you know the value of each customer when you provide them support.  
                        </p>
                        <img src="<?php echo $plugin_folder; ?>img/sd-image.png" height="192" width="220" alt="Support Dynamo" class="imgleft"/>
                        <p>
                            With Support Dynamos, Role Manager you can restrict access to certain topics for certain support users using the "Custom Roles" function. This is a great way to only allow Billing Staff to see the Billing Tickets or to outsource your support and not worry what they can see in your WordPress site. 
                        </p>
                        <p>You also have the ability to launch your own hosted Knowledge Base. Simply create a knowledge base of FAQ's to help users solve issues before they contact your support staff.  All you do is copy tickets as a draft article with a click of a button for faster publishing of common issues.</p>
                        <p>Support Dynamo makes providing high quality premium support a breeze!  Check out the Support Tab for FAQs.</p>
                        <div class="clear"></div>

                    </div>
                </div>
                <?php
                    break;
                case 'account':

                    require_once('dynamo-support-account.php');

                    break;
                case 'inputs':
                    if($_POST['submit']) {
                        //Inputs
                        $support_options['input'] = '';
                        foreach($_POST['input'] as $k => $v) {
                            $support_options['input'][$k] = $v;
                        }
                        update_option('dynamo_support_options',$support_options);
                        $support_options = get_option('dynamo_support_options');
                    ?>
                    <div id="message" class="updated">
                        <p><strong>
                                Settings Saved!
                            </strong></p>
                    </div>
                    <?php
                    }
                ?>
                <div class="postbox">
                    <h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Support Dynamo - New Ticket Form Inputs</span></h3>
                    <div class="inside" style="padding:0 5px 5px 5px;">
                        <?php require_once('support-tabs.php'); ?>
                        <form method="post" action="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=inputs">
                        <table class="form-table">
                        <tbody>
                        <tr valign="top">
                        <th scope="row">
                            <label>Additional Inputs:</label>
                        </th>
                        <td>
                        <div id="input-box">
                            <?php if(is_array($support_options['input'])) { 
                                    foreach($support_options['input'] as $k => $v) { 
                                        if($k == 1) { ?>
                                        <div style="margin-bottom:15px;">
                                            <label for="p_scnts" style="margin-right:85px;">Input Name:</label>
                                            <input type="text" size="40" name="input[1][label]" value="<?php echo stripslashes($v['label']);?>" placeholder="Label" /> 
                                            <input type="checkbox" name="input[1][required]" value="true" <?php if($v['required'] == 'true') { ?>checked="checked"<?php } ?>/> Required
                                        </div>
                                        <?php } else { ?>
                                        <div style="margin-bottom:15px;">
                                            <label for="p_scnts" style="margin-right:85px;">Input Name:</label>
                                            <input type="text" size="40" name="input[<?php echo $k; ?>][label]" value="<?php echo stripslashes($v['label']);?>" placeholder="Label" /> 
                                            <input type="checkbox" name="input[<?php echo $k; ?>][required]" value="true" <?php if($v['required'] == 'true') { ?>checked="checked"<?php } ?>/> Required

                                            <a href="#" class="remInput" title="Remove Input"></a>
                                        </div>
                                        <?php } ?>
                                    <?php } 
                                } else { ?>
                                <div style="margin-bottom:15px;">
                                    <label for="p_scnts" style="margin-right:85px;">Input Name:</label>
                                    <input type="text" size="40" name="input[1][label]" value=""  placeholder="Label" /> 
                                    <input type="checkbox" value="true" name="input[1][required]"/> Required 
                                </div>
                                <?php } ?>
                        </div>
                        <a href="#" id="addInput"><img src="<?php echo WP_CONTENT_URL.'/plugins/dynamo-support/img/add.gif';?>"/>Add New Field</a>
                    </div>
                    </td>
                    </tr>
                    </tbody>
                    </table>
                    <p class="submit"><input type="submit" class="button-primary" name="submit" value="Save Changes &#187;"/></p>
                    </form>
                </div>
            </div>
            <?php
                break;
            case 'auto-responses':
                if($_GET['delete']) {
                    unset($support_options['response'][$_GET['delete']]);
                    update_option('dynamo_support_options',$support_options);
                    $support_options = get_option('dynamo_support_options');
                ?>
                <div id="message" class="updated">
                    <p><strong>
                            Response Deleted!
                        </strong></p>
                </div>
                <?php
                }
                if($_POST['update-submit']) {
                    $support_options['response'][$_POST['response-key']]['title'] = $_POST['response-title'];
                    $support_options['response'][$_POST['response-key']]['content'] = $_POST['response-content'];

                    update_option('dynamo_support_options',$support_options);
                    $support_options = get_option('dynamo_support_options');
                ?>
                <div id="message" class="updated">
                    <p><strong>
                            Response Updated!
                        </strong></p>
                </div>
                <?php
                } else 
                    if($_POST['submit']) {
                        if($_POST['response-title'] != '' && $_POST['response-content'] != '') {
                            //Add New Response
                            $key = sanitize_title($_POST['response-title']);
                            $title = $_POST['response-title'];
                            $content = $_POST['response-content'];
                            $support_options['response'][$key]['title'] = $title;
                            $support_options['response'][$key]['content'] = $content;

                            update_option('dynamo_support_options',$support_options);
                            $support_options = get_option('dynamo_support_options');
                        }
                ?>
                <div id="message" class="updated">
                    <p><strong>
                            Settings Saved!
                        </strong></p>
                </div>
                <?php
                }
            ?>
            <div class="postbox">
                <h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Support Dynamo - Canned Responses</span></h3>
                <div class="inside" style="padding:0 5px 5px 5px;">
                    <?php require_once('support-tabs.php'); ?>
                    <?php echo $f; ?>
                    <?php if($_GET['edit'] && $_GET['edit'] != '') { ?>
                        <br/>
                        <a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=auto-responses" title="Back To Auto Responses Overview">&#171; Auto Response Overview</a>
                        <br/>
                        <h4>Edit Response: <?php echo $support_options['response'][$_GET['edit']]['title'];?></h4>
                        <form method="post" action="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=auto-responses&edit=<?php echo $_GET['edit'];?>">
                            <table class="form-table">
                                <tbody>
                                    <tr valign="top">
                                        <th scope="row">
                                            <label for="response-title">Response Title</label>
                                        </th>
                                        <td>
                                            <input type="hidden" name="response-key" value="<?php echo $_GET['edit'];?>"/>
                                            <input name="response-title" <?php echo $d; ?> type="text" size="60" value="<?php echo $support_options['response'][$_GET['edit']]['title'];?>"/>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row">
                                            <label for="response-content">Response Content</label>
                                        </th>
                                        <td>
                                            <textarea name="response-content" <?php echo $d; ?> rows="10" cols="80"><?php echo stripslashes($support_options['response'][$_GET['edit']]['content']);?></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="submit">
                                <input type="submit" name="update-submit" id="submit" class="button-primary" value="Update Response &#0187;"/>
                            </p>
                        </form>
                        <?php } else { ?>
                        <form method="post" action="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=auto-responses">
                            <table class="form-table">
                                <tbody>
                                    <tr valign="top">
                                        <th scope="row">
                                            <label for="response-title">Response Title</label>
                                        </th>
                                        <td>
                                            <input name="response-title" <?php echo $d; ?> type="text" placeholder="Enter a title for your response" size="60" value=""/>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row">
                                            <label for="response-content">Response Content</label>
                                        </th>
                                        <td>
                                            <textarea <?php echo $d; ?> name="response-content" placeholder="Enter your auto response here" rows="10" cols="80"></textarea>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><label>Current Responses</label></th>
                                        <td>
                                            <div id="current-responses">
                                                <?php
                                                    $responses = $support_options['response'];
                                                    if(is_array($responses) && !empty($responses)) {
                                                        foreach($responses as $k => $array) {
                                                            echo '<a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=dynamo_support_&view=auto-responses&edit='.$k.'" title="Edit This Auto Response">'.$array['title'].'</a> <a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=dynamo_support_&view=auto-responses&delete='.$k.'" title="Delete This Auto Response" style="color:red; text-decoration:none; font-size:14px; margin-left:20px;">X</a><br/>';
                                                        }
                                                    } else {
                                                        echo'You currently don\'t have any auto responses set-up at this time, why not create one?';
                                                    }
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="submit">
                                <input type="submit" name="submit" id="submit" class="button-primary" value="Save Response &#0187;"/>
                            </p>
                        </form>
                        <?php } ?>
                </div>
            </div>
            <?php
                break;
            case 'email-settings':
                if($_POST['submit']) {
                    $support_options['email-from-name'] = $_POST['email-from-name'];
                    $support_options['email-from'] = $_POST['email-from'];
                    $support_options['new_username'] = $_POST['new_username'];
                    $support_options['email-admin-notice'] = $_POST['email-admin-notice'];
                    $support_options['email-admin'] = $_POST['email-admin'];
                    $support_options['new-ticket-email'] = $_POST['new-ticket-email'];
                    $support_options['ticket-reply-email'] = $_POST['ticket-reply-email'];
                    $support_options['email-affiliate-link'] = $_POST['email-affiliate-link'];
                    $support_options['email-affiliate-id'] = $_POST['email-affiliate-id'];
                    $support_options['feedback_email_text'] = $_POST['feedback_email_text'];
                    $support_options['weekly-reports'] = $_POST['weekly-reports'];


                    update_option('dynamo_support_options',$support_options);
                    $support_options = get_option('dynamo_support_options');
                ?>
                <div id="message" class="updated">
                    <p><strong>
                            Settings Saved!
                        </strong></p>
                </div>
                <?php
                }
                if($_POST['save-notice'] && $_GET['deletenotice'] == '') {
                    if($_POST['email-role'] != '' && $_POST['email-topic'] != '') {
                        $support_options['email-notifications'][] = array('role' => $_POST['email-role'],'topic' => $_POST['email-topic']);
                        update_option('dynamo_support_options',$support_options);
                        $support_options = get_option('dynamo_support_options');

                    ?>
                    <div id="message" class="updated">
                        <p><strong>
                                E-Mail Notification Saved!
                            </strong></p>
                    </div>
                    <?php
                    } else {
                    ?>
                    <div id="message" class="updated">
                        <p><strong>
                                Please select both a role and an email topic to create a new notification!
                            </strong></p>
                    </div>
                    <?php
                    }
                }
                if(isset($_GET['deletenotice'])) {
                    unset($support_options['email-notifications'][$_GET['deletenotice']]);
                    update_option('dynamo_support_options',$support_options);
                    $support_options = get_option('dynamo_support_options');
                ?>
                <div id="message" class="updated">
                    <p><strong>
                            E-Mail Notification Deleted!
                        </strong></p>
                </div>
                <?php
                }
                if($_POST['ticket-mailserver-submit']) {

                    // Check server fields are set
                    if(isset($_POST['servers'])){
                        $support_options['servers'] = array_values($_POST['servers']);   
                    }                    
//                  $support_options['servers'] = array_values($_POST['servers']);
                    $support_options['allow_attachments'] = $_POST['allow_attachments'];
                    $support_options['max_attachment_size'] = $_POST['max_attachment_size'];
                    $support_options['allowed_attachments'] = explode(',',trim($_POST['allowed_attachments']));
                    update_option('dynamo_support_options',$support_options);
                    $support_options = get_option('dynamo_support_options');
                ?>
                <div id="message" class="updated">
                    <p><strong>
                            Tickets By Email Settings Saved!
                        </strong></p>
                </div>
                <?php
                }
                //dynamo_support_retrieve_tickets_via_mail();
                if($_GET['reset_default'] && $_GET['reset_default'] != '' && !$_POST) {
                    dynamo_support_defaults($_GET['reset_default']);
                    $support_options = get_option('dynamo_support_options');
                ?>
                <div id="message" class="updated">
                    <p><strong>
                            Individual Default Setting Restored!
                        </strong></p>
                </div>
                <?php
                }

                dynamo_support_retrieve_tickets_via_mail();

            ?>
            <div class="postbox">
                <h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Support Dynamo - Email Settings</span></h3>
                <div class="inside" style="padding:0 5px 5px 5px;">
                    <?php require_once('support-tabs.php'); ?>
                    <form method="post" action="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=email-settings">
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="email-from-name">E-Mail From Name</label>
                                    </th>
                                    <td>
                                        <input type="text" value="<?php echo $support_options['email-from-name'];?>" name="email-from-name" size="40"/>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="email-from">E-Mail From Address</label>
                                    </th>
                                    <td>
                                        <input type="text" value="<?php echo $support_options['email-from'];?>" name="email-from" size="40"/>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="new_username">New User Username</label>
                                    </th>
                                    <td>
                                        Use Email: <input type="radio" name="new_username" value="email" <?php if($support_options['new_username'] == 'email') { echo 'checked="checked"'; } ?>/>&nbsp;&nbsp;&nbsp; Use Displayname: <input type="radio" name="new_username" value="username" <?php if($support_options['new_username'] == 'username') { echo 'checked="checked"'; } ?>/><br/>
                                        <span class="description">When tickets come in from unregistered users and a new account needs to be created, use their email as the username or use their display name (first+last name).</span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="email-admin-notice">Send New/Update Ticket Notifications</label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="email-admin-notice" value="1" <?php if($support_options['email-admin-notice'] == '1') { echo 'checked="checked"';} ?>/><br/><span class="description">This affects all notifications globalby even role specific.</span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="weekly-reports">Send Weekly Email Reports</label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="weekly-reports" value="1" <?php if($support_options['weekly-reports'] == '1') { echo 'checked="checked"';} ?>/><br/><span class="description">Weekly Support Email Reports Sent to the Admin Email Address.</span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="email-admin">Admin E-mail Address</label>
                                    </th>
                                    <td>
                                        <input type="text" name="email-admin" value="<?php echo $support_options['email-admin']; ?>" size="40"/><br/><span class="description">The admin will receive all ticket notifications if turned on.</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label>Additional E-Mail Notifications</label></th>
                                    <td>
                                        E-Mail all users who are <select name="email-role"><option value="">-- Select Role --</option><?php dynamo_support_roles_dropdown();?></select> when a new ticket is posted in <select name="email-topic"><option value="">-- Select Topic --</option><?php dynamo_support_topic_dropdown();?></select> <input type="submit" value="Save Notification &#187;" name="save-notice"/>
                                        <div>
                                            <h4>Current Notifications</h4>
                                            <?php
                                                if(!empty($support_options['email-notifications'])) {
                                                    foreach($support_options['email-notifications'] as $k => $ve) {
                                                        echo '<a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=dynamo_support_&view=email-settings&deletenotice='.$k.'" title="Delete notice" style="color:red; font-size:14px; margin-right:20px; text-decoration:none;">x</a>E-Mail all users in <strong style="text-decoration:underline; color:#21759B;">'.$ve['role'].'</strong> when a new ticket is posted in <strong style="text-decoration:underline; color:#21759B;">'.$ve['topic'].'</strong><br/>';
                                                    }
                                                } else {
                                                    echo 'You currently don\'t have any notification rules set-up.';
                                                }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="new-ticket-email">New Ticket E-Mail Template</label>
                                    </th>
                                    <td>
                                        <textarea id="new-ticket-email" name="new-ticket-email" rows="10" cols="80"><?php echo stripslashes($support_options['new-ticket-email']); ?></textarea><br/><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=email-settings&reset_default=new-ticket-email" title="Reset Default">Reset Default &#187;</a><br/>
                                        <span class="description">Enter <strong>%content%</strong> to display the ticket content, Enter <strong>%title%</strong> to display the ticket title, Enter <strong>%link%</strong> to display the ticket link, Enter <strong>%days%</strong> to display days until ticket is closed</span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="ticket-reply-email">Ticket Reply E-Mail Template</label>
                                    </th>
                                    <td>
                                        <textarea id="ticket-reply-email" name="ticket-reply-email" rows="10" cols="80"><?php echo stripslashes($support_options['ticket-reply-email']); ?></textarea><br/><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=email-settings&reset_default=ticket-reply-email" title="Reset Default">Reset Default &#187;</a><br/>
                                        <span class="description">Enter <strong>%content%</strong> to display the ticket content, Enter <strong>%title%</strong> to display the ticket title, Enter <strong>%link%</strong> to display the ticket link, Enter <strong>%days%</strong> to display days until ticket is closed</span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="ticket-reply-email">Ticket Closes Requesting Feedback E-Mail Template</label>
                                    </th>
                                    <td>
                                        <textarea id="feedback_email_text" name="feedback_email_text" rows="10" cols="80"><?php echo stripslashes($support_options['feedback_email_text']); ?></textarea><br/><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=email-settings&reset_default=feedback_email_text" title="Reset Default">Reset Default &#187;</a><br/>
                                        <span class="description">Enter <strong>%title%</strong> to display the ticket title, Enter <strong>%link%</strong> to display the ticket link</span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><label for="email-affiliate-link">Affiliate Link In Footer Of All Sent E-Mails</label></th>
                                    <td>
                                        <input type="checkbox" name="email-affiliate-link" value="Y" <?php if($support_options['email-affiliate-link'] == 'Y') { echo 'checked="checked"'; } ?>/>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><label for="email-affiliate-id">ClickBank ID (for affiliate link)</label></th>
                                    <td>
                                        <input type="text" name="email-affiliate-id" value="<?php echo $support_options['email-affiliate-id']; ?>" size="40"/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="submit">
                            <input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes &#0187;"/>
                        </p>
                    </form>

                    <h4>Tickets via e-mail</h4>

                    <p>To allow your customers to submit a ticket by e-mail you must set up an e-mail account with IMAP access. Any mail received at this address will be posted. If the mail "from" address is a current user the ticket will be published and assigned to that user. If the from address does not match any current exisiting users a Temporary account will be created for the user.</p>
                    <p>If your server does not have IMAP support and your status below is "In-active" contact your hosting provider and ask them if they can enable IMAP for you. You can always check the support center inside PluginDynamo.com for more details.</p>
                    <p><b>Your Sever's IMAP Status:</b> <?php if(function_exists('imap_open')) { ?>Active<?php } else { ?>In-active<?php } ?></p>
                    <?php
                        // Get topic array
                        global $support_options;
                        $support_options_topic_arr = array();
                        if(count($support_options['topics'])){
                            foreach($support_options['topics'] as $key=>$supporttopics) 
                            {
                                $support_options_topic_arr[]= $supporttopics['name'];
                            }                                   
                        }
                    ?>
                    <?php if ($v === 'P') { ?>
                        <form name="ticket-mailserver" method="post" action="">
                            <table class="email-accounts-table" cellpadding="2">
                                <thead>
                                    <tr>
                                        <th>Account</th>
                                        <th>Server</th>
                                        <th>Port</th>
                                        <th>Username</th>
                                        <th>Password</th>
                                        <th>Topic</th>
                                        <th>Protocol</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="email-acts">
                                    <?php 
                                        $servers = $support_options['servers'];
                                        if(is_array($servers) && count($servers)) {
                                            $i = 0; $c = count($servers);
                                            foreach($servers as $k => $s) {                                              
                                            ?>
                                            <tr rel="<?php echo $k;?>" class="server-row">
                                                <td>
                                                    <input class="mail-act-input" action-to="servers[<?php echo $i;?>][name]" type="text" disabled="disabled" value="<?php echo $s['name'];?>" name="servers[<?php echo $i;?>][name-dump]"/>
                                                    <input type="hidden" value="<?php echo $s['name'];?>" name="servers[<?php echo $i;?>][name]"/>
                                                </td>
                                                <td>
                                                    <input class="mail-act-input" action-to="servers[<?php echo $i;?>][server]" type="text" disabled="disabled" value="<?php echo $s['server'];?>" name="servers[<?php echo $i;?>][server-dump]" />
                                                    <input type="hidden" value="<?php echo $s['server'];?>" name="servers[<?php echo $i;?>][server]"/>
                                                </td>
                                                <td>
                                                    <input class="mail-act-input" action-to="servers[<?php echo $i;?>][port]" type="text" disabled="disabled" value="<?php echo $s['port'];?>" name="servers[<?php echo $i;?>][port-dump]"/>
                                                    <input type="hidden" value="<?php echo $s['port'];?>" name="servers[<?php echo $i;?>][port]"/>
                                                </td>
                                                <td>
                                                    <input class="mail-act-input" action-to="servers[<?php echo $i;?>][user]" type="text" disabled="disabled" value="<?php echo $s['user'];?>" name="servers[<?php echo $i;?>][user-dump]"/>
                                                    <input type="hidden" value="<?php echo $s['user'];?>" name="servers[<?php echo $i;?>][user]"/>
                                                </td>
                                                <td>
                                                    <input class="mail-act-input" action-to="servers[<?php echo $i;?>][pass]" type="password" disabled="disabled" value="<?php echo $s['pass'];?>" name="servers[<?php echo $i;?>][pass-dump]"/>
                                                    <input type="hidden" value="<?php echo $s['pass'];?>" name="servers[<?php echo $i;?>][pass]"/>
                                                </td>
                                                <td>
                                                    <select class="mail-act-input" name="servers[<?php echo $i;?>][email-topic-ticket-dump]" rel="servers[<?php echo $i;?>][email-topic-ticket]" id="email-topic-ticket_<?php echo $j;?>" disabled="disabled">
                                                        <option value="">-- Select Topic --</option>
                                                        <?php //dynamo_support_topic_dropdown();?>
                                                        <?php if(count($support_options_topic_arr)):?>
                                                            <?php foreach($support_options_topic_arr as $topicarr):?>
                                                                <option value="<?php echo $topicarr;?>" <?php if($s['email-topic-ticket']==$topicarr){echo 'selected="selected"'; $selected_topicarr = $topicarr; }?> ><?php echo $topicarr;?></option>
                                                                <?php endforeach;?>
                                                            <?php endif;?>
                                                    </select>
                                                    <input type="hidden" name="servers[<?php echo $i;?>][email-topic-ticket]" value="<?php echo $selected_topicarr;?>">
                                                </td>
                                                <td>
                                                    <input class="mail-act-input" action-to="servers[<?php echo $i;?>][protocol]" type="radio" disabled="disabled" value="pop3" name="servers[<?php echo $i;?>][protocol-dump]" <?php if($s['protocol'] != 'imap') { echo 'checked="checked"'; } ?>/>POP3&nbsp;&nbsp;
                                                    <input class="mail-act-input" action-to="servers[<?php echo $k;?>][protocol]" type="radio" disabled="disabled" value="imap" name="servers[<?php echo $k;?>][protocol-dump]" <?php if($s['protocol'] == 'imap') { echo 'checked="checked"'; } ?>/>IMAP
                                                    <input type="hidden" name="servers[<?php echo $i;?>][protocol]" value="<?php if($s['protocol'] != 'imap') { echo 'pop3"'; } else { echo 'imap'; } ?>">
                                                </td>
                                                <td>
                                                    <input type="checkbox" class="mail-act-input" disabled="disabled" value="1" action-to="servers[<?php echo $i;?>][ssl]" <?php if($s['ssl'] == '1') { echo 'checked="checked"'; } ?> name="servers[<?php echo $i;?>][ssl-dump]"/> Requires SSL
                                                    <input type="hidden" value="<?php if($s['ssl'] == '1') { echo '1'; } ?>" name="servers[<?php echo $i;?>][ssl]">
                                                </td>
                                                <td><button class="row-action" rel="<?php echo $i;?>">Edit</button><?php if($i != '0') { ?><button class="remove-row" rel="<?php echo $i; ?>">Remove</button> <?php } ?></td>
                                            </tr>
                                            <?php
                                                $i++;
                                            }
                                        } else {
                                        ?>
                                        <tr rel="0" class="server-row">
                                            <td>
                                                <input class="mail-act-input" action-to="servers[0][name]" type="text" disabled="disabled" value="" name="servers[0][name-dump]"/>
                                                <input type="hidden" value="" name="servers[0][name]"/>
                                            </td>
                                            <td>
                                                <input class="mail-act-input"  action-to="servers[0][server]" type="text" disabled="disabled" value="" name="servers[0][server-dump]"/>
                                                <input type="hidden" value="" name="servers[0][server]"/>
                                            </td>
                                            <td>
                                                <input class="mail-act-input" action-to="servers[0][port]" type="text" disabled="disabled" value="" name="servers[0][port-dump]"/>
                                                <input type="hidden" value="" name="servers[0][port]"/>
                                            </td>
                                            <td>
                                                <input class="mail-act-input" action-to="servers[0][user]" type="text" disabled="disabled" value="" name="servers[0][user-dump]"/>
                                                <input type="hidden" value="" name="servers[0][user]"/>
                                            </td>
                                            <td>
                                                <input class="mail-act-input" action-to="servers[0][pass]" type="password" disabled="disabled" value="" name="servers[0][pass-dump]"/>
                                                <input type="hidden" value="" name="servers[0][pass]"/>
                                            </td>
                                            <td>
                                                <select class="mail-act-input" id="email-topic-ticket_0" name="servers[0][email-ticket-topic-dump]" rel="servers[0][email-topic-ticket]" disabled="disabled"><option value="">-- Select Topic --</option><?php dynamo_support_topic_dropdown();?></select>
                                                <input type="hidden" name="servers[0][email-topic-ticket]" value="">
                                            </td>
                                            <td>
                                                <input class="mail-act-input" type="radio" disabled="disabled" value="pop3" name="servers[0][protocol-dump]" checked="checked"/>POP3&nbsp;&nbsp;
                                                <input class="mail-act-input" type="radio" disabled="disabled" value="imap" name="servers[0][protocol-dump]" />IMAP
                                                <input type="hidden" name="servers[<?php echo $i;?>][protocol]" value="">
                                            </td>
                                            <td>
                                                <input class="mail-act-input" type="checkbox" disabled="disabled" value="1" action-to="servers[0][ssl]" name="server[0][ssl-dump]"/>  Requires SSL
                                                <input type="hidden" value="" name="servers[0][ssl]">
                                            </td>
                                            <td><button class="row-action" rel="0">Edit</button></td>
                                        </tr>
                                        <?php 
                                        }

                                        // ******************************************
                                        // Our target is to create a topic string which 
                                        // will be accessable by the javascript ticket-options.js
                                        // ******************************************

                                        if(count($support_options_topic_arr)){
                                            echo '<input type="hidden" name="topicstring" id="topicstring" value="'.implode('|',$support_options_topic_arr).'" />';
                                        }else{
                                            echo '<input type="hidden" name="topicstring" id="topicstring" value="" />'; 
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr id="add-new">
                                        <td colspan="6">
                                            <a href="#" class="add-new-server"><img src="<?php echo WP_CONTENT_URL.'/plugins/dynamo-support/img/add.gif';?>"/> Add New</a>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td scope="row"><label for="allow_attachments">Allow Incoming Attachments</label></td>
                                        <td colspan="5">
                                            <input type="checkbox" name="allow_attachments" value="1" <?php if($support_options['allow_attachments'] == '1') { echo 'checked="checked"'; } ?>/><br/>
                                            <span class="description">Do you want to allow incoming attachments</span>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td scope="row"><label for="max_attachment_size">Max Attachment Size (bytes)</label></td>
                                        <td colspan="5">
                                            <input type="text" name="max_attachment_size" value="<?php echo $support_options['max_attachment_size']; ?>" size="40"/><br/>
                                            <span class="description">Enter the size in bytes of the max allowed file size for attachments 1 mb = 1048576 bytes</span>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td scope="row"><label for="allowed_attachments">Allowed Attachments</label></td>
                                        <td colspan="5">
                                            <input type="text" name="allowed_attachments" value="<?php echo implode(',',$support_options['allowed_attachments']); ?>" size="40"/><br/>
                                            <span class="description">Comma seperated allowed attachments i.e. jpg, bmp, gif, psd etc</span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <p class="submit">
                                <input type="submit" name="ticket-mailserver-submit" id="" class="button-primary" value="Save Ticket E-Mail Settings &#187;"/>
                            </p>
                        </form>
                        <?php } else { 
                            echo $f;
                        }
                    ?>

                </div>
            </div>
            <?php
                break;
            case 'roles':
                if($_GET['delete-role']) {
                    $role = $_GET['delete-role'];
                    $default_role = get_option( 'default_role' );
                    //Dont delete default
                    if($role != $default_role) {
                        $users = get_users( array( 'role' => $role ) );

                        // Check if there are any users 
                        if ( is_array( $users ) ) {
                            foreach ( $users as $user ) {
                                $new_user = new WP_User( $user->ID );
                                if ( $new_user->has_cap( $role ) ) {
                                    $new_user->remove_role( $role );
                                    $new_user->set_role( $default_role );
                                }
                            }
                        }
                        remove_role( $role );
                        unset($support_options['roles'][$role]);
                    ?>
                    <div id="message" class="updated">
                        <p><strong>
                                Role Deleted!
                            </strong></p>
                    </div>
                    <?php
                    }
                    update_option('dynamo_support_options',$support_options);
                    $support_options = get_option('dynamo_support_options');
                }
                if($_POST['submit']) {
                    /* echo '<pre>';
                    var_dump($_REQUEST);
                    echo '</pre>';*/
                    //Roles Update / New
                    //Edit / Update Current Role
                    $caps = dymamo_support_get_capabilities();
                    foreach($caps as $capa => $array) {
                        $capabilities2[$capa] = $array['val'];
                    }

                    if(is_array($_POST['roles'])) { 
                        foreach($_POST['roles'] as $role => $array) {
                            $current = $wp_roles->get_role($role);  
                            //Go through all caps and updat as accordingly
                            $caps = array_merge($capabilities2, $array['capabilities']);
                            foreach($caps as $cap => $value) {

                                if($value == true) {
                                    //If true and does not have cap add it
                                    if(!$current->has_cap($cap)) {
                                        $current->add_cap($cap);
                                        //If is Access KB or Access Tickets add other stuff
                                        if($cap == 'access_knowledgebase') {
                                            $current->add_cap('publish_articles');
                                            $current->add_cap('edit_articles');
                                            $current->add_cap('delete_articles');
                                            $current->add_cap('delete_others_articles');
                                            $current->add_cap('edit_others_articles');
                                            $current->add_cap('read_private_articles');
                                            $current->add_cap('read_articles');
                                            $current->add_cap('read_article');
                                            $current->add_cap('edit_article');
                                            $current->add_cap('delete_article');
                                        } else if($cap == 'access_tickets') {
                                                $current->add_cap('publish_tickets');
                                                $current->add_cap('edit_tickets');
                                                $current->add_cap('delete_tickets');
                                                $current->add_cap('delete_others_tickets');
                                                $current->add_cap('edit_others_tickets');
                                                $current->add_cap('read_private_tickets');
                                                $current->add_cap('read_tickets');
                                                $current->add_cap('read_ticket');
                                                $current->add_cap('edit_ticket');
                                                $current->add_cap('delete_ticket');
                                            }
                                    }
                                } else if($value == false) {
                                        if($current->has_cap($cap)) {
                                            $current->remove_cap($cap);
                                            //If is Access KB or Access Tickets remove other stuff
                                            if($cap == 'access_knowledgebase') {
                                                $current->remove_cap('publish_articles');
                                                $current->remove_cap('edit_articles');
                                                $current->remove_cap('delete_articles');
                                                $current->remove_cap('delete_others_articles');
                                                $current->remove_cap('edit_others_articles');
                                                $current->remove_cap('read_private_articles');
                                                $current->remove_cap('read_articles');
                                                $current->remove_cap('read_article');
                                                $current->remove_cap('edit_article');
                                                $current->remove_cap('delete_article');
                                            } else if($cap == 'access_tickets') {
                                                    $current->remove_cap('publish_tickets');
                                                    $current->remove_cap('edit_tickets');
                                                    $current->remove_cap('delete_tickets');
                                                    $current->remove_cap('delete_others_tickets');
                                                    $current->remove_cap('edit_others_tickets');
                                                    $current->remove_cap('read_private_tickets');
                                                    $current->remove_cap('read_tickets');
                                                    $current->remove_cap('read_ticket');
                                                    $current->remove_cap('edit_ticket');
                                                    $current->remove_cap('delete_ticket');
                                                }
                                    }
                                } 
                                if($cap == 'ticket-assignment') {
                                    if($value == 'all') {

                                        $current->add_cap('view_all_tickets');
                                        $current->remove_cap('view_own_tickets');
                                        $current->remove_cap('view_unassigned_tickets');

                                    } else if($value == 'own-unassigned') {

                                            $current->add_cap('view_unassigned_tickets');
                                            $current->remove_cap('view_own_tickets');
                                            $current->remove_cap('view_all_tickets');

                                        } else if($value == 'own') {

                                                $current->add_cap('view_own_tickets');
                                                $current->remove_cap('view_all_tickets');
                                                $current->remove_cap('view_unassigned_tickets');

                                            }
                                }
                            }
                        }
                    } 
                    $support_options['roles'] = $_POST['roles'];
                    //Look if new role was submited


                    update_option('dynamo_support_options',$support_options);
                    $support_options = get_option('dynamo_support_options'); 
                ?>
                <div id="message" class="updated">
                    <p><strong>
                            Settings Saved!
                        </strong></p>
                </div>
                <?php } ?>
            <?php
                if($_POST['submit'] === 'Save Role' && $_POST['role_name'] != '' && !empty($_POST['capabilities'])) {
                    //Add New Role
                    global $wp_roles,$support_options;

                    $new_role_name = sanitize_title( $_POST['role_name'] );
                    $new_role_label = strip_tags( $_POST['role_name'] );

                    $caps = dymamo_support_get_capabilities();

                    if(!$wp_roles->get_role($new_role_name)) {
                        $capabilities = array();
                        foreach($caps as $k => $v) {
                            if($_POST['capabilities'][$k] == true) {
                                $capabilities[$k] = true;
                            } else {
                                $capabilities[$k] = false;
                            }
                        }

                        //Ticket Assign Options
                        if($_POST['capabilities']['ticket-assignment'] == 'all') {
                            $capabilities['view_all_tickets'] = true;
                            $capabilities['ticket-assignment'] = 'all';   
                        } else if($_POST['capabilities']['ticket-assignment'] == 'own-unassigned') {
                                $capabilities['view_unassigned_tickets'] = true;
                                $capabilities['ticket-assignment'] = 'own-unassigned';
                            } else if($_POST['capabilities']['ticket-assignment'] == 'own') {
                                    $capabilities['view_own_tickets'] = true;
                                    $capabilities['ticket-assignment'] = 'own';
                                } 

                                //Save To SD Options
                                $support_options['roles'][$new_role_name]['name'] = $new_role_label;

                        // Capability value '1' is not working
                        // Change default value 1 to 'true'
                        $new_capabilities = array();
                        if(count($capabilities)){
                            foreach($capabilities as $capabilities_key=>$capabilities_val) 
                            {
                                if(!empty($capabilities_val)){
                                    if($capabilities_val==1){
                                        $new_capabilities[$capabilities_key] = 'true';
                                    }else{
                                        $new_capabilities[$capabilities_key] = $capabilities_val;
                                    }  
                                }


                            } 

                        }

                        // Set role option
                        $support_options['roles'][$new_role_name]['capabilities'] = $new_capabilities;

                        //Check To See If User Has Knowledgebase Access
                        if($capabilities['access_knowledgebase'] == true) {
                            $capabilities['publish_articles'] = true;
                            $capabilities['edit_articles'] = true;
                            $capabilities['delete_articles'] = true;
                            $capabilities['delete_others_articles'] = true;
                            $capabilities['edit_others_articles'] = true;
                            $capabilities['read_private_articles'] = true;
                            $capabilities['read_articles'] = true;
                            $capabilities['read_article'] = true;
                            $capabilities['edit_article'] = true;
                            $capabilities['delete_article'] = true;
                        }
                        //Give Ticket Permissions
                        if($capabilities['access_tickets'] == true) {
                            $capabilities['publish_tickets'] = true;
                            $capabilities['edit_tickets'] = true;
                            $capabilities['delete_tickets'] = true;
                            $capabilities['delete_others_tickets'] = true;
                            $capabilities['edit_others_tickets'] = true;
                            $capabilities['read_private_tickets'] = true;
                            $capabilities['read_tickets'] = true;
                            $capabilities['read_ticket'] = true;
                            $capabilities['edit_ticket'] = true;
                            $capabilities['delete_ticket'] = true;
                        }
                        //Add Default WP Needed Caps
                        $capabilities['read'] = true;                            
                        $capabilities['userstatistics'] = true;


                        // Capability value '1' is not working
                        // Change default value 1 to 'true'
                        $newcapabilities = array();
                        foreach($capabilities as $capabilitieskey=>$capabilitiesval) 
                        {
                            if(!empty($capabilitiesval)){
                                if($capabilitiesval==1){
                                    $newcapabilities[$capabilitieskey] = 'true';
                                }else{
                                    $newcapabilities[$capabilitieskey] = $capabilitiesval;
                                }  
                            }


                        }                              

                        // exit;
                        $wp_roles->add_role( $new_role_name, $new_role_label, $newcapabilities );


                        //Look if new role was submited                                                            
                        update_option('dynamo_support_options',$support_options);


                        unset($new_role_name, $new_role_label, $capabilities);
                        unset($caps);
                    }

                } 
            ?>
            <div class="postbox">
                <h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Support Dynamo - Role Manager</span></h3>
                <div class="inside" style="padding:0 5px 5px 5px;">
                    <?php require_once('support-tabs.php'); ?>
                    <form method="post" action="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=roles">
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row">

                                    </th>
                                    <td>
                                        <div id="roles-creation">
                                            <h4>Create New Role:</h4>
                                            <label>Role Name:</label><input type="text" value="" name="role_name" size="40" placeholder="Name your role"/>
                                            <div id="capabilities">
                                                <?php $caps = dymamo_support_get_capabilities(); 
                                                    foreach($caps as $cap => $v) {


                                                        if($v['val'] == '1') { $v['val'] = 'true'; $checked = 'checked="checked"'; } else { $v['val'] = 'false'; $checked = ''; }
                                                    ?>
                                                    <span class="cap">
                                                        <!--<input type="checkbox" name="capabilities[<?php //echo $cap;?>]" value="<?php //echo $v['val'];?>" <?php //echo $checked; ?> />--> <?php //echo $v['name']; ?>
                                                        <input type="checkbox" name="capabilities[<?php echo $cap;?>]" value="true" /> <?php echo $v['name']; ?>
                                                    </span>
                                                    <?php

                                                    }
                                                ?>
                                                <br/><br/><br/>
                                                <p><strong>Assign Ticket Settings</strong></p>
                                                <input type="radio" name="capabilities[ticket-assignment]" value="all" checked="checked"/> View All Tickets &nbsp;&nbsp;&nbsp; <input type="radio" name="capabilities[ticket-assignment]" value="own-unassigned" /> View Own & Un-Assigned Tickets  Only&nbsp;&nbsp;&nbsp; <input type="radio" name="capabilities[ticket-assignment]" value="own" /> View Own Tickets Only<br/>
                                                <input type="submit" name="submit" id="create-role" value="Save Role"/>
                                                <p class="clear" style="margin-top:10px;">*Access Tickets - Allows a user to see  Tickets link in their menu and post/edit/delete any ticket<br/>
                                                    *Access Knowledgebase - Allows a user to see Knowledgebase in their menu and post/edit/delete any article<br/>
                                                    *Access Tickets ~Topic~ - Allows a user to only see selected topic/s</p>
                                                <div id="current-roles">
                                                    <?php if(is_array($support_options['roles']) && !empty($support_options['roles'])) {
                                                            echo '<h4>Current Roles:</h4>';
                                                            $capabilities = dymamo_support_get_capabilities();
                                                            foreach($capabilities as $capa => $array) {
                                                                $capabilities2[$capa] = $array['val'];
                                                            }
                                                            foreach($support_options['roles'] as $role => $v) {
                                                            ?>
                                                            <div class="role">
                                                                <strong class="clear" style="display:block; width:100%;"><?php echo $v['name'];?> <a style="color:red; margin-left:10px;" href="<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=dynamo_support_&view=roles&delete-role='.$role.'';?>" title="Delete Role">X</a></strong>
                                                                <input type="hidden" name="roles[<?php echo $role;?>][name]" value="<?php echo $v['name'];?>"/>
                                                                <?php 
                                                                    if(is_array($capabilities2) && is_array($v['capabilities'])) {
                                                                        $caps = array_merge($capabilities2, $v['capabilities']);
                                                                        foreach($caps as $cap => $value) { 
                                                                            if($cap != 'view_own_tickets' && $cap != 'view_unassigned_tickets' && $cap != 'view_all_tickets' && $cap != 'ticket-assignment') {
                                                                                if($value == 'true') { $value = 'true'; $checked = 'checked="checked"'; } else { $value = 'true'; $checked = ''; }
                                                                            ?>
                                                                            <span class="cap">
                                                                                <input type="checkbox" name="roles[<?php echo $role; ?>][capabilities][<?php echo $cap;?>]" value="<?php echo $value;?>" <?php echo $checked; ?> /> <?php echo $capabilities[$cap]['name']; ?>
                                                                            </span>
                                                                            <?php 
                                                                            }
                                                                        }
                                                                    }
                                                                ?>
                                                                <div class="clear"></div>
                                                                <br/>
                                                                <?php
                                                                    $current = $wp_roles->get_role($role);
                                                                ?>
                                                                <p><strong>Assign Ticket Settings</strong></p>
                                                                <input type="radio" name="roles[<?php echo $role;?>][capabilities][ticket-assignment]" value="all" <?php if($current->has_cap('view_all_tickets')) { ?> checked="checked" <?php } ?>/> View All Tickets 
                                                                &nbsp;&nbsp;&nbsp; 
                                                                <input type="radio" name="roles[<?php echo $role;?>][capabilities][ticket-assignment]" value="own-unassigned" <?php if($current->has_cap('view_unassigned_tickets')) { ?> checked="checked" <?php } ?>/> View Own & Un-Assigned Tickets Only
                                                                &nbsp;&nbsp;&nbsp; 
                                                                <input type="radio" name="roles[<?php echo $role;?>][capabilities][ticket-assignment]" value="own" <?php if($current->has_cap('view_own_tickets')) { ?> checked="checked" <?php } ?>/> View Own Tickets Only<br/>
                                                            </div>
                                                            <?php
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="submit">
                            <input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes &#0187;"/>
                        </p>
                    </form>
                </div>
            </div>
            <?php
                break;
            case 'settings':

                if($_POST['submit']) {
                    //Topics
                    $support_options['topics'] = '';
                    foreach($_POST['topics'] as $k => $v) {
                        if($v['source'] == '' || $v['source'] == 'user') {
                            $v['source'] = 'user';
                        }
                        $support_options['topics'][$k] = $v;
                    }
                    //If Integration With Another Plugin Check And Do Necessary Updates
                    //WLM
                    if($_POST['integration'] == 'wishlist' && $support_options['integration'] != 'wishlist') {
                        if(is_plugin_active('wishlist-member/wpm.php')) {
                            $support_options['topics'] = dynamo_support_import_wlm_levels_topics($support_options['topics']);	
                        } else {
                        ?>
                        <div id="message" class="updated">
                            <p><strong>
                                    *NOTE: To integrate with WishList Member you must first have it activated on your WordPress install.
                                </strong></p>
                        </div>
                        <?php
                            $support_options['integration'] = ''; 
                        }
                    } else if ($_POST['integration'] == 'memberwingx' && $support_options['integration'] != 'memberwingx') {
                            if(is_plugin_active('memberwing-x/mwx.php')) {
                                $support_options['topics'] = dynamo_support_import_mwx_levels_topics($support_options['topics']);
                            } else {
                            ?>
                            <div id="message" class="updated">
                                <p><strong>
                                        *NOTE: To integrate with Member Wing X you must first have it activated on your WordPress install.
                                    </strong></p>
                            </div>
                            <?php
                                $support_options['integration'] = '';
                            }
                        } else if($_POST['integration'] == 'cart66' && $support_options['integration'] != 'cart66') {
                                if(class_exists('Cart66')) {
                                    $support_options['topics'] = dynamo_support_import_c66_levels_topics($support_options['topics']);
                                } else {
                                ?>
                                <div id="message" class="updated">
                                    <p><strong>
                                            *NOTE: To integrate with Cart 66 you must first have it activated on your WordPress install.
                                        </strong></p>
                                </div>
                                <?php
                                }
                            }
                            //Other options
                            $support_options['close-tickets'] = $_POST['close-tickets'];
                    $support_options['delete-tickets'] = $_POST['delete-tickets'];
                    $support_options['integration'] = $_POST['integration'];
                    $support_options['every'] = $_POST['every'];
                    $support_options['repeat'] = $_POST['repeat'];
                    $support_options['knowledgebase-welcome'] = $_POST['knowledgebase-welcome'];
                    $support_options['open-support'] = $_POST['open-support'];
                    $support_options['report-bug'] = $_POST['report-bug'];
                    $support_options['feedback-text'] = $_POST['feedback-text'];
                    $support_options['capture-data'] = $_POST['capture-data'];
                    $support_options['support-page'] = $_POST['support-page'];
                    $support_options['frontend_attachments'] = $_POST['frontend_attachments'];
                    $support_options['kb-enable-votes'] = $_POST['kb-enable-votes'];

                    update_option('dynamo_support_options',$support_options);
                    $support_options = get_option('dynamo_support_options');

                    if($support_options['open-support'] == '1') {
                        update_option('users_can_register','1');
                    }

                ?>
                <div id="message" class="">
                    <p><strong>
                            Settings Saved!
                        </strong></p>
                </div>
                <?php
                }
                if($_POST['get_topics']) {
                    if($support_options['integration'] != '') {
                        if($support_options['integration'] == 'wishlist') {
                            //Genarate Topics
                            $support_options['topics'] = dynamo_support_import_wlm_levels_topics($support_options['topics']);
                        ?>
                        <div id="message" class="updated">
                            <p><strong>
                                    Topics Imported From WishList Member!
                                </strong></p>
                        </div>
                        <?php
                        } else if ($support_options['integration'] == 'memberwingx') {
                                //Generate Topics
                                $support_options['topics'] = dynamo_support_import_mwx_levels_topics($support_options['topics']);
                            ?>
                            <div id="message" class="updated">
                                <p><strong>
                                        Topics Imported From Member Wing X!
                                    </strong></p>
                            </div>
                            <?php
                            } else if ($support_options['integration'] == 'cart66') {
                                    //Generate Topics
                                    $support_options['topics'] = dynamo_support_import_c66_levels_topics($support_options['topics']);
                                ?>
                                <div id="message" class="updated">
                                    <p><strong>
                                            Topics Imported From Cart 66!
                                        </strong></p>
                                </div>
                                <?php
                                }
                    }
                    update_option('dynamo_support_options',$support_options);
                }
                if($_GET['reset_default'] && $_GET['reset_default'] != '' && !$_POST) {
                    dynamo_support_defaults($_GET['reset_default']);
                    $support_options = get_option('dynamo_support_options');
                ?>
                <div id="message" class="updated">
                    <p><strong>
                            Individual Default Setting Restored!
                        </strong></p>
                </div>
                <?php
                }

            ?>
            <div class="postbox">
                <h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Dynamo SUPPORT - Settings</span></h3>
                <div class="inside" style="padding:0 5px 5px 5px;">
                    <?php require_once('support-tabs.php'); ?>
                    <form method="post" action="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=settings">
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="open-support">Select Your Support Page</label>
                                    </th>
                                    <td>
                                        <?php wp_dropdown_pages(array('name' => 'support-page', 'selected' => $support_options['support-page'])); ?>

                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="open-support">Open Support To All Visitors</label>
                                    </th>
                                    <td>
                                        <input type="checkbox" <?php echo $c;?> <?php if($support_options['open-support'] == '1') { echo 'checked="checked"'; } ?> value="1" name="open-support"/>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="report-bug">Enable Report Bug Feature:</label>
                                    </th>
                                    <td>
                                        <input type="checkbox" <?php echo $d;?> <?php if($support_options['report-bug'] == '1') { echo 'checked="checked"'; } ?> value="1" name="report-bug"/> <?php echo $f;?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="report-bug">Capture Customer Data:</label>
                                    </th>
                                    <td>
                                        <input type="checkbox" <?php if($support_options['capture-data'] == '1') { echo 'checked="checked"'; } ?> value="1" name="capture-data"/>
                                        <span class="description">Captures and includes at bottom of ticket a users: browser &amp; operating system.</span>								
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="open-support">Enable Knowledgebase "Helpful" Votes</label>
                                    </th>
                                    <td>
                                        <input type="checkbox" <?php echo $c;?> <?php if($support_options['kb-enable-votes'] == '1') { echo 'checked="checked"'; } ?> value="1" name="kb-enable-votes"/>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="open-support">Allow Logged In Users To Upload Attachments</label>
                                    </th>
                                    <td>
                                        <input type="checkbox" <?php echo $c;?> <?php if($support_options['frontend_attachments'] == '1') { echo 'checked="checked"'; } ?> value="1" name="frontend_attachments"/> <span class="description">Enable logged in users to upload attachments to tickets from the front end support portal.</span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="integration">Do You Want To Integrate With Any Of The Following Membership Scripts/Plugins</label>
                                    </th>
                                    <td>
                                        <select id="integration" name="integration" <?php echo $d;?>>
                                            <option value="" <?php if($support_options['integration'] == '') { echo 'selected="selected"'; }?>>-- None --</option>
                                            <option value="wishlist" <?php if($support_options['integration'] == 'wishlist') { echo 'selected="selected"'; }?>>WishList Member</option>
                                            <option value="memberwingx" <?php if($support_options['integration'] == 'memberwingx') { echo 'selected="selected"'; }?>>MemberWing X</option>
                                            <option value="cart66" <?php if($support_options['integration'] == 'cart66') { echo 'selected="selected"'; }?>>Cart 66</option>
                                        </select><?php echo $f;?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="close-tickets">Automatically Close Tickets After X Days Without A Response From The Ticket Author</label>
                                    </th>
                                    <td>
                                        <input type="text" value="<?php echo  $support_options['close-tickets'];?>" name="close-tickets" id="close-tickets" size="2"/>
                                        <span class="description">Enter 0 for never or the amount of days you want.</span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="delete-tickets">Automatically Delete Closed Tickets After Being Closed For X Days</label>
                                    </th>
                                    <td>
                                        <input type="text" value="<?php echo  $support_options['delete-tickets'];?>" name="delete-tickets" id="delete-tickets" size="2"/> <span class="description">Enter 0 for never or the amount of days you want.</span>
                                    </td>
                                </tr>


                                <tr valign="top">
                                    <th scope="row">
                                        <label for="knowledgebase-welcome">Knowledgebase Welcome Text</label>
                                    </th>
                                    <td>
                                        <textarea name="knowledgebase-welcome" id="knowledgebase-welcome" rows="10" cols="80"><?php echo stripslashes($support_options['knowledgebase-welcome']);?></textarea><br/><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=settings&reset_default=knowledgebase-welcome" title="Reset Default">Reset Default &#187;</a><br/>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row">
                                        <label for="knowledgebase-welcome">Feedback Text</label>
                                    </th>
                                    <td>
                                        <textarea name="feedback-text" id="feedback-text" <?php echo $d;?> rows="10" cols="80"><?php echo stripslashes($support_options['feedback-text']);?></textarea><br/><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=settings&reset_default=feedback-text" title="Reset Default">Reset Default &#187;</a><?php echo $f;?><br/>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="javapopup">Java Version Popup Link</label>
                                    </th>
                                    <td>
                                        <textarea rows="4" cols="80"><a href="#javaversion" onclick="window.open('<?php echo get_bloginfo('wpurl');?>/wp-content/plugins/dynamo-support/includes/javatest.php','Support Dynamo Java Version','height=5,width=457')" title="Get Your Java Version">Click To View Java Version</a></textarea><br/><span class="description">If you want to show your visitors their java version copy and paste this link into your welcome text or place it on your support page.</span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="topics[]">
                                            Topics
                                        </label>
                                    </th>
                                    <td>
                                        <div id="topicslist">
                                            <?php if(is_array($support_options['topics'])) { 
                                                    foreach($support_options['topics'] as $k => $v) { 
                                                        if($k == 1) { ?>
                                                        <div style="margin-bottom:15px;">
                                                            <label for="p_scnts" style="margin-right:85px;">Topic:</label>
                                                            <input type="text" size="40" name="topics[1][name]" value="<?php echo stripslashes($v['name']);?>" placeholder="Name your topic" /> 
                                                            <input type="checkbox" name="topics[1][hide]" value="true" <?php if($v['hide'] == 'true') { ?>checked="checked"<?php } ?>/> Don't Display Topic <input type="hidden" name="topics[1][source]" value="user"/>
                                                        </div>
                                                        <?php } else { ?>
                                                        <div style="margin-bottom:15px;">
                                                            <label for="p_scnts" style="margin-right:85px;">Topic:</label>
                                                            <input type="text" size="40" name="topics[<?php echo $k; ?>][name]" value="<?php echo stripslashes($v['name']);?>" placeholder="Name your topic" /> 
                                                            <input type="checkbox" name="topics[<?php echo $k; ?>][hide]" value="true" <?php if($v['hide'] == 'true') { ?>checked="checked"<?php } ?>/> Don't Display Topic  <input type="hidden" name="topics[<?php echo $k; ?>][source]" value="<?php echo $v['source'];?>"/>

                                                            <a href="#" class="remScnt" title="Remove Topic"></a>
                                                        </div>
                                                        <?php } ?>
                                                    <?php } 
                                                } else { ?>
                                                <div style="margin-bottom:15px;">
                                                    <label for="p_scnts" style="margin-right:85px;">Topic:</label>
                                                    <input type="text" size="40" name="topics[1][name]" value=""  placeholder="Name your topic" /> 
                                                    <input type="checkbox" value="true" name="topics[1][hide]"/> Don't Display Topic <input type="hidden" name="topics[1][source]" value="user"/>
                                                </div>
                                                <?php } ?>
                                        </div>
                                        <a href="#" id="addScnt"><img src="<?php echo WP_CONTENT_URL.'/plugins/dynamo-support/img/add.gif';?>"/>Add Topic</a>
                                        <?php if($support_options['integration'] != '') {?>
                                            <br/><form method="post" ><input type="submit" name="get_topics" value="Fetch Integration Topics"/></form>
                                            <?php } ?>
                                    </td>
                                </tr>
                                <?php if($support_options['integration'] != '') { ?>
                                    <?php if($support_options['integration'] == 'wishlist') {
                                            $calc_levels = dynamo_support_get_all_wlm_levels();
                                        } else if ($support_options['integration'] == 'memberwingx') {
                                                $calc_levels = dynamo_support_get_all_mwx_levels();
                                            } 
                                    ?>
                                    <tr valign="top">
                                        <th scope="row">
                                        <label for="price-calculator">Product Values</label>
                                        Legend: <strong style="font-size:16px;">$</strong> - Indicates a price has been set for a product.
                                        </td>
                                        <td>
                                            <div id="price-calc">
                                                <?php
                                                    if(is_array($calc_levels) && count($calc_levels)) {
                                                        foreach($calc_levels as $k => $v) {
                                                        ?>
                                                        <div style="">

                                                            <label style="font-weight:bold;"><?php if(is_array($support_options['price'][$v['id']]) && count($support_options['price'][$v['id']]) > '0') { echo '<strong style="font-size:16px;">$</strong>'; } ?> <?php echo $v['name'];?></label>&nbsp;&nbsp;<a href="<?php echo get_bloginfo('wpurl');?>/wp-content/plugins/dynamo-support/includes/dynamo-support-price-calc.php?id=<?php echo $v['id'];?>&TB_iframe=true&width=370&height=200" rel="<?php echo $v['name'];?>" class="set-price-settings">Set Pricing</a>

                                                        </div>
                                                        <?php
                                                        }
                                                    } else if($support_options['integration'] == 'cart66') {
                                                        ?>
                                                        <div style="">
                                                            Since you are integrated with Cart 66, there is no need to enter in any pricing information as all required details are accessible from a users order history.
                                                        </div>	
                                                        <?php
                                                        } else {
                                                        ?>
                                                        <div style="">
                                                            Currently there are no integrated products / membershiplevels to set prices for.
                                                        </div>
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } else { ?>
                                    <tr valign="top">
                                        <th scope="row">
                                        <label for="price-calculator">Product Values</label>
                                        </td>
                                        <td>
                                            <?php echo $f; ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                        <p class="submit">
                            <input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes &#0187;"/> <a href="<?php echo get_bloginfo('wpurl').'/wp-content/plugins/dynamo-support/logfile.txt_'.date('Y-m-d', current_time('timestamp',1)).'';?>" class="right">View Log</a>
                        </p>

                    </form>

                </div>
            </div>
            <?php
                break;
            case 'support':
                global $sd_updates;
            ?>
            <div class="wrap">  
                <div class="postbox">
                    <h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Support Dynamo - Support</span></h3>
                    <div class="inside" style="padding:0 5px 5px 5px;">

                        <div id="dyno-content-holder" style="margin-top: 20px;">
                            <?php echo $sd_updates->print_update_form();?>
                        </div>
                        <?php 
                            @$support = file_get_contents('http://cdn.plugindynamo.com/plugins/support-dynamo/support.html');
                            if($support !== false) {
                                echo $support;
                            } 
                        ?>

                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <?php
                break;
        }
    ?>
    <?php
    }

    function dynamo_support_search_array($needle, $key, $haystack) 
    {
        $in_multi_array = false;
        if (in_array($needle, $haystack))
        {
            $in_multi_array = true; 
        }else 
        {
            foreach( $haystack as $key1 => $val )
            {
                if(is_array($val)) 
                {
                    if(dynamo_support_search_array($needle, $key, $val)) 
                    {
                        $in_multi_array = true;
                        break;
                    }
                }
            }
        }

        return $in_multi_array;
    } 
?>