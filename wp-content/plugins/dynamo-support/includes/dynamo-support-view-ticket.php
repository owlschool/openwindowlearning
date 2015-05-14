<?php
    /*
    * Dynamo Support - Custom Post Type Creation + Admin View Functions
    */

    /*
    * Create Ticket Custom Post Type
    */
add_action('init', 'dynamo_support_init');
function dynamo_support_init() {
	global $plugin_folder;
	
	$plugin_folder = plugins_url('', dirname(__FILE__)).'/';
	
        /* 
        * Scripts
        */
        if(is_admin()) {
            //Admin Scripts
            wp_register_script('ticket-options',$plugin_folder.'js/ticket-options.js',array('jquery'),'1.0',false);
            wp_enqueue_script('ticket-options');
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
        } else {
            //Public Scripts
            wp_register_script('support',$plugin_folder.'js/support.js',array('jquery', 'hashchange'),'1.0',false);
            wp_register_script('hashchange', $plugin_folder.'js/hashchange.js',array('jquery'), '1.0',false);
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            wp_enqueue_script('support');
            wp_enqueue_script('hashchange');
        }
    }
	
	function dynamo_support_ticket_type_init() {
		global $plugin_folder, $support_options;
        $count = '<span class="count"><span class="ticket-count">' . number_format_i18n(dynamo_support_count_open()) . '</span></span>';
        $labels = array(
        'name' => _x('Tickets', 'post type general name'),
        'singular_name' => _x('Ticket', 'post type singular name'),
        'add_new' => _x('Add New', 'ticket'),
        'add_new_item' => __('Add New Question'),
        'edit_item' => __('Edit Question'),
        'new_item' => __('New Question'),
        'view_item' => __('View Question'),
        'search_items' => __('Search Questions'),
        'not_found' =>  __('No questions found'),
        'not_found_in_trash' => __('No questions found in Trash'), 
        'parent_item_colon' => '',
        'menu_name' => 'Support'

        );
        $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true, 
        'show_in_menu' => true, 
        'exclude_from_search' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'ticket',
        'capabilities' => array(
			'read' => 'read_tickets',
			'publish_posts' => 'publish_tickets',
			'edit_posts' => 'edit_tickets',
			'edit_others_posts' => 'edit_others_tickets',
			'delete_posts' => 'delete_tickets',
			'delete_others_posts' => 'delete_others_tickets',
			'read_private_posts' => 'read_private_tickets',
			'edit_post' => 'edit_ticket',
			'delete_post' => 'delete_ticket',
			'read_post' => 'read_ticket',
        ),
        'has_archive' => false, 
        'hierarchical' => false,
        'menu_position' => null,
        'description' => 'Questions for a support system, replies are comments.',
        'exclude_from_search' => true,
        'taxonomies' => array('support-topic'),
        'menu_icon' => $plugin_folder.'img/support-icon.png',
        'supports' => array('title','editor')
        ); 
		register_post_type('ticket',$args);
	}
	add_action('init','dynamo_support_ticket_type_init');
	
    /*
    * Add In Labels and Messages for Ticket post type
    */
    add_filter('post_updated_messages', 'dynamo_support_updated_messages');
    function dynamo_support_updated_messages( $messages ) {
        global $post, $post_ID;

        $messages['ticket'] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => sprintf( __('Question updated. <a href="%s">View question</a>'), esc_url( get_permalink($post_ID) ) ),
        2 => __('Custom field updated.'),
        3 => __('Custom field deleted.'),
        4 => __('Question updated.'),
        /* translators: %s: date and time of the revision */
        5 => isset($_GET['revision']) ? sprintf( __('Question restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __('Question published. <a href="%s">View question</a>'), esc_url( get_permalink($post_ID) ) ),
        7 => __('Question saved.'),
        8 => sprintf( __('Question submitted. <a target="_blank" href="%s">Preview question</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( __('Question scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview question</a>'),
        // translators: Publish box date format, see http://php.net/date
        date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
        10 => sprintf( __('Question draft updated. <a target="_blank" href="%s">Preview question</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        );

        return $messages;
    } 

    /*
    * Ticket View Page Set Up Columns
    */
    add_action("manage_posts_custom_column", "dynamo_support_custom_columns");
    add_filter("manage_edit-ticket_columns", "dynamo_support_ticket_columns");

    function dynamo_support_ticket_columns($columns) //this function display the columns headings
    {
        $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "date" => "Date",
        "assigned" => "Assigned",
        "title" => "Title",
        "topic" => "Topic",
        "creator" => "Author",
        "comments" => "#",
        "reply_date" => "Reply Date",
        "status" => "Status",
        );
        return $columns;
    }
    /*
    * Ticket View Page Columns Display Function
    */
    function dynamo_support_custom_columns($column)
    {
        global $post, $support_options;
        $servers = $support_options['servers'];
        if ("topic" == $column) {  echo '<b>'.get_post_meta($post->ID,'ticket_topic',true).'</b>'; if(get_post_meta($post->ID,'ticket_bug',true) == '1') { echo '<span class="ticket-bug"></span>'; } if(is_array($servers) && count($servers) > 1) { echo '<br/>['.get_post_meta($post->ID,'email_account',true).']'; }  }
        else if ("status" == $column) { $status = get_post_meta($post->ID,'ticket_status',true); if($status == '0') { echo '<b>Closed</b>'; } else if($status == '1') { echo '<b>Open</b>'; } else if($status == '2') { echo '<b>Answered</b>'; } }
        else if("reply_date" == $column) { echo dynamo_support_get_last_reply_date($post->ID);}
            else if ("creator" == $column) { $author = get_userdata($post->post_author); echo '<a href="edit.php?post_type=ticket&author='.$author->ID.'&ticket_status=all" title="View All Posts By This Author - Count (Current # of tickets in system by user | Total # of tickets ever submitted by user)">'.$author->display_name.' <span class="count">('.dynamo_support_get_user_total_tickets($author->ID).')</span></a>'; }
                else if ("assigned" == $column) { if(get_post_meta($post->ID,'no_ticket_assigned',true) == 'none') { echo '-'; } else if(get_post_meta($post->ID,'ticket_assigned',true) != '') { $ass = get_userdata(get_post_meta($post->ID,'ticket_assigned',true)); echo $ass->display_name;  } else { echo '-'; } }
    }

    /*
    * Integration expired check
    */
    function sd_expired_check() {
        global $support_options;

        if($support_options['integration'] != '') {

            $id = $_POST['id'];
            $post = get_post($id);
            if($support_options['integration'] == 'wishlist') {
                $levels = dynamo_support_get_user_wlm_levels($post->post_author);
                if(is_array($levels) && count($levels) > 0) {
                    $s = '1';
                    $i = 0;
                    foreach($levels as $k => $level) {
                        if($level['status'] != 'Active') {
                            $i++;
                            $s = '0';
                        }
                    }
                }
                if($i >= count($levels) && $s == '0') {
                    echo 'all';
                }
                if($s == '0') {
                    echo '| <span class="expired-product"></span>';
                }
            }

        }
        die();
    }

    add_action('wp_ajax_sd_expired_check','sd_expired_check');
    /* 
    * Set the Query Vars for Each of our Views: Open, Answeres, Closed, Author and All
    */
    function dynamo_support_sort_tickets( $vars ) {
    
        /* Check if we're viewing the 'ticket' post type. */
        if ( isset( $vars['post_type'] ) && 'ticket' == $vars['post_type'] ) {
            /* Merge the query vars with our custom variables. */  

            if(isset($_REQUEST['ticket_status']) && 'open' == $_REQUEST['ticket_status']) {
                //Open Tickets
                $vars = array_merge(
                $vars,
                array(
                'meta_key' => 'ticket_status',
                'meta_value' => '1',
                'order' => 'ASC',
                'orderby' => 'date'

                )
                );
            } else if(isset($_REQUEST['ticket_status']) && 'closed' == $_REQUEST['ticket_status']) {
                    //Closed Tickets
                    $vars = array_merge(
                    $vars,
                    array(
                    'meta_key' => 'ticket_status',
                    'meta_value' => '0',
                    'order' => 'ASC',
                    'orderby' => 'date',
                    'post_status' => 'publish'
                    )
                    );
                } else if(isset($_REQUEST['ticket_status']) && 'answered' == $_REQUEST['ticket_status']) {
                        //Answered Tickets
                        $vars = array_merge(
                        $vars,
                        array(
                        'meta_key' => 'ticket_status',
                        'meta_value' => '2',
                        'order' => 'ASC',
                        'orderby' => 'date',
                        'post_status' => 'publish'
                        )
                        );
                    } else if(isset($_REQUEST['ticket_status']) && 'all' == $_REQUEST['ticket_status']) {
                            $vars = $vars;

                        } else if(isset($_REQUEST['author']) && '' != $_REQUEST['author']) {
                                $vars = array_merge(
                                $vars,
                                array(
                                'author' => $_REQUEST['author'],
                                'order' => 'ASC',
                                'orderby' => 'date'
                                )
                                );
                            } /*else if(isset($_REQUEST['ticket_topic']) && '' != $_REQUEST['ticket_topic']) {
                            echo 'Hello';
                            $vars = array_merge(
                            $vars,
                            array(
                            'meta_key' => 'ticket_topic',
                            'meta_value' => str_replace('+',' ',$_REQUEST['ticket_topic']),
                            'order' => 'ASC',
                            'orderby' => 'date',
                            'post_status' => 'publish'
                            )
                            );
                            }*/ else if(isset($_REQUEST['post_status']) && 'draft' == $_REQUEST['post_status']) {
                                    $vars = array_merge(
                                    $vars,
                                    array(
                                    'post_status' => 'draft'
                                    )
                                    );
                                } else if(isset($_REQUEST['post_status']) && 'trash' == $_REQUEST['post_status']) {
                                        $vars = array_merge(
                                        $vars,
                                        array(
                                        'post_status' => 'trash'
                                        )
                                        );
                                    } else if(isset($_REQUEST['s'])) {
                                            $vars = $vars;
                                        } else {
                                            //Default Open
                                            $vars = array_merge(
                                            $vars,
                                            array(
                                            'meta_key' => 'ticket_status',
                                            'meta_value' => '1',
                                            'order' => 'ASC',
                                            'orderby' => 'date'

                                            )
                                            );
            }
        }
        // Check ticket topic (Meta key & value ) 03.02.14
        if(isset($_REQUEST['ticket_topic']) && '' != $_REQUEST['ticket_topic']) { 
            $vars = array_merge(
            $vars,
            array(
            'meta_key' => 'ticket_topic',
            'meta_value' => str_replace('+',' ',$_REQUEST['ticket_topic']),
            'order' => 'ASC',
            'orderby' => 'date',
            'post_status' => 'publish'
            )
            );
        }


        return $vars;
    }
    /*
    * Change the views menu for tickets to Open, Answered, Closed and All
    */
    function dynamo_support_views( $views ) {
        if(isset($_REQUEST['ticket_status']) && 'open' == $_REQUEST['ticket_status'] && !isset($_REQUEST['author']) && $_REQUEST['post_status'] != 'draft' && $_REQUEST['post_status'] != 'trash') {
            $openclass="current";
        }
        if(isset($_REQUEST['ticket_status']) && 'closed' == $_REQUEST['ticket_status'] && !isset($_REQUEST['author'])) {
            $closedclass="current";
        }
        if(isset($_REQUEST['ticket_status']) && 'answered' == $_REQUEST['ticket_status'] && !isset($_REQUEST['author'])) {
            $answeredclass="current";
        }
        if(isset($_REQUEST['ticket_status']) && 'all' == $_REQUEST['ticket_status']) {
            $allclass="current";
        }
        if(!isset($_REQUEST['ticket_status']) && '' == $_REQUEST['ticket_status'] && !isset($_REQUEST['author']) && !isset($_REQUEST['action']) && $_REQUEST['post_status'] != 'draft' && $_REQUEST['post_status'] != 'trash' ) {
            $openclass="current";
        }
        if($_REQUEST['ticket_status'] == '') {
            $openclass="current";
        }	
        /*
        if(isset($_REQUEST['action']) && '-1' == $_REQUEST['action']) {
        $allclass="current";
        }
        */
        if(isset($_REQUEST['author']) && '' != $_REQUEST['author']) {
            $allclass="current";
        }
        if(isset($_REQUEST['assign']) && '' != $_REQUEST['assign']) {
            $url = '&assign='.$_REQUEST['assign'].'';
        }
        $views = array_merge(
        array(
        'open' => '<a href="'.get_bloginfo('wpurl').'/wp-admin/edit.php?post_type=ticket'.$url.'" class="'.$openclass.'">Open <span class="count">('.dynamo_support_count_open().')</span></a>',
        'answered' => '<a href="'.get_bloginfo('wpurl').'/wp-admin/edit.php?post_type=ticket&ticket_status=answered'.$url.'" class="'.$answeredclass.'">Answered <span class="count">('.dynamo_support_count_answered().')</span></a>',
        'close' => '<a href="'.get_bloginfo('wpurl').'/wp-admin/edit.php?post_type=ticket&ticket_status=closed'.$url.'" class="'.$closedclass.'">Closed <span class="count">('.dynamo_support_count_closed().')</span></a>',
        'all2' => '<a href="'.get_bloginfo('wpurl').'/wp-admin/edit.php?post_type=ticket&ticket_status=all'.$url.'" class="'.$allclass.'">All <span class="count">('.dynamo_support_count_all().')</span></a>'
        ),
        $views
        );
        unset($views['publish']);
        unset($views['all']);
        return $views;
    }
    /* Only run our customization on the 'edit.php' page in the admin. */
    add_action( 'load-edit.php', 'dynamo_support_edit_ticket_load' );

    /*
    * Add all filters and hooks for view ticket customizations
    */
    function dynamo_support_edit_ticket_load() {
        add_filter( 'request', 'dynamo_support_sort_tickets' );
        add_filter( 'request', 'dynamo_support_ticket_assignments' );
        add_filter( 'views_edit-ticket', 'dynamo_support_views');
        add_action('restrict_manage_posts','dynamo_support_sort_by_topic');
        add_action('restrict_manage_posts','dynamo_support_view_assignment');
    }

    /*
    * View Assignment Links
    */
    function dynamo_support_view_assignment($query_vars) {
        global $current_user;
        $capabilities = $current_user->allcaps;
        if ( isset( $_REQUEST['post_type'] ) && 'ticket' == $_REQUEST['post_type'] ) {
            if(is_array($capabilities)) {
                $assign = $_GET['assign'];
                foreach($capabilities as $k => $v) {
                    if($k == 'view_all_tickets') {
                    ?>
                    <select name="assign">
                        <option value="all" <?php if($assign == 'all' || $assign == '') { echo 'selected="selected"'; }?>>Show All Tickets</option>
                        <option value="own" <?php if($assign == 'own') { echo 'selected="selected"'; }?>>Show Only Tickets Assigned To You</option>
                        <option value="unassigned" <?php if($assign == 'unassigned') { echo 'selected="selected"'; }?>>Show Unassigned Tickets Only</option>
                    </select>

                    <?php
                    } else if ($k == 'view_own_tickets') {
                        ?>
                        <select name="assign">
                            <option value="own" <?php if($assign == 'own' || $assign == '') { echo 'selected="selected"'; }?>>Show Only Tickets Assigned To You</option>
                    </select>

                    <?php
                    } else if ($k == 'view_unassigned_tickets') {
                        ?>
                        <select name="assign">
                            <option value="own" <?php if($assign == 'own' || $assign == '') { echo 'selected="selected"'; }?>>Show Only Tickets Assigned To You</option>
                        <option value="unassigned" <?php if($assign == 'unassigned') { echo 'selected="selected"'; }?>>Show Unassigned Tickets Only</option>
                    </select>

                    <?php
                    }
                }
            }
        ?>
        <input type="hidden" name="ticket_status" value="<?php if(isset($_GET['ticket_status'])) { echo $_GET['ticket_status']; } else { echo 'open'; } ?>"/>
        <?php
        }
    }

    /*
    * Create SQL For Ticket Assignments
    */
    function dynamo_support_ticket_assignments($request) {
        global $current_user, $pagenow;

        if(isset($request['meta_key']) && isset($request['meta_value'])) {
            $key = $request['meta_key'];
            $val = $request['meta_value'];

        }


        if ( is_admin() && $pagenow=='edit.php' && isset($_GET['assign']) && $_GET['assign'] != '' && isset( $request['post_type'] ) && 'ticket' == $request['post_type']) {


            $k = $_GET['assign'];

            if($k == 'all') {


            } else if ($k == 'own') {

                    $request['meta_query'] = 
                    array(	
                    array(
                    'key' => 'ticket_assigned',
                    'value' => $current_user->ID,
                    'compare' => '='
                    ),
                    array(
                    'key' => $key,
                    'value' => $val	
                    )
                    );


                    unset($request['meta_key'], $request['meta_value']);
                } else if ($k == 'unassigned') {


                        $request['meta_query'] = 
                        array(	
                        array(
                        'key' => 'no_ticket_assigned',
                        'compare' => 'EXISTS'
                        ),
                        array(
                        'key' => $key,
                        'value' => $val	
                        )
                        );
                        unset($request['meta_key'], $request['meta_value']);		
                    } 
        } else if(is_admin() && $pagenow =='edit.php' && !isset($_GET['assign']) && isset( $request['post_type'] ) && 'ticket' == $request['post_type']) {

                //Default View
                $capabilities = $current_user->allcaps;
                foreach($capabilities as $o => $v) {
                    if($o == 'view_all_tickets') {

                    } else if ($o == 'view_own_tickets') {
                            $request['meta_query'] = 
                            array(
                            array(
                            'key' => 'ticket_assigned',
                            'value' => $current_user->ID,
                            'compare' => '='
                            ),
                            array(
                            'key' => $key,
                            'value' => $val	
                            )
                            );
                            unset($request['meta_key'], $request['meta_value']);			
                        } else if($o == 'view_unassigned_tickets') {

                                $request['meta_query'] = 
                                array(	
                                array(
                                'key' => 'ticket_assigned',
                                'value' => $current_user->ID,
                                'compare' => '='
                                ),
                                array(
                                'key' => $key,
                                'value' => $val	
                                )
                                );
                                unset($request['meta_key'], $request['meta_value']);			
                            }
            }
        }  
        return $request;

    }

    /*
    * Creates SQL for Topic Specific Queries
    */
    function dynamo_support_topics_filter($query_vars) { 
        if(!is_array($query_vars)) return false;
        foreach($query_vars as $array) {
            if($array['key'] == 'ticket_topic') {
                $topics = $array;
                break;
            }
        }
        if(is_array($topics) && !empty($topics)) {
            foreach($topics['value'] as $k => $t) {
                $tops .= "'$t',";
            }
            $tops = substr($tops,0,-1);
            $sql =" AND meta2.meta_key = 'ticket_topic' AND meta2.meta_value IN ($tops)";
        } else {
            $sql = '';
        }
        return $sql;
    }
    /*
    Count Open Before WP_QUERY is initiated
    */
    function dynamo_support_manual_count($status = 'open') {
        global $current_user, $wpdb, $support_options;
		
		$sql = '';
		
        get_currentuserinfo();
        if($status == 'open') {
            $val = '1';
        }
        if($status == 'closed') {
            $val = '0';
        }
        if($status == 'answered') {
            $val = '2';
        }
        //print_r($current_user);
        $userRole = $current_user->caps;
        //TO DO BACKWARDS COMP
        //$userRole = $current_user->data->wp_capabilities;
        if(is_Array($userRole)) {

            $role = key($userRole);
			if(isset($support_options['roles'][$role]['capabilities'])) {
				$capabilities = $support_options['roles'][$role]['capabilities'];
			} else {
				$capabilities = '';
			}
            if(is_array($capabilities)) {
                foreach($capabilities as $k => $v) {
                    if($k != 'access_tickets' && $k != 'access_knowledgebase') {
                        $topics[] = $support_options['topics'][str_replace('access_tickets_','',$k)]['name'];
                    }
                }
                if(is_array($topics) && $topics[0] != '') {
                    foreach($topics as $k => $t) {
                        $tops .= "'$t',";
                    }
                    $tops = substr($tops,0,-1);
                    $sql =" AND meta2.meta_key = 'ticket_topic' AND meta2.meta_value IN ($tops)";
                }
            }
        }
        $capabilities = $current_user->allcaps;
        if(is_array($capabilities)) {
            foreach($capabilities as $o => $v) {
                if($o == 'view_all_tickets') {
                    if(isset($_GET['assign']) && $_GET['assign'] == 'unassigned') {
                        $assign = " AND meta3.meta_key = 'no_ticket_assigned' AND meta3.meta_value = 'none'";	
                    }
                    if(isset($_GET['assign']) &&  $_GET['assign'] == 'own') {
                        $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                    }
                } else if ($o == 'view_own_tickets') {
                        if(isset($_GET['assign']) &&  $_GET['assign'] == '' || $_GET['assign'] == 'own') {
                            $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                        }
                } else if($o == 'view_unassigned_tickets') {
                    if(isset($_GET['assign']) &&  $_GET['assign'] == 'unassigned') {
                        $assign = " AND meta3.meta_key = 'no_ticket_assigned' AND meta3.meta_value = 'none'";				
                    } else {
                        $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                    }
                } else {
					$assign = '';
				}
            }
        }
        $posts_table = $wpdb->prefix.'posts';
        $meta_table = $wpdb->prefix.'postmeta';

        if($status != 'all') {

            $tickets = $wpdb->get_results("SELECT $posts_table.ID FROM $posts_table INNER JOIN $meta_table ON ($posts_table.ID = $meta_table.post_id) INNER JOIN $meta_table AS meta2 ON ($posts_table.ID = meta2.post_id) INNER JOIN $meta_table AS meta3 ON ($posts_table.ID = meta3.post_id) WHERE 1=1 AND  $meta_table.meta_key = 'ticket_status' AND $meta_table.meta_value = '$val' and $posts_table.post_type = 'ticket' $sql $assign GROUP BY $posts_table.ID");

        } else {

            $tickets = $wpdb->get_results("SELECT $posts_table.ID FROM $posts_table INNER JOIN $meta_table ON ($posts_table.ID = $meta_table.post_id) INNER JOIN $meta_table AS meta2 ON ($posts_table.ID = meta2.post_id) INNER JOIN $meta_table AS meta3 ON ($posts_table.ID = meta3.post_id) WHERE 1=1 AND $posts_table.post_parent = '0' AND $posts_table.post_type = 'ticket' A $sql $assign GROUP BY $posts_table.ID");

        }

        return count($tickets);


    }

    /*
    * Count Open Tickets
    */
    function dynamo_support_count_open() {
        global $wpdb, $wp_query, $current_user;
		if(isset($wp_query->query_vars['meta_query']) && $wp_query->query_vars['meta_query'] != '') {
			$a = $wp_query->query_vars['meta_query'];
		} else {
			$a = '';
		}
		$topics = dynamo_support_topics_filter($a);
		
        $capabilities = $current_user->allcaps;
        $posts_table = $wpdb->prefix.'posts';
        $meta_table = $wpdb->prefix.'postmeta';
        foreach($capabilities as $o => $v) {
            if($o == 'view_all_tickets') {
                if(isset($_GET['assign']) && $_GET['assign'] == 'unassigned') {
                    $assign = " AND meta3.meta_key = 'no_ticket_assigned' AND meta3.meta_value = 'none'";	
                }
                if(isset($_GET['assign']) && $_GET['assign'] == 'own') {
                    $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                }
            } else if ($o == 'view_own_tickets') {
                    if($_GET['assign'] == '' || $_GET['assign'] == 'own') {
                        $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                    }
            } else if($o == 'view_unassigned_tickets') {
                if($_GET['assign'] == 'unassigned') {
                    $assign = " AND meta3.meta_key = 'no_ticket_assigned' AND meta3.meta_value = 'none'";				
                } else {
                    $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                }
            } else {
				$assign = '';
			}
        }



        $tickets = $wpdb->get_results("SELECT $posts_table.ID FROM $posts_table INNER JOIN $meta_table ON ($posts_table.ID = $meta_table.post_id) INNER JOIN $meta_table AS meta2 ON ($posts_table.ID = meta2.post_id) INNER JOIN $meta_table AS meta3 ON ($posts_table.ID = meta3.post_id) WHERE 1=1 AND $meta_table.meta_key = 'ticket_status' AND $meta_table.meta_value = '1' AND $posts_table.post_type = 'ticket' $topics $assign GROUP BY $posts_table.ID");
        return count($tickets);
    }
    /*
    * Count Closed Tickets
    */
    function dynamo_support_count_closed() {
        global $wpdb, $wp_query, $current_user;
        $topics = dynamo_support_topics_filter($wp_query->query_vars['meta_query']);
        $capabilities = $current_user->allcaps;
        $posts_table = $wpdb->prefix.'posts';
        $meta_table = $wpdb->prefix.'postmeta';
        foreach($capabilities as $o => $v) {
            if($o == 'view_all_tickets') {
                if($_GET['assign'] == 'unassigned') {
                    $assign = " AND meta3.meta_key = 'no_ticket_assigned' AND meta3.meta_value = 'none'";	
                }
                if($_GET['assign'] == 'own') {
                    $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                }
            } else if ($o == 'view_own_tickets') {
                    if($_GET['assign'] == '' || $_GET['assign'] == 'own') {
                        $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                    }
            } else if($o == 'view_unassigned_tickets') {
                    if($_GET['assign'] == 'unassigned') {
                        $assign = " AND meta3.meta_key = 'no_ticket_assigned' AND meta3.meta_value = 'none'";				
                    } else {
                        $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                    }
                }
        }



        $tickets = $wpdb->get_results("SELECT $posts_table.ID FROM $posts_table INNER JOIN $meta_table ON ($posts_table.ID = $meta_table.post_id) INNER JOIN $meta_table AS meta2 ON ($posts_table.ID = meta2.post_id) INNER JOIN $meta_table AS meta3 ON ($posts_table.ID = meta3.post_id) WHERE 1=1 AND  $meta_table.meta_key = 'ticket_status' AND $meta_table.meta_value = '0' AND $posts_table.post_type = 'ticket' $topics $assign GROUP BY $posts_table.ID");
        return count($tickets);
    }
    /*
    * Count Answered Tickets
    */
    function dynamo_support_count_answered() {
        global $wpdb, $wp_query, $current_user;
        $topics = dynamo_support_topics_filter($wp_query->query_vars['meta_query']);
        $capabilities = $current_user->allcaps;
        $posts_table = $wpdb->prefix.'posts';
        $meta_table = $wpdb->prefix.'postmeta';
        foreach($capabilities as $o => $v) {
            if($o == 'view_all_tickets') {

                if($_GET['assign'] == 'unassigned') {
                    $assign = " AND meta3.meta_key = 'no_ticket_assigned' AND meta3.meta_value = 'none'";	
                }
                if($_GET['assign'] == 'own') {

                    $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                }
            } else if ($o == 'view_own_tickets') {
                    if($_GET['assign'] == '' || $_GET['assign'] == 'own') {
                        $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                    }
            } else if($o == 'view_unassigned_tickets') {
                    if($_GET['assign'] == 'unassigned') {
                        $assign = " AND meta3.meta_key = 'no_ticket_assigned' AND meta3.meta_value = 'none'";				
                    } else {
                        $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                    }
                }
        }

        $tickets = $wpdb->get_results("SELECT $posts_table.ID FROM $posts_table INNER JOIN $meta_table ON ($posts_table.ID = $meta_table.post_id) INNER JOIN $meta_table AS meta2 ON ($posts_table.ID = meta2.post_id) INNER JOIN $meta_table AS meta3 ON ($posts_table.ID = meta3.post_id) WHERE 1=1 AND  $meta_table.meta_key = 'ticket_status' AND $meta_table.meta_value = '2' AND $posts_table.post_type = 'ticket' $topics $assign GROUP BY $posts_table.ID");
        return count($tickets);
    }
    /*
    * Count All Tickets
    */
    function dynamo_support_count_all() {
        global $wpdb, $wp_query, $current_user;
        $topics = dynamo_support_topics_filter($wp_query->query_vars['meta_query']);
        $capabilities = $current_user->allcaps;
        $posts_table = $wpdb->prefix.'posts';
        $meta_table = $wpdb->prefix.'postmeta';
        foreach($capabilities as $o => $v) {
            if($o == 'view_all_tickets') {
                if($_GET['assign'] == 'unassigned') {
                    $assign = " AND meta3.meta_key = 'no_ticket_assigned' AND meta3.meta_value = 'none'";	
                }
                if($_GET['assign'] == 'own') {
                    $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                }
            } else if ($o == 'view_own_tickets') {
                    if($_GET['assign'] == '' || $_GET['assign'] == 'own') {
                        $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                    }
            } else if($o == 'view_unassigned_tickets') {
                    if($_GET['assign'] == 'unassigned') {
                        $assign = " AND meta3.meta_key = 'no_ticket_assigned' AND meta3.meta_value = 'none'";				
                    } else {
                        $assign = " AND meta3.meta_key = 'ticket_assigned' AND meta3.meta_value = '".$current_user->ID."'";
                    }
                }
        }

        $tickets = $wpdb->get_results("SELECT $posts_table.ID FROM $posts_table INNER JOIN $meta_table ON ($posts_table.ID = $meta_table.post_id) INNER JOIN $meta_table AS meta2 ON ($posts_table.ID = meta2.post_id) INNER JOIN $meta_table AS meta3 ON ($posts_table.ID = meta3.post_id) WHERE 1=1 AND $posts_table.post_parent = '0' AND $posts_table.post_type = 'ticket' $topics $assign GROUP BY $posts_table.ID");
        return count($tickets);
    }
    /*
    * Get Last Reply Date Of Ticket
    */
    function dynamo_support_get_last_reply_date($post_id) {
        global $wpdb;
        $table = $wpdb->prefix.'comments';
        $date = $wpdb->get_var("SELECT comment_date FROM $table WHERE comment_post_id = '$post_id' ORDER BY comment_date DESC LIMIT 0,1");
        if(!$date) {
            return '-';
        }
        $date = explode(' ',$date);
        return $date[0].'<br/>'.$date[1];
    }
    /*
    * Get last person who replied
    */
    function dynamo_support_get_last_reply_author($post_id) {
        global $wpdb;
        $table = $wpdb->prefix.'comments';
        $author = $wpdb->get_var("SELECT user_id FROM $table WHERE comment_post_id = '$post_id' ORDER BY comment_date DESC LIMIT 0,1");
        if($author != 0) {
            $author = get_userdata($author);
            return $author->display_name;
        } else if(!$author) {
                return '-';
            }
    }
    /*
    * Count Users Total # Of Open/Closed/Answered Tickets From Count (Current/Total) 
    */
    function dynamo_support_get_user_total_tickets($user_id) {
        global $wpdb;
        $table = $wpdb->prefix.'posts';
        $posts = $wpdb->get_results("SELECT DISTINCT id FROM $table WHERE post_author ='$user_id' AND post_type = 'ticket' AND post_parent = '0'");
        return count($posts).'/'.get_user_meta($user_id,'ticket_count',true);
    }

    /*
    * Sort By Topic Selection
    */
    function dynamo_support_sort_by_topic() {
        global $wpdb, $support_options, $wp_roles;
        if ( isset( $_REQUEST['post_type'] ) && 'ticket' == $_REQUEST['post_type'] ) {
            $topics = $support_options['topics'];
            $topics[] = array( 'name' => 'Bug Report', 'source' => 'sd');
            echo '<select id="topic-sort" name="ticket_topic"><option value="">Show all topics</option>';


            if(is_array($topics) && !empty($topics)) {
                $limited = 0;
                foreach($topics as $k => $t) {
                    if(current_user_can('access_tickets_'.$k.'')) {
                        $limited = 1;
                    }
                }

                foreach($topics as $k => $t) {
                    if($limited == 1) {
                        if(current_user_can('access_tickets_'.$k.'') && current_user_can('access_tickets')) {

                            if(str_replace('+',' ',$_GET['ticket_topic']) == $t['name']) { $selected = 'selected="selected"'; }

                            echo '<option value="'.$t['name'].'" '.$selected.'>'.$t['name'].'</option>';

                            unset($selected);
                        }
                    } else {

                        if(str_replace('+',' ',$_GET['ticket_topic']) == $t['name']) { $selected = 'selected="selected"'; }

                        echo '<option value="'.$t['name'].'" '.$selected.'>'.$t['name'].'</option>';

                        unset($selected);

                    }
                }
            }
            echo '</select>';
        }
    }



    /*
    * Add Notes Row Action
    */
    add_filter('post_row_actions','dynamo_support_view_notes', 10, 2);
    function dynamo_support_view_notes($actions, $post) {
        global $current_user;
        //Check for ticket post type
        if($post->post_type == 'ticket') {
            unset($actions['inline hide-if-no-js']);
            $actions['view'] = '<a href="'.get_permalink($post->ID).'" target="_blank" title="Reply to this question">Reply</a>';
            $actions['quickview'] = '<a href="#'.$post->ID.'" class="quick-view" rel="'.$post->ID.'" title="Quick view this question">Quick View</a>';
            $actions['notes'] = '<a class="view-ticket-notes" href="'.get_bloginfo('wpurl').'/wp-content/plugins/dynamo-support/includes/dynamo-support-notes.php?id='.$post->ID.'&amp;user='.$current_user->ID.'&amp;author='.$post->post_author.'&amp;TB_iframe=true" title="View or Add Ticket Notes (Ticket Notes / User Notes)" rel="'.$post->ID.'"/>Notes <span class="count">('.dynamo_support_count_notes($post->ID).'/'.dynamo_support_count_user_notes($post->post_author).')</span></a>';

            if(get_post_meta($post->ID,'ticket_status',true) != '0') {
                $actions['status'] = '<a class="ticket-status" href="#'.$post->ID.'" rel="'.$post->ID.'" title="Close ticket">Close Question</a>';
            } else {
                $actions['status'] = '<a class="ticket-status" href="#'.$post->ID.'" rel="'.$post->ID.'" title="Re-open ticket">Open Question</a>';
            }

        }
        return $actions;
    }

    /*
    * Open / Close Ticket
    */
    function dynamo_support_open_close_ticket() {
        $id = $_POST['id'];
        $status = get_post_meta($id,'ticket_status',true);
        if($status == '0') {
            update_post_meta($id,'ticket_status','1');
            echo 'opened';
        } else {
            update_post_meta($id,'ticket_status','0');
            echo 'closed';
        }
        die();
    }
    add_action('wp_ajax_dynamo_support_open_close_ticket','dynamo_support_open_close_ticket');

    /*
    * Get Note Count
    */
    function dynamo_support_count_notes($post_id) {
        $notes = get_post_meta($post_id,'ticket_notes',true);
        if(is_array($notes) && !empty($notes)) {
            return count($notes);
        } else {
            return '0';
        }
    }

    function dynamo_support_count_user_notes($user_id) {
        $notes = get_user_meta($user_id,'user_notes',true);
        if(is_array($notes) && !empty($notes)) {
            return count($notes);
        } else {
            return '0';
        }
    }

    /*
    * Updated Search To Include Id's
    */
    add_action( 'parse_request', 'dynamo_support_idsearch' );
    function dynamo_support_idsearch( $wp ) {
        global $pagenow;

        // If it's not the post listing return
        if( 'edit.php' != $pagenow )
            return;

        // If it's not a search return
        if( !isset( $wp->query_vars['s'] ) )
            return;

        // If it's a search but there's no prefix, return
        if( '#' != substr( $wp->query_vars['s'], 0, 1 ) )
            return;

        // Validate the numeric value
        $id = absint( substr( $wp->query_vars['s'], 1 ) );
        if( !$id )
            return; // Return if no ID, absint returns 0 for invalid values

        // If we reach here, all criteria is fulfilled, unset search and select by ID instead
        unset( $wp->query_vars['s'], $_GET['s'] );
        $wp->query_vars['p'] = $id;
    }

    /*
    * Quick View
    */
    function dynamo_support_quick_view() {
        global $wpdb, $support_options;  
        $id = $_POST['id'];
        $post = get_post($id);
        $author = get_userdata($post->post_author);
        $sql = "SELECT comment_ID, comment_author_email, comment_author, comment_date, comment_date_gmt, comment_approved, comment_content FROM $wpdb->comments WHERE comment_approved = '1' AND comment_post_ID = '$id' ORDER BY comment_date_gmt ASC";  
        $comments = $wpdb->get_results($sql);  
        echo '<div class="quickview-container metabox-holder">';
        echo '<div style="float:right; width:64%;" class="qv-ticket"><div class="original-ticket postbox" style="margin-bottom:5px;"><h3 class="hndle"><span>'.get_avatar($post->post_author, '16' ).' Original Question By: '.$author->display_name.' <span style="float:right; font-size:13px;">'.$post->post_date.'</span></span></h3><div class="inside"><p>'."".$post->post_content."".'</p></div></div>';
        if(is_array($comments) && !empty($comments)) {
            foreach($comments as $c) {
                echo '<div class="reply postbox" style="margin-bottom:5px;"><h3 class="hndle"><span>'.get_avatar($c->comment_author_email, '16' ).' Reply By: '. get_comment_author_link($c->comment_ID).'  <span style="float:right; font-size:13px;">'.$c->comment_date.'</span></span></h3><div class="inside"><p>'."".$c->comment_content."".'</p></div></div>';
            }
        }
        echo '</div>';
        echo '<div style="float:left; width:35%;" class="qv-stats"><div class="postbox" style="margin-bottom:5px;"><h3 class="hndl"><span>Question Details</span> </h3><div class="inside"><p>';

        echo'<strong>Question #'.$post->ID.' Created By:</strong> <a target="_blank" href="'.get_bloginfo('wpurl').'/wp-admin//user-edit.php?user_id='.$author->ID.'" title="Edit This User">'.$author->first_name.' '.$author->last_name.'</a> <span class="ticket_count"><a href="'.get_bloginfo('wpurl').'/wp-admin/edit.php?post_type=ticket&author='.$author->ID.'" title="View All Tickets By This User - Count (Current # of tickets in system by user | Total # of tickets ever submitted by user)">('.dynamo_support_get_user_total_tickets($author->ID).')</a></span>';
        if ($support_options['plugin_version'] == '==AUWVUeZhFZzFWMaNVTWJVU') {
            echo '<a href="#" class="user-datacard" rel="'.$author->ID.'"></a>';
        }
        if ($support_options['plugin_version'] == '==AUWVUeZhFZzFWMaNVTWJVU') {
            if($support_options['integration'] != '') {
                echo '<span class="user-spending" rel="'.$author->ID.'"></span>';
            }
        }
        echo '<br/>
        <strong>E-Mail:</strong> '.$author->user_email.'
        <br/>
        <strong>Member Since:</strong> '.date('M jS, Y',strtotime(substr($author->user_registered,0,10))).'
        <br/>
        <strong>Publish Date:</strong> '.date('m/d/Y - H:m:s',strtotime($post->post_date)).'
        <br/>
        <strong>Topic:</strong> '.get_post_meta($post->ID,'ticket_topic', true).' '.sd_pr_integration($author->ID).'
        </p></div></div>

        <div class="postbox" style="margin-bottom:5px;"><h3 class="hndle"><span>Quick Reply</span>';
        if ($support_options['plugin_version'] == '==AUWVUeZhFZzFWMaNVTWJVU') {
            $responses = $support_options['response'];
            if(is_array($responses) && !empty($responses)){
                echo '<select name="response" class="auto-response" rel="'.$post->ID.'"><option value="">-- Select Response --</option>';
                foreach($responses as $k => $array) {
                    echo'<option value="'.$k.'">'.$array['title'].'</option>';
                }
                echo '</select>';
            }
        }
        echo '</h3>
        <div class="inside">
        <p><textarea class="quick-reply" rel="'.$post->ID.'" style="width:100%; margin:0; height:100px;"></textarea></p>
        </div>
        </div>';
        echo '<input type="button" class="close-quickview" value="X Close Quick View" rel="'.$post->ID.'" "/><input type="button" value="Reply To Ticket &#187;" class="quick-reply-btn" style="float:right;" rel="'.$post->ID.'"/><span style="display:block; width:100px; font-size:10px; float:right;"><input type="checkbox" name="close-on-reply" class="close-on-reply" value="1"/> Close on reply</span></div><div class="clear"></div>';
        die();
    }
    add_action('wp_ajax_dynamo_support_quick_view','dynamo_support_quick_view');

    function dynamo_support_quick_reply() {
        global $current_user;
        $id = $_REQUEST['id'];
        $text = $_REQUEST['text'];
        $data = array(
        'comment_post_ID' => $id,
        'comment_author' => $current_user->display_name,
        'comment_author_email' => $current_user->user_email,
        'comment_content' => nl2br(htmlspecialchars($text)),
        'comment_parent' => '0',
        'user_id' => $current_user->ID,
        'comment_approved' => 1,
        );
        $cid = wp_new_comment($data);
        $c = get_comment($cid);

        //Check Post Status for e-mailed in new tickets
        $post = get_post($id);
        if($post->post_status === 'draft') {
            //if is draft and admin replies publish it for user reply.
            wp_publish_post($id);
        }

        if($_REQUEST['close'] == '1') {
            update_post_meta($id,'ticket_status','0');
        }

        //Check Ticket Assignment
        $assign = get_post_meta($id,'ticket_assigned',true);
        if(isset($assign) && is_numeric($assign)) {   
            
            /**
             * Re-assign that ticket to the user who last replied
             * Check if this assignment is lock to one user
             * 
             * @author Ian Tumulak <edden87@gmail.com>
             */   
            
            $lock = get_post_meta($id, 'ticket_assign_lock', true);
            
            if(empty($lock))
                update_post_meta($id,'ticket_assigned',$current_user->ID);
            
        } else {
            update_post_meta($id,'ticket_assigned',$current_user->ID);
            delete_post_meta($id, 'no_ticket_assigned','none');
        }


        echo '<h3 class="hndle"><span>'.get_avatar($c->comment_author_email, '16' ).' Reply By: '. get_comment_author_link($c->comment_ID).'  <span style="float:right; font-size:13px;">'.$c->comment_date.'</span></span></h3><div class="inside"><p>'.$c->comment_content.'</p></div>';
        die();
    }
    add_action('wp_ajax_dynamo_support_quick_reply','dynamo_support_quick_reply');

    /*
    * Generate the user spend card
    */
    function dynamo_support_user_spendcard() {
        global $support_options;
        $userid =  $_POST['val'];
        $authordata = get_userdata($userid);
        if($support_options['integration'] != '') {
            if($support_options['integration'] == 'wishlist') {
                echo dynamo_support_wlm_price_calc($authordata);
            } else if($support_options['integration'] == 'memberwingx') {
                    echo dynamo_support_mwx_price_calc($authordata);
                } else if($support_options['integration'] == 'cart66') {
                        echo dynamo_support_c66_price_calc($authordata);
                    }
        } 
        if($support_options['integration'] != '' && $total != '') {
            echo'<strong>Total Value:</strong> $'.$total.'';
        }
        die();
    }
    add_action('wp_ajax_dynamo_support_user_spendcard','dynamo_support_user_spendcard');

    /*
    * Integration with product registration plugin created for customer
    */
    function sd_pr_integration($userID) {
        if(is_plugin_active('product-registration/product-registration.php')) {
            global $wpdb;
            $products = unserialize($wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta WHERE user_id = '$userID' AND meta_key = 'pr_product'"));
            if(is_array($products) && count($products) > 0){
                $count = '('.count($products).')';
                $r .='<a class="pr_products" href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=product_registration&view=registration&user='.$userID.'" target="_blank">View Products'.$count.'</a>';
                return $r;
            }
        }
        return;
    }

    //Block Tickets From Recent Comments Widgets
    add_filter('widget_comments_args','pd_widget_comments_args',10,1);
    function pd_widget_comments_args($args) {
        $args['post_type'] = array('post','page','knowledgebase');
        return $args;
    }

    //Remove Next/Prev Page Links
    function pd_pagenum_links($links) {
        global $post;
        if($post->post_type == 'ticket') {
            return;
        }
        return $links;
    }
    add_filter('next_post_link', 'pd_pagenum_links');
    add_filter('previous_post_link', 'pd_pagenum_links');
?>