<?php

global $global_theme_options;

$social_links = array(  "Facebook"      => "facebook",
                        "Twitter"       => "twitter",
                        "Linkedin"      => "linkedin",
                        "Behance"       => "behance",
                        "Deviantart"    => "deviantart",
                        "Dribble"       => "dribble",
                        "Flickr"        => "flickr",
                        "Google+"       => "google",
                        "Lastfm"        => "lastfm",
                        "Picasa"        => "picasa",
                        "Pinterest"     => "pinterest",
                        "RSS"           => "rss",
                        "Skype"         => "skype",
                        "Stumble"       => "stumble",
                        "Vimeo"         => "vimeo",
                        "Youtube"       => "youtube",
                        "Instagram"     => "instagram"
                    );

$footer_position = 'normal';
if( isset($global_theme_options['footer_position']) && $global_theme_options['footer_position'] ){

    $footer_position = $global_theme_options['footer_position'];
}


?>

    <!-- Footer -->
    <?php if( $footer_position == 'hidden' ) {
        echo '<footer class="fixed">';
    } else {
        echo '<footer>';
    }
    ?>
		<div class="container no-padding">

            <a id="back-top"><div id="menu_top"><div id="menu_top_inside"></div></div></a>

            <?php if( $global_theme_options['footer_social_links'] ) { ?>
            <ul class="socials-icons">
                <?php
                foreach( $social_links as $key => $value ){

                    $option_idx = "social_link_" . $value;

                    if( $global_theme_options[$option_idx] ){
                ?>
                <li><a href="<?php echo $global_theme_options[$option_idx]; ?>"><img src="<?php echo get_template_directory_uri()."/images/social_icons/" . $value . ".png"; ?>" alt="<?php echo $key; ?>" /></a></li>
                <?php
                    }
                }
                ?>
            </ul>
            <?php } ?>
            
			<p class="copyright"><?php echo $global_theme_options['footer_text']; ?></p>
            
		</div>
	</footer>
	<!--/Footer -->
    <?php if( $footer_position == 'hidden' ) { ?>
    <div class="footer-height">
    </div>
    <?php } ?>

<?php wp_footer(); ?>
</body>
	
	<?php get_template_part("inline_scripts"); ?>
	
</html>