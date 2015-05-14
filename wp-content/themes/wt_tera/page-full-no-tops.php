<?php
/**
 * Template Name: Full Page Without Annotations
 * Description: A Page Template to display page content without the sidebar.
 *
 * @package  WellThemes
 * @file     page-full-no-tops.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<?php get_header(); ?>
<div id="content" class="full-content">
    <?php if (have_posts()) : ?>
        <?php while ( have_posts() ) : the_post(); ?>				
            <?php get_template_part( 'content', 'page' ); ?>
        <?php endwhile; // end of the loop. ?>
    <?php endif ?>	
</div><!-- /content -->
<?php get_footer(); ?>