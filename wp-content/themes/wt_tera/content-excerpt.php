<?php
/**
 * The template for displaying content in the archive and search results template
 *
 * @package  WellThemes
 * @file     content-excerpt.php
 * @author   WellThemes Team
 * @link 	 http://wellthemes.com
 */
?>

<article <?php post_class(); ?>>
	
	<?php if ( has_post_thumbnail() ) {	?>
		<div class="thumb excerpt-thumb overlay">
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
		<?php the_excerpt(); ?>
				
	</div>
	
</article><!-- /post-<?php the_ID(); ?> -->