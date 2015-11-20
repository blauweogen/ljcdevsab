<?php
	
if ( $posts->have_posts() ) {	

	global $king;	
	if( $atts['column'] == 'sliders' ){
?>
<div class="slider nosidearrows centernav construction-sliders">
	<div class="flexslider carousel">
		<ul class="slides">
			<?php
				
				$i = 0;
				
				while ( $posts->have_posts() ) :
		
					$posts->the_post();
					global $post;
					$i++;
					$image = $king->get_featured_image( $post );
					$_end = false;
					if( $i % 2 != 0 )echo '<li><div class="repro">';
				?>
				
		            <div class="one_half_less <?php if( $i%2 == 0 )echo 'last'; ?>">
		            	<img  src="<?php echo esc_url( $image ); ?>" alt="<?php the_title(); ?>" />
		                <div class="box">
		                	<h4 class="roboto dark caps"><strong><?php the_title(); ?></strong></h4>
		                	 <ul class="list">
		                	<?php
			                	
			                	$_content = get_the_content();
			                	$_content = strip_tags( str_replace( array('<br>','<br />'), array("\n","\n"), $_content ) );
			                	$_content = explode( "\n", $_content );
			                	foreach( $_content as $cot ){
				                	$cot = explode( ':', $cot );
				                	if( !empty( $cot[0] ) ){
					                	echo '<li><strong>'.esc_html( $cot[0] ).': </strong>';
					                	if( !empty( $cot[1] ) )echo esc_html( $cot[1] );
					                	echo '</li>';
				                	}
			                	}
			                	
		                	?>
		                	 </ul>
		                </div>
		            </div><!-- end section -->
	                
			<?php
				
				if( $i % 2 == 0 ){
					echo '</div></li>';
					$_end = true;
				}
				endwhile;
				if( $_end != true ){
					echo '</div></li>';
				}
			?>
		</ul>
	</div>
</div>		

<?php
	
	}else if( $atts['column'] != 'masonry' ){
?>
<div>
	<div id="king-filters-container" class="king-portfolio-filters" <?php if( $atts['filter'] != 'Yes' )echo 'style="visibility: hidden;height: 0px;margin: 0px;width: 100%;clear: both;"'; ?>>
	    <div data-filter="*" class="king-portfolio-filter-item-active king-portfolio-filter-item">
	    	<?php _e('All', KING_DOMAIN ); ?>
	    </div>
	</div>
	
	<div id="king-grid-container" class="king-portfolio-main <?php echo esc_attr( $atts['column'] ); ?>">
	    <ul>
		<?php

			$catsStack = array();
			
			$i = 0;
			switch( $atts['column'] ){
				case 'two' : $col = 2; break;
				case 'four' : $col = 4; break;
				case 'five' : $col = 5; break;
				default : $col = 3; break;
			}
			
			while ( $posts->have_posts() ) :
			
				$posts->the_post();
				global $post;
				$i++;
				$image = $king->get_featured_image( $post );
				$cats = wp_get_post_terms( $post->ID, 'our-works-category' );
				$cateClass = '';
				
				if( count( $cats ) ){
					foreach( $cats as $cat ){
						$cat_name = strtolower( str_replace(array(' ','&amp;','&'),array('-','',''),$cat->name) );
						$cat_args = array( $cat_name, $cat->count );
						if( !in_array( $cat_args, $catsStack ) ){
							array_push( $catsStack , $cat_args );
						}
						$cateClass .= $cat_name.'-category ';
					}	
				}
				
				if( $i%$col == 0 )$cateClass .= ' last';
				
			?>
			
			
			<li class="king-portfolio-item <?php echo esc_attr( $cateClass ); ?>">
				<div class="king-portfolio-item-wrapper">
                    <div class="king-portfolio-image">
                        <img class="noborder" src="<?php echo esc_url( $image ); ?>" alt="<?php the_title(); ?>" /></div>
                    <a href="<?php the_permalink(); ?>" class="king-portfolio-caption-wrap">
                        <div class="king-portfolio-caption">
                            <div class="king-portfolio-caption-body">
                                <div class="king-portfolio-caption-title"><?php the_title(); ?></div>
                                <div class="king-portfolio-caption-desc"><?php echo wp_trim_words( get_the_excerpt(), 7 ); ?></div>
                            </div>
                        </div>
                    </a>
                    <a href="<?php echo esc_url( $image ); ?>"  title="<?php the_title(); ?>" class="btn linkfr view-large lightbox">
                    	<i class="fa fa-search"></i>
                    </a>
                    <a href="<?php the_permalink(); ?>" title="<?php _e('More Detail',KING_DOMAIN); ?>" class="btn linkfr more-detail">
                    	<i class="fa fa-link"></i>
                    </a>
                </div>
             </li>
                
		<?php
			endwhile;
		?>
		
		
		</ul>
		
		<script type="text/javascript">
		<?php 
			
			$btn = '';
			foreach( $catsStack as $_cat ){
				$btn .= ' / <div data-filter=".'.$_cat[0].'-category" class="king-portfolio-filter-item">'.str_replace('-',' ',$_cat[0]);
				$btn .= '</div>';
			}
			echo 'jQuery("#king-filters-container").append(\''.$btn.'\');';
			
		?>
		</script>
		
		
	</div>
</div>

<?php
	
	}else{
		wp_enqueue_style('king-pf');
		wp_enqueue_script('king-pf');
?>	

<div id="filters-container" class="cbp-l-filters-alignCenter">
    <div data-filter="*" class="cbp-filter-item-active cbp-filter-item">
        ALL
    </div>
</div>

<div id="grid-container" class="cbp-l-grid-masonry">
	<?php

		$catsStack = array();
		
		$i = 0;
		while ( $posts->have_posts() ) :
		
			$posts->the_post();
			global $post;
			$i++;
			$image = $king->get_featured_image( $post );
			$cats = wp_get_post_terms( $post->ID, 'our-works-category' );
			$cateClass = '';
			
			if( count( $cats ) ){
				foreach( $cats as $cat ){
					$cat_name = strtolower( str_replace(array(' ','&amp;','&'),array('-','',''),$cat->name) );
					$cat_args = array( $cat_name, $cat->count );
					if( !in_array( $cat_args, $catsStack ) ){
						array_push( $catsStack , $cat_args );
					}
					$cateClass .= $cat_name.'-category ';
				}
			}
			
		?>

    <div class="cbp-item identity  <?php echo esc_attr( $cateClass ); ?>">
        <a class="cbp-caption cbp-lightbox" data-title="<?php echo esc_attr( get_the_title() ); ?><br>by <?php echo esc_attr( ucfirst( get_the_author() ) ); ?>" href="<?php the_permalink(); ?>">
            <div class="cbp-caption-defaultWrap">
                <img src="<?php echo esc_url( $image ); ?>" alt="">
            </div>
            <div class="cbp-caption-activeWrap">
                <div class="cbp-l-caption-alignCenter">
                    <div class="cbp-l-caption-body">
                        <div class="cbp-l-caption-title"><?php echo esc_attr( get_the_title() ); ?></div>
                        <div class="cbp-l-caption-desc">by <?php echo esc_attr( ucfirst( get_the_author() ) ); ?></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    		<?php
		endwhile;
	?>
</div>
<script type="text/javascript">
<?php 
	
	$btn = '';
	foreach( $catsStack as $_cat ){
		$btn .= ' / <div data-filter=".'.$_cat[0].'-category" class="cbp-filter-item">'.strtoupper( str_replace('-',' ',$_cat[0]));
		$btn .= '</div>';
	}
	echo 'jQuery("#filters-container").append(\''.$btn.'\');';
	
?>
</script>       
<?php	
	}
		
}else {
	echo '<h4>' . __( 'Works not found', KING_DOMAIN ) . '</h4> <a href="'.admin_url('post-new.php?post_type=our-works').'"><i class="fa fa-plus"></i> Add New Work</a>';
}	
	
?>