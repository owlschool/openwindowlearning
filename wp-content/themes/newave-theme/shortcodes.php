<?php

//////////////////////////////////////////////////////////////////
// Helper Functions
//////////////////////////////////////////////////////////////////
function translate_fx_effect( $shortcode_option ){

    switch( $shortcode_option ){

        case 'fade':
            return 'element_fade_in';
        case 'fade-from-left':
            return 'element_from_left';
        case 'fade-from-right':
            return 'element_from_right';
        case 'none':
            return '';
    }

    return '';
}


//////////////////////////////////////////////////////////////////
// MEDIA SHORTCODES
//////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////
// Youtube shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('youtube', 'shortcode_youtube');
	function shortcode_youtube($atts) {
		$atts = shortcode_atts(
			array(
				'id' => '',
				'width' => 600,
				'height' => 360
			), $atts);

			return '<div class="video-wrapper"><div class="video-container"><iframe title="YouTube video player" width="' . $atts['width'] . '" height="' . $atts['height'] . '" src="http://www.youtube.com/embed/' . $atts['id'] . '" frameborder="0" allowfullscreen></iframe></div></div>';
	}
	
//////////////////////////////////////////////////////////////////
// Vimeo shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('vimeo', 'shortcode_vimeo');
	function shortcode_vimeo($atts) {
		$atts = shortcode_atts(
			array(
				'id' => '',
				'width' => 600,
				'height' => 360
			), $atts);
		
			return '<div class="video-wrapper"><div class="video-container"><iframe src="http://player.vimeo.com/video/' . $atts['id'] . '" width="' . $atts['width'] . '" height="' . $atts['height'] . '" frameborder="0"></iframe></div></div>';
	}
	


//////////////////////////////////////////////////////////////////
// WEB ELEMENTS SHORTCODES
//////////////////////////////////////////////////////////////////	
	
	
//////////////////////////////////////////////////////////////////
// Button shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('button', 'shortcode_button');
function shortcode_button($atts, $content = null) {

    extract(shortcode_atts(array(
        'link'      => '',
        'target'    => '_blank',
        'size'      => 'small',
        'outline'   => 'no',
        'color'     => 'black',
        'shape'     => 'square',
		'move'     	=> 'no',
		'external'  => 'no',
    ), $atts));

    $outline = 'grey';
    if( $atts['outline'] == 'yes' )
        $outline = 'outline';

    $shape = '';
    if( $atts['shape'] == 'rounded' )
        $shape = 'rounded';
		
	$external = '';
    if( $atts['external'] == 'yes' )
        $external = 'external';

    $move = '';
    if( $atts['move'] == 'yes' )
        $move = 'move';	

	return '<a class="newave-button ' . $atts['size'] . ' ' . $shape . ' ' . $outline . ' ' . $move . ' ' . $external . ' '. $color . '" href="' . $atts['link'] . '" target="' . $atts['target'] . '">' .do_shortcode($content). '</a>';
}


//////////////////////////////////////////////////////////////////
// Tabs shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('tabs', 'shortcode_tabs');
function shortcode_tabs( $atts, $content = null ) {

	extract(shortcode_atts(array(
    ), $atts));
    
    $out = '';

    $out .= '<div class="tab-shortcode">';

    $out .= '<ul class="clearfix tabs">';
    $first = true;
    foreach ($atts as $key => $tab) {
		$out .= '<li><a href="#' . $key . '">' . $tab . '</a></li>';
		$first = false;
    }
    $out .= '</ul>';
	
    $out .= do_shortcode( $content );
	
    $out .= '</div>';
        
    return $out;
}


add_shortcode('tab', 'shortcode_tab');
function shortcode_tab( $atts, $content = null ) {

	extract(shortcode_atts(array(
    ), $atts));
    
	$out = '';
	$out .= '<div id="tab' . $atts['id'] . '" class="tab_container">' . do_shortcode($content) .'</div>';
		
	return $out;
}

//////////////////////////////////////////////////////////////////
// Toggle shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('toggle', 'shortcode_toggle');
function shortcode_toggle( $atts, $content = null ) {
	
	extract(shortcode_atts(array(
        'title'      => ''
    ), $atts));

    
	$html = '<div class="toggle-wrap"> ';
	
	$html .= '<span class="toggle-title"><a href="#">' . $title . '</a></span>';
	$html .= '<div class="toggle_container">' . do_shortcode( $content ) . '</div>';            
        
    $html .= '</div>';
	
    return $html;
}


//////////////////////////////////////////////////////////////////
// Alert Message
//////////////////////////////////////////////////////////////////
add_shortcode('alert', 'shortcode_alert');
function shortcode_alert($atts, $content = null) {
	
	$box_type = '';
	switch( $atts['color'] ){
	case 'red':
		$box_type = 'red';
		break;
	case 'blue':
		$box_type = 'blue';
		break;
	case 'yellow':
		$box_type = 'yellow';
		break;
	case 'green':
        $box_type = 'green';
        break;
	default:
		$box_type = 'red';
		break;
	}
	
	$out  = '';
	$out .= '<div class="alertboxes">';
	$out .= '<div class="shortcode_alertbox box_'. $box_type .'">';
	$out .= do_shortcode($content);
	$out .= '<a class="box_close"></a>';
	$out .= '</div>';
    $out .= '</div>';

	return $out;
}

//////////////////////////////////////////////////////////////////
// List styles
//////////////////////////////////////////////////////////////////
add_shortcode('list_styles', 'shortcode_list_styles');
function shortcode_list_styles($atts, $content = null) {

    extract(shortcode_atts(array(
        'size'      => ''
    ), $atts));

    $list_class = '';
    if( $atts['size'] == 'big' ){
        $list_class = 'big-list';
    }

    $out  = '';
    $out .= '<ul class="fa-ul ' . $list_class . '">';
    $out .= do_shortcode($content);
    $out .= '</ul>';

    return $out;
}

add_shortcode('list_style', 'shortcode_list_style');
function shortcode_list_style($atts, $content = null) {
	
	$out  = '';
	$out .= '<li>';
    $out .= '<i class="fa-li ' . $atts['icon'] . '">';
    $out .= '</i>';
    $out .= do_shortcode($content);
    $out .= '</li>';

	return $out;
}


//////////////////////////////////////////////////////////////////
// Clients
//////////////////////////////////////////////////////////////////
add_shortcode('clients', 'shortcode_clients');
function shortcode_clients($atts, $content = null) {

    $out = '<!-- Clients Slider -->';
    $out .= do_shortcode($content);
    $out .= '<!-- Clients Slider -->';

    return $out;
}

