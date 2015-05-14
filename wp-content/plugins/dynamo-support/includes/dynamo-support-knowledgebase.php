<?php
/*
* Dynamo Support Knowledgebase 
*/
/*
* Create Knowledgebase Post Type
*/

add_action('init', 'dynamo_support_kb_init');
function dynamo_support_kb_init() {
	global $plugin_folder;
			
	$labels = array(
		'name' => _x('Articles', 'post type general name'),
		'singular_name' => _x('Article', 'post type singular name'),
		'add_new' => _x('Add New', 'article'),
		'add_new_item' => __('Add New Article'),
		'edit_item' => __('Edit Article'),
		'new_item' => __('New Article'),
		'view_item' => __('View Article'),
		'search_items' => __('Search Articles'),
		'not_found' =>  __('No articles found'),
		'not_found_in_trash' => __('No articles found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => 'Articles'

	  );
	  $args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'rewrite' => true,
		'capability_type' => 'article',
		'capabilities' => array(
			'read' => 'read_articles',
			'publish_posts' => 'publish_articles',
			'edit_posts' => 'edit_articles',
			'edit_others_posts' => 'edit_others_articles',
			'delete_posts' => 'delete_articles',
			'delete_others_posts' => 'delete_others_articles',
			'read_private_posts' => 'read_private_articles',
			'edit_post' => 'edit_article',
			'delete_post' => 'delete_article',
			'read_post' => 'read_article',
		),
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => null,
		'description' => 'Knowledge base Articles for a support system.',
		'exclude_from_search' => true,
		'taxonomies' => array('knowledge-base-topic'),
		'menu_icon' => $plugin_folder.'img/article-icon.png',
		'supports' => array('title','editor','custom-fields','comments')
	  ); 
	  register_post_type('knowledgebase',$args);
}

