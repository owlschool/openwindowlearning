<?php
class NewaveMetaboxes {
	
	public function __construct()
	{
		global $global_theme_options;
		$this->data = $global_theme_options;

		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_action('save_post', array($this, 'save_meta_boxes'));
        add_action('admin_enqueue_scripts', array($this, 'admin_script_loader'));
	}

	// Load backend scripts
	function admin_script_loader() {
		
		if ( is_admin() ) {
        	
			wp_register_script('newave_upload', get_template_directory_uri().'/js/upload.js');
            wp_enqueue_script('newave_upload');
                    
            wp_register_script('newave_metaboxes', get_template_directory_uri().'/components/metaboxes/js/metaboxes.js');
            wp_enqueue_script('newave_metaboxes');
		}
                
	}
        
    public function add_meta_boxes()
	{
		// blog post options	
		$this->add_meta_box('blog_options', 		'Blog Post Options', 'post');
		$this->add_meta_box('blog_options_gallery', 'Blog Gallery Post Options', 'post');
		$this->add_meta_box('blog_options_video', 	'Blog Video Post Options', 'post');
		$this->add_meta_box('blog_options_audio', 	'Blog Audio Post Options', 'post');
		$this->add_meta_box('blog_options_link', 	'Blog Link Post Options', 'post');
		$this->add_meta_box('blog_options_quote', 	'Blog Quote Post Options', 'post');
		
		// portfolio options
		$this->add_meta_box('portfolio_options', 'Portfolio Options', 'newave_portfolio');
		
		// page options
		$this->add_meta_box('page_options', 			'Page Options', 'page');
		$this->add_meta_box('page_options_parallax', 	'Parallax Section Options', 'page');
		$this->add_meta_box('page_options_default', 	'Default Section Options', 'page');
		$this->add_meta_box('page_options_home', 		'Home Section Options', 'page');
		$this->add_meta_box('page_options_portfolio', 	'Portfolio Section Options', 'page');
		$this->add_meta_box('page_options_contact', 	'Contact Section Options', 'page');
	}
	
	public function add_meta_box($id, $label, $post_type)
	{
	    add_meta_box( 
	        'newave_' . $id,
	        $label,
	        array(&$this, $id),
	        $post_type
	    );
	}
	