add_shortcode('client_headers', 'shortcode_client_headers');
function shortcode_client_headers($atts, $content = null) {

    $out = '<div id="bx-pager">';
    $out .= do_shortcode( $content );
    $out .= '</div>';

    return $out;
}

add_shortcode('client_header', 'shortcode_client_header');
function shortcode_client_header($atts, $content = null) {

    $atts = shortcode_atts(
            array(
                'index' => '0',
                'logo'  => ''
            ), $atts);

    $out = '<a data-slide-index="' . $atts['index'] . '" href=""><img src="' . $atts['logo'] . '" alt="" /></a>';

	return $out;
}

add_shortcode('client_testimonials', 'shortcode_client_testimonials');
function shortcode_client_testimonials($atts, $content = null) {

    $out = '<ul class="clients-slider">';
    $out .= do_shortcode( $content );
    $out .= '</ul>';

    return $out;
}

add_shortcode('client_testimonial', 'shortcode_client_testimonial');
function shortcode_client_testimonial($atts, $content = null) {

    $atts = shortcode_atts(
        array(
            'name' => '0',
            'company'  => ''
        ), $atts);

    $out = '';
    $out .= '<li>';
    $out .= '<h3>';
    $out .= do_shortcode($content);
    $out .= '</h3>';

    $out .= '<p>- ' . $atts['name'] . ', ' . $atts['company'] . ' -</p>';

    $out .= '</li>';

    return $out;
}


//////////////////////////////////////////////////////////////////
// Carousel Clients
//////////////////////////////////////////////////////////////////
add_shortcode('carousel_clients', 'shortcode_carousel_clients');
function shortcode_carousel_clients($atts, $content = null) {

    extract(shortcode_atts(array(
        'dark_bknd'  => 'no'
    ), $atts));

    $class_bknd = '';
    if( $dark_bknd == 'yes' ){

        $class_bknd = 'dark-bg-client';
    }

    $out = '<!-- Clients Slider -->';
    $out .= '<div class="new-client-slider ' . $class_bknd . '">';
    $out .= do_shortcode($content);
    $out .= '<div class="new-client-nav"></div>';
    $out .= '</div>';
    $out .= '<!-- Clients Slider -->';

    return $out;
}

add_shortcode('carousel_client_headers', 'shortcode_carousel_client_headers');
function shortcode_carousel_client_headers($atts, $content = null) {

    $out = '<div class="new-client-slider-image">';
    $out .= '<ul class="slides">';
    $out .= do_shortcode( $content );
    $out .= '</ul>';
    $out .= '</div>';

    return $out;
}

add_shortcode('carousel_client_header', 'shortcode_carousel_client_header');
function shortcode_carousel_client_header($atts, $content = null) {

    extract( shortcode_atts(
        array(
            'image_url' => '0'
        ), $atts) );

    $out = '<li style="background-image:url(' . $image_url . ')"></li>';

    return $out;
}

add_shortcode('carousel_client_testimonials', 'shortcode_carousel_client_testimonials');
function shortcode_carousel_client_testimonials($atts, $content = null) {

    $out = '<div class="new-client-slider-info">';
    $out .= '<ul class="slides">';
    $out .= do_shortcode( $content );
    $out .= '</ul>';
    $out .= '</div>';

    return $out;
}

add_shortcode('carousel_client_testimonial', 'shortcode_carousel_client_testimonial');
function shortcode_carousel_client_testimonial($atts, $content = null) {

    extract( shortcode_atts(
        array(
            'name' => ''
        ), $atts) );

    $out = '';
    $out .= '<li>';
    $out .= '<h3>' . $name . '</h3>';
    $out .= '<p>' . do_shortcode($content) . '</p>';
    $out .= '</li>';

    return $out;
}

//////////////////////////////////////////////////////////////////
// Contact Details Slider
//////////////////////////////////////////////////////////////////
add_shortcode('contact_details_slider', 'shortcode_contact_details_slider');
function shortcode_contact_details_slider($atts, $content = null) {

    $out = '';

    $out .= do_shortcode( $content );

    return $out;
}

add_shortcode('contact_details_info_slides', 'shortcode_contact_details_info_slides');
function shortcode_contact_details_info_slides($atts, $content = null) {

    $out = '';

    $out .= '<ul class="contact-details-slider">';
    $out .= do_shortcode( $content );
    $out .= '</ul>';

    return $out;
}

add_shortcode('contact_details_info_slide', 'shortcode_contact_details_info_slide');
function shortcode_contact_details_info_slide($atts, $content = null) {

    extract( shortcode_atts(
        array(
            'index' => '1',
            'title' => ''
        ), $atts) );

    $out = '';

    $out .= '<li id="c' . $index . '">';
    $out .= '<h5>' . $title . '</h5>';
    $out .= '<h1>' . do_shortcode( $content ) . '</h1>';
    $out .= '</li>';

    return $out;
}

add_shortcode('contact_details_icon_slides', 'shortcode_contact_details_icon_slides');
function shortcode_contact_details_icon_slides($atts, $content = null) {

    $out = '';

    $out .= '<div class="contact-details-nav">';
    $out .= '<ul class="contact-icons-slider">';
    $out .= do_shortcode( $content );
    $out .= '</ul>';
    $out .= '</div>';

    return $out;
}

add_shortcode('contact_details_icon_slide', 'shortcode_contact_details_icon_slide');
function shortcode_contact_details_icon_slide($atts, $content = null) {

    extract( shortcode_atts(
        array(
            'index' => '1',
            'icon' => ''
        ), $atts) );

    $out = '';

    if( $index == '1' ){
        $out .= '<li class="c' . $index . ' active-icon">';
    }
    else{
        $out .= '<li class="c' . $index . '">';
    }
    $out .= '<i class="' . $icon . '"></i>';
    $out .= '</li>';

    return $out;
}


//////////////////////////////////////////////////////////////////
// Tagline box shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('tagline_box', 'shortcode_tagline_box');
function shortcode_tagline_box($atts, $content = null) {

    $out = '';

    $out .= '<h4 class="call-action">' . do_shortcode( $content );
    if($atts['link'] && $atts['button']){

        $out .= '<a href="'.$atts['link'].'" target="' . $atts['target'] . '" class="newave-button medium grey move">'.$atts['button'].'</a>';
    }

    $out .= '</h4>';

    return $out;
}

