<?php
/*
Template name: Portfolio Template
*/

get_header();

get_template_part('sections/menu_section');

if( have_posts() ){

    the_post();

    // retrieve the background color for this section. A section has gray background (#f5f5f5) by default, therefore
    // we care only if the section has white background
    $section_style = '';
    if( get_post_meta(get_the_ID(), 'newave_page_default_background', true) == 'white' ){

        $section_style = 'style="background-color:#FFF;"';
    }

    $container_type = get_post_meta(get_the_ID(), 'newave_page_default_container', true);
    if( $container_type == 'normal' ){

        $container_type = '';
    }
    else if( $container_type == 'small' ){

        $container_type = 'small-width';
    }
    else if( $container_type == 'full' ){

        $container_type = 'full-width';
    }
    else {

        $container_type = '';
    }

?>

	<!-- Portfolio -->
	<section id="<?php echo $post->post_name; ?>" class="content" <?php echo $section_style; ?> >
    		
        <!-- Container -->
		<div class="container portfolio-title">

            <?php if( get_post_meta( $post->ID, "newave_show_page_title", true ) == "yes" ){ ?>
            <!-- Section Title -->
            <div class="section-title">
                <h1><?php the_title(); ?></h1>
                <span class="border"></span>
                <p><?php echo get_post_meta(get_the_ID(), 'newave_page_subtitle', true); ?></p>
            </div>				
			<!--/Section Title -->
            <?php } ?>
            
        </div> 
        <!-- Container -->  
          
          
          
          
          
        <div class="portfolio-top"></div>          
        
              
        <!-- Portfolio Plus Filters -->
        <div class="portfolio">
        
            
     
       <?php
       global $global_theme_options;
       $style_filters = '';
       if( isset( $global_theme_options['show_portfolio_filter'] ) && ($global_theme_options['show_portfolio_filter'] != 1) ){

           $style_filters = 'style="display: none"';
       }

       $portfolio_columns = 4;
       if( isset($global_theme_options['portfolio_columns']) && $global_theme_options['portfolio_columns'] ){

           $portfolio_columns = $global_theme_options['portfolio_columns'];
       }
       wp_localize_script( 'scriptsjs',
                           'PortfolioColumnsOptions',
                            array( "columns_no" => $portfolio_columns ) );
       ?>
       <!-- Portfolio Filters -->   
       <div  id="filters" class="sixteen columns" <?php echo $style_filters; ?>>
    
          <ul class="clearfix">
          
            <li><a id="all" href="#" data-filter="*" class="active"><h5>All</h5></a></li>
            <?php
             
            	$portfolio_category = get_terms('portfolio_category', array( 'hide_empty' => 0 ));
            
            	if($portfolio_category){
            		
            		foreach($portfolio_category as $portfolio_cat){
            ?>
            <li><a href="#" data-filter=".<?php echo $portfolio_cat->slug; ?>"><h5><?php echo  $portfolio_cat->name; ?></h5></a></li>         	
            <?php                      
                    }
				}
            ?>	
            
          </ul>
        </div>
        <!--/Portfolio Filters -->
    
       
             
        <!-- Portfolio Wrap -->  
        <div id="portfolio-wrap">
        
        
        <?php
        	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(
                    		'post_type' => 'newave_portfolio',
							'paged' => $paged,
							'posts_per_page' => 1000,
                         );
            
            $pcats = get_post_meta(get_the_ID(), 'newave_portfolio_category', true);
            if( $pcats && $pcats[0] == 0 ) {
				unset($pcats[0]);
            }
            
            if( $pcats ){
				$args['tax_query'][] = array(
                			       			  	'taxonomy' => 'portfolio_category',
						  						'field' => 'ID',
						  						'terms' => $pcats
											);
            }
            
            $gallery = new WP_Query($args);
            
					
            while($gallery->have_posts()){
            	
            	$gallery->the_post();
						
                 if( has_post_thumbnail() ){
	
                 	$item_classes 		= '';
                 	$item_categories 	= '';
					$item_cats = get_the_terms($post->ID, 'portfolio_category');
					if($item_cats){

                        foreach($item_cats as $item_cat) {
                            $item_classes 		.= $item_cat->slug . ' ';
                            $item_categories 	.= $item_cat->name . ' / ';
                        }
                        $item_categories = rtrim($item_categories, ' / ');

					}
							
				$full_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
				$thumbnail  = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'portfolio-thumbnail');
        ?>
        
            <!-- Portfolio Item -->                 
            <div class="portfolio-item one-four <?php echo $item_classes; ?>">
            
            <div class="portfolio-image" style="background-image: url('<?php echo $full_image[0]; ?>')">
            </div>
            
            <?php
				$post_layout = get_post_meta(get_the_ID(), 'newave_portfolio_item_layout', true);
				
				if( $post_layout == "image_popup" ){ ?>
            <a title="<?php the_title(); ?>" rel="prettyPhoto[<?php echo $post->ID; ?>]" href="<?php echo $full_image[0]; ?>">

            <?php } ?>
            <?php if( ($post_layout == "external") || ($post_layout == "external_slider") ){ ?>
                <a href="<?php the_permalink(); ?>" class="external">
            <?php } ?>
            <div class="project-overlay">
                <?php
                if( ($post_layout == "normal") || ($post_layout == "slider") || ($post_layout == "full_screen") ){
                    ?>
                    <div class="open-project-link">
                        <a class="open-project" href="<?php the_permalink(); ?>" title="Open Project"></a>
                    </div>
                <?php
                }
                ?>
                <div class="project-info">
                	<div class="zoom-icon"></div>
                    <h4 class="project-name"><?php the_title(); ?></h4>
                    <p class="project-categories"><?php echo $item_categories; ?></p>	
                </div>
            </div>
            <?php if( $post_layout == "image_popup" ){ ?>
            </a>

            <?php

            $i = 2;
            $images_no = 5;
            if( function_exists( 'get_portfolio_featured_images_no' ) ){
                $images_no = get_portfolio_featured_images_no();
            }

            while( $i <= $images_no ) {

                $image_url = "";
                if (class_exists('MultiPostThumbnails')) {

                    $image_url = MultiPostThumbnails::get_post_thumbnail_url(get_post_type(), 'featured-image-'.$i);
                }

                $i++;

                if( $image_url != "" ) {
            ?>
                <a title="<?php the_title(); ?>" style="display:none" rel="prettyPhoto[<?php echo $post->ID; ?>]" href="<?php echo $image_url; ?>"></a>
            <?php

                } // if image url is not empty

            } // while images

            ?>

            <?php } ?>
            <?php if( ($post_layout == "external") || ($post_layout == "external_slider") ){ ?>
            </a>
            <?php } ?>
              
            </div>
            <!--/Portfolio Item -->       
        
       <?php
                 } // if post has thumbnail
                 
            } // while gallery has posts 
       ?>           
                 
       </div>
       <!--/Portfolio Wrap -->
            
        </div>
        <!--/Portfolio Plus Filters -->
        
        
        
        
        
        <div class="portfolio-bottom"></div>
        
        
        
        
        
        <!-- Project Page Holder-->
        <div id="project-page-holder">
            
            <div class="clear"></div>
            <div id="project-page-data"></div>
        
        </div>
        <!--/Project Page Holder-->


        <!-- Container -->
        <div class="container <?php echo $container_type; ?>">

           <?php

                wp_reset_postdata();

                the_content();

           ?>

        </div>
        <!-- Container -->
    
             
	</section>	
	<!--/Portfolio -->

<?php

}

get_footer();

?>