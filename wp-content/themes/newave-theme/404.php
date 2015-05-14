<?php 

get_header(); 

global $global_theme_options;

?>

    <!-- Home Section -->
    <div id="not-found">

        <div class="home-pattern">

            <div class="container clearfix">

                <div id="home-center" class="element_fade_in">

                    <div class="div-align-center">

                        <h1 class="four-zero-four "><span class="text-color"><?php echo $global_theme_options['404_title']; ?></span></h1>

                        <p class="below-four-zero-four"><?php echo $global_theme_options['404_subtitle']; ?></p>

                        <a href="<?php echo home_url(); ?>" class="newave-button medium outline white external"><?php echo $global_theme_options['404_escape']; ?></a>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- End Home Section -->
    
<?php get_footer(); ?>