	<?php

	if ( $posts->have_posts() ) {
		$i = 1;
		while ( $posts->have_posts() ) :
			
			$posts->the_post();
			global $post;
		?>
		
			<div class="one_third<?php if( $i%3 == 0 )echo ' last '; ?> animated eff-fadeInUp delay-<?php echo ($i); ?>00ms">
				<div class="box">
					<a class="king-post-thumbnail" href="<?php the_permalink(); ?>">
						<span><?php echo wp_trim_words($post->post_title, 4);  ?></span>
					<?php 
						
						$img = king::get_featured_image( $post );
						echo '<img src="'.king_createLinkImage( $img, '780x400xc' ).'" alt="" />';
					?>
					</a> 
				</div>
		    </div>
		<?php
			$i++;
			endwhile;
		}
		// Posts not found
		else {
			echo '<h4>' . __( 'Posts not found', KING_DOMAIN ) . '</h4>';
		}
	?>
