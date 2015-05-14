<?php

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package  WellThemes
 * @file     page.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
 ?>
<?php get_header(); ?>
<div id="content" class="single-page">
	
	<?php if (have_posts()) : ?>
		<?php while ( have_posts() ) : the_post(); ?>				
			<?php get_template_part( 'content', 'page' ); ?>
			<?php //comments_template( '', true ); ?>
		<?php endwhile; // end of the loop. ?>
	<?php endif ?>	

</div><!-- /content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>