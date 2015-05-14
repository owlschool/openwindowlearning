<?php

function newave_theme_options_init() {

	register_setting(
		'newave_options',       // Options group, see settings_fields() call in twentyeleven_theme_options_render_page()
		'newave_theme_options', // Database option, see twentyeleven_get_theme_options()
		'newave_theme_options_validate' // The sanitization callback, see twentyeleven_theme_options_validate()
	);
	
}

add_action( 'admin_init', 'newave_theme_options_init' );

?>