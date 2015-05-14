<?php
/*
* User Feedback if is pro and user visits page based on url
*/
function dymamo_support_close_feedback($post_id = '') {
global $support_options, $wpdb;

if ($support_options['plugin_version'] == '==AUWVUeZhFZzFWMaNVTWJVU') {

		//Close Ticket
		if($post_id == '' && $_POST['post_id'] != '') {
			$post_id = $_POST['post_id'];
		}
		update_post_meta($post_id, 'ticket_status', '0'); 
		$wpdb->query("UPDATE ".$wpdb->prefix."posts SET comment_status = 'closed' WHERE id = '$post_id'");
		update_post_meta($post_id, 'close_time', current_time('timestamp',1)); 
		//Print out Feedback form
	if($support_options['feedback-text'] == '') {
		$text = '<p><strong>WAIT!</strong> We could really use your help.  We would love for you to leaves us your comments on our products.  It will won\'t take long and will really help us know what you like and what we can do better.<br/>If you would like to leave a testimonial we would love that as well.</p>';
	} else {
		$text = stripslashes($support_options['feedback-text']);
	}

	$r .='<strong>This ticket is now closed</strong>
	 '.$text.'
	<form method="" action="" id="review-form">
		<div>
			<div class="left" style="width:49%;">
				<label><b style="color:#ff0000;">*</b><strong>Name:</strong></label><br/>
				<input type="text" name="review-name" placeholder="Enter your name" />
			</div>
			<div class="right" style="width:49%;">
				<label><b style="color:#ff0000;">*</b><strong>E-mail:</strong></label><br/>
				<input type="text" name="review-email" placeholder="Enter your e-mail address"/>
			</div>
			<div class="clear"></div>
		</div>
		<br/>
		<label style=""><strong>Domain:</strong></label><br/>
		<input type="text" name="review-domain" placeholder="Enter your url"/>
		<br/><br/>
		<label><b style="color:#ff0000;">*</b><strong>Subject:</strong></label><br/>
		<input type="Text" name="review-subject" placeholder="Title your feedback"/>
		<br/><br/>
		<label><b style="color:#ff0000;">*</b><strong>Rating:</strong></label>'. dynamo_support_show_rater('0',true).'
		<input type="hidden" name="rating" value="" id="new-review-rating"/><br/>
		<label><b style="color:#ff0000;">*</b><strong>Feedback:</strong></label><br/>
		<textarea name="review-feedback"></textarea>
		<br/>
		<input type="submit" value="Submit Feedback &#187;" id="submit-feedback"/>
	</form>';
		if(!$_POST['post_id']) {
			return $r;
		} else {
			echo $r;
		}
}
die();
}
add_action('wp_ajax_nopriv_dynamo_support_close_feedback','dymamo_support_close_feedback');
add_action('wp_ajax_dynamo_support_close_feedback','dymamo_support_close_feedback');

/*
* Display Star Rater
*/
function dynamo_support_show_rater($current = '0', $clickable = true) {
$width = $current*20;
$r = '
<div class="rev_rating">
<ul class="star-rating">
<li class="current-rating" id="current-rating" style="width:'.$width.'%;"></li>';
	if($clickable === true) {
	$r .= '<ul id="ratelinks">
			<li><a href="javascript:void(0)" title="1 star out of 5" class="one-star">1</a></li>
			<li><a href="javascript:void(0)" title="2 stars out of 5" class="two-stars">2</a></li>
			<li><a href="javascript:void(0)" title="3 stars out of 5" class="three-stars">3</a></li>
			<li><a href="javascript:void(0)" title="4 stars out of 5" class="four-stars">4</a></li>
			<li><a href="javascript:void(0)" title="5 stars out of 5" class="five-stars">5</a></li>
		</ul>';
	}
$r .='</ul>
</div>
';
return $r;
}
/*
* Process Feedback form
*/
function dynamo_support_submit_feedback() {
	global $support_options;
	$name = esc_attr($_POST['name']);
	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$domain = filter_var(esc_attr($_POST['domain']),FILTER_SANITIZE_ENCODED);
	$subject = filter_var(esc_attr($_POST['subject'],FILTER_SANITIZE_SPECIAL_CHARS));
	$rating = filter_var($_POST['rating'], FILTER_SANITIZE_NUMBER_INT);
	$feedback = esc_attr(filter_var($_POST['feedback'],FILTER_SANITIZE_SPECIAL_CHARS));
	
	//Validate Data
	if(strlen($name) < 3) {
		echo 'Please Enter Your Name';
		die();
	}
	if(!is_email($email)) {
		echo 'Please Enter A Valid Email Address';
		die();
	}
	if(!is_numeric($rating) || $rating == '') {
		echo 'Please Give A Rating';
		die();
	}
	if($feedback == '' || strlen($feedback) < 10) {
		echo 'Please Enter Some Feedback';
		die();
	}
	$feedback = array(
		'name' => $name,
		'email' => $email,
		'domain' => $domain,
		'subject' => $subject,
		'rating' => $rating,
		'feedback' => $feedback,
		'approved' => '0',
		'show_widget' => '0',
		'show_shortcode' => '0',
		'date' => date('m/d/Y H:i:s',current_time('timestamp',1))
	);
	$support_options['feedback'][] = $feedback;
	update_option('dynamo_support_options',$support_options);
	echo '1';
	
	die();
}
add_action('wp_ajax_dynamo_support_submit_feedback','dynamo_support_submit_feedback');
add_action('wp_ajax_nopriv_dynamo_support_submit_feedback','dynamo_support_submit_feedback');

