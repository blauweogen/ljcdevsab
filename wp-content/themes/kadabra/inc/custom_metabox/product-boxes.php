<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'dfd_product_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */

function dfd_product_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'dfd_product_';

	$meta_boxes[] = array(
		'id'         => 'product_subtitle_metabox',
		'title'      => __('Product subtitle', 'dfd'),
		'pages'      => array( 'product' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => false, // Show field names on the left
		'fields'     => array(
			array(
	            'name' => 'Subtitle',
	            'desc' => __('Set product subtitle', 'dfd'),
	            'id'   => $prefix . 'product_subtitle',
                'type' => 'text',
                'save_id' => false, // save ID using true
				'std'  => ''
	        ),
		),
	);

	// Add other metaboxes as needed

	return $meta_boxes;
}
