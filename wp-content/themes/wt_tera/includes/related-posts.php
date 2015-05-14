<?php
$tags = wp_get_post_tags($post->ID);
$number = 2;
$args = array();
$args2 = array();
if ($tags) {
    $tag_ids = array();
    
	foreach($tags as $tag){
		$tag_ids[] = $tag->term_id;
	}

    $args = array(
        'tag__in' => $tag_ids,
        'post__not_in' => array($post->ID),
        'showposts'=> $number,
    ); 
	
    if( count($args) < $number ) {
        $n = $number - count($args);  //to get posts based on the category
        if ($categories) {
			$category_ids = array();
			foreach($categories as $cat) $category_ids[] = $cat->term_id;

			$args2 = array(     //this is the args array for category based posts
				'category__in' => $category_ids,
				'post__not_in' => array($post->ID),
				'showposts'=> $n,
            );      
		}
    }
    $args = array_merge( $args, $args2 );
} else {
    $categories = get_the_category($post->ID);  
    if ($categories) {
        $category_ids = array();
        foreach($categories as $cat) $category_ids[] = $cat->term_id;

        $args = array(
            'category__in' => $category_ids,
            'post__not_in' => array($post->ID),
            'showposts'=> $number,
        );      
    }
}

if($args){
	$query = new WP_Query( $args ); ?>
	<?php if ( $query -> have_posts() ) : ?>
		<div class="related-posts">
			
			<h3><?php _e('Related Posts', 'wellthemes'); ?></h3>			
			<div class="post-list">
				<?php $i = 0; ?>
				<?php while ( $query -> have_posts() ) : $query -> the_post(); ?>
					<?php								
						$post_class ="";
						if ( $i % 2 == 1 ){
							$post_class =" col-last";
						}					
					?>								
					<div class="one-half<?php echo $post_class; ?>">
						<?php get_template_part( 'content', 'excerpt' ); ?>
					</div>
					<?php $i++; ?>					
				<?php endwhile; ?>
			</div>
			
		</div>		
	<?php endif; ?>	
	<?php wp_reset_query();	
}

?>