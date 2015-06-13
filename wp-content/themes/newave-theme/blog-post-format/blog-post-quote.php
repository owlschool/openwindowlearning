<?php

global $global_theme_options;

?>
    			<!-- Blog Post -->
            	<?php if( $global_theme_options['blog_layout'] == "Normal" ) { ?>
    			<div id="post-<?php the_ID(); ?>" <?php post_class("blog-post"); ?>>
    			<?php } else { ?>
    			<div id="post-<?php the_ID(); ?>" <?php post_class("blog-post masonry-quote"); ?>>
    			<?php } ?>
                
                	<?php if( $global_theme_options['blog_layout'] == "Normal" ) { ?>
                    <a href="<?php the_permalink(); ?>" class="external">
            		<div class="type-date">                    
                    	<div class="blog-type"><img src="<?php echo get_template_directory_uri()."/images/blog-quote.png"; ?>" alt=""></div>
                        <div class="blog-date"><h5><?php  echo date_i18n( __('d'), get_post_time() );  ?></h5><h5><?php  echo date_i18n( __('M'), get_post_time() );  ?></h5></div>                    
                    </div>
                    </a>
                    <?php } ?>
                	
                    
                    <!-- Post Content -->
                	<div class="post-content">
                            
                        <a href="<?php the_permalink(); ?>" class="external">
                    	<div class="post-quote">
                        	<?php echo get_post_meta($post->ID, 'newave_blog_post_quote', true); ?>
                        </div>
                    </a>

                    <?php if( $global_theme_options['blog_layout'] == "Normal" ) { ?>
                    <hr>
                    <?php } ?>

                    </div>
                	<!--/Post Content -->
                    
                </div>
            	<!-- Blog Post -->


