<?php

global $global_theme_options;

// retrieve the background color for this section. A section has gray background (#f5f5f5) by default, therefore
// we care only if the section has white background
$section_style = '';
if( get_post_meta(get_the_ID(), 'newave_contact_default_background', true) == 'white' ){

    $section_style = 'style="background-color:#FFF;"';
}

if( get_post_meta(get_the_ID(), 'newave_contact_map_overlay', true) == 'yes' ){

    get_template_part('sections/contact_section_overlay');
}
else{

?>

<!--/Section -->
<section id="<?php echo $post->post_name; ?>" <?php echo $section_style; ?>>

	<!-- Container -->
	<div class="container small-width">

        <?php if( get_post_meta( $post->ID, "newave_show_page_title", true ) == "yes" ){ ?>
    	<!-- Section Title -->
    	<div class="section-title">
        	<h1><?php the_title(); ?></h1>
            <span class="border"></span>
            <p><?php echo get_post_meta(get_the_ID(), 'newave_page_subtitle', true); ?></p>
    	</div>				
        <!--/Section Title -->
        <?php } ?>

        <!-- Contact Formular -->
    	<div id="contact_formular">
        
    		<?php the_content(); ?> 
        
    	</div>
    	<!--/Contact Formular -->
    	
    </div>
    <!-- Container -->

    <?php if( $global_theme_options['contact_show_map'] ){ ?>
    <!-- Map-->
    <div id="map_canvas"></div>
    <!--End Map-->
    <?php } ?>
        
</section>    
<!--/Section -->

<?php
}
?>