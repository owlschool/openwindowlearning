					<div class="type-date">                    
                    	<div class="blog-type"><img src="<?php echo get_template_directory_uri()."/images/blog-gallery.png"; ?>" alt=""></div>
                        <div class="blog-date"><h5><?php echo date_i18n( __('d'), get_post_time() ); ?></h5><h5><?php echo date_i18n( __('M'), get_post_time() ); ?></h5></div>                    
                    </div>
                	
                    
                    <!-- Post Content -->
                	<div class="post-content">

                    <?php if( has_post_thumbnail() ){ ?>
                    <div class="post-slider">                        
                    	<ul class="blog-slider">
                    	<?php
                    	
                    		if( has_post_thumbnail() ){
                    		
                    			$full_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                    			
                    			echo '<li><img src="'. $full_image[0] . '" alt=""></li>';
                    		}
                    	
                   			$i = 2;
                            $images_no = 5;
                            if( function_exists( 'get_blog_post_featured_images_no' ) ){
                                $images_no = get_blog_post_featured_images_no();
                            }
				    		while( $i <= $images_no ){
					
                        		$image_url = "";
                            	if (class_exists('MultiPostThumbnails')) { 
                            		$image_url = MultiPostThumbnails::get_post_thumbnail_url(get_post_type(), 'featured-image-'.$i); 
                            	}
                                        
								if( $image_url != "" ){
									
									echo '<li><img src="'. $image_url . '" alt=""></li>';
								}
								
								$i++;
				    		}
						
                    	?>
                    	</ul>
                    </div>
                    <?php } ?>