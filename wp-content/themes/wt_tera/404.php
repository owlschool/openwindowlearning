<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package  WellThemes
 * @file     404.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */

?>
<?php get_header(); ?>

<div id="content" class="error-page full-content">
	
	<div class="error-number">	
		<h1>404<h1>
		<h2><?php _e('Page not found', 'fairpixels');?></h2>
		<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', 'wellthemes' ); ?></p>
	</div>
	
	<div class="error-info">
		
		<div class="post-list">
			<?php the_widget('WP_Widget_Recent_Posts', array('number' => 3, 'title' => ' '), array('before_title' => '', 'after_title' => '')); ?>
		</div>
		
		<div class="search">
			<?php get_search_form(); ?>
		</div>
		
	</div>
	
</div><!-- /content -->
<?php get_footer(); ?>