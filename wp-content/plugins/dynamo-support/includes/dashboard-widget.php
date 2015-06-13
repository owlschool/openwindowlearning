<?php
if(!function_exists('pd_dashboard_widget_function')) {
	function pd_dashboard_widget_function() {
	
		//PD News / Upates
		$feed = fetch_feed('http://plugindynamo.com/feed/');
		if(!is_wp_error($feed)) {
			$maxitems = $feed->get_item_quantity(3);
			$items = $feed->get_items(0,$maxitems);
		}
		echo '<ul>';
			foreach($items as $i) {
			?>
			<li>
				<h4><a href="<?php echo $i->get_permalink();?>"><strong><?php echo $i->get_title();?></strong></a> - <em style="font-weight:normal;"><?php echo $i->get_date('j F Y');?></em></h4>
				
			</li>
			<?php
			}
		echo '</ul> <hr/> <h4 style="margin-bottom:10px;">More News From The dotCOMreport.com:</h4>';
		
		// dotCOMreport
		$feed = fetch_feed('http://dotcomreport.com/feed/');
		if(!is_wp_error($feed)) {
			$maxitems = $feed->get_item_quantity(5);
			$items = $feed->get_items(0,$maxitems);
		}
		echo '<ul>';
			foreach($items as $i) {
			?>
			<li>
				<strong><a href="<?php echo $i->get_permalink();?>" title="<?php echo $i->get_title(); ?>"><?php echo $i->get_title();?></a></strong>
			</li>
			<?php
			}
		echo '</ul>';
	} 
}

if(!function_exists('pd_add_dashboard_widgets')) {
	function pd_add_dashboard_widgets() {
		wp_add_dashboard_widget('pd_dashboard_widget', 'PluginDynamo.com Updates', 'pd_dashboard_widget_function');	
		
		// Globalize the metaboxes array, this holds all the widgets for wp-admin

	global $wp_meta_boxes;
	
	// Get the regular dashboard widgets array 
	// (which has our new widget already but at the end)

	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	
	// Backup and delete our new dashbaord widget from the end of the array

	$example_widget_backup = array('pd_dashboard_widget' => $normal_dashboard['pd_dashboard_widget']);
	unset($normal_dashboard['pd_dashboard_widget']);

	// Merge the two arrays together so our widget is at the beginning

	$sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);

	// Save the sorted array back into the original metaboxes 

	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
		
	} 
}
if(function_exists('pd_add_dashboard_widgets')) {
	add_action('wp_dashboard_setup', 'pd_add_dashboard_widgets' );
}
?>