//////////////////////////////////////////////////////////////////
// Team
//////////////////////////////////////////////////////////////////
add_shortcode('team', 'shortcode_team');
function shortcode_team($atts, $content = null) {

    $out = '';
    $out .= '<ul class="our-team">';
    $out .= do_shortcode( $content );
    $out .= '</ul>';

    return $out;
}

add_shortcode('team_member', 'shortcode_team_member');
function shortcode_team_member($atts, $content = null) {
	
	$out = '';

    $out .= '<li class="team-person ' . translate_fx_effect( $atts['fx_effect'] ) . '">';

    if($atts['picture']){
	    $out .= '<img src="' . $atts['picture'] . '" alt="" />';
    }

	$out .= '<div class="team-profile">';

	$out .= '<h5>'.$atts['name'].'</h5>';
	$out .= '<p>' . $atts['title'] . '</p>';
	$out .= '<p class="about-team">' . substr( $content, 0, 100 ) . '</p>';

    $out .= '<ul class="socials-icons">';
	if($atts['social_link1'] && $atts['social_link1_url'] ) {
		$out .= '<li><a target="_blank" href="'. $atts['social_link1_url'] .'"><img alt="" src="'.get_template_directory_uri().'/images/social_icons/' . $atts['social_link1'] . '.png"></a></li>';
	}
	if($atts['social_link2'] && $atts['social_link2_url']) {
		$out .= '<li><a target="_blank" href="'. $atts['social_link2_url'] .'"><img alt="" src="'.get_template_directory_uri().'/images/social_icons/' . $atts['social_link2'] . '.png"></a></li>';
	}
	if($atts['social_link3'] && $atts['social_link3_url']) {
		$out .= '<li><a target="_blank" href="'. $atts['social_link3_url'] .'"><img alt="" src="'.get_template_directory_uri().'/images/social_icons/' . $atts['social_link3'] . '.png"></a></li>';
	}
    $out .= '</ul>';

    $out .= '</div>'; // div class team profile

	$out .= '</li>'; // li team person

	return $out;
}


//////////////////////////////////////////////////////////////////
// Team Carousel
//////////////////////////////////////////////////////////////////
add_shortcode('team_carousel', 'shortcode_team_carousel');
function shortcode_team_carousel($atts, $content = null) {

    $out = '';
    $out .= '<ul class="our-team-new">';
    $out .= do_shortcode( $content );
    $out .= '</ul>';

    $out .= '<ul class="team-navigation">';
    $out .= '<li class="element_from_left"><a id="prev3" class="prev"><i class="fa fa-chevron-left"></i></a></li>';
    $out .= '<li class="element_from_right"><a id="next3" class="next"><i class="fa fa-chevron-right"></i></a></li>';
    $out .= '</ul>';

    return $out;
}

add_shortcode('team_member_carousel', 'shortcode_team_member_carousel');
function shortcode_team_member_carousel($atts, $content = null) {

    $out = '';

    $out .= '<li class="item team-person-new">';

    if($atts['picture']){
        $out .= '<img src="' . $atts['picture'] . '" alt="" />';
    }

    $out .= '<div class="team-profile-new">';

    $out .= '<h5>'.$atts['name'].'</h5>';
    $out .= '<p>' . $atts['title'] . '</p>';
    $out .= '<p class="about-team-new">' . substr( $content, 0, 100 ) . '</p>';

    $out .= '<ul class="socials-icons-new">';
    if($atts['social_link1'] && $atts['social_link1_url'] ) {
        $out .= '<li><a target="_blank" href="'. $atts['social_link1_url'] .'"><img alt="" src="'.get_template_directory_uri().'/images/social_icons/' . $atts['social_link1'] . '_white.png"></a></li>';
    }
    if($atts['social_link2'] && $atts['social_link2_url']) {
        $out .= '<li><a target="_blank" href="'. $atts['social_link2_url'] .'"><img alt="" src="'.get_template_directory_uri().'/images/social_icons/' . $atts['social_link2'] . '_white.png"></a></li>';
    }
    if($atts['social_link3'] && $atts['social_link3_url']) {
        $out .= '<li><a target="_blank" href="'. $atts['social_link3_url'] .'"><img alt="" src="'.get_template_directory_uri().'/images/social_icons/' . $atts['social_link3'] . '_white.png"></a></li>';
    }
    $out .= '</ul>';

    $out .= '</div>'; // div class team profile

    $out .= '</li>'; // li team person

    return $out;
}


//////////////////////////////////////////////////////////////////
// Progress Bar
//////////////////////////////////////////////////////////////////
add_shortcode('progress', 'shortcode_progress');
function shortcode_progress($atts, $content = null) {
	
	$html = '';
	$html .= '<div class="skills">';
	$html .= '<ul class="bar">';
	$html .= do_shortcode( $content );
	$html .= '</ul>';
	$html .= '</div>';
	
	return $html;
}

add_shortcode('progress_bar', 'shortcode_progress_bar');
function shortcode_progress_bar($atts, $content = null) {
	
	$percentage = '100';
	if( isset($atts['percentage']) )
		$percentage = $atts['percentage'];
		
	$html = '<li>';
	$html .= '<div class="bar-wrap"><span data-width="' . $percentage . '"><strong>' . $content . '</strong></span>';
	$html .= '</div>';
    $html .= '</li>';

	return $html;
}



//////////////////////////////////////////////////////////////////
// Typography
//////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////
// Title
//////////////////////////////////////////////////////////////////
add_shortcode('title', 'shortcode_title');
function shortcode_title($atts, $content = null) {
	$html = '';
	$html .= '<h'.$atts['size'].'>'.do_shortcode($content).'</h'.$atts['size'].'>';
	return $html;
}

//////////////////////////////////////////////////////////////////
// Small Title
//////////////////////////////////////////////////////////////////
add_shortcode('small_title', 'shortcode_small_title');
function shortcode_small_title($atts, $content = null) {

    extract(shortcode_atts(array(
        'header_text'      => ''
    ), $atts));

    $html = '<h5>' . $atts['header_text'] . '</h5>';
    $html .= '<div class="small-border"></div>';
    $html .= '<p>' . do_shortcode( $content ) . '</p>';

    return $html;
}

//////////////////////////////////////////////////////////////////
// Breaking line
//////////////////////////////////////////////////////////////////
add_shortcode('br', 'shortcode_br');
function shortcode_br($atts, $content = null) {

    return '<br />';
}

//////////////////////////////////////////////////////////////////
// Title Divider
//////////////////////////////////////////////////////////////////
add_shortcode('title_divider', 'shortcode_title_divider');
function shortcode_title_divider($atts, $content = null) {
	$html = '';
	$html .= '<h4 class="title-divider">'.do_shortcode($content).'</h4>';
	return $html;
}

