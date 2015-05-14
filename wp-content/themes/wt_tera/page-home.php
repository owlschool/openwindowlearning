<?php
/**
  * Template Name: Home Page
  * Description: A Page Template to display home page content   
  *
  * @package  WellThemes  
  * @file     page-home.php  
  * @author   Well Themes Team  
  * @link 	 http://wellthemes.com   
  */?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	echo ' - ';

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'wellthemes' ), max( $paged, $page ) );

	?>
</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php
	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>
<body <?php body_class(); ?>>
<style type="text/css">
#header { padding-bottom: 0px; background-color:#fafafa; color: #628e06; }
#header #container.top-page-header, #header .top-page-header-full-width { background-color:#fafafa; color: #628e06;}
}
</style>
    <header id="header">
        <div class="top-page-header-full-width">
	<div id="container" class="hfeed top-page-header">
            <div class="top-edge" style="width: 100%; overflow: hidden;">
                <div class="logo" style="width: 35%; float: left; display:inline-block; margin-top:5px;">			
                    <h1 class="site-title">
                        <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>" style="font-weight:bold; font-size: 20px;"><?php bloginfo('name'); ?></a>
                    </h1>					
                </div>
                <div class="home-page-login-section" style="float: right; display: inline-block; text-align: right; margin-top:5px;">
                    <?php if ( $user_ID ) : ?>
                        <?php global $user_identity; ?>
                        <?php $profile_url = get_option('siteurl') . '/membership-account/'; ?>
                        <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
                            <b>Hola</b>, <a href="<?php echo $profile_url; ?>"><?php echo $user_identity; ?></a>.  [<a href="<?php echo wp_logout_url(); ?>" title="Salir de esta cuenta">Salir</a>]
                        <?php } else { ?>
                            <b>Hello</b>, <a href="<?php echo $profile_url; ?>"><?php echo $user_identity; ?></a>.  [<a href="<?php echo wp_logout_url(); ?>" title="Log out of this account">Log Out</a>]
                        <?php } ?>
                    <?php else : ?>
                        <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
                            <form name="loginform" id="loginform" action="<?php echo get_option('siteurl'); ?>/login/" method="post">
                                <input value="Nombre de Usuario" type="text" size="20" tabindex="10" name="log" id="user_login"  onfocus="if (this.value == 'Nombre de Usuario') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Nombre de Usuario';}" style="color:#999999; padding-left: 5px; font-family: 'Open Sans', sans-serif, serif;"/>
                                <input value="Contraseña" type="password" size="20" tabindex="20" name="pwd" id="user_pass" onfocus="if (this.value == 'Contraseña') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Contraseña';}" style="border: 1px solid #E7E7E7; color:#999999; padding-left: 5px; font-family: 'Open Sans', sans-serif, serif;"/>
                                <input name="wp-submit" id="wp-submit" value="Entrar" tabindex="100" type="submit" style="">
                                <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=lostpassword" value="Lost Password">Forgot Password?</a>
                            </form>
                        <?php } else { ?>
                            <form name="loginform" id="loginform" action="<?php echo get_option('siteurl'); ?>/login/" method="post">
                                <input value="Username" type="text" size="20" tabindex="10" name="log" id="user_login"  onfocus="if (this.value == 'Username') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Username';}" style="color:#999999; padding-left: 5px; font-family: 'Open Sans', sans-serif, serif;"/>
                                <input value="Password" type="password" size="20" tabindex="20" name="pwd" id="user_pass" onfocus="if (this.value == 'Password') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Password';}" style="border: 1px solid #E7E7E7; color:#999999; padding-left: 5px;font-family: 'Open Sans', sans-serif, serif;"/>
                                <input name="wp-submit" id="wp-submit" value="Log In" tabindex="100" type="submit">
                                <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=lostpassword" value="Lost Password">Forgot Password?</a>
                            </form>
                        <?php } ?> 
                    <?php endif; ?>
                </div>
            </div>
            <div class="menu-section clearfix">
                <nav id="main-menu" class="clearfix">
                    <?php wp_nav_menu( array( 'theme_location' => 'top-menu', 'container' => '0', 'fallback_cb' => 'wellthemes_main_menu_fallback',) ); ?>
                </nav>
            </div>
            </div>
        </div>
    </header>

    <div style="display:block; width: 100%; padding: 0px; margin: 0px; height:400px; position:relative">
        <div style="display:table; width: 90%; margin-left:auto; margin-right:auto;">
            <div style="display:table-row; width:100%;">
                <div style="display:table-cell; width: 50%; border-right: 10px solid black; border-bottom: 10px solid black; text-align:center">
                    <span style="color:blue; font-size:38px;">GED</span>
                </div>
                <div style="display:table-cell; width: 50%; border-left: 10px solid black; border-bottom: 10px solid black; text-align:center">
                    <span style="color:blue; font-size:38px;">HiSET</span>
                </div>
            </div>
            <div style="display:table-row; width:100%;">
                <div style="display:table-cell; width: 50%; border-right: 10px solid black; border-top: 10px solid black; text-align:center">
                    <span style="color:blue; font-size:38px;">TASC</span>
                </div>
                <div style="display:table-cell; width: 50%; border-left: 10px solid black; border-top: 10px solid black; text-align:center">
                    <span style="color:blue; font-size:38px;">HSE</span>
                </div>
            </div>
        </div>
    </div>
                <div style="display:block; width: 100%; padding: 0px; margin: 0px; font-size: 40px; text-align: center; color: white; background-color: #455268;">
                    Start studying now!
                </div>
    <div id="container" class="hfeed">
        <section>
<?php get_footer(); ?>