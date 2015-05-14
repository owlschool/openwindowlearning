<?php
/*
* Dynamo Support Media Library Functions
*/

/* Only run our customization on the 'upload.php' page in the admin. */
add_action( 'admin_init', 'dynamo_support_media_load' );

function dynamo_support_media_load() {
	if(strpos($_SERVER['REQUEST_URI'], 'upload.php') == true) {
		if ($support_options['plugin_version'] == '==AUWVUeZhFZzFWMaNVTWJVU') {
			add_filter( 'request', 'dynamo_support_sort_media' );
			add_filter( 'views_upload', 'dynamo_support_media_views');
		}
	}
}

/*
* Request to only show Support Attachments
*/
function dynamo_support_sort_media($vars) {
	/* Merge the query vars with our custom variables. */
		if(isset($_REQUEST['view']) && 'support' == $_REQUEST['view']) {
			//Support Media
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'ticket_attachment',
					'meta_value' => '1'
				)
			);
		}
	return $vars;
}
/*
* Add to the Media Views menu to add Support
*/
function dynamo_support_media_views( $views ) {
	if(isset($_REQUEST['view']) && 'support' == $_REQUEST['view']) {
		$supportclass = "current";
	}
	//Add post_mime_type=true to remove all as current
	$views = array_merge(
		$views,
		array(
			'support' => '<a href="upload.php?post_mime_type=true&view=support" class="'.$supportclass.'">Support <span class="count">('.dynamo_support_count_support_media().')</span></a>'
		)
	);
	return ($views);
}

/*
* Total Count Of Support Attachments
*/
function dynamo_support_count_support_media() {
	global $wpdb;
	$table = $wpdb->prefix.'postmeta';
	$tickets = $wpdb->get_results("SELECT DISTINCT post_id FROM $table WHERE meta_key = 'ticket_attachment' AND meta_value = '1'");
	return count($tickets);
}
?>