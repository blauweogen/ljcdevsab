<?php

global $king;	

$i = 0; $colsn = '';
if( count( $prcs  ) ){
	
	$centerAl = '';
	if( $atts['amount'] == 1 || (  $atts['amount'] == 2 &&  $atts['style'] == 3  ) ){	
		$centerAl = 'float:left;height:1px;width: 35%;';
	}	
	if( $atts['amount'] == 2 ){
		$centerAl = 'float:left;height:1px;width: 15%;';
	}
	if( $atts['amount'] == 3 && ( $atts['style'] == 4  ) ){
		$centerAl = 'float:left;height:1px;width: 12%;';
	}
	
	if(  $atts['style'] == 1 ){
		
		if( $centerAl != '' )echo '<div style="'.$centerAl.'"></div>';
		
		foreach( $prcs as $prc ){
	
			$pricing = get_post_meta( $prc->ID , 'king_pricing' );
			if( !empty( $pricing ) ){
				$pricing  = $pricing[0];
			}else{
					$pricing = array( 'price' => '$100', 'per' => 'per month', 'trydes' => 'Making this the first true generator necessary on the Internet.', 'trytext' => 'Try Free for 30 Days', 'trylink' => '#', 'attr' => "Option 1\nOption 2", 'morelink' => '#', 'textsubmit' => 'Choose Plan', 'linksubmit' => '#' );
				}
				$i++;
				switch( $i ){
				case 2: $colsn = ' two'; break;
				case 3: $colsn = ' three'; break;
				case 4: $colsn = ' four'; break;
				default : $colsn = ''; break;
			}
				
				if( $atts['active'] == $i ){
				$colsn .= ' active';
			}
			?>
			
			<?php if( empty( $atts['class'] ) ){ ?>	
			<div class="<?php if( $atts['amount'] ==3  )echo 'one_third';else echo 'one_fourth'; if( ($i == 4 && $atts['amount'] !=3) || ($i == 3 && $atts['amount'] ==3) )echo ' last'; ?> animated <?php echo esc_attr($eff); ?> delay-<?php echo esc_attr( $i+1 ); ?>00ms">
				<div class="pricingtable9">
			<?php }else{
				echo '<div class="'.esc_attr($atts['class']).'">';
			} ?>
	                <h3><?php echo esc_html( $prc->post_title ); ?></h3>
	                <strong>
	                	<?php echo esc_html( $pricing['price'] ); ?>
	                </strong>	
	                <b> / <?php echo esc_html( $pricing['per'] ); ?></b>
	                <br />
	                <br />
	                <a href="<?php echo esc_url( $pricing['linksubmit'] ); ?>" class="button five">
                		<?php echo esc_html( $pricing['textsubmit'] ); ?>
                	</a>
		            <span>
		            	<?php
			            	$pros = explode( "\n", $pricing['attr'] );
			            	if( count( $pros ) ){
				            	
				            	foreach( $pros as $pro ){
					            	print( $atts['icon'].$pro.'<br />' );
				            	}
			            	}
		            	?>
		            </span>						
		        </div>
	        <?php if( empty( $atts['class'] ) ){ ?>	
	        </div>
			<?php } ?>
			
			<?php
		
			}
	
	}else if(  $atts['style'] == 2 ){
		
		if( $centerAl != '' ) echo '<div style="'.$centerAl.'"></div>';
		
		foreach( $prcs as $prc ){ 
		
			$i++;
			$last = '';
			$gray = '';
			
			if( $i == $atts['amount'] ){
				$last = ' last';
			}
			if( $i == $atts['active'] ){
				$gray = ' highlight';
			}
			
			$pricing = get_post_meta( $prc->ID , 'king_pricing' );
			
			if( !empty( $pricing ) ){
				$pricing  = $pricing[0];
			}else{
				$pricing = array( 'price' => '$100', 'per' => 'per month', 'trydes' => 'Making this the first true generator necessary on the Internet.', 'trytext' => 'Try Free for 30 Days', 'trylink' => '#', 'attr' => "Option 1\nOption 2", 'morelink' => '#', 'textsubmit' => 'Choose Plan', 'linksubmit' => '#' );
			}
					
		?>
			<div class="one_third<?php echo esc_attr( $last.$gray ); ?> animated <?php echo esc_attr($eff); ?> delay-<?php echo esc_attr( $i+1 ); ?>00ms">
			
				<div class="pricingtable3">
		        	<div class="titie">
		        		<h2 class="roboto"><?php echo esc_html( $prc->post_title ); ?></h2>
		        	</div>
		            <div class="price">
		            	<h2 class="roboto">$</h2>
		            	<h1 class="roboto"><?php echo esc_html( str_replace( '$','', $pricing['price']) ); ?></h1>
		            	<h5 class="roboto">/<?php echo esc_html( $pricing['per'] ); ?></h5> 
		            </div>
	                <div class="info">
		                <?php
			                
			                $pros = explode( "\n", $pricing['attr'] );
			            	if( count( $pros ) ){
				            	
				            	foreach( $pros as $pro ){
					            	echo '<p>'.$atts['icon'].$pro.'</p>';
				            	}
				            	
			            	}
			                
		                ?>
		                <a href="<?php echo esc_url( $pricing['linksubmit'] ); ?>" class="but_small1">
		                	<?php echo esc_html( $pricing['textsubmit'] ); ?>
		                </a>

		              </div>
		    	</div>
	    	</div>
	    	
	    <?php 
	   
	   }
	    
	
	}else if(  $atts['style'] == 3 ){
		
		if( $centerAl != '' )echo '<div style="'.$centerAl.'"></div>';
		
		foreach( $prcs as $prc ){ 
		
			$i++;
			$gray = '';
			$last = '';
			if( $i == $atts['active'] ){
				$gray = ' highlight active';
			}
			if( $i%3 == 0 ){
				$last = ' last';
			}
			$pricing = get_post_meta( $prc->ID , 'king_pricing' );
			
			if( !empty( $pricing ) ){
				$pricing  = $pricing[0];
			}else{
				$pricing = array( 'price' => '$100', 'per' => 'per month', 'trydes' => 'Making this the first true generator necessary on the Internet.', 'trytext' => 'Try Free for 30 Days', 'trylink' => '#', 'attr' => "Option 1\nOption 2", 'morelink' => '#', 'textsubmit' => 'Choose Plan', 'linksubmit' => '#' );
			}
					
		?>
		<div class="one_third_less <?php echo esc_attr($last); ?> animated <?php echo esc_attr($eff); ?> delay-<?php echo esc_attr( $i+1 ); ?>00ms">
			<div class="pricbox">
				<div class="title<?php echo esc_attr($gray); ?>">
    
	            	<h4 class="caps"><b><?php echo esc_html( $prc->post_title ); ?></b></h4>
	                <p><?php _e( 'for you always', KING_DOMAIN ); ?></p>
	                                
	                 <strong><sup>$</sup><?php echo esc_html( str_replace( '$','', $pricing['price']) ); ?></strong>
	                   
	            </div>
                <ul>
                	<?php
		                
		                $pros = explode( "\n", $pricing['attr'] );
		            	if( count( $pros ) ){
			            	
			            	foreach( $pros as $pro ){
				            	echo '<li>'.$atts['icon'].$pro.'</li>';
			            	}
		            	} 
	                ?>
                </ul>

	            <a href="<?php echo esc_url( $pricing['linksubmit'] ); ?>" class="button sixteen two <?php echo esc_attr( $gray ); ?>">
	            	<span><i class="fa fa-shopping-cart"></i></span> <?php echo esc_html( $pricing['textsubmit'] ); ?>
	            </a>
			</div>
		</div>
	    	
	    <?php 
	   
	   }
	   
	}else if( $atts['style'] == 4){
	
		if( $centerAl != '' )echo '<div style="'.$centerAl.'"></div>';
		
		foreach( $prcs as $prc ){
	
			$pricing = get_post_meta( $prc->ID , 'king_pricing' );
			if( !empty( $pricing ) ){
				$pricing  = $pricing[0];
			}else{
					$pricing = array( 'price' => '$100', 'per' => 'per month', 'trydes' => 'Making this the first true generator necessary on the Internet.', 'trytext' => 'Try Free for 30 Days', 'trylink' => '#', 'attr' => "Option 1\nOption 2", 'morelink' => '#', 'textsubmit' => 'Choose Plan', 'linksubmit' => '#' );
				}
				$i++;
				switch( $i ){
				case 2: $colsn = ' two'; break;
				case 3: $colsn = ' three'; break;
				case 4: $colsn = ' four'; break;
				default : $colsn = ''; break;
			}
				
			if( $atts['active'] == $i ){
				$colsn .= ' highlight';
			}
			?>
				
			<div class="pricings <?php echo esc_attr( $colsn ); ?> <?php if( $i == 4 )echo ' last'; ?> animated <?php echo esc_attr($eff); ?> delay-<?php echo esc_attr( $i+1 ); ?>00ms">			                
				<div class="title">
					<h4><?php echo esc_html( $prc->post_title ); ?></h4>
					<strong><?php echo esc_html( $pricing['price'] ); ?></strong>
					/<?php echo esc_html( $pricing['per'] ); ?>
				</div>
				<ul>
				<?php
		                
	                $pros = explode( "\n", $pricing['attr'] );
	            	if( count( $pros ) ){
		            	
		            	foreach( $pros as $pro ){
			            	echo '<li>'.$atts['icon'].$pro.'</li>';
		            	}
	            	} 
                ?>
				</ul>
                <div class="clearfix margin_top1"></div>

                <a href="<?php echo esc_url( $pricing['linksubmit'] ); ?>" class="button nine<?php if( $atts['active'] == $i )echo ' white'; ?>">
            		<?php echo esc_html( $pricing['textsubmit'] ); ?>
            	</a>
	        </div>
			
			<?php
		
			}
	}else if( $atts['style'] == 5){
	
		if( $centerAl != '' )echo '<div style="'.$centerAl.'"></div>';
		
		foreach( $prcs as $prc ){
	
			$pricing = get_post_meta( $prc->ID , 'king_pricing' );
			if( !empty( $pricing ) ){
				$pricing  = $pricing[0];
			}else{
				$pricing = array( 'price' => '$100', 'per' => 'per month', 'trydes' => 'Making this the first true generator necessary on the Internet.', 'trytext' => 'Try Free for 30 Days', 'trylink' => '#', 'attr' => "Option 1\nOption 2", 'morelink' => '#', 'textsubmit' => 'Choose Plan', 'linksubmit' => '#' );
			}
			
			$i++;

			$colsn = '';
			$btn = '';	
			if( $atts['active'] == $i ){
				$colsn = ' highlight';
				$btn = ' white';
			}
			?>
				
			<div class="box <?php echo esc_attr( $colsn ); ?> <?php if( $i == 1 )echo ' first '; ?>  <?php if( $i == 4 )echo ' last '; ?> animated <?php echo esc_attr($eff); ?> delay-<?php echo esc_attr( $i+1 ); ?>00ms">			                
				<ul>
					<?php
		                
		                if( $i > 1 ){
			                echo '<li class="title"><h3>'.esc_html( $prc->post_title ).' <strong>'.esc_html( $pricing['price'] ).'</strong> <em>/'.esc_html( $pricing['per'] ).'</em></h3></li>';
		                }
		                
		                $pros = explode( "\n", $pricing['attr'] );
		            	if( count( $pros ) ){
			            	
			            	foreach( $pros as $pro ){
				            	echo '<li>'.$pro.'</li>';
			            	}
		            	} 
		            	
		            	if( $i > 1 ){
							echo '<li><a href="'.esc_url( $pricing['linksubmit'] ).'" class="button twentyfour'.esc_attr($btn).'">'.esc_html( $pricing['textsubmit'] ).'</a></li>';
						}	
						
	                ?>
				</ul>
	        </div>
			
			<?php
		
			}
	}

}else {
	echo 'No pricing table, <a href="'.admin_url('post-new.php?post_type=pricing-tables').'" target="_blank">Add Pricing</a>';
}
