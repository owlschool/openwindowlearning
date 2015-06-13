<?php
/**
 * Plugin Name: State Information
 * Plugin URI: http://wellthemes.com
 * Description: Displays State Information
 * Version: 1.0
 * Author: Nalin Singal
 *
 */
 
 /**
 * Add function to widgets_init that'll load our widget.
 */
add_action('widgets_init','wellthemes_state_information_widgets');

function wellthemes_state_information_widgets() {
	register_widget('wellthemes_state_information_widget');
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class wellthemes_state_information_widget extends WP_Widget {
	
	/**
	 * Widget setup.
	 */
	function wellthemes_state_information_widget() {
		
		/* Widget settings. */
		$widget_ops = array('classname' => 'widget_state_information','description' => 'Display State Information');
		
		/* Create the widget. */
		$this->WP_Widget('wellthemes_state_information_widget', 'Display State Information', $widget_ops);

	}
	
	/**
	 * display the widget on the screen.
	 */	
	function widget( $args, $instance ) {
		extract( $args );
		//user settings.
		if (ICL_LANGUAGE_CODE == 'es') {
		    $title = '¿Cuál es el Estado Donde Vives?';
		} else {
  		    $title = apply_filters('widget_title', $instance['title'] );
  		}
		$partner_title = $instance['si_partner_title'];
		$partner_image = $instance['si_partner_image'];
		$partner_link = $instance['si_partner_link'];
		$category = $instance['si_category'];
		$background = $instance['si_background'];

		$args = array(
			//'category_name' => $category,
			'post_type' => 'state',
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page' => 60,
            'order' => 'ASC',
            'orderby' => 'title'
		);

		echo $before_widget;				
		
		if ( $title ){ ?>
			<div class="widget-title">
				<span class="title-sep main-color-bg"></span>
				<h4 class="title"><?php echo $title; ?></h4>
			</div>
		<?php }
		
		?>
		<div class="wrap">
			<div class="widget_wrapper">
			<script type='text/javascript'>
				function onstatesubmit() {
					var e = document.getElementById("state_to_go");
					var strState = e.options[e.selectedIndex].value;
					window.location.href = strState;
				}
			</script>
			<?php if ($background) { ?>
				<div class="widget_background">
					<img src="<?php echo $background; ?>" />
				</div>
			<?php } ?>
            <div class="widget_content">
				<div class="widget_content_cell">
					<select name="state_to_go" id="state_to_go">
						<?php $query = new WP_Query( $args ); ?>
						<?php if ( $query -> have_posts() ) : ?>
							<?php while ( $query -> have_posts() ) : $query -> the_post(); ?>
							<?php if (ICL_LANGUAGE_CODE == 'es') { $link = str_ireplace("gedboard.com","gedboard.com/es",get_permalink()); } else { $link = get_permalink(); } ?>
								<option value="<?php echo $link; ?>"> <?php the_title(); ?> </option>
							<?php endwhile; ?>
						<?php endif;?>
						<?php wp_reset_query();?>				
					</select>
					<button class="main-color-bg button" onclick="onstatesubmit()"><?php if (ICL_LANGUAGE_CODE == 'es') { echo 'Info Según Estado'; } else { echo 'Get State Info'; } ?></button>
				</div>
            </div>
			</div>
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
		$instance['si_category'] = $new_instance['si_category'];
		$instance['si_background'] = $new_instance['si_background'];
		$instance['si_partner_image'] = $new_instance['si_partner_image'];
		$instance['si_partner_title'] = $new_instance['si_partner_title'];
		$instance['si_partner_link'] = $new_instance['si_partner_link'];
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
			'si_category' => '',
			'si_background' => '',
			'si_partner_image' => '',
			'si_partner_link' => '',
			'si_partner_title' => '',
 		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wellthemes'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'si_category' ); ?>">Category To List</label>
			<input id="<?php echo $this->get_field_id( 'si_category' ); ?>" name="<?php echo $this->get_field_name( 'si_category' ); ?>" value="<?php echo $instance['si_category']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'si_background' ); ?>">Background URL</label>
			<input id="<?php echo $this->get_field_id( 'si_background' ); ?>" name="<?php echo $this->get_field_name( 'si_background' ); ?>" value="<?php echo $instance['si_background']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'si_partner_title' ); ?>">Partners Title</label>
			<input id="<?php echo $this->get_field_id( 'si_partner_title' ); ?>" name="<?php echo $this->get_field_name( 'si_partner_title' ); ?>" value="<?php echo $instance['si_partner_title']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'si_partner_link' ); ?>">Partners Link</label>
			<input id="<?php echo $this->get_field_id( 'si_partner_link' ); ?>" name="<?php echo $this->get_field_name( 'si_partner_link' ); ?>" value="<?php echo $instance['si_partner_link']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'si_partner_image' ); ?>">Partners Image</label>
			<input id="<?php echo $this->get_field_id( 'si_partner_image' ); ?>" name="<?php echo $this->get_field_name( 'si_partner_image' ); ?>" value="<?php echo $instance['si_partner_image']; ?>" class="widefat" />
		</p>

		
	<?php 
	}
} //end class