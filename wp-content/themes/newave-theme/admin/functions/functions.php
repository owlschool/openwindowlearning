<?php

// Metaboxes
require_once( get_template_directory().'/components/metaboxes/metaboxes.php');

add_theme_support('post-thumbnails');
add_theme_support( 'automatic-feed-links' );

// Register custom post types
add_action('init', 'newave_init');
function newave_init() {

	global $global_theme_options;

    $custom_slug = null;

    if( isset($global_theme_options['portfolio_rewrite_slug']) && !empty($global_theme_options['portfolio_rewrite_slug']) ) $custom_slug = $global_theme_options['portfolio_rewrite_slug'];

	register_post_type(
		'newave_portfolio',
		array(
			'labels' => array(
				'name' => 'Portfolio',
				'singular_name' => 'Portfolio'
			),
            'rewrite' => array('slug' => $custom_slug, 'with_front' => false),
			'public' => true,
			'has_archive' => true,
			'supports' => array('title', 'editor', 'thumbnail','comments'),
			'can_export' => true,
		)
	);

	register_taxonomy('portfolio_category', 'newave_portfolio', array('hierarchical' => true, 'label' => 'Categories', 'query_var' => true, 'rewrite' => true));

}

// refresh rewrite rules for custom portfolio slugs
add_action( 'after_switch_theme', 'rewrite_flush_slug' );
function rewrite_flush_slug() {

    flush_rewrite_rules();
}

?>