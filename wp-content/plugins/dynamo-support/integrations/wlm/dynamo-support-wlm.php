<?php
/*
* Dynamop Support Wish List Member Functions
*/

/*
* Get a users WLM Levels & Status and return it using the WLM API class
*/
function dynamo_support_get_user_wlm_levels($user_id) {
	global $wpdb;
	$table = $wpdb->prefix.'wlm_options';
	require_once('wlmapiclass.php');
	$apikey = $wpdb->get_var("SELECT option_value FROM $table WHERE option_name = 'WLMAPIKey'");
	$wlmapi = new wlmapiclass(get_bloginfo('wpurl'), $apikey);
	$wlmapi->return_format = 'php';
	
	$response = $wlmapi->get('/members/'.$user_id);
	$response = unserialize($response);
	
	//Get User Level + Status
		$c = 0;
	foreach ($response['member'][0]['Levels'] as $l => $v) {
		$levels[$c]['id'] = $v->Level_ID;
		$levels[$c]['name'] = $v->Name;
		$levels[$c]['status'] = $v->Status[0];
		$c++;
	}
	return $levels;
}
/*
* Get all WLM levels and return in array
*/
function dynamo_support_get_all_wlm_levels() {
	global $wpdb;
	$table = $wpdb->prefix.'wlm_options';
	require_once('wlmapiclass.php');
	$apikey = $wpdb->get_var("SELECT option_value FROM $table WHERE option_name = 'WLMAPIKey'");
	$wlmapi = new wlmapiclass(get_bloginfo('wpurl').'/index.php', $apikey);
	$wlmapi->return_format = 'php';
	$response = $wlmapi->get('/levels');
	$response = unserialize($response);
	$c = 0;
	foreach($response['levels']['level'] as $k => $v) {
		$levels[$c]['id'] = $v['id'];
		$levels[$c]['name'] = $v['name'];
		$c++;
	}
	return $levels;
}
/*
* Import Topics From WLM
*/
function dynamo_support_import_wlm_levels_topics($current_topics) {
	global $wpdb;
	//Import WLM Levels as Topics
	$table = $wpdb->prefix.'wlm_options';
	$wlm_levels = unserialize($wpdb->get_var("SELECT option_value FROM $table WHERE option_name = 'wpm_levels'"));
	foreach($wlm_levels as $k => $v) {
		$id = $k;
		if(dynamo_support_search_array($v['name'], 'name', $current_topics) === false) {
			$topics[$id]['name'] = $v['name'];
			$topics[$id]['source'] = 'wlm';
			$topics[$id]['hidden'] = '';
		}
	}
	if(is_array($topics) && !empty($topics)) {
		return array_merge($current_topics, $topics);
	}
	return $current_topics;
}
/*
* User Registration Get Level Price for Price Calc
*/
//After a new user registers do the following actions
function dynamo_support_wlm_new_user_register($user_id, $levels) {
	global $support_options;
	//Save current value of purchased product
	if(is_array($levels) && count($levels) > 0 ) {
		foreach($levels as $k => $v) {
			$level_id = $v;
			$price = $support_options['price'][$level_id];
			$user_prices = get_user_meta($user_id,'dynamo_support_value',true);
			$user_prices[$level_id] = $price;
			update_user_meta($user_id,'dynamo_support_value',$user_prices);
		}
	}
}
add_action('wishlistmember_add_user_levels','dynamo_support_wlm_new_user_register',0,2);

//If User is canceled from level remove price
add_action('wishlistmember_cancel_user_levels','dynamo_support_remove_price','10','2');
//If user is removed from level remove price
add_action('wishlistmember_remove_user_levels','dynamo_support_remove_price','10','2');

function dynamo_support_remove_price($user_id, $levels) {
	$user_prices = get_user_meta($user_id,'dynamo_support_value',true);
	if(is_array($user_prices) && !empty($user_prices)) {
		unset($user_prices[$levels[0]]);
		update_user_meta($user_id,'dynamo_support_value',$user_prices);
	}
}

function dynamo_support_wlm_price_calc($authordata) {
global $wpdb, $support_options;
//Price Calc
$total = '0.00';
$prices = get_user_meta($authordata->ID,'dynamo_support_value',true);
$ds_content .= '<br/><strong>User Subscriptions:</strong><br/>';
$subscriptions = dynamo_support_get_user_wlm_levels($authordata->ID);
foreach($subscriptions as $k => $v) {
	$p = '';
	if(is_array($prices[$v['id']]) && !empty($prices[$v['id']]) ) {
		if($prices[$v['id']]['repeat']['enabled'] != '' && $prices[$v['id']]['repeat']['enabled'] == '1') {
			
			$repeat = $prices[$v['id']]['repeat']['every']; 
			$every = $prices[$v['id']]['repeat']['repeater'];
			$repeatPrice = $prices[$v['id']]['repeat']['price'];	
			$table1 = $wpdb->prefix.'wlm_userlevels';
			$table2 = $wpdb->prefix.'wlm_userlevel_options';
			$sql = "SELECT options.option_value FROM $table2 options LEFT JOIN $table1 wlm ON options.userlevel_id = wlm.ID WHERE wlm.user_id = '$authordata->ID' AND wlm.level_id = '$v[id]' AND options.option_name = 'registration_date'";
			$reg = $wpdb->get_var($sql);	
			$reg = substr($reg,0,19);
			$timestamp = strtotime($reg);
			$now = current_time('timestamp');
			$time = abs($now-$timestamp);
			$time = ceil($time/(60*60*24));
			if($every == 'day') {
				$x = $repeat;
			}
			if($every == 'week') {
				$x = $repeat*7;
			}
			if($every == 'month') {
				$x = $repeat*30;
			}
			if($every == 'year') {
			$x = $repeat*365;
			}
			$multi = floor($time/$x);
			$p = $prices[$v['id']]['one-time']['price']+($repeatPrice*$multi);	
			
		} else {
			
			$p = $prices[$v['id']]['one-time']['price'];
			
		}
	}
	if($p == '') { $p = '0.00'; }
						
	$total = $total+$p;
							
	if($v['status'] == 'Active') {
		$ds_content .= '<span class="level">'.$v['name'].' <strong class="value">$'.number_format($p,2).'</strong></span><br/>';
	} else {
		$ds_content .= '<span class="level"><del>'.$v['name'].'</del> <strong class="value">$'.number_format($p,2).'</strong></span><br/>';
	}
}
						
$ds_content .='<strong>Total Value:</strong> $'.number_format($total,2).'';
						
return $ds_content;
}

//Price Calc update all users WLM
function dynamo_support_wlm_update_all_price_calc($id) {
	global $wpdb, $support_options;
		$table = $wpdb->prefix.'wlm_options';
		require_once('wlmapiclass.php');
		$apikey = $wpdb->get_var("SELECT option_value FROM $table WHERE option_name = 'WLMAPIKey'");
		$wlmapi = new wlmapiclass(get_bloginfo('wpurl'), $apikey);
		$wlmapi->return_format = 'php';
		
		$response = $wlmapi->get('/levels/'.$id.'/members');
		$response = unserialize($response);
		$table = $wpdb->prefix.'users';
		foreach($response['members']['member'] as $user) {
			$userID = $user['id'];
			$price = $support_options['price'][$id];
			
			$user_prices = get_user_meta($userID,'dynamo_support_value',true);
			$user_prices[$id] = $price;
			update_user_meta($userID,'dynamo_support_value',$user_prices);
		}
}
?>