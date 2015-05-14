<?php

global $global_theme_options;

$post_icon = get_template_directory_uri()."/images/blog-text.png";
if( has_post_thumbnail() )
    $post_icon = get_template_directory_uri()."/images/blog-image.png";

?>
    			<!-- Blog Post -->
    			<?php if( $global_theme_options['blog_layout'] == "Normal" ) { ?>
    			<div id="post-<?php the_ID(); ?>" <?php post_class("blog-post"); ?>>
    			<?php } else { ?>
    			<div id="post-<?php the_ID(); ?>" <?php post_class("blog-post masonry"); ?>>
    			<?php } ?>
    			
    				<?php if( $global_theme_options['blog_layout'] == "Normal" ) { ?>
                    <a href="<?php the_permalink(); ?>" class="external">
                    <div class="type-date">
                    	<div class="blog-type"><img src="<?php echo $post_icon; ?>" alt=""></div>
                        <div class="blog-date"><h5><?php echo date_i18n( __('d'), get_post_time() ); ?></h5><h5><?php echo date_i18n( __('M'), get_post_time() ); ?></h5></div>                    
                    </div>
                    </a>
                    <?php } ?>
                	
                    
                    <!-- Post Content -->
                	<div class="post-content">

                        <?php if( has_post_thumbnail() ){
                            $full_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                        ?>
                        <div class="post-slider">
                            <ul class="blog-slider">
                                <li><img src="<?php echo $full_image[0]; ?>" alt=""></li>
                            </ul>
                        </div>
                        <?php } ?>

                        <?php get_template_part('blog-post-format/post-common'); ?>
                    
                    </div>
                	<!--/Post Content -->
                    
                </div>
            	<!-- Blog Post -->


