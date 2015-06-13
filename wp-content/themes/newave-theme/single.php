<?php

get_header();

global $global_theme_options;

get_template_part('sections/menu_section');

if ( have_posts() ) {

    the_post();

?>
    
    
    <!-- Blog Header -->
    <div id="blog-header">
    </div>
    <!--/Blog Header -->


    <!-- Blog Content -->
    <div id="blog" class="clearfix">
    	
        <!-- Container -->
    	<div class="container">
        
        
            <!-- Blog Posts Content -->
    		<div class="blog-posts-content">

            	<!-- Blog Post -->
            	<div id="post-<?php the_ID(); ?>" <?php post_class("blog-post"); ?>>
            
            	<?php get_template_part( 'blog-post-format/single', get_post_format() );  ?>

                        <?php if( $global_theme_options['blog_post_title'] ) { ?>
            			<h3 class="blog-title"><?php the_title(); ?></h3>
                        <?php } ?>

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
                        
                        <div class="blog-border"></div>
                        
                        
                        <!-- Blog Content -->
                        <div class="blog-content">
                        
                            <?php the_content(); ?>
                            
                            <div class="page-links">
							<?php
                                
								wp_link_pages();

							?>
							</div>
                            
                        </div>
                        <!--/Blog Content -->
                        
                        
                        <hr>
                                                
						<?php comments_template(); ?>                        
                        
                    </div>
                	<!--/Post Content -->
            	
            	
            	</div>
            	<!-- Blog Post -->
            
    		</div>
            <!-- Blog Posts Content -->
            
            
            
            
            <?php if( is_active_sidebar( 'blog-sidebar' ) ){ ?>
                    <!-- Sidebar -->
                    <div id="sidebar">
                
                        <?php dynamic_sidebar( 'blog-sidebar' ); ?>
                
                    </div>
                    <!--/Sidebar -->
            <?php } ?>  
            
            
        </div>
    	<!--/Container -->
        
    </div>
    <!--/Blog Content -->
    
    
    
    
    
    <!-- Blog Navigation -->
    <div id="blog-footer" class="clearfix">
    	
        <!--/Container -->
        <div class="container no-padding">
        
        	<ul class="inner-navigation masonry clearfix">

                <?php previous_post_link( '<li class="blog-pagination-prev">%link</li>', '<span><img src="' . get_template_directory_uri() . '/images/prev_article.png" alt=""></span>'); ?>
                <?php next_post_link( '<li class="blog-pagination-next">%link</li>', '<span><img src="' . get_template_directory_uri() . '/images/next_article.png" alt=""></span>'); ?>
            	
            </ul>
        
    	</div>
    	<!--/Container -->
    
    </div>
    <!--/Blog Navigation -->
    
    
    
<?php

} // if have posts
	
wp_reset_query();

get_footer();

?>