<?php
/**
 * The Template for displaying all single posts.
 *
 * @package  WellThemes
 * @file     single.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<?php get_header(); ?>	
<div id="content" class="single-post-course">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content', 'course' ); ?>
		<?php //comments_template( '', true ); ?>		
	<?php endwhile; // end of the loop. ?>		
</div><!-- /content -->	
<?php get_footer(); ?>
