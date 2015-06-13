<?php


class NewaveLike {
	
	 function __construct()   {	
        add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
        add_action('wp_ajax_newave-like', array(&$this, 'ajax'));
		add_action('wp_ajax_nopriv_newave-like', array(&$this, 'ajax'));
	}
	
	function enqueue_scripts() {
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'newave-like', get_template_directory_uri() . '/components/post_like/js/newave-like.js', 'jquery', '1.0', TRUE );
		
		wp_localize_script( 'newave-like', 'newaveLike', array(
			'ajaxurl' => admin_url('admin-ajax.php')
		));
	}
	
	function ajax($post_id) {
		
		//update
		if( isset($_POST['likes_id']) ) {
			$post_id = str_replace('newave-like-', '', $_POST['likes_id']);
			echo $this->like_post($post_id, 'update');
		} 
		
		//get
		else {
			$post_id = str_replace('newave-like-', '', $_POST['likes_id']);
			echo $this->like_post($post_id, 'get');
		}
		
		exit;
	}
	
	
	function like_post($post_id, $action = 'get')
	{
		if(!is_numeric($post_id)) return;
	
		switch($action) {
		
			case 'get':
				$like_count = get_post_meta($post_id, '_newave_like', true);
				if( !$like_count ){
					$like_count = 0;
					add_post_meta($post_id, '_newave_like', $like_count, true);
				}
				
				return '<span class="newave-like-count">'. $like_count .'</span>';
				break;
				
			case 'update':
				$like_count = get_post_meta($post_id, '_newave_like', true);
				if( isset($_COOKIE['newave_like_'. $post_id]) ) return $like_count;
				
				$like_count++;
				update_post_meta($post_id, '_newave_like', $like_count);
				setcookie('newave_like_'. $post_id, $post_id, time()*20, '/');
				
				return '<span class="newave-like-count">'. $like_count .'</span>';
				break;
		
		}
	}


	function add_like() {

		global $post;

		$output = $this->like_post($post->ID);
  
  		$class = 'newave-like';
  		$title = __('Like this', THEME_LANGUAGE_DOMAIN);
		if( isset($_COOKIE['newave_like_'. $post->ID]) ){
			$class = 'newave-like liked';
			$title = __('You already liked this!', THEME_LANGUAGE_DOMAIN);
		}
		
		return '<a href="#" class="'. $class .'" id="newave-like-'. $post->ID .'" title="'. $title .'"> <i class="fa fa-heart"></i> '. $output .'</a>';
	}
	
}


global $newave_like;
$newave_like = new NewaveLike();

function newave_like($return = '') {
	
	global $newave_like;

	if($return == 'return') {
		return $newave_like->add_like();
	} else {
		echo $newave_like->add_like();
	}
	
}

?>
