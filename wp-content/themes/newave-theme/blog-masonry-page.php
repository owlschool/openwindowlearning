<?php
/*
Template name: Blog Masonry Template
*/

get_header();

global $global_theme_options;

$global_theme_options['blog_layout'] = "Masonry";

get_template_part('sections/menu_section');

?>

    <!-- Blog Header -->
    <div id="blog-header">

        <?php echo '<h1>' . get_the_title() . '</h1>' ?>

    </div>
    <!--/Blog Header -->





    <!-- Blog Content -->
    <div id="blog" class="masonry clearfix">

    <!-- Container -->
    <div class="container">


        <!-- Blog Posts Content -->
        <div class="blog-posts-content">

            <?php

            global $paged;
            query_posts('numberposts=-1&post_type=post&paged='.$paged);

            // the loop
            while(have_posts()){

                the_post();

                get_template_part( 'blog-post-format/blog-post', get_post_format() );

            }
            ?>

        </div>
        <!--/Blog Posts Content -->

    </div>
    <!--/Container -->

    </div>
    <!--/Blog Content -->


<?php

newave_pagination();

wp_reset_query();

get_footer();

?>