jQuery(document).ready(function($){
   
    //init Thickbox
    
    ////stop the flash from happening
	$('#TB_window').css('opacity',0);
	
	function calcTB_Pos() {
		$('#TB_window').css({
	   	   'height': ($('#TB_ajaxContent').outerHeight()) + 'px',
	   	   'top' : (($(window).height() + $(window).scrollTop())/2 - (($('#TB_ajaxContent').outerHeight()-$(window).scrollTop()) + 30)/2) + 'px',
	   	   'opacity' : 1,
                   'width' : '815px',
                   'margin-left': '-407px'
		});
	}
	
	setTimeout(calcTB_Pos,10);
	
	//just in case..
	setTimeout(calcTB_Pos,100);
	
	$(window).resize(calcTB_Pos);
        
    var ed = tinyMCE.activeEditor;
	
	//events
    $('#add-shortcode').click(function(){

        var sc_name = $(this).parent().children('#shortcode-name').val();

        if( sc_name == 'tabs' ){

            ed.selection.setContent( display_tabs() );

            tb_remove();

            return true;
        }

        if( sc_name == 'testimonials' ){

            ed.selection.setContent( display_testimonials() );

            tb_remove();

            return true;
        }

        if( sc_name == 'carousel_testimonials' ){

            $shortcode = $(this);

            ed.selection.setContent( display_carousel_testimonials( $shortcode ) );

            tb_remove();

            return true;
        }

        if( sc_name == 'contact_details_slider' ){

            $shortcode = $(this);

            ed.selection.setContent( display_contact_details_slider() );

            tb_remove();

            return true;
        }

    	var code = '[' + sc_name;
        
        //input loop for extra attrs
        $(this).parent().children('#options-group').find('.attr').each( function(){

            code += ' ' + $(this).attr('data-attrname') + '="' + $(this).val() + '"';

        });

	    code += ']';

        // if the shortcode has content
        if( $(this).parent().children('#shortcode-content').length > 0 ){

            code += $(this).parent().find('#shortcode_content').val();

            code += '[/' + sc_name + ']';
        }

        //if the shortcode has subitems
        if( $(this).parent().children('.shortcode-dynamic-items').length > 0 ){

            code += dynamic_items();

            code += '[/' + sc_name + ']';
        }

    	ed.selection.setContent( code );
			
	    tb_remove();
		
	    return true;
        
    });

    function dynamic_items(){

        var code = '';

        $('.shortcode-dynamic-items').each( function(){

            $(this).children('.shortcode-dynamic-item').each( function(){

                var sc_name = $(this).children('#shortcode-name').val();

                code += '[' + sc_name;

                //input loop for extra attrs
                $(this).children('#options-group').find('.attr').each( function(){

                    code += ' ' + $(this).attr('data-attrname') + '="' + $(this).val() + '"';

                });

                code += ']';

                // if the shortcode has content
                if( $(this).find('#shortcode-content').length > 0 ){

                    code += $(this).find('#shortcode_content').val();

                    code += '[/' + sc_name + ']';
                }

            })
        });

        return code;
    }

    function display_contact_details_slider(){

        var code           = '[contact_details_slider]';
        var slides_info    = '[contact_details_info_slides]';
        var slides_icons   = '[contact_details_icon_slides]';
        var slide_index    = 1;

        $('.shortcode-dynamic-items').each( function(){

            $(this).children('.shortcode-dynamic-item').each( function(){

                var slide_info   = '[contact_details_info_slide index="' + slide_index + '" ';
                var slide_icons  = '[contact_details_icon_slide index="' + slide_index + '" ';

                slide_index++;

                //input loop for extra attrs
                $(this).children('#options-group').find('.attr').each( function(){

                    if( $(this).attr('data-attrname') == 'icon' ){

                        slide_icons += 'icon' + '="' + $(this).val() + '" ';
                    }
                    if( $(this).attr('data-attrname') == 'title' ){

                        slide_info += 'title' + '="' + $(this).val() + '" ';
                    }
                });

                slide_icons     += ']';
                slide_info      += ']';

                // if the shortcode has content
                if( $(this).find('#shortcode-content').length > 0 ){

                    slide_info  += $(this).find('#shortcode_content').val();

                }

                slide_info      += '[/contact_details_info_slide]';

                slides_info     += slide_info;
                slides_icons    += slide_icons;

            })
        });

        slides_info      += '[/contact_details_info_slides]';
        slides_icons     += '[/contact_details_icon_slides]'
        code             += slides_info + slides_icons + '[/contact_details_slider]';

        return code;
    }

    function display_testimonials(){

        var code           = '[clients]';
        var headers        = '[client_headers]';
        var testimonials   = '[client_testimonials]';
        var client_index   = 0;

        $('.shortcode-dynamic-items').each( function(){

            $(this).children('.shortcode-dynamic-item').each( function(){

                var client_header   = '[client_header index="' + client_index + '" ';
                var testimonial     = '[client_testimonial ';

                client_index++;

                //input loop for extra attrs
                $(this).children('#options-group').find('.attr').each( function(){

                    if( $(this).attr('data-attrname') == 'logo' ){

                        client_header += 'logo' + '="' + $(this).val() + '" ';
                    }
                    if( $(this).attr('data-attrname') == 'url' ){

                        client_header += 'url' + '="' + $(this).val() + '" ';
                    }
                    if( $(this).attr('data-attrname') == 'name' ){

                        testimonial += 'name' + '="' + $(this).val() + '" ';
                    }
                    if( $(this).attr('data-attrname') == 'company' ){

                        testimonial += 'company' + '="' + $(this).val() + '" ';
                    }

                });

                client_header   += ']';
                testimonial     += ']';

                // if the shortcode has content
                if( $(this).find('#shortcode-content').length > 0 ){

                    testimonial += $(this).find('#shortcode_content').val();

                }

                testimonial     += '[/client_testimonial]';

                headers         += client_header;
                testimonials    += testimonial;

            })
        });

        headers         += '[/client_headers]';
        testimonials    += '[/client_testimonials]'
        code            += headers + testimonials + '[/clients]';

        return code;
    }

    function display_carousel_testimonials( $shortcode ){

        var code           = '[carousel_clients';

        //input loop for extra attrs
        $shortcode.parent().children('#options-group').find('.attr').each( function(){

            code += ' ' + $(this).attr('data-attrname') + '="' + $(this).val() + '"';

        });

        code += ']';

        var headers        = '[carousel_client_headers]';
        var testimonials   = '[carousel_client_testimonials]';
        var client_index   = 0;

        $('.shortcode-dynamic-items').each( function(){

            $(this).children('.shortcode-dynamic-item').each( function(){

                var client_header   = '[carousel_client_header index="' + client_index + '" ';
                var testimonial     = '[carousel_client_testimonial ';

                client_index++;

                //input loop for extra attrs
                $(this).children('#options-group').find('.attr').each( function(){

                    if( $(this).attr('data-attrname') == 'image_url' ){

                        client_header += 'image_url' + '="' + $(this).val() + '" ';
                    }
                    if( $(this).attr('data-attrname') == 'name' ){

                        testimonial += 'name' + '="' + $(this).val() + '" ';
                    }
                });

                client_header   += ']';
                testimonial     += ']';

                // if the shortcode has content
                if( $(this).find('#shortcode-content').length > 0 ){

                    testimonial += $(this).find('#shortcode_content').val();

                }

                testimonial     += '[/carousel_client_testimonial]';

                headers         += client_header;
                testimonials    += testimonial;

            })
        });

        headers         += '[/carousel_client_headers]';
        testimonials    += '[/carousel_client_testimonials]'
        code            += headers + testimonials + '[/carousel_clients]';

        return code;
    }

    function display_tabs(){

        var code        = '[tabs ';
        var tab_names   = '';
        var tabs        = '';
        var tab_index   = 0;

        $('.shortcode-dynamic-items').each( function(){

            $(this).children('.shortcode-dynamic-item').each( function(){

                tab_index++;

                tabs += '[tab id=' + tab_index + ']';

                //input loop for extra attrs
                $(this).children('#options-group').find('.attr').each( function(){

                    if( $(this).attr('data-attrname') == 'tab_name' ){

                        tab_names += 'tab' + tab_index + '="' + $(this).val() + '" ';
                    }

                });

                // if the shortcode has content
                if( $(this).find('#shortcode-content').length > 0 ){

                    tabs += $(this).find('#shortcode_content').val();

                }

                tabs += '[/tab]';

            })
        });

        code += tab_names + ']' + tabs + '[/tabs]';

        return code;
    }

    var iconbutton = '',
        iconinput  = '';

    $(document).on("click", ".display-icons-list", function(event){

        event.preventDefault();

        iconbutton = $(this),
        iconinput  = $(this).siblings('input:text').first();

        $('#fontawesome-icon-list').fadeIn();

    });

    // icon selection
    $('.icon-option i').click(function(){

        $(iconinput).val( $(this).attr('class') );

        $('#fontawesome-icon-list').fadeOut();
    });

    $('#close-fontawesome-list').click(function(){

        $('#fontawesome-icon-list').fadeOut();

    })

    ////Dynamic item events
    $('.add-list-item').click(function(){

        if(!$(this).parent().find('.remove-list-item').is(':visible')) $(this).parent().find('.remove-list-item').show();

        //clone item
        var $clone = $(this).parent().find('.shortcode-dynamic-item:first').clone();
        $clone.find('input[type=text],textarea').attr('value','');

        //append clone
        $(this).prevAll('.shortcode-dynamic-items').append($clone);

        return false;
    });

    $('.remove-list-item').live('click', function(){
        if($(this).parent().find('.shortcode-dynamic-item').length > 1){

            $(this).parent().find('#options-item .shortcode-dynamic-item:last').remove();

        }
        if($(this).parent().find('.shortcode-dynamic-item').length == 1) $(this).hide();


        return false;
    });

    //hide remove btn to start
    $('.remove-list-item').hide();

});