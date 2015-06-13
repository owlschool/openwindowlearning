<?php		

function wt_set_post_bg(){
	global $post;
	
	if (isset ($post) ){
		$post_id = "";
		$post_id = $post->ID;
		
		$wt_meta_bg_color = get_post_meta($post->ID, 'wt_meta_post_bg_color', true);
		$wt_meta_bg_img = get_post_meta($post->ID, 'wt_meta_post_bg_img', true);
		$wt_meta_bg_repeat = get_post_meta($post->ID, 'wt_meta_post_bg_img_repeat', true);			
		
		$wt_custom_style = '';
			
		if (($wt_meta_bg_color != "") OR ($wt_meta_bg_img !="")){
					
			if ( is_page() ){
				$wt_custom_style .= "body.page-id-$post_id{\n" ;
			} else {
				$wt_custom_style .= "body.postid-$post_id{\n" ;
			}
			
			if (!empty($wt_meta_bg_color)){
				$wt_custom_style .= "	background-color: " .$wt_meta_bg_color. ";\n";
			}
		
			if (!empty($wt_meta_bg_img)){
				$wt_custom_style .= "	background-image: url(" .$wt_meta_bg_img. ");\n";
			}
			
			if (!empty($wt_meta_bg_repeat)){				
				
				if ($wt_meta_bg_repeat == "cover"){
					$wt_custom_style .= "	background-repeat: no-repeat;\n";
					$wt_custom_style .= "	background-position: center center;\n";
					$wt_custom_style .= "	background-attachment: fixed;\n";
					$wt_custom_style .= "	background-size: cover";	
				}else{
					$wt_custom_style .= "	background-repeat: " .$wt_meta_bg_repeat. ";\n";
				}
			
			}	
			
			$wt_custom_style .="}\n\n";
			
			$wt_custom_css_output = "\n<!-- Post Custom Background -->\n<style type=\"text/css\"> \n" . $wt_custom_style . " \n</style>\n<!-- /Post Custom Background -->\n\n";
			echo $wt_custom_css_output;	
			
		}
	}
}	
	add_action('wp_head', 'wt_set_post_bg');
?>