<?php
/**
*
* (C) King-Theme.com
*
*/



/********************************************************/
/*                        Actions                       */
/********************************************************/

	// Constants
	
	$theme = wp_get_theme();
	if( !empty( $theme['Template'] ) ){
		$theme = wp_get_theme($theme['Template']);
	}
	
	define('THEME_NAME', $theme['Name'] );
	define('KING_VERSION', $theme['Version'] );
	
	define( 'DS', DIRECTORY_SEPARATOR );
	define('KING_FILE', __FILE__);
	define('OPTIONS_PATH', dirname(__FILE__));
	define('CORE_PATH', dirname(__FILE__));
	define('KING_URL', get_template_directory_uri().'/options' );
	define('SITE_URL', site_url() );
	define('SITE_URI', site_url() );
	define('THEME_URI', get_template_directory_uri() );
	define('THEME_PATH', get_template_directory() );
	define('KING_OPTNAME', 'king');
	define('KING_DOMAIN', KING_OPTNAME);


	// Shared
	include 'king.class.php';
	
	global $king;
	$king = new king();
	$king->init();


