<?php
/**
 * The template for displaying the featured slider on homepage.
 * Gets the category for the posts from the theme options. 
 * If no category is selected, displays the latest posts.
 *
 * @package  WordPress
 * @file     feat-slider.php
 * @author   WellThemes
 * @link 	 http://wellthemes.com
 * 
 **/
?>
<?php		
	$slider_cat_id = get_post_meta($post->ID, 'wt_meta_slider_cat', true);
	$slider_speed = get_post_meta($post->ID, 'wt_meta_slider_speed', true);	
	$slider_right_cat1_id = get_post_meta($post->ID, 'wt_meta_slider_right_cat1', true);
	$slider_right_cat2_id = get_post_meta($post->ID, 'wt_meta_slider_right_cat2', true);
	
	if (empty($slider_speed)){
		$slider_speed = 5000;
	}
	
	$args = array(
		'cat' => $slider_cat_id,
		'post_status' => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page' => 5,
                'orderby' => 'date',
                'order' => 'DESC'
	);
		
?>

<div class="slider-section">
	<script>
			jQuery(document).ready(function($) {
				$('.slider-main').show();
				$('.slider-main').flexslider({						//slider settings
					animation: "slide",								//animation style
					controlNav: false,								//slider thumnails class
					slideshow: true,								//enable automatic sliding
					directionNav: true,								//disable nav arrows
					slideshowSpeed: <?php echo $slider_speed; ?>,   //slider speed
					smoothHeight: false,
					keyboard: true,
					mousewheel: false,
					startAt: 1,
					controlsContainer: ".slider-main-nav",
				});
			});
		</script>
	<div class="slider-main">				
		<ul class="slides">
			<?php $query = new WP_Query( $args ); ?>
				<?php if ( $query -> have_posts() ) : ?>
					<?php while ( $query -> have_posts() ) : $query -> the_post(); ?>
							<?php if ( has_post_thumbnail()) { ?>				
								<li>
									<a href="<?php the_permalink(); ?>" >
										<?php the_post_thumbnail( 'wt720_415' ); ?>
									</a>
<?php if (get_permalink() == 'http://www.gedboard.com/tutoring/') { ?>
									<div class="post-info">
										<div class="entry-meta">
										</div>
									
										<div class="title">
											<h2><a href="<?php the_permalink() ?>">GED Tutoring: Sign Up Now For $30/mo</a></h2>
										</div>
										
									</div>	
<?php } else { ?>
									<div class="post-info">
										<div class="entry-meta">
											<?php 
												if ( wt_get_option( 'wt_enable_rating' ) == 1 ){
													ec_stars_rating_archive(); 
												}
											?>
											<span class="date"><?php echo get_the_date(); ?></span>
											<span class="sep">-</span>
											<span class="by"><?php _e('Posted by', 'wellthemes'); ?></span>
											<span class="author"><?php the_author(); ?></span>
										</div>
									
										<div class="title">
											<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
										</div>
										
									</div>	
<?php } ?>										
								</li>					
						<?php } ?>
					<?php endwhile; ?>
				<?php endif;?>
			<?php wp_reset_query();?>	
		</ul>
		<div class="slider-main-nav"></div>
	</div>
	
	<div class="slider-right">
		<?php 
			$args = array(
				'cat' => $slider_right_cat1_id,
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page' => 1,
                'orderby' => 'date',
                'order' => 'DESC'
			);
		?>		
		<?php $query = new WP_Query( $args ); ?>
				<?php if ( $query -> have_posts() ) : ?>
					<?php while ( $query -> have_posts() ) : $query -> the_post(); ?>
							<?php if ( has_post_thumbnail()) { ?>				
								<div class="item-post overlay">
									<a href="<?php the_permalink(); ?>" >
										<?php the_post_thumbnail( 'wt375_205' ); ?>
									</a>
										
									<div class="post-info">
										<div class="entry-meta">
                                                                                  <?php if (ICL_LANGUAGE_CODE == 'es') { ?>

											<?php 
												if ( wt_get_option( 'wt_enable_rating' ) == 1 ){
													ec_stars_rating_archive(); 
												}
											?>
											<span class="date"><?php echo get_the_date(); ?></span>
											<span class="sep">-</span>
											<span class="by"><?php _e('Posted by', 'wellthemes'); ?></span>
											<span class="author"><?php the_author(); ?></span>
									<?php } else { ?>

											<span class="date">GED MATHEMATICS</span>
									<?php } ?>
										</div>
									
										<div class="title">
											<h4><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
										</div>
										
									</div>											
								</div>					
						<?php } ?>
					<?php endwhile; ?>
				<?php endif;?>
			<?php wp_reset_query();?>
			
			<?php 
			$args = array(
				'cat' => $slider_right_cat2_id,
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page' => 1,
                'orderby' => 'date',
                'order' => 'DESC'
			);
		?>		
		<?php $query = new WP_Query( $args ); ?>
				<?php if ( $query -> have_posts() ) : ?>
					<?php while ( $query -> have_posts() ) : $query -> the_post(); ?>
							<?php if ( has_post_thumbnail()) { ?>				
								<div class="item-post overlay">
									<a href="<?php the_permalink(); ?>" >
										<?php the_post_thumbnail( 'wt375_205' ); ?>
									</a>
										
									<div class="post-info">
										<div class="entry-meta">
<?php if (ICL_LANGUAGE_CODE == 'es') { ?>

											<?php 
												if ( wt_get_option( 'wt_enable_rating' ) == 1 ){
													ec_stars_rating_archive(); 
												}
											?>
											<span class="date"><?php echo get_the_date(); ?></span>
											<span class="sep">-</span>
											<span class="by"><?php _e('Posted by', 'wellthemes'); ?></span>
											<span class="author"><?php the_author(); ?></span>
									<?php } else { ?>

											<span class="date">GED LANGUAGE ARTS</span>
									<?php } ?>										</div>
									
										<div class="title">
											<h4><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
										</div>
										
									</div>											
								</div>					
						<?php } ?>
					<?php endwhile; ?>
				<?php endif;?>
			<?php wp_reset_query();?>
	</div>
</div>