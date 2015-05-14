jQuery(document).ready(function($){
	

/*----------------------------------------------------------------------------------*/
/*	Display post format meta boxes as needed
/*----------------------------------------------------------------------------------*/
	
	
	$('#post-formats-select input').change(checkFormat);
	
	function checkFormat(){
		var format = $('#post-formats-select input:checked').attr('value');
		
		//only run on the posts page
		if(typeof format != 'undefined'){
			
			$('#post-body div[id^=newave_blog_options_]').hide();
			$('#post-body #newave_blog_options_'+format+'').stop(true,true).fadeIn(500);
					
		}
	
	}
	
	$('#newave_page_section').change(checkSection);
	
	function checkSection(){
		var format = $('#newave_page_section option:selected').attr('value');
		
		//only run on the posts page
		if(typeof format != 'undefined'){
			
			$('#post-body div[id^=newave_page_options_]').hide();
			$('#post-body #newave_page_options_'+format+'').stop(true,true).fadeIn(500);
					
		}
	
	}
	
	$(window).load(function(){
		
		checkFormat();
		
		checkSection();
		
	})
		
    
});


