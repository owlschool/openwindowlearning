<?php
require( get_template_directory() . '/framework/functions.php' );
require( get_template_directory() . '/learndash/functions.php' );
/**
 * Set the format for the more in excerpt, return ... instead of [...]
 */ 
function wellthemes_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'wellthemes_excerpt_more');

// custom excerpt length
function wellthemes_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'wellthemes_excerpt_length');

function pmpro_non_member_text_filter_override($existing_page)
{
    $this_postid = url_to_postid( 'http://www.gedboard.com/tutoring-no-access/' );
    $content_post = get_post($this_postid);
    $content = $content_post->post_content;
    //$content = apply_filters('the_content', $content);
    return $content;
}

add_filter('pmpro_non_member_text_filter', 'pmpro_non_member_text_filter_override', 7, 1);
add_filter('pmpro_not_logged_in_text_filter', 'pmpro_non_member_text_filter_override', 7, 1);


/*
  Change cancellation to set expiration date for next payment instead of cancelling immediately.
	
	Assumes orders are generated for each payment (i.e. your webhooks/etc are setup correctly).
*/
//give users their level back with an expiration
function my_pmpro_after_change_membership_level($level_id, $user_id)
{
	//are we on the cancel page?
	global $pmpro_pages, $wpdb;
	if(is_page($pmpro_pages['cancel']))
	{
		/*
			okay, let's give the user his old level back with an expiration based on his subscription date
		*/
		//get last order
		$order = new MemberOrder();
		$order->getLastMemberOrder($user_id, "cancelled");
		
		//get the last level they had		
		$level = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_memberships_users WHERE membership_id = '" . $order->membership_id . "' AND user_id = '" . $user_id . "' ORDER BY id DESC LIMIT 1");

		//can't do this if the level isn't recurring
		if(empty($level->cycle_number))
			return false;
				
		//can't do this unless we find an order and level
		if(empty($order->id) || empty($level))
			return false;
			
		//last payment date
		$lastdate = date("Y-m-d", $order->timestamp);
				
		//next payment date
		$nextdate = $wpdb->get_var("SELECT UNIX_TIMESTAMP('" . $lastdate . "' + INTERVAL " . $level->cycle_number . " " . $level->cycle_period . ")");		
				
		//if the date in the future?
		if($nextdate - time() > 0)
		{						
			//give them their level back with the expiration date set
			$old_level = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_memberships_users WHERE membership_id = '" . $order->membership_id . "' AND user_id = '" . $user_id . "' ORDER BY id DESC LIMIT 1", ARRAY_A);
			$old_level['enddate'] = date("Y-m-d H:i:s", $nextdate);
						
			//disable this hook so we don't loop
			remove_action("pmpro_after_change_membership_level", "my_pmpro_after_change_membership_level", 10, 2);
			
			//change level
			pmpro_changeMembershipLevel($old_level, $user_id);
			
			//add the action back just in case
			add_action("pmpro_after_change_membership_level", "my_pmpro_after_change_membership_level", 10, 2);
			
			//change message shown on cancel page
			add_filter("gettext", "my_gettext_cancel_text", 10, 3);
		}
	}
}
add_action("pmpro_after_change_membership_level", "my_pmpro_after_change_membership_level", 10, 2);
 
//this replaces the cancellation text so people know they'll still have access for a certain amount of time
function  my_gettext_cancel_text($translated_text, $text, $domain)
{
	if($domain == "pmpro" && $text == "Your membership has been cancelled.")
	{
		global $current_user;
		$translated_text = "Your recurring subscription has been cancelled. Your active membership will expire on " . date(get_option("date_format"), pmpro_next_payment($current_user->ID, "cancelled")) . ".";
	}
	
	return $translated_text;
}
 
//want to update the cancellation email as well
function my_pmpro_email_body($body, $email)
{
	if($email->template == "cancel")
	{
		global $wpdb;
		$user_id = $wpdb->get_var("SELECT ID FROM $wpdb->users WHERE user_email = '" . esc_sql($email->email) . "' LIMIT 1");
		if(!empty($user_id))
		{
			$expiration_date = pmpro_next_payment($user_id);
			
			//if the date in the future?
			if($expiration_date - time() > 0)
			{						
				$body .= "<p>Your access will expire on " . date(get_option("date_format"), $expiration_date) . ".</p>";
			}
		}
	}
	
	return $body;
}
add_filter("pmpro_email_body", "my_pmpro_email_body", 10, 2);

?>