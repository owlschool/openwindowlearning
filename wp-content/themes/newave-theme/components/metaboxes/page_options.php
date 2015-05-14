 <div class="newave_metabox">
<?php

//if this page is a section or not
$this->select(	    'is_page_section',
					'This page is a section:',
                    array('yes' => 'Yes', 'no' => 'No'),
					''
             	);

//show or hide page title
$this->select(	    'show_page_title',
					'Show page title:',
                    array('yes' => 'Yes', 'no' => 'No'),
					''
             	);
             	
             	
//page subtitle
$this->textarea(	'page_subtitle',
					'Page Subtitle',
					''
           		);
           		
// disable page from menu
$this->select(	    'menu_disable_page',
					'Disable page from menu:',
                    array('no' => 'No', 'yes' => 'Yes'),
					''
             	);

// menu item margin right
$this->text(	'menu_item_margin_right',
                'Page menu item margin right (in pixels):',
                ''
           );

// menu item responsive
$this->text(	'menu_item_threshold_margin_right',
                'Page menu item margin right - responsive threshold (in pixels):',
                ''
            );


// page section type           		
$this->select(	'page_section',
				'Assign current page as',
				array('default' => 'Default Section', 'home' => 'Home Section', 'portfolio' => 'Portfolio Section', 'parallax' => 'Parallax Section', 'contact' => 'Contact Section', 'blog' => 'Blog Section'),
				''
            );

?>

</div>