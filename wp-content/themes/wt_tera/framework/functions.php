<?php

/**
 * Tell WordPress to run wellthemes_theme_setup() when the 'after_setup_theme' hook is run.
 */
 
add_action( 'after_setup_theme', 'wellthemes_theme_setup' );

if ( ! function_exists( 'wellthemes_theme_setup' ) ):

function wellthemes_theme_setup() {

	/**
	 * Load up our required theme files.
	 */
	require( get_template_directory() . '/framework/settings/theme-options.php' );
	require( get_template_directory() . '/framework/settings/option-functions.php' );
	
	require( get_template_directory() . '/framework/meta/meta_post.php' );
	require( get_template_directory() . '/framework/meta/meta_category.php' );
	require( get_template_directory() . '/framework/meta/meta_functions.php' );
	require( get_template_directory() . '/framework/rating/rating.php' );
	/**
	 * Load our theme widgets
	 */
	require( get_template_directory() . '/framework/widgets/widget_adsingle.php' );
	require( get_template_directory() . '/framework/widgets/widget_flickr.php' );
	require( get_template_directory() . '/framework/widgets/widget_recent_posts.php' );
	require( get_template_directory() . '/framework/widgets/widget_video.php' );
	require( get_template_directory() . '/framework/widgets/widget_facebook.php' );
	require( get_template_directory() . '/framework/widgets/widget_slider.php' );
	require( get_template_directory() . '/framework/widgets/widget_aboutus.php' );
	require( get_template_directory() . '/framework/widgets/widget_pinterest.php' );
	require( get_template_directory() . '/framework/widgets/widget_recent_comments.php' );
	require( get_template_directory() . '/framework/widgets/widget_google.php' );
	require( get_template_directory() . '/framework/widgets/widget_subscribe.php' );
	require( get_template_directory() . '/framework/widgets/widget_popular_categories.php' );
	require( get_template_directory() . '/framework/widgets/widget_subscribers_count.php' );
	require( get_template_directory() . '/framework/widgets/widget_latest_tweets.php' );
	require( get_template_directory() . '/framework/widgets/widget_tabs.php' );
	require( get_template_directory() . '/framework/widgets/widget_quick_links.php' );
	require( get_template_directory() . '/framework/widgets/widget_state_information.php' );
	
	/* Add translation support.
	 * Translations can be added to the /languages/ directory.
	 */
	load_theme_textdomain( 'wellthemes', get_template_directory() . '/languages' );
	
	/**
	 * Set the content width based on the theme's design and stylesheet.
	 */
	if ( ! isset( $content_width ) )
		$content_width = 600;
		
	/** 
	 * Add default posts and comments RSS feed links to <head>.
	 */
	add_theme_support( 'automatic-feed-links' );
	
	/**
	 * This theme styles the visual editor with editor-style.css to match the theme style.
	 */
	add_editor_style();
	
	/**
	 * Register menus
	 *
	 */
	register_nav_menus( array(
		'top-menu' => __( 'Top Menu', 'wellthemes' ),
		'primary-menu' => __( 'Primary Menu', 'wellthemes' )	
	) );
	
	/**
	 * Add support for the featured images (also known as post thumbnails).
	 */
	if ( function_exists( 'add_theme_support' ) ) { 
		add_theme_support( 'post-thumbnails' );
	}
	
	/**
	 * Add custom image sizes
	 */
	//add_image_size( 'wt550_300', 550, 300, true );		//slider
	
	//
	//add_image_size( 'wt80_60', 80, 60, true );			//thumbnails
	
	add_image_size( 'wt720_415', 720, 415, true );		//slider
	add_image_size( 'wt375_205', 375, 205, true );		//archives
	add_image_size( 'wt340_230', 340, 230, true );		//archives
	add_image_size( 'wt250_160', 250, 160, true );		//carousel
	add_image_size( 'wt75_75', 75, 75, true );			//thumbnails	

}
endif; // wellthemes_theme_setup

/**
 * A safe way of adding JavaScripts to a WordPress generated page.
 */

if (!is_admin()){
    add_action('wp_enqueue_scripts', 'wellthemes_js');
}

