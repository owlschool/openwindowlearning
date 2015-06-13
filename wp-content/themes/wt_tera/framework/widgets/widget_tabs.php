<?php
/**
 * Plugin Name: WellThemes: Tabs
 * Plugin URI: http://wellthemes.com/
 * Description: This widhet displays the most recent and popular posts with thumbnails in the tabs.
 * Version: 1.0
 * Author: WellThemes
 * Author URI: http://wellthemes.com/
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'wt_popular_tabs_widget_register' );

function wt_popular_tabs_widget_register() {
	register_widget( 'wt_popular_tabs_widget' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class wt_popular_tabs_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function wt_popular_tabs_widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_tabs', 'description' => __('Displays the recent, popular posts and comments in tabs.', 'wellthemes') );

		/* Create the widget. */
		$this->WP_Widget( 'wt_popular_tabs_widget', __('WellThemes: Tabs', 'wellthemes'), $widget_ops);
	}

	/**
	 *display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		echo $before_widget;
		
		/* if ( $title )
		echo $before_title . $title . $after_title; */
		$entries_display = $instance['entries_display'];
		$latest_category = $instance['latest_category'];
		$popular_category = $instance['popular_category'];
		
		if(empty($entries_display)){ 
			$entries_display = '5'; 
		}	
				
		$args_latest = array(
			'cat' => $latest_category,
			'post_type' => 'post',
			'ignore_sticky_posts' => 1,
			'posts_per_page' => $entries_display		
		);	
		
		?>
		
		<script>
			jQuery(document).ready(function($) {				
				$(".widget-tab-titles li").click(function() {
					$(".widget-tab-titles li").removeClass('active');
					$(this).addClass("active");
					$(".tab-content").hide();
					var selected_tab = $(this).find("a").attr("href");
					$(selected_tab).fadeIn();
					return false;
				});
			});
		</script>

		<div class="widget-tabs-title-container">
			<ul class="widget-tab-titles list">
				<li class="active"><h5><a href="#widget-tab1-content"><?php _e('Recent', 'fairpixels'); ?></a></h5></li>
				<li class=""><h5><a href="#widget-tab2-content"><?php _e('Popular', 'fairpixels'); ?></a></h5></li>
				<li class=""><h5><a href="#widget-tab3-content"><?php _e('Comments', 'fairpixels'); ?></a></h5></li>
			</ul>
		</div> 
		<div class="tabs-content-container">
			
			<div id="widget-tab1-content" class="tab-content" style="display: block;">	
				<?php $latest_posts = new WP_Query( $args_latest ); ?>
				<?php if ( $latest_posts -> have_posts() ) : ?>
					<ul class="list post-list">
						<?php while ( $latest_posts -> have_posts() ) : $latest_posts -> the_post(); ?>					
							<li>
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
							</li>
						<?php endwhile; ?>
					</ul>
				<?php endif;?>
				<?php wp_reset_query();?>	
			</div>
			
			<div id="widget-tab2-content" class="tab-content">
				<?php
					$args_popular = array(
						'cat' => $popular_category,
						'post_type' => 'post',
						'ignore_sticky_posts' => 1,
						'posts_per_page' => $entries_display,
						'orderby' => 'comment_count'						
					);	
				?>
				<?php $latest_posts = new WP_Query( $args_popular ); ?>
				<?php if ( $latest_posts -> have_posts() ) : ?>
					<ul class="list post-list">
						<?php while ( $latest_posts -> have_posts() ) : $latest_posts -> the_post(); ?>					
							<li>
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
							</li>
						<?php endwhile; ?>
					</ul>
				<?php endif;?>
				<?php wp_reset_query();?>	
			</div>
			
			<div id="widget-tab3-content" class="tab-content">
				<ul class="list comment-list">
					<?php 
						//get recent comments
						$args = array(
							   'status' => 'approve',
								'number' => $entries_display
							);	
						
						$postcount=0;
						$comments = get_comments($args);
						
						foreach($comments as $comment) :
								$postcount++;								
								$commentcontent = strip_tags($comment->comment_content);			
								if (strlen($commentcontent)> 50) {
									$commentcontent = mb_substr($commentcontent, 0, 49) . "...";
								}
								$commentauthor = $comment->comment_author;
								if (strlen($commentauthor)> 40) {
									$commentauthor = mb_substr($commentauthor, 0, 39) . "...";			
								}
								$commentid = $comment->comment_ID;
								$commenturl = get_comment_link($commentid); 
								$commentdate = get_comment_date( '', $commentid );
								
								?>
								
							   <li>
									<div class="thumbnail">
										<?php echo get_avatar( $comment, '75' ); ?>
									</div>
									<div class="post-right">
										<div class="comment-author"><h5><?php echo $commentauthor; ?></h5></div>
										<div class="comment-text">
											<a<?php if($postcount==1) { ?> class="first"<?php } ?> href="<?php echo $commenturl; ?>"><?php echo $commentcontent; ?></a>
										</div>
										<div class="entry-meta">
											<span class="date">
												<?php echo $commentdate; ?>
											</span>
									
										</div>
									</div>
								</li>
					<?php endforeach; ?>
					<?php wp_reset_query();?>	
					
				</ul>
			</div>
		</div>

	   <?php
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}
	
	/**
	 * update widget settings
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['entries_display'] = strip_tags($new_instance['entries_display']);
        $instance['latest_category'] = strip_tags($new_instance['latest_category']);
        $instance['popular_category'] = strip_tags($new_instance['popular_category']);
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		$defaults = array('entries_display' => 5, 'latest_category' => '', 'popular_category' => '');
		$instance = wp_parse_args((array) $instance, $defaults);
	?>
		
		<p><label for="<?php echo $this->get_field_id( 'entries_display' ); ?>"><?php _e('How many entries to display?', 'fairpixels'); ?></label>
		<input type="text" id="<?php echo $this->get_field_id('entries_display'); ?>" name="<?php echo $this->get_field_name('entries_display'); ?>" value="<?php echo $instance['entries_display']; ?>" style="width:100%;" /></p>
 
		<p><label for="<?php echo $this->get_field_id( 'latest_category' ); ?>"><?php _e('If you want to display specific category latest posts, enter category ids separated with a comma (e.g. - 1, 3, 8)', 'fairpixels'); ?></label>
		<input type="text" id="<?php echo $this->get_field_id('latest_category'); ?>" name="<?php echo $this->get_field_name('latest_category'); ?>" value="<?php echo $instance['latest_category']; ?>" style="width:100%;" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'popular_category' ); ?>"><?php _e('If you want to display specific category popular posts, enter category ids separated with a comma (e.g. - 1, 3, 8)', 'fairpixels'); ?></label>
		<input type="text" id="<?php echo $this->get_field_id('popular_category'); ?>" name="<?php echo $this->get_field_name('popular_category'); ?>" value="<?php echo $instance['popular_category']; ?>" style="width:100%;" /></p>
	<?php
	}
}
?>