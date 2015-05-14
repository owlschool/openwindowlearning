<?php

global $global_theme_options;

$number_of_posts = 6;
if( !empty( $global_theme_options['blog_section_posts'] ) ){

	$number_of_posts = $global_theme_options['blog_section_posts'];
}
if( $number_of_posts <= 0 ) {

	$number_of_posts = 6;
}

?>

	<!--/Section -->
	<section id="<?php echo $post->post_name; ?>" class="content" style="background-color:#fff">

	<!-- Container -->
	<div class="container">
	
	<?php if( get_post_meta( $post->ID, "newave_show_page_title", true ) == "yes" ){ ?>
    	<!-- Section Title -->
    	<div class="section-title">
        	<h1><?php the_title(); ?></h1>
            <span class="border"></span>
            <p><?php echo get_post_meta(get_the_ID(), 'newave_page_subtitle', true); ?></p>
    	</div>				
        <!--/Section Title -->
    <?php } ?>
		
    <!-- Blog Content -->
    <div id="blog" class="masonry clearfix">

    <!-- Container -->
    <div class="container" style="margin-top:0; top:0">


        <!-- Blog Posts Content -->
        <div class="blog-posts-content">

            <?php

            global $paged;
            query_posts('numberposts=-1&post_type=post&paged='.$paged);

            // the loop
			$index = 1;
            while(have_posts()){

                the_post();

                get_template_part( 'blog-post-format/blog-post', get_post_format() );
				
				if( $index >= $number_of_posts ){
					
					break;
				}
				
				$index++;

            }
            ?>

        </div>
        <!--/Blog Posts Content -->

    </div>
    <!--/Container -->

    </div>
    <!--/Blog Content -->
	
	<!-- Container -->
	</div>

	<!--/Section -->
	</section>

<?php

wp_reset_query();

?>