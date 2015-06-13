<?php
/**
 * Template Name: Full Page - Fixed
 * Description: A Page Template to display page content without the sidebar.
 *
 * @package  WellThemes
 * @file     page-full.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<?php get_header(); ?>
        <script type='text/javascript'>
            function onstatesubmit() {
                var e = document.getElementById("state_to_go");
                var strState = e.options[e.selectedIndex].value;
                window.location.href = strState;
            }
        </script>
<div id="content" class="full-content">
    <?php if (have_posts()) : ?>
        <?php while ( have_posts() ) : the_post(); ?>				
            <?php get_template_part( 'content', 'page' ); ?>
            <div class="full_page_sign_up">
                <h3 style="color:white;"><i class="fa fa-envelope-o"></i> Get New Articles Delivered Right To Your Email!</h3>
                <?php echo do_shortcode("[mc4wp_form]"); ?>
            </div>
            <?php
                if (ICL_LANGUAGE_CODE == 'es') { 
                    $query_other_posts = new WP_Query( array ( 'orderby' => 'rand', 'posts_per_page' => '3', 'post_status' => 'publish', 'category_name' => 'featured-spanish' ) );
                } else {
                    $query_other_posts = new WP_Query( array ( 'orderby' => 'rand', 'posts_per_page' => '3', 'post_status' => 'publish', 'category_name' => 'featured' ) );
                }
                if ( $query_other_posts->have_posts() ) { ?>
                    <div class="full_page_related_posts" style="padding: 20px 10px 20px 0px; max-width: 710px;">
                        <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
                            <h3>Posiblemente Le Gustan Tambien...</h3>
                        <?php } else { ?>
                            <h3>You May Also Like...</h3>
                        <?php } ?>
	                <ul style="list-style:none; margin-left: 0px;">
                            <?php while ( $query_other_posts->have_posts() ) {
                                $query_other_posts->the_post(); ?>
                                <li style="display: inline-block; width: 30%; padding-left: 10px; vertical-align: top;">
                                    <a href="<?php echo get_permalink(); ?>">
                                        <?php $thumbnail_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') ); ?>
                                        <img src="<?php echo $thumbnail_url ?>" style="height:150px; margin-left: auto; margin-right: auto;"/>
                                        <br/>
                                        <h4><?php echo get_the_title(); ?></h4>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php }
                wp_reset_postdata(); ?>
            <?php comments_template( '', true ); ?>
        <?php endwhile; // end of the loop. ?>
    <?php endif ?>	
</div><!-- /content -->
<?php get_footer(); ?>