<?php
//Dynamo Plugins Update Checker
if ( !class_exists('dynamoUpdateCheckerSup') ):
class dynamoUpdateCheckerSup {
	public $plugin = ''; //Plugin Name
	public $slug = ''; //Plugin Slug
	public $plugin_path = ''; //Plugin File Name
	public $plugin_activation_var = ''; //Activation 'activated' option var
	public $plugin_email = ''; //Email option var
	public $plugin_dir = ''; //Plugin Directory 

	//Class Constructor
	function __construct($plugin, $slug, $plugin_path, $plugin_activation_var, $plugin_email, $plugin_dir) {
		$this->plugin = $plugin;
		$this->slug = $slug;
		$this->plugin_path = $plugin_path;
		$this->local_ver = $this->get_plugin_version();
		$this->activated = get_option($plugin_activation_var);
		$this->activation_var = $plugin_activation_var;
		$this->plugin_dir = $plugin_dir;
		$this->remote_ver = $this->get_remote_version();
		$this->update_available = get_option('dyno_'.$this->slug.'_update_available');
		$this->email = $this->decode5t(get_option($plugin_email));
		$this->last_check = get_option('dynamo_'.$this->slug.'_last_check');
		$this->last_check_status = get_option('dynamo_'.$this->slug.'_last_check_status');
		//$this->m = $m;
		
		$this->install();
	}
	
	function install() {
		add_action('dynamo_'.$this->slug.'_twicedaily_check', array($this,'dynamo_check_ver'));
		
		
		// Schedule Cron Job For Twice Daily
		if(!wp_next_scheduled('dynamo_'.$this->slug.'_twicedaily_check')) {
			wp_schedule_event(time(), 'twicedaily', 'dynamo_'.$this->slug.'_twicedaily_check');
		}
		//Register De-Activation Function
		register_deactivation_hook(WP_PLUGIN_DIR .'/'.$this->plugin_path, array($this,'remove_cron_deactivate'));
		
		//Update Notices
		if($this->update_available == '1') {
			//Plugin table update notice
			add_action('after_plugin_row_'.$this->plugin_path.'',array($this,'show_plugin_table_update_notice'));
		}
		
	}
	//De-Activation Function
	function remove_cron_deactivate() {
		wp_clear_scheduled_hook('dynamo_'.$this->slug.'_twicedaily_check');
	}
	
