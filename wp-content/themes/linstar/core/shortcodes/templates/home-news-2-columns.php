	<?php
	
	if ( $posts->have_posts() ) {
		$i = 1;
		while ( $posts->have_posts() ) :

			$posts->the_post();
			global $post;
		?>
			<div class="box <?php if( $i%2 == 0 )echo 'last'; ?> animated eff-fadeIn<?php if( $i%2 == 0 )echo 'Right';else echo 'Left'; ?> delay-150ms">
				<a class="king-post-thumbnail" href="<?php the_permalink(); ?>">
				<?php 
					
					$img = king::get_featured_image( $post );
					echo '<img src="'.king_createLinkImage( $img, '585x300xc' ).'" alt="" />';
				?>
				</a>
				<div class="sp_wrapper">
				    <h3 class="caps" title="<?php echo esc_attr($post->post_title); ?>">
				    	<a href="<?php the_permalink(); ?>">
				    		<?php echo wp_trim_words($post->post_title, 3); ?>
				    	</a> 	
				    </h3> 
				    <p class="bigtfont"><?php echo wp_trim_words($post->post_content, 20); ?></p>
					<br><br>
		    		<a href="<?php the_permalink(); ?>" class="button twentyone"><?php _e( 'READ MORE', KING_DOMAIN ); ?></a>
		        </div>
		    </div>
		<?php
			if( $i%2 == 0 )echo '<div class="clearfix margin_top5"></div>';
			$i++;
			endwhile;
		}
		// Posts not found
		else {
			echo '<h4>' . __( 'Posts not found', KING_DOMAIN ) . '</h4>';
		}
	?>