//////////////////////////////////////////////////////////////////
// Dropcap shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('dropcap', 'shortcode_dropcap');
function shortcode_dropcap( $atts, $content = null ) {  
		
		$shape = '-normal';
		if( $atts['shape'] == 'round' ) $shape = ' round dark';
		if( $atts['shape'] == 'square' ) $shape = ' square dark';
		
        $first_letter = $atts['letter'];
        if( !$first_letter ) $first_letter = 'A';
		return '<p><span class="dropcap'. $shape . '">' . $first_letter . '</span>' . do_shortcode($content). '</p>';  
		
}

//////////////////////////////////////////////////////////////////
// Column one_half shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('one_half', 'shortcode_one_half');
	function shortcode_one_half($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'last' => 'no',
                'text_align' => 'text-align-left'
			), $atts);

            $class = $atts['text_align'];
            if( $atts['last'] == 'yes' ){

                $class .= ' last';
            }

			return '<div class="one_half ' . $class . '">' .do_shortcode($content). '</div>';

	}
	
//////////////////////////////////////////////////////////////////
// Column one_third shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('one_third', 'shortcode_one_third');
	function shortcode_one_third($atts, $content = null) {
        $atts = shortcode_atts(
            array(
                'last' => 'no',
                'text_align' => 'text-align-left'
            ), $atts);

        $class = $atts['text_align'];
        if( $atts['last'] == 'yes' ){

            $class .= ' last';
        }

        return '<div class="one_third ' . $class . '">' .do_shortcode($content). '</div>';

	}
	
//////////////////////////////////////////////////////////////////
// Column two_third shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('two_third', 'shortcode_two_third');
	function shortcode_two_third($atts, $content = null) {
        $atts = shortcode_atts(
            array(
                'last' => 'no',
                'text_align' => 'text-align-left'
            ), $atts);

        $class = $atts['text_align'];
        if( $atts['last'] == 'yes' ){

            $class .= ' last';
        }

        return '<div class="two_third ' . $class . '">' .do_shortcode($content). '</div>';

	}
	
//////////////////////////////////////////////////////////////////
// Column one_fourth shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('one_fourth', 'shortcode_one_fourth');
	function shortcode_one_fourth($atts, $content = null) {
        $atts = shortcode_atts(
            array(
                'last' => 'no',
                'text_align' => 'text-align-left'
            ), $atts);

        $class = $atts['text_align'];
        if( $atts['last'] == 'yes' ){

            $class .= ' last';
        }

        return '<div class="one_fourth ' . $class . '">' .do_shortcode($content). '</div>';

	}
	
//////////////////////////////////////////////////////////////////
// Column three_fourth shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('three_fourth', 'shortcode_three_fourth');
	function shortcode_three_fourth($atts, $content = null) {
        $atts = shortcode_atts(
            array(
                'last' => 'no',
                'text_align' => 'text-align-left'
            ), $atts);

        $class = $atts['text_align'];
        if( $atts['last'] == 'yes' ){

            $class .= ' last';
        }

        return '<div class="three_fourth ' . $class . '">' .do_shortcode($content). '</div>';

	}

//////////////////////////////////////////////////////////////////
// Column one fifth shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('one_fifth', 'shortcode_one_fifth');
	function shortcode_one_fifth($atts, $content = null) {
        $atts = shortcode_atts(
            array(
                'last' => 'no',
                'text_align' => 'text-align-left'
            ), $atts);

        $class = $atts['text_align'];
        if( $atts['last'] == 'yes' ){

            $class .= ' last';
        }

        return '<div class="one_fifth ' . $class . '">' .do_shortcode($content). '</div>';

	}

add_shortcode('two_fifth', 'shortcode_two_fifth');
function shortcode_two_fifth($atts, $content = null) {
    $atts = shortcode_atts(
        array(
            'last' => 'no',
            'text_align' => 'text-align-left'
        ), $atts);

    $class = $atts['text_align'];
    if( $atts['last'] == 'yes' ){

        $class .= ' last';
    }

    return '<div class="two_fifth ' . $class . '">' .do_shortcode($content). '</div>';

}

add_shortcode('three_fifth', 'shortcode_three_fifth');
function shortcode_three_fifth($atts, $content = null) {
    $atts = shortcode_atts(
        array(
            'last' => 'no',
            'text_align' => 'text-align-left'
        ), $atts);

    $class = $atts['text_align'];
    if( $atts['last'] == 'yes' ){

        $class .= ' last';
    }

    return '<div class="three_fifth ' . $class . '">' .do_shortcode($content). '</div>';

}

add_shortcode('four_fifth', 'shortcode_four_fifth');
function shortcode_four_fifth($atts, $content = null) {
    $atts = shortcode_atts(
        array(
            'last' => 'no',
            'text_align' => 'text-align-left'
        ), $atts);

    $class = $atts['text_align'];
    if( $atts['last'] == 'yes' ){

        $class .= ' last';
    }

    return '<div class="four_fifth ' . $class . '">' .do_shortcode($content). '</div>';

}

//////////////////////////////////////////////////////////////////
// Column one sixth shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('one_sixth', 'shortcode_one_sixth');
function shortcode_one_sixth($atts, $content = null) {
    $atts = shortcode_atts(
        array(
            'last' => 'no',
            'text_align' => 'text-align-left'
        ), $atts);

    $class = $atts['text_align'];
    if( $atts['last'] == 'yes' ){

        $class .= ' last';
    }

    return '<div class="one_sixth ' . $class . '">' .do_shortcode($content). '</div>';

}

//////////////////////////////////////////////////////////////////
// Column five sixth shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('five_sixth', 'shortcode_five_sixth');
function shortcode_five_sixth($atts, $content = null) {
    $atts = shortcode_atts(
        array(
            'last' => 'no',
            'text_align' => 'text-align-left'
        ), $atts);

    $class = $atts['text_align'];
    if( $atts['last'] == 'yes' ){

        $class .= ' last';
    }

    return '<div class="five_sixth ' . $class . '">' .do_shortcode($content). '</div>';

}


//////////////////////////////////////////////////////////////////
// Pricing tables
//////////////////////////////////////////////////////////////////	

//////////////////////////////////////////////////////////////////
// Pricing table
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_table', 'shortcode_pricing_table');
function shortcode_pricing_table($atts, $content = null) {

    $str = '';
	$str .= '<div class="pricing-tables">';
	$str .= do_shortcode($content);
	$str .= '</div>';

	return $str;
}

