<?php


add_action( 'wp_enqueue_scripts', 'king_enqueue_content', 1 );
add_action( 'wp_enqueue_scripts', 'king_enqueue_content_last', 9999 );
add_action('admin_enqueue_scripts', 'king_enqueue_admin');
add_action( 'admin_head', 'king_admin_head' );

function king_enqueue_content() {
	
	global $king;

	$css_dir = THEME_URI.'/assets/css/'; 
	$js_dir = THEME_URI.'/assets/js/'; 
	
	wp_enqueue_style('king-reset', king_child_theme_enqueue( $css_dir.'reset.css' ), false, KING_VERSION );
	wp_enqueue_style('king-bootstrap', king_child_theme_enqueue( $css_dir.'bootstrap3/css/bootstrap.min.css' ), false, KING_VERSION );
	
	wp_enqueue_style('king-stylesheet', king_child_theme_enqueue( THEME_URI.'/style.css' ), false, KING_VERSION );

	if( $king->cfg['effects'] == 1 ){	
		wp_enqueue_style('king-effects', THEME_URI.'/core/assets/css/animate.css', false, KING_VERSION );
	}

	wp_enqueue_style('king-linstar', king_child_theme_enqueue( $css_dir.'linstar.css'  ), false, KING_VERSION );
	
	wp_enqueue_style('king-options', $css_dir.'options.css', false, time() );
	
	
	wp_register_style('king-menu', king_child_theme_enqueue( $css_dir.'menu.css' ), false, KING_VERSION );
	wp_register_style('king-menu-1', king_child_theme_enqueue( $css_dir.'menu-1.css' ), false, KING_VERSION );
	wp_register_style('king-menu-2', king_child_theme_enqueue( $css_dir.'menu-2.css' ), false, KING_VERSION );
	wp_register_style('king-menu-3', king_child_theme_enqueue( $css_dir.'menu-3.css' ), false, KING_VERSION );
	wp_register_style('king-menu-4', king_child_theme_enqueue( $css_dir.'menu-4.css' ), false, KING_VERSION );
	wp_register_style('king-menu-5', king_child_theme_enqueue( $css_dir.'menu-5.css' ), false, KING_VERSION );
	wp_register_style('king-menu-6', king_child_theme_enqueue( $css_dir.'menu-6.css' ), false, KING_VERSION );
	wp_register_style('king-menu-7', king_child_theme_enqueue( $css_dir.'menu-7.css' ), false, KING_VERSION );
	wp_register_style('king-menu-8', king_child_theme_enqueue( $css_dir.'menu-8.css' ), false, KING_VERSION );
	wp_register_style('king-menu-9', king_child_theme_enqueue( $css_dir.'menu-9.css' ), false, KING_VERSION );
	wp_register_style('king-menu-10', king_child_theme_enqueue( $css_dir.'menu-10.css' ), false, KING_VERSION );
	wp_register_style('king-menu-11', king_child_theme_enqueue( $css_dir.'menu-11.css' ), false, KING_VERSION );
	wp_register_style('king-menu-12', king_child_theme_enqueue( $css_dir.'menu-12.css' ), false, KING_VERSION );
	wp_register_style('king-menu-13', king_child_theme_enqueue( $css_dir.'menu-13.css' ), false, KING_VERSION );
	wp_register_style('king-menu-14', king_child_theme_enqueue( $css_dir.'menu-14.css' ), false, KING_VERSION );
	wp_register_style('king-menu-15', king_child_theme_enqueue( $css_dir.'menu-15.css' ), false, KING_VERSION );
	wp_register_style('king-menu-16', king_child_theme_enqueue( $css_dir.'menu-16.css' ), false, KING_VERSION );
	wp_register_style('king-menu-17', king_child_theme_enqueue( $css_dir.'menu-17.css' ), false, KING_VERSION );
	wp_register_style('king-menu-18', king_child_theme_enqueue( $css_dir.'menu-18.css' ), false, KING_VERSION );
	wp_register_style('king-menu-19', king_child_theme_enqueue( $css_dir.'menu-19.css' ), false, KING_VERSION );
	wp_register_style('king-menu-21', king_child_theme_enqueue( $css_dir.'menu-21.css' ), false, KING_VERSION );
	wp_register_style('king-menu-22', king_child_theme_enqueue( $css_dir.'menu-22.css' ), false, KING_VERSION );
	wp_register_style('king-menu-23', king_child_theme_enqueue( $css_dir.'menu-23.css' ), false, KING_VERSION );
	wp_register_style('king-menu-onepage-1', king_child_theme_enqueue( $css_dir.'menu-onepage-1.css' ), false, KING_VERSION );
	wp_register_style('king-menu-onepage-2', king_child_theme_enqueue( $css_dir.'menu-onepage-2.css' ), false, KING_VERSION );
	wp_register_style('king-menu-onepage-3', king_child_theme_enqueue( $css_dir.'menu-onepage-3.css' ), false, KING_VERSION );
	wp_register_style('king-menu-onepage-4', king_child_theme_enqueue( $css_dir.'menu-onepage-4.css' ), false, KING_VERSION );
	wp_register_style('king-menu-onepage-leftmenu', king_child_theme_enqueue( $css_dir.'menu-onepage-leftmenu.css' ), false, KING_VERSION );

	wp_register_style('king-pf', king_child_theme_enqueue( $css_dir.'king_pf.min.css' ), false, KING_VERSION );
			
	wp_enqueue_style('king-box-shortcodes', king_child_theme_enqueue( $css_dir.'box-shortcodes.css' ), false, KING_VERSION );
	wp_enqueue_style('king-shortcodes', king_child_theme_enqueue( $css_dir.'shortcodes.css' ), false, KING_VERSION );

	
	
	wp_enqueue_script('jquery');
	
	wp_register_script('king-custom', king_child_theme_enqueue( $js_dir.'custom.js' ), false, KING_VERSION, true );
	wp_register_script('king-user', king_child_theme_enqueue( $js_dir.'king.user.js' ), false, KING_VERSION, true );
	wp_register_script('king-viewportchecker', king_child_theme_enqueue( $js_dir.'viewportchecker.js' ), false, KING_VERSION, true );
	wp_register_script('king-prettyphoto', king_child_theme_enqueue( $js_dir.'pretty/js/jquery.prettyPhoto.js' ), false, KING_VERSION, true );
	wp_register_script('king-flexslider', king_child_theme_enqueue( $js_dir.'jquery.flexslider.js' ), false, KING_VERSION, true );
	wp_register_script('king-bacslider', king_child_theme_enqueue( $js_dir.'bacslider.js' ), false, KING_VERSION, true );
	wp_register_script('king-portfolio', king_child_theme_enqueue( $js_dir.'king_portfolio.js' ), false, KING_VERSION, true );
	wp_register_script('king-pf', king_child_theme_enqueue( $js_dir.'jquery.king_pf.min.js' ), false, KING_VERSION, true );
	wp_register_script('king-responsive-tabs', king_child_theme_enqueue( $js_dir.'responsive-tabs.min.js' ), false, KING_VERSION, true );
	
	
	wp_enqueue_style('king-portfolio', king_child_theme_enqueue( $css_dir.'king_portfolio.css' ), false, KING_VERSION );
	wp_register_style('king-sticky', king_child_theme_enqueue( $css_dir.'sticky.css' ), false, KING_VERSION );
	
	
	if( empty($king->cfg['blog_layout']) ){
	   $king->cfg['blog_layout'] = '';
	}
	
	wp_enqueue_script('king-custom');
	wp_enqueue_script('king-user');
	wp_enqueue_script('king-viewportchecker');
	
	wp_enqueue_script('king-prettyphoto');
	wp_enqueue_script('king-flexslider');
	wp_enqueue_script('king-responsive-tabs');
	
	wp_register_style('king-masonry', king_child_theme_enqueue( $css_dir.'blog-masonry.css' ), false, KING_VERSION );
   	wp_register_script('king-masonry', king_child_theme_enqueue( $js_dir.'jquery.masonry.min.js' ), false, KING_VERSION, true );
   	
   	wp_register_style('king-mslider', king_child_theme_enqueue( $css_dir.'mslider.css' ), false, KING_VERSION );
   	wp_register_script('king-mslider', king_child_theme_enqueue( $js_dir.'mslider.min.js' ), false, KING_VERSION, true );

	
	if ( is_singular() ){
			wp_enqueue_script( "comment-reply" );
	}

   /* Register google fonts */
   $protocol = is_ssl() ? 'https' : 'http';
   wp_enqueue_style( 'king-google-fonts', "$protocol://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic|Raleway:400,100,200,300,500,600,700,800,900|Dancing+Script:400,700|Josefin+Sans:400,100,100italic,300,300italic,400italic,600,600italic,700,700italic|Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic|Oswald:400,300,700" );
   
	ob_start();
	$header = $king->path( 'header' );
	if( $header == true ){
		$king->path['header'] = ob_get_contents();
	}
	ob_end_clean();
	
}

