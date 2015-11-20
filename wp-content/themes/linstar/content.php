<?php 
/**
 * (c) king-theme.com
 */

	global $king;
 
?><article id="post-<?php the_ID(); ?>" <?php post_class( is_page()?'':'blog_post' ); ?>>

		<div class="entry-content blog_postcontent">
			
			<?php 
				
				global $more,$post;
				
				if( $king->cfg['excerptImage'] == 1 && !is_page() && !is_single() )
				{

					$img = $king->get_featured_image( $post, true );
					if( strpos( $img , 'default.') === false && $img != null  && !is_single() )
					{
						if( strpos( $img , 'youtube') !== false )
						{
							echo '<div class="video_frame">';
							echo '<ifr'.'ame src="'.$img.'"></ifra'.'me>';
							echo '</div>';
							
						}else{
					
							echo '<div class="imgframe animated fadeInUp">';
							if( $more == false ){
								echo '<a title="Continue read: '.get_the_title().'" href="'.get_permalink(get_the_ID()).'">';
							}else{
								echo '<a href="#">';
							}	
							echo '<img alt="'.get_the_title().'" class="featured-image" src="'.$img.'" />';
							echo '</a></div>';	
							
						}	
					}	
				
				};
				
				if( $king->cfg['excerptImage'] == 1 && is_single() ){
					
					$img = $king->get_featured_image( $post, false );
					
					if( strpos( $img , 'default.') === false && $img != null )
					{
					
						echo '<div class="image_frame animated eff-fadeInUp">';
						if( $more == false ){
							echo '<a title="Continue read: '.get_the_title().'" href="'.get_permalink(get_the_ID()).'">';
						}else{
							echo '<a href="#">';
						}	
						echo '<img alt="'.get_the_title().'" class="featured-image" src="'.$img.'" />';
						echo '</a></div>';	
								
					}	
				}
				
				?>
				
				<?php if( !is_page() ): ?>
				
					<header class="entry-header animated ext-fadeInUp">
						
						<h3 class="entry-title">
							<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', KING_DOMAIN ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
								<?php the_title(); ?>
							</a>
							<?php //edit_post_link( __( 'Edit', KING_DOMAIN ), '<span class="edit-link">', '</span>' ); ?>
						</h3>
							
						<?php if ( is_sticky() ) : ?>	
							<h3 class="entry-format">
									<?php _e( 'Featured', KING_DOMAIN ); ?>
							</h3>
						<?php endif; ?>
			
						<?php 
						
						if ( 'post' == get_post_type() ){
	
							if ( $king->cfg['showMeta'] ==  1 ){ 
								king::posted_on( 'post_meta_links ' );
							}
							 
						}
				
						
					echo '</header><!-- .entry-header -->';
				
				endif;
				/*End of header of single post*/
	
				if( ( get_option('rss_use_excerpt') == 1 || is_search() ) && !is_single() && !is_page() ){
			
					the_excerpt();
					echo '<a href="'.get_the_permalink().'">'.__('read more &#187;',KING_DOMAIN).'</a>';
					
				}else{
					the_content( __( 'Read more &#187;', KING_DOMAIN ) ); 				
				}
				
				wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', KING_DOMAIN ) . '</span>', 'after' => '</div>' ) ); 
			
			?>
		</div><!-- .entry-content -->
		
	</article><!-- #post-<?php the_ID(); ?> -->
	<?php

	if( !is_page() ){
		echo '<div class="clearfix divider_line8 artciles-between"></div>';
	}
	?>	