 <div class="newave_metabox">
 <?php
     $this->radio(	'contact_default_background',
                    'Background:',
                    array('white' => 'White', 'light_grey' => 'Light Grey'),
                    ''
                );

     $this->select(	'contact_map_overlay',
                     'Map Overlay',
                     array('no' => 'No', 'yes' => 'Yes'),
                     ''
                 );
 ?>
 </div>