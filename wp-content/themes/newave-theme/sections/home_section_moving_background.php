<?php

global $global_theme_options;

$scroll_speed = '5';
if( isset($global_theme_options['home_moving_bknd_scroll_speed']) && $global_theme_options['home_moving_bknd_scroll_speed'] ){

    if( $global_theme_options['home_moving_bknd_scroll_speed'] == 'slow' ){

        $scroll_speed = '100';
    }

}

$scroll_direction = 'h';
if( isset($global_theme_options['home_moving_bknd_direction']) && $global_theme_options['home_moving_bknd_direction'] ){

    if( $global_theme_options['home_moving_bknd_direction'] == 'horizontal' ){

        $scroll_direction = 'h';
    }
    if( $global_theme_options['home_moving_bknd_direction'] == 'vertical' ){

        $scroll_direction = 'v';
    }
    if( $global_theme_options['home_moving_bknd_direction'] == 'diagonal' ){

        $scroll_direction = 'd';
    }

}

wp_localize_script( 'scriptsjs',
                    'CollageBkndOptions',
                    array(  "scroll_speed"      => $scroll_speed,
                            "scroll_direction"  => $scroll_direction ) );
?>

    <!-- Home Section -->
    <div id="<?php echo $post->post_name; ?>" class="home-section collage">


        <?php
        $collage_style = 'style="';
        if( isset($global_theme_options['home_moving_bknd_image']) && $global_theme_options['home_moving_bknd_image'] ){
            $collage_style .= 'background: url(' . $global_theme_options['home_moving_bknd_image'] . ') repeat;';
        }
        if( isset($global_theme_options['home_moving_bknd_repeat']) && $global_theme_options['home_moving_bknd_repeat'] ){
            if( $global_theme_options['home_moving_bknd_repeat'] == 'yes' ){
                $collage_style .= 'background-size:auto;';
            }
            else{
                $collage_style .= 'background-size:cover;';
            }
        }
        $collage_style .= '"';

        $collage_color = 'rgba(0,0,0,';
        $collage_opacity = '0.8';
        if( isset($global_theme_options['home_moving_bknd_color']) && $global_theme_options['home_moving_bknd_color'] ){

            $hex = str_replace("#", "", $global_theme_options['home_moving_bknd_color']);

            if(strlen($hex) == 3) {
                $r = hexdec(substr($hex,0,1).substr($hex,0,1));
                $g = hexdec(substr($hex,1,1).substr($hex,1,1));
                $b = hexdec(substr($hex,2,1).substr($hex,2,1));
            } else {
                $r = hexdec(substr($hex,0,2));
                $g = hexdec(substr($hex,2,2));
                $b = hexdec(substr($hex,4,2));
            }

            $collage_color = 'rgba('. $r .','. $g . ','. $b .',';
        }

        if( isset($global_theme_options['home_moving_bknd_opacity']) && $global_theme_options['home_moving_bknd_opacity'] ){

            $collage_opacity = $global_theme_options['home_moving_bknd_opacity'];
        }

        $collage_overlay = 'style="background-color:' . $collage_color . $collage_opacity . ');"';

        $text_color  = '';
        $arrow_image = get_template_directory_uri() . '/images/scroll_arrows.png';
        $arrow_class = '';
        if( isset($global_theme_options['home_moving_bknd_text_color']) && $global_theme_options['home_moving_bknd_text_color'] == 'Dark'){

            $text_color  = ' dark';
            $arrow_image = get_template_directory_uri() . '/images/scroll_arrows_black.png';
            $arrow_class = 'class="arrow-box-dark"';
        }

        ?>

        <div id="collage" <?php echo $collage_style; ?>>

            <div class="collage-overlay <?php echo $text_color; ?>" <?php echo $collage_overlay; ?> >


                <div class="container clearfix">

                    <div id="home-center" class="element_fade_in">

                        <div class="div-align-center">

                            <?php
                            if( !empty( $global_theme_options['home_moving_bknd_content'] ) ){
                                the_content();
                            }
                            ?>

                                <?php $bullet_words = $global_theme_options['home_moving_bknd_bullet_words'];
                                if ( !empty( $bullet_words )) { ?>
                            <p class="colage-text">
								<?php	
									$first = true;
                                    foreach( $bullet_words  as $slide){
                                        if( !$first )
                                            echo '<span class="bullet">•</span>';
                                        echo $slide['text'];
                                        $first = false;
                                    } //end foreach
								?>
							</p>	
								<?php
                                }
                                ?>
                            
                                <?php $text_slider = $global_theme_options['home_moving_bknd_text_slider'];
                                if ( !empty( $text_slider )) { ?>
							<ul class="text-slide-vertical <?php echo $text_color; ?>">
								<?php
                                    foreach( $text_slider  as $slide){
                                        echo '<li>' . $slide['text'] . '</li>';
                                    } //end foreach
                                ?>
							</ul>		
								<?php	
                                }
                                ?>
                            
                            <?php if( isset($global_theme_options['home_moving_bknd_arrow_url']) && $global_theme_options['home_moving_bknd_arrow_url'] ){ ?>

                            <div id="arrow-box" <?php echo $arrow_class; ?>>
                                <div id="arrow" style="position: relative;">
                                    <a href="<?php echo $global_theme_options['home_moving_bknd_arrow_url'];  ?>"><img src="<?php echo $arrow_image; ?>" alt="" /></a>
                                </div>
                            </div>

                           <?php } ?>

                        </div>

                    </div>





                </div>

            </div>

        </div>


    </div>
    <!-- End Home Section -->
