<?php
/*
* Dynamo Support Ticket Notes system
*/
//Require WP_Load Script

require_once('../../../../wp-load.php');
if(is_user_logged_in() && current_user_can('edit_tickets')) {
?>
<html>
<title>View Question Notes</title>
<head>
	<style type="text/css">
body {
background:#f5f5f5;
}
#notes-nav {
width:605px;
height:41px;
border-bottom:2px solid #ccc;
margin-top:10px;
box-shadow:inset 0 -5px 5px #eee;
list-style:none;
margin:0;
}
#notes-nav li {
float:left;
height:41px;
position:relative;
}
#notes-nav li a {
border-radius:8px 8px 0 0;
-moz-border-radius:8px 8px 0 0;
-webkit-border-radius:8px 8px 0 0;
background:#fff;
height:16px;
display:block;
font-size:22px;
padding:12px 20px;
margin:0 15px 0 0;top:1px;
border:1px solid #ccc;
border-bottom:2px solid #ccc;
text-decoration:none;
box-shadow:0 -1px 1px #fff, inset 0 5px 5px #f2f2f2;
color: #21759B;
font-family: sans-serif;
line-height: 16.7833px;
}
#notes-nav li a.current, #notes-nav li a:hover {
background:#f5f5f5;
border-bottom:2px solid #f5f5f5;
color:#d54e21;
}
	</style>
</head>
<body>
<?php


$post = get_post($_GET['id']);
$user = $_GET['user'];
$userdata = get_userdata($_GET['author']);
$view = $_GET['view'];

$usernotes = get_user_meta($userdata->ID,'user_notes', true);

$ticknotes = get_post_meta($post->ID,'ticket_notes',true);

if($_POST['note-submit'] && !empty($_POST['note-submit'])) {
	if($_GET['view'] == 'user') {
		$usernotes[] = array('date' => $_POST['date'], 'user' => $_POST['user'], 'note' => $_POST['note']);
		update_user_meta($userdata->ID,'user_notes',$usernotes);
		if($_POST['include-opposite']) {
			$ticknotes[] = array('date' => $_POST['date'], 'user' => $_POST['user'], 'note' => $_POST['note']);
			update_post_meta($post->ID,'ticket_notes',$ticknotes);
		}
		
	} else {
		$ticknotes[] = array('date' => $_POST['date'], 'user' => $_POST['user'], 'note' => $_POST['note']);
		update_post_meta($post->ID,'ticket_notes',$ticknotes);
		if($_POST['include-opposite']) {
			$usernotes[] = array('date' => $_POST['date'], 'user' => $_POST['user'], 'note' => $_POST['note']);
			update_user_meta($userdata->ID,'user_notes',$usernotes);
		}
		
	}
	$usernotes = get_user_meta($userdata->ID,'user_notes', true);
	$ticknotes = get_post_meta($post->ID,'ticket_notes',true);
}



if(is_array($ticknotes) && !empty($ticknotes)) {
	$tickstatus = '1';
	$tickcount = count($ticknotes);
} else {
	$tickcount = 0;
}

if(is_array($usernotes) && !empty($usernotes)) {
	$userstatus = '1';
	$usercount = count($usernotes);
} else {
	$usercount = 0;
}

?>
<ul id="notes-nav">
	<li><a href="<?php echo get_bloginfo('wpurl');?>/wp-content/plugins/dynamo-support/includes/dynamo-support-notes.php?id=<?php echo $_GET['id'];?>&user=<?php echo $_GET['user'];?>&author=<?php echo $_GET['author']; ?>" class="tick-note <?php if($view == '') { echo 'current'; } ?>" title="View Question Notes" >Question Notes (<?php echo $tickcount; ?>)</a></li>
	<li><a href="<?php echo get_bloginfo('wpurl');?>/wp-content/plugins/dynamo-support/includes/dynamo-support-notes.php?id=<?php echo $_GET['id'];?>&user=<?php echo $_GET['user'];?>&view=user&author=<?php echo $_GET['author']; ?>" class="user-note <?php if($view == 'user') { echo 'current'; } ?>" title="View User Notes">User Notes (<?php echo $usercount;?>)</a></li>