/*
* Add In Labels and Messages for Knowledgebase post type
*/
add_filter('post_updated_messages', 'dynamo_support_updated_messages_kb');
function dynamo_support_updated_messages_kb( $messages ) {
  global $post, $post_ID;

  $messages['knowledgebase'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Article updated. <a href="%s">View article</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Ticket updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Article restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Article published. <a href="%s">View ticket</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Article saved.'),
    8 => sprintf( __('Article submitted. <a target="_blank" href="%s">Preview article</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Article scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview article</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Article draft updated. <a target="_blank" href="%s">Preview article</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
} 
//Taxonomy
function dynamo_support_kb_tax() 
{
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name' => _x( 'Topics', 'taxonomy general name' ),
    'singular_name' => _x( 'Topic', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Topics' ),
    'all_items' => __( 'All Topics' ),
    'parent_item' => __( 'Parent Topic' ),
    'parent_item_colon' => __( 'Parent Topic:' ),
    'edit_item' => __( 'Edit Topic' ), 
    'update_item' => __( 'Update Topic' ),
    'add_new_item' => __( 'Add New Topic' ),
    'new_item_name' => __( 'New Topic Name' ),
    'menu_name' => __( 'Topics' ),
  ); 	

  register_taxonomy('knowledge-base-topic',array('knowledgebase'), array(
    'hierarchical' => true,
	'public' => true,
    'labels' => $labels,
    'show_ui' => true,
    'rewrite' => array( 'slug' => 'knowledge-base-topic', 'with-front' => false, 'hierarchical' => true ),
  ));
}
add_action( 'init', 'dynamo_support_kb_tax', 0 );


/*
* KB View Page Set Up Columns
*/
add_action("manage_posts_custom_column", "dynamo_support_custom_kb_columns");
add_filter("manage_edit-knowledgebase_columns", "dynamo_support_kb_columns");
 
function dynamo_support_kb_columns($columns) //this function display the columns headings
{
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => "Title",
		"comments" => "<img alt='Comments' src='".get_bloginfo('wpurl')."/wp-admin/images/comment-grey-bubble.png'/>",
		"views" => "Views",
		"votes" => "Votes",
		"date" => "Date"
	);
	return $columns;
}
/*
* KB View Page Columns Display Function
*/
function dynamo_support_custom_kb_columns($column)
{
	global $post, $support_options;
	if ("views" == $column) {  
		$views = get_post_meta($post->ID,'kb_views',true);
		if($views == '') {
			$views = 0;
		}
		echo $views;
	} else if('votes' == $column) {
		$yes = get_post_meta($post->ID,'kb_vote_yes',true);
		$no = get_post_meta($post->ID,'kb_vote_no',true);
		if($yes == '') { $yes = 0; }
		if($no == '') { $no = 0; }
		echo '<span class="thumbs-up"></span> <span class="yes">'.$yes.'</span>&nbsp;&nbsp;&nbsp;<span class="thumbs-down"></span> <span class="no">'.$no.'</span> ';
	}  	
}
add_filter( 'manage_edit-knowledgebase_sortable_columns', 'sd_kb_sortable_columns');
function sd_kb_sortable_columns($columns) {
	$columns['views'] = 'views';
	$columns['votes'] = 'votes';
	return $columns;
}

function sd_archive_display() {
global $wp_query;
	if(is_tax('knowledge-base-topic')) {	
		echo 'yes';
	
		$args = array_merge( $wp_query->query, array( 'post_type' => array('knowledgebase') ) );
		query_posts( $args );
		
	}
}
add_action('wp_head','sd_archive_display');

function sd_kb_request($query_string) {
	
	
	if(isset($query_string['knowledge-base-topic']) && isset($query_string['paged'])){
		$query_string['post_type'] = get_post_types($args = array(
			'public'   => true,
			'_builtin' => false
		));
		array_push($query_string['post_type'],'post');
	}  
	
	return $query_string;
}
add_filter('request', 'sd_kb_request');

//Add KB Features Meta Box
add_action( 'add_meta_boxes', 'sd_kb_metabox' );

function sd_kb_metabox() {
	 add_meta_box( 
        'sd_featured_kb',
        __( 'Featured Knowledge Base Article', 'support-synamo' ),
        'sd_kb_inner_custom_box',
        'knowledgebase',
		'side',
		'high'
    );
}
/*
* Post Meta Box
*/
function sd_kb_inner_custom_box($post) {
echo '<label><strong>Set As Featured Knowledge Base Article:</strong></label><br/>';
echo '<input type="radio" name="kb_features" value="1"';
	if(get_post_meta($post->ID,'kb_features',true) == '1') {
		echo 'checked="checked"';
	}
echo ' /> Feature';

echo '&nbsp;&nbsp;&nbsp;<input type="radio" name="kb_features" value="no"';
	if(!get_post_meta($post->ID,'kb_features',true)) {
		echo 'checked="checked"';
	}
echo ' /> Don\'t Feature';

}
add_action('save_post','sd_kb_feature_save');
/*
* Save Post Meta Box 
*/
function sd_kb_feature_save($post) {
global $post;
	if($post->post_type == 'knowledgebase') {
		if($_POST['kb_features'] == 'no') {
			delete_post_meta($post->ID,'kb_features');
		} else {
			update_post_meta($post->ID,'kb_features',$_POST['kb_features']);
		}
	}

}
/*
* Update KB Views
*/
function sd_kb_views() {
global $post;
	if(is_single()) { //Is single post and is kb post type
		if($post->post_type == 'knowledgebase') {	
			$views = get_post_meta($post->ID,'kb_views',true);
			if($views == '') {
				$views = 0;
			}
			$views++;
			update_post_meta($post->ID,'kb_views',$views);
		}
	}
}
add_action('wp','sd_kb_views');

//add_filter( 'pre_get_posts' , 'sd_include_custom_post_types' );
function sd_include_custom_post_types( $query ) {
  global $wp_query;
  /* Don't break admin or preview pages. */
  if ( !is_preview() && !is_admin() && !is_singular() ) {
    $args = array(
      'public' => true ,
      '_builtin' => false
    );
    $output = 'names';
    $operator = 'and';

    $post_types = array( 'knowledgebase' => 'knowledgebase');
	

    /* Add 'link' and/or 'page' to array() if you want these included:
     * array( 'post' , 'link' , 'page' ), etc.
     */
    $post_types = array_merge( $post_types , array( 'post' ) );

    if ($query->is_feed) {
	   //Dont Add To Feed
    } else {
      $my_post_type = get_query_var( 'post_type' );
      if ( empty( $my_post_type ) )
        $query->set( 'post_type' , $post_types );
		
    }
  }
  return $query;
}


 add_filter('the_content','dynamo_support_kb_like');
 /*
 * KB Was This Helpful Voter
 */
 function dynamo_support_kb_like($content) {
 global $post, $current_user, $support_options;
 get_currentuserinfo();
 if(is_single()) {
	if($post->post_type == 'knowledgebase' && $support_options['kb-enable-votes']) {
		$yes = get_post_meta($post->ID,'kb_vote_yes',true);
		$no = get_post_meta($post->ID,'kb_vote_no',true);
		if($yes == '') { $yes = 0; }
		if($no == '') { $no = 0; }
		$total = $yes+$no;
		$notice = '';
		if($total == 0) {
			$notice = '<p class="notice"><strong>Be the first to let us know!</strong></p>';
		} 
		$content .='
			<div id="kb-rate-box" class="clear">
				<p><strong>Did You Find This Article Helpful?</strong></p>
				<div id="kb-rate-votes">
					'.$notice.'
					<span class="thumbs-up"></span> <input type="radio" class="rate-kb" name="rate-kb" value="1"/><strong>Yes</strong> - <small><span class="yes">'.$yes.'</span> visitors found this post helpful</small><br/>
					<span class="thumbs-down"></span> <input type="radio" class="rate-kb" name="rate-kb" value="0"/><strong>No</strong> - <small><span class="no">'.$no.'</span> visitors found this post was not helpful</small>
				</div>
				<div class="loading-small-kb" id="kb-loading" style="display:none;"></div>
				<div class="clear"></div>
				<input type="hidden" id="ajax-url" value="'.get_bloginfo('wpurl').'"/>
				<input type="hidden" id="post-id" value="'.$post->ID.'"/>
			</div>';
	}
}
 return $content;
 }
 
 add_action('wp_ajax_dynamo_support_kb_vote','dynamo_support_kb_vote');
 add_action('wp_ajax_nopriv_dynamo_support_kb_vote','dynamo_support_kb_vote');
 /*
 * Vote Ajax
 */
 function dynamo_support_kb_vote() {
	$postid = $_POST['postid'];
	$val = $_POST['vote'];
		$yes = get_post_meta($postid,'kb_vote_yes',true);
		$no = get_post_meta($postid,'kb_vote_no',true);
		if($yes == '') { $yes = 0; }
		if($no == '') { $no = 0; }
	if($val == 1) {
		$yes++;
		update_post_meta($postid, 'kb_vote_yes',$yes);
		echo $yes;
	} else {
		$no++;
		update_post_meta($postid, 'kb_vote_no',$no);
		echo $no;
	}
	die();
 }
?>