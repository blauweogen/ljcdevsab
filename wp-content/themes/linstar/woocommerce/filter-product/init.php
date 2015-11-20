<?php
/**
* Init
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

    define( 'king_FILTER_URL', get_template_directory_uri() . '/woocommerce/filter-product/' );
    define( 'king_FILTER_DIR', get_template_directory_uri() . '/woocommerce/filter-product/' );
	global $king_filter;
	$path = dirname(__FILE__).DS;
    // Load required classes and functions
    require_once $path.'functions.king-filter.php';
    require_once $path.'king-filter-admin.php';
    require_once $path.'king-filter-frontend.php';
    require_once $path.'king-filter-helper.php';
    require_once $path.'widgets'.DS.'king-filter-widget.php';
    require_once $path.'widgets'.DS.'king-filter-reset-widget.php';
    require_once $path.'king-filter.php';

    $king_filter = new king_FILTER();