</ul>
<?php

//Save Note Function
if($_POST['note-submit'] && !empty($_POST['note-submit'])) {
	?>
	<div style="background:#FFFFE0; border:1px solid #E6DB55; margin:5px 0 15px 0; boredr-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; padding:0 0.6em;">
		<p style="margin:1em 0; font-size:12px; padding:2px; line-height:140%;"><strong>Note Saved!</strong></p>
	</div>
	<?php
}



switch($view) {
default:

echo '<h2>Question Notes: '.$post->post_title.'</h2>';
if ($support_options['plugin_version'] == '==AUWVUeZhFZzFWMaNVTWJVU') {
if($tickstatus === '1') {
	foreach($ticknotes as $k => $v) {
		$author = get_userdata($v['user']);
	?>
		<div class="note note-<?php echo $k;?>" style="margin-bottom:20px; border-bottom:1px solid #333;">
			<div class="notes-meta"><b>By:</b> <i><?php echo $author->display_name; ?></i>, <b>On:</b> <i><?php echo $v['date']; ?></i></div>
			<p style="margin-top:6px;"><?php echo $v['note']; ?></p>
		</div>
	<?php
		unset($author);
	}
} else {
	echo 'This question currently has no notes attached to it, if you would like to add a note please do so below.';
}
} else {
  echo '<span style="color:#cc0000; font-weight:bold; font-size:12px; margin-left:10px;">This option is included with the Customer Interaction Manager and is enabled for Support Dynamo Pro users only. <a href="http://plugindynamo.com/cim-upgrade" title="Upgrade To Support Dynamo Pro Today" target="_blank">Click Here To Upgrade Now</a></span>';
}
break;
case 'user':
echo '<h2>User Notes: '.$userdata->user_nicename.'</h2>';
if ($support_options['plugin_version'] == '==AUWVUeZhFZzFWMaNVTWJVU') {
if($userstatus === '1') {
	foreach($usernotes as $k => $v) {
		$author = get_userdata($v['user']);
	?>
		<div class="note note-<?php echo $k;?>" style="margin-bottom:20px; border-bottom:1px solid #333;">
			<div class="notes-meta"><b>By:</b> <i><?php echo $author->display_name; ?></i>, <b>On:</b> <i><?php echo $v['date']; ?></i></div>
			<p style="margin-top:6px;"><?php echo $v['note']; ?></p>
		</div>
	<?php
		unset($author);
	}
} else {
	echo 'This user currently has no notes attached , if you would like to add a note please do so below.';
}
} else {
 echo '<span style="color:#cc0000; font-weight:bold; font-size:12px; margin-left:10px;">This option is included with the Customer Interaction Manager and is enabled for Support Dynamo Pro users only. <a href="http://plugindynamo.com/cim-upgrade" title="Upgrade To Support Dynamo Pro Today" target="_blank">Click Here To Upgrade Now</a></span>';
}

break;
}
?>
<br/><br/>
<form method="post" action="">
<label for="note"><b>Enter Your Note Below:</b></label><br/>
<textarea style="width:100%; height:200px;" id="note" name="note"></textarea><br/>
<input type="hidden" name="user" value="<?php echo $user;?>"/>
<input type="hidden" name="date" value="<?php echo current_time('mysql',0); ?>"/>
<input type="submit" name="note-submit" value="Save Note &#0187;"/> <input type="checkbox" name="include-opposite" value="1"/><?php if($view == 'user') { echo 'Include in Question Notes'; } else { echo 'Include in User Notes'; } ?>
</form>
</body>
</html>
<?php
} else {
die('Access Denied');
}
?>