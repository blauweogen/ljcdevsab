<div class="king-posts king-posts-default-loop">
	<?php
		// Posts are found
		if ( $posts->have_posts() ) {
			while ( $posts->have_posts() ) :
				$posts->the_post();
				global $post;
				?>

				<div id="king-post-<?php the_ID(); ?>" class="king-post">
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="king-post-thumbnail" href="<?php the_permalink(); ?>"><?php @the_post_thumbnail(); ?></a>
					<?php endif; ?>
					<h2 class="king-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="king-post-meta"><?php _e( 'Posted', KING_DOMAIN ); ?>: <?php the_time( get_option( 'date_format' ) ); ?></div>
					<div class="king-post-excerpt">
						<?php the_excerpt(); ?>
					</div>
					<a href="<?php comments_link(); ?>" class="king-post-comments-link"><?php comments_number( __( '0 comments', KING_DOMAIN ), __( '1 comment', KING_DOMAIN ), __( '%n comments', KING_DOMAIN ) ); ?></a>
				</div>

				<?php
			endwhile;
		}
		// Posts not found
		else {
			echo '<h4>' . __( 'Posts not found', KING_DOMAIN ) . '</h4>';
		}
	?>
</div>