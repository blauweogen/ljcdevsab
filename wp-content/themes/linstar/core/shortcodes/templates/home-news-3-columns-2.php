	<?php
	
	if ( $posts->have_posts() ) {
		$i = 1;
		while ( $posts->have_posts() ) :
			
			if( $i > 3 ){
				break;	
			}
			
			$posts->the_post();
			global $post;
		?>
		
			<div class="<?php if( $i == 1 || $i == 3 ){echo 'one_fourth';}else{ echo 'one_half';}if( $i == 3 )echo ' last '; ?> animated eff-fadeInUp delay-<?php echo ($i); ?>00ms">
				<a class="king-post-thumbnail" href="<?php the_permalink(); ?>">
				<?php 
					
					$img = king::get_featured_image( $post );
					if( $i == 2 )echo '<img src="'.king_createLinkImage( $img, '585x488xc' ).'" alt="" class="rimg" />';
					else echo '<img src="'.king_createLinkImage( $img, '293x250xc' ).'" alt="" class="rimg" />';
				?>
				</a> 
				<div class="cont">
		            <em><?php the_time( 'M d, Y' ); ?></em>
		            <h4 class="roboto">
		            	<a href="<?php the_permalink(); ?>">
				    		<?php 
				    			if( $i == 2 )echo wp_trim_words($post->post_title, 20);
				    			else echo wp_trim_words($post->post_title, 3); 
				    		?>
				    	</a>
		            </h4>
		            <p><?php echo wp_trim_words($post->post_content, 12); ?></p>
		        </div>
		    </div>
		<?php
			$i += 1;
			endwhile;
		}
		// Posts not found
		else {
			echo '<h4>' . __( 'Posts not found', KING_DOMAIN ) . '</h4>';
		}
	?>
