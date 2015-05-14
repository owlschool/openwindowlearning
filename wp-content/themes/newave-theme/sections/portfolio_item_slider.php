<?php

$item_categories 	= '';
$item_cats = get_the_terms($post->ID, 'portfolio_category');
if($item_cats){

    foreach($item_cats as $item_cat) {
        $item_categories 	.= $item_cat->name . ' / ';
    }
    $item_categories = rtrim($item_categories, ' / ');

}
	
?>

    <!-- Project Close Button -->
    <ul id="project-page-button" class="clearfix">
        <li><a id="project_close" class="icon-awesome" href="#" title="<?php _e('Close Project', THEME_LANGUAGE_DOMAIN); ?>"><i class="fa fa-times-circle-o"></i></a></li>
    </ul>
    <!--/Project Close Button -->

	<!-- Container-->    
    <div class="container small-width no-padding ">
    
    
    
        <!-- Section Title -->
        <div class="project-section-title">
            <h1><?php the_title(); ?></h1>    
            <p><?php echo $item_categories; ?></p>
        </div>				
        <!--/Section Title -->
        
        
        
        <?php if( has_post_thumbnail() ){ 
			$full_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
        ?>
        <!-- Project Slider -->
        <div class="project-image-slider">
            <ul class="project-slider">
                <li><img src="<?php echo $full_image[0]; ?>" alt="" /></li>
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
                		<li><img src="<?php echo $image_url; ?>" alt="" /></li>
                		<?php 
                		}
                    } 
                	?>
            </ul>
        </div>
        <!--/Project Slider -->
        <?php } ?>
        
        
        <?php the_content(); ?>
        

    </div>    
    <!--/Container-->