//////////////////////////////////////////////////////////////////
// Pricing Column
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_column', 'shortcode_pricing_column');
function shortcode_pricing_column($atts, $content = null) {

    $str = '<div class="price-table">';
    $str .= '<i class="' . $atts['icon'] . '"></i>';
    $str .= '<ul class="services-info">';
    $str .= '<li><h3>' . $atts['title'] . '</h3></li>';
    $str .= do_shortcode( $content );
    $str .= '</ul>';
    $str .= '</div>';

    return $str;
}

//////////////////////////////////////////////////////////////////
// Pricing Row
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_price', 'shortcode_pricing_price');
function shortcode_pricing_price($atts, $content = null) {
		
    $str = '';

    $str .= '<li class="pricetable-price"><h3>' . $atts['price'] . '</h3><span>' . $atts['time'] .'</span></li>';

    return $str;
}

//////////////////////////////////////////////////////////////////
// Pricing Row
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_row', 'shortcode_pricing_row');
function shortcode_pricing_row($atts, $content = null) {

	$str = '';
	$str .= '<li>';
	$str .= do_shortcode($content);
	$str .= '</li>';

	return $str;
}

//////////////////////////////////////////////////////////////////
// Pricing Footer
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_footer', 'shortcode_pricing_footer');
function shortcode_pricing_footer($atts, $content = null) {

    $str = '';
    $str .= '<li><a class="newave-button medium grey rounded" href="' . $atts['url'] . '" target="_blank">' . do_shortcode( $content ) . '</a></li>';

    return $str;

}
        
     
        
        
//////////////////////////////////////////////////////////////////
// Icons
//////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////
// Retina Icons
//////////////////////////////////////////////////////////////////
add_shortcode('fontawesome_icon', 'shortcode_fontawesome_icon');
function shortcode_fontawesome_icon($atts, $content = null) {

    extract(shortcode_atts(array(
        'size'                 => '1'
    ), $atts));

    $str = '';
        
    $icon_size = '';
    if( isset( $atts['size'] ) && $atts['size'] && ( $atts['size'] != 'none') ){
        $icon_size = "fa-" . $atts['size'];
    }

    $icone_shape = 'normal-icon';
    if( isset( $atts['shape'] ) && ( $atts['shape'] == 'circle-box') ){
        $icone_shape = 'icon-circle-box';
    } else if( isset( $atts['shape'] ) && ( $atts['shape'] == 'normal') ){
        $icone_shape = 'normal-icon';
    }

    $str .= '<i class="'. $atts['icon'] .' '. $icon_size . ' ' . $icone_shape .'"></i>';

	return $str;
}

//////////////////////////////////////////////////////////////////
// Social Icons
//////////////////////////////////////////////////////////////////
add_shortcode('social_icons', 'shortcode_social_icons');
function shortcode_social_icons($atts, $content = null) {

    $str = '';
    $str .= '<ul class="socials-icons">';
    $str .= do_shortcode( $content );
    $str .= '</ul>';

    return $str;
}

add_shortcode('social_icon', 'shortcode_social_icon');
function shortcode_social_icon($atts, $content = null) {

    $str = '';
    $str .= '<li>';
	$str .= '<a href="' . $atts['url'] . '" target="' . $atts['target'] . '"><img src="' . get_template_directory_uri() . '/images/social_icons/' . $atts['social'] . '.png' . '"></a>';
    $str .= '</li>';

	return $str;
}

//////////////////////////////////////////////////////////////////
// Recent Posts
//////////////////////////////////////////////////////////////////
add_shortcode('recent_posts', 'shortcode_recent_posts');
function shortcode_recent_posts($atts, $content = null) {

    extract(shortcode_atts(array(
        'posts'                 => '5',
        'post_title_length'     => '20'
    ), $atts));

    $str = "";

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $atts['posts']
    );

    $posts_query = new WP_Query( $args );

    while ( $posts_query->have_posts()) {

        $posts_query->the_post();

        $post_title = get_the_title();
        if( strlen( $post_title ) > $atts['post_title_length'] ){

            $post_title = substr( $post_title, 0, $atts['post_title_length'] );

            $post_title .= "[...]";
        }

        switch( get_post_format() ){

            case 'audio':
                $image_url = get_template_directory_uri() . '/images/blog-audio.png';
                break;
            case 'gallery':
                $image_url = get_template_directory_uri() . '/images/blog-gallery.png';
                break;
            case 'link':
                $image_url = get_template_directory_uri() . '/images/blog-link.png';
                break;
            case 'quote':
                $image_url = get_template_directory_uri() . '/images/blog-quote.png';
                break;
            case 'video':
                $image_url = get_template_directory_uri() . '/images/blog-video.png';
                break;
            default:
                if( has_post_thumbnail( get_the_ID() ) ){
                    $image_url = get_template_directory_uri() . '/images/blog-image.png';
                }
                else{
                    $image_url = get_template_directory_uri() . '/images/blog-text.png';
                }
        }

        $str .= '<div class="recent-post">';
        $str .= '<p class="recent-post-date">' . get_the_time('j F Y') . '</p>';
        $str .= '<div class="blog-type">';
        $str .= '<img alt src="' . $image_url . '">';
        $str .= '</div>';
        $str .= '<h5 class="blog-title">';
        $str .= '<a class="external" href="' . get_permalink() . '">' . $post_title . '</a>';
        $str .= '</h5>';

        $categories     = get_the_category();
        $separator      = ' · ';
        $str_categories = '';
        if($categories){

            foreach($categories as $category) {
                $str_categories .= '<a href="'.get_category_link( $category->term_id ).'">'.$category->cat_name.'</a>'.$separator;
            }

            $str_categories = trim($str_categories, $separator);
        }

        $author_id = get_the_author_meta("ID");
        $author_url = get_author_posts_url( $author_id );

        $str .= '<p class="blog-meta">Posted by <a href="' . $author_url . '">' . get_the_author() . '</a> | ' . $str_categories . ' | <a href="' . get_comments_link() . '"> ' . get_comments_number() . ' comment(s) </a></p>';
        $str .= '</div>';
    }

    wp_reset_query();

    return $str;
}

