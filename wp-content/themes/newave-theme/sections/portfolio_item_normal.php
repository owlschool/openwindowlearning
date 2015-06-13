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
        
        <?php the_content(); ?>

    </div>    
    <!--/Container-->