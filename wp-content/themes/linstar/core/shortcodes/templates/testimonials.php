<?php

if ( $posts->have_posts() ){
	
	$eff = rand(0,10);
	if( $eff <= 2 ){
		$eff = 'eff-fadeInUp';
	}else if( $eff > 2 && $eff <=4 ){
		$eff = 'eff-fadeInRight';
	}else if( $eff > 4 && $eff <=8 ){
		$eff = 'eff-fadeInLeft';
	}else{
		$eff = 'eff-flipInY';
	}
	
	switch( $layout ){
		
		case 'slider-1' : 
		
		?>
		<div class="peosays slider nosidearrows centernav">
			<div class="flexslider carousel">
				<ul class="slides">
			<?php
				
				$i = 0;
				$_end = false;
				while ( $posts->have_posts() ) :
					$posts->the_post();
					global $post;
					$options = get_post_meta( $post->ID, 'king_testi' );
					$options = shortcode_atts( array(
						'website'	=> 'www.yourwebsite.com',
						'rate'	=> 5
					), $options[0], false );
					
				if( $i % 2 == 0 ){
					echo '<li>';
					$_end = false;
				}	
			?>
	             
	            <div class="one_half<?php if( $i%2 != 0 )echo ' last'; ?>">
	                <div class="box"><?php echo wp_trim_words( $post->post_content, $words ); ?></div>
	                <div class="who">
	                    <?php 
				    	$img = king::get_featured_image( $post );
						echo '<img src="'.king_createLinkImage( $img, '190x190xc' ).'" alt="" class="cirimg" />';
						?>
	                    <strong><?php the_title(); ?> </strong> <?php echo esc_attr( $options['website'] ); ?>
	                </div>
	            </div> 

	        <?php
	        
	        	if( $i % 2 != 0 ){
					echo '</li>';
					$_end = true;
				}
	        	$i++;
		        endwhile;
		        if( $_end == false )echo '</li>';
	        ?>
				</ul>
			</div>	
        </div>
		
		<?php
		break;
		
		case 'slider-2' : 
		
		?>
		<div class="peosays slider nosidearrows nosidearrows_three">
			<div class="flexslider carousel">
				<ul class="slides">
			<?php
		
				while ( $posts->have_posts() ) :
					$posts->the_post();
					global $post;
					$options = get_post_meta( $post->ID, 'king_testi' );
					$options = shortcode_atts( array(
						'website'	=> 'www.yourwebsite.com',
						'rate'	=> 5
					), $options[0], false );
			?>
			<li>
                
            	<?php 
				    	$img = king::get_featured_image( $post );
						echo '<img src="'.king_createLinkImage( $img, '190x190xc' ).'" alt="" class="cirimg" />';
			    ?>
                <h5><?php the_title(); ?><em>- <?php echo esc_url( $options['website'] ); ?> -</em></h5>
                
                <p><?php echo wp_trim_words( $post->post_content, $words ); ?></p>

			</li>
	        <?php
		        endwhile;
	        ?>
				</ul>
			</div>	
        </div>
		
		<?php
		break;
		
		case 'slider-3' : 
		
		?>
		<div class="peosays slider nosidearrows centernav">
			<div class="flexslider carousel">
				<ul class="slides">
			<?php
				
				$i = 0;
				$_end = false;
				while ( $posts->have_posts() ) :
					$posts->the_post();
					global $post;
					$options = get_post_meta( $post->ID, 'king_testi' );
					$options = shortcode_atts( array(
						'website'	=> 'www.yourwebsite.com',
						'rate'	=> 5
					), $options[0], false );
					
				if( $i % 3 == 0 ){
					echo '<li>';
					$_end = false;
				}	
			?>
	             
	            <div class="one_third<?php if( ($i+1)%3 == 0 )echo ' last'; ?>">
	                <div class="box"><?php echo wp_trim_words( $post->post_content, $words ); ?></div>
	                <div class="who">
	                    <?php 
				    	$img = king::get_featured_image( $post );
						echo '<img src="'.king_createLinkImage( $img, '190x190xc' ).'" alt="" class="cirimg" />';
						?>
	                    <strong><?php the_title(); ?> </strong> <?php echo esc_attr( $options['website'] ); ?>
	                </div>
	            </div> 

	        <?php
	        
	        	$i++;
				if( $i % 3 == 0 ){
					echo '</li>';
					$_end = true;
				}
		        endwhile;
		       
		        if( $_end == false )echo '</li>';
		        
	        ?>
				</ul>
			</div>	
        </div>
        
		<?php
		break;
				
		case 'slider-4' : 
		
		?>
		<div  id="slider-outline">
			<div class="peosays slider nosidepaging nosidearrows" id="sl-view">
				<div class="flexslider carousel clearfix">
					<ul class="slides" id="sl-wrap">
				<?php
					
					$i = 0;
					$_end = false;
					while ( $posts->have_posts() ) :
						$posts->the_post();
						global $post;
						$options = get_post_meta( $post->ID, 'king_testi' );
						$options = shortcode_atts( array(
							'website'	=> 'www.yourwebsite.com',
							'rate'	=> 5
						), $options[0], false );
						
	
					echo '<li class="sl-div';
					if( $i%2 == 0 )echo ' light';
					echo '">';	
				?>
	                <div class="cirpimg">
	                <?php 
				    	$img = king::get_featured_image( $post );
						echo '<img src="'.king_createLinkImage( $img, '190x190xtc' ).'" alt="" class="cirimg" />';
			    	?>
	                </div>
	                <h4 class="roboto"><?php the_title(); ?> <em>- <?php echo esc_attr( $options['website'] ); ?> -</em></h4>
					<p><?php echo wp_trim_words( $post->post_content, $words ); ?></p>
					<br />
				<?php if( !empty( $options['rate'] ) ){
	  				for( $j = 0 ; $j < $options['rate']; $j++  ){
		  				echo '<i class="fa fa-star"></i>';
	  				}		
	  			} ?>
		        <?php
		        
		        	$i++;
					echo '</li>';
			        endwhile;
			        
		        ?>
					</ul>
				</div>
	        </div>
	        <div id="sl-next" onclick="jQuery(this.parentNode).find('.flex-next').click()"><span>&raquo;</span></div>
			<div id="sl-prev" onclick="jQuery(this.parentNode).find('.flex-prev').click()"><span>&laquo;</span></div>
	    </div>
        
		<?php
		break;
		
		case 'slider-5' : 
		
		?>
		<div class="peosays slider nosidearrows centernav">
			<div class="flexslider carousel">
				<ul class="slides">
				<?php
					
					$i = 0;
					$_end = false;
					while ( $posts->have_posts() ) :
						$posts->the_post();
						global $post;
						$options = get_post_meta( $post->ID, 'king_testi' );
						$options = shortcode_atts( array(
							'website'	=> 'www.yourwebsite.com',
							'rate'	=> 5
						), $options[0], false );
						
					if( $i % 2 == 0 ){
						echo '<li>';
						$_end = false;
					}	
				?> 
		            <div class="one_half<?php if( $i%2 != 0 )echo ' last'; ?>">
		                 <?php 
					    	$img = king::get_featured_image( $post );
							echo '<img src="'.king_createLinkImage( $img, '190x190xc' ).'" alt="" />';
						?>
		                 <strong><?php the_title(); ?> <em>- <?php echo esc_attr( $options['website'] ); ?> -</em></strong>
		                 <p><?php echo wp_trim_words( $post->post_content, $words ); ?></p>
		            </div>

		        <?php
		        
		        	if( $i % 2 != 0 ){
						echo '</li>';
						$_end = true;
					}
		        	$i++;
			        endwhile;
			        if( $_end == false )echo '</li>';
		        ?>
				</ul>
			</div>	
        </div>
		<?php
		break;
				
		case 'slider-6' : 
		
		?>
		<div class="peosays slider nosidearrows centernav">
			<div class="flexslider carousel">
				<ul class="slides king-testi-456">
				<?php
					
					$i = 0;
					$_end = false;
					while ( $posts->have_posts() ) :
						$posts->the_post();
						global $post;
						$options = get_post_meta( $post->ID, 'king_testi' );
						$options = shortcode_atts( array(
							'website'	=> 'www.yourwebsite.com',
							'rate'	=> 5
						), $options[0], false );
	
				?> 
					<li>
						<div class="item">
							<h5 class="roboto"><?php echo wp_trim_words( $post->post_content, 4 ); ?></h5>
			                <?php if( !empty( $options['rate'] ) ){
				  				for( $j = 0 ; $j < $options['rate']; $j++  ){
					  				echo '<i class="fa fa-star"></i>';
				  				}		
				  			} ?>
			                <p><?php echo wp_trim_words( $post->post_content, $words ); ?></p>
			                <div class="who">
								<?php 
							    	$img = king::get_featured_image( $post );
									echo '<img src="'.king_createLinkImage( $img, '100x100xc' ).'" alt="" width="44" />';
								?>
			                    <strong><?php the_title(); ?> <br>
			                    <em><?php echo esc_attr( $options['website'] ); ?></em></strong>
			                </div>
			            </div>
					</li>    
		        <?php
		        	$i++;
			        endwhile;
		        ?>
				</ul>
			</div>	
        </div>
		<?php
		break;

		case 'slider-ms' : 
		
		?>
		<div class="ms-staff-carousel ms-round">
        <!-- testimonials -->
        	<div class="master-slider" id="masterslider2">
				<?php
					
					$i = 0;
					$_end = false;
					while ( $posts->have_posts() ) :
						$posts->the_post();
						global $post;
						$options = get_post_meta( $post->ID, 'king_testi' );
						$options = shortcode_atts( array(
							'website'	=> 'www.yourwebsite.com',
							'rate'	=> 5
						), $options[0], false );
						$img = king::get_featured_image( $post );
						$img = king_createLinkImage( $img, '290x290xc' );
				?> 
					<div class="ms-slide">
		                <img src="<?php echo esc_url($img); ?>" data-src="<?php echo esc_url($img); ?>" alt="lorem ipsum dolor sit"/>  
		                <div class="ms-info">
		                	<br />
		                    <h5><?php the_title(); ?> </h5>
		                    <h6 class="gray"><?php echo esc_attr( $options['website'] ); ?></h6>
		                    <p class="less1">"<?php echo wp_trim_words( $post->post_content, $words ); ?>"</p>
		                </div>     
		            </div><!-- end slide -->
		        <?php
		        	$i++;
			        endwhile;
		        ?>
			</div>
			<div class="ms-staff-info" id="staff-info"> </div>
        </div>
        
		<script type="text/javascript">
			(function($) {
			 "use strict";
			 	$(document).ready(function(){
					var slider = new MasterSlider();
					// adds Arrows navigation control to the slider.
					slider.control('arrows');
					slider.control('bullets');
				
					slider.setup('masterslider' , {
						 width:1400,    // slider standard width
						 height:680,   // slider standard height
						 space:0,
						 speed:45,
						 layout:'fullwidth',
						 loop:true,
						 preload:0,
						 autoplay:true,
						 view:"parallaxMask"
					});
					
					var slider = new MasterSlider();
						slider.setup('masterslider2' , {
							loop:true,
							width:250,
							height:250,
							speed:20,
							view:'focus',
							preload:0,
							space:0,
							space:90,
							viewOptions:{centerSpace:1.6}
						});
						slider.control('arrows');
						slider.control('slideinfo',{insertTo:'#staff-info'});
				});
			})(jQuery);
		</script>
		<?php
		
		wp_enqueue_style( 'king-mslider' );
		wp_enqueue_script( 'king-mslider' );
		
		break;
						
		case '2-columns' : 
		
				
		$i = 1;

		while ( $posts->have_posts() ) :
			$posts->the_post();
			global $post;
			$options = get_post_meta( $post->ID, 'king_testi' );
			$options = shortcode_atts( array(
				'website'	=> 'www.yourwebsite.com',
				'rate'	=> 5
			), $options[0], false );
			?>
	             
            <div class="one_half<?php if( $i%2 === 0 )echo ' last'; ?> animated <?php echo esc_attr( $eff ); ?> delay-<?php echo esc_attr($i); ?>00ms">
            	<div class="bubble">
		        	<?php 
				    	$img = king::get_featured_image( $post );
						echo '<img src="'.king_createLinkImage( $img, '190x190xc' ).'" alt="" />';
					?>
		            <p><?php echo wp_trim_words( $post->post_content, $words ); ?></p>
		        	<div class="clearfix"></div>
				</div>
				<strong><?php echo esc_html( $post->post_title ); ?> <em><?php echo esc_attr( $options['website'] ); ?></em></strong>           
             </div> 

        <?php
        	if( $i%2 === 0 )echo '<div class="clearfix margin_top5"></div>';
        	$i++;
	        endwhile;

		break;
								
		case '3-columns' : 
		
				
		$i = 1;

		while ( $posts->have_posts() ) :
			$posts->the_post();
			global $post;
			$options = get_post_meta( $post->ID, 'king_testi' );
			$options = shortcode_atts( array(
				'website'	=> 'www.yourwebsite.com',
				'rate'	=> 5
			), $options[0], false );
			?>
	             
            <div class="one_third<?php if( $i%3 === 0 )echo ' last'; ?> animated <?php echo esc_attr( $eff ); ?> delay-<?php echo esc_attr($i); ?>00ms">
            	<div class="testimo<?php if($i==2)echo ' highlight'; ?>">
            		<h5 class="caps"><?php echo wp_trim_words( $post->post_title, 3 ); ?></h5>
	                <p><?php echo wp_trim_words( $post->post_content, $words ); ?></p>
	                <div class="lbt">
	                	<?php echo esc_attr( $options['website'] ); ?>
		  				<strong>
			  			<?php if( !empty( $options['rate'] ) ){
			  				for( $j = 0 ; $j < $options['rate']; $j++  ){
				  				echo '<i class="fa fa-star"></i>';
			  				}		
			  			} ?>
		                </strong>
					</div><!-- end -->
		            
		            <b><?php the_title(); ?>
		           		<em><?php echo esc_attr( $options['website'] ); ?></em>
		            </b>
                </div>
            </div> 

        <?php
        	if( $i%3 === 0 )echo '<div class="clearfix margin_top5"></div>';
        	$i++;
	        endwhile;

		break;
		
	}
	
}else {
	echo '<h4>' . __( 'Testimonials not found', KING_DOMAIN ) . '</h4> <a href="'.admin_url('post-new.php?post_type=testimonials').'"><i class="fa fa-plus"></i> Add New Testimonial</a>';
}	
	
?>