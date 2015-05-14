jQuery(document).ready(function($){
	
	var fp_uploader;
	
	$('.field').on('click','.upload_image_button',function(e){
		e.preventDefault();
		
		form_field = jQuery(this).prev('input');
		
		if (fp_uploader) {
            fp_uploader.open();
            return;
        }
		
		fp_uploader = wp.media.frames.file_frame = wp.media({
			library: {
				type: 'image'
			},
			multiple: false
		});
	
		
		fp_uploader.on('select', function() {
			attachment = fp_uploader.state().get('selection').first().toJSON();
				var thumbnail_url = attachment.url;
				
				if (thumbnail_url != ''){
					form_field.val(thumbnail_url);
				}
		});
	
		fp_uploader.open();
		
	});	
	
	
});