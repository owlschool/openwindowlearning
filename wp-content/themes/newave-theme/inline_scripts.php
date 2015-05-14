<?php global $global_theme_options; ?>

<?php 
	if( is_page_template( 'one-page.php' ) && !empty($global_theme_options['contact_show_map']) ) {
?>    
    <style type="text/css">
	#map_canvas{
		<?php 
                    if($global_theme_options['gmap_width']){
                        echo 'width:'.$global_theme_options['gmap_width'].';';
                    } else {
                        echo 'width:100%;';
                    }

                    if($global_theme_options['gmap_height']){
                        echo 'height:' . $global_theme_options['gmap_height'] . ';';
                    } else {
                        echo 'height:500px;';
                    }
        ?>
	}
    .map-overlay.overlay-hide {
        <?php
                if($global_theme_options['gmap_height']){
                    echo 'margin-top:-' . $global_theme_options['gmap_height'] . ';';
                } else {
                    echo 'margin-top:-500px;';
                }
        ?>
        </style>
    <?php
    }
    ?>
            
<?php
	if( is_page_template( 'one-page.php' ) && !empty($global_theme_options['contact_show_map']) ) {
?>	
	<script type="text/javascript">
        /* <![CDATA[ */    
	jQuery(document).ready(function($) {

        if( jQuery('#map_canvas').length > 0 ) {

            var geocoder = new google.maps.Geocoder();

            geocoder.geocode({'address':'<?php echo addslashes($global_theme_options['gmap_address']); ?>'}, function(results, status) {
                if(status == google.maps.GeocoderStatus.OK) {
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();

                    var contentString = '<div id="content">'+
                    '<div id="siteNotice">'+
                    '</div>'+
                    '<h2 id="firstHeading" class="firstHeading"><?php echo addslashes($global_theme_options["gmap_company_name"]); ?></h2>'+
                    '<div id="bodyContent">'+
                    '<p><?php  echo addslashes($global_theme_options["gmap_company_info"]); ?></p>'+
                    '</div>'+
                    '</div>';

                    var settings = {
                        mapTypeControl: false,
                        scrollwheel: false,
                        draggable: true,
                        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
                        navigationControl: false,
                        navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };

                    jQuery('#map_canvas').gmap( settings ).bind('init', function(ev, map) {
                        jQuery('#map_canvas').gmap('addMarker', {'position': latitude+','+longitude, 'bounds': true, icon : new google.maps.MarkerImage("<?php if( trim($global_theme_options["gmap_marker_icon"]) == "" ) echo get_template_directory_uri() . '/images/marker.png'; else echo $global_theme_options["gmap_marker_icon"]; ?>") }).click(function() {
                            jQuery('#map_canvas').gmap('openInfoWindow', {'content': contentString}, this);
                        });
                        jQuery('#map_canvas').gmap('option', 'zoom', <?php if( $global_theme_options['map_zoom_level'] ) echo $global_theme_options['map_zoom_level']; else echo '8'; ?>);

                    });

                    var gmap = jQuery('#map_canvas').gmap('get', 'map');
                    google.maps.event.addDomListener(window, "resize", function() {
                        var center = gmap.getCenter();
                        google.maps.event.trigger(gmap, "resize");
                        gmap.setCenter(center);
                    });
                }
            });
        }
	});
        /* ]]> */
	</script>

<?php    
	}
?>