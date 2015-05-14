<?php
/**
 * WellThemes Category Settings
 * 
 * @package  WellThemes
 * @file     meta_category.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */ 
 
add_action( 'category_add_form_fields', 'wt_category_meta_add_field'  );
add_action( 'edit_category_form_fields', 'wt_category_meta_add_field' );
add_action( 'edited_category', 'wt_category_meta_save_field' );
add_action( 'created_category', 'wt_category_meta_save_field' );
		

function wt_category_meta_add_field( $cat ) {	
		
		$saved_color = "";
		if( isset($cat->term_id) ){
			$t_id = $cat->term_id;	
			$wt_category_meta = get_option( 'wt_category_meta_color_' . $t_id);		
			$saved_color = $wt_category_meta['wt_cat_meta_color'];				
		}		
		?>
		
		<table class="form-table">
			<h4><?php _e( 'Wellthemes Category Settings', 'wellthemes'); ?></h4>
			<tbody>
				
				<tr class="form-field">
					<th scope="row" valign="top"><label for="wt_cat_meta_color"><?php _e( 'Category Color', 'wellthemes' ); ?></label></th>			
					<td>
						<div id="wt_cat_color_selector" class="color-pic"><div style="background-color:<?php echo $saved_color; ?>"></div></div>
						<input style="width:80px; margin-right:5px;"  name="wt_category_meta[wt_cat_meta_color]" id="wt_cat_meta_color" type="text" value="<?php echo $saved_color; ?>" />
											
					<p class="description"><?php _e( 'Select color for the category', 'wellthemes'); ?></p></td>
				</tr>
						
			<tbody>
		</table>
		<?php 
}
	
function wt_category_meta_save_field( $term_id ) {	    
		if ( isset( $_POST['wt_category_meta'] ) ) {			
			$t_id = $term_id;		 
		  	$wt_category_meta = get_option( "wt_category_meta_color_$t_id" );		  
		  	$cat_keys = array_keys($_POST['wt_category_meta']);		 
		    foreach ($cat_keys as $key){		    
		      	if (isset($_POST['wt_category_meta'][$key])){		          
		         	 $wt_category_meta[$key] = $_POST['wt_category_meta'][$key];		      
		      	} else {			   		
			   		unset($wt_category_meta[$key]);				
				}		 
		  	}		  
			update_option( 'wt_category_meta_color_' . $t_id, $wt_category_meta );		
		} else {			
			delete_option( 'wt_category_meta_color_' . $term_id );		
		}	
	}