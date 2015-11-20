<?php
add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {
    if ( ! class_exists( 'cmb_Meta_Box' ) )
        require_once locate_template('/inc/lib/metabox/init.php');
}

require_once 'page-boxes.php';
require_once 'post-boxes.php';

if (DfdThemeSettings::get('stan_header')){
    require_once 'headers-boxes.php';
}

require_once(dirname(__FILE__) . '/features-boxes.php'); 
require_once(dirname(__FILE__) . '/testimonial-boxes.php'); 
require_once(dirname(__FILE__) . '/portfolio-boxes.php'); 
require_once(dirname(__FILE__) . '/custom-sidebar.php'); 
require_once(dirname(__FILE__) . '/custom-headers.php'); 
require_once(dirname(__FILE__) . '/timeline-boxes.php'); 
require_once(dirname(__FILE__) . '/product-boxes.php'); 


