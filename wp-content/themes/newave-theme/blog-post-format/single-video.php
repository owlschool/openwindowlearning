					<div class="type-date">                    
                    	<div class="blog-type"><img src="<?php echo get_template_directory_uri()."/images/blog-video.png"; ?>" alt=""></div>
                        <div class="blog-date"><h5><?php echo date_i18n( __('d'), get_post_time() ); ?></h5><h5><?php echo date_i18n( __('M'), get_post_time() ); ?></h5></div>                    
                    </div>
                	
                    
                    <!-- Post Content -->
                	<div class="post-content">

                        <?php if(get_post_meta($post->ID, 'newave_blog_post_video', true)){ ?>
                    	<div class="video-wrapper">
                            <div class="video-container">
                                <?php echo get_post_meta($post->ID, 'newave_blog_post_video', true); ?>
                            </div>
                        </div>
                        <?php } ?>