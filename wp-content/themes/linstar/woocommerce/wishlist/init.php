<?php
// DEVN WISHLIST STARTUP
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

define( 'king_WISHLIST', true );
    if( !defined( 'king_WISHLIST_URL' ) ) { define( 'king_WISHLIST_URL', get_template_directory_uri() . '/woocommerce' ); }
    define( 'WISHLIST_URL', king_WISHLIST_URL . '/wishlist/' );
    define( 'WISHLIST_DIR', dirname( __FILE__ ) . '/' );

global $woocommerce;


if( isset($woocommerce) ) {
    // Load necessary files
    include WISHLIST_DIR.'functions-wishlist.php';
    include WISHLIST_DIR.'wishlist.php';
    include WISHLIST_DIR.'wishlist-init.php';
    include WISHLIST_DIR.'wishlist-install.php';
    
    if( king_wishlist_actived() ) {
        include WISHLIST_DIR.'wishlist-ui.php';
        include WISHLIST_DIR.'wishlist-shco.php';
    }
    
    // ============
    global $king_wishlist;
    $king_wishlist = new king_WISHLIST( $_REQUEST );
}