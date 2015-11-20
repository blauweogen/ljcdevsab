<?php

class king_elements {

	public static function icon( $atts ){
		
		$image = $atts['image'];
		if((int)$image > 0 && ($image_url = wp_get_attachment_url( $image, 'thumbnail' )) !== false) {
	        // some stuff
	    }else{
		    $image_url = THEME_URI.'/assets/images/default.png';
	    }
	    if( $image_url != THEME_URI.'/assets/images/default.png' ){
    		echo '<img src="'.esc_url( $image_url ).'" class="element-icon '.esc_attr( $atts['icon_class'] ).'" alt=""/>';
		}else if( strpos( $atts['icon_awesome'], 'empty' ) === false ){
    		echo '<i class="fa fa-'.esc_attr( $atts['icon_awesome'] ).' element-icon '.esc_attr( $atts['icon_class'] ).'"></i>';
		}else if( strpos( $atts['icon_simple_line'], 'empty' ) === false ){
    		echo '<i aria-hidden="true" class="icon-'.esc_attr( $atts['icon_simple_line'] ).' element-icon '.esc_attr( $atts['icon_class'] ).'"></i>';
		}else if( strpos( $atts['icon_etline'], 'empty' ) === false ){
    		echo '<i aria-hidden="true" class="et-'.esc_attr( $atts['icon_etline'] ).' element-icon '.esc_attr( $atts['icon_class'] ).'"></i>';
		}
	}
		
	public static function display( $atts ){
    
	?>	
		
		<div class="king-elements <?php echo esc_attr( $atts['class'] ); ?>">
        	<?php
        	
        		self::icon( $atts );
        	
	        	if( !empty( $atts['title'] ) ){
	        		if( strpos( $atts['title'] , '<h' ) === false ){
	        			echo '<h4>'.esc_html($atts['title']).'</h4>';
	        		}else{
		        		print( $atts['title'] );
	        		}	
				}
				
				if( !empty( $atts['des'] ) ){
					if( strpos( $atts['des'] , '<' ) === false ){
	        			print( '<p>'.$atts['des'].'</p>' );
	        		}else{
		        		print( $atts['des'] );
	        		}
				}
				if( !empty( $atts['link'] ) ){
					echo '<a href="'.esc_url($atts['link']).'" class="'.esc_attr($atts['linkclass']).'"><i class="fa fa-caret-right"></i> '.__( 'Read More', KING_DOMAIN ).'</a>';
				}
			?>		
        </div>
		
	<?php	
	}
	
	public static function sec2( $atts ){
		
		$image = $atts['image'];
		if((int)$image > 0 && ($image_url = wp_get_attachment_url( $image, 'thumbnail' )) !== false) {
	        
	    }else{
		    $image_url = THEME_URI.'/assets/images/default.png';
	    }
    
	?>	
		<div class="box">
			<img src="<?php echo esc_url( $image_url ); ?>" alt="">
			<h5>
				<?php 
					if( $atts['link'] != '' )echo '<a href="'.esc_url( $atts['link'] ).'">';
					echo esc_html($atts['title']);
					if( $atts['link'] != '' )echo '</a>'; 
				?>
			</h5>
			<p>
				<?php print( $atts['des'] ); ?>
			</p>
		</div>
		
	<?php	
	}	
	
	public static function tabs_detached( $content, $class ) {
	 
		$rgex = '\[(\[?)(vc_tab)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';

		preg_match_all( '/'.$rgex.'/s', $content, $lines, PREG_SET_ORDER );
		
		$nav = '';
		$sec = '';
	    foreach ($lines as $line) {
	    	preg_match_all( '/(\w+)\s*=\s*"(.*?)"/s', $line[3], $attrs );
	    	$data = array();
			if( count( $attrs[1] )){
				for( $i = 0; $i < count( $attrs[1] ); $i++ ){
					$data[ $attrs[1][$i] ] = $attrs[2][$i];
				}
			}
			$nav .= '<li role="tab">';
			if( empty( $data['icon_simple_line'] ) ){
				$data['icon_simple_line'] = 'paper-plane';
			}	
			$nav .= '<i aria-hidden="true" class="icon-'.esc_attr( $data['icon_simple_line'] ).'"></i>';
			$nav .= '<span class="tab-label">'.esc_attr( $data['title'] ).'</span>';
			$nav .= '</li>';
			$sec .= '<section aria-expanded="false">';
			$sec .= '<div><div>';
			$sec .= do_shortcode($line[5]).'</div></div></section>';
	    }
	    
	?>
	<div class="detached <?php echo esc_attr( $class ); ?> hide-title cross-fade transition tabs">
		<ul class="detached-nav" role="tablist">
			<?php print( $nav );?>
		</ul>
		<?php print( $sec );?>
	</div>
	<?php		
			
	}
		
	public static function flex_sliders( $content, $class ) {
	 
		$rgex = '\[(\[?)(vc_tab)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
		$content = preg_replace( '/'.$rgex.'/s', '<li>$5</li>', $content );
	?>
		<div class="slider <?php echo esc_attr($class); ?>">
			<div class="flexslider carousel">
				<ul class="slides">
					<?php echo do_shortcode( $content ); ?>
				</ul>
			</div>
		</div>
		
	<?php		
			
	}
	
	public static function ipad_sliders( $content, $class ) {
	 
		$rgex = '\[(\[?)(vc_tab)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
		$content = preg_replace( '/'.$rgex.'/s', '<li>$5</li>', $content );
	?>
	<div class="ms-phone-template">
		<div class="ms-phone-cont">
			<img src="<?php echo THEME_URI; ?>/assets/images/phone.png" class="ms-phone-bg" alt="">
			<div class="ms-lt-slider-cont" style="position: relative;">
				<div class="slider ">
					<div class="flexslider carousel">
						<ul class="slides">
							<?php echo do_shortcode( $content ); ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>			
		
	<?php		
			
	}
	
	
}	