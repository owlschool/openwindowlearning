	
	
	<!-- Home Section -->
        <?php
        global $global_theme_options;
        $section_type = $global_theme_options['home_layout_type'];

        if( $section_type == 'Full Screen Slider' ){

            get_template_part('sections/home_section_slider');
        }
        else if( $section_type == 'Full Width Parallax Slider' ){

            get_template_part('sections/home_section_parallax_slider');
        }
        else if( $section_type == 'Full Screen Image Parallax' ){

            get_template_part('sections/home_section_parallax_image');
        }
        else if( $section_type == 'Full Screen Video Background' ){

            get_template_part('sections/home_section_parallax_video');
        }
        else if( $section_type == 'Full Screen Moving Background' ){

            get_template_part('sections/home_section_moving_background');
        }
        else if( $section_type == 'Full Screen Overlay Slider' ){

            get_template_part('sections/home_section_overlay_slider');
        }
        else{

            get_template_part('sections/home_section_pattern');
        }

        ?>

	<!-- End Home Section -->