	<div class="ds-header">
		<div id="icon-support" class="icon32"></div>
		<h2>Support Dynamo  v<?php echo dynamo_support_get_plugin_version();?></h2>
		<div>
			<a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_" title="Support Dynamo Overview" <?php if($_GET['view'] == '') {?>class="current"<?php } ?>>Overview</a>
			<a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=settings" title="Support Dynamo General Settings" <?php if($_GET['view'] == 'settings') {?>class="current"<?php } ?>>Settings</a>
			<a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=account" title="View Your Support Dynamo Account Status" <?php if($_GET['view'] == 'account') {?>class="current"<?php } ?>>Account</a>
			<a href="<?php echo get_bloginfo('wpurl');?>/wp-admin/admin.php?page=dynamo_support_&view=support" title="Get Support For Dynamo Support" <?php if($_GET['view'] == 'support') {?>class="current"<?php } ?>>Support</a>
			<a id="affiliates-green" href="http://plugindynamo.com/sd-affiliates" target="_blank" title="Become a Click Heat Dynamo Affiliate Today!">Affiliate<em>$</em></a>
		</div>
	</div>