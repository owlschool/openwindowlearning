<?php

get_header();

get_template_part('sections/menu_section');

if( have_posts() ){

	the_post();

    // retrieve the background color for this section. A section has gray background (#f5f5f5) by default, therefore
    // we care only if the section has white background
    $section_style = '';
    if( get_post_meta(get_the_ID(), 'newave_page_default_background', true) == 'white' ){

        $section_style = 'style="background-color:#FFF;"';
    }

?>

<!--/Section -->
<section>

    <?php
    $container_type = get_post_meta(get_the_ID(), 'newave_page_default_container', true);
    if( $container_type == 'normal' ){

        $container_type = '';
    }
    else if( $container_type == 'small' ){

        $container_type = 'small-width';
    }
    else if( $container_type == 'full' ){

        $container_type = 'full-width';
    }
    else {

        $container_type = '';
    }

    ?>

	<!-- Container -->
	<div class="container <?php echo $container_type; ?>">

        <?php if( get_post_meta( $post->ID, "newave_show_page_title", true ) == "yes" ){ ?>
            <!-- Section Title -->
            <div class="section-title">
                <h1><?php the_title(); ?></h1>
                <span class="border"></span>
                <p><?php echo get_post_meta(get_the_ID(), 'newave_page_subtitle', true); ?></p>
            </div>
            <!--/Section Title -->
        <?php } ?>
		
    	<?php the_content(); ?>
    	
    </div>
    <!-- Container -->
        
</section>    
<!--/Section -->
    

<?php

}

get_footer();

?>
