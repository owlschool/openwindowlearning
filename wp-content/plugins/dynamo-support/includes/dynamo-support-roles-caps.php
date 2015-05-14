<?php
/*
* Deactivate Deletes Roles
*/

function dynamo_support_roles_deactivate() {
global $wp_roles;
$wp_roles->remove_role('support_admin');

/* TO DO - Loop through current Roles and Remove and Reset Roles */

}




/*
* Get Current User Role
*/
function dynamo_support_get_current_user_role() {
	global $wp_roles;
	$current_user = wp_get_current_user();
	$roles = $current_user->roles;
	$role = array_shift($roles);
	return $role;
}
/*
* Get all possible new caps
*/
function dymamo_support_get_capabilities() {
	global $support_options;
	$capabilities = array();
	
	//See Tickets In Sidebar
	$capabilities['access_tickets']['val'] = false;
	$capabilities['access_tickets']['name'] = 'Access Tickets';
	
	
	//Access Knowledgebase / See Knowledgebase in Sidebar
	$capabilities['access_knowledgebase']['val'] = false;
	$capabilities['access_knowledgebase']['name'] = 'Access Knowledgebase';
	
	//See & Access Feedback In The Sidebar
	$capabilities['access_feedback']['val'] = false;
	$capabilities['access_feedback']['name'] = 'Access Feedback';
	
	//See Specific Ticket Topics
	if(is_array($support_options['topics']) && !empty($support_options['topics'])) {
		foreach($support_options['topics'] as $k => $v) {
			$capabilities['access_tickets_'.$k.'']['val'] = false;
			$capabilities['access_tickets_'.$k.'']['name'] = 'Access Tickets '.$v['name'];
		}
	}
	
	return $capabilities;
}

/*
* Enforce capabilities for viewing tickets/kb articles
*/
function dynamo_support_enforce_capabilities() {
	global $wp_query, $current_user, $support_options, $wp_roles;
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] == 'ticket') {
	
		$userRole = $current_user->caps;
		if(is_array($userRole) && count($userRole) > 0) {
			$role = '';
			foreach($userRole as $k => $v) {
				if($role == '') {
					$role = $k;
					if($role != '') {
						break;
					}
				}
			}
		}
		if(isset($support_options['roles'][$role]['capabilities'])) {
			$capabilities = $support_options['roles'][$role]['capabilities'];
		} else {
			$capabilities = '';
		}
		
	if(is_array($capabilities)) {
		foreach($capabilities as $k => $v) {
			if($k != 'access_tickets' && $k != 'access_knowledgebase' && $k != 'ticket-assignment') {
				$topics[] = $support_options['topics'][str_replace('access_tickets_','',$k)]['name'];
			}
		}
		if(is_array($topics) && $topics[0] != '') {
			$wp_query->query_vars['meta_query'] = array(
				array(
					'key' => 'ticket_topic',
					'value' => $topics,
					'compare' => 'IN'
				),
				array( 
					'key' => $wp_query->query_vars['meta_key'],
					'value' => $wp_query->query_vars['meta_value']
				)
			);
			$wp_query->query_vars['meta_key'] = '';
			$wp_query->query_vars['meta_value'] = '';
		} else {
			return;
		}
	}
	}
}
add_action('pre_get_posts','dynamo_support_enforce_capabilities');


/*
Maybe Delete!
*/
function dynamo_support_enforce_assignments() {
	global $wp_query, $current_user, $support_options, $wp_roles;
	if(is_admin() && $_GET['post_type'] == 'ticket') {
		$userRole = $current_user->caps;
		$role = key($userRole);
		
	}	
}
//add_action('pre_get_posts','dynamo_support_enforce_assignments');

/*
* Dropdown for roles
*/
function dynamo_support_roles_dropdown() {
	global $wp_roles;
	foreach($wp_roles->roles as $k => $v) {
		$roles[$k] = $v['name'];
	}
	foreach($roles as $k => $v) {
		echo '<option value="'.$k.'">'.$v.'</option>';
	}
}
/*
* Topics Dropdown
*/
function dynamo_support_topic_dropdown() {
	global $support_options;
	print_r($support_options['topics']);
	foreach($support_options['topics'] as $k => $v) {
		echo '<option value="'.$v['name'].'">'.$v['name'].'</option>';
	}
}


/**
 * Retrieve all our Support users
 * 
 * @global array $support_options
 * @return array $roles
 */
function dynamo_support_fetch_all_roles() {
    global $support_options;
    
    $roles = array();
    
    foreach ($support_options['roles'] as $role => $data) {
        $roles[] = $role;
    }
    
    return $roles;
}

/**
 * Retrieve all our existing support users
 * 
 * @return array    Users Object
 */
function dynamo_support_fetch_all_user_support() {
    
    $users = array();
    $roles = dynamo_support_fetch_all_roles();
    
    foreach($roles as $role) {
        
        $role_users = get_users('role='. $role);        
        foreach($role_users as $user) {
            $users[] = $user->data;
        }
    }
    
    return $users;
    
}
?>