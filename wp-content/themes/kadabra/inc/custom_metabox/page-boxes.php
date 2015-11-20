<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'cmb_sample_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_sample_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'crum_page_custom_';

    $meta_boxes[] = array(
        'id'         => 'blog_params',
        'title'      => __('Select Blog parameters', 'dfd'),
        'pages'      => array( 'page', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_on' => array( 'key' => 'page-template', 'value' => array( 'posts-sidebar-sel.php' ) ),
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'name' => 'Select blog page layout',
                'desc' => 'You can select layout for current blog page',
                'id'   => 'blog_layout_select',
                'type' => 'radio_inline',
                'options' => array(
                    array( 'name' => 'Default', 'value' => '', ),
                    array( 'name' => 'No sidebars', 'value' => '1col-fixed', ),
                    array( 'name' => 'Sidebar on left', 'value' => '2c-l-fixed', ),
                    array( 'name' => 'Sidebar on right', 'value' => '2c-r-fixed', ),
                    array( 'name' => '2 left sidebars', 'value' => '3c-l-fixed', ),
                    array( 'name' => '2 right sidebars', 'value' => '3c-r-fixed', ),
                    array( 'name' => 'Sidebar on either side', 'value' => '3c-fixed', ),
                ),
            ),
            array(
                'name' => 'Display posts of certain category?',
                'desc' => 'Check, if you want to display posts from a certain category',
                'id'   => 'blog_sort_category',
                'type' => 'checkbox'
            ),
            array(
                'name' => 'Blog Category',
                'desc'	=> 'Select blog category',
                'id'	=> 'blog_category',
                'taxonomy' => 'category',
                'type' => 'taxonomy_multicheck',
            ),
            array (
                'name' => 'Number of posts ot display',
                'desc'	=> '',
                'id'	=> 'blog_number_to_display',
                'type'	=> 'text'
            ),
        ),
    );

    $meta_boxes[] = array(
        'id'         => 'masonry_blog_params',
        'title'      => __('Select Blog parameters', 'dfd'),
        'pages'      => array( 'page', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_on' => array( 'key' => 'page-template', 'value' => array( 
//			'posts-sidebar-sel.php',
			'tmp-posts-left-img.php', 
			'tmp-posts-masonry-2-left-side.php',
			'tmp-posts-masonry-2-side.php',
			'tmp-posts-masonry-2.php',
			'tmp-posts-masonry-3-left-sidebar.php',
			'tmp-posts-masonry-3-right-sidebar.php',
			'tmp-posts-masonry-3.php', 
			'tmp-posts-right-img.php' ) ),
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'name' => 'Display posts of certain category?',
                'desc' => 'Check, if you want to display posts from a certain category',
                'id'   => 'blog_sort_category',
                'type' => 'checkbox'
            ),
            array(
                'name' => 'Blog Category',
                'desc'	=> 'Select blog category',
                'id'	=> 'blog_category',
                'taxonomy' => 'category',
                'type' => 'taxonomy_multicheck',
            ),
            array (
                'name' => 'Number of posts ot display',
                'desc'	=> '',
                'id'	=> 'blog_number_to_display',
                'type'	=> 'text'
            ),
        ),
    );

	$meta_boxes[] = array(
		'id'         => 'page_bg_metabox',
		'title'      => __('Boxed Page background options', 'dfd'),
		'pages'      => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
	            'name' => __('Background color', 'dfd'),
	            'desc' => __('Color of body of page (page will be set to boxed)', 'dfd'),
	            'id'   => $prefix . 'bg_color',
	            'type' => 'colorpicker',
				'std'  => '#ffffff'
	        ),
            array(
                'name' => __('Fixed backrgound', 'dfd'),
                'desc' => __('Check if you want to bg will be fixed on page scroll', 'dfd'),
                'id'   => $prefix . 'bg_fixed',
                'type' => 'checkbox',
            ),
			array(
				'name' => __('Background image', 'dfd'),
				'desc' => __('Upload an image or enter an URL.', 'dfd'),
				'id'   => $prefix . 'bg_image',
				'type' => 'file',
			),
            array(
                'name'    => __('Background image repeat', 'dfd'),
                'desc'    => '',
                'id'      => $prefix . 'bg_repeat',
                'type'    => 'select',
                'options' => array(
                    array( 'name' => 'All', 'value' => 'repeat', ),
                    array( 'name' => 'Horizontally', 'value' => 'repeat-x', ),
                    array( 'name' => 'Vertically', 'value' => 'repeat-y', ),
                    array( 'name' => 'No-Repeat', 'value' => 'no-repeat', ),
                ),
            ),
		),
	);


    $meta_boxes[] = array(
        'id'         => 'top_text_fields',
        'title'      => __('Block before content', 'dfd'),
        'pages'      => array( 'page', ), // Post type
        'show_on'    => array('key' => 'page-template', 'value' => 'large-right-aside.php' ),
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'name' => __('Text block ( shortcodes will work here )', 'dfd'),
                'id' =>   '_top_page_text',
                'type' => 'wysiwyg',
                'options' => array(
                    'wpautop' => false, // use wpautop?
                    'media_buttons' => false, // show insert/upload button(s)
                    'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
                    'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
                    'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                    'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
                ),
                'std' => ''
            ),
        ),
    );

    $meta_boxes[] = array(
        'id'         => 'cont_text_fields',
        'title'      => __('Additional Text fields', 'dfd'),
        'pages'      => array( 'page', ), // Post type
        'show_on'    => array('key' => 'page-template', 'value' => 'page-contacts.php' ),
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
            'name' => __('Text block', 'dfd'),
            'id' =>   '_contacts_page_text',
            'type' => 'wysiwyg',
            'options' => array(
                'wpautop' => false, // use wpautop?
                'media_buttons' => false, // show insert/upload button(s)
                'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
                'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
                'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
                ),
                'std' => ''
            ),
            array(
                'name' => __('QR Code address', 'dfd'),
                'id' =>   '_contacts_page_qr',
                'type' => 'text',
                'std' => 'http://dfd.name',
            ),
        ),
    );


	// Add other metaboxes as needed

	return $meta_boxes;
}
