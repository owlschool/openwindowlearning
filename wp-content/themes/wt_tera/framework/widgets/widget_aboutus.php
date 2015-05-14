<?php
/**
 * Plugin Name: WellThemes: About us
 * Plugin URI: http://wellthemes.com/
 * Description: This widhet displays the logo, information and the social links.
 * Version: 1.0
 * Author: WellThemes Team
 * Author URI: http://wellthemes.com/
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'wellthemes_aboutus_widgets' );

function wellthemes_aboutus_widgets() {
	register_widget( 'wellthemes_aboutus_widget' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class wellthemes_aboutus_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function wellthemes_aboutus_widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_aboutus', 'description' => __('Displays the logo, information and the social links.', 'wellthemes') );

		/* Create the widget. */
		$this->WP_Widget( 'wellthemes_aboutus_widget', __('WellThemes: About us', 'wellthemes'), $widget_ops);
	}

	/**
	 * display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		
		extract( $args );
		
		/* Our variables from the widget settings. */
		$title = $instance['title'];
		$img_url = $instance['img_url'];
		$text = $instance['text'];
		$twitter_url = $instance['twitter_url'];
		$facebook_url = $instance['facebook_url'];
		$gplus_url = $instance['gplus_url'];
		$pinterest_url = $instance['pinterest_url'];
		$dribbble_url = $instance['dribbble_url'];
		$linkedin_url = $instance['linkedin_url'];
		$instagram_url = $instance['instagram_url'];
		$youtube_url = $instance['youtube_url'];
		$rss_url = $instance['rss_url'];
		
		echo $before_widget;
  
       ?>
	    
		<?php if ( $title ){ ?>
			<div class="widget-title">
				<h4 class="title"><?php echo $title; ?></h4>
			</div>
		<?php } ?>
		
		<?php if ( $img_url ){ ?>
		   <div class="logo-wrap">
				<img src="<?php echo $img_url; ?>" />
		   </div>
	    <?php } ?>
		
	   <?php if ( $text ){ ?>
		   <div class="info-text">
				<?php echo $text; ?>
		   </div>
	   <?php } ?>
	   
	   <div class="social-links">
		   <ul class="list">
			   <?php if(!empty($twitter_url)){	?>
					<li>						
						<a class="twitter" href="<?php echo $twitter_url; ?>" target="_blank"><i class="fa fa-twitter"></i></a>
					</li>
				<?php
				} 
				
				if(!empty($facebook_url)){	?>
					<li>
						<a class="fb" href="<?php echo $facebook_url; ?>" target="_blank"><i class="fa fa-facebook"></i></a>
					</li>
				<?php }
				
				if(!empty($gplus_url)){	?>
					<li>
						<a class="gplus" href="<?php echo $gplus_url; ?>" target="_blank"><i class="fa fa-google-plus"></i></a>
					</li>
				<?php }
				
				if(!empty($pinterest_url)){	?>
					<li>
						<a class="pinterest" href="<?php echo $pinterest_url; ?>" target="_blank"><i class="fa fa-pinterest"></i></a>
					</li>
				<?php }
				
				if(!empty($dribbble_url)){	?>
					<li>
						<a class="dribbble" href="<?php echo $dribbble_url; ?>" target="_blank"><i class="fa fa-dribbble"></i></a>
					</li>
				<?php }
										
				if(!empty($linkedin_url)){	?>
					<li class="linkedin">
						<a class="linkedin" href="<?php echo $linkedin_url; ?>" target="_blank"><i class="fa fa-linkedin"></i></a>
					</li>
				<?php }
				
				if(!empty($instagram_url)){	?>
					<li>
						<a class="instagram" href="<?php echo $instagram_url; ?>" target="_blank"><i class="fa fa-instagram"></i></a>
					</li>
				<?php }
				
				if(!empty($youtube_url)){	?>
					<li>
						<a class="youtube" href="<?php echo $youtube_url; ?>" target="_blank"><i class="fa fa-youtube-play"></i></a>
					</li>
				<?php }
				
				if(!empty($rss_url)){	?>
					<li>
						<a class="rss" href="<?php echo $rss_url; ?>" target="_blank"><i class="fa fa-rss"></i></a>
					</li>
				<?php }	 ?>
				
			</ul>
		</div>		
	   <?php
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		$img_url = get_template_directory_uri().'/images/logo.png'; 
		/* Set up some default widget settings. */
		$defaults = array(
			'title' => '',
			'img_url' => $img_url,	
			'text' => '',	
			'twitter_url' => '',
			'facebook_url' => '',
			'gplus_url' => '',
			'pinterest_url' => '',
			'dribbble_url' => '',
			'linkedin_url' => '',
			'instagram_url' => '',
			'youtube_url' => '',
			'rss_url' => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wellthemes'); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'img_url' ); ?>"><?php _e('Full logo image path', 'wellthemes'); ?></label>
		<input type="text" id="<?php echo $this->get_field_id('img_url'); ?>" name="<?php echo $this->get_field_name('img_url'); ?>" value="<?php echo $instance['img_url']; ?>" style="width:100%;" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e('Text', 'wellthemes'); ?></label>
		<textarea class="widefat" rows="10" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $instance['text']; ?></textarea>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_url' ); ?>"><?php _e('Twitter URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'twitter_url' ); ?>" name="<?php echo $this->get_field_name( 'twitter_url' ); ?>" value="<?php echo $instance['twitter_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'facebook_url' ); ?>"><?php _e('Facebook URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'facebook_url' ); ?>" name="<?php echo $this->get_field_name( 'facebook_url' ); ?>" value="<?php echo $instance['facebook_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'gplus_url' ); ?>"><?php _e('Google Plus URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'gplus_url' ); ?>" name="<?php echo $this->get_field_name( 'gplus_url' ); ?>" value="<?php echo $instance['gplus_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'pinterest_url' ); ?>"><?php _e('Pinterest URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'pinterest_url' ); ?>" name="<?php echo $this->get_field_name( 'pinterest_url' ); ?>" value="<?php echo $instance['pinterest_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'dribbble_url' ); ?>"><?php _e('Dribbble URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'dribbble_url' ); ?>" name="<?php echo $this->get_field_name( 'dribbble_url' ); ?>" value="<?php echo $instance['dribbble_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'linkedin_url' ); ?>"><?php _e('Linkedin URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'linkedin_url' ); ?>" name="<?php echo $this->get_field_name( 'linkedin_url' ); ?>" value="<?php echo $instance['linkedin_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'instagram_url' ); ?>"><?php _e('Instagram URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'instagram_url' ); ?>" name="<?php echo $this->get_field_name( 'instagram_url' ); ?>" value="<?php echo $instance['instagram_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'youtube_url' ); ?>"><?php _e('Youtube URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'youtube_url' ); ?>" name="<?php echo $this->get_field_name( 'youtube_url' ); ?>" value="<?php echo $instance['youtube_url']; ?>" />
		</p>		
				
		<p>
			<label for="<?php echo $this->get_field_id( 'rss_url' ); ?>"><?php _e('RSS URL:', 'wellthemes') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'rss_url' ); ?>" name="<?php echo $this->get_field_name( 'rss_url' ); ?>" value="<?php echo $instance['rss_url']; ?>" />
		</p>
		
		
	<?php
	}
}
?>