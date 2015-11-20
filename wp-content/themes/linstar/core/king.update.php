<?php
/**
* (c) King-theme.com
*/

if( is_admin() ){

	class king_update{
		
		function __construct() {
			 add_action('init',  array($this, 'check' ), 88);
		}
		
		private function ver( $st = '' ){
			return intval( substr( str_replace( '.', '', $st ).'000', 0, 3 ) );
		}
		
		public function check(){
			
			$oname = strtolower( THEME_NAME) .'_curentversion';
			$verDB = self::ver( get_option( $oname, true ) );
			$verRL = self::ver( KING_VERSION );

			if( $verDB < $verRL ){
				
				/**
				*	 If current version from database lower than real version of the theme
				*/
				
				if( $verDB < 402 ){
					self::_402();
				}
				if( $verDB < 403 ){
					// Do stuff in next update
				}
				
				self::update_plugins();
				
				if( add_option( $oname, $verRL ) === false ){
					update_option( $oname, $verRL );
				}
			}	
			
		}
		
		private function _402(){
			
			/* Fixed double key active_plugins */
			$plugins = get_option('active_plugins');
			if( is_array( $plugins ) ){
				$_plugins = array();
				foreach( $plugins as $plugin ){
					if ( !in_array( $plugin , $_plugins ) ) {
						array_push( $_plugins, $plugin );
					}
				}
				update_option( 'active_plugins', $_plugins );
			}
			
			/* Create new menu - top nav  */
			$menu_name = 'Top Navigation';
			//wp_delete_nav_menu( $menu_name );
			$menu_exists = wp_get_nav_menu_object( $menu_name );

			if( !$menu_exists){
			    $menu_id = wp_create_nav_menu($menu_name);
			    wp_update_nav_menu_item($menu_id, 0, array(
			        'menu-item-title' =>  __('<i class="fa fa-lock"></i>&nbsp; Login'),
			        'menu-item-url' => home_url( '/?action=login' ), 
			        'menu-item-status' => 'publish'));
			    wp_update_nav_menu_item($menu_id, 0, array(
			        'menu-item-title' =>  __('<i class="fa fa-pencil-square-o"></i>&nbsp; Register'),
			        'menu-item-url' => home_url( '/?action=register' ), 
			        'menu-item-classes' => 'two active',
			        'menu-item-status' => 'publish'));
			    $thememods = 'theme_mods_'.get_option('stylesheet', true);
				$mod = get_option( $thememods );
				if( !isset( $mod ) ){
					$mod = array( 'nav_menu_locations' => array( 'top_nav' => $menu_id )  );
				}else{
					$mod['nav_menu_locations']['top_nav'] = $menu_id;
				}
				add_option( $thememods , $mod ) || update_option( $thememods , $mod );
			}
		}
		
		private function update_plugins(){
			
			global $king, $wp_filesystem;
			
			require_once ABSPATH . 'wp-admin/includes/file.php';
			
			$fields = array( 'action', '_wp_http_referer', '_wpnonce' );
			$canUnZip = false;
			if ( false !== ( $creds = request_filesystem_credentials( '', '', false, false, $fields ) ) ) {
			
				if ( ! WP_Filesystem( $creds ) ) {
				    request_filesystem_credentials( $url, $method, true, false, $fields );
				}else{
					$canUnZip = true;
				}
			}
	
			if( $canUnZip == true ){
			    
				$direct = ABSPATH.DS.'wp-content'.DS.'plugins';
				$path = THEME_PATH.DS.'core'.DS.'sample'.DS.'plugins'.DS;
				
				$plugins = array('linstar-helper', 'LayerSlider', 'contact-form-7', 'js_composer' );
				
				foreach( $plugins as $plugin ){
					$tmpfile = $path.$plugin.'.zip';
					if( !is_dir( $direct.DS.$plugin ) ){
						unzip_file( $tmpfile, $direct );
					}else{
						@rename( $direct.DS.$plugin, $direct.DS.$plugin.'_tmpl' );
						if( unzip_file( $tmpfile, $direct ) ){
							self::removeDir( $direct.DS.$plugin.'_tmpl' );
						}else{
							@rename( $direct.DS.$plugin.'_tmpl', $direct.DS.$plugin );
						}
					}
				
				}//end foreach
					
			}//end if canUnZip		
		}
		
		public function removeDir( $dir = '' ){

			if( $dir != '' && is_dir( $dir ) ){
				
				if ( $handle = opendir( $dir ) ){
					
					while ( false !== ( $entry = readdir($handle) ) ) {
						if( is_dir( $dir.DS.$entry ) && $entry != '..' && $entry != '.' ){
							king_update::removeDir( $dir.DS.$entry );
						}else{
							@unlink( $dir.DS.$entry );
						}
					}
				}
				@rmdir( $dir );
			}	
		}	
	}
	
	new king_update();
	
}
	
?>