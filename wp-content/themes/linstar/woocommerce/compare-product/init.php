<?php
/**
* Init
*/
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly
    define( 'king_WOOCOMPARE_URL', get_template_directory_uri() . '/woocommerce/compare-product/' );
    define( 'king_WOOCOMPARE_DIR', get_template_directory_uri() . '/woocommerce/compare-product/' );
	global $king, $king_woocompare;
    // Load required classes and functions
    $king->ext['rqo']( dirname(__FILE__).DS.'woocompare-frontend.php');
    $king->ext['rqo']( dirname(__FILE__).DS.'woocompare-widget.php');
    $king->ext['rqo']( dirname(__FILE__).DS.'woocompare.php');

    // Let's start the game!
    $king_woocompare = new king_Woocompare();