<?php
/**
 * The template for displaying the featured carousel on homepage.
 * Gets the category for the posts from the theme options. 
 * If no category is selected, displays the latest posts.
 *
 * @package  WellThemes
 * @file     feat-list.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 * 
 **/
?>
<?php
	$carousel_title = get_post_meta($post->ID, 'wt_meta_carousel_title', true);
	$cat_id = get_post_meta($post->ID, 'wt_meta_carousel_cat', true);	
	
	$args = array(
		'cat' => $cat_id,
		'post_status' => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page' => 52,
                'order' => 'ASC',
                'orderby' => 'title'
	);
		
?>
<div id="feat-carousel" class="carousel-section clearfix">
	<div class="cat-title">
		<span class="title-sep main-color-bg"></span>
		<h4><?php echo $carousel_title; ?></h4>	
	</div>
        <script type='text/javascript'>
            function onstatesubmit() {
                var e = document.getElementById("state_to_go");
                var strState = e.options[e.selectedIndex].value;
                window.location.href = strState;
            }
        </script>
	<div class="front-feature-lists"  style="display: inline-block; width: 100%;">
             <table>
                <tr>
                <td style="vertical-align:middle; text-align: center; width:70%; height: 100%; border-width:0px;">
		<select name="state_to_go" id="state_to_go">
			<?php $query = new WP_Query( $args ); ?>
			<?php if ( $query -> have_posts() ) : ?>
				<?php while ( $query -> have_posts() ) : $query -> the_post(); ?>
					<option value="<?php the_permalink(); ?>"> <?php the_title(); ?> </option>
				<?php endwhile; ?>
			<?php endif;?>
			<?php wp_reset_query();?>				
		</select>
                <button class="main-color-bg button" onclick="onstatesubmit()">Get State Info</button>
               </td></tr>	
              </table>
	</div>
</div>