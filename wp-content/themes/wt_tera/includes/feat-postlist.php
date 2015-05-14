<?php
/**
 * The template for displaying the featured slider on homepage.
 * Gets the category for the posts from the theme options. 
 * If no category is selected, displays the latest posts.
 *
 * @package  WellThemes
 * @file     feat-postlist.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 * 
 **/
?>
<div id="feat-postlist" class="section">
	<?php
		$cat_title = get_post_meta($post->ID, 'wt_meta_postlist_title', true);
		$cat_color = get_post_meta($post->ID, 'wt_meta_postlist_color', true);
		$cat_id = get_post_meta($post->ID, 'wt_meta_postlist_cat', true);
	
		if ($cat_title){ ?>
			<div class="cat-title">
				<span class="title-sep" style="background: <?php echo $cat_color; ?>"></span>
				<h4><?php echo $cat_title; ?></h4>				
			</div>
	<?php }	?>
	
		<div class="archive-postlist">
		<?php							
			
			if ( get_query_var('paged') ) {
				$paged = get_query_var('paged');
			} elseif ( get_query_var('page') ) {
				$paged = get_query_var('page');
			} else {
				$paged = 1;
			}
				
			$args = array(
				'cat' => $cat_id,
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
				 'paged' => $paged
			);			
			
			$i = 0;
			$wp_query = new WP_Query( $args );
			if ( $wp_query -> have_posts() ) :
				while ( $wp_query -> have_posts() ) : $wp_query -> the_post();
						
					$post_class ="";
					if ( $i % 2 == 1 ){
						$post_class =" col-last";
					}					
									
				?>								
				<div class="one-half<?php echo $post_class; ?>">
					<?php get_template_part( 'content', 'excerpt' ); ?>
				</div>
				<?php $i++;
				endwhile;
			endif;
		?>
		</div>
	<?php wt_pagination(); ?>
	<?php wp_reset_query();?>
</div><!-- /feat-postlist -->