//////////////////////////////////////////////////////////////////
// Show Three Blog Posts
//////////////////////////////////////////////////////////////////
add_shortcode('show_three_blog_posts', 'shortcode_show_three_blog_posts');
function shortcode_show_three_blog_posts($atts, $content = null) {

	extract(shortcode_atts(array(
        'order'                 => 'latest'
    ), $atts));
	
	if( $order == 'most_commented' ){
	
		$posts_query = new WP_Query( array(
									'post_type' => 'post',
									'post_status' => 'publish',
									'posts_per_page' => 3,
									'orderby' => 'comment_count'
							) );
	}
	else{
	
		$posts_query = new WP_Query( array(
									'post_type' => 'post',
									'post_status' => 'publish',
									'posts_per_page' => 3
							) );
	}
	
	echo '<div class="three_posts">';
	
	while ( $posts_query->have_posts()) {

        $posts_query->the_post();
		
	    get_template_part( 'blog-post-format/blog-post', get_post_format() );
		
	}
	
	echo '</div>';
	
	wp_reset_query();

    return $str;
}


//////////////////////////////////////////////////////////////////
// Image Group
//////////////////////////////////////////////////////////////////
add_shortcode('image_group', 'shortcode_image_group');
function shortcode_image_group($atts, $content = null) {
	
	$str = '<div class="screens">';

    $left_image_url     = $atts['left_image_url'];
    $center_image_url   = $atts['center_image_url'];
    $right_image_url    = $atts['right_image_url'];

    $left_image_fx     = $atts['left_image_fx'];
    $center_image_fx   = $atts['center_image_fx'];
    $right_image_fx    = $atts['right_image_fx'];

    $fx_effect = translate_fx_effect( $left_image_fx );
    $img_class = '';
    if( $fx_effect ){
        $img_class = 'class="' . $fx_effect . '"';
    }
    $str .= '<div class="small-screen-left"><img ' . $img_class . ' src="' . $left_image_url . '" alt="" /></div>';

    $fx_effect = translate_fx_effect( $right_image_fx );
    $img_class = '';
    if( $fx_effect ){
        $img_class = 'class="' . $fx_effect . '"';
    }
    $str .= '<div class="small-screen-right"><img ' . $img_class . ' src="' . $right_image_url . '" alt="" /></div>';

    $fx_effect = translate_fx_effect( $center_image_fx );
    $img_class = '';
    if( $fx_effect ){
        $img_class = 'class="' . $fx_effect . '"';
    }
    $str .= '<div class="big-screen"><img ' . $img_class . ' src="' . $center_image_url . '" alt="" /></div>';

    $str .= '</div>';

	return $str;
}

add_shortcode('image_group_item', 'shortcode_image_group_item');
function shortcode_image_group_item($atts, $content = null) {
	
	if( isset($atts['position']) )
		$image_url = $atts['image_url'];
	
	$class_div 		= 'class="big-screen"';
	$class_image 	= 'class="element_fade_in"';
	
	if( isset($atts['position']) ){
		
		if( $atts['position'] == 'left' ){
			
			$class_div 		= 'class="small-screen-left"';
			$class_image 	= 'class="element_from_left"';
		}
		else if( $atts['position'] == 'right' ){
			
			$class_div 		= 'class="small-screen-right"';
			$class_image 	= 'class="element_from_right"';
		}
	}
	
	$str = '<div ' . $class_div . '><img ' . $class_image . ' src="' . $image_url . '" alt="" /></div>';

	return $str;
}


//////////////////////////////////////////////////////////////////
// Contact Details
//////////////////////////////////////////////////////////////////
add_shortcode('contact_details', 'shortcode_contact_details');
function shortcode_contact_details($atts, $content = null) {
	
	$str = '<div class="contact-details">';
    $str .= '<div class="phone-icon"><img src="' . get_template_directory_uri() . '/images/phone_white_big.png" alt="" /></div>';
    $str .= '<div class="company-phone"><a>' . $atts['company_phone'] . '</a></div>';
    $str .= '<i class="fa fa-envelope-o fa-inverse fa-2x"></i>';
    $str .= '<h5 class="company-email">' . $atts['company_email'] . '</h5>';
    $str .= '<i class="fa fa-map-marker fa-inverse fa-2x"></i>';
    $str .= '<h5 class="company-address">' . $atts['company_address'] . '</h5>';
	$str .= '</div>';

	return $str;
}

//////////////////////////////////////////////////////////////////
// Accordion
//////////////////////////////////////////////////////////////////
add_shortcode('accordion', 'shortcode_accordion');
function shortcode_accordion($atts, $content = null) {
	
	$str = '<dl class="accordion">';
    $str .= do_shortcode( $content );
	$str .= '</dl>';

	return $str;
}

add_shortcode('accordion_item', 'shortcode_accordion_item');
function shortcode_accordion_item($atts, $content = null) {
	
	extract(shortcode_atts(array(
        'title'      => ''
    ), $atts));
	
	$str = '<dt><span class="accordion-status"></span><span>' . $title . '</span></dt>';
    $str .= '<dd class="accordion-content">' . do_shortcode( $content ) . '</dd>';

	return $str;
}

//////////////////////////////////////////////////////////////////
// Counter
//////////////////////////////////////////////////////////////////
add_shortcode('counter', 'shortcode_counter');
function shortcode_counter($atts, $content = null) {
	
	extract(shortcode_atts(array(
        'counts'	=> '100',
		'icon'		=> ''
    ), $atts));

    $str = '<div class="counters">';
	$str .= '<ul>';
    $str .= '<li class="timer" data-perc="' . $counts . '">';
    if( $icon ){
	$str .= '<i class="' . $icon . '"></i>';
    }
	$str .= '<span class="count">' . $counts . '</span> ';
	$str .= do_shortcode( $content );
	$str .= '</li>';
	$str .= '</ul>';
    $str .= '</div>';

	return $str;
}

//////////////////////////////////////////////////////////////////
// Radial Counter
//////////////////////////////////////////////////////////////////
add_shortcode('radial_counter', 'shortcode_radial_counter');
function shortcode_radial_counter($atts, $content = null) {

    extract(shortcode_atts(array(
        'counts'	=> '100',
        'title'     => ''
    ), $atts));

    $str = '<div class="radial-counter">';
    $str .= '<input class="knob"  rel="' . $counts . '" value="0">';
    $str .= '<p class="radial-counter-name">' . $title . '</p>';
    $str .= '<p class="radial-counter-info">' . do_shortcode( $content ) . '</p>';
    $str .= '</div>';

    return $str;
}

//////////////////////////////////////////////////////////////////
// Big Counter
//////////////////////////////////////////////////////////////////
add_shortcode('big_counter', 'shortcode_big_counter');
function shortcode_big_counter($atts, $content = null) {

    extract(shortcode_atts(array(
        'counts'	=> '100'
    ), $atts));

    $str = '<div class="project-counters">';
    $str .= '<div class="counters">';
    $str .= '<ul>';
    $str .= '<li class="timer" data-perc="' . $counts . '">';
    $str .= '<span class="count">' . $counts . '</span> ';
    $str .= do_shortcode( $content );
    $str .= '</li>';
    $str .= '</ul>';
    $str .= '</div>';
    $str .= '</div>';

    return $str;
}

