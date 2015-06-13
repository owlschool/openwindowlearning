<?php
require_once('access-wp.php');
?>

<!DOCTYPE html>
<html>
<head>
<title></title>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/components/shortcodes/shortcodes_popup/css/shortcodes_popup.css" />

<script src="<?php echo get_template_directory_uri(); ?>/components/shortcodes/shortcodes_popup/js/simple-slider.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/components/shortcodes/shortcodes_popup/js/shortcodes_popup.js"></script>

</head>

<body>	
    <br>

<?php

function shortcode_options( $sc_name, $shortcode ){

    if( $shortcode ){

        if( isset($shortcode['require_icon']) && $shortcode['require_icon'] ){

            require_once( get_template_directory() . "/components/shortcodes/shortcodes_popup/fontawesome-icons.php" );
        }

        // display shortcode attributes
        if( isset( $shortcode['attributes'] ) && $shortcode['attributes'] ){

            echo '<div class="shortcode-options" id="options-group">';

            $attributes = $shortcode['attributes'];

            foreach( $attributes as $attribute => $attr_options ){

                echo '<div class="label"><label for="'.$attribute.'"><strong>'.$attr_options['title'].': </strong></label></div>';
                echo '<div class="content">';

                if( $attr_options['type'] == "text" ){

                    echo '<input class="attr" type="text" data-attrname="'.$attribute.'" value="' . $attr_options['default'] . '" />';

                }
                if( $attr_options['type'] == "icon" ){

                    echo '<input class="attr" type="text" data-attrname="'.$attribute.'" value="' . $attr_options['default'] . '" />';
                    echo '<a class="btn display-icons-list" id="btn-' . $attribute . '">' . __('Set Icon', THEME_LANGUAGE_DOMAIN) . '</a>';

                }
                if( $attr_options['type'] == "select" ){

                    echo '<select class="attr" type="select" data-attrname="'.$attribute.'" id="'.$attribute.'">';

                    $values = $attr_options['values'];
                    foreach( $values as $value ){
                        echo '<option value="'.$value.'">'.$value.'</option>';
                    }

                    echo '</select>';
                }

                echo $attr_options['desc'] . '</div>';
            }

            echo '</div>'; // div options group

        }

        if( isset( $shortcode['sub_items'] ) && $shortcode['sub_items'] ){

            foreach ( $shortcode['sub_items'] as $subitem_name => $subitem ) {

                echo '<div class="shortcode-dynamic-items" id="options-item" data-name="item">';
                echo '<div class="label"><label id="dynamic-item-label" for="shortcode-dynamic-items">' . $subitem['name'] . '</label></div>';
                echo '<div class="shortcode-dynamic-item">';

                shortcode_options( $subitem_name, $subitem );

                echo '</div>';
                echo '</div>';
                echo '<a href="#" class="btn blue add-list-item">' . sprintf(__('Add %s', THEME_LANGUAGE_DOMAIN ), $subitem['name']) . '</a>' . '<a href="#" class="btn blue remove-list-item">'. sprintf(__('Remove %s', THEME_LANGUAGE_DOMAIN ), $subitem['name']) . '</a>';
            }

        }

        if( isset( $shortcode['has_content'] ) && $shortcode['has_content'] ){

            ?>
            <div id="shortcode-content">

                <div class="label"><label id="option-label" for="shortcode-content"><?php echo __( 'Content: ', THEME_LANGUAGE_DOMAIN ); ?> </label></div>
                <div class="content"><textarea id="shortcode_content"><?php echo $shortcode['default_content']; ?></textarea></div>

                <div class="hr"></div>

            </div>

        <?php

        } // if shortcode has content
        else{

            echo '<div class="hr"></div>';
        }

        echo '<input type="hidden" id="shortcode-name" value="' . $sc_name . '" />';


    } // if found shortcode

}

if( isset( $_GET['sc'] ) ){

    $sc = $_GET['sc'];

    require_once( get_template_directory() . "/components/shortcodes/shortcode_definitions.php" );

    global $newave_shortcodes;

    $shortcode = $newave_shortcodes[$sc];

    if( $shortcode ){

        shortcode_options( $sc, $shortcode );
    }
?>

<a class="btn" id="add-shortcode"><?php _e('Add Shortcode', THEME_LANGUAGE_DOMAIN); ?></a>

<?php
}
?>

</body>
</html>
