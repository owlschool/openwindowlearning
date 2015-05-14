<?php
/*
* Dynamop Support Wish List Member Functions
*/

/*
* Get a users Purchased C66 Levels & Status and return
*/
function dynamo_support_get_user_c66_levels($user_email) {
	global $wpdb;
	$table = $wpdb->prefix.'cart66_orders';
	
	//Get All Orders for user email
	$response = $wpdb->get_results("SELECT id FROM $table WHERE email = '$user_email'");
	//get item_number for each product in an order
	$c = 0;
	foreach($response as $k => $v) {
		$table = $wpdb->prefix.'cart66_order_items';
		$order_items = $wpdb->get_results("SELECT product_id, item_number, description FROM $table WHERE order_id = '$v->id'");
		
		foreach($order_items as $l => $p) {
			$levels[$c]['id'] = $p->item_number;
			$levels[$c]['name'] = $p->description;
			$levels[$c]['status'] = 'active';
			$c++;
		}
	}
	return $levels;
}
/*
* Get all C66 levels and return in array
*/
function dynamo_support_get_all_c66_levels() {
	global $wpdb;
	$table = $wpdb->prefix.'cart66_products';
	$response = $wpdb->get_results("SELECT id, name, item_number FROM $table");
	
	$c = 0;
	foreach($response as $k => $v) {
		$levels[$c]['id'] = $v->item_number;
		$levels[$c]['name'] = $v->name;
		$c++;
	}
	return $levels;
}
/*
* Import Topics From C66
*/
function dynamo_support_import_c66_levels_topics($current_topics) {
	global $wpdb;
	//Import WLM Levels as Topics
	$table = $wpdb->prefix.'cart66_products';
	$response = $wpdb->get_results("SELECT id, name, item_number FROM $table");
	foreach($response as $k => $v) {
		$id = $v->item_number;
		if(dynamo_support_search_array($v->name, 'name', $current_topics) === false) {
			$topics[$id]['name'] = $v->name;
			$topics[$id]['source'] = 'c66';
			$topics[$id]['hidden'] = '';
		}
	}
	if(is_array($topics) && !empty($topics)) {
		return array_merge($current_topics, $topics);
	}
	return $current_topics;
}


function dynamo_support_c66_price_calc($authordata) {
global $wpdb, $support_options;
//Price Calc
$total = '0.00';
$email = trim(str_replace('TEMP__','',$authordata->user_email));
$table = $wpdb->prefix.'cart66_orders';
$sql = "SELECT total FROM $table WHERE email = '$email'";
$prices = $wpdb->get_results($sql);

$ds_content .= '<br/>';
	if(dynamo_support_getBrowser($_SERVER['HTTP_USER_AGENT']) != 'Chrome') {
		$ds_content .= '<form class="phorm" target="_blank" action="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=cart66_admin" method="post" style="z-index:9998;">
<input type="hidden" name="cart66-task" value="search orders"/>
<input type="hidden" name="search" value="'.trim($authordata->user_email).'" />
<input type="submit" class="button-secondary" value="View Orders" style="width: auto; z-index:9999; "/>
</form><br/>';
}
		if(is_array($prices) && !empty($prices) ) {
			foreach($prices as $k => $v) {
				$p = '';
				$p = $v->total;

				if($p == '') { $p = '0.00'; }
						
				$total = $total+$p;
			
			}					
		} else {
			$total = '0.00';
		}
						
$ds_content .='<strong>Total Value:</strong> $'.number_format($total,2).'';
						
return $ds_content;
}
?>