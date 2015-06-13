<?php

global $global_theme_options;

$slider_auto = true;
if( !$global_theme_options['home_parallax_slider_auto'] ){

    $slider_auto = false;
}

wp_localize_script( 'scriptsjs',
                    'FullwidthSliderOptions',
                    array(  "slider_auto"   => $slider_auto,
                            "slider_speed"  => $global_theme_options['home_parallax_slider_speed'] ) );


?>

    <div id="<?php echo $post->post_name; ?>" class="home-section parallax-slider">

        <!-- Slider -->
        <ul class="fullwidth-slider">

            <?php

                $parallax_slider = $global_theme_options['home_parallax_slider_slider'];
                if ( !empty( $parallax_slider )) {

                    foreach( $parallax_slider  as $slide ){

                        $class_color    = "";
                        $class_button   = "";
                        if( $slide['text_color'] == "Light" ){

                            $class_color    = "";
                            $class_button   = "white outline move";
                        }
                        else{

                            $class_color    = "dark";
                            $class_button   = "grey move";
                        }

            ?>
            <li class="slide" style="background-image: url(<?php echo $slide['url']; ?>)" data-anchor-target="section" data-70-top="background-position: center 400px;" data-start="background-position: center 0px;">

                <!-- Caption -->
                <div class="caption">
                    <div class="caption-inside" data-anchor-target="section"  data-start="top: 0px; opacity: 1;" data-200-top="top: 270px; opacity: 0;">
                        <div class="slide-caption">
                            <div class="vertical-align-middle <?php echo $class_color; ?>">

                                <?php if( $slide['text_highlighted'] ){ ?>
                                    <p class="georgia"><?php echo $slide['text_highlighted']; ?></p>
                                <?php } ?>
                                <?php if( $slide['text_heading'] ){ ?>
                                    <h1 class="ultralarge"><?php echo $slide['text_heading']; ?></h1>
                                <?php } ?>
                                <?php if( $slide['text_normal'] ){ ?>
                                    <p><?php echo $slide['text_normal']; ?></p>
                                <?php } ?>
                                <?php if( $slide['button_name'] ){ ?>
                                    <p><a href="<?php echo $slide['button_url']; ?>" class="newave-button medium <?php echo $class_button; ?>"><?php echo $slide['button_name']; ?></a></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/.Caption -->

            </li>

            <?php
                    }
                }
            ?>

            </li>

        </ul>
        <!-- Slider -->

    </div>