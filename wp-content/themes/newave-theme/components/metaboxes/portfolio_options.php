<div class='newave_metabox'>

<?php
$this->select(	'portfolio_item_layout',
				'Layout type',
				array('normal' => 'Normal', 'slider' => 'Slider', 'full_screen' => 'Full Screen Slider', 'external' => 'External Project', 'external_slider' => 'External Project With Full Slider', 'image_popup' => 'Image Popup'),
				''
            );

$this->upload(	'portfolio_item_external_header_image',
                'External Project Header Background Image:',
                ''
             );

$this->text(	'portfolio_item_external_header_subtitle',
                'External Project Header Subtitle',
                ''
            );

$this->select(	'portfolio_item_external_header_text_alignment',
                'External Project Header Text Alignment',
                array('center' => 'Center', 'left' => 'Left', 'right' => 'Right'),
                ''
            );
?>

</div>