					<div class="type-date">                    
                    	<div class="blog-type"><img src="<?php echo get_template_directory_uri()."/images/blog-quote.png"; ?>" alt=""></div>
                        <div class="blog-date"><h5><?php echo date_i18n( __('d'), get_post_time() ); ?></h5><h5><?php echo date_i18n( __('M'), get_post_time() ); ?></h5></div>                    
                    </div>
                	
                    
                    <!-- Post Content -->
                	<div class="post-content">
                            
                        <div class="post-quote">
                        	<?php echo get_post_meta($post->ID, 'newave_blog_post_quote', true); ?>
                        </div>
                    