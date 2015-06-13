<?php
/**
 * Template Name: Archives
 * Description: A Page Template to display archives with the sidebar.
 *
 * @package  WellThemes
 * @file     page-archives.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<?php get_header(); ?>

<div id="content" class="archive-page">
	
	<header class="entry-header">
		<h1><?php the_title(); ?></h1>
	</header><!-- /entry-header -->
		
	<?php if (have_posts()) :?>
			<?php while ( have_posts() ) : the_post(); ?>				
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content">
						<?php the_content(); ?>						
					</div><!-- /entry-content -->
				</article><!-- /post-<?php the_ID(); ?> -->
			<?php endwhile; ?>
		<?php endif; ?>
		
		<div class="archive-columns">
		
			<div class="row">
			
				<div class="one-half">
					<div class="col-header">
						<h3><?php _e('Last 10 Posts:', 'wellthemes')?></h3>
					</div>
					
					<ol class="archive-list">
						<?php
							$recent_posts = wp_get_recent_posts();
							foreach( $recent_posts as $recent ){
								echo '<li><a href="' . get_permalink($recent["ID"]) . '">' .   $recent["post_title"].'</a> </li>';
							}
						?>
					</ol>					
				</div>
				
				<div class="one-half col-last">
					<div class="col-header">
						<h3><?php _e('Most Popular Tags:', 'wellthemes')?></h3>
					</div>
					<?php wp_tag_cloud('number=10&format=list&topic_count_text_callback=default_topic_count_text&orderby=count&order=DESC'); ?>
				</div>
			</div> <!-- /row -->
			
			<div class="row">
										
				<div class="one-half">
					<div class="col-header">
						<h3><?php _e('Archives by Category:', 'wellthemes')?></h3>
					</div>
				
					<ul class="archive-list">
						<?php wp_list_categories('title_li=') ?>
					</ul>
				</div>	

				<div class="one-half col-last">
					<div class="col-header">
					<h3><?php _e('Archives By Month:', 'wellthemes')?></h3>
				</div>
					<ul class="sp-list unordered-list">
						<?php wp_get_archives('type=monthly&show_post_count=1'); ?>
					</ul>
				</div>
			
			</div> <!-- /row -->
			
			<div class="row">
				<div class="one-half">
					<div class="col-header">
						<h3><?php _e('Pages:', 'wellthemes')?></h3>
					</div>
				
					<ul class="pages">
						<?php wp_list_pages("title_li=" ); ?>
					</ul>
				</div>
				
				<div class="one-half col-last">
					<div class="col-header">
						<h3><?php _e('Search Archives:', 'wellthemes')?></h3>
					</div>
					<div class="archive-search">
						<?php get_search_form(); ?>
					</div>
				</div>
			
			</div> <!-- /row -->
		</div><!-- /archive-columns -->	
	
</div><!-- /content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>