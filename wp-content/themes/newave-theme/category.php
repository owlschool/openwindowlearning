<?php

get_header();

global $global_theme_options;

$global_theme_options['blog_layout'] = "Normal";

get_template_part('sections/menu_section');

?>        
    
    <!-- Blog Header -->
    <div id="blog-header">
    
    	<?php echo '<h1>' . single_cat_title( '', false ) . '</h1>'; ?>
    
    </div>
    <!--/Blog Header -->
    
    
    
    
    
    <!-- Blog Content -->
    <div id="blog" class="clearfix">

        <!-- Container -->
    	<div class="container">
        
        
            <!-- Blog Posts Content -->
    		<div class="blog-posts-content">

<?php
    // the loop
    while(have_posts()){ 

    	the_post();
    	
    	get_template_part( 'blog-post-format/blog-post', get_post_format() ); 
 
	} 
?>            	
    		
			</div>
            <!--/Blog Posts Content -->    		
    		

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
    
    		    
<?php

    newave_pagination();
	
    wp_reset_query();	

    get_footer();

?>