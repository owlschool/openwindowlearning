<?php

global $global_theme_options;

?>

<!-- Map Section -->
<section id="<?php echo $post->post_name; ?>" class="dark-section-bg">

    <?php if( $global_theme_options['contact_show_map'] ){ ?>
    <!-- Map-->
    <div id="map_canvas"></div>
    <!--End Map-->
    <?php } ?>

    <div class="map-overlay">


        <div class="overlay-container">

            <div class="overlay-container-vertical">

                <?php the_content(); ?>

            </div>

        </div>

    </div>

    <div class="toggle-map">
        <div class="hide-overlay"></div>
    </div>


</section>
<!--/Map Section -->
