<?php

/**
*
*	Theme functions
*	(c) king-theme.com
*
*/
global $king;
/*----------------------------------------------------------*/
#	Theme Setup
/*----------------------------------------------------------*/


function king_themeSetup() {

	load_theme_textdomain( 'king', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ,'title','editor','author','thumbnail','excerpt','custom-fields','page-attributes') );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
	    'primary' => __( 'Primary Menu', KING_DOMAIN ),
	    'top_nav' => __( 'Top Navigation', KING_DOMAIN ),
	    'onepage' => __( 'One Page Menu', KING_DOMAIN ),
	    'onepage-2' => __( 'One Page Menu 2nd', KING_DOMAIN ),
	    'footer' => __( 'Footer Menu', KING_DOMAIN )
	));
	
	/*
	 * This theme supports custom background color and image,
	 * and here we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	) );
	
	add_theme_support( "custom-header", array() ); 

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	
	add_theme_support( "title-tag" );
	
}
add_action( 'after_setup_theme', 'king_themeSetup' );

/*-----------------------------------------------------------------------------------*/
# Comment template
/*-----------------------------------------------------------------------------------*/

function king_comment( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment;
	
	switch ( $comment->comment_type ) :
		case 'pingback' : break;
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'king' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'king' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class('comment_wrap'); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			
			<?php
				$avatar_size = 68;
				if ( '0' != $comment->comment_parent )
					$avatar_size = 39;

				echo '<div class="gravatar">'.get_avatar( $comment, $avatar_size ).'</div>';
						
			?>			
			<div class="comment_content">
				<div class="comment_meta">
					<div class="comment_author">
						<?php
							/* translators: 1: comment author, 2: date and time */
							printf( __( '%1$s - %2$s ', 'king' ),
								sprintf( '%s', get_comment_author_link() ),
								sprintf( '<i>%1$s</i>',
									sprintf( __( '%1$s at %2$s', 'king' ), get_comment_date(), get_comment_time() )
								)
							);
						?>
	
						<?php edit_comment_link( __( 'Edit', 'king' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .comment-author .vcard -->
	
					<?php if ( $comment->comment_approved == '0' ) : ?>
						<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'king' ); ?></em>
						<br />
					<?php endif; ?>
	
				</div>

				<div class="comment_text">
					<?php comment_text(); ?>
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'king' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div>
				
			</div>
		</article><!-- #comment-## -->

	<?php
	break;
	endswitch;
}

/*-----------------------------------------------------------------------------------*/
# Display title with options format
/*-----------------------------------------------------------------------------------*/

add_filter('wp_title', 'king_title');
function king_title( $title ){
	
	global $king, $paged, $page;

	$title = trim( str_replace( array( '&raquo;', get_bloginfo( 'name' ), '|' ),array( '', '', ''), $title ) );
	
	if( $king->cfg['titleSeparate'] == '' )$king->cfg['titleSeparate'] = '&raquo;';
	
	ob_start();
	
	if( is_home() || is_front_page() )
	{
		if( !empty( $king->cfg['homeTitle'] ) )
		{
			echo esc_html( str_replace( array('%Site Title%', '%Tagline%' ), array( get_bloginfo( 'name' ), get_bloginfo( 'description', 'display' ) ), $king->cfg['homeTitle'] ) );
		}else{
			$site_description = get_bloginfo( 'description', 'display' );
			if( $king->cfg['homeTitleFm'] == 1 )
			{
				bloginfo( 'name' );
				if ( $site_description )
					echo ' '.$king->cfg['titleSeparate']." $site_description";	
				
			}else if( $king->cfg['homeTitleFm'] == 2 ){
				if ( $site_description )
					echo esc_html( $king->cfg['titleSeparate'] )." $site_description";
				bloginfo( 'name' );
			}else{
				bloginfo( 'name' );
			}
		}	
	
	}else if( is_page() || is_single() )
	{
		
		if( $king->cfg['postTitleFm'] == 1 )
		{

			echo esc_html( $title.' '.$king->cfg['titleSeparate'].' ' );
			bloginfo( 'name' );
			
		}else if( $king->cfg['postTitleFm'] == 2 ){
			bloginfo( 'name' );
			echo esc_html( ' '.$king->cfg['titleSeparate'].' '.$title );
		}else{
			echo esc_html( $title );
		}
		
	}else{
		if( $king->cfg['archivesTitleFm'] == 1 )
		{
			echo esc_html( $title.' '.$king->cfg['titleSeparate'].' ' );
			bloginfo( 'name' );
			
		}else if( $king->cfg['archivesTitleFm'] == 2 ){
			bloginfo( 'name' );
			echo esc_html( ' '.$king->cfg['titleSeparate'].' '.$title );
		}else{
			echo esc_html( $title );
		}
	}
	if ( $paged >= 2 || $page >= 2 )
		echo esc_html( ' '.$king->cfg['titleSeparate'].' ' . 'Page '. max( $paged, $page ) );
		
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;	
}
	
/*-----------------------------------------------------------------------------------*/
# Set meta tags on header for SEO onpage
/*-----------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------*/
function king_meta(){

	global $king, $post;
	
?>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="generator" content="devn" />
<?php if( $king->cfg['responsive'] == 1 ){ ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<?php } ?><?php if( is_home() || is_front_page() ){ ?>
<meta name="description" content="<?php echo esc_attr( $king->cfg['homeMetaDescription'] ); ?>" />
<meta name="keywords" content="<?php echo esc_attr( $king->cfg['homeMetaKeywords'] ); ?>" />
<?php }else{ ?>
<meta name="description" content="<?php echo esc_attr( $king->cfg['otherMetaDescription'] ); ?>" />
<meta name="keywords" content="<?php echo esc_attr( $king->cfg['otherMetaKeywords'] ); ?>" />
<?php }

if( $king->cfg['ogmeta'] == 1 && ( is_page() || is_single() ) ){
?>
<meta property="og:type" content="devn:photo" />
<meta property="og:url" content="<?php echo get_permalink( $post->ID ); ?>" />
<meta property="og:title" content="<?php echo esc_attr( $post->post_title ); ?>" />
<meta property="og:description" content="<?php

if( is_front_page() || is_home() ){ 
	echo esc_attr( bloginfo( 'description' ) ); 
}else {
	
	if( !empty( $post->ID ) ){
		
		$pagedes = get_post_meta( $post->ID, '_king_page_description', true );
		
		if( !empty( $pagedes ) ){
			echo esc_attr( $pagedes );
		}else if( !empty( $post->post_excerpt ) ){
			echo esc_attr( wp_trim_words( $post->post_excerpt, 50 ) );
		}else if( strpos( $post->post_content, '[vc_row') === false ){
			echo esc_attr( wp_trim_words( $post->post_content, 50 ) );
		}else{
			echo esc_attr( $post->post_title );
		}
	}
	
} 

?>" />
<meta property="og:image" content="<?php print( $king->get_featured_image( $post ) ); ?>" />
<?php } ?>
<meta name="author" content="<?php echo esc_attr( $king->cfg['authorMetaKeywords'] ); ?>" />
<meta name="contact" content="<?php echo esc_attr( $king->cfg['contactMetaKeywords'] ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php
	if( !empty( $king->cfg['favicon'] ) ){
		echo '<link rel="shortcut icon" href="'.$king->cfg['favicon'].'" type="image/x-icon" />';
	}
}


/*-----------------------------------------------------------------------------------*/
# Filter content at blog posts
/*-----------------------------------------------------------------------------------*/


function king_the_content_filter( $content ) {
  
  if( is_home() ){
	  
	  $content = preg_replace('/<ifr'.'ame.+src=[\'"]([^\'"]+)[\'"].*iframe>/i', '', $content );
	  
  }
  
  return $content;
}

add_filter( 'the_content', 'king_the_content_filter' );

function king_blog_link() {
  
  if( get_option( 'show_on_front', true ) ){
	  
	  $_id = get_option( 'page_for_posts', true );
	  if( !empty( $_id ) ){
		  echo get_permalink( $_id );
		  return;
	  }
  }
  
  echo SITE_URI;
  
}


function king_createLinkImage( $source, $attr ){

	global $king;
	
	$attr = explode( 'x', $attr );
	$arg = array();
	if( !empty( $attr[2] ) ){
		$arg['w'] = $attr[0];
		$arg['h'] = $attr[1];
		$arg['a'] = $attr[2];
		if( $attr[2] != 'c' ){
			$attr = '-'.implode('x',$attr);
			$arg['a'] = $attr[2];
		}else{
			$attr = '-'.$attr[0].'x'.$attr[1];
		}
	}else if( !empty( $attr[0] ) && !empty( $attr[1] ) ){
		$arg['w'] = $attr[0];
		$arg['h'] = $attr[1];
		$attr = '-'.$attr[0].'x'.$attr[1];
	}else{
		return $source;
	}
	
	$source = strrev( $source );
	$st = strpos( $source, '.');
	
	if( $st === false ){
		return strrev( $source ).$attr;
	}else{
		
		$file = str_replace( array( SITE_URI.'/', '\\', '/' ), array( ABSPATH, DS, DS ), strrev( $source ) );
		
		$_return = strrev( substr( $source, 0, $st+1 ).strrev($attr).substr( $source, $st+1 ) );
		$__return = str_replace( array( SITE_URI.'/', '\\', '/' ), array( ABSPATH, DS, DS ), $_return );

		if( file_exists( $file ) && !file_exists( $__return ) ){
			ob_start();
			$king->processImage( $file, $arg, $__return );
			ob_end_clean();
		}
		
		return $_return;
		
	}
}




