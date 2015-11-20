	<?php
	
	if ( $posts->have_posts() ) {
		$i = 1;
		while ( $posts->have_posts() ) :
			$posts->the_post();
			global $post;
		?>
				
		<div class="one_fourth_less <?php if( $i == 4 )echo 'last'; ?> animated eff-fadeInUp delay-<?php echo ($i); ?>00ms">
			<a class="king-post-thumbnail" href="<?php the_permalink(); ?>">
			<?php 
				
				$img = king::get_featured_image( $post );
				echo '<img src="'.king_createLinkImage( $img, '275x220xc' ).'" alt="" class="rimg" />';
			?>
			</a>
	        <h4 title="<?php echo esc_attr($post->post_title); ?>"><?php echo wp_trim_words($post->post_title, 3); ?></h4>
	        <p><?php echo wp_trim_words($post->post_content, 12); ?></p>
	    	<a href="<?php the_permalink(); ?>" class="button two"><?php _e( 'Read More', KING_DOMAIN ); ?></a>
	        
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