function king_enqueue_content_last(){
	
	$css_dir = THEME_URI.'/assets/css/'; 
	wp_enqueue_style('king-ls-style', $css_dir.'ls-style.css', false, KING_VERSION );
	wp_enqueue_style('king-responsive', king_child_theme_enqueue( $css_dir.'responsive.css' ), false, KING_VERSION );
	/*wp_enqueue_style('king-bootstrap-resp', king_child_theme_enqueue( $css_dir.'bootstrap_responsive.css' ), false, KING_VERSION );*/
}

function king_enqueue_admin() {
	
	global $king;
	
	$css_dir = THEME_URI.'/assets/css/'; 
	
	if( $king->page == strtolower( THEME_NAME ).'-panel' ||  $king->page == 'page' ){
		wp_enqueue_style('king-admin', THEME_URI.'/core/assets/css/king-admin.css', false, time() );
	}
	if( $king->page == strtolower( THEME_NAME ).'-importer' ){
		add_thickbox();
	}
	if( $king->page == 'page' ){	
		wp_enqueue_style('king-simple-line-icons.', THEME_URI.'/core/assets/css/simple-line-icons.css', false, time() );
		wp_enqueue_style('king-etlinefont-icons.', THEME_URI.'/core/assets/css/etlinefont.css', false, time() );
		wp_register_script('king-admin', THEME_URI.'/core/assets/js/king-admin.js', false, KING_VERSION, true );
		wp_register_script('king-bs64', THEME_URI.'/core/assets/js/base'.'64.js', false, KING_VERSION, true );
		wp_enqueue_script('king-admin');
		wp_enqueue_script('king-bs64');
		
		__loadMirror();
		
	}
	if( $king->page == 'layerslider' ){
		$protocol = is_ssl() ? 'https' : 'http';
		wp_enqueue_style('king-ls-style', $css_dir.'ls-style.css', false, KING_VERSION );
		wp_enqueue_style( 'king-google-fonts', "$protocol://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic|Raleway:400,100,200,300,500,600,700,800,900|Dancing+Script:400,700|Josefin+Sans:400,100,100italic,300,300italic,400italic,600,600italic,700,700italic|Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic|Oswald:400,300,700" );
	}

}

