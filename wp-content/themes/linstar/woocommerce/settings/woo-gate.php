<?php
	
	global $woocommerce, $king;
	
	if( empty( $woocommerce ) ){
		if( class_exists( 'WooCommerce' ) ){
			$woocommerce = WooCommerce::instance();
		}
		if( class_exists( 'Woocommerce' ) ){
			$woocommerce = new Woocommerce();
		}
		if( empty( $woocommerce ) ){return;}
	}
	
	$king->woo = (int)substr( str_replace( '.', '', $woocommerce->version.'000' ), 0 , 3 );
	
	if( $king->woo >= 210 ){
	
		add_theme_support( 'woocommerce' );
		include 'woo-functions.php';
		
	}
	
	function woo_gate( $f, $no = false ){

		global $woocommerce, $king;
		
		if( $king->woo < 210 ){
			
			if( $no == true ){
				$theme = wp_get_theme();
				echo '<div class="alert alert-warning" role="alert">';
				echo esc_html( $theme->name ).' Theme Does not support this woocommerce\'s version, Please upgrade to newest version of Woocommerce plugin!</div>';
			}
			
			$baseName = substr( $f , strpos( $f, 'woocommerce')+12 );
			
			include $woocommerce->plugin_path().DS.'templates'.DS.$baseName;
			
			return false;
		}
		
		return true;
	
	}
	
?>