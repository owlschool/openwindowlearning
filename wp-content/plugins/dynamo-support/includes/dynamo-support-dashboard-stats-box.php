<?php
/*
* Dynamo Support Dashboard Stats Widget
*/
function dynamo_support_stats_dashboard_widget() {
	?>
	<div class="table table_content">
	<p class="sub">Ticket Overview</p>
	<table>
	<tbody>
		<tr>
			<td class="first b b_pages"><a href="edit.php?post_type=ticket"><?php echo dynamo_support_manual_count('open'); ?></a></td>
			<td class="t pages"><a href="edit.php?post_type=ticket">Open Tickets</a></td>
		</tr>
		<tr>
			<td class="first b b_pages"><a href="edit.php?post_type=ticket&ticket_status=answered"><?php echo dynamo_support_manual_count('answered'); ?></a></td>
			<td class="t pages"><a href="edit.php?post_type=ticket&ticket_status=answered">Answered Tickets</a></td>
		</tr>
		<tr>
			<td class="first b b_pages"><a href="edit.php?post_type=ticket&ticket_status=closed"><?php echo dynamo_support_manual_count('closed'); ?></a></td>
			<td class="t pages"><a href="edit.php?post_type=ticket&ticket_status=closed">Closed Tickets</a></td>
		</tr>
		<tr>
			<td class="first b b_pages"><a href="edit.php?post_type=ticket&ticket_status=all"><?php echo dynamo_support_total_tickets(); ?></a></td>
			<td class="t pages"><a href="edit.php?post_type=ticket&ticket_status=all">Total Tickets</a></td>
		</tr>
	</tbody>
	</table>
	</div>
	<div class="clear"></div>
	<?php
}

function dynamo_support_add_dashboard_widgets() {
	wp_add_dashboard_widget('dynamo-support-stats','Dynamo Support Stats','dynamo_support_stats_dashboard_widget');
}
add_action('wp_dashboard_setup','dynamo_support_add_dashboard_widgets');
?>