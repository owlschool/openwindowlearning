<?php

global $global_theme_options;

$audio_embed = get_post_meta( $post->ID, 'newave_blog_post_audio', true );

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
                    	<div class="blog-type"><img src="<?php echo get_template_directory_uri()."/images/blog-audio.png"; ?>" alt=""></div>
                        <div class="blog-date"><h5><?php echo date_i18n( __('d'), get_post_time() ); ?></h5><h5><?php echo date_i18n( __('M'), get_post_time() ); ?></h5></div>                    
                    </div>
                    </a>
                    <?php } ?>
                	
                    
                    <!-- Post Content -->
                	<div class="post-content">

                        <?php if( $audio_embed ){ ?>
                        <div class="audio-wrapper">
                            <div class="audio-container">
                                <?php echo $audio_embed; ?>
                            </div>
                        </div>
                        <?php } ?>

                        <?php get_template_part('blog-post-format/post-common'); ?>
                    
                    </div>
                	<!--/Post Content -->
                    
                </div>
            	<!-- Blog Post -->


