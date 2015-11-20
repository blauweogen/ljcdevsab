<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'crum_headers_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */

function crum_headers_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'crum_headers_';

	$meta_boxes[] = array(
		'id'         => 'header_img_metabox',
		'title'      => __('Page header background', 'dfd'),
		'pages'      => array('post', 'page', 'my-product'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
	            'name' => 'Background image',
	            'desc' => __('Select image pattern for header background', 'dfd'),
	            'id'   => $prefix . 'bg_img',
                'type' => 'file',
                'save_id' => false, // save ID using true
				'std'  => ''
	        ),
            array(
                'name' => 'Background color',
                'desc' => __('Select color for header background', 'dfd'),
                'id'   => $prefix . 'bg_color',
                'type' => 'colorpicker',
                'save_id' => false, // save ID using true
                'std'  => ''
            ),
            array(
                'name' => __('Page subtitle', 'dfd'),
                'desc'	=> '',
                'id'	=> $prefix . 'subtitle',
                'type'	=> 'text'
            ),
		),
	);

	// Add other metaboxes as needed

	return $meta_boxes;
}
