
<?php

global $global_theme_options;

wp_localize_script( 'scriptsjs',
    'TextSliderOptions',
    array(  "slider_speed"       => $global_theme_options['fullscreen_image_parallax_text_slider_speed'],
            "slider_transition"  => $global_theme_options['fullscreen_image_parallax_text_slider_transition'] ) );

?>

    <div id="<?php echo $post->post_name; ?>" class="parallax home-section" style="background-image: url('<?php echo $global_theme_options['fullscreen_image_parallax_background']; ?>');">

        <div class="pattern">

            <div class="container">

                 <div id="home-center" class="element_fade_in">

                    <div class="div-align-center">

                        <?php
                        if( !empty( $global_theme_options['fullscreen_image_parallax_content'] ) ){
                            the_content();
                        }
                        ?>

						<?php $text_slider = $global_theme_options['fullscreen_image_parallax_text_slider'];
                            if ( !empty( $text_slider )) { ?>
							<ul class="text-slide-vertical">
						<?php	
                                foreach( $text_slider  as $slide){
                                    echo '<li>' . $slide['text'] . '</li>';
                                } //end foreach
						?>		
							</ul>
						<?php
							}
                        ?>
                        
						<?php $bullet_words = $global_theme_options['fullscreen_image_parallax_bullet_words'];
                            if ( !empty( $bullet_words )) { ?>
                        <?php if( $global_theme_options['fullscreen_image_parallax_text_color'] == "Light" ){ ?>
                            <p class="light">
                        <?php } else { ?>
                            <p>
                        <?php }
                            
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
                        
						<?php if( !empty( $global_theme_options['fullscreen_image_parallax_button_url'] ) ){ ?>
							<?php if( $global_theme_options['fullscreen_image_parallax_text_color'] == "Light" ){ ?>
                            <a href="<?php echo $global_theme_options['fullscreen_image_parallax_button_url']; ?>" class="newave-button medium outline white"><?php echo $global_theme_options['fullscreen_image_parallax_button_name']; ?></a>
							<?php } else { ?>
                            <a href="<?php echo $global_theme_options['fullscreen_image_parallax_button_url']; ?>" class="newave-button medium outline white"><?php echo $global_theme_options['fullscreen_image_parallax_button_name']; ?></a>
							<?php } 
						}
						?>

                    </div>

                </div>

             </div>

         </div>

    </div>