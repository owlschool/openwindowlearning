<?php
/*
* Price Calculator Pop-Up Modal Box
*/
require_once('../../../../wp-load.php');
global $support_options;
$support_options = get_option('dynamo_support_options');
if(is_user_logged_in() && current_user_can('manage_options')) {
$id = $_GET['id'];
?>
<div>
<?php
if($_POST['save-price']) {
	$support_options['price'][$id] = $_POST['price'];
	update_option('dynamo_support_options',$support_options);
	echo '<div class="notice" style="background:#FFFFE0; width:90%; border:1px solid #E6DB55; padding:3px; margin-bottom:15px;">Pricing Saved!</div>';
} 
if($_POST['apply-to-all']) {
	global $wpdb;
	
	if($support_options['integration'] == 'wishlist') {
		dynamo_support_wlm_update_all_price_calc($id);
	} else if ($support_options['integration'] == 'memberwingx') {
		dynamo_support_mwx_update_all_price_calc($id);
	}
	echo '<div class="notice" style="background:#FFFFE0; width:90%; border:1px solid #E6DB55; padding:3px; margin-bottom:15px;">Users Pricing Saved!</div>';
}
?>
<form method="post">
<div style="margin-bottom:10px; border-bottom:1px solid #DFDFDF; padding-bottom:10px;">
	<input type="checkbox" name="price[one-time][enabled]" value="1" <?php if($support_options['price'][$id]['one-time']['enabled'] == '1') { echo 'checked="checked"';}?>/> One time $
	<input type="text" size="5" name="price[one-time][price]" value="<?php echo $support_options['price'][$id]['one-time']['price']; ?>" style="margin-right:15px;"/>
</div>
<div style="margin-bottom:10px; border-bottom:1px solid #DFDFDF; padding-bottom:10px;">
	<input type="checkbox" name="price[repeat][enabled]" value="1" <?php if($support_options['price'][$id]['repeat']['enabled'] == '1') { echo 'checked="checked"';}?>/> Repeats every <input type="text" value="<?php echo $support_options['price'][$id]['repeat']['every']; ?>" size="2" name="price[repeat][every]"/>
<select name="price[repeat][repeater]">
	<option value="" <?php if($support_options['price'][$id]['repeat']['repeater'] == '') { echo 'selected'; }?>>Never</option>
	<option value="day" <?php if($support_options['price'][$id]['repeat']['repeater'] == 'day') { echo 'selected'; }?>>Days</option>
	<option value="week" <?php if($support_options['price'][$id]['repeat']['repeater'] == 'week') { echo 'selected'; }?>>Weeks</option>
	<option value="month" <?php if($support_options['price'][$id]['repeat']['repeater'] == 'month') { echo 'selected'; }?>>Months</option>
	<option value="year" <?php if($support_options['price'][$id]['repeat']['repeater'] == 'year') { echo 'selected'; }?>>Years</option>
</select>
 at $<input type="text" size="5" name="price[repeat][price]" value="<?php echo $support_options['price'][$id]['repeat']['price']; ?>" />
</div>

	<input type="submit" name="save-price" value="Save &#187;" style="color: #FFFFFF; font-weight: bold; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.3); background:#21759B; border-radius:11px; -moz-border-radius:11px; -webkit-border-radius:11px; cursor:pointer; border:1px solid #298CBA; font-size:12px !important; line-height:13px; padding:3px 8px; text-decoration:none; margin-top:10px; "/>
</form>
<form method="post">
	<input type="submit" name="apply-to-all" value="Apply Pricing To All Current Users &#187;" style="color: #FFFFFF; font-weight: bold; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.3); background:#21759B; border-radius:11px; -moz-border-radius:11px; -webkit-border-radius:11px; cursor:pointer; border:1px solid #298CBA; font-size:12px !important; line-height:13px; padding:3px 8px; text-decoration:none; margin-top:10px; "/>
</form
</div>
<?php
}
?>