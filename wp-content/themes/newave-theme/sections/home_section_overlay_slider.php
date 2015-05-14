<?php

global $global_theme_options;

wp_localize_script( 'scriptsjs',
                    'FullScreenSliderOptions',
                    array(  "slider_speed"       => $global_theme_options['home_overlay_slider_speed'],
                            "slider_transition"  => $global_theme_options['home_overlay_slider_transition'] ) );

$class_horz = '';
if( $global_theme_options['home_overlay_slider_transition'] == 'scrollHorz' ){

    $class_horz = 'class="horz"';
}

?>

<div id="<?php echo $post->post_name; ?>" class="home-section">

    <div class="slider-overlay-content">

        <div class="pattern">
            <div class="slide-overlay-content light">
                <div class="div-align-center">

                    <?php
                    if( !empty( $global_theme_options['home_overlay_slider_content'] ) ){
                        the_content();
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>

    <a id="slider_left"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow_left.png" alt="Slide Left" /></a>
    <a id="slider_right"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow_right.png" alt="Slide Right" /></a>
    <img id="cycle-loader" src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" />
    <div id="fullscreen-slider" <?php echo $class_horz; ?>>

<?php

        $fullscreen_slider = $global_theme_options['home_overlay_slider_slider'];
        if ( !empty( $fullscreen_slider )) {

            foreach( $fullscreen_slider  as $slide ){

                if( $slide['url'] != "" ){

               ?>

                    <div>
                        <img src="<?php echo $slide['url']; ?>" />
                    </div>

                <?php
                }

            } //end foreach
        }

?>

    </div>

</div>