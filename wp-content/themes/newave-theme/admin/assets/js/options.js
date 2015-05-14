jQuery(document).ready(function($){


    /*----------------------------------------------------------------------------------*/
    /*	Display sections in admin as needed
     /*----------------------------------------------------------------------------------*/


    jQuery('#home_layout_type').change(checkHomeSectionFormat);

    function checkHomeSectionFormat(){
        var format = jQuery('#home_layout_type option:selected').attr('value');

        if(typeof format != 'undefined'){

            $('#of-option-homeoptions div[id^=section-home_image_pattern]').hide();
            $('#of-option-homeoptions div[id^=section-home_full_screen_slider]').hide();
            $('#of-option-homeoptions div[id^=section-home_parallax_slider]').hide();
            $('#of-option-homeoptions div[id^=section-fullscreen_image_parallax]').hide();
            $('#of-option-homeoptions div[id^=section-home_video]').hide();
            $('#of-option-homeoptions div[id^=section-home_moving_bknd]').hide();
            $('#of-option-homeoptions div[id^=section-home_overlay_slider]').hide();

            if( format == "Image Pattern" ){

                $('#of-option-homeoptions div[id^=section-home_image_pattern]').stop(true,true).fadeIn(500);
            }
            if( format == "Full Screen Slider" ){

                $('#of-option-homeoptions div[id^=section-home_full_screen_slider]').stop(true,true).fadeIn(500);
            }
            if( format == "Full Width Parallax Slider" ){

                $('#of-option-homeoptions div[id^=section-home_parallax_slider]').stop(true,true).fadeIn(500);
            }
            if( format == "Full Screen Image Parallax" ){

                $('#of-option-homeoptions div[id^=section-fullscreen_image_parallax]').stop(true,true).fadeIn(500);
            }
            if( format == "Full Screen Video Background" ){

                $('#of-option-homeoptions div[id^=section-home_video]').stop(true,true).fadeIn(500);
            }
            if( format == "Full Screen Moving Background" ){

                $('#of-option-homeoptions div[id^=section-home_moving_bknd]').stop(true,true).fadeIn(500);
            }
            if( format == "Full Screen Overlay Slider" ){

                $('#of-option-homeoptions div[id^=section-home_overlay_slider]').stop(true,true).fadeIn(500);
            }

        }

    }

    jQuery('#color_skin').change(checkColorSkin);

    function checkColorSkin(){

        var format = jQuery('#color_skin option:selected').attr('value');

        if(typeof format != 'undefined'){

           if( format == 'custom' ){

               jQuery('#of-option-stylingoptions #section-custom_color').stop(true,true).fadeIn(500);
           }
           else{

               jQuery('#of-option-stylingoptions #section-custom_color').hide();
           }
        }
    }

    jQuery(window).load(function(){

        checkHomeSectionFormat();

        checkColorSkin();

    })


});


