<?php

if ( $posts->have_posts() ) {
	$i = 1;
	while ( $posts->have_posts() ) :
		
		if( $i > 6 ){
			break;	
		}
		
		$posts->the_post();
		global $post;
		
		
		if($i == 1 || $i == 6){
			$class = "one_half_less";
			$w = 574;
		}else{
			$class = "one_fourth_less";
			$w = 275;
		}
		
		$categories_list = get_the_category_list( __( ', ', KING_DOMAIN ) );
		$categories = explode( ',', $categories_list );
		if( count( $categories ) == 1 ){
			$categories = $categories_list;
		}else if( count( $categories ) > 1 ){
			$categories_list = '';	
			foreach( $categories as $categorie ){
				if( strpos( $categorie, 'Uncategorized' ) === false ){
					$categories_list .= $categorie.', ';
				}
			}
			$categories_list .= '.';
			$categories = str_replace( ', .', '', $categories_list );
		}else{
			$categories = $categories_list;
		}
	?>
	
		<div class="<?php echo esc_attr( $class ); if( $i % 3 == 0 ) echo ' last'; ?> animated eff-fadeInUp delay-<?php echo ($i); ?>00ms">
			
			<?php 					
				$img = king::get_featured_image( $post );
				echo '<img src="'.king_createLinkImage( $img, $w.'x250xc' ).'" alt="" class="rimg" />';
			?>
			
			<div class="bcont">
    	
				<h6><?php print( $categories ); ?></h6>
				<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
				
				<a href="<?php the_permalink(); ?>"><i class="fa fa-angle-right"></i></a>
				
			</div>
				        
	    </div>
	<?php
		if($i == 3) echo '<div class="clearfix margin_top3"></div>';
		$i += 1;
		endwhile;
	}
	// Posts not found
	else {
		echo '<h4>' . __( 'Posts not found', KING_DOMAIN ) . '</h4>';
	}
?>

