<?php
/**
 * Plugin Name: Quick Links
 * Plugin URI: http://wellthemes.com
 * Description: Displays Quick Links
 * Version: 1.0
 * Author: Nalin Singal
 *
 */
 
 /**
 * Add function to widgets_init that'll load our widget.
 */
add_action('widgets_init','wellthemes_quick_links_widgets');

function wellthemes_quick_links_widgets() {
	register_widget('wellthemes_quick_links_widget');
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class wellthemes_quick_links_widget extends WP_Widget {
	
	/**
	 * Widget setup.
	 */
	function wellthemes_quick_links_widget() {
		
		/* Widget settings. */
		$widget_ops = array('classname' => 'widget_quick_links','description' => 'Display Quick Links');
		
		/* Create the widget. */
		$this->WP_Widget('wellthemes_quick_links_widget', 'Display Quick Links', $widget_ops);

	}
	
	/**
	 * display the widget on the screen.
	 */	
	function widget( $args, $instance ) {
		extract( $args );
		//user settings.
		if (ICL_LANGUAGE_CODE == 'es') {
		    $title = 'Examenes: Sitios Web';
		} else {
    		    $title = apply_filters('widget_title', $instance['title'] );
    		}
    		
		$ql_link_image_1 = $instance['ql_link_image_1'];
		$ql_link_1 = $instance['ql_link_1'];
		$ql_link_text_1 = $instance['ql_link_text_1'];
		$ql_link_image_2 = $instance['ql_link_image_2'];
		$ql_link_text_2 = $instance['ql_link_text_2'];
		$ql_link_2 = $instance['ql_link_2'];
		$ql_link_image_3 = $instance['ql_link_image_3'];
		$ql_link_3 = $instance['ql_link_3'];
		$ql_link_text_3 = $instance['ql_link_text_3'];

		echo $before_widget;				
		
		if ( $title ){ ?>
			<div class="widget-title">
				<span class="title-sep main-color-bg"></span>
				<h4 class="title"><?php echo $title; ?></h4>
			</div>
		<?php }
		
		?>
		<div class="wrap">
			<ul>		
					
				<?php if ($ql_link_1){ ?>
					<li class="quick_link">
						<div class="icon">
							<a target="_blank" href="<?php echo $ql_link_1; ?>">
							<img src="<?php echo $ql_link_image_1; ?>" />
							</a>
						</div>
						
						<div class="right">						
							<h3>					
								<a target="_blank" href="<?php echo $ql_link_1; ?>">
									<?php echo $ql_link_text_1; ?>
								</a>						
							</h3>
						</div>	
					</li>
				<?php } ?>
				
				<?php if ($ql_link_2){ ?>
					<li class="quick_link">
						<div class="icon">
							<a target="_blank" href="<?php echo $ql_link_2; ?>">
							<img src="<?php echo $ql_link_image_2; ?>" />
							</a>
						</div>
						
						<div class="right">						
							<h3>					
								<a target="_blank" href="<?php echo $ql_link_2; ?>">
									<?php echo $ql_link_text_2; ?>
								</a>						
							</h3>
						</div>	

					</li>
				<?php } ?>
				
				<?php if ($ql_link_3){ ?>
					<li class="quick_link">
						<div class="icon">
							<a target="_blank" href="<?php echo $ql_link_3; ?>">
							<img src="<?php echo $ql_link_image_3; ?>" />
							</a>
						</div>
						
						<div class="right">						
							<h3>					
								<a target="_blank" href="<?php echo $ql_link_3; ?>">
									<?php echo $ql_link_text_3; ?>
								</a>						
							</h3>
						</div>	

					</li>
				<?php } ?>
				
			</ul>
				
		</div><!-- /wrap -->			
		<?php 
		echo $after_widget;
	}
	
	/**
	 * update widget settings
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['ql_link_image_1'] = $new_instance['ql_link_image_1'];
		$instance['ql_link_1'] = $new_instance['ql_link_1'];
		$instance['ql_link_text_1'] = $new_instance['ql_link_text_1'];
		$instance['ql_link_image_2'] = $new_instance['ql_link_image_2'];
		$instance['ql_link_2'] = $new_instance['ql_link_2'];
		$instance['ql_link_text_2'] = $new_instance['ql_link_text_2'];
		$instance['ql_link_image_3'] = $new_instance['ql_link_image_3'];
		$instance['ql_link_3'] = $new_instance['ql_link_3'];
		$instance['ql_link_text_3'] = $new_instance['ql_link_text_3'];
		return $instance;
	}
	
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	 
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 
			'title' => '',
			'ql_link_image_1' => '',
			'ql_link_1' => '',
			'ql_link_text_1' => '',
			'ql_link_image_2' => '',
			'ql_link_2' => '',
			'ql_link_text_2' => '',
			'ql_link_image_3' => '',
			'ql_link_3' => '',
			'ql_link_text_3' => '',
 		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wellthemes'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'ql_link_text_1' ); ?>">Link 1 Name</label>
			<input id="<?php echo $this->get_field_id( 'ql_link_text_1' ); ?>" name="<?php echo $this->get_field_name( 'ql_link_text_1' ); ?>" value="<?php echo $instance['ql_link_text_1']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'ql_link_1' ); ?>">Link 1 URL</label>
			<input id="<?php echo $this->get_field_id( 'ql_link_1' ); ?>" name="<?php echo $this->get_field_name( 'ql_link_1' ); ?>" value="<?php echo $instance['ql_link_1']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'ql_link_image_1' ); ?>">Link 1 Image URL</label>
			<input id="<?php echo $this->get_field_id( 'ql_link_image_1' ); ?>" name="<?php echo $this->get_field_name( 'ql_link_image_1' ); ?>" value="<?php echo $instance['ql_link_image_1']; ?>" class="widefat" />
		</p>

		
		
		<p>
			<label for="<?php echo $this->get_field_id( 'ql_link_text_2' ); ?>">Link 2 Name</label>
			<input id="<?php echo $this->get_field_id( 'ql_link_text_2' ); ?>" name="<?php echo $this->get_field_name( 'ql_link_text_2' ); ?>" value="<?php echo $instance['ql_link_text_2']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'ql_link_2' ); ?>">Link 2 URL</label>
			<input id="<?php echo $this->get_field_id( 'ql_link_2' ); ?>" name="<?php echo $this->get_field_name( 'ql_link_2' ); ?>" value="<?php echo $instance['ql_link_2']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'ql_link_image_2' ); ?>">Link 2 Image URL</label>
			<input id="<?php echo $this->get_field_id( 'ql_link_image_2' ); ?>" name="<?php echo $this->get_field_name( 'ql_link_image_2' ); ?>" value="<?php echo $instance['ql_link_image_2']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'ql_link_text_3' ); ?>">Link 3 Name</label>
			<input id="<?php echo $this->get_field_id( 'ql_link_text_3' ); ?>" name="<?php echo $this->get_field_name( 'ql_link_text_3' ); ?>" value="<?php echo $instance['ql_link_text_3']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'ql_link_3' ); ?>">Link 3 URL</label>
			<input id="<?php echo $this->get_field_id( 'ql_link_3' ); ?>" name="<?php echo $this->get_field_name( 'ql_link_3' ); ?>" value="<?php echo $instance['ql_link_3']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'ql_link_image_3' ); ?>">Link 3 Image URL</label>
			<input id="<?php echo $this->get_field_id( 'ql_link_image_3' ); ?>" name="<?php echo $this->get_field_name( 'ql_link_image_3' ); ?>" value="<?php echo $instance['ql_link_image_3']; ?>" class="widefat" />
		</p>

		

	<?php 
	}
} //end class