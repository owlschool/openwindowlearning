    <!-- Parallax Section -->
	<div id="<?php echo $post->post_name; ?>" class="parallax" style="background-image: url('<?php echo get_post_meta(get_the_ID(), 'newave_page_parallax_background_image', true); ?>');">

        <?php
        $container_type = get_post_meta(get_the_ID(), 'newave_page_parallax_container', true);
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

        <?php
        $overlay_type = get_post_meta(get_the_ID(), 'newave_page_parallax_image_overlay', true);
        if( $overlay_type == 'pattern' ){

            $overlay_type = 'parallax-pattern-overlay';
        }
        else if( $overlay_type == 'color_dark' ){

            $overlay_type = 'parallax-overlay';
        }
        else if( $overlay_type == 'color_primary' ){

            $overlay_type = 'parallax-overlay parallax-background-color';
        }
        else if( $overlay_type == 'color_none' ){

            $overlay_type = 'no-overlay';
        }
        else {

            $overlay_type = '';
        }
        ?>

		<div class="<?php echo $overlay_type; ?>">
        	<div class="container <?php echo $container_type; ?>">
            
            	<?php the_content(); ?>
                
            </div>    
    	</div>
        
	</div>
    <!--/Parallax Section -->