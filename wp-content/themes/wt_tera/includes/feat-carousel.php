<?php
/**
 * The template for displaying the featured carousel on homepage.
 * Gets the category for the posts from the theme options. 
 * If no category is selected, displays the latest posts.
 *
 * @package  WellThemes
 * @file     feat-carousel.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 * 
 **/
?>
<?php
	$carousel_title = get_post_meta($post->ID, 'wt_meta_carousel_title', true);
	$cat_id = get_post_meta($post->ID, 'wt_meta_carousel_cat', true);	
	
	$args = array(
		'cat' => $cat_id,
		'post_status' => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page' => 51,
                'order' => 'ASC',
                'orderby' => 'title'
	);
		
?>
<div id="feat-carousel" class="carousel-section clearfix">
	<script>
		jQuery(document).ready(function($) {				
			$(".carousel").show();
			$('.carousel').flexslider({
				slideshow: true,							
				slideshowSpeed: 3000,   
				mousewheel: false,
				keyboard: true,
				controlNav: false,
				directionNav: true,	
				controlsContainer: ".feat-carousel-nav",
				animation: "slide",
				itemWidth: 250,
				itemMargin: 30,
				minItems: 1,                   
				maxItems: 6,                   
				move: 1,
			});
		});
	</script>
				
		<div class="cat-title">
			<span class="title-sep main-color-bg"></span>
			<h4><?php echo $carousel_title; ?></h4>
			<div class="carousel-nav feat-carousel-nav"></div>		
		</div>
		
		<div class="carousel">
			<ul class="slides">
				<?php $query = new WP_Query( $args ); ?>
					<?php if ( $query -> have_posts() ) : ?>
						<?php while ( $query -> have_posts() ) : $query -> the_post(); ?>
								<?php if ( has_post_thumbnail()) { ?>				
									<li>
										<div class="thumb overlay">
											<a href="<?php the_permalink(); ?>" >
												<?php the_post_thumbnail( 'wt250_160' ); ?>
											</a>								
										</div>
										<div class="post-info">									
											
											<div class="entry-meta">
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
							
							
											<div class="title">
												<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
											</div>	
																																
										</div>										
									</li>							
							<?php } ?>
						<?php endwhile; ?>
					<?php endif;?>
				<?php wp_reset_query();?>				
			</ul>		
		</div>
</div>