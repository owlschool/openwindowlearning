<?php
/**
 * Plugin Name: WellThemes: Single Ad Widget
 * Plugin URI: http://wellthemes.com
 * Description: Widget to display 300x300px ads in the sidebar of the theme.
 * Version: 1.0
 * Author: WellThemes Team
 * Author URI: http://wellthemes.com
 *
 */
 
 /**
 * Add function to widgets_init that'll load our widget.
 */
add_action('widgets_init','wellthemes_adsingle_widgets');

function wellthemes_adsingle_widgets(){
	register_widget("wellthemes_adsingle_widget");
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class wellthemes_adsingle_widget extends WP_widget{
	
	/**
	 * Widget setup.
	 */
	function wellthemes_adsingle_widget(){
		
		/* Widget settings. */
		$widget_ops = array('classname' => 'widget_adsingle', 'description' => 'Displays Single full size ad in the sidebar.');
		
		/* Create the widget. */
		$this->WP_Widget('wellthemes_adsingle_widget', 'WellThemes: Large Ad', $widget_ops);		
	}
	
	/**
	 *display the widget on the screen.
	 */
	function widget($args,$instance){
		extract($args);
			$title = apply_filters('widget_title', $instance['title'] );
			$link = $instance['link'];
			$image = $instance['image'];
			
			echo $before_widget;
			
			if ( $title ){ ?>
			<div class="widget-title">
				<h4 class="title"><?php echo $title; ?></h4>
			</div>
		<?php }
			
			if($image) { ?>	
				<div class="outer-wrap">
					<div class="in-wrap">
						<a href="<?php echo $link; ?>"><img src="<?php echo $image; ?>" /></a>
					</div>
				</div>
	  <?php }
			echo $after_widget;
	}
	
	/**
	 * update widget settings
	 */
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['link'] = $new_instance['link'];
		$instance['image'] = $new_instance['image'];
		
		return $instance;
	}
	
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form($instance){
		$defaults = array('title' => 'Sponsor', 'link' => '#', 'image' => get_template_directory_uri().'/images/ad265.png');
		$instance = wp_parse_args((array) $instance, $defaults);
		?>	
		
		<p>			
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link Url:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" value="<?php echo $instance['link']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" value="<?php echo $instance['image']; ?>" />
		</p>
		<?php
	}
}
?>