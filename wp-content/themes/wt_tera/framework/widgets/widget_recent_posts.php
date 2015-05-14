<?php
/**
 * Plugin Name: WellThemes: Recent Posts
 * Plugin URI: http://wellthemes.com/
 * Description: This widhet displays the most recent posts in the sidebar.
 * Version: 1.0
 * Author: WellThemes Team
 * Author URI: http://wellthemes.com/
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'wellthemes_recent_posts_widgets' );

function wellthemes_recent_posts_widgets() {
	register_widget( 'wellthemes_recent_posts_widget' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class wellthemes_recent_posts_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function wellthemes_recent_posts_widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_posts', 'description' => __('Displays the most recent posts in the sidebar.', 'wellthemes') );

		/* Create the widget. */
		$this->WP_Widget( 'wellthemes_recent_posts_widget', __('WellThemes: Recent Posts', 'wellthemes'), $widget_ops);
	}

	/**
	 * display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		
		extract( $args );
	    $title = apply_filters('widget_title', $instance['title'] );
		$display_category = $instance['display_category'];
		$entries_display = $instance['entries_display'];
		
		if(empty($entries_display)){ 
			$entries_display = '5'; 
		}
		
		echo $before_widget;
		
		if ( $title ){ ?>
			<div class="widget-title">
				<h4 class="title"><?php echo $title; ?></h4>
			</div>
		<?php }

        $args = array(
			'cat' => $display_category,
			'post_type' => 'post',
			'ignore_sticky_posts' => 1,
			'posts_per_page' => $entries_display,
		);
		
		$query = new WP_Query( $args );
				if ( $query -> have_posts() ) :
					$last_post  = $query -> post_count -1;
					while ( $query -> have_posts() ) : $query -> the_post();
						if ( $query->current_post == 0 ) { ?>	
							<div class="main-post">								
								<?php get_template_part( 'content', 'excerpt' ); ?>									
							</div><!-- /main-post -->
					<?php } 
						if ( $query->current_post == 1 ) {	?>
						<div class="post-list">
					<?php }
						if ( $query->current_post >= 1 ) { ?>	
							<div class="item-post">
								<?php if ( has_post_thumbnail() ) {	?>
									<div class="thumb overlay">
										<a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'wt75_75' ); ?></a>
									</div>
								<?php }	?>
								<div class="post-right">
									
									<div class="entry-meta">
										<?php wt_get_first_cat(); ?>
										<?php
											global $post;
											$comment_count = get_comments_number($post->ID);
											if ($comment_count > 0){ ?>	
												<span class="comments">
													<i class="fa fa-comment"></i>
													<?php comments_popup_link( __('', 'wellthemes'), __( '1', 'wellthemes'), __('%', 'wellthemes')); ?>	
												</span>
												<span class="sep">-</span>
												<?php
											}			
										?>
										<span class="date"><?php echo get_the_date(); ?></span>			
									</div>
									<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
																
								</div>
							</div>		
						<?php } 
						if (( $query->post_count  > 1) AND ($query->current_post == $last_post )) { ?>					
						</div><!-- /post-list -->
					<?php }	
					endwhile;
				endif;
			wp_reset_query(); 
			
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		$defaults = array('title' => 'Recent Posts', 'entries_display' => 5, 'display_category' => '');
		$instance = wp_parse_args((array) $instance, $defaults);
	?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wellthemes'); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'entries_display' ); ?>"><?php _e('How many entries to display?', 'wellthemes'); ?></label>
		<input type="text" id="<?php echo $this->get_field_id('entries_display'); ?>" name="<?php echo $this->get_field_name('entries_display'); ?>" value="<?php echo $instance['entries_display']; ?>" style="width:100%;" /></p>
 
		<p><label for="<?php echo $this->get_field_id( 'display_category' ); ?>"><?php _e('Display specific categories? Enter category ids separated with a comma (e.g. - 1, 3, 8)', 'wellthemes'); ?></label>
		<input type="text" id="<?php echo $this->get_field_id('display_category'); ?>" name="<?php echo $this->get_field_name('display_category'); ?>" value="<?php echo $instance['display_category']; ?>" style="width:100%;" /></p>
	<?php
	}
}
?>