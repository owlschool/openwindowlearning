<?php

global $global_theme_options;

?>

    <?php if( $global_theme_options['blog_post_title'] ) { ?>
    <h3 class="blog-title"><a href="<?php the_permalink(); ?>" class="external"><?php the_title(); ?></a></h3>
    <?php } ?>

    <?php if( $global_theme_options['blog_layout'] == "Normal" ) { ?>
        <p class="blog-meta">
            <?php if( $global_theme_options['author_info'] ){ ?>
                <?php _e( 'Posted by', THEME_LANGUAGE_DOMAIN ); ?> <?php the_author_posts_link(); ?> |
            <?php } ?>
            <?php if ( has_tag() ) { ?>
                <?php the_tags('', ' · ', ''); ?> |
            <?php } else { ?>
                <?php _e( 'No Tags', THEME_LANGUAGE_DOMAIN ); ?> |
            <?php } ?>
            <?php the_category(' · '); ?>
            <?php if( $global_theme_options['blog_comments'] ){ ?>
                | <?php comments_popup_link(); ?>
            <?php } ?>
        </p>
    <?php } else { ?>
        <p class="blog-meta">
            <?php if( $global_theme_options['author_info'] ){ ?>
                <?php _e( 'Posted by', THEME_LANGUAGE_DOMAIN ); ?> <?php the_author_posts_link(); ?> |
            <?php } ?>
            <?php the_time('F j'); ?>
            <?php if( $global_theme_options['blog_comments'] ){ ?>
                | <?php comments_popup_link(); ?>
            <?php } ?>
    <?php } ?>

    <div class="blog-border"></div>

    <div class="blog-content">

        <p>
            <?php
            if( ($global_theme_options['content_length'] == 'excerpt') || is_search() || is_archive() || is_category() ) {

                the_excerpt();

            } else {

                the_content('');

            ?>

            <div class="page-links">
            <?php

            wp_link_pages();

            ?>
            </div>

            <?php
            }
        ?>
        </p>

    </div>

    <?php if( $global_theme_options['blog_layout'] == "Normal" ) { ?>
        <a href="<?php the_permalink(); ?>" class="newave-button medium color external"><?php _e( "Read More", THEME_LANGUAGE_DOMAIN ); ?></a>
    <?php } else { ?>
        <a href="<?php the_permalink(); ?>" class="newave-button small grey external"><?php _e( "Read More", THEME_LANGUAGE_DOMAIN ); ?></a>
    <?php } ?>

    <?php if( $global_theme_options['blog_layout'] == "Normal" ) { ?>
    <hr>
    <?php } ?>
