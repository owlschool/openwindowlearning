<?php
/**
 * Template Name: Blog
 * Description: A Page Template to display blog archives with the sidebar.
 *
 * @package  WellThemes
 * @file     page-blog.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<?php get_header('verticalmenu'); ?>

<div id="content" class="archive blog-page">
		
		<header class="entry-header">	
			<h1><?php the_title(); ?></h1>
		</header><!-- /entry-header -->
		
		<?php
		
			if ( get_query_var('paged') ) {
				$paged = get_query_var('paged');
			} elseif ( get_query_var('page') ) {
				$paged = get_query_var('page');
			} else {
				$paged = 1;
			}
			
			$args = array(
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
				 'paged' => $paged
			);			
		?>
	
		<?php $wp_query = new WP_Query( $args ); ?>
		<?php if ( $wp_query -> have_posts() ) : ?>
			<div class="archive-postlist">
				<?php 
					$i = 0;				
					while ( $wp_query -> have_posts() ) : $wp_query -> the_post();													
						$post_class ="";
						if ( $i % 2 == 1 ){
							$post_class =" col-last";							
						}					
					?>								
					<div class="one-half<?php echo $post_class; ?>">
						<?php get_template_part( 'content', 'excerpt' ); ?>
					</div>
					<?php $i++; ?>
				<?php endwhile; ?>
			</div>
			<?php wt_pagination(); ?>
			<?php wp_reset_query();?>
			<?php else : ?>

					<article id="post-0" class="post no-results not-found">
						<header class="entry-header">
							<h1 class="entry-title"><?php _e( 'Nothing Found', 'wellthemes' ); ?></h1>
						</header><!-- /entry-header -->

						<div class="entry-content">
							<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'wellthemes' ); ?></p>
							<?php get_search_form(); ?>
						</div><!-- /entry-content -->
					</article><!-- /post-0 -->

				<?php endif; ?>
</div><!-- /content -->
<?php get_sidebar(); ?>
<?php get_footer('verticalmenu'); ?>