//////////////////////////////////////////////////////////////////
// Text color
//////////////////////////////////////////////////////////////////
add_shortcode('text_color', 'shortcode_text_color');
function shortcode_text_color($atts, $content = null) {

    return '<span class="text-color">' . do_shortcode( $content ) . '</span>';
}

//////////////////////////////////////////////////////////////////
// Technologies
//////////////////////////////////////////////////////////////////
add_shortcode('technologies', 'shortcode_technologies');
function shortcode_technologies($atts, $content = null) {

    $str = '<ul class="technology">';
    $str .= do_shortcode( $content );
    $str .= '</ul>';
    return $str;
}

add_shortcode('technology', 'shortcode_technology');
function shortcode_technology($atts, $content = null) {

    extract(shortcode_atts(array(
        'title'	=> '',
        'icon' => '',
        'fx_effect' => 'element_fade_in'
    ), $atts));

    $str = '<li class="' . translate_fx_effect( $atts['fx_effect'] ) . '">';
    $str .= '<i class="' . $atts['icon'] . '"></i>';
    $str .= '<h5>' . $atts['title'] . '</h5>';
    $str .= '<p>' . $content . '</p>';
    $str .= '</li>';

    return $str;
}

//////////////////////////////////////////////////////////////////
// Services
//////////////////////////////////////////////////////////////////
add_shortcode('service', 'shortcode_service');
function shortcode_service($atts, $content = null) {

    extract(shortcode_atts(array(
        'title' => '',
        'position'	=> 'left',
        'icon' => '',
    ), $atts));

    if( $atts['position'] )
        $text_position = $atts['position'];
    else
        $text_position = 'left';

    $str = '<div class="service-item text-' . $text_position . ' ' . translate_fx_effect( $atts['fx_effect'] ) . '">';
    $str .= '<span class="fa-stack fa-3x ' . $text_position . '">';
    $str .= '<i class="fa fa-circle fa-2x fa-stack-2x"></i>';
    $str .= '<i class="' . $atts['icon'] . ' fa-stack-1x fa-inverse service-icon"></i>';
    $str .= '</span><h5>' . $atts['title'] . '</h5>';
    $str .= '<p>' . do_shortcode($content) . '</p>';
    $str .= '</div>';

    return $str;
}

//////////////////////////////////////////////////////////////////
// Services Box
//////////////////////////////////////////////////////////////////
add_shortcode('services_box_carousel', 'shortcode_services_box_carousel');
function shortcode_services_box_carousel($atts, $content = null) {

    $str = '<ul class="new-service">';
    $str .= do_shortcode( $content );
    $str .= '</ul>';

    $str .= '<ul class="team-navigation">';
    $str .= '<li class="element_from_left"><a id="prev1" class="prev"><i class="fa fa-chevron-left"></i></a></li>';
    $str .= '<li class="element_from_right"><a id="next1" class="next"><i class="fa fa-chevron-right"></i></a></li>';
    $str .= '</ul>';

    return $str;
}

add_shortcode('service_box_carousel_item', 'shortcode_service_box_carousel_item');
function shortcode_service_box_carousel_item($atts, $content = null) {

    extract(shortcode_atts(array(
        'icon' => '',
        'title' => '',
        'url' => '',
        'button_text' => 'Details'
    ), $atts));

    $str = '<li class="new-service-item">';
    $str .= '<i class="' . $icon . '"><div class="service-icon-border"></div></i>';
    $str .= '<h5>' . $title . '</h5>';
    $str .= '<p class="service-info">' . do_shortcode( $content ) . '</p>';
    if( !empty( $url )){
        $str .= '<a class="newave-button small outline rounded" href="' . $url . '">' . $button_text . '</a>';
    }
    $str .= '</li>';

    return $str;
}


//////////////////////////////////////////////////////////////////
// Parallax Quote
//////////////////////////////////////////////////////////////////
add_shortcode('parallax_quote', 'shortcode_parallax_quote');
function shortcode_parallax_quote($atts, $content = null) {

    extract(shortcode_atts(array(
        'author' => '',
    ), $atts));

    $str = '<div class="quote">';
    $str .= '<h1>';
    $str .= '<span class="quote-img">';
    $str .= '<img src="' . get_template_directory_uri() . '/images/quote1.png">';
    $str .= '</span>';
    $str .= do_shortcode( $content );
    $str .= '<span class="quote-img">';
    $str .= '<img src="' . get_template_directory_uri() . '/images/quote2.png">';
    $str .= '</span>';
    $str .= '</h1>';
    $str .= '<p> - ' . $atts['author'] . ' - </p>';
    $str .= '</div>';

    return $str;
}

