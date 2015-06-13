jQuery(document).ready(function($){
	
	
	if ($('#wt_meta_enable_review').is(':checked')) {		
		$('#wt-post-meta-review-options').css('display', 'block');
	}

	$('#wt_meta_enable_review').click(function(){
		if (this.checked) {
		
			$('#wt-post-meta-review-options').slideDown();
		} else {
			$('#wt-post-meta-review-options').slideUp();
		}
	});	

	$('#wt_cat_color_selector').ColorPicker({								
		onChange: function (hsb, hex, rgb) {
				$('#wt_cat_color_selector div').css('backgroundColor', '#' + hex);
				$('#wt_cat_meta_color').val('#'+hex);
		}
	});
	
	$('#wt_meta_feat_cat1_title_color_selector').ColorPicker({								
		onChange: function (hsb, hex, rgb) {
				$('#wt_meta_feat_cat1_title_color_selector div').css('backgroundColor', '#' + hex);
				$('#wt_meta_feat_cat1_title_color').val('#'+hex);
		}
	});
	
	
	
	
});