function king_admin_head() {
	global $king;
	if( $king->page == 'page' ){
		echo '<script type="text/javascript">var site_uri = "'.SITE_URI.'";var theme_uri = "'.THEME_URI.'";var theme_name = "'.THEME_NAME.'";</script>';
	}
	echo '<style type="text/css">.vc_license-activation-notice,.ls-plugins-screen-notice{display: none;}</style>';
}


function __loadMirror(){
	
	global $king;
	
	$uri = THEME_URI.'/core/assets/';
	
	$footer = true; 
	$u = $uri.'codemirror/lib/';
	$ut = $uri.'codemirror/lib/util/';
	$m = $uri.'codemirror/mode/';
	
	wp_enqueue_style('king-codemirror', $u.'codemirror.css', false, KING_VERSION );
	wp_enqueue_style('king-dialog', $u.'util/dialog.css', false, KING_VERSION );
	wp_enqueue_style('king-themeeclipse', $uri.'codemirror/theme/eclipse.css', false, KING_VERSION );
	
	wp_register_script( 'king-codemirror', $u.'codemirror.js', false, KING_VERSION, $footer );
	wp_register_script( 'king-mirrorhighlighter', $ut.'match-highlighter.js', false, KING_VERSION, $footer );
	wp_register_script( 'king-mirrorclosetag', $ut.'closetag.js', false, KING_VERSION, $footer );
	wp_register_script( 'king-mirrorformatting', $ut.'formatting.js', false, KING_VERSION, $footer );
	wp_register_script( 'king-mirrordialog', $ut.'dialog.js', false, KING_VERSION, $footer );
	wp_register_script( 'king-mirrorsearchcursor', $ut.'searchcursor.js', false, KING_VERSION, $footer );
	wp_register_script( 'king-mirrorsearch', $ut.'search.js', false, KING_VERSION, $footer );
	wp_register_script( 'king-mirrorhtmlmixed', $m.'htmlmixed/htmlmixed.js', false, KING_VERSION, $footer );
	wp_register_script( 'king-mirrorxml', $m.'xml/xml.js', false, KING_VERSION, $footer );
	wp_register_script( 'king-mirrorjs', $m.'javascript/javascript.js', false, KING_VERSION, $footer );
	wp_register_script( 'king-mirrorcss', $m.'css/css.js', false, KING_VERSION, $footer );
	wp_register_script( 'king-mirrorclike', $m.'clike/clike.js', false, KING_VERSION, $footer );
	wp_register_script( 'king-mirrorphp', $m.'php/php.js', false, KING_VERSION, $footer );
	
	wp_enqueue_script('king-codemirror');
	wp_enqueue_script('king-mirrorhighlighter');
	wp_enqueue_script('king-mirrorclosetag');
	wp_enqueue_script('king-mirrorformatting');
	wp_enqueue_script('king-mirrordialog');
	wp_enqueue_script('king-mirrorsearchcursor');
	wp_enqueue_script('king-mirrorsearch');
	wp_enqueue_script('king-mirrorhtmlmixed');
	wp_enqueue_script('king-mirrorxml');
	wp_enqueue_script('king-mirrorjs');
	wp_enqueue_script('king-mirrorcss');
	wp_enqueue_script('king-mirrorclike');
	wp_enqueue_script('king-mirrorphp');
	
}


function king_child_theme_enqueue( $url ){
	
	global $king;

	if( $king->template != $king->stylesheet ){
		$path = str_replace( THEME_URI, ABSPATH.'wp-content'.DS.'themes'.DS.$king->stylesheet, $url );
		$path = str_replace( array( '\\', '\/' ), array(DS, DS), $path );

		if( file_exists( $path ) ){
			return str_replace( DS, '/', str_replace( ABSPATH , SITE_URI.'/', $path ) );
		}else{
			return $url;
		}
		
	}else{
		
		return $url;
		
	}
}