if (!function_exists('wellthemes_js')) {

    function wellthemes_js() {
		wp_enqueue_script('wt_hoverIntent', get_template_directory_uri().'/js/hoverIntent.js',array('jquery'),'', true);
		wp_enqueue_script('wt_superfish', get_template_directory_uri().'/js/superfish.js',array('hoverIntent'),'', true);
		wp_enqueue_script('wt_slider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array('jquery'),'', true); 
		wp_enqueue_script('wt_lightbox', get_template_directory_uri() . '/js/lightbox.min.js', array('jquery'),'', true); 		
		wp_enqueue_script('wt_jflickrfeed', get_template_directory_uri() . '/js/jflickrfeed.min.js', array('jquery'),'', true); 
		wp_enqueue_script('wt_mobilemenu', get_template_directory_uri() . '/js/jquery.mobilemenu.js', array('jquery'),'', true); 
		wp_enqueue_script('wt_touchSwipe', get_template_directory_uri() . '/js/jquery.touchSwipe.min.js', array('jquery'),'', true); 
		wp_enqueue_script('wt_mousewheel', get_template_directory_uri() . '/js/jquery.mousewheel.min.js', array('jquery'),'', true);	
		wp_enqueue_script('wt_custom', get_template_directory_uri() . '/js/custom.js', array('jquery'),'', true);	
		wp_enqueue_script('wt_ticker', get_template_directory_uri() . '/js/jquery.ticker.js', array('jquery'), '', true); 		
    }
	
}

/**
 * Enqueues styles for front-end.
 *
 */ 
if (!function_exists('wellthemes_css')) {
	function wellthemes_css() {
		wp_enqueue_style( 'wt-style', get_stylesheet_uri() );
		wp_enqueue_style( 'wt-font-awesome', get_template_directory_uri().'/css/font-awesome/css/font-awesome.min.css' );		
	}
}
add_action( 'wp_enqueue_scripts', 'wellthemes_css' );


/**
 * Register our sidebars and widgetized areas.
 *
 */
 
if ( function_exists('register_sidebar') ) {
			
	register_sidebar( array(
		'name' => __( 'Right Sidebar', 'wellthemes' ),
		'id' => 'sidebar-2',
		'description' => __( 'Right sidebar area', 'wellthemes' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<div class="widget-title"><div class="icon"></div><h4>',
		'after_title' => '</h4></div>',
	) );
}

/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own wellthemes_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 */
if ( ! function_exists( 'wellthemes_comment' ) ) :
function wellthemes_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	global $post;
	
	switch ( $comment->comment_type ) :
		case '' :
		
		if($comment->user_id == $post->post_author) {
			$author_text = '<span class="author-comment main-color-bg">Author</span>';
		} else {
			$author_text = '';
		}
		
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>">
		
			<div class="author-avatar">
				<a href="<?php comment_author_url()?>"><?php echo get_avatar( $comment, 60 ); ?></a>
			</div>			
		
			<div class="comment-right">
				
				<div class="comment-header">
						<h5><?php printf( __( '%s', 'wellthemes' ), sprintf( '<cite class="fn cufon">%s</cite>', get_comment_author_link() ) ); ?></h5>
						<?php echo $author_text; ?>
                                                <?php if(get_post_type() == 'ticket') {
                                                    $attach_count = get_comment_meta( $comment->comment_ID, 'attachment_count', true);	
                                                    $attach_num = 1;
                                                    if ($attach_count > 0) {
                                                        echo '<span class="sep">-</span>';
                                                        while ($attach_count > 0) {
                                                            $attach_url = get_comment_meta(comment_ID(), 'attachment_' . $attach_num, true);
                                                            echo '<a href="' . $attach_url . '">Attachment ' . $attach_num . '</a>';
                                                            $attach_count--;
                                                            $attach_num++;
                                                        }
                                                     }
                                                 } ?>                                                     
				</div>
					
				<div class="comment-meta">					
					
					<span class="comment-time">
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<?php
							/* translators: 1: date, 2: time */
							printf( __( '%1$s at %2$s', 'wellthemes' ), get_comment_date(),  get_comment_time() ); ?></a>
					</span>
					<span class="sep">-</span>
					<span class="reply">
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'wellthemes' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</span>
									
					<?php edit_comment_link( __( '[ Edit ]', 'wellthemes' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- /comment-meta -->
			
				<div class="comment-text">
					<?php comment_text(); ?>
				</div>
		
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<p class="moderation"><?php _e( 'Your comment is awaiting moderation.', 'wellthemes' ); ?></p>
				<?php endif; ?>

				<!-- /reply -->
		
			</div><!-- /comment-right -->
		
		</article><!-- /comment  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'wellthemes' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '[ Edit ]', 'wellthemes' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php	
			break;
	endswitch;
}
endif;	//ends check for wellthemes_comment()


/**
 * Pagination for archive, taxonomy, category, tag and search results pages
 *
 * @global $wp_query http://codex.wordpress.org/Class_Reference/WP_Query
 * @return Prints the HTML for the pagination if a template is $paged
 */
if ( ! function_exists( 'wt_pagination' ) ) :
function wt_pagination() {
	global $wp_query;
 
	$big = 999999999; // This needs to be an unlikely integer
 
	// For more options and info view the docs for paginate_links()
	// http://codex.wordpress.org/Function_Reference/paginate_links
	$paginate_links = paginate_links( array(
		'base' => str_replace( $big, '%#%', get_pagenum_link($big) ),
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages,
		'mid_size' => 5
	) );
 
	// Display the pagination if more than one page is found
	if ( $paginate_links ) {
		echo '<div class="pagination">';
		echo $paginate_links;
		echo '</div><!--// end .pagination -->';
	}
}
endif; // ends check for wt_pagination()


if ( ! function_exists( 'wellthemes_main_menu_fallback' ) ) :
	
	function wellthemes_main_menu_fallback() { ?>
		<ul class="menu">
			<?php
				wp_list_categories(array(
					'number' => 5,
					'exclude' => '1',		//exclude uncategorized posts
					'title_li' => '',
					'orderby' => 'count',
					'order' => 'DESC'  
				));
			?>  
		</ul>
    <?php
	}

endif; // ends check for wellthemes_main_menu_fallback()


if ( ! function_exists( 'wellthemes_top_menu_fallback' ) ) :
	
	function wellthemes_top_menu_fallback() { ?>
		
    <?php
	}

endif; // ends check for wellthemes_top_menu_fallback()



function wt_get_first_cat(){
	$category = get_the_category();
	
	if ($category){		
	
		$output = "";
		if (isset($category[0]->term_id)){
			
			$cat1_id = $category[0]->term_id;			
			$wt_category_meta = get_option( "wt_category_meta_color_$cat1_id" );						
			$output .= '<span class="entry-cat-bg main-color-bg cat'.$cat1_id.'-bg">';			
			$output .= '<a href="' . get_category_link( $category[0]->term_id ) . '">' . $category[0]->name.'</a>';
			$output .= '</span>';
		}
		
		echo $output;
				
	}
}


function wt_get_cats(){
	$category = get_the_category();
	
	if ($category){		
	
		$output = "";
		if (isset($category[0]->term_id)){
			
			$cat1_id = $category[0]->term_id;			
			$wt_category_meta = get_option( "wt_category_meta_color_$cat1_id" );						
			$output .= '<span class="entry-cat-bg main-color-bg cat'.$cat1_id.'-bg">';			
			$output .= '<a href="' . get_category_link( $category[0]->term_id ) . '">' . $category[0]->name.'</a>';
			$output .= '</span>';
		}
		
		if (isset($category[1]->term_id)){
			
			$cat2_id = $category[1]->term_id;			
			$wt_category_meta = get_option( "wt_category_meta_color_$cat2_id" );						
			$output .= '<span class="entry-cat-bg main-color-bg cat'.$cat2_id.'-bg">';			
			$output .= '<a href="' . get_category_link( $category[1]->term_id ) . '">' . $category[1]->name.'</a>';	
			$output .= '</span>';
			
		}
		
		echo $output;
				
	}
}

function set_category_styles(){
	$categories = get_categories();
		$cat_css = "";
		foreach($categories as $category) {
			
			$cat_id = $category->term_id;
			$wt_category_meta = get_option( "wt_category_meta_color_$cat_id" );
			
			if (isset($wt_category_meta['wt_cat_meta_color'])){
				$cat_color = $wt_category_meta['wt_cat_meta_color'];
				$cat_css .=".cat".$cat_id."-bg{background:".$cat_color."} ";
			}		
				
		}
				
		wp_add_inline_style('wt-style', $cat_css);	
	
}
add_action( 'wp_enqueue_scripts', 'set_category_styles',11 );