/*
* Feedback admin page
*/
function support_feedback() {
global $support_options;

if ($support_options['plugin_version'] != 'VZlWXRlVsNnUsR2MadEdaZVMaZVVB1TP') {

//Approve Feedback
if($_GET['approve-feedback'] != '' && isset($_GET['approve-feedback'])) {
	$k = $_GET['approve-feedback'];
	$support_options['feedback'][$k]['approved'] = 1;
	update_option('dynamo_support_options',$support_options);
	?>
	<div id="message" class="updated">
        <p><strong>
        Feedback Approved
        </strong></p>
    </div>
	<?php
}
//Un-Approve Feedback
if($_GET['unapprove-feedback'] != '' && isset($_GET['unapprove-feedback'])) {
	$k = $_GET['unapprove-feedback'];
	$support_options['feedback'][$k]['approved'] = 0;
	update_option('dynamo_support_options',$support_options);
	?>
	<div id="message" class="updated">
        <p><strong>
        Feedback Un-Approved
        </strong></p>
    </div>
	<?php
}
//Delete Feedback
if($_GET['trash-feedback'] != '' && isset($_GET['trash-feedback'])) {
	$k = $_GET['trash-feedback'];
	unset($support_options['feedback'][$k]);
	update_option('dynamo_support_options',$support_options);
	?>
	<div id="message" class="updated">
        <p><strong>
        Feedback Trashed
        </strong></p>
    </div>
	<?php
}
if($_GET['edit-feedback'] != '' && isset($_GET['edit-feedback'])) {
	$k = $_GET['edit-feedback']; 
	$f = $support_options['feedback'][$k];
	if($_POST['update-feedback']) {
		$name = esc_attr($_POST['name']);
		$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		$domain = filter_var(esc_attr($_POST['domain']),FILTER_SANITIZE_ENCODED);
		$subject = filter_var(esc_attr($_POST['subject'],FILTER_SANITIZE_SPECIAL_CHARS));
		
		$feedback = esc_attr(filter_var($_POST['feedback'],FILTER_SANITIZE_SPECIAL_CHARS));
		
		
		$support_options['feedback'][$k]['name'] = $name;
		$support_options['feedback'][$k]['email'] = $email;
		$support_options['feedback'][$k]['domain'] = $domain;
		$support_options['feedback'][$k]['subject'] = $subject;
		$support_options['feedback'][$k]['feedback'] = $feedback;
		update_option('dynamo_support_options',$support_options);
		?>
		<div id="message" class="updated">
			<p><strong>
			Feedback Saved
			</strong></p>
		</div>
		<?php
		$f = $support_options['feedback'][$k];
	}
?>
<div class="wrap">
	<div class="ds-header">
		<div id="icon-support" class="icon32"></div>
		<h2>Feedback</h2>
	</div>
	<div class="postbox">
		<h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span>Support Dynamo - Edit Feedback</span></h3>
		<div class="inside" style="padding:0 5px 5px 5px;">
			<form method="post" action="<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=support-feedback';?>&amp;edit-feedback=<?php echo $k; ?>">
				<br/>
				<strong>Name:</strong><br/><input name="name" size="80" type="text" value="<?php echo $f['name']; ?>" size="60"/><br/><br/>
				<strong>Email:</strong><br/><input name="email" type="text" size="80" value="<?php echo $f['email']; ?>" size="60"/><br/><br/>
				<strong>Domain:</strong><br/><input name="domain" type="text" size="80" value="<?php echo urldecode($f['domain']); ?>" size="60"/><br/><br/>
				<strong>Subject:</strong><br/><input type="text" name="subject" size="80" value="<?php echo $f['subject']; ?>" size="60"/><br/><br/>
				<strong>Feedback:</strong><br/><textarea name="feedback" cols="70" rows="15"><?php echo stripslashes($f['feedback']); ?></textarea><br/>
				<p class="submit">
					<input type="submit" value="Update &#187;" name="update-feedback"/>
				</p>
			</form>
		</div>
	</div>
</div>
<?php
	
} else {
?>
<div class="wrap">
	<div class="ds-header">
		<div id="icon-support" class="icon32"></div>
		<h2>Feedback</h2>
	</div>
	<table class="wp-list-table widefat fixed feedback" cellspacing="0">
		<thead>
			<tr>
				<th scope="col" id="cb" class="manage-column column-cb check-column">
					<input type="checkbox"/>
				</th>
				<th scope="col" id="date" class="manage-column column-date sortable desc">
					Date
				</th>
				<th scope="col" id="subject" class="manage-column column-subject">
					Subject
				</th>
				<th scope="col" id="feedback" class="manage-column column-feedback">
					Feedback
				</th>
				<th scope="col" id="Author" class="manage-column column-author">
					Author
				</th>
				<th scope="col" id="rating" class="manage-column column-rating">
					Rating
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th scope="col" id="cb" class="manage-column column-cb check-column">
					<input type="checkbox"/>
				</th>
				<th scope="col" id="date" class="manage-column column-date sortable desc">
					Date
				</th>
				<th scope="col" id="subject" class="manage-column column-subject">
					Subject
				</th>
				<th scope="col" id="feedback" class="manage-column column-feedback">
					Feedback
				</th>
				<th scope="col" id="Author" class="manage-column column-author">
					Author
				</th>
				<th scope="col" id="rating" class="manage-column column-rating">
					Rating
				</th>
			</tr>
		</tfoot>
		<tbody id="the-list">
			<?php dynamo_support_show_feedback(); ?>
		</tbody>
	</table>
</div>
<?php
}
} else {
	echo '<span style="color:#cc0000; font-weight:bold; font-size:16px; margin-left:10px;">This option is included with the Customer Interaction Manager and is enabled for Support Dynamo Pro users only. <a href="http://plugindynamo.com/cim-upgrade" title="Upgrade To Support Dynamo Pro Today" target="_blank">Click Here To Upgrade Now</a></span>';
}
}
function dynamo_support_show_feedback() {
	global $support_options;
	$feedback = $support_options['feedback'];
	if(is_array($feedback) && count($feedback)) {
		foreach($feedback as $k => $f) {
			if($f['approved'] == 0) {
				$class = 'unapproved';
			}
			?>
			<tr valign="top" class="<?php echo $class;?>">
				<th class="check-column">
					<input type="checkbox"/>
				</th>
				<td class="column-date">
				<?php echo $f['date']; ?>
				</td>
				<td class="column-subject">
				<?php echo $f['subject'];?>
				<div class="row-actions">
					<span class="edit"><a href="<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=support-feedback';?>&amp;edit-feedback=<?php echo $k; ?>">Edit</a></span> | 
					<span class="trash"><a href="<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=support-feedback';?>&amp;trash-feedback=<?php echo $k; ?>">Trash</a></span> | 
					<?php if($f['approved'] == 0) { ?>
					<span class="approve"><a href="<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=support-feedback';?>&amp;approve-feedback=<?php echo $k; ?>">Approve</a></span>
					<?php } else { ?>
					<span class="unapprove"><a href="<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=support-feedback';?>&amp;unapprove-feedback=<?php echo $k; ?>">Un-Approve</a></span>
					<?php } ?>
				</div>
				</td>
				<td class="column-feedback">
				<?php echo stripslashes($f['feedback']); ?>
				</td>
				<td class="column-author">
				<strong>Name:</strong> <?php echo $f['name']; ?><br/>
				<strong>E-Mail:</strong> <?php echo $f['email']; ?><br/>
				<strong>Domain:</strong> <?php echo urldecode($f['domain']);?>
				</td>
				<td class="column-rating">
					<?php echo dynamo_support_show_rater( $f['rating'], false); ?>
				</td>
			</tr>
			<?php
		}
	} else {
		?>
		<tr valign="top">
			<td colspan="6">You currently don't have any feedback to review</td>
		</tr>
		<?php
	}
}

global $support_options;

if ($support_options['plugin_version'] == '==AUWVUeZhFZzFWMaNVTWJVU') {
	if($support_options['report-bug'] === '1') {
		add_action('wp_footer','dynamo_support_report_bug');
	}
}


function dynamo_support_report_bug() {
	global $post;
	echo '<a href="#report-bug" id="ds-report-bug" title="Use this button to submit an error/issue with this page" rel="'.$post->ID.'"></a><input type="hidden" id="ajax-url" value="'.get_bloginfo('wpurl').'"/>';
}
?>