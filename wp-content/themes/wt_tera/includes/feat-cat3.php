<?php
/**
 * The template for displaying the featured single categories section on homepage.
 * Gets the category for the posts from the theme options. 
 * If no category is selected, displays the latest posts.
 *
 * @package  WellThemes
 * @file     feat-cat3.php
 * @author   WellThemes Team
 * @link 	 http://wellthemes.com
 * 
 **/
?>
<div class="feat-cat section">
	<?php
		$cat_title = get_post_meta($post->ID, 'wt_meta_feat_cat3_title', true);
		$cat_color = get_post_meta($post->ID, 'wt_meta_feat_cat3_title_color', true);
		$cat_id = get_post_meta($post->ID, 'wt_meta_feat_cat3', true);
		
		if ($cat_title){ ?>
			<div class="cat-title">
				<span class="title-sep" style="background: <?php echo $cat_color; ?>"></span>
				<h4><?php echo $cat_title; ?></h4>				
			</div>
	<?php }
	
		$args = array(
			'cat' => $cat_id,
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page' => 5
		);
		$query = new WP_Query( $args );
		if ( $query -> have_posts() ) :
			$last_post  = $query -> post_count -1;
			while ( $query -> have_posts() ) : $query -> the_post();
				if ( $query->current_post == 0 ) { ?>	
					<div class="main-post one-half">								
						<?php get_template_part( 'content', 'excerpt' ); ?>									
					</div><!-- /main-post -->
			<?php } 
				if ( $query->current_post == 1 ) {	?>
				<div class="post-list one-half col-last">
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
	wp_reset_query(); ?>
	
</div><!-- /feat-cat -->