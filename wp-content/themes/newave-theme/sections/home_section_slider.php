<?php

global $global_theme_options;

wp_localize_script( 'scriptsjs',
                    'FullScreenSliderOptions',
                    array(  "slider_speed"       => $global_theme_options['home_full_screen_slider_speed'],
                            "slider_transition"  => $global_theme_options['home_full_screen_slider_transition'] ) );

$class_horz = '';
if( $global_theme_options['home_full_screen_slider_transition'] == 'scrollHorz' ){

    $class_horz = 'class="horz"';
}

?>

<div id="<?php echo $post->post_name; ?>" class="home-section">

    <a id="slider_left"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow_left.png" alt="Slide Left" /></a>
    <a id="slider_right"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow_right.png" alt="Slide Right" /></a>
    <img id="cycle-loader" src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" />
    <div id="fullscreen-slider" <?php echo $class_horz; ?>>

<?php

        $fullscreen_slider = $global_theme_options['home_full_screen_slider_slider'];
        if ( !empty( $fullscreen_slider )) {

            foreach( $fullscreen_slider  as $slide ){

                if( $slide['url'] != "" ){

                    $class_color    = "";
                    $class_button   = "";
                    if( $slide['text_color'] == "Light" ){

                        $class_color    = "light";
                        $class_button   = "white outline";
                    }
                ?>

                    <div>
                        <img src="<?php echo $slide['url']; ?>" alt="" />
                        <div class="pattern">
                            <div class="slide-content <?php echo $class_color; ?>">
                                <div class="div-align-center">
                                    <?php if( $slide['text_highlighted'] ){ ?>
                                    <p class="georgia"><?php echo $slide['text_highlighted']; ?></p>
                                    <?php } ?>
                                    <?php if( $slide['text_heading'] ){ ?>
                                    <h1><?php echo $slide['text_heading']; ?></h1>
                                    <?php } ?>
                                    <?php if( $slide['text_normal'] ){ ?>
                                    <p><?php echo $slide['text_normal']; ?></p>
                                    <?php } ?>
                                    <?php if( $slide['button_name'] ){ ?>
                                    <a href="<?php echo $slide['button_url']; ?>" class="newave-button medium <?php echo $class_button; ?>"><?php echo $slide['button_name']; ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
                }

            } //end foreach
        }

?>

    </div>

</div>