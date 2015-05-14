<?php
/**
 * Created by Clapat.
 * Utility function used throughout the theme
 * Date: 21/02/14
 * Time: 12:54 PM
 */
function get_htag_font_option(){

    global $global_theme_options;

    $htag_font = '';
    if( isset( $global_theme_options['custom_google_font_htag'] ) && ( trim($global_theme_options['custom_google_font_htag']) != '') ){

        $htag_font = $global_theme_options['custom_google_font_htag'];
        return $htag_font;
    }

    if( isset( $global_theme_options['google_font_htag'] ) && $global_theme_options['google_font_htag'] && ($global_theme_options['google_font_htag'] != 'Select Font') ){

        $htag_font = $global_theme_options['google_font_htag'];
    }

    return $htag_font;
}

function get_body_font_option(){

    global $global_theme_options;

    $body_font = '';
    if( isset( $global_theme_options['custom_google_font_body'] ) && ( trim($global_theme_options['custom_google_font_body']) != '') ){

        $body_font = $global_theme_options['custom_google_font_body'];
        return $body_font;
    }

    if( isset( $global_theme_options['google_font_body'] ) && $global_theme_options['google_font_body'] && ($global_theme_options['google_font_body'] != 'Select Font') ){

        $body_font = $global_theme_options['google_font_body'];
    }

    return $body_font;
}

function get_portfolio_featured_images_no(){

    global $global_theme_options;

    $images_no = 5;
    if( isset( $global_theme_options['portfolio_featured_images'] ) && ( trim($global_theme_options['portfolio_featured_images']) != '') ){

        $images_no = trim($global_theme_options['portfolio_featured_images']);
    }
    if( $images_no < 1){

        $images_no = 1;
    }
    if( $images_no > 50){

        $images_no = 50;
    }

    return $images_no;
}

function get_blog_post_featured_images_no(){

    global $global_theme_options;

    $images_no = 5;
    if( isset( $global_theme_options['blog_featured_images'] ) && ( trim($global_theme_options['blog_featured_images']) != '') ){

        $images_no = trim($global_theme_options['blog_featured_images']);
    }
    if( $images_no < 1){

        $images_no = 1;
    }
    if( $images_no > 50){

        $images_no = 50;
    }

    return $images_no;
}

?>