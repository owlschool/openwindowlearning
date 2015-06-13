<?php
global $support_options;
/*
* Dynamo Support - Member Wing X Intergration
*/

//When User Registers Add Price
function dynamo_support_user_register_price($user_id, $ip) {
	global $support_options;
	$products_purchased = MWX__GetListOfProductsForUser ($user_id);
	if (is_array($products_purchased) && count($products_purchased)) {
		foreach ($products_purchased as $idx => $product) {
			$id = $product['product_id'];
			$price = $support_options['price'][$id];
			$user_prices[$id] = $price;
			update_user_meta($user_id,'dynamo_support_value',$user_prices);
		}
	}
}
if($support_options['integration'] == 'memberwingx') {
	add_action('user_register', 'dynamo_support_user_register_price', 300,2);
}


//Get A Users MWX Levels and return
function dynamo_support_get_user_mwx_levels($user_id) {
	$products_purchased = MWX__GetListOfProductsForUser ($user_id);
	if (is_array($products_purchased) && count($products_purchased)) {
		foreach ($products_purchased as $idx => $product) {
			$levels[$c]['id'] = $product['product_id'];
			$levels[$c]['name'] = $product['product_name'];
			$levels[$c]['status'] = $product['product_status'];
			$levels[$c]['purchase'] = $product['purchase_date'];
			$levels[$c]['expire'] = $product['expiry_date'];
			$c++;
		}
	}
	return $levels;
}

//Import Levels As Topics
function dynamo_support_import_mwx_levels_topics($current_topics) {
	global $wpdb;
	$table = $wpdb->prefix.'usermeta';
	$sql = "SELECT meta_value FROM $table WHERE meta_key = 'mwx_purchases'";
	$results = $wpdb->get_results($sql);
	
	if(is_array($results) && !empty($results)) {
		//Filter through and receive individual products.
		foreach($results as $r) {
			$meta = unserialize($r->meta_value);
			$meta = unserialize($meta);
			$id = $meta[0]['product_id'];
			$name = $meta[0]['product_name'];
			if(dynamo_support_search_array($name, 'name', $current_topics) === false) {
				$topics[$id]['name'] = $name;
				$topics[$id]['source'] = 'mwx';
				$topics[$id]['hidden'] = '';
			}
		}
	}
	if(is_array($topics) && !empty($topics)) {
		return array_merge($current_topics, $topics);
	}
	return $current_topics;
}
//Get ALl Levels
function dynamo_support_get_all_mwx_levels() {
	global $wpdb;
	$table = $wpdb->prefix.'usermeta';
	$sql = "SELECT  meta_value FROM $table WHERE meta_key = 'mwx_purchases'";
	$results = $wpdb->get_results($sql);
	
	if(is_array($results) && !empty($results)) {
		//Filter through and receive individual products.
		$c = 0;
		$levels = array();
		foreach($results as $r) {
			$meta = unserialize($r->meta_value);
			$meta = unserialize($meta);
			$id = $meta[0]['product_id'];
			$name = $meta[0]['product_name'];
			if(dynamo_support_search_array($name, 'name', $levels) === false) {	
				$levels[$c]['id'] = $id;
				$levels[$c]['name'] = $name;
			}
		}
	}
	return $levels;
}

//Update Price Calc To All Members
function dynamo_support_mwx_update_all_price_calc($id) {
	global $wpdb, $support_options;
	$table = $wpdb->prefix.'users';
	$sql = "SELECT ID FROM $table";
	$results = $wpdb->get_results($sql);
	foreach($results as $k => $user_id) {
		$products_purchased = MWX__GetListOfProductsForUser ($user_id);
		if (is_array($products_purchased) && count($products_purchased)) {
			foreach ($products_purchased as $idx => $product) {
				if($product['product_id'] == $id) {
					$update[] = $user_id;
				}
			}
		}
	}
	$price = $support_options['price'][$id];
	foreach($update as $userID) {
		$user_prices = get_user_meta($userID,'dynamo_support_value',true);
		$user_prices[$id] = $price;
		update_user_meta($userID,'dynamo_support_value',$user_prices);
	}
}

//Price Calc
function dynamo_support_mwx_price_calc($authordata) {
	global $wpdb, $support_options;
	//Price Calc
$total = '0.00';
$prices = get_user_meta($authordata->ID,'dynamo_support_value',true);
$ds_content .= '<br/><strong>User Subscriptions:</strong><br/>';
$subscriptions = dynamo_support_get_user_mwx_levels($authordata->ID);
foreach($subscriptions as $k => $v) {
	$p = '';
	if(is_array($prices[$v['id']]) && !empty($prices[$v['id']]) ) {
		if($prices[$v['id']]['repeat']['enabled'] != '' && $prices[$v['id']]['repeat']['enabled'] == '1') {
			$repeat = $prices[$v['id']]['repeat']['every']; 
			$every = $prices[$v['id']]['repeat']['repeater'];
			$repeatPrice = $prices[$v['id']]['repeat']['price'];
			
			$reg = $v['purchase'];
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
								
	if($v['status'] == 'active') {
		$ds_content .= '<span class="level">'.$v['name'].' <strong class="value">$'.number_format($p,2).'</strong></span><br/>';
	} else {
		$ds_content .= '<span class="level"><del>'.$v['name'].'</del> <strong class="value">$'.number_format($p,2).'</strong></span><br/>';
	}	
	
}
$ds_content .='<strong>Total Value:</strong> $'.number_format($total,2).'';
						
return $ds_content;
}
?>