	/*
	* Check Current Ver v Server Ver
	*/
	function dynamo_check_ver() {
			//if remote version is blank or could not connect to server die..
			if($this->remote_ver == '') {
				return;
			}
			//Compare Remote to local if remote is greater add update notices etc.
			if($this->remote_ver > $this->local_ver) {
				//update available
				add_option('dyno_'.$this->slug.'_update_available','1');
				//add remote ver to options
				add_option('dyno_'.$this->slug.'_remote_ver',$this->remote_ver);
				
			} else if($this->remote_ver == $this->local_ver) {
				//no update needed clear all update notifications (incase they havent been cleared yet)
				delete_option('dyno_'.$this->slug.'_update_available');
				delete_option('dyno_'.$this->slug.'_remote_ver');
			}
			
			//once all is done 
			
	}
	//Init updater check
	function dynamo_updater_init() {
		if(date('Y-m-d', strtotime('+7 day', strtotime($this->last_check))) <= date('Y-m-d', current_time('timestamp',1))  || $this->last_check == '' || $r === true ) {
			if($this->dynamo_check_user_status() == false) {
				
				$this->last_check_status = false;
				update_option('dynamo_'.$this->slug.'_last_check_status',$this->encode5t('false'));
			} else {
				$this->last_check_status = true;
				update_option('dynamo_'.$this->slug.'_last_check_status',$this->encode5t('true'));
			}
			update_option('dynamo_'.$this->slug.'_last_check',date('Y-m-d',current_time('timestamp',1)));
		} 
		return $this->decode5t(get_option('dynamo_'.$this->slug.'_last_check_status'));
	}
	function update_init() {
		?>
<div class="wrap">
	<div class="postbox">
		<h3 style="margin:0 0 0 0; padding:5px; font-size:12px;"><span><?php echo $this->plugin; ?> - Update Account</span></h3>
		<div class="inside" style="padding:0 5px 5px 5px;">
		
			<div id="dyno-content-holder" style="margin-top: 20px;">
			   <strong style="font-size:18px;">Oops.. It looks like your <?php echo $this->plugin;?> PluginDynamo.com account has expired!</strong>
			   <br/><br/>
			   <a href="http://plugindynamo.com/account-activations-<?php echo $this->slug;?>" target="_blank" title="Renew Your Account" rel="" class="orange-button">Click Here To Renew Your Account &#187;</a>
			   <br/><br/>
			   <p><strong>Note:</strong> If you beleive you are seeing this message in error and know that your <?php echo $this->plugin;?> PluginDynamo.com account is still active and in good standing, please try refreshing this page and ensure you have entered your the correct e-mail associated with your PluginDynamo.com account on the "Account" tab in the <?php echo $this->plugin;?> options.
			   <br/><br/>
			   If this fails, please submit a support ticket to <a href="mailto:support@plugindynamo.com" title="Contact PluginDynamo.com Support"><strong>Support@PluginDynamo.com</strong></a> from the e-mail address associated with your account or login to your PluginDynamo.com account and click on the Support link.</p>
			  
			</div>
			
			<div class="clear"></div>
		</div>
	</div>
</div>
		<?php
	}
	/*
	*Print out the upgrade box
	*/
	function print_update_form() {
	if($_POST['dyno_update']) {
		$this->dynamo_update();
	}
	if($_POST['force-chk-acct'] && $_POST['force-chk-acct'] != '') {
		//Force Check For Account Info
		$this->status = $this->dynamo_updater_init(true);
		$this->last_check_status = $this->decode5t(get_option('dynamo_'.$this->slug.'_last_check_status'));
		$this->last_check = get_option('dynamo_'.$this->slug.'_last_check');
	}
	$this->status = $this->decode5t(get_option('dynamo_'.$this->slug.'_last_check_status'));
	$this->activated = get_option($this->activation_var);
	 ?>
	  <form id="force-update-check" action="" method="post">
	 <p>To upgrade <?php echo $this->plugin;?>, please verify your PluginDynamo.com account <?php echo $this->plugin;?> Support &amp; Upgrade Status is Active.<br/><br/>The e-mail used to check your account status is: <b><?php echo $this->email;?></b><br/><br/>
                    <?php
                        if($this->activated == 'activated' && $this->status == true) { 
                    ?>
                        <span style="font-size:16px; line-height:22px;"><?php echo $this->plugin;?> Support &amp; Upgrade Status: <b style="color:green;">Active</b></span>.
                    <?php
                        } else {
                    ?> 
                        <span style="font-size:16px; line-height:22px;"><?php echo $this->plugin;?> Support &amp; Upgrade Status: <b style="color:red;">In-Active</b></span>    
                    <?php
                        }
                    ?>
				<input class="button-primary" type="submit" value="Check Account Status &#187;" name="force-chk-acct" style="margin:5px auto; width:150px; display:block;"/>
                <br/><br/><b>* NOTE *</b> - Ensure you have activated the plugin on the account tab to receive upgrades.</p>
				</form>
			    <form action="" method="POST" id="activate-form">
				    <?php
					    if($this->activated == 'activated' && $this->status == true && $this->update_available == '1') { 
							//Activates status true and update = update
                    ?>          
						<strong style="font-size:16px;">There is an upgrade available for <?php echo $this->plugin;?><br/><br/>
						To upgrade to version <?php echo get_option('dyno_'.$this->slug.'_remote_ver'); ?> click the upgrade plugin button below:</strong><br/><br/>
                    <input type="hidden" name="dynamo_check_update_status" value="<?php echo $this->status;?>" />
				    <input type="submit" name="dyno_update" value="Upgrade Plugin &#187;" id="dyno_activate" class="orange-button"/>
				    <?php
					    } else if($this->activated == 'activated' && $this->status == false && $this->update_available == '1') {
							//Activated, but status failed and update available = renew
						?>
							<strong style="font-size:16px;">There is an upgrade available for <?php echo $this->plugin;?><br/><br/>To upgrade to version <?php echo get_option('dyno_'.$this->slug.'_remote_ver'); ?> please <a href="http://plugindynamo.com/renew?prod=<?php echo $this->slug;?>" title="Renew Your Subscription" target="_blank"/>renew your PluginDynamo.com Subscription</a></strong>
						<?php
						} else if($this->activated == 'deactivated' && $this->status == false && $this->update_available == '1') {
							//Not activated status failed and update available = activate
						?>
							<strong style="font-size:16px;">There is an upgrade available for <?php echo $this->plugin;?><br/><br/>To gain access to version <?php echo get_option('dyno_'.$this->slug.'_remote_ver'); ?> please click on the Account tab above and activate the plugin.<br/><br/>If your currently do not have a <a href="http://plugindynamo.com?prod=<?php echo $this->slug;?>" title="Plugin Dynamo" target="_blank">PluginDynamo.com</a> Account <a href="http://plugindynamo.com?prod=<?php echo $this->slug;?>" title="Plugin Dynamo" target="_blank">Click Here To Register &#187;</a></strong>
						<?php
						} else {
							//No Update = up to date
						?>
							<strong style="font-size:16px;">You are currently running the most recent version of <?php echo $this->plugin;?> <?php echo $this->local_ver;?> - there is no upgrade at this time</strong>
						<?php
						}
				    ?>
				    <br/><br/>
			    </form>
	 <?php
	}
	
