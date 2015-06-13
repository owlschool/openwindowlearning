<?php
/*
* Settings Support Tabs
* Main tab navigation on settings page
*/
?>
<ul id="support-tabs">
	<li><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=settings" title="General Settings" <?php if($_GET['view'] == 'settings') { ?>class="current"<?php } ?>>General Settings</a></li>
	<li><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=email-settings" title="Email Settings" <?php if($_GET['view'] == 'email-settings') { ?>class="current"<?php } ?>>Email Settings</a></li>
	<li><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=roles" title="Role Management" <?php if($_GET['view'] == 'roles') { ?>class="current"<?php } ?>>Role Management</a></li>
	<li><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=auto-responses" title="Role Management" <?php if($_GET['view'] == 'auto-responses') { ?>class="current"<?php } ?>>Auto Responses</a></li>
	<li><a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=inputs" title="Form Inputs" <?php if($_GET['view'] == 'inputs') { ?>class="current"<?php } ?>>Form Inputs</a></li>
</ul>
<?php
?>