//////////////////////////////////////////////////////////////////
// Parallax Twitter
//////////////////////////////////////////////////////////////////
add_shortcode('parallax_twitter', 'shortcode_parallax_twitter');
function shortcode_parallax_twitter($atts, $content = null) {

    extract(shortcode_atts(array(
        'username'  => '',
        'count'     => '3'
    ), $atts));

    if( function_exists('getTweets') ){

        if( $atts['username'] != '' ){

            $str = '<div class="twitter-feed">';
            $str .= '<a class="twitter-feed-icon" href="https://twitter.com/' . $atts['username'] . '" target="_blank"><img src="' . get_template_directory_uri() . '/images/twitter_white_big.png" alt="" /></a>';
            $str .= '<ul class="twitter-slider">';

            $options = array( "include_rts" => true,
                              "exclude_replies" => false  );

            $tweets = getTweets($atts['username'], $atts['count'], $options);

            if(is_array($tweets)){

                foreach($tweets as $tweet){

                    if($tweet['text']){

                        $the_tweet = $tweet['text'];

                        $the_tweet = preg_replace('/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '&nbsp;<a href="http://$1" target="_blank">http://$1</a>&nbsp;', $the_tweet);
                        $the_tweet = preg_replace('/@([a-z0-9_]+)/i', '&nbsp;<a href="http://twitter.com/$1" target="_blank">@$1</a>&nbsp;', $the_tweet);

                        $str .= '<li><h4>' . $the_tweet . '</li></h4>';
                    }
                }
            }

            $str .= '</ul>';
            $str .= '<p>@' . $atts['username'] . '</p>';
            $str .= '</div>';

            return $str;
        }
        else{

            return __("Please specify the twitter username.", THEME_LANGUAGE_DOMAIN);
        }
    }
    else{

        return __("Please install and activate oAuth Twitter Feed for Developers plugin.", THEME_LANGUAGE_DOMAIN);
    }
}

//////////////////////////////////////////////////////////////////
// Project Section
//////////////////////////////////////////////////////////////////
add_shortcode('project_section', 'shortcode_project_section');
function shortcode_project_section($atts, $content = null) {

    extract(shortcode_atts(array(
        'color' => 'light-grey',
        'container' => 'none'
    ), $atts));

    $str = '';

    $section_clearfix = 'clearfix';

    $section_class = 'class="' . $atts['color'] . ' ' . $section_clearfix . '"';

    $str .= '<section ' . $section_class . '>';

    if( $atts['container'] != 'none' ){

        if( $atts['container'] == 'normal'){

            $str .= '<div class="container">';
        }
        else {

            $str .= '<div class="container ' . $atts['container'] . '">';
        }
    }

    $str .= do_shortcode( $content );

    if( $atts['container'] != 'none' ){

        $str .= '</div>';
    }
    $str .= '</section>';

    return $str;
}


//////////////////////////////////////////////////////////////////
// Project URL
//////////////////////////////////////////////////////////////////
add_shortcode('project_url', 'shortcode_project_url');
function shortcode_project_url($atts, $content = null) {

    extract(shortcode_atts(array(
        'url'       => '',
        'target'    => '_blank',
        'url_text'  => '',
        'url_goto'  => '',
    ), $atts));

    $str = '<section id="project-url">';

    $str .= '<div class="site">';
    $str .= '<div class="clear"></div>';
    $str .= '<a title="' . $atts['url_goto'] . '" target="' . $atts['target'] . '" href="' . $atts['url'] . '" >';
    $str .= '<span class="group">';
    $str .= '<span class="text">' . $atts['url_text'] . '</span>';
    $str .= '<span class="hover">' . $atts['url_goto'] . '</span>';
    $str .= '</span>';
    $str .= '</a>';
    $str .= '</div>';

    $str .= '</section>';

    return $str;
}

//////////////////////////////////////////////////////////////////
// Project Desc
//////////////////////////////////////////////////////////////////
add_shortcode('project_title', 'shortcode_project_title');
function shortcode_project_title($atts, $content = null) {

    extract(shortcode_atts(array(
        'project_title'       => ''
    ), $atts));

    $str = '<div class="section-title">';
    $str .= '<h1>' . $atts['project_title'] . '</h1>';
    $str .= '<span class="border"></span>';
    $str .= '<p>' . do_shortcode( $content ) . '</p>';
    $str .= '</div>';

    return $str;

}


//////////////////////////////////////////////////////////////////
// Project Slider
//////////////////////////////////////////////////////////////////
add_shortcode('project_slider', 'shortcode_project_slider');
function shortcode_project_slider($atts, $content = null) {

    $str = '<div class="project-image-slider">';
    $str .= '<ul class="project-slider">';
    $str .= do_shortcode( $content );
    $str .= '</ul>';
    $str .= '</div>';

    return $str;
}

add_shortcode('project_slide', 'shortcode_project_slide');
function shortcode_project_slide($atts, $content = null) {

    extract(shortcode_atts(array(
        'img_url' => '',
        'img_alt' => ''
    ), $atts));

    $str = '<li><img src="' . $atts['img_url'] . '" alt="' . $atts['img_alt'] . '" /></li>';

    return $str;
}


//////////////////////////////////////////////////////////////////
// Image
//////////////////////////////////////////////////////////////////
add_shortcode('single_image', 'shortcode_single_image');
function shortcode_single_image($atts, $content = null) {

    extract(shortcode_atts(array(
        'img_url' => '',
        'img_alt' => '',
        'fx_effect' => 'fade'
    ), $atts));

    $fx_effect = translate_fx_effect( $atts['fx_effect'] );
    $img_class = '';
    if( $fx_effect ){
        $img_class = 'class="' . $fx_effect . '"';
    }

    return '<img ' . $img_class . ' src="' . $atts['img_url'] . '" alt="' . $atts['img_alt'] . '" />';

}

//////////////////////////////////////////////////////////////////
// Visit Project
//////////////////////////////////////////////////////////////////
add_shortcode('visit_project', 'shortcode_visit_project');
function shortcode_visit_project($atts, $content = null) {

    extract(shortcode_atts(array(
        'url' => '',
        'button_name' => '',
    ), $atts));

    $str = '<div class="visit-project clearfix">';
    $str .= '<a class="newave-button medium grey" href="' . $atts['url'] . '" target="_blank">' . $atts['button_name'] . '</a>';
    $str .= "</div>";

    return $str;

}

//////////////////////////////////////////////////////////////////
// Parallax link
//////////////////////////////////////////////////////////////////
add_shortcode('parallax_link', 'shortcode_parallax_link');
function shortcode_parallax_link($atts, $content = null) {

    extract(shortcode_atts(array(
        'url' => '',
        'external' => 'no',
    ), $atts));

    $class = '';
    if( $external == 'yes' ){

        $class = 'class="external"';
    }

    $str = '<div class="photo-category">';
    $str .= '<p class="photo-link">';
    $str .= '<a ' . $class . ' href="' . $url . '">';
    $str .= do_shortcode( $content );
    $str .= '</a></p></div>';

    return $str;

}


//////////////////////////////////////////////////////////////////
// Add shortcodes buttons to tinyMCE
//////////////////////////////////////////////////////////////////
add_action('init',          'init_shortcodes_menu');
add_action('admin_init',    'admin_init_shortcodes_menu');

function init_shortcodes_menu(){

    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
        return;

    if ( get_user_option('rich_editing') == 'true' )
    {
        add_filter( 'mce_external_plugins', 'add_shortcode_plugins' );
        add_filter( 'mce_buttons', 'register_shortcode_menu_buttons' );
    }
}

function add_shortcode_plugins( $plugin_array ){

    $plugin_array['NewaveShortcodes'] = get_template_directory_uri() . '/components/shortcodes/shortcodes.js';
    return $plugin_array;
}

function register_shortcode_menu_buttons( $buttons ){

    array_push( $buttons, "|", 'newave_shortcode_button' );
    return $buttons;
}

function admin_init_shortcodes_menu(){

    wp_localize_script( 'jquery', 'ShortcodeAttributes', array('shortcode_folder' => get_template_directory_uri() . '/components/shortcodes' ) );
}