<div class="flexslider carousel">
	<div class="flex-viewport">
		<ul class="slides">
	
		<?php
		
		if ( $posts->have_posts() ) {
			$i = 1;
			$endli = false;
			while ( $posts->have_posts() ) :
				$posts->the_post();
				global $post;
				if( $i %2 != 0 ){
					echo '<li>';
					$endli = false;
				}
			?>
					
			<div class="one_half <?php if( $i%2 == 0 )echo 'last'; ?>">
				<a class="king-post-thumbnail" href="<?php the_permalink(); ?>">
				<?php 
					
					$img = king::get_featured_image( $post );
					echo '<img src="'.king_createLinkImage( $img, '220x220xtc' ).'" alt="" />';
				?>
				</a>
				<div class="date"><a href="#"><?php echo get_the_date('d F, Y'); ?></a></div>
		        <h5 class="caps" title="<?php echo esc_attr($post->post_title); ?>"><?php echo wp_trim_words($post->post_title, 3); ?></h5>
		        <p><?php echo wp_trim_words($post->post_content, 12); ?></p>
		        <br />
		    	<a href="<?php the_permalink(); ?>" class="button ten"><?php _e( 'Read More', KING_DOMAIN ); ?></a>
		    </div>
					
			<?php
				if( $i %2 == 0 ){
					echo '</li>';
					$endli = true;
				}
				$i++;
				endwhile;
			}
			if( $endli == false )echo '</li>';
			// Posts not found
			else {
				echo '<h4>' . __( 'Posts not found', KING_DOMAIN ) . '</h4>';
			}
		?>
		</ul>
	</div>
</div>		
