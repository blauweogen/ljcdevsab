<?php

if ( $posts->have_posts() ) {
	$i = 1;
	while ( $posts->have_posts() ) :
		
		$posts->the_post();
		global $post;
	?>
	
		<div class="bbox animated eff-fadeInUp delay-<?php echo ($i); ?>00ms">
			
			<?php if( $i%2 !== 0  ){ ?>
			
				<div class="one_half">
					<a class="king-post-thumbnail" href="<?php the_permalink(); ?>">
					<?php
						$img = king::get_featured_image( $post );
						echo '<img src="'.king_createLinkImage( $img, '870x330xc' ).'" alt="" class="rimg" />';
					?>
					</a>
					<div class="date">
						<a href="<?php the_permalink(); ?>">
							<strong><?php the_time( 'd' ); ?></strong> 
							<p><?php the_time( 'M' ); ?><br><?php the_time( 'Y' ); ?></p>
						</a>
						<a href="<?php the_permalink(); ?>#comments"><i class="fa fa-comments"></i></a>
						<a href="<?php the_permalink(); ?>"><i class="fa fa-file"></i></a>
					</div>
				</div>
				<div class="one_half last">
		    		<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
		            <p><?php echo wp_trim_words($post->post_content, 20); ?></p>
				</div>
			
			<?php }else{ ?>
			
				<div class="one_half last">
		    		<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
		            <p><?php echo wp_trim_words($post->post_content, 20); ?></p>
				</div>
				<div class="one_half">
					<a class="king-post-thumbnail" href="<?php the_permalink(); ?>">
					<?php
						$img = king::get_featured_image( $post );
						echo '<img src="'.king_createLinkImage( $img, '870x330xc' ).'" alt="" class="rimg" />';
					?>
					</a>
					<div class="date">
						<a href="<?php the_permalink(); ?>">
							<strong><?php the_time( 'd' ); ?></strong> 
							<p><?php the_time( 'M' ); ?><br><?php the_time( 'Y' ); ?></p>
						</a>
						<a href="<?php the_permalink(); ?>#comments"><i class="fa fa-comments"></i></a>
						<a href="<?php the_permalink(); ?>"><i class="fa fa-file"></i></a>
					</div>
				</div>
				
			<?php } ?>
			
		</div>
		
		<div class="clearfix margin_top3"></div>
		
	<?php
		$i += 1;
		endwhile;
	}
	// Posts not found
	else {
		echo '<h4>' . __( 'Posts not found', KING_DOMAIN ) . '</h4>';
	}
?>
