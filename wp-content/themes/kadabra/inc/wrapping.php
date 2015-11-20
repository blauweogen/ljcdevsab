<?php
/**
 * Theme Wrapper
 *
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */

class DFD_Wrapping {
	/**
     * Stores the full path to the main template file
     */
	static $main_template;

	/**
     * Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
     */
	static $base;

	static function wrap( $template ) {
		self::$main_template = $template;

		self::$base = substr( basename( self::$main_template ), 0, -4 );

		if ( 'index' == self::$base )
		self::$base = false;

		$templates = array( 'base.php' );

		if ( self::$base )
		array_unshift( $templates, sprintf( 'base-%s.php', self::$base ) );

		return locate_template( $templates );
	}
}

function crum_template_path() {
	return DFD_Wrapping::$main_template;
}

function crum_template_base() {
	return DFD_Wrapping::$base;
}

//@TODO: Move out HTML helper

class DFD_HTML {
	public static function read_more($url, $text=null) {
		$class = 'read-more';
		if (is_null($text)) {
			$text = __('Read more', 'dfd');
		}
		return sprintf('<a href="%s" class="%s"><span>%s</span></a>', $url, $class, $text);
	}
}

//@TODO: Move out DFD_Carousel helper

class DFD_Carousel {
	public static function controls() {
		$prev = '<a href="#prev" class="jcarousel-control jcarousel-control-prev"><i class="icon-arrow-57"></i></a>';
		$next = '<a href="#nex" class="jcarousel-control jcarousel-control-next"><i class="icon-arrow-58"></i></a>';
		
		echo $prev . $next;
	}
}