<?php
/*
* Edit comment query on ticket reply link to only show comments from tickets
*/
add_action('pre_get_comments','dynamo_support_ticket_comments');
function dynamo_support_ticket_comments($args) {
	global $pagenow;
	if(is_admin() && $pagenow == 'edit-comments.php' && $_REQUEST['comment_status'] === 'ticket') {
		$args->query_vars['post_type'] = 'ticket'; 
	}	
}
/*
* Add ticker replys link to comment page links
*/
function dynamo_support_ticket_comment_link($links) {
	$link = 'edit-comments.php';
	$ticketcount = dynamo_support_count_ticket_comments();
	$link = add_query_arg( 'comment_status', 'ticket', $link );
	$links['ticket'] = '<a href="'.$link.'">Question Replys <span class="count">('.$ticketcount.')</span></a>';
	return $links;
}
add_action('comment_status_links','dynamo_support_ticket_comment_link');

/*
* Count ticket comments
*/
function dynamo_support_count_ticket_comments() {
	global $wpdb;
	$comments = $wpdb->prefix.'comments';
	$post = $wpdb->prefix.'posts';
	$sql = "SELECT COUNT($comments.comment_ID) FROM $comments INNER JOIN $post ON $post.ID = $comments.comment_post_ID WHERE $post.post_type = 'ticket'";
	return $wpdb->get_var($sql);
}
?>