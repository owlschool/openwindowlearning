<?php

function wt_register_meta_scripts($hook_suffix) {
   if( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix ) {
	wp_enqueue_script('wt_upload', get_template_directory_uri() .'/framework/settings/js/upload.js', array('jquery'));
     //wp_enqueue_style( 'custom_css', get_template_directory_uri() . '/inc/meta/custom.css')
  }
}
add_action( 'admin_enqueue_scripts', 'wt_register_meta_scripts' );

/**
 * Adds post layout meta box to post edit screen
 *
 */
function wellthemes_post_meta_settings(){
	add_meta_box("wt_meta_post_sidebar_settings", "Sidebar Settings", "wt_meta_post_sidebar_settings", "post", "normal", "low");
	add_meta_box("wt_meta_post_ads_settings", "Ads Settings", "wt_meta_post_ads_settings", "post", "normal", "low");
	
	add_meta_box("wt_meta_post_sidebar_settings", "Sidebar Settings", "wt_meta_post_sidebar_settings", "page", "normal", "low");
	add_meta_box("wt_meta_post_ads_settings", "Ads Settings", "wt_meta_post_ads_settings", "page", "normal", "low");
	
	add_meta_box("wt_meta_post_style_settings", "Style Settings", "wt_meta_post_style_settings", "post", "normal", "low");
	add_meta_box("wt_meta_post_style_settings", "Style Settings", "wt_meta_post_style_settings", "page", "normal", "low");
	
	$post_id = '';	
	if(isset($_GET['post'])){  
		$post_id = $_GET['post'];
    }
	
	if(isset($_POST['post_ID'])){
		$post_id =  $_POST['post_ID'];
    }	
	
	$template_file = get_post_meta($post_id, '_wp_page_template', TRUE);
	if ($template_file == 'page-featured.php') {
		add_meta_box("wt_meta_featured_page_settings", "Featured Page Settings", "wt_meta_featured_page_settings", "page", "normal", "high");
	}

    if ($template_file == 'page-local-information.php') {
        add_meta_box("wt_meta_local_information_page_settings", "Local Information Settings", "wt_meta_local_information_page_settings", "page", "normal", "high");
    }
}
add_action( 'add_meta_boxes', 'wellthemes_post_meta_settings' );

/**
 * Display featured post settings
 *
 */ 
