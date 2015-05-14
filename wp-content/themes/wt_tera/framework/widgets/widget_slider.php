<?php
/**
 * Plugin Name: WellThemes: Slider Widget
 * Plugin URI: http://wellthemes.com
 * Description: This widget allows to display latest posts carousel with thumbnails and post title in the sidebar of well themes.
 * Version: 1.0
 * Author: WellThemes Team
 * Author URI: http://wellthemes.com
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action('widgets_init', 'wellthemes_slider_widgets');

function wellthemes_slider_widgets(){
	register_widget('wellthemes_slider_widget');
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */ 
class wellthemes_slider_widget extends WP_Widget {
	
	/**
	 * Widget setup.
	 */
	function wellthemes_slider_widget(){
		/* Widget settings. */	
		$widget_ops = array('classname' => 'widget_slider', 'description' => 'Displays the slider in the sidebar.');
		
		/* Create the widget. */
		$this->WP_Widget('wellthemes_slider_widget', 'WellThemes: Slider', $widget_ops);
	}
	
	/**
	 * display the widget on the screen.
	 */
	function widget($args, $instance){	
		extract($args);
		echo $before_widget;
		$title = $instance['title'];
		$categories = $instance['categories'];
		$posts = $instance['posts'];
					
		if($categories != 'all') {
			$categories_array = array($categories);
		}
		
			$recent_posts = new WP_Query(array( 'showposts' => $posts, 'post_type' => 'post', 'cat' => $categories, 'ignore_sticky_posts' => 1));
			
			if ( $title ){ ?>
				<div class="widget-title">
					<h4 class="title"><?php echo $title; ?></h4>
				</div>
			<?php } ?>
			
			<script>
				jQuery(document).ready(function($) {				
					$(".sidebar-slider-posts").show();
					$('.sidebar-slider-posts').flexslider({			// slider settings
						animation: "slide",							// animation style
						controlNav: false,							// slider thumnails class
						slideshow: true,							// enable automatic sliding
						directionNav: true,							// disable nav arrows
						slideshowSpeed: 6000,   					// slider speed
						smoothHeight: false,
						keyboard: false,
						mousewheel: false,
					});	
				});
			</script>
			
			<div class="sidebar-slider-posts" >
				<ul class="slides">
					<?php while($recent_posts->have_posts()): $recent_posts->the_post(); 
						global $post
					?>						
						<?php if(has_post_thumbnail()): ?>								
							<li>									
								<?php if ( has_post_thumbnail() ) {	?>
									<div class="thumb">
										<a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'wt340_230' ); ?></a>
										<?php 
											if ( wt_get_option( 'wt_enable_rating' ) == 1 ){
												ec_stars_rating_archive(); 
											}
										?>						
									</div>
								<?php } ?>
								
								<div class="excerpt-wrap">
								
									<div class="entry-meta">
										<?php wt_get_first_cat(); ?>
										<?php
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
								
							</li>														
						<?php endif; ?>							
					<?php endwhile; ?>
					<?php wp_reset_query();?>
				</ul>
			</div>
							
		<?php
		echo $after_widget;
	}
	
	/**
	 * update widget settings
	 */
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		
		$instance['title'] = $new_instance['title'];
		$instance['categories'] = $new_instance['categories'];
		$instance['posts'] = $new_instance['posts'];
		return $instance;
	}
	
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */	
	function form($instance){
		$defaults = array('title' => 'Featured Posts', 'categories' => 'all', 'posts' => 5);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wellthemes'); ?></label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Filter by Category:', 'wellthemes'); ?></label> 
			<select id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>" class="widefat categories" style="width:100%;">
				<option value='all' <?php if ('all' == $instance['categories']) echo 'selected="selected"'; ?>>all categories</option>
				<?php $categories = get_categories('hide_empty=0&depth=1&type=post'); ?>
				<?php foreach($categories as $category) { ?>
				<option value='<?php echo $category->term_id; ?>' <?php if ($category->term_id == $instance['categories']) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option>
				<?php } ?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('posts'); ?>"><?php _e('Number of posts:', 'wellthemes'); ?></label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo $instance['posts']; ?>" />
		</p>
	<?php }
}
?>