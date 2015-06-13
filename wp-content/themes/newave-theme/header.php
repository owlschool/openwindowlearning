<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php bloginfo('name'); ?> <?php wp_title(' - ', true, 'left'); ?></title>

    <?php global $global_theme_options; ?>
    
    <link rel="shortcut icon" href="<?php if( $global_theme_options['favicon'] ){ echo $global_theme_options['favicon']; } else { echo get_template_directory_uri()."/images/favicon.ico"; } ?>">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> data-spy="scroll" data-target=".navbar" data-offset="75">

    <?php
    if( $global_theme_options['loading_img'] ){

        $bknd_color_style = '';
        if( !empty( $global_theme_options['loading_img_bknd_color'] ) ){

            $bknd_color_style = 'style="background-color: ' . $global_theme_options['loading_img_bknd_color'] . ';"';
        }
    ?>
    <!-- Preloader -->
    <div class="mask" <?php echo $bknd_color_style; ?>><div id="loader" style="background:url(<?php echo $global_theme_options['loading_img']; ?>) no-repeat scroll center center transparent"></div></div>
    <!--/Preloader -->
    <?php
    }
    ?>