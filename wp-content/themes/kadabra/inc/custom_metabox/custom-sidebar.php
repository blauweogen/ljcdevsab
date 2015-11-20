<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'cmb_sidebar_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_sidebar_metaboxes( array $meta_boxes ) {

    // Start with an underscore to hide fields from custom fields list
    $prefix = 'crum_sidebars_';

    $meta_boxes[] = array(
        'id'         => 'sidebar_select_metabox',
        'title'      => __('Select custom sidebar', 'dfd'),
        'pages'      => array( 'page','post'), // Post type
        'context'    => 'side',
        'priority'   => 'default',
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'name' => 'Sidebar_Left',
                'desc' => '',
                'id'   => $prefix . 'sidebar_1',
                'type' => 'sidebar_select',
                'std'  => 'Left Sidebar'
            ),
            array(
                'name' => 'Sidebar_Right',
                'desc' => '',
                'id'   => $prefix . 'sidebar_2',
                'type' => 'sidebar_select',
                'std'  => 'Right Sidebar'
            ),
         ),
    );
    
   
    // Add other metaboxes as needed

    return $meta_boxes;
}