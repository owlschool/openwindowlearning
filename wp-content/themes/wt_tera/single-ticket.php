<?php
/**
 * The Template for displaying all single ticket.
 *
 * @package  WellThemes
 * @file     single-ticket.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<?php get_header(); ?>

	
<div id="content" class="single-post-ticket">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content', 'course' ); ?>
		<?php comments_template( '', true ); ?>		
	<?php endwhile; // end of the loop. ?>		
</div><!-- /content -->	

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