	public function save_meta_boxes($post_id)
	{
		if(defined( 'DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}
		
		foreach($_POST as $key => $value) {
			if(strstr($key, 'newave_')) {
				update_post_meta($post_id, $key, $value);
			}
		}
	}

	public function blog_options()
	{
		include 'style.php';
		include 'blog_options.php';
	
	}
	
	public function blog_options_gallery()
	{
		include 'style.php';
		include 'blog_gallery_options.php';
	}
	
	public function blog_options_video()
	{
		include 'style.php';
		include 'blog_video_options.php';
	}
	
	public function blog_options_audio()
	{
		include 'style.php';
		include 'blog_audio_options.php';
	}
	
	public function blog_options_link()
	{
		include 'style.php';
		include 'blog_link_options.php';
	}
	
	public function blog_options_quote()
	{
		include 'style.php';
		include 'blog_quote_options.php';
	}
	
	
	
	public function page_options()
	{
		include 'style.php';
		include 'page_options.php';
	}
	
	public function page_options_parallax()
	{
		include 'style.php';
		include 'page_options_parallax.php';
	}
	
	public function page_options_home()
	{
		include 'style.php';
		include 'page_options_home.php';
	}
	
	public function page_options_default()
	{
		include 'style.php';
		include 'page_options_default.php';
	}
	
	public function page_options_portfolio()
	{
		include 'style.php';
		include 'page_options_portfolio.php';
	}
	
	public function page_options_contact()
	{
		include 'style.php';
		include 'page_options_contact.php';
	}

	
	
	public function portfolio_options()
	{
		include 'style.php';
		include 'portfolio_options.php';
	}
	
	
	public function text($id, $label, $desc = '')
	{
		global $post;
		
		$html = '';
		$html .= '<div class="newave_metabox_field">';
			$html .= '<label for="newave_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
				$html .= '<input type="text" id="newave_' . $id . '" name="newave_' . $id . '" value="' . get_post_meta($post->ID, 'newave_' . $id, true) . '" />';
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';
		
		echo $html;
	}
	
	public function select($id, $label, $options, $desc = '')
	{
		global $post;
		
		$html = '';
		$html .= '<div class="newave_metabox_field">';
			$html .= '<label for="newave_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
				$html .= '<select id="newave_' . $id . '" name="newave_' . $id . '">';
				foreach($options as $key => $option) {
					if(get_post_meta($post->ID, 'newave_' . $id, true) == $key) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}
					
					$html .= '<option ' . $selected . 'value="' . $key . '">' . $option . '</option>';
				}
				$html .= '</select>';
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';
		
		echo $html;
	}
	
	public function radio($id, $label, $options, $desc = '')
	{
		global $post;
		
		$html = '';
		$html .= '<div class="newave_metabox_field">';
			$html .= '<label for="newave_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
				foreach($options as $key => $option) {
					if(get_post_meta($post->ID, 'newave_' . $id, true) == $key) {
						$selected = 'checked="checked"';
					} else {
						$selected = '';
					}
					
					$html .= '<input type="radio" id="newave_' . $id . '" name="newave_' . $id . '" ' . $selected . 'value="' . $key . '">&nbsp;' . $option . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';
		
		echo $html;
	}
	
	public function check_box($id, $label, $options, $desc = '')
	{
		global $post;
		
		$html = '';
		$html .= '<div class="newave_metabox_field">';
			$html .= '<label for="newave_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
				foreach($options as $key => $option) {
					if(get_post_meta($post->ID, 'newave_' . $id, true) == $key) {
						$selected = 'checked="checked"';
					} else {
						$selected = '';
					}
					
					$html .= '<input type="checkbox" id="newave_' . $id . '" name="newave_' . $id . '" ' . $selected . 'value="' . $key . '">&nbsp;' . $option;
				}
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';
		
		echo $html;
	}

	public function multiple($id, $label, $options, $desc = '')
	{
		global $post;
		
		$html = '';
		$html .= '<div class="newave_metabox_field">';
			$html .= '<label for="newave_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
				$html .= '<select multiple="multiple" id="newave_' . $id . '" name="newave_' . $id . '[]">';
				foreach($options as $key => $option) {
					if(is_array(get_post_meta($post->ID, 'newave_' . $id, true)) && in_array($key, get_post_meta($post->ID, 'newave_' . $id, true))) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}
					
					$html .= '<option ' . $selected . 'value="' . $key . '">' . $option . '</option>';
				}
				$html .= '</select>';
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';
		
		echo $html;
	}

	public function textarea($id, $label, $desc = '')
	{
		global $post;

		$html = '';
		$html = '';
		$html .= '<div class="newave_metabox_field">';
			$html .= '<label for="newave_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
				$html .= '<textarea cols="120" rows="10" id="newave_' . $id . '" name="newave_' . $id . '">' . get_post_meta($post->ID, 'newave_' . $id, true) . '</textarea>';
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';
		
		echo $html;
	}

	public function upload($id, $label, $desc = '')
	{
		global $post;

		$html = '';
		$html = '';
		$html .= '<div class="newave_metabox_field">';
			$html .= '<label for="newave_' . $id . '">';
			$html .= $label;
			$html .= '</label>';
			$html .= '<div class="field">';
			    $html .= '<input name="newave_' . $id . '" class="upload_field" id="newave_' . $id . '" type="text" value="' . get_post_meta($post->ID, 'newave_' . $id, true) . '" />';
			    $html .= '<input class="upload_button" type="button" value="Browse" />';
				if($desc) {
					$html .= '<p>' . $desc . '</p>';
				}
			$html .= '</div>';
		$html .= '</div>';
		
		echo $html;
	}
	
}

$metaboxes = new NewaveMetaboxes;