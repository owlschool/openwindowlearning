<?php

global $global_theme_options;

?>
	
	<!-- Navigation -->
    <div id="navigation" class="navbar navbar-fixed-top is-sticky">
		<div class="navbar-inner ">
        	<div class="container no-padding">
				
					<a class="show-menu" data-toggle="collapse" data-target=".nav-collapse">
						<span class="show-menu-bar"></span>
					</a>

                    <?php
                        // retrieve the path to the logo displayed in the menu bar
                    if( $global_theme_options['header_layout'] == 'v1' ) {
                        $logo_path = $global_theme_options['logo_v1'];
                        if( !$logo_path ){
                            $logo_path = get_template_directory_uri() . "/images/logo.png";
                        }
                    }
                    if( $global_theme_options['header_layout'] == 'v2' ) {
                        $logo_path = $global_theme_options['logo_v2'];
                        if( !$logo_path ){
                            $logo_path = get_template_directory_uri() . "/images/logo1.png";
                        }
                    }
                    if( $global_theme_options['header_layout'] == 'v3' ) {
                        $logo_path = $global_theme_options['logo_v3'];
                        if( !$logo_path ){
                            $logo_path = get_template_directory_uri() . "/images/logo2.png";
                        }
                    }
                    if( $global_theme_options['header_layout'] == 'v4' ) {
                        $logo_path = $global_theme_options['logo_v4'];
                        if( !$logo_path ){
                            $logo_path = get_template_directory_uri() . "/images/logo1.png";
                        }
                    }
					?>
                    <?php if( $global_theme_options['header_layout'] == 'v1' ) { ?>
                    <div id="logo" style="background:url(<?php echo $logo_path; ?>)"><a class="external" href="<?php echo get_home_url(); ?>"></a></div>
                    <?php } else { ?>
                    <div id="logo">
                        <a class="external"  style="background:url()" href="<?php echo get_home_url(); ?>">
                            <img src="<?php echo $logo_path; ?>">
                        </a>
                    </div>
                    <?php } ?>
                    <div class="nav-collapse collapse">
						<?php
                        if( has_nav_menu('main') ){
                           if( is_user_logged_in() ) {
                			wp_nav_menu(array(
                                                'menu' => 'main-loggedin',
                  				'theme_location' => 'main',
                  				'container' => '',
                  				'menu_class' => 'nav',
                  				'menu_id' => 'nav',
                  				'echo' => true,
                  				'walker' => new Menu_Walker(),
                  				'depth' => 0, 
							));
                           } else {
                			wp_nav_menu(array(
                  				'theme_location' => 'main',
                  				'container' => '',
                  				'menu_class' => 'nav',
                  				'menu_id' => 'nav',
                  				'echo' => true,
                  				'walker' => new Menu_Walker(),
                  				'depth' => 0 
							));
                           }
                        }
                        else{
                        ?>
                            <p class="no-main-menu-text">
                            <?php
                            _e('There is no Main Menu assigned!', THEME_LANGUAGE_DOMAIN);
                            ?>
                            </p>

                        <?php
                        }
              			?>
					</div>
				</div>
			</div>
	</div>
    <!--/Navigation -->