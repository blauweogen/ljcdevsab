<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'cmb_headers_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_headers_metaboxes( array $meta_boxes ) {

    // Start with an underscore to hide fields from custom fields list
    $prefix = 'dfd_headers_';
   
     $meta_boxes[] = array(
        'id'         => 'select_header',
        'title'      => __('Select header type', 'dfd'),
        'pages'      =>  get_post_types(),
        'context'    => 'side',
        'priority'   => 'default',
        'show_names' => true, // Show field names on the left
        'fields'     => array(         
            array(
                'name' => 'Header_Type',
                'desc' => '',
                'id' =>  $prefix.'header_style',
                'type' => 'header_select',   
                'std'  => 'Left Sidebar'
            ),
        ),
    );

    // Add other metaboxes as needed

    return $meta_boxes;
}