<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package  WellThemes
 * @file     category.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<?php get_header(); ?>

<div id="content" class="post-archive">
	<?php if ( have_posts() ) : ?>
	
		<header class="archive-header">
		
			<h2><?php	printf( __( 'Category Archives: %s', 'wellthemes' ), '<span>' . single_cat_title( '', false ) . '</span>' );?></h2>

			<?php
				$category_description = category_description();
				if ( ! empty( $category_description )) {
					echo apply_filters( 'category_archive_meta', '<div class="archive-desc">' . $category_description . '</div>' );
				}
			?>
		</header>
		
		<div class="archive-postlist">
			<?php $i = 0; ?>				
			<?php while ( have_posts() ) : the_post(); ?>
				<?php								
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
<?php get_footer(); ?>
