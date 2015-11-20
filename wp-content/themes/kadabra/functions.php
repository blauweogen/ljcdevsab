<?php
/**
 * kadabra themes functions
 */

define('DFD_THEME_SETTINGS_NAME', 'kadabra');

if (!isset($content_width)) $content_width = 1200;

# Including theme components
require_once locate_template('/inc/includes.php');

add_action('after_setup_theme', 'dfd_kadabra_setup_theme');

if (!function_exists('dfd_kadabra_setup_theme')) {
	function dfd_kadabra_setup_theme() {

		// Enqueue theme scripts and styles
		add_action('wp_enqueue_scripts', 'dfd_kadabra_enq_fonts');
		add_action('wp_enqueue_scripts', 'dfd_kadabra_scripts', 100);

		// Enqueue admin scripts and styles
		add_action('admin_enqueue_scripts', 'dfd_kadabra_admin_css');

		// Template Wrapping
		add_filter( 'template_include', array( 'DFD_Wrapping', 'wrap' ), 99 );
		
		add_filter('wp_get_attachment_link', 'dfd_kadabra_prettyadd');
		
		add_filter('widget_text', 'do_shortcode');
		
		add_filter('excerpt_length', 'dfd_kadabra_excerpt_length', 999 );
		add_filter('next_posts_link_attributes', 'dfd_kadabra_posts_link_attributes_1');
		add_filter('previous_posts_link_attributes', 'dfd_kadabra_posts_link_attributes_2');
		add_filter('mce_buttons','dfd_next_page_button');
		
		// Disable default gallery style
		add_filter( 'use_default_gallery_style' , 'dfd_kadabra_use_default_gallery_style_filter' );

		// Make theme available for translation
		load_theme_textdomain('dfd', get_template_directory() . '/lang');
		
		// Register wp_nav_menu() menus
		register_nav_menus(array(
			'primary_navigation' => __('Primary Navigation', 'dfd'),
			'footer_menu' => __('Footer navigation', 'dfd'),
		));

		// Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
		add_theme_support('post-thumbnails');

		// Add post formats (http://codex.wordpress.org/Post_Formats)
		add_theme_support('post-formats', array('gallery','video','quote','audio'));
		
		add_theme_support( 'automatic-feed-links' );

		add_post_type_support('page', 'excerpt');
		
		// Tell the TinyMCE editor to use a custom stylesheet
		add_editor_style('assets/css/editor-style.css');
		
		// Set default values for the upload media box
		dfd_kadabra_setup();
		
		dfd_kadabra_woocommerce_support();
		
		add_action('admin_init', 'envato_toolkit_admin_init');
		
		if (function_exists('dfd_mvb_theme_support')) {
			dfd_mvb_theme_support();
		}
	}
}

if (!function_exists('dfd_mvb_theme_support')) {
	function dfd_mvb_theme_support() {
		add_filter('mvb_fields_filter', 'dfd_mvb_field_filter');
	}
}

if (!function_exists('dfd_mvb_field_filter')) {
	function dfd_mvb_field_filter($fields) {
		unset($fields['main_title_decoration']);
		$fields['main_title_type'] = array(
			'type' => 'select',
			'label' => __('Title type', 'mvb'),
			'default' => 'h2',
			'options' => array(
				'h2' => 'H2',
				'h3' => 'H3',
			),
			'col_span' => 'lbl_third',
		);
		$fields['sub_title_type'] = array(
			'type' => 'select',
			'label' => __('Sub Title type', 'mvb'),
			'default' => 'h3',
			'options' => array(
				'h3' => 'H3',
				'h4' => 'H4',
			),
			'col_span' => 'lbl_half'
		);
		return $fields;
	}
}

if (!function_exists('dfd_kadabra_setup')) {
	function dfd_kadabra_setup() {
		// Set default values for the upload media box
		update_option('image_default_link_type', 'none' );
		update_option('image_default_size', 'large' );
	}
}

/**
 * Woocommerce support
 */
if (!function_exists('dfd_kadabra_woocommerce_support')) {
	function dfd_kadabra_woocommerce_support() {
		add_theme_support( 'woocommerce' );

		# star rating for proucts in loop
		add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
		if (function_exists('dfd_woocommerce_disable_styles')) {
			dfd_woocommerce_disable_styles();
		}

		# Hook in on activation
		global $pagenow;
		if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
			add_action( 'init', 'dfd_kadabra_woocommerce_image_dimensions', 1 );
		}
	}
}
