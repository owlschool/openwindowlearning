<?php


$newave_shortcodes = array(

    'accordion' => array(
        'name' => __('Accordion', THEME_LANGUAGE_DOMAIN),
        'sub_items' => array(
                                'accordion_item' => array(  'name' => __('Accordion Item', THEME_LANGUAGE_DOMAIN),
                                                            'attributes' => array(
                                                                                    'title' => array( 'title' => __('Title', THEME_LANGUAGE_DOMAIN),
                                                                                                      'desc' => '',
                                                                                                      'type' => 'text',
                                                                                                      'default' => __('Accordion Title', THEME_LANGUAGE_DOMAIN)
                                                                                                     )
                                                                                  ),
                                                            'has_content' => true,
                                                            'default_content' => __('Accordion Content Here', THEME_LANGUAGE_DOMAIN)
                                                         )
        ),
        'has_content' => false
    ),

    'alert' => array(
        'name' => __('Alert Box', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
                            'color' => array( 'title' => __('Color', THEME_LANGUAGE_DOMAIN),
                                              'desc' => __('Background color for the alert box', THEME_LANGUAGE_DOMAIN),
                                              'type' => 'select',
                                              'values' => array("red", "blue", "yellow", "green")
                                            )
                            ),
        'has_content' => true,
        'default_content' => __('YOUR MESSAGE HERE', THEME_LANGUAGE_DOMAIN)
    ),

    'button' => array(
        'name' => __('Button', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
                                "link" => array(    "title" => __("Button Link", THEME_LANGUAGE_DOMAIN),
                                                    "desc"  => __("URL for the button", THEME_LANGUAGE_DOMAIN),
                                                    "type"  => "text",
                                                    "default" => "http://"
                                                ),
                                "target" => array(  "title" => __("Target Window", THEME_LANGUAGE_DOMAIN),
                                                    "desc" => __("Button link opens in a new or current window", THEME_LANGUAGE_DOMAIN),
                                                    "type" => "select",
                                                    "values" => array("_blank", "_self")
                                                ),
                                "shape" => array(   "title" => __("Button Shape", THEME_LANGUAGE_DOMAIN),
                                                    "desc" => "",
                                                    "type" => "select",
                                                    "values" => array("square", "rounded")
                                                ),
                                "size" => array(    "title" => __("Button Size", THEME_LANGUAGE_DOMAIN),
                                                    "desc" => "",
                                                    "type" => "select",
                                                    "values" => array("small", "medium", "large")
                                                ),
                                "outline" => array( "title" => __("Outlined", THEME_LANGUAGE_DOMAIN),
                                                    "desc" => "",
                                                    "type" => "select",
                                                    "values" => array("yes", "no")
                                                ),
                                "color" => array(   "title" => __("Button Color", THEME_LANGUAGE_DOMAIN),
                                                    "desc" => "",
                                                    "type" => "select",
                                                    "values" => array("black", "white")
                                                ),
								"move" => array( "title" => __("In-page scrolling", THEME_LANGUAGE_DOMAIN),
                                                    "desc" => "",
                                                    "type" => "select",
                                                    "values" => array("no", "yes")
                                                ),
								"external" => array( "title" => __("External", THEME_LANGUAGE_DOMAIN),
                                                    "desc" => __("Link to external page", THEME_LANGUAGE_DOMAIN),
                                                    "type" => "select",
                                                    "values" => array("no", "yes")
                                                ),						
        ),
        'has_content' => true,
        'default_content' => __('Button Caption', THEME_LANGUAGE_DOMAIN)
    ),

    'counter' => array(
        'name' => __('Counter Box', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
                                "icon" => array(  "title" => __("Icon", THEME_LANGUAGE_DOMAIN),
                                                  "desc" => __("Icon displayed within counter box", THEME_LANGUAGE_DOMAIN),
                                                  "type" => "icon",
                                                  "default" => ""
                                                ),
                                "counts" => array(  "title" => __("Counts", THEME_LANGUAGE_DOMAIN),
                                                  "desc" => __("Number of counts", THEME_LANGUAGE_DOMAIN),
                                                  "type" => "text",
                                                  "default" => "100"
                                                )
        ),
        'require_icon' => true,
        'has_content' => true,
        'default_content' => __('TITLE GOES HERE', THEME_LANGUAGE_DOMAIN)
    ),

    'big_counter' => array(
        'name' => __('Big Counter Box', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            "counts" => array(  "title" => __("Counts", THEME_LANGUAGE_DOMAIN),
                "desc" => __("Number of counts", THEME_LANGUAGE_DOMAIN),
                "type" => "text",
                "default" => "100"
            )
        ),
        'has_content' => true,
        'default_content' => __('TITLE GOES HERE', THEME_LANGUAGE_DOMAIN)
    ),

    'radial_counter' => array(
        'name' => __('Radial Counter Box', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
                "counts" => array(  "title" => __("Counts", THEME_LANGUAGE_DOMAIN),
                    "desc" => __("Number of counts", THEME_LANGUAGE_DOMAIN),
                    "type" => "text",
                    "default" => "100"
                ),
                "title" => array(  "title" => __("Title", THEME_LANGUAGE_DOMAIN),
                    "desc" => "",
                    "type" => "text",
                    "default" => ""
                )
        ),
        'has_content' => true,
        'default_content' => __('DESCRIPTION GOES HERE', THEME_LANGUAGE_DOMAIN)
    ),

    'testimonials' => array(    'name' => __('Client Testimonials', THEME_LANGUAGE_DOMAIN),
                                'sub_items' => array(
                                                    'testimonial' => array( 'name' => __('Testimonial', THEME_LANGUAGE_DOMAIN),
                                                                            'attributes' => array(
                                                                                            'logo' => array( 'title' => __('Client Logo', THEME_LANGUAGE_DOMAIN),
                                                                                                             'desc' => __('URL or path to logo file', THEME_LANGUAGE_DOMAIN),
                                                                                                             'type' => 'text',
                                                                                                             'default' => ''
                                                                                                            ),
                                                                                            'name' => array( 'title' => __('Client Name', THEME_LANGUAGE_DOMAIN),
                                                                                                            'desc' => '',
                                                                                                            'type' => 'text',
                                                                                                            'default' => ''
                                                                                                        ),
                                                                                            'company' => array( 'title' => __('Client Company', THEME_LANGUAGE_DOMAIN),
                                                                                                            'desc' => '',
                                                                                                            'type' => 'text',
                                                                                                            'default' => ''
                                                                                                        )
                                                                                            ),
                                                                            'has_content' => true,
                                                                            'default_content' => __('Client Testimonial goes here', THEME_LANGUAGE_DOMAIN)
                                                                          )
                                                    ),
                                'has_content' => false
                            ),

    'carousel_testimonials' => array(    'name' => __('Client Testimonials', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'dark_bknd' => array( 'title' => __('Dark Background', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                "type" => "select",
                "values" => array("yes", "no")
            )
        ),
        'sub_items' => array(
            'carousel_testimonial' => array( 'name' => __('Testimonial', THEME_LANGUAGE_DOMAIN),
                'attributes' => array(
                    'image_url' => array( 'title' => __('Client Image', THEME_LANGUAGE_DOMAIN),
                        'desc' => __('URL or path to image file', THEME_LANGUAGE_DOMAIN),
                        'type' => 'text',
                        'default' => ''
                    ),
                    'name' => array( 'title' => __('Client Name', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => ''
                    ),
                ),
                'has_content' => true,
                'default_content' => __('Client Testimonial goes here', THEME_LANGUAGE_DOMAIN)
            )
        ),
        'has_content' => false
    ),

    'tabs' => array(    'name' => __('Tabs', THEME_LANGUAGE_DOMAIN),
                        'sub_items' => array(
                                                'tab_item' => array(  'name' => __('Tab Item', THEME_LANGUAGE_DOMAIN),
                                                                      'attributes' => array(
                                                                                            'tab_name' => array( 'title' => __('Tab Name', THEME_LANGUAGE_DOMAIN),
                                                                                                              'desc' => '',
                                                                                                              'type' => 'text',
                                                                                                              'default' => __('Tab Title', THEME_LANGUAGE_DOMAIN)
                                                                                                            )
                                                                                           ),
                                                                      'has_content' => true,
                                                                      'default_content' => __('Tab Content Here', THEME_LANGUAGE_DOMAIN)
                                                                )
                                            ),
                        'has_content' => false
                    ),

    'contact_details' => array(
        'name' => __('Contact Box', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'company_phone' => array(   'title' => __('Company Phone', THEME_LANGUAGE_DOMAIN),
                                        'desc' => '',
                                        'type' => 'text',
                                        'default' => '0753-016-572'
                                    ),
            'company_email' => array(   'title' => __('Company Email', THEME_LANGUAGE_DOMAIN),
                                        'desc' => '',
                                        'type' => 'text',
                                        'default' => 'office@newave.com'
                                    ),
            'company_address' => array(   'title' => __('Company Address', THEME_LANGUAGE_DOMAIN),
                                        'desc' => '',
                                        'type' => 'text',
                                        'default' => '77a First Street, London, United Kingdom'
                                    ),

        ),
        'has_content' => false,
    ),

    'contact_details_slider' => array(
        'name' => __('Contact Details Slider', THEME_LANGUAGE_DOMAIN),
        'sub_items' => array(
            'contact_details_slide' => array(  'name' => __('Contact Info', THEME_LANGUAGE_DOMAIN),
                'attributes' => array(
                    'icon' => array( 'title' => __('Icon', THEME_LANGUAGE_DOMAIN),
                        'desc' => 'Icon for this service',
                        'type' => 'icon',
                        'default' => ''
                    ),
                    'title' => array( 'title' => __('Title', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => __('Contact Info Title', THEME_LANGUAGE_DOMAIN)
                    )
                ),
                'has_content' => true,
                'default_content' => __('Contact Info Here', THEME_LANGUAGE_DOMAIN)
            )
        ),
        'require_icon' => true,
        'has_content' => false
    ),

    'parallax_quote' => array(
                                'name' => __('Parallax Quote', THEME_LANGUAGE_DOMAIN),
                                'attributes' => array(  'author' => array( 'title' => __('Author', THEME_LANGUAGE_DOMAIN),
                                                                          'desc' => __('Author of the quote', THEME_LANGUAGE_DOMAIN),
                                                                          'type' => 'text',
                                                                          'default' => 'John Doe'
                                                                        )
                                                    ),
                                'has_content' => true,
                                'default_content' => __('The Quote Here', THEME_LANGUAGE_DOMAIN )
                              ),

    'parallax_link' => array(
                                'name' => __('Parallax Link', THEME_LANGUAGE_DOMAIN),
                                                'attributes' => array(  'url' => array( 'title' => __('Link URL', THEME_LANGUAGE_DOMAIN),
                                                                                            'desc' => '',
                                                                                            'type' => 'text',
                                                                                            'default' => 'http://' ),
                                                                        'external' => array(  "title" => __("External Link", THEME_LANGUAGE_DOMAIN),
                                                                                            "desc" => __("If the link points to an external page or an anchor in the current page", THEME_LANGUAGE_DOMAIN),
                                                                                            "type" => "select",
                                                                                            "values" => array("yes", "no")   ),
                                                                    ),
                                'has_content' => true,
                                'default_content' => __('The Content Here', THEME_LANGUAGE_DOMAIN )
                            ),

    'recent_posts' => array(
                               'name' => __('Recent Posts', THEME_LANGUAGE_DOMAIN),
                               'attributes' => array(  'posts' => array( 'title' => __('Posts', THEME_LANGUAGE_DOMAIN),
                                                                         'desc' => __('Number of posts to display', THEME_LANGUAGE_DOMAIN),
                                                                         'type' => 'text',
                                                                         'default' => '5'
                                                                        ),
                                                        'post_title_length' => array( 'title' => __('Post Title Length', THEME_LANGUAGE_DOMAIN),
                                                                                      'desc' => __('The length of the post title', THEME_LANGUAGE_DOMAIN),
                                                                                      'type' => 'text',
                                                                                      'default' => '40'
                                                                                    ),
                                                    ),
                                'has_content' => false,
                            ),
							
	'show_three_blog_posts' => array(
                               'name' => __('Show Three Blog_Posts', THEME_LANGUAGE_DOMAIN),
                               'attributes' => array(  
														"order" => array(  	"title" => __("Order", THEME_LANGUAGE_DOMAIN),
																			"desc" => __("Order of the blog posts being displayed", THEME_LANGUAGE_DOMAIN),
																			"type" => "select",
																			"values" => array("latest", "most_commented" )
																			),
                                                    ),
                                'has_content' => false,
                            ),						

    'parallax_twitter' => array(
        'name' => __('Twitter Parallax Box', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(  'count' => array( 'title' => __('Tweets Count', THEME_LANGUAGE_DOMAIN),
            'desc' => __('Number of tweets to display', THEME_LANGUAGE_DOMAIN),
            'type' => 'text',
            'default' => '5'
        ),
            'username' => array( 'title' => __('Twitter username', THEME_LANGUAGE_DOMAIN),
                'desc' => __('Twitter account name', THEME_LANGUAGE_DOMAIN),
                'type' => 'text',
                'default' => ''
            ),
        ),
        'has_content' => false,
    ),

    'service' => array(
        'name' => __('Service Box', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            "icon" => array(  "title" => __("Icon", THEME_LANGUAGE_DOMAIN),
                "desc" => __("Icon displayed within service box", THEME_LANGUAGE_DOMAIN),
                "type" => "icon",
                "default" => ""
            ),
            "title" => array(  "title" => __("Service Title", THEME_LANGUAGE_DOMAIN),
                "desc" => __("Title of the service", THEME_LANGUAGE_DOMAIN),
                "type" => "text",
                "default" => ""
            ),
            "position" => array(  "title" => __("Content position", THEME_LANGUAGE_DOMAIN),
                                "desc" => __("Position of the content within the layout", THEME_LANGUAGE_DOMAIN),
                                "type" => "select",
                                "values" => array("left", "right")
                            ),
            'fx_effect' => array( 'title' => __('FX effect', THEME_LANGUAGE_DOMAIN),
                                        'desc' => '',
                                        'type' => 'select',
                                        'values' => array('fade', 'fade-from-left', 'fade-from-right', 'none')
                                    )
        ),
        'require_icon' => true,
        'has_content' => true,
        'default_content' => __('Service Description', THEME_LANGUAGE_DOMAIN)
    ),

    'services_box_carousel' => array(
        'name' => __('Services Box', THEME_LANGUAGE_DOMAIN),
        'sub_items' => array(
            'service_box_carousel_item' => array(  'name' => __('Service', THEME_LANGUAGE_DOMAIN),
                'attributes' => array(
                    'icon' => array( 'title' => __('Icon', THEME_LANGUAGE_DOMAIN),
                        'desc' => 'Icon for this service',
                        'type' => 'icon',
                        'default' => ''
                    ),
                    'title' => array( 'title' => __('Title', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => __('Service Title', THEME_LANGUAGE_DOMAIN)
                    ),
                    'button_text' => array( 'title' => __('Button Text', THEME_LANGUAGE_DOMAIN),
                        'desc' => __('Caption of the button displayed underneath service description', THEME_LANGUAGE_DOMAIN),
                        'type' => 'text',
                        'default' => __('Details', THEME_LANGUAGE_DOMAIN)
                    ),
                    'url' => array( 'title' => __('Service URL', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => '#'
                    )
                ),
                'has_content' => true,
                'default_content' => __('Service Description Here', THEME_LANGUAGE_DOMAIN)
            )
        ),
        'require_icon' => true,
        'has_content' => false
    ),

    'tagline_box' => array(
        'name' => __('Tagline Box', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            "button" => array(  "title" => __("Button Caption", THEME_LANGUAGE_DOMAIN),
                "desc" => __("The caption of the tagline button", THEME_LANGUAGE_DOMAIN),
                "type" => "text",
                "default" => ""
            ),
            "link" => array(  "title" => __("Link", THEME_LANGUAGE_DOMAIN),
                "desc" => __("URL of the tagline", THEME_LANGUAGE_DOMAIN),
                "type" => "text",
                "default" => "http://"
            ),
            "target" => array(  "title" => __("Target Action", THEME_LANGUAGE_DOMAIN),
                "desc" => __("Open the link in a new page or same page", THEME_LANGUAGE_DOMAIN),
                "type" => "select",
                "values" => array("_blank", "_self")
            )
        ),
        'has_content' => true,
        'default_content' => __('Tagline description', THEME_LANGUAGE_DOMAIN)
    ),

    'technologies' => array(
        'name' => __('Technologies', THEME_LANGUAGE_DOMAIN),
        'sub_items' => array(
            'technology' => array(  'name' => __('Technology', THEME_LANGUAGE_DOMAIN),
                'attributes' => array(
                    'icon' => array( 'title' => __('Icon', THEME_LANGUAGE_DOMAIN),
                        'desc' => 'Icon for this technology',
                        'type' => 'icon',
                        'default' => ''
                    ),
                    'title' => array( 'title' => __('Title', THEME_LANGUAGE_DOMAIN),
                                      'desc' => '',
                                      'type' => 'text',
                                      'default' => __('Technology Title', THEME_LANGUAGE_DOMAIN)
                                    ),
                    'fx_effect' => array( 'title' => __('FX effect', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'select',
                        'values' => array('fade', 'fade-from-left', 'fade-from-right', 'none')
                    )
                ),
                'has_content' => true,
                'default_content' => __('Technology Description Here', THEME_LANGUAGE_DOMAIN)
            )
        ),
        'require_icon' => true,
        'has_content' => false
    ),

    'image_group' => array(
        'name' => __('Image Group', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
                    'left_image_url' => array( 'title' => __('Left Image URL', THEME_LANGUAGE_DOMAIN),
                        'desc' => 'The URL or path of the left image',
                        'type' => 'text',
                        'default' => ''
                    ),
                    'left_image_fx' => array( 'title' => __('Left Image FX', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'select',
                        'values' => array('fade', 'fade-from-left', 'fade-from-right', 'none')
                    ),
                    'center_image_url' => array( 'title' => __('Center Image URL', THEME_LANGUAGE_DOMAIN),
                        'desc' => 'The URL or path of the center image',
                        'type' => 'text',
                        'default' => ''
                    ),
                    'center_image_fx' => array( 'title' => __('Center Image FX', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'select',
                        'values' => array('fade', 'fade-from-left', 'fade-from-right', 'none')
                    ),
                    'right_image_url' => array( 'title' => __('Right Image URL', THEME_LANGUAGE_DOMAIN),
                        'desc' => 'The URL or path of the right image',
                        'type' => 'text',
                        'default' => ''
                    ),
                    'right_image_fx' => array( 'title' => __('Right Image FX', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'select',
                        'values' => array('fade', 'fade-from-left', 'fade-from-right', 'none')
                    )
                ),
        'has_content' => false
    ),

    'list_styles' => array(
        'name' => __('List Styles', THEME_LANGUAGE_DOMAIN),
        'sub_items' => array(
            'list_style' => array(  'name' => __('List Item', THEME_LANGUAGE_DOMAIN),
                'attributes' => array(
                    'icon' => array( 'title' => __('Icon', THEME_LANGUAGE_DOMAIN),
                        'desc' => 'Icon for this list item',
                        'type' => 'icon',
                        'default' => ''
                    ),
                ),
                'has_content' => true,
                'default_content' => 'Nullam dignissim convallis'
            )
        ),
        'attributes' => array(
            'size' => array( 'title' => __('List Size', THEME_LANGUAGE_DOMAIN),
                'desc' => 'Size of the list items',
                'type' => 'select',
                'values' => array('normal', 'big')
            )
        ),
        'require_icon' => true,
        'has_content' => false
    ),

    'progress' => array(
        'name' => __('Progress Bars', THEME_LANGUAGE_DOMAIN),
        'sub_items' => array(
            'progress_bar' => array(  'name' => __('Progress Bar', THEME_LANGUAGE_DOMAIN),
                'attributes' => array(
                    'percentage' => array( 'title' => __('Percentage', THEME_LANGUAGE_DOMAIN),
                        'desc' => 'Progress Bar Percentage',
                        'type' => 'text',
                        'default' => '100'
                    ),
                ),
                'has_content' => true,
                'default_content' => __('Web Design', THEME_LANGUAGE_DOMAIN)
            )
        ),
        'has_content' => false,
     ),

    'social_icons' => array(
        'name' => __('Social Sharing Links', THEME_LANGUAGE_DOMAIN),
        'sub_items' => array(
            'social_icon' => array(  'name' => __('Social Sharing Link', THEME_LANGUAGE_DOMAIN),
                'attributes' => array(
                    'url' => array( 'title' => __('Link URL', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => ''
                    ),
                    'target' => array( 'title' => __('Link Target Window', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'select',
                        'values' => array('_blank', '_self')
                    ),
                    'social' => array( 'title' => __('Social Link Type', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'select',
                        'values' => array(  "facebook",
                                            "twitter",
                                            "linkedin",
                                            "behance",
                                            "deviantart",
                                            "dribble",
                                            "flickr",
                                            "google",
                                            "lastfm",
                                            "picasa",
                                            "pinterest",
                                            "rss",
                                            "skype",
                                            "stumble",
                                            "vimeo",
                                            "youtube",
                                            "instagram"
                                        )
                    ),
                ),
                'has_content' => false,
             )
        ),
        'has_content' => false,
    ),

    'team' => array(
        'name' => __('Team Members', THEME_LANGUAGE_DOMAIN),
        'sub_items' => array(
            'team_member' => array(  'name' => __('Team Member', THEME_LANGUAGE_DOMAIN),
                'attributes' => array(
                    'name' => array( 'title' => __('Team Member Name', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => ''
                    ),
                    'title' => array( 'title' => __('Job Title', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => ''
                    ),
                    'picture' => array( 'title' => __('Picture', THEME_LANGUAGE_DOMAIN),
                        'desc' => __('URL or path to team member\'s picture', THEME_LANGUAGE_DOMAIN),
                        'type' => 'text',
                        'default' => ''
                    ),
                    'fx_effect' => array( 'title' => __('FX effect', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'select',
                        'values' => array('fade', 'fade-from-left', 'fade-from-right', 'none')
                    ),
                    'social_link1' => array( 'title' => __('Social Link 1', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'select',
                        'values' => array(  "facebook",
                            "twitter",
                            "linkedin",
                            "behance",
                            "deviantart",
                            "dribble",
                            "flickr",
                            "google",
                            "lastfm",
                            "picasa",
                            "pinterest",
                            "rss",
                            "skype",
                            "stumble",
                            "vimeo",
                            "youtube",
                            "instagram"
                        )
                    ),
                    'social_link1_url' => array( 'title' => __('Social Link 1 URL', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => ''
                    ),
                    'social_link2' => array( 'title' => __('Social Link 2', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'select',
                        'values' => array(  "facebook",
                            "twitter",
                            "linkedin",
                            "behance",
                            "deviantart",
                            "dribble",
                            "flickr",
                            "google",
                            "lastfm",
                            "picasa",
                            "pinterest",
                            "rss",
                            "skype",
                            "stumble",
                            "vimeo",
                            "youtube",
                            "instagram"
                        )
                    ),
                    'social_link2_url' => array( 'title' => __('Social Link 2 URL', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => ''
                    ),
                    'social_link3' => array( 'title' => __('Social Link 3', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'select',
                        'values' => array(  "facebook",
                            "twitter",
                            "linkedin",
                            "behance",
                            "deviantart",
                            "dribble",
                            "flickr",
                            "google",
                            "lastfm",
                            "picasa",
                            "pinterest",
                            "rss",
                            "skype",
                            "stumble",
                            "vimeo",
                            "youtube",
                            "instagram"
                        )
                    ),
                    'social_link3_url' => array( 'title' => __('Social Link 3 URL', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => ''
                    ),
                ),
                'has_content' => true,
                'default_content' => __('Team member description', THEME_LANGUAGE_DOMAIN)
            )
        ),
        'has_content' => false,
    ),

    'team_carousel' => array(
        'name' => __('Team Members', THEME_LANGUAGE_DOMAIN),
        'sub_items' => array(
            'team_member_carousel' => array(  'name' => __('Team Member', THEME_LANGUAGE_DOMAIN),
                'attributes' => array(
                    'name' => array( 'title' => __('Team Member Name', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => ''
                    ),
                    'title' => array( 'title' => __('Job Title', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => ''
                    ),
                    'picture' => array( 'title' => __('Picture', THEME_LANGUAGE_DOMAIN),
                        'desc' => __('URL or path to team member\'s picture', THEME_LANGUAGE_DOMAIN),
                        'type' => 'text',
                        'default' => ''
                    ),
                    'social_link1' => array( 'title' => __('Social Link 1', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'select',
                        'values' => array(  "facebook",
                            "twitter",
                            "linkedin",
                            "behance",
                            "deviantart",
                            "dribble",
                            "flickr",
                            "google",
                            "lastfm",
                            "picasa",
                            "pinterest",
                            "rss",
                            "skype",
                            "stumble",
                            "vimeo",
                            "youtube",
                            "instagram"
                        )
                    ),
                    'social_link1_url' => array( 'title' => __('Social Link 1 URL', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => ''
                    ),
                    'social_link2' => array( 'title' => __('Social Link 2', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'select',
                        'values' => array(  "facebook",
                            "twitter",
                            "linkedin",
                            "behance",
                            "deviantart",
                            "dribble",
                            "flickr",
                            "google",
                            "lastfm",
                            "picasa",
                            "pinterest",
                            "rss",
                            "skype",
                            "stumble",
                            "vimeo",
                            "youtube",
                            "instagram"
                        )
                    ),
                    'social_link2_url' => array( 'title' => __('Social Link 2 URL', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => ''
                    ),
                    'social_link3' => array( 'title' => __('Social Link 3', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'select',
                        'values' => array(  "facebook",
                            "twitter",
                            "linkedin",
                            "behance",
                            "deviantart",
                            "dribble",
                            "flickr",
                            "google",
                            "lastfm",
                            "picasa",
                            "pinterest",
                            "rss",
                            "skype",
                            "stumble",
                            "vimeo",
                            "youtube",
                            "instagram"
                        )
                    ),
                    'social_link3_url' => array( 'title' => __('Social Link 3 URL', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => ''
                    ),
                ),
                'has_content' => true,
                'default_content' => __('Team member description', THEME_LANGUAGE_DOMAIN)
            )
        ),
        'has_content' => false,
    ),

    'fontawesome_icon' => array(
        'name' => __('FontAwesome Icon', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            "icon" => array(  "title" => __("Icon", THEME_LANGUAGE_DOMAIN),
                "desc" => '',
                "type" => "icon",
                "default" => ""
            ),
            "size" => array(  "title" => __("Size", THEME_LANGUAGE_DOMAIN),
                "desc" => __("Icon size relative to their container", THEME_LANGUAGE_DOMAIN),
                "type" => "select",
                "values" => array('none', 'lg', '2x', '3x', '4x', '5x')
            ),
            "shape" => array(  "title" => __("Shape", THEME_LANGUAGE_DOMAIN),
                "desc" => __("Icon shape", THEME_LANGUAGE_DOMAIN),
                "type" => "select",
                "values" => array('normal', 'circle-box')
            )
        ),
        'require_icon' => true,
        'has_content' => false
    ),

    'youtube' => array(
        'name' => __('Youtube Video', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'id' => array( 'title' => __('Video ID', THEME_LANGUAGE_DOMAIN),
                'desc' => __('Enter video ID (eg. Wq4Y7ztznKc)', THEME_LANGUAGE_DOMAIN),
                'type' => 'text',
                'default' => ''
            ),
            'width' => array( 'title' => __('Video Width', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'text',
                'default' => '600'
            ),
            'height' => array( 'title' => __('Video Height', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'text',
                'default' => '350'
            )
        ),
        'has_content' => false

    ),

    'vimeo' => array(
        'name' => __('Vimeo Video', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'id' => array( 'title' => __('Video ID', THEME_LANGUAGE_DOMAIN),
                'desc' => __('Enter video ID (eg. 10145153)', THEME_LANGUAGE_DOMAIN),
                'type' => 'text',
                'default' => ''
            ),
            'width' => array( 'title' => __('Video Width', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'text',
                'default' => '600'
            ),
            'height' => array( 'title' => __('Video Height', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'text',
                'default' => '350'
            )
        ),
        'has_content' => false

    ),

    'toggle' => array(
        'name' => __('Toggle', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'title' => array( 'title' => __('Title', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'text',
                'default' => ''
            )
        ),
        'has_content' => true,
        'default_content' => __('Toggle Content Here', THEME_LANGUAGE_DOMAIN)
    ),

    'dropcap' => array(
        'name' => __('Dropcap', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'letter' => array( 'title' => __('Dropcap Letter', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'text',
                'default' => 'A'
            ),
            'shape' => array( 'title' => __('Dropcap Shape', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('normal', 'round', 'square')
            )
        ),
        'has_content' => true,
        'default_content' => __('Content With Dropcap', THEME_LANGUAGE_DOMAIN)
    ),

    'title' => array(
        'name' => __('Title', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'size' => array( 'title' => __('Title Size', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('1', '2', '3', '4')
            )
        ),
        'has_content' => true,
        'default_content' => __('Title', THEME_LANGUAGE_DOMAIN)
    ),

    'small_title' => array(
        'name' => __('Small Title', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'header_text' => array( 'title' => __('Header', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'text',
                'default' => ''
            )
        ),
        'has_content' => true,
        'default_content' => __('Content goes here', THEME_LANGUAGE_DOMAIN)
    ),

    'one_half' => array(
        'name' => __('Column', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'last' => array( 'title' => __('Last Column?', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('no', 'yes')
            ),
            'text_align' => array( 'title' => __('Text Alignment', THEME_LANGUAGE_DOMAIN),
                 'desc' => '',
                 'type' => 'select',
                 'values' => array('text-align-left', 'text-align-center', 'text-align-right' )
                )
        ),
        'has_content' => true,
        'default_content' => __('Content goes here', THEME_LANGUAGE_DOMAIN)
    ),

    'one_third' => array(
        'name' => __('Column', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'last' => array( 'title' => __('Last Column?', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('no', 'yes')
            ),
            'text_align' => array( 'title' => __('Text Alignment', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('text-align-left', 'text-align-center', 'text-align-right' )
            )
        ),
        'has_content' => true,
        'default_content' => __('Content goes here', THEME_LANGUAGE_DOMAIN)
    ),

    'one_fourth' => array(
        'name' => __('Column', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'last' => array( 'title' => __('Last Column?', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('no', 'yes')
            ),
            'text_align' => array( 'title' => __('Text Alignment', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('text-align-left', 'text-align-center', 'text-align-right' )
            )
        ),
        'has_content' => true,
        'default_content' => __('Content goes here', THEME_LANGUAGE_DOMAIN)
    ),

    'one_fifth' => array(
        'name' => __('Column', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'last' => array( 'title' => __('Last Column?', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('no', 'yes')
            ),
            'text_align' => array( 'title' => __('Text Alignment', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('text-align-left', 'text-align-center', 'text-align-right' )
            )
        ),
        'has_content' => true,
        'default_content' => __('Content goes here', THEME_LANGUAGE_DOMAIN)
    ),

    'one_sixth' => array(
        'name' => __('Column', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'last' => array( 'title' => __('Last Column?', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('no', 'yes')
            ),
            'text_align' => array( 'title' => __('Text Alignment', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('text-align-left', 'text-align-center', 'text-align-right' )
            )
        ),
        'has_content' => true,
        'default_content' => __('Content goes here', THEME_LANGUAGE_DOMAIN)
    ),

    'two_third' => array(
        'name' => __('Column', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'last' => array( 'title' => __('Last Column?', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('no', 'yes')
            ),
            'text_align' => array( 'title' => __('Text Alignment', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('text-align-left', 'text-align-center', 'text-align-right' )
            )
        ),
        'has_content' => true,
        'default_content' => __('Content goes here', THEME_LANGUAGE_DOMAIN)
    ),

    'two_fifth' => array(
        'name' => __('Column', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'last' => array( 'title' => __('Last Column?', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('no', 'yes')
            ),
            'text_align' => array( 'title' => __('Text Alignment', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('text-align-left', 'text-align-center', 'text-align-right' )
            )
        ),
        'has_content' => true,
        'default_content' => __('Content goes here', THEME_LANGUAGE_DOMAIN)
    ),

    'three_fourth' => array(
        'name' => __('Column', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'last' => array( 'title' => __('Last Column?', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('no', 'yes')
            ),
            'text_align' => array( 'title' => __('Text Alignment', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('text-align-left', 'text-align-center', 'text-align-right' )
            )
        ),
        'has_content' => true,
        'default_content' => __('Content goes here', THEME_LANGUAGE_DOMAIN)
    ),

    'three_fifth' => array(
        'name' => __('Column', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'last' => array( 'title' => __('Last Column?', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('no', 'yes')
            ),
            'text_align' => array( 'title' => __('Text Alignment', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('text-align-left', 'text-align-center', 'text-align-right' )
            )
        ),
        'has_content' => true,
        'default_content' => __('Content goes here', THEME_LANGUAGE_DOMAIN)
    ),

    'four_fifth' => array(
        'name' => __('Column', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'last' => array( 'title' => __('Last Column?', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('no', 'yes')
            ),
            'text_align' => array( 'title' => __('Text Alignment', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('text-align-left', 'text-align-center', 'text-align-right' )
            )
        ),
        'has_content' => true,
        'default_content' => __('Content goes here', THEME_LANGUAGE_DOMAIN)
    ),

    'five_sixth' => array(
        'name' => __('Column', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'last' => array( 'title' => __('Last Column?', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('no', 'yes')
            ),
            'text_align' => array( 'title' => __('Text Alignment', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('text-align-left', 'text-align-center', 'text-align-right' )
            )
        ),
        'has_content' => true,
        'default_content' => __('Content goes here', THEME_LANGUAGE_DOMAIN)
    ),

    'project_section' => array(
        'name' => __('Project Section', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'color' => array( 'title' => __('Background Color', THEME_LANGUAGE_DOMAIN),
                               'desc' => '',
                               'type' => 'select',
                               'values' => array('light-grey-background', 'white-background', 'dark-background', 'primary-color-background')
                            ),
            'container' => array( 'title' => __('Container type', THEME_LANGUAGE_DOMAIN),
                                  'desc' => '',
                                  'type' => 'select',
                                  'values' => array('none', 'normal', 'without-padding', 'small-width')
                                ),
        ),
        'has_content' => true,
        'default_content' => __('Content goes here', THEME_LANGUAGE_DOMAIN)
    ),

    'project_url' => array(
        'name' => __('Project URL', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'url' => array( 'title' => __('URL of the project', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'text',
                'default' => 'http://'
            ),
            'url_text' => array( 'title' => __('Website name', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'text',
                'default' => 'www.newave.com'
            ),
            'url_goto' => array( 'title' => __('"Go to website" text', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'text',
                'default' => __('go to website', THEME_LANGUAGE_DOMAIN)
            ),
            'target' => array( 'title' => __('Target Window', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('_blank', '_self')
            ),
        ),
        'has_content' => false
    ),

    'project_title' => array(
        'name' => __('Project Title', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
                                'project_title' => array( 'title' => __('Title', THEME_LANGUAGE_DOMAIN),
                                                 'desc' => '',
                                                 'type' => 'text',
                                                  'default' => ''
                                               )
                             ),
        'has_content' => true,
        'default_content' => __('Project description goes here', THEME_LANGUAGE_DOMAIN)
    ),

    'single_image' => array(
        'name' => __('Project Single Image', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'img_url' => array( 'title' => __('Image URL', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'text',
                'default' => 'http://'
            ),
            'img_alt' => array( 'title' => __('Image ALT', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'text',
                'default' => ''
            ),
            'fx_effect' => array( 'title' => __('FX effect', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'select',
                'values' => array('fade', 'fade-from-left', 'fade-from-right', 'none')
            )
        ),
        'has_content' => false
    ),

    'project_slider' => array(
        'name' => __('Project Slider', THEME_LANGUAGE_DOMAIN),
        'sub_items' => array(
            'project_slide' => array(  'name' => __('Project Slide', THEME_LANGUAGE_DOMAIN),
                'attributes' => array(
                    'img_url' => array( 'title' => __('Slider Image URL', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => ''
                    ),
                    'img_alt' => array( 'title' => __('Slider Image ALT', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'text',
                        'default' => ''
                    )
                ),
                'has_content' => false
            )
        ),
        'has_content' => false
    ),

    'project_options' => array(
        'name' => __('Project Options', THEME_LANGUAGE_DOMAIN),
        'sub_items' => array(
            'project_option' => array(  'name' => __('Project Option', THEME_LANGUAGE_DOMAIN),
                'attributes' => array(
                    'icon' => array( 'title' => __('Project Option Icon', THEME_LANGUAGE_DOMAIN),
                        'desc' => '',
                        'type' => 'icon',
                        'default' => ''
                    ),

                ),
                'has_content' => true,
                'default_content' => __('Project option goes here', THEME_LANGUAGE_DOMAIN)
            )
        ),
        'require_icon' => true,
        'has_content' => false
    ),

    'visit_project' => array(
        'name' => __('Visit Project', THEME_LANGUAGE_DOMAIN),
        'attributes' => array(
            'url' => array( 'title' => __('Project URL', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'text',
                'default' => 'http://'
            ),
            'button_name' => array( 'title' => __('Button Name', THEME_LANGUAGE_DOMAIN),
                'desc' => '',
                'type' => 'text',
                'default' => 'Visit Website'
            )
        ),
        'has_content' => false
    ),

);

?>