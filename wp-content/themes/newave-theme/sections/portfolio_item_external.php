<?php

get_header();

get_template_part('sections/menu_section');

?>

    <!-- Project Header -->
    <section class="project-header">

        <?php

        $image_background = get_post_meta( get_the_ID(), 'newave_portfolio_item_external_header_image', true );
        $subtitle = get_post_meta( get_the_ID(), 'newave_portfolio_item_external_header_subtitle', true );
        $text_align = get_post_meta( get_the_ID(), 'newave_portfolio_item_external_header_text_alignment', true );

        if( $image_background ){

            $image_background = 'style="background-image: url(' . $image_background . ');"';
        }

        ?>

        <div class="block block-intro" <?php echo $image_background; ?>>
            <div class="vertical-align global" style="text-align: <?php echo $text_align; ?>;">
                <div class="container">
                    <h1 style="color:#fff">
                        <span class="text-large"><?php the_title(); ?></span>
                        <span class="text-small"><?php echo $subtitle; ?></span>
                    </h1>
                </div>
            </div>
        </div>



    </section>
    <!--/Project Header -->


<?php

the_content();

global $global_theme_options;

// portfolio social sharting icons
if( !empty($global_theme_options['portfolio_social']) && $global_theme_options['portfolio_social'] == 1 ) {

    $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' );

    echo '<div id="single-portfolio-featured-image" data-featured-img="' . $thumbnail[0] . '"></div>';

    echo '<div class="newave-social-container">';

    echo '<div class="newave-social sharing">';

    echo '<span class="n-shortcode">'.newave_like('return').'</span>';

    //facebook
    if(!empty($global_theme_options['portfolio-facebook-sharing']) && $global_theme_options['portfolio-facebook-sharing'] == 1) {
        echo "<a class='facebook-share newave-sharing' href='#' title='". __('Share this', THEME_LANGUAGE_DOMAIN) . "'> <i class='fa fa-facebook'></i> <span class='count'></span></a>";
    }

    //twitter
    if(!empty($global_theme_options['portfolio-twitter-sharing']) && $global_theme_options['portfolio-twitter-sharing'] == 1) {
        echo "<a class='twitter-share newave-sharing' href='#' title='". __('Tweet this', THEME_LANGUAGE_DOMAIN) . "'> <i class='fa fa-twitter'></i> <span class='count'></span></a>";
    }

    //pinterest
    if(!empty($global_theme_options['portfolio-pinterest-sharing']) && $global_theme_options['portfolio-pinterest-sharing'] == 1) {
        echo "<a class='pinterest-share newave-sharing' href='#' title='". __('Pin this', THEME_LANGUAGE_DOMAIN) . "'> <i class='fa fa-pinterest'></i> <span class='count'></span></a>";
    }

    echo '</div>';

    if( trim( $global_theme_options['portfolio_sharing_text'] ) ){
        echo '<p>' . $global_theme_options['portfolio_sharing_text'] . '</p>';
    }

    echo '</div>';
}

get_footer();

?>