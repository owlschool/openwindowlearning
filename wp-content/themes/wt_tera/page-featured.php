<?php
/**
 * Template Name: Featured Page
 * Description: A Page Template to display featured page content
 *
 * @package  WellThemes
 * @file     page-featured.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<?php get_header('verticalmenu'); ?>

<div id="content" class="featured-page">
	<?php
		
		if ($paged < 2 ){
			
			//Featured category 1
			$feat_cat1 = get_post_meta($post->ID, 'wt_meta_feat_cat1_title', true);	
			if ($feat_cat1) {
				get_template_part( 'includes/feat-cat1' );				
			}
			
			//Featured category 2
			$feat_cat2 = get_post_meta($post->ID, 'wt_meta_feat_cat2_title', true);	
			if ($feat_cat2) {
				get_template_part( 'includes/feat-cat2' );				
			}
			
			//Featured category 3
			$feat_cat3 = get_post_meta($post->ID, 'wt_meta_feat_cat3_title', true);	
			if ($feat_cat3) {
				get_template_part( 'includes/feat-cat3' );				
			}
			
			//Featured category 4
			$feat_cat4 = get_post_meta($post->ID, 'wt_meta_feat_cat4_title', true);	
			if ($feat_cat4) {
				get_template_part( 'includes/feat-cat4' );				
			}
			
			//Featured category 5
			$feat_cat5 = get_post_meta($post->ID, 'wt_meta_feat_cat5_title', true);	
			if ($feat_cat5) {
				get_template_part( 'includes/feat-cat5' );				
			}
			
			//Content Banner 1
			$post_banner1 = get_post_meta($post->ID, 'wt_meta_banner1', true);						
			if (!empty($post_banner1)) { ?>
					
				<div class="entry-ad section">
					<div class="ad-inner-wrap">
						<?php echo $post_banner1; ?>
					</div>			
				</div>
					
			<?php }
			
		} //if page < 2
			
		//Featured category 5
		$post_list = get_post_meta($post->ID, 'wt_meta_postlist_title', true);	
		if ($post_list) {
			get_template_part( 'includes/feat-postlist' );				
		}

		if ($paged < 2 ){			
			//Content Banner 2
			$post_banner2 = get_post_meta($post->ID, 'wt_meta_banner2', true);			
			if (!empty($post_banner2)) { ?>					
				<div class="entry-ad section">
					<div class="ad-inner-wrap">
						<?php echo $post_banner2; ?>
					</div>			
				</div>					
			<?php }						
		}
	?>
</div>
<?php get_sidebar(); ?>
<?php get_footer('verticalmenu'); ?>