	/*
	*do update
	*/
	function dynamo_update() {
		$check = $_POST['dynamo_check_update_status'];    
		if ($check == true && $this->activated == 'activated') {
            
            require_once(ABSPATH . 'wp-admin/admin.php');

            include_once (ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
            
            $upgrader = new Plugin_Upgrader( new Plugin_Upgrader_Skin( compact('title', 'nonce', 'url', 'plugin') ) );
            
            $upgrader->init();
            $upgrader->upgrade_strings();

            

            add_filter('upgrader_pre_install', array(&$upgrader, 'deactivate_plugin_before_upgrade'), 10, 2);
            add_filter('upgrader_clear_destination', array(&$upgrader, 'delete_old_plugin'), 10, 4);
            //'source_selection' => array(&$this, 'source_selection'), //theres a track ticket to move up the directory for zip's which are made a bit differently, useful for non-.org plugins.

			$details = array(
                        'package' => 'http://cdn.plugindynamo.com/plugins/'.$this->slug.'/'.$this->slug.'.zip',
                        'destination' => WP_PLUGIN_DIR,
                        'clear_destination' => true,
                        'clear_working' => true,
                        'hook_extra' => array(
                                    'plugin' => $this->plugin_path
                        )
                    );
			
            $upgrader->run($details);		

            // Cleanup our hooks, incase something else does a upgrade on this connection.
            remove_filter('upgrader_pre_install', array(&$upgrader, 'deactivate_plugin_before_upgrade'));
            remove_filter('upgrader_clear_destination', array(&$upgrader, 'delete_old_plugin'));

            /*if ( ! $upgrader->result || is_wp_error($upgrader->result) )
                return $upgrader->result;*/

            // Force refresh of plugin update information
            delete_site_transient('update_plugins');
            
            
            
            // Get already-active plugins   
            $active_plugins = get_option('active_plugins');
            // Make sure your plugin isn't active
            if (isset($active_plugins[$this->plugin_path]))
                return;

            // Include the plugin.php file so you have access to the activate_plugin() function
            require_once(ABSPATH .'/wp-admin/includes/plugin.php');
            // Activate your plugin
            activate_plugin($this->plugin_path);
            
			
			//After Update Delete Update Ready DB Stuff
			delete_option('dyno_'.$this->slug.'_update_available');
			delete_option('dyno_'.$this->slug.'_remote_ver');
            
            echo "<script language='javascript'>window.location = window.location;</script>";
			return true;
        }
        
        
        
		return false;
	}
	
	/*
	* Check Users Status
	*/
	function dynamo_check_user_status() {	
		//The Activation page inserts the $email var for that specific plugin
		$email = $this->email;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://plugindynamo.com/fsjlhf546gfdg21cx/commSD.php?email='.$this->encode5t($email).'&prod='.$this->encode5t($this->slug).'');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = @curl_exec($ch);
		curl_close($ch);
		if($output == 'UserID') {
			update_option($this->activation_var,'deactivated');
			return false;
		} else {
			$membership = $this->cleanit($output);
			if($membership != '') {
				$membership = explode('|',$membership);
				$type = $membership[1];
				$membership = $membership[0];
				$this->m = $type;
				if($membership == 'Success') {
					update_option($this->activation_var,'activated');
					return true;
				} else {
					if($type == 'Monthly') {
						update_option($this->activation_var,'deactivated');
						return false;
					} else if($type == 'Full'){
						update_option($this->activation_var,'activated');
						return false;
					} else {
						update_option($this->activation_var,'deactivated');
						return false;
					}
				}
			} else { 
				update_option($this->activation_var,'deactivated');
				return false;
			}
		}		
	}
	
	/*
	* Plugin Table Update Notice
	*/
	function show_plugin_table_update_notice() {
				echo "<tr class='plugin-update-tr'><td colspan='5' class='plugin-update'>";
				echo '<div class="update-message">There is a new version of '.$this->plugin.' available. Download version '.get_option('dyno_'.$this->slug.'_remote_ver').' from the '.$this->plugin.' > Support tab.</div>';
				echo "</td></tr>";
	}
	
	/*
	* Get Plugin Version
	*/
	function get_plugin_version() {
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$plugin_folder = get_plugins('/' . $this->plugin_dir );	
		return $plugin_folder[$this->plugin_path]['Version'];
	}	
	
	/*
	* Get Remote Plugin Version
	*/
	function get_remote_version() {
		$ver = @file_get_contents('http://cdn.plugindynamo.com/plugins/'.$this->slug.'/v.txt');
		update_option('dynamo_'.$this->slug.'_remote_ver',$ver);
		return $ver;
	}
	function cleanit($str) {
		$str = @trim($str);
			if(get_magic_quotes_gpc()) {
				$str = stripslashes($str);
			}
		return $str;
	}

	function encode5t($str)
	{
	  for($i=0; $i<5;$i++)
	  {
		$str=strrev(base64_encode($str)); 
	  }
	  return $str;
	}


	function decode5t($str)
	{
	  for($i=0; $i<5;$i++)
	  {
		$str=base64_decode(strrev($str)); 
	  }
	  return $str;
	}

}

endif;
?>