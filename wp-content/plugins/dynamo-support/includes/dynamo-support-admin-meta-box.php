<?php
/*
* Dynamo Support Admin Meta Box Functions
*/

add_action('add_meta_boxes' ,'dynamo_support_add_meta_box');
add_action('admin_init', 'dynamo_support_add_meta_box');

function dynamo_support_add_meta_box() {
	add_meta_box("ticket-meta", "Ticket Options", "dynamo_support_ticket_meta_box", "ticket", "side", "high");
}

/*
* Ticket Settings Meta Box on edit / add ticket page
*/
function dynamo_support_ticket_meta_box() {
	global $post;
	$status = get_post_meta($post->ID, 'ticket_status', true);

	// Verify
	echo'<input type="hidden" name="dynamo_support_noncename" id="dynamo_support_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';

	// Fields for data entry
  	echo '<div class="misc-pub-section"><label for="ticket_status" style="margin-right: 10px;">Status:</label>';
  	echo '<select name="ticket_status" id="ticket_status">
			<option value="1"';
			if($status === '1') { echo 'selected="selected"'; }
			echo '>Open</option>';
			echo '<option value="0"';
			if($status === '0') { echo 'selected="selected"'; }
			echo '>Closed</option>';
			echo'<option value="2"';
			if($status === '2') { echo 'selected="selected"'; }
			echo '>Answered</option>';
	
	echo '</select></div>';
	echo '<div class="misc-pub-section"><label for="ticket_topic" style="margin-right: 10px;">Topic:</label><select name="ticket_topic" id="ticket_topic">';
	
	$topics = dynamo_support_get_topics(true);
	foreach($topics as $t) {					
		echo '<option value="'.$t->name.'"';
			if(get_post_meta($post->ID,'ticket_topic',true) === $t->name) { echo ' selected="selected"'; }
		echo '>'.$t->name.'</option>';						
	}
	echo '</select></div>';
        
        /**
         * List our Users to be manually assigned
         */
        echo '<div class="misc-pub-section"><label for="ticket_assigned" style="margin-right: 10px;">Assign to:</label><select name="ticket_assigned" id="ticket_assigned">';
        $support_users = dynamo_support_fetch_all_user_support();
        echo '<option value="">---</option>';
        foreach ($support_users as $user) {            
            echo '<option value="'.$user->ID.'"';
                if(get_post_meta($post->ID,'ticket_assigned',true) === $user->ID) { echo ' selected="selected"'; }
            echo '>'.$user->display_name.'</option>';	
        }
        echo '</select></div>';
        
        /**
         * Check options locking assignments
         */
        echo '<div class="misc-pub-section"><label for="ticket_assign_lock" style="margin-right: 10px;">Keep it assign to this user.</label> <input ';
            if(get_post_meta($post->ID,'ticket_assign_lock',true) == true) { echo ' checked="checked"'; };
        echo 'style="margin-top: 3px;" type="checkbox" name="ticket_assign_lock" id="ticket_assign_lock" value="true"></div>';
        
	$author = get_userdata($post->post_author);
	echo '<div class="misc-pub-section"><label for="post_author_override" style="margin-right:10px;">Author:</label> <input type="hidden" id="post_author_override" name="post_author_override" value="'.$post->post_author.'"/><input type="text" id="author-search" name="author-search" style="width:70%;" value="'.$author->display_name .'"/></select></div>';
	echo '<div class="misc-pub-section"><input class="button button-highlighted" type="button" id="add-to-knowledgebase" value="Add To Knowledge base &#187;" rel="'.$post->ID.'"/><br/><br/><span id="add-to-know-notice"></span></div>';
}

/*
* Save Ticket Options Widget Data on Post Update/Publish
*/
 add_action('save_post', 'dynamo_support_save_post_data');
 function dynamo_support_save_post_data( $post_id ) {
		global $post, $wpdb;;

		// Verify
		if ( !wp_verify_nonce( $_POST["dynamo_support_noncename"], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ))
				return $post_id;
		} else {
			if ( !current_user_can( 'edit_post', $post_id ))
				return $post_id;
		}

		// Update Topic
		update_post_meta($post_id,'ticket_topic',$_POST['ticket_topic']);
                
                // Update Ticket assigned to
                update_post_meta($post_id,'ticket_assigned',$_POST['ticket_assigned']);
                
                // Update lock assignment
                update_post_meta($post_id,'ticket_assign_lock',$_POST['ticket_assign_lock']);
		
		$status = $_POST['ticket_status'];
		
		if($status == 1 || $status == 2) {
			$comments = 'open';
			delete_post_meta($post_id,'close_time');
		} else if ($status == 0) {
			$comments = 'closed';
			update_post_meta($post_id, 'close_time', time()); 
		}
		$wpdb->query("UPDATE ".$wpdb->prefix."posts SET comment_status = '$comments' WHERE id = '$post_id'");
		// New, Update, and Delete
		if(get_post_meta($post_id, 'ticket_status') == "") 
			add_post_meta($post_id, 'ticket_status', $status, true);
		elseif($status != get_post_meta($post_id, 'ticket_status', true))
			update_post_meta($post_id, 'ticket_status', $status); 
		elseif($status == "")
			delete_post_meta($post_id, 'ticket_status', get_post_meta($post_id, 'ticket_status', true));
}

/*
* Auto Complete For Author Box on Add/Edit Ticket Page
*/
function dynamo_support_auto_complete() {
	global $wpdb;
	$search = $_POST['query'].'%';
		$table = $wpdb->prefix.'users';
		$user = $wpdb->get_results("SELECT id, display_name FROM $table WHERE user_login LIKE '$search' OR user_email LIKE '$search' OR display_name LIKE '$search' ORDER BY display_name ASC LIMIT 0,15");
		
		echo '<ul id="auto-complete-list">';
			foreach($user as $k => $u) {
				echo '<li><a href="#" class="set-author" rel="'.$u->id.'">'.$u->display_name.'</a></li>';
			}
		echo '</ul>';
		
	die();
}
add_action('wp_ajax_dynamo_support_auto_complete', 'dynamo_support_auto_complete');

/*
* Add To Knowledgebase
*/
function dynamo_support_add_to_knowledgebase() {
global $current_user;
	$cur = get_post($_POST['id']);
	$args = array(
		'post_title' => $cur->post_title,
		'post_content' => $cur->post_content,
		'post_author' => $current_user->ID,
		'post_status' => 'draft',
		'comment_status' => 'closed',
		'ping_status' => 'closed',
		'post_type' => 'knowledgebase'
	);
	$id = wp_insert_post($args);
	if(!is_wp_error($id) && $id != 0) {
	echo '<a href="'.get_bloginfo('wpurl').'/wp-admin/post.php?post='.$id.'&action=edit" title="Go Edit Knowledge base Item"><strong>View Knowledge base Draft &#187;</strong></a>';
	} else {
	echo 'Failed to copy ticket, please try again...';
	}
	die();
}
add_action('wp_ajax_dynamo_support_add_to_knowledgebase', 'dynamo_support_add_to_knowledgebase');
?>