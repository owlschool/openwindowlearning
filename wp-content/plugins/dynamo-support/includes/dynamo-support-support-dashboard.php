<?php
/*
* Support Dashboard page
*/
function support_dash() {
	global $support_options , $wpdb;
	?>
	<div class="wrap">
		<?php require_once('dynamo_support_header.php'); ?>
		<div class="postbox">
			<h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Support Dynamo - Last 30 Days Stats</span></h3>
			<div class="inside" style="padding:0 5px 5px 5px;">
				<div style="padding:10px 5px;">
					<div id="ds-graph" style="height: 210px;"></div>
				<?php
				/*
				* Calulcate graph
				*/
				$data = dynamo_support_ticket_days();
				$dataset = $data['data'];
				$labels = $data['labels'];
				$markings = $data['markings'];
				$average = $data['average'];
				$total = $data['total'];
				?>
				<script type="text/javascript">
					jQuery(function ($) {
						var dataset = <?php echo json_encode($dataset);?>;
						var data = [
							{
							label: '# of tickets',
							data: dataset,
							color: 'rgba(255, 204, 51, 1)',
							hoverable: true,
							}
						];
						var options = {
							series: {stack: 0,
									 lines: {show: false, steps: false },
									 bars: {show: true, barWidth: 0.9, align: 'center', fillColor: 'rgba(255, 204, 51, 1)', lineWidth: 0, shadowSize: 0},},
							xaxis: {
									ticks: <?php echo json_encode($labels); ?>, 
									tickSize:1, 
									tickDecimals:0,
									tickLength: 0
									},
							yaxis: {
									 tickDecimals: 0, min: 0
									} ,
							grid: {
									hoverable:true, backgroundColor: '#fff', borderWidth:1, borderColor: '#bbb', clickable: true, 
									markings: <?php echo json_encode($markings); ?>
								}
						};
						
						
						$.plot($("#ds-graph"), data, options);
					});
				</script>
				</div>
				<div id="stats-overview">
					<ul>
						<li><span><?php echo $total; ?></span>Total Tickets (30 Days)</li>
						<li><span><?php echo dynamo_support_total_today(); ?></span>Tickets Today</li>
						<li><span><?php echo $average; ?></span>Average Tickets per Day (30 Days)</li>
						<li><span>&nbsp;</span><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/edit.php?post_type=ticket" class="button right">View Open Tickets &#187;</a></li>			
					</ul>
				</div>
			</div>
		</div>
		
		<div class="postbox">
			<h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Support Dynamo - Current Overview</span></h3>
			<div class="inside" style="padding:0 5px 5px 5px;">
				<div id="ticket-stat">
					<ul>
						<li><span><?php echo dynamo_support_manual_count('open'); ?></span>Open Tickets<br/><br/><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/edit.php?post_type=ticket" title="View Open Tickets" class="button">View Open &#187;</a></li>
						<li><span><?php echo dynamo_support_manual_count('answered'); ?></span>Answered Tickets<br/><br/><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/edit.php?post_type=ticket&ticket_status=answered" title="View Answered Tickets" class="button">View Answered &#187;</a></li>
						<li><span><?php echo dynamo_support_manual_count('closed'); ?></span>Closed Tickets<br/><br/><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/edit.php?post_type=ticket&ticket_status=closed" title="View Closed Tickets" class="button">View Closed &#187;</a></li>
						<li><span><?php echo dynamo_support_total_tickets(); ?></span>Total Tickets<br/><br/><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/edit.php?post_type=ticket&ticket_status=all" title="View All Tickets" class="button">View All &#187;</a></li>
						
					</ul>
				</div>
			</div>
		</div>
		
		<div style="clear:both;">
		
		<div class="postbox-container left" style="width:49%; padding-right:0 !important;">
			<?php $emailtix = dynamo_support_tickets_by_email(); ?>
			<div class="postbox">
				<h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>New Ticket Stats</span></h3>
				<div class="inside" style="padding:0 5px 5px 5px;">
					<table class="ds-stats-table">
						<tr class="h">
							<th>Tickets Source </th><th>#</th><th>%</th>
						</tr>
						<tr class="alternate">
							<th>System:</th><td><?php echo (dynamo_support_total_tickets()-$emailtix); ?></td><td><?php echo dynamo_support_percent(dynamo_support_total_tickets()-$emailtix, dynamo_support_total_tickets() ); ?>%</td>
						</tr>
						<tr>
							<th>E-Mail:</th><td><?php echo $emailtix; ?></td><td><?php echo dynamo_support_percent($emailtix, dynamo_support_total_tickets()); ?>%</td>
						</tr>
						
				
					</table>
				</div>
			</div>

		</div>
		<div class="right postbox-container" style="width:49%; padding-right:0 !important;">
			<?php $emailreply = dynamo_support_replys_by_email(); ?>
			<div class="postbox">
				<h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Ticket Reply Stats</span></h3>
				<div class="inside" style="padding:0 5px 5px 5px;">
					<table class="ds-stats-table">
						<tr class="h">
							<th>Reply Source </th><th>#</th><th>%</th>
						</tr>
						<tr class="alternate">
							<th>System:</th><td><?php echo (dynamo_support_total_replys()-$emailreply); ?></td><td><?php echo dynamo_support_percent(dynamo_support_total_replys()-$emailreply, dynamo_support_total_replys() ); ?>%</td>
						</tr>
						<tr>
							<th>E-Mail:</th><td><?php echo $emailreply; ?></td><td><?php echo dynamo_support_percent($emailreply, dynamo_support_total_replys()) ?>%</td>
						</tr>
						
						
						
						
					</table>
				</div>
			</div>

		</div>
		
		</div>
		
		<div style="clear:both;">
		
		<div class="postbox-container left" style="width:49%; padding-right:0 !important;">
			<?php $postmeta = $wpdb->prefix.'postmeta'; $posts = $wpdb->prefix.'posts'; 
					$views = $wpdb->get_results("SELECT meta.post_id, posts.post_title, meta.meta_value AS views
												FROM $postmeta meta
												INNER JOIN $posts posts ON ( meta.post_id = posts.ID )
												WHERE meta.meta_key = 'kb_views'
												ORDER BY views DESC
												LIMIT 0 , 30");
					$helpful = $wpdb->get_results("SELECT meta.post_id, posts.post_title, meta.meta_value AS votes
												FROM $postmeta meta
												INNER JOIN $posts posts ON ( meta.post_id = posts.ID )
												WHERE meta.meta_key = 'kb_vote_yes'
												ORDER BY votes DESC
												LIMIT 0 , 30");
					$nothelpful = $wpdb->get_results("SELECT meta.post_id, posts.post_title, meta.meta_value AS votes
												FROM $postmeta meta
												INNER JOIN $posts posts ON ( meta.post_id = posts.ID )
												WHERE meta.meta_key = 'kb_vote_no'
												ORDER BY votes DESC
												LIMIT 0 , 30");
					?>
			<div class="postbox">
				<h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Knowledge Base Stats</span></h3>
				<div class="inside customlinkdiv " style="padding:0 5px 5px 5px;">
					<ul id="kb-tabs" class="category-tabs" style="margin:8px 0 3px;">
						<li class="tabs">
							<a href="#kb-views">Views</a>
						</li>
						<li class="hide-if-no-js">
							<a href="#kb-helpful">Most Helpful</a>
						</li>
						<li class="hide-if-no-js">
							<a href="#kb-least-helpful">Least Helpful</a>
						</li>
					</ul>
					<div class="tabs-panel" id="kb-views">
					<table class="ds-stats-table">
						<tr class="h">
							<th>Article </th><th>Views</th>
						</tr>
						<?php if(is_array($views) && count($views) > 0) { ?>
						<?php 
						//Sort By Views
$views = sortArrayofObjectByProperty( $views, 'views' ); $views = array_reverse($views); ?>
							<?php foreach($views as $v) { ?>
								<tr class="alternate">
									<td><a href="<?php echo get_permalink($v->post_id);?>" title="View Article" target="_blank"><?php echo $v->post_title; ?></a></td><td><?php echo $v->views;?></td>
								</tr>
							<?php } ?>
						<?php  } else { ?>
						<tr class="alternate">
							<td colspan="2">No knowledge base views found</td>
						</tr>
						<?php } ?>
					</table>
					</div>
					<div class="tabs-panel" id="kb-helpful" style="display:none;">
						<table class="ds-stats-table">
						<tr class="h">
							<th>Article </th><th>Helpful Votes</th>
						</tr>
						<?php
						if(is_array($helpful) && count($helpful) > 0) {
							foreach($helpful as $v) { ?>
								<tr class="alternate">
									<td><a href="<?php echo get_permalink($v->post_id);?>" title="View Article" target="_blank"><?php echo $v->post_title; ?></a></td><td><?php echo $v->votes;?></td>
								</tr>
							<?php }
						} else {
						?>
							<tr class="alternate">
								<td colspan="2">No helpful votes found</td>
							</tr>
						<?php
						}
						?>
						</table>
					</div>
					<div class="tabs-panel" id="kb-least-helpful" style="display:none;">
						<table class="ds-stats-table">
						<tr class="h">
							<th>Article </th><th>Not Helpful Votes</th>
						</tr>
						<?php
							if(is_array($nothelpful) && count($nothelpful) > 0) {
								foreach($nothelpful as $v) { ?>
								<tr class="alternate">
									<td><a href="<?php echo get_permalink($v->post_id);?>" title="View Article" target="_blank"><?php echo $v->post_title; ?></a></td><td><?php echo $v->votes;?></td>
								</tr>
							<?php }
							} else {
							?>
							<tr class="alternate">
								<td colspan="2">No not helpful votes found</td>
							</tr>
							<?php
							}
							?>
						</table>
					</div>
				</div>
			</div>

		</div>
		<div class="right postbox-container" style="width:49%; padding-right:0 !important;">
			<?php $searches = $support_options['kb_searches']; ?>
			<div class="postbox">
				<h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Knowledge Base Searches</span></h3>
				<div class="inside" style="padding:0 5px 5px 5px;">
					<table class="ds-stats-table">
						<tr class="h">
							<th>Search Term </th>
						</tr>
						<?php if(is_array($searches) && count($searches) > 0) { ?>
							<?php foreach(array_reverse($searches) as $s) { ?>
							<tr class="alternate">
								<td><?php echo $s; ?></td>
							</tr>
							<?php } ?>
						<?php } else { ?>
						<tr class="alternate">
							<td>No recent search terms found</td>
						</tr>
						<?php } ?>
					</table>
				</div>
			</div>

		</div>
		
		</div>
		
		<div style="clear:both;">
			<div class="postbox-container left" style="width:49%; padding-right:0 !important;">
			<?php
			$sql = "SELECT DISTINCT meta_value AS topic, COUNT(post_id) AS count FROM $wpdb->postmeta WHERE meta_key = 'ticket_topic' GROUP BY meta_value";
			$topics = $wpdb->get_results($sql);
			?>
			<div class="postbox">
				<h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Topic Stats</span></h3>
				<div class="inside customlinkdiv " style="padding:0 5px 5px 5px;">
					<table class="ds-stats-table">
						<tr class="h">
							<th>Topic</th>
							<th># Of Tickets</th>
						</tr>
				<?php
				if(is_array($topics) && count($topics) > 0) {
					$x = 0;
					foreach($topics as $k => $top) {
						if($x == 1) {
							$class = 'alternate';
							$x = 0;
						} else {
							$class = '';
						}
						$x++;
					?>
						<tr class="<?php echo $class;?>">
							<td><?php echo $top->topic;?></td>
							<td><?php echo $top->count;?></td>
						</tr>
					<?php
					}
				} else {
				?>
						<tr class="alternate">
							<td colspan="2">No topics found, please create some.</td>
						</tr>
				<?php
				}
				?>
					</table>
				</div>
			</div>
			</div>
		</div>
		
	</div>
	<?php
}

function dynamo_support_percent($num_amount = '', $num_total = '') {
if($num_total == '0') { $num_total = '1'; }
$count1 = $num_amount / $num_total;
$count2 = $count1 * 100;
$count = number_format($count2, 2);
echo $count;
}
function dynamo_support_ticket_days() {
	global $wpdb;
	$table = $wpdb->prefix.'posts';
	
	$today = date('Y-m-d H:i:s',current_time('timestamp', 1));
	$thirty = date('Y-m-d H:i:s',strtotime('- 30 day',current_time('timestamp',1)));
	
	
	$sql = "SELECT COUNT(ID) AS count, DATE_FORMAT(post_date, '%b %e') AS pdate FROM $table WHERE post_type = 'ticket' AND post_parent = '0' AND post_status = 'publish' AND post_date BETWEEN '$thirty' AND '$today' GROUP BY pdate ORDER BY post_date ASC";
	$results = $wpdb->get_results($sql);
		if(is_array($results)) {
			$i = 0;
			$c = 0;
			//Figure out missing dates
			$t = strtotime($thirty);
			while($c <= 30):
				$date = date('M j', strtotime('+'.$c.' day',$t));
				$dates[] = $date;
				$c++;
			endwhile;
			foreach($dates as $d) {
					if(date('D',strtotime($d)) == 'Sat') {
						$markings[] = array('xaxis' => array('from'=>($i-1).'.5', 'to' => ($i+1).'.5'), 'color' => '#eee');
					}
					$labels[$i] = array($i, $d);
				foreach($results as $result) {
					if($result->pdate == $d) {
						$dataset[$i] = array($i, $result->count);
					} 
				}
				if(empty($dataset[$i])) {
					$dataset[$i] = array($i, '0');
				}
				$average = $average+$dataset[$i][1];
				$i++;
			}
		}
		return $results = array('data' => $dataset,'labels' => $labels, 'markings' => $markings, 'average' => number_format($average/30, 2, '.', ''), 'total' => $average);
}

function dynamo_support_tickets_by_email() {
	global $wpdb;
	$table = $wpdb->prefix.'postmeta';
	$sql = "SELECT COUNT(post_id) FROM $table WHERE meta_key = 'ticket_source' AND meta_value = 'email'";
	$ret = $wpdb->get_var($sql);
	if($ret != '') {
		return $ret;
	}
	return '0';
}

function dynamo_support_replys_by_email() {
	global $wpdb;
	$table = $wpdb->prefix.'commentmeta';
	$sql = "SELECT COUNT(comment_id) FROM $table WHERE meta_key = 'reply_source' AND meta_value = 'email'";
	$ret = $wpdb->get_var($sql);
	if($ret != '') {
		return $ret;
	}
	return '0';
}

function dynamo_support_total_replys() {
	global $wpdb;
	
	//Exclude User ID's
	$ids = get_users(array('role' =>'administrator'));
	foreach($ids as $k => $v) {
		$results[] = $v->ID;
	}
	
	$support_options = get_option('dynamo_support_options');
	if(is_array($support_options['roles']) && count($support_options['roles']) > 0) {
		foreach($support_options['roles'] as $role => $v) {
			$ids = get_users(array('role' => $role));
			foreach($ids as $k => $v) {
				$results[] = $v->ID;
			}
		}
	}
	$table = $wpdb->prefix.'comments';
	$table2 = $wpdb->prefix.'posts';
	$sql = "SELECT COUNT(com.comment_ID) AS count FROM $table com LEFT JOIN $table2 post ON (com.comment_post_ID = post.ID)   WHERE post.post_type = 'ticket' AND post.post_parent = '0' AND post.post_status = 'publish'";
	if(is_array($results) && count($results) > 0) {
		foreach($results as $k => $id) {
			$sql .=" AND post.post_author != '$id'";
		}
	}
	$ret = $wpdb->get_var($sql);
 	if($ret != '') {
		return $ret;
	}
	return '0';
}

function dynamo_support_total_tickets() {
	global $wpdb;
	$table = $wpdb->prefix.'posts';
	$sql = "SELECT COUNT(ID) AS count FROM $table WHERE post_type = 'ticket' AND post_parent = '0'";
	$ret = $wpdb->get_var($sql);
	if($ret != '') {
		return $ret;
	}
	return '0';
}
function dynamo_support_total_today() {
global $wpdb;
	$table = $wpdb->prefix.'posts';
	$d = date('Y-m-d');
	$sql = "SELECT COUNT(ID) AS count FROM $table WHERE post_type = 'ticket' AND post_parent = '0' AND post_date LIKE '$d%'";
	$ret = $wpdb->get_var($sql);
	if($ret != '') {
		return $ret;
	}
	return '0';
}

function sortArrayofObjectByProperty( $array, $property )
{
    $cur = 1;
    $stack[1]['l'] = 0;
    $stack[1]['r'] = count($array)-1;

    do
    {
        $l = $stack[$cur]['l'];
        $r = $stack[$cur]['r'];
        $cur--;

        do
        {
            $i = $l;
            $j = $r;
            $tmp = $array[(int)( ($l+$r)/2 )];

            // split the array in to parts
            // first: objects with "smaller" property $property
            // second: objects with "bigger" property $property
            do
            {
                while( $array[$i]->{$property} < $tmp->{$property} ) $i++;
                while( $tmp->{$property} < $array[$j]->{$property} ) $j--;

                // Swap elements of two parts if necesary
                if( $i <= $j)
                {
                    $w = $array[$i];
                    $array[$i] = $array[$j];
                    $array[$j] = $w;

                    $i++;
                    $j--;
                }

            } while ( $i <= $j );

            if( $i < $r ) {
                $cur++;
                $stack[$cur]['l'] = $i;
                $stack[$cur]['r'] = $r;
            }
            $r = $j;

        } while ( $l < $r );

    } while ( $cur != 0 );

    return $array;

}
?>