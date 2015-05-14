
        <?php
            global $global_theme_options;

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

            $max_items  = 10000; //theoretically infinite
            if( isset( $global_theme_options['max_portfolio_items'] ) && ( trim($global_theme_options['max_portfolio_items']) != '') ){

                $max_items = trim($global_theme_options['max_portfolio_items']);
                if( $max_items <= 0 )
                    $max_items = 1;
            }
            $items = 0;
					
            while($gallery->have_posts()){
            	
            	$gallery->the_post();

                $items++;
                if( $items > $max_items ){
                    continue;
                }

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