function wt_meta_featured_page_settings() {
	global $post;
	global $pagenow;
	
	wp_nonce_field( 'wellthemes_save_postmeta_nonce', 'wellthemes_postmeta_nonce' ); ?>
	
	<div class="meta-section">
		<h4><?php _e('Slider Section', 'wellthemes'); ?></h4>
		
		<div class="meta-field field-checkbox">			
			<input name="wt_meta_show_slider" id="wt_meta_show_slider" type="checkbox" value="1" <?php checked( get_post_meta( $post->ID, 'wt_meta_show_slider', true ), 1 ); ?> /> 
			<label for="wt_meta_show_slider"><?php _e( 'Enable Featured Slider', 'wellthemes' ); ?></label>
		</div>
	
		<div class="meta-field">
			<label><?php _e( 'Slider Category', 'wellthemes' ); ?></label>
			<select id="wt_meta_slider_cat" name="wt_meta_slider_cat" class="styled">
				<?php 
					$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  
					$saved_cat = get_post_meta( $post->ID, 'wt_meta_slider_cat', true );
				?>
				<option <?php selected( 0 == $saved_cat ); ?> value="0"><?php _e('--none--', 'wellthemes'); ?></option>	
				<?php
					if($categories){
						foreach ($categories as $category){?>
							<option <?php selected( $category->term_id == $saved_cat ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>							
							<?php					
						}
					}
				?>			
			</select>
			<div class="desc"><?php _e( 'Select category for slider. Select none to display latest posts.', 'wellthemes' ); ?></div>
		</div>
		
		<div class="meta-field">
			<?php
				$value = get_post_meta( $post->ID, 'wt_meta_slider_speed', true );
				if (empty($value)){
					$value = "5000";
				}
			?>			
			<label for="wt_meta_slider_speed"><?php _e( 'Slider Speed:', 'wellthemes' ); ?></label>
			<input name="wt_meta_slider_speed" class="compact-input" type="text" id="wt_meta_slider_speed" value="<?php echo $value; ?>" />
			<div class="desc"><?php _e( 'Enter the slider speed in milliseconds eg. 4000.', 'wellthemes' ); ?></div>
		</div>

		<div class="meta-field">
			<label><?php _e( 'Right Category 1', 'wellthemes' ); ?></label>
			<select id="wt_meta_slider_right_cat1" name="wt_meta_slider_right_cat1" class="styled">
				<?php 
					$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  
					$saved_cat = get_post_meta( $post->ID, 'wt_meta_slider_right_cat1', true );
				?>
				<option <?php selected( 0 == $saved_cat ); ?> value="0"><?php _e('All Categories', 'wellthemes'); ?></option>	
				<?php
					if($categories){
						foreach ($categories as $category){?>
							<option <?php selected( $category->term_id == $saved_cat ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>							
							<?php					
						}
					}
				?>			
			</select>
			<div class="desc"><?php _e( 'Select slider right category 1', 'wellthemes' ); ?></div>
		</div>
		
		<div class="meta-field">
			<label><?php _e( 'Right Category 2', 'wellthemes' ); ?></label>
			<select id="wt_meta_slider_right_cat2" name="wt_meta_slider_right_cat2" class="styled">
				<?php 
					$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  
					$saved_cat = get_post_meta( $post->ID, 'wt_meta_slider_right_cat2', true );
				?>
				<option <?php selected( 0 == $saved_cat ); ?> value="0"><?php _e('All Categories', 'wellthemes'); ?></option>	
				<?php
					if($categories){
						foreach ($categories as $category){?>
							<option <?php selected( $category->term_id == $saved_cat ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>							
							<?php					
						}
					}
				?>			
			</select>
			<div class="desc"><?php _e( 'Select  slider right category 2.', 'wellthemes' ); ?></div>
		</div>
	</div><!-- /meta-section -->	
		
	<div class="meta-section">
		<h4><?php _e('Carousel', 'wellthemes'); ?></h4>
				
		<div class="meta-field">
			<label for="wt_meta_carousel_title"><?php _e( 'Title:', 'wellthemes' ); ?></label>
			<input name="wt_meta_carousel_title" type="text" class="compact-input" id="wt_meta_carousel_title" value="<?php echo get_post_meta( $post->ID, 'wt_meta_carousel_title', true ); ?>" />
			<div class="desc"><?php _e( 'Enter the carousel title. Leave blank to disable.', 'wellthemes' ); ?></div>
		</div>
		
		<div class="meta-field">
			<label><?php _e( 'Category', 'wellthemes' ); ?></label>
			<select id="wt_meta_carousel_cat" name="wt_meta_carousel_cat" class="styled">
				<?php 
					$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  
					$saved_cat = get_post_meta( $post->ID, 'wt_meta_carousel_cat', true );
				?>
				<option <?php selected( 0 == $saved_cat ); ?> value="0"><?php _e('--none--', 'wellthemes'); ?></option>	
				<?php
					if($categories){
						foreach ($categories as $category){?>
							<option <?php selected( $category->term_id == $saved_cat ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>							
							<?php					
						}
					}
				?>			
			</select>
			<div class="desc"><?php _e( 'Select category. Select none to display latest posts.', 'wellthemes' ); ?></div>
		</div>
	</div><!-- /meta-section -->
		
	<div class="meta-section">
		<h4><?php _e('Featured Categories', 'wellthemes'); ?></h4>
		<h4><?php _e('Category 1', 'wellthemes'); ?></h4>
		
		<div class="meta-field">
			<?php
				$value = get_post_meta( $post->ID, 'wt_meta_feat_cat1_title', true );
				if (empty($value)){
					$value = "";
				}
			?>			
			<label for="wt_meta_feat_cat1_title"><?php _e( 'Section Title:', 'wellthemes' ); ?></label>
			<input name="wt_meta_feat_cat1_title" type="text" class="compact-input" id="wt_meta_feat_cat1_title" value="<?php echo $value; ?>" />
			<div class="desc"><?php _e( 'Enter section title. Leave blank to disable.', 'wellthemes' ); ?></div>
		</div>
		
		<div class="meta-field">
			<?php
				$value = get_post_meta( $post->ID, 'wt_meta_feat_cat1_title_color', true );
				?>			
			<label for="wt_meta_feat_cat1_title_color"><?php _e( 'Title color:', 'wellthemes' ); ?></label>
			<div id="wt_meta_feat_cat1_title_color_selector" class="color-pic"><div style="background-color:<?php echo $value; ?>"></div></div>
			<input style="width:80px; margin-right:5px;"  name="wt_meta_feat_cat1_title_color" id="wt_meta_feat_cat1_title_color" type="text" value="<?php echo $value; ?>" />
			<div class="desc"><?php _e( 'Enter section title color.', 'wellthemes' ); ?></div>
		</div>
		
		<div class="meta-field">
			<label><?php _e( 'Category', 'wellthemes' ); ?></label>
			<select id="wt_meta_feat_cat1" name="wt_meta_feat_cat1" class="styled">
				<?php 
					$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  
					$saved_cat = get_post_meta( $post->ID, 'wt_meta_feat_cat1', true );
				?>
				<option <?php selected( 0 == $saved_cat ); ?> value="0"><?php _e('--none--', 'wellthemes'); ?></option>	
				<?php
					if($categories){
						foreach ($categories as $category){?>
							<option <?php selected( $category->term_id == $saved_cat ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>							
							<?php					
						}
					}
				?>			
			</select>
			<div class="desc"><?php _e( 'Select category for the section', 'wellthemes' ); ?></div>
		</div>
		
		<h4><?php _e('Category 2', 'wellthemes'); ?></h4>
		
		<div class="meta-field">
			<?php
				$value = get_post_meta( $post->ID, 'wt_meta_feat_cat2_title', true );
				if (empty($value)){
					$value = "";
				}
			?>			
			<label for="wt_meta_feat_cat2_title"><?php _e( 'Section Title:', 'wellthemes' ); ?></label>
			<input name="wt_meta_feat_cat2_title" type="text" class="compact-input" id="wt_meta_feat_cat2_title" value="<?php echo $value; ?>" />
			<div class="desc"><?php _e( 'Enter section title. Leave blank to disable.', 'wellthemes' ); ?></div>
		</div>
		
		<div class="meta-field">
			<?php
				$value = get_post_meta( $post->ID, 'wt_meta_feat_cat2_title_color', true );
				?>			
			<label for="wt_meta_feat_cat2_title_color"><?php _e( 'Title color:', 'wellthemes' ); ?></label>
			<div id="wt_meta_feat_cat2_title_color_selector" class="color-pic"><div style="background-color:<?php echo $value; ?>"></div></div>
			<input style="width:80px; margin-right:5px;"  name="wt_meta_feat_cat2_title_color" id="wt_meta_feat_cat2_title_color" type="text" value="<?php echo $value; ?>" />
			<div class="desc"><?php _e( 'Enter section title color.', 'wellthemes' ); ?></div>
		</div>		
		
		<div class="meta-field">
			<label><?php _e( 'Category', 'wellthemes' ); ?></label>
			<select id="wt_meta_feat_cat2" name="wt_meta_feat_cat2" class="styled">
				<?php 
					$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  
					$saved_cat = get_post_meta( $post->ID, 'wt_meta_feat_cat2', true );
				?>
				<option <?php selected( 0 == $saved_cat ); ?> value="0"><?php _e('--none--', 'wellthemes'); ?></option>	
				<?php
					if($categories){
						foreach ($categories as $category){?>
							<option <?php selected( $category->term_id == $saved_cat ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>							
							<?php					
						}
					}
				?>			
			</select>
			<div class="desc"><?php _e( 'Select category. Select none to disable.', 'wellthemes' ); ?></div>
		</div>
		
		<h4><?php _e('Category 3', 'wellthemes'); ?></h4>
		
		<div class="meta-field">
			<?php
				$value = get_post_meta( $post->ID, 'wt_meta_feat_cat3_title', true );
				if (empty($value)){
					$value = "";
				}
			?>			
			<label for="wt_meta_feat_cat3_title"><?php _e( 'Section Title:', 'wellthemes' ); ?></label>
			<input name="wt_meta_feat_cat3_title" type="text" class="compact-input" id="wt_meta_feat_cat3_title" value="<?php echo $value; ?>" />
			<div class="desc"><?php _e( 'Enter section title. Leave blank to disable.', 'wellthemes' ); ?></div>
		</div>
		
		<div class="meta-field">
			<?php
				$value = get_post_meta( $post->ID, 'wt_meta_feat_cat3_title_color', true );
				?>			
			<label for="wt_meta_feat_cat3_title_color"><?php _e( 'Title color:', 'wellthemes' ); ?></label>
			<div id="wt_meta_feat_cat3_title_color_selector" class="color-pic"><div style="background-color:<?php echo $value; ?>"></div></div>
			<input style="width:80px; margin-right:5px;"  name="wt_meta_feat_cat3_title_color" id="wt_meta_feat_cat3_title_color" type="text" value="<?php echo $value; ?>" />
			<div class="desc"><?php _e( 'Enter section title color.', 'wellthemes' ); ?></div>
		</div>
		
		<div class="meta-field">
			<label><?php _e( 'Category', 'wellthemes' ); ?></label>
			<select id="wt_meta_feat_cat3" name="wt_meta_feat_cat3" class="styled">
				<?php 
					$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  
					$saved_cat = get_post_meta( $post->ID, 'wt_meta_feat_cat3', true );
				?>
				<option <?php selected( 0 == $saved_cat ); ?> value="0"><?php _e('--none--', 'wellthemes'); ?></option>	
				<?php
					if($categories){
						foreach ($categories as $category){?>
							<option <?php selected( $category->term_id == $saved_cat ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>							
							<?php					
						}
					}
				?>			
			</select>
			<div class="desc"><?php _e( 'Select category. Select none to disable.', 'wellthemes' ); ?></div>
		</div>
		
		<h4><?php _e('Category 4', 'wellthemes'); ?></h4>
		
		<div class="meta-field">
			<?php
				$value = get_post_meta( $post->ID, 'wt_meta_feat_cat4_title', true );
				if (empty($value)){
					$value = "";
				}
			?>			
			<label for="wt_meta_feat_cat4_title"><?php _e( 'Section Title:', 'wellthemes' ); ?></label>
			<input name="wt_meta_feat_cat4_title" type="text" class="compact-input" id="wt_meta_feat_cat4_title" value="<?php echo $value; ?>" />
			<div class="desc"><?php _e( 'Enter section title. Leave blank to disable.', 'wellthemes' ); ?></div>
		</div>
		
		<div class="meta-field">
			<?php
				$value = get_post_meta( $post->ID, 'wt_meta_feat_cat4_title_color', true );
				?>			
			<label for="wt_meta_feat_cat4_title_color"><?php _e( 'Title color:', 'wellthemes' ); ?></label>
			<div id="wt_meta_feat_cat4_title_color_selector" class="color-pic"><div style="background-color:<?php echo $value; ?>"></div></div>
			<input style="width:80px; margin-right:5px;"  name="wt_meta_feat_cat4_title_color" id="wt_meta_feat_cat4_title_color" type="text" value="<?php echo $value; ?>" />
			<div class="desc"><?php _e( 'Enter section title color.', 'wellthemes' ); ?></div>
		</div>
		
		<div class="meta-field">
			<label><?php _e( 'Category', 'wellthemes' ); ?></label>
			<select id="wt_meta_feat_cat4" name="wt_meta_feat_cat4" class="styled">
				<?php 
					$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  
					$saved_cat = get_post_meta( $post->ID, 'wt_meta_feat_cat4', true );
				?>
				<option <?php selected( 0 == $saved_cat ); ?> value="0"><?php _e('--none--', 'wellthemes'); ?></option>	
				<?php
					if($categories){
						foreach ($categories as $category){?>
							<option <?php selected( $category->term_id == $saved_cat ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>							
							<?php					
						}
					}
				?>			
			</select>
			<div class="desc"><?php _e( 'Select category. Select none to disable.', 'wellthemes' ); ?></div>
		</div>
		
		<h4><?php _e('Category 5', 'wellthemes'); ?></h4>
		
		<div class="meta-field">
			<?php
				$value = get_post_meta( $post->ID, 'wt_meta_feat_cat5_title', true );
				if (empty($value)){
					$value = "";
				}
			?>			
			<label for="wt_meta_feat_cat5_title"><?php _e( 'Section Title:', 'wellthemes' ); ?></label>
			<input name="wt_meta_feat_cat5_title" type="text" class="compact-input" id="wt_meta_feat_cat5_title" value="<?php echo $value; ?>" />
			<div class="desc"><?php _e( 'Enter section title. Leave blank to disable.', 'wellthemes' ); ?></div>
		</div>
		
		<div class="meta-field">
			<?php
				$value = get_post_meta( $post->ID, 'wt_meta_feat_cat5_title_color', true );
				?>			
			<label for="wt_meta_feat_cat5_title_color"><?php _e( 'Title color:', 'wellthemes' ); ?></label>
			<div id="wt_meta_feat_cat5_title_color_selector" class="color-pic"><div style="background-color:<?php echo $value; ?>"></div></div>
			<input style="width:80px; margin-right:5px;"  name="wt_meta_feat_cat5_title_color" id="wt_meta_feat_cat5_title_color" type="text" value="<?php echo $value; ?>" />
			<div class="desc"><?php _e( 'Enter section title color.', 'wellthemes' ); ?></div>
		</div>
		
		<div class="meta-field">
			<label><?php _e( 'Category', 'wellthemes' ); ?></label>
			<select id="wt_meta_feat_cat5" name="wt_meta_feat_cat5" class="styled">
				<?php 
					$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  
					$saved_cat = get_post_meta( $post->ID, 'wt_meta_feat_cat5', true );
				?>
				<option <?php selected( 0 == $saved_cat ); ?> value="0"><?php _e('--none--', 'wellthemes'); ?></option>	
				<?php
					if($categories){
						foreach ($categories as $category){?>
							<option <?php selected( $category->term_id == $saved_cat ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>							
							<?php					
						}
					}
				?>			
			</select>
			<div class="desc"><?php _e( 'Select category. Select none to disable.', 'wellthemes' ); ?></div>
		</div>
	</div><!-- /meta-section -->

	<div class="meta-section">
		<h4><?php _e('Latest Posts', 'wellthemes'); ?></h4>
		
		<div class="meta-field">
			<label for="wt_meta_postlist_title"><?php _e( 'Title:', 'wellthemes' ); ?></label>
			<input name="wt_meta_postlist_title" type="text" class="compact-input" id="wt_meta_postlist_title" value="<?php echo get_post_meta( $post->ID, 'wt_meta_postlist_title', true ); ?>" />
			<div class="desc"><?php _e( 'Enter the posts title. Leave blank to disable.', 'wellthemes' ); ?></div>
		</div>
		
		<div class="meta-field">
			<?php
				$value = get_post_meta( $post->ID, 'wt_meta_postlist_color', true );
				?>			
			<label for="wt_meta_postlist_color"><?php _e( 'Title color:', 'wellthemes' ); ?></label>
			<div id="wt_meta_postlist_color_selector" class="color-pic"><div style="background-color:<?php echo $value; ?>"></div></div>
			<input style="width:80px; margin-right:5px;"  name="wt_meta_postlist_color" id="wt_meta_postlist_color" type="text" value="<?php echo $value; ?>" />
			<div class="desc"><?php _e( 'Enter section title color.', 'wellthemes' ); ?></div>
		</div>
		
		<div class="meta-field">
			<label><?php _e( 'Category', 'wellthemes' ); ?></label>
			<select id="wt_meta_postlist_cat" name="wt_meta_postlist_cat" class="styled">
				<?php 
					$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  
					$saved_cat = get_post_meta( $post->ID, 'wt_meta_postlist_cat', true );
				?>
				<option <?php selected( 0 == $saved_cat ); ?> value="0"><?php _e('--none--', 'wellthemes'); ?></option>	
				<?php
					if($categories){
						foreach ($categories as $category){?>
							<option <?php selected( $category->term_id == $saved_cat ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>							
							<?php					
						}
					}
				?>			
			</select>
			<div class="desc"><?php _e( 'Select category. Select none to disable.', 'wellthemes' ); ?></div>
		</div>
		
	</div><!-- /meta-section -->
				
<?php
	}

/**
 * Display local information page settings
 *
 */
function wt_meta_local_information_page_settings() {
    global $post;
    global $pagenow;

    wp_nonce_field( 'wellthemes_save_postmeta_nonce', 'wellthemes_postmeta_nonce' ); ?>

    <div class="meta-section">
        <h4>Available Tests</h4>

        <div class="meta-field field-checkbox">
            <input name="wt_meta_available_test_ged" id="wt_meta_available_test_ged" type="checkbox" value="1" <?php checked( get_post_meta( $post->ID, 'wt_meta_available_test_ged', true ), 1 ); ?> />
            <label for="wt_meta_available_test_ged">GED Available:</label>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_available_test_ged_reg_link', true );
            if (empty($value)){
                $value = "http://www.ged.com";
            }
            ?>
            <label for="wt_meta_available_test_ged_reg_link">GED Registration Link</label>
            <input name="wt_meta_available_test_ged_reg_link" class="compact-input" type="text" id="wt_meta_available_test_ged_reg_link" value="<?php echo $value; ?>" />
            <div class="desc">Enter link for registration website for GED</div>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_available_test_ged_state_policy_link', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_available_test_ged_state_policy_link">GED State Policy Link</label>
            <input name="wt_meta_available_test_ged_state_policy_link" class="compact-input" type="text" id="wt_meta_available_test_ged_state_policy_link" value="<?php echo $value; ?>" />
            <div class="desc">Enter link for state policy website for GED</div>
        </div>

        <div class="meta-field field-checkbox">
            <input name="wt_meta_available_test_hiset" id="wt_meta_available_test_hiset" type="checkbox" value="1" <?php checked( get_post_meta( $post->ID, 'wt_meta_available_test_hiset', true ), 1 ); ?> />
            <label for="wt_meta_available_test_hiset">HiSET Available:</label>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_available_test_hiset_reg_link', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_available_test_hiset_reg_link">HiSET Registration Link</label>
            <input name="wt_meta_available_test_hiset_reg_link" class="compact-input" type="text" id="wt_meta_available_test_hiset_reg_link" value="<?php echo $value; ?>" />
            <div class="desc">Enter link for registration website for HiSET</div>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_available_test_hiset_state_policy_link', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_available_test_hiset_state_policy_link">HiSET State Policy Link</label>
            <input name="wt_meta_available_test_hiset_state_policy_link" class="compact-input" type="text" id="wt_meta_available_test_hiset_state_policy_link" value="<?php echo $value; ?>" />
            <div class="desc">Enter link for state policy website for HiSET</div>
        </div>


        <div class="meta-field field-checkbox">
            <input name="wt_meta_available_test_tasc" id="wt_meta_available_test_tasc" type="checkbox" value="1" <?php checked( get_post_meta( $post->ID, 'wt_meta_available_test_tasc', true ), 1 ); ?> />
            <label for="wt_meta_available_test_tasc">TASC Available:</label>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_available_test_tasc_reg_link', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_available_test_tasc_reg_link">TASC Registration Link</label>
            <input name="wt_meta_available_test_tasc_reg_link" class="compact-input" type="text" id="wt_meta_available_test_tasc_reg_link" value="<?php echo $value; ?>" />
            <div class="desc">Enter link for registration website for TASC</div>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_available_test_tasc_state_policy_link', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_available_test_tasc_state_policy_link">TASC State Policy Link</label>
            <input name="wt_meta_available_test_tasc_state_policy_link" class="compact-input" type="text" id="wt_meta_available_test_tasc_state_policy_link" value="<?php echo $value; ?>" />
            <div class="desc">Enter link for state policy website for TASC</div>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_available_test_general_state_link', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_available_test_general_state_link">General State Policy/Information Link</label>
            <input name="wt_meta_available_test_general_state_link" class="compact-input" type="text" id="wt_meta_available_test_general_state_link" value="<?php echo $value; ?>" />
            <div class="desc">Enter link for general information about the tests for the state</div>
        </div>

    </div><!-- /meta-section -->

    <div class="meta-section">
        <h4>Financial Aid</h4>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_financial_aid_scholarships_for_ged', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_financial_aid_scholarships_for_ged">Scholarships For GED</label>
            <textarea id="wt_meta_financial_aid_scholarships_for_ged" class="textarea" name="wt_meta_financial_aid_scholarships_for_ged"><?php echo $value; ?></textarea>
            <div class="desc">Enter scholarship information for GED students who score high.</div>
        </div>

    </div><!-- /meta-section -->

    <div class="meta-section">
        <h4>Educational Programs - School districts</h4>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_school_districts_description', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_school_districts_description">Description:</label>
            <textarea id="wt_meta_school_districts_description" class="textarea" name="wt_meta_school_districts_description"><?php echo $value; ?></textarea>
            <div class="desc">Description for school district programs. Leave empty for default.</div>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_alternative_programs_link_1', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_alternative_programs_link_1">Link 1 to alternative high school programs</label>
            <input name="wt_meta_alternative_programs_link_1" class="compact-input" type="text" id="wt_meta_alternative_programs_link_1" value="<?php echo $value; ?>" />
            <div class="desc">Link to alternative high school programs</div>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_alternative_programs_link_2', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_alternative_programs_link_2">Link 2 to alternative high school programs</label>
            <input name="wt_meta_alternative_programs_link_2" class="compact-input" type="text" id="wt_meta_alternative_programs_link_2" value="<?php echo $value; ?>" />
            <div class="desc">Link to alternative high school programs</div>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_school_districts_link_1', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_school_districts_link_1">Link 1 to school districts</label>
            <input name="wt_meta_school_districts_link_1" class="compact-input" type="text" id="wt_meta_school_districts_link_1" value="<?php echo $value; ?>" />
            <div class="desc">Link to school districts</div>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_school_districts_link_2', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_school_districts_link_2">Link 2 to school districts</label>
            <input name="wt_meta_school_districts_link_2" class="compact-input" type="text" id="wt_meta_school_districts_link_2" value="<?php echo $value; ?>" />
            <div class="desc">Link to school districts</div>
        </div>

    </div><!-- /meta-section -->

    <div class="meta-section">
        <h4>Educational Programs - Community Colleges</h4>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_community_colleges_description', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_community_colleges_description">Description:</label>
            <textarea id="wt_meta_community_colleges_description" class="textarea" name="wt_meta_community_colleges_description"><?php echo $value; ?></textarea>
            <div class="desc">Description for community college programs. Leave empty for default.</div>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_community_colleges_link_1', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_community_colleges_link_1">Link 1 to Comm Colleges</label>
            <input name="wt_meta_community_colleges_link_1" class="compact-input" type="text" id="wt_meta_community_colleges_link_1" value="<?php echo $value; ?>" />
            <div class="desc">Link to community colleges</div>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_community_colleges_link_2', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_community_colleges_link_2">Link 2 to Comm Colleges</label>
            <input name="wt_meta_community_colleges_link_2" class="compact-input" type="text" id="wt_meta_community_colleges_link_2" value="<?php echo $value; ?>" />
            <div class="desc">Link to community colleges</div>
        </div>
    </div><!-- /meta-section -->

    <div class="meta-section">
        <h4>Educational Programs - Unemployment Offices</h4>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_unemployment_offices_description', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_unemployment_offices_description">Description:</label>
            <textarea id="wt_meta_unemployment_offices_description" class="textarea" name="wt_meta_unemployment_offices_description"><?php echo $value; ?></textarea>
            <div class="desc">Description for unemployment office programs. Leave empty for default.</div>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_unemployment_offices_link_1', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_unemployment_offices_link_1">Link 1</label>
            <input name="wt_meta_unemployment_offices_link_1" class="compact-input" type="text" id="wt_meta_unemployment_offices_link_1" value="<?php echo $value; ?>" />
            <div class="desc">Link to unemployment offices</div>
        </div>

        <div class="meta-field">
            <?php
            $value = get_post_meta( $post->ID, 'wt_meta_unemployment_offices_link_2', true );
            if (empty($value)){
                $value = "";
            }
            ?>
            <label for="wt_meta_unemployment_offices_link_2">Link 2</label>
            <input name="wt_meta_unemployment_offices_link_2" class="compact-input" type="text" id="wt_meta_unemployment_offices_link_2" value="<?php echo $value; ?>" />
            <div class="desc">Link to unemployment offices</div>
        </div>
    </div><!-- /meta-section -->

<?php
}

/**
 * Display sidebar settings
 *
 */
function wt_meta_post_sidebar_settings() {
	global $post;
	wp_nonce_field( 'wellthemes_save_postmeta_nonce', 'wellthemes_postmeta_nonce' ); ?>
		
	<div class="meta-field">
		<?php 	
			$options = get_option('wt_options');
			$sidebars = "";													
			if (isset($options['wt_custom_sidebars'])){
				$sidebars = $options['wt_custom_sidebars'] ;
			}
		
			$saved_right_sidebar = get_post_meta( $post->ID, 'wt_meta_sidebar_name', true ); 
		?>
				
		<div class="meta-field">
			<label><?php _e( 'Select Sidebar:', 'wellthemes' ); ?></label>
			<select id="wt_meta_sidebar_name" name="wt_meta_sidebar_name" class="styled">
				<option <?php selected( "" == $saved_right_sidebar ); ?> value=""><?php _e('Default', 'wellthemes'); ?></option>	
				<?php
					if($sidebars){
						foreach ($sidebars as $sidebar){?>
							<option <?php selected( $sidebar == $saved_right_sidebar ); ?> value="<?php echo $sidebar; ?>"><?php echo $sidebar ?></option><?php					
						}
					}
				?>		
			</select>
			<span class="desc"><?php _e( 'You can create custom sidebars in WellThemes\'s theme options page.', 'wellthemes' ); ?></span>
		</div>
				
	</div>
	<?php
}

/**
 * Display style settings
 *
 */ 
function wt_meta_post_style_settings() {
	global $post;
	wp_nonce_field( 'wellthemes_save_postmeta_nonce', 'wellthemes_postmeta_nonce' ); ?>
	
	<div class="meta-field">
		<?php $saved_bg_color= get_post_meta( $post->ID, 'wt_meta_post_bg_color', true ); ?>
		<label><?php _e( 'Background color:', 'wellthemes' ); ?></label>
		<div id="wt_meta_post_bg_selector" class="color-pic"><div style="background-color:<?php echo $saved_bg_color  ?>"></div></div>
		<input style="width:80px; margin-right:5px;"  name="wt_meta_post_bg_color" id="wt_meta_post_bg_color" type="text" value="<?php echo $saved_bg_color  ?>" />				
		<span class="desc"><?php _e( 'Select background color for the post. Leave blank for default.', 'wellthemes' ); ?></span>				
	</div>
	
	<div class="meta-field field">
		<?php $saved_bg_img= get_post_meta( $post->ID, 'wt_meta_post_bg_img', true ); ?>
		<label><?php _e( 'Background Image:', 'wellthemes' ); ?></label>
		<input id="wt_meta_post_bg_img" class="upload_image" type="text" name="wt_meta_post_bg_img" value="<?php echo $saved_bg_img  ?>" />
        <input class="upload_image_button" id="wt_meta_post_bg_color_button" type="button" value="Upload" />
		<span class="desc"><?php _e( 'Upload image or leave blank for default.', 'wellthemes' ); ?></span>	
	</div>
	
	<div class="meta-field">
		<?php $saved_img_repeat = get_post_meta( $post->ID, 'wt_meta_post_bg_img_repeat', true ); ?>
		<label><?php _e( 'Background repeat:', 'wellthemes' ); ?></label>
		<select id="wt_meta_post_bg_img_repeat" name="wt_meta_post_bg_img_repeat" class="styled">	
			<option <?php selected( "repeat" == $saved_img_repeat ); ?> value="repeat"><?php _e('Repeat', 'wellthemes'); ?></option>			
			<option <?php selected( "repeat-x" == $saved_img_repeat ); ?> value="repeat-x"><?php _e('Repeat x', 'wellthemes'); ?></option>
			<option <?php selected( "repeat-y" == $saved_img_repeat ); ?> value="repeat-y"><?php _e('Repeat y', 'wellthemes'); ?></option>
			<option <?php selected( "no-repeat" == $saved_img_repeat ); ?> value="no-repeat"><?php _e('No Repeat', 'wellthemes'); ?></option>
			<option <?php selected( "cover" == $saved_img_repeat ); ?> value="cover"><?php _e('Cover', 'wellthemes'); ?></option>			
		</select>
		<span class="desc"><?php _e( 'Select the background image repeat style.', 'wellthemes' ); ?></span>
	</div>
	<?php
			
	}

/**
 * Display Ads settings
 *
 */ 
function wt_meta_post_ads_settings() {
	global $post;
	wp_nonce_field( 'wellthemes_save_postmeta_nonce', 'wellthemes_postmeta_nonce' );	?>
	
	<div class="meta-field textarea-field">
		<label><?php _e( 'Post Top Banner:', 'wellthemes' ); ?></label>
		<textarea name="wt_meta_banner1" id="wt_meta_banner1" type="textarea" cols="100%" rows="3"><?php echo get_post_meta( $post->ID, 'wt_meta_banner1', true ); ?></textarea>
		<div class="desc"><?php _e( 'Paste the banner code for post top banner. Leave blank to disable.', 'wellthemes' ); ?></div>			
	</div>
	
	<div class="meta-field textarea-field">
		<label><?php _e( 'Post Bottom Banner:', 'wellthemes' ); ?></label>
		<textarea name="wt_meta_banner2" id="wt_meta_banner2" type="textarea" cols="100%" rows="3"><?php echo get_post_meta( $post->ID, 'wt_meta_banner2', true ); ?></textarea>
		<div class="desc"><?php _e( 'Paste the banner code for post top banner. Leave blank to disable.', 'wellthemes' ); ?></div>			
	</div>
	
	<?php
	}
	
/**
 * Save post meta box settings
 *
 */
function wt_post_meta_save_settings() {
	global $post;
	
	if( !isset( $_POST['wellthemes_postmeta_nonce'] ) || !wp_verify_nonce( $_POST['wellthemes_postmeta_nonce'], 'wellthemes_save_postmeta_nonce' ) )
		return;

	if( !current_user_can( 'edit_posts' ) )
		return;
	
	if ( isset( $_POST['wt_meta_tiles_cat'] )){
		update_post_meta( $post->ID, 'wt_meta_tiles_cat', $_POST['wt_meta_tiles_cat'] );	
	}
	
	if ( isset( $_POST['wt_meta_show_slider'] ) && $_POST['wt_meta_show_slider'] == 1) {
		update_post_meta( $post->ID, 'wt_meta_show_slider', 1 );	
	} else {
		delete_post_meta( $post->ID, 'wt_meta_show_slider');	
	}
	
	if ( isset( $_POST['wt_meta_slider_cat'] )){
		update_post_meta( $post->ID, 'wt_meta_slider_cat', $_POST['wt_meta_slider_cat'] );	
	}
	
	if ( isset( $_POST['wt_meta_slider_right_cat1'] )){
		update_post_meta( $post->ID, 'wt_meta_slider_right_cat1', $_POST['wt_meta_slider_right_cat1'] );	
	}
	
	if ( isset( $_POST['wt_meta_slider_right_cat2'] )){
		update_post_meta( $post->ID, 'wt_meta_slider_right_cat2', $_POST['wt_meta_slider_right_cat2'] );	
	}	
	
	if(isset($_POST['wt_meta_slider_speed'])){
		update_post_meta($post->ID, 'wt_meta_slider_speed', sanitize_text_field($_POST['wt_meta_slider_speed']));
	}
	
	if(isset($_POST['wt_meta_feat_cat1_title'])){
		update_post_meta($post->ID, 'wt_meta_feat_cat1_title', sanitize_text_field($_POST['wt_meta_feat_cat1_title']));
	}
	
	if(isset($_POST['wt_meta_feat_cat2_title'])){
		update_post_meta($post->ID, 'wt_meta_feat_cat2_title', sanitize_text_field($_POST['wt_meta_feat_cat2_title']));
	}
	
	if(isset($_POST['wt_meta_feat_cat3_title'])){
		update_post_meta($post->ID, 'wt_meta_feat_cat3_title', sanitize_text_field($_POST['wt_meta_feat_cat3_title']));
	}
	
	if(isset($_POST['wt_meta_feat_cat4_title'])){
		update_post_meta($post->ID, 'wt_meta_feat_cat4_title', sanitize_text_field($_POST['wt_meta_feat_cat4_title']));
	}
	
	if(isset($_POST['wt_meta_feat_cat5_title'])){
		update_post_meta($post->ID, 'wt_meta_feat_cat5_title', sanitize_text_field($_POST['wt_meta_feat_cat5_title']));
	}
		
	if(isset($_POST['wt_meta_feat_cat1_title_color'])){
		update_post_meta($post->ID, 'wt_meta_feat_cat1_title_color', sanitize_text_field($_POST['wt_meta_feat_cat1_title_color']));
	}

	if(isset($_POST['wt_meta_feat_cat2_title_color'])){
		update_post_meta($post->ID, 'wt_meta_feat_cat2_title_color', sanitize_text_field($_POST['wt_meta_feat_cat2_title_color']));
	}
	
	if(isset($_POST['wt_meta_feat_cat3_title_color'])){
		update_post_meta($post->ID, 'wt_meta_feat_cat3_title_color', sanitize_text_field($_POST['wt_meta_feat_cat3_title_color']));
	}
	
	if(isset($_POST['wt_meta_feat_cat4_title_color'])){
		update_post_meta($post->ID, 'wt_meta_feat_cat4_title_color', sanitize_text_field($_POST['wt_meta_feat_cat4_title_color']));
	}
	
	if(isset($_POST['wt_meta_feat_cat5_title_color'])){
		update_post_meta($post->ID, 'wt_meta_feat_cat5_title_color', sanitize_text_field($_POST['wt_meta_feat_cat5_title_color']));
	}
		
	if ( isset( $_POST['wt_meta_feat_cat1'] )){
		update_post_meta( $post->ID, 'wt_meta_feat_cat1', $_POST['wt_meta_feat_cat1'] );	
	}
	
	if ( isset( $_POST['wt_meta_feat_cat2'] )){
		update_post_meta( $post->ID, 'wt_meta_feat_cat2', $_POST['wt_meta_feat_cat2'] );	
	}
	
	if ( isset( $_POST['wt_meta_feat_cat3'] )){
		update_post_meta( $post->ID, 'wt_meta_feat_cat3', $_POST['wt_meta_feat_cat3'] );	
	}
	
	if ( isset( $_POST['wt_meta_feat_cat4'] )){
		update_post_meta( $post->ID, 'wt_meta_feat_cat4', $_POST['wt_meta_feat_cat4'] );	
	}
	
	if ( isset( $_POST['wt_meta_feat_cat5'] )){
		update_post_meta( $post->ID, 'wt_meta_feat_cat5', $_POST['wt_meta_feat_cat5'] );	
	}	
	
	if(isset($_POST['wt_meta_postlist_title'])){
		update_post_meta($post->ID, 'wt_meta_postlist_title', sanitize_text_field($_POST['wt_meta_postlist_title']));
	}
	
	if(isset($_POST['wt_meta_postlist_color'])){
		update_post_meta($post->ID, 'wt_meta_postlist_color', sanitize_text_field($_POST['wt_meta_postlist_color']));
	}
		
	if ( isset( $_POST['wt_meta_postlist_cat'] )){
		update_post_meta( $post->ID, 'wt_meta_postlist_cat', $_POST['wt_meta_postlist_cat'] );	
	}
		
	if(isset($_POST['wt_meta_carousel_title'])){
		update_post_meta($post->ID, 'wt_meta_carousel_title', sanitize_text_field($_POST['wt_meta_carousel_title']));
	}
		
	if ( isset( $_POST['wt_meta_carousel_cat'] )){
		update_post_meta( $post->ID, 'wt_meta_carousel_cat', $_POST['wt_meta_carousel_cat'] );	
	}
		
	if ( isset( $_POST['wt_meta_sidebar_name'] )){
		update_post_meta( $post->ID, 'wt_meta_sidebar_name', $_POST['wt_meta_sidebar_name'] );	
	}
	
	if(isset($_POST['wt_meta_post_bg_color'])){
		update_post_meta($post->ID, 'wt_meta_post_bg_color', sanitize_text_field($_POST['wt_meta_post_bg_color']));
	}
	
	if(isset($_POST['wt_meta_post_bg_img'])){
		update_post_meta($post->ID, 'wt_meta_post_bg_img', esc_url_raw($_POST['wt_meta_post_bg_img']));
	}
	
	if ( isset( $_POST['wt_meta_post_bg_img_repeat'] ) && in_array( $_POST['wt_meta_post_bg_img_repeat'], array( 'repeat','repeat-x','repeat-y','no-repeat','cover') ) ){
		update_post_meta( $post->ID, 'wt_meta_post_bg_img_repeat', $_POST['wt_meta_post_bg_img_repeat'] );	
	}
		
	if(isset($_POST['wt_meta_banner1'])){
		update_post_meta( $post->ID, 'wt_meta_banner1', $_POST['wt_meta_banner1'] );
	}
	
	if(isset($_POST['wt_meta_banner2'])){
		update_post_meta( $post->ID, 'wt_meta_banner2', $_POST['wt_meta_banner2'] );
	}

    if ( isset( $_POST['wt_meta_available_test_ged'] ) && $_POST['wt_meta_available_test_ged'] == 1) {
        update_post_meta( $post->ID, 'wt_meta_available_test_ged', 1 );
    } else {
        delete_post_meta( $post->ID, 'wt_meta_available_test_ged');
    }

    if ( isset( $_POST['wt_meta_available_test_ged_reg_link'] )){
        update_post_meta( $post->ID, 'wt_meta_available_test_ged_reg_link', esc_url_raw($_POST['wt_meta_available_test_ged_reg_link']) );
    }

    if ( isset( $_POST['wt_meta_available_test_ged_state_policy_link'] )){
        update_post_meta( $post->ID, 'wt_meta_available_test_ged_state_policy_link', esc_url_raw($_POST['wt_meta_available_test_ged_state_policy_link']) );
    }

    if ( isset( $_POST['wt_meta_available_test_hiset'] ) && $_POST['wt_meta_available_test_hiset'] == 1) {
        update_post_meta( $post->ID, 'wt_meta_available_test_hiset', 1 );
    } else {
        delete_post_meta( $post->ID, 'wt_meta_available_test_hiset');
    }

    if ( isset( $_POST['wt_meta_available_test_hiset_reg_link'] )){
        update_post_meta( $post->ID, 'wt_meta_available_test_hiset_reg_link', esc_url_raw($_POST['wt_meta_available_test_hiset_reg_link']) );
    }

    if ( isset( $_POST['wt_meta_available_test_hiset_state_policy_link'] )){
        update_post_meta( $post->ID, 'wt_meta_available_test_hiset_state_policy_link', esc_url_raw($_POST['wt_meta_available_test_hiset_state_policy_link']) );
    }

    if ( isset( $_POST['wt_meta_available_test_tasc'] ) && $_POST['wt_meta_available_test_tasc'] == 1) {
        update_post_meta( $post->ID, 'wt_meta_available_test_tasc', 1 );
    } else {
        delete_post_meta( $post->ID, 'wt_meta_available_test_tasc');
    }

    if ( isset( $_POST['wt_meta_available_test_tasc_reg_link'] )){
        update_post_meta( $post->ID, 'wt_meta_available_test_tasc_reg_link', esc_url_raw($_POST['wt_meta_available_test_tasc_reg_link']) );
    }

    if ( isset( $_POST['wt_meta_available_test_tasc_state_policy_link'] )){
        update_post_meta( $post->ID, 'wt_meta_available_test_tasc_state_policy_link', esc_url_raw($_POST['wt_meta_available_test_tasc_state_policy_link']) );
    }

    if ( isset( $_POST['wt_meta_available_test_general_state_link'] )){
        update_post_meta( $post->ID, 'wt_meta_available_test_general_state_link', esc_url_raw($_POST['wt_meta_available_test_general_state_link']) );
    }

    if ( isset( $_POST['wt_meta_financial_aid_scholarships_for_ged'] )){
        update_post_meta( $post->ID, 'wt_meta_financial_aid_scholarships_for_ged', $_POST['wt_meta_financial_aid_scholarships_for_ged'] );
    }

    if ( isset( $_POST['wt_meta_school_districts_description'] )){
        update_post_meta( $post->ID, 'wt_meta_school_districts_description', $_POST['wt_meta_school_districts_description'] );
    }

    if ( isset( $_POST['wt_meta_alternative_programs_link_1'] )){
        update_post_meta( $post->ID, 'wt_meta_alternative_programs_link_1', esc_url_raw($_POST['wt_meta_alternative_programs_link_1']) );
    }

    if ( isset( $_POST['wt_meta_alternative_programs_link_2'] )){
        update_post_meta( $post->ID, 'wt_meta_alternative_programs_link_2', esc_url_raw($_POST['wt_meta_alternative_programs_link_2']) );
    }

    if ( isset( $_POST['wt_meta_school_districts_link_1'] )){
        update_post_meta( $post->ID, 'wt_meta_school_districts_link_1', esc_url_raw($_POST['wt_meta_school_districts_link_1']) );
    }

    if ( isset( $_POST['wt_meta_school_districts_link_2'] )){
        update_post_meta( $post->ID, 'wt_meta_school_districts_link_2', esc_url_raw($_POST['wt_meta_school_districts_link_2']) );
    }

    if ( isset( $_POST['wt_meta_community_colleges_description'] )){
        update_post_meta( $post->ID, 'wt_meta_community_colleges_description', $_POST['wt_meta_community_colleges_description'] );
    }

    if ( isset( $_POST['wt_meta_community_colleges_link_1'] )){
        update_post_meta( $post->ID, 'wt_meta_community_colleges_link_1', esc_url_raw($_POST['wt_meta_community_colleges_link_1']) );
    }

    if ( isset( $_POST['wt_meta_community_colleges_link_2'] )){
        update_post_meta( $post->ID, 'wt_meta_community_colleges_link_2', esc_url_raw($_POST['wt_meta_community_colleges_link_2']) );
    }

    if ( isset( $_POST['wt_meta_unemployment_offices_description'] )){
        update_post_meta( $post->ID, 'wt_meta_unemployment_offices_description', $_POST['wt_meta_unemployment_offices_description'] );
    }

    if ( isset( $_POST['wt_meta_unemployment_offices_link_1'] )){
        update_post_meta( $post->ID, 'wt_meta_unemployment_offices_link_1', esc_url_raw($_POST['wt_meta_unemployment_offices_link_1']) );
    }

    if ( isset( $_POST['wt_meta_unemployment_offices_link_2'] )){
        update_post_meta( $post->ID, 'wt_meta_unemployment_offices_link_2', esc_url_raw($_POST['wt_meta_unemployment_offices_link_2']) );
    }

}
add_action( 'save_post', 'wt_post_meta_save_settings' );