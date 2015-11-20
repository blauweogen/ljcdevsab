<?php
/**
*	This file has been preloaded, so you can wp_enqueue_style to out in wp_head();
*/	

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $king;
	
	wp_enqueue_style('king-menu-onepage-2');
	
?>
<!--Header Layout Onepage 2: Location /templates/header/-->

<div class="container">
	<div id="menu" class="panel" role="navigation">
	
	    <?php 
	    	if ( has_nav_menu( 'onepage' ) ){
		    	wp_nav_menu( array( 
					'theme_location' 	=> 'onepage', 
					'menu_class' 		=> '',
					'menu_id'			=> 'menu-onepage',
					'walker' 			=> new king_Walker_Onepage_Nav_Menu()
					)
				);
			}else{
				echo 'Missing onepage menu, go to /wp-admin/nav-menus.php and set theme locations of one menu as One-Page';
			}
		?>
	    
	</div>
</div>
<div id="wrap">
	<div class="container">
		
	    <!-- Logo -->
	    <div class="logoopv2">
	    	<a href="#home" data-scroll="">
	    		<img src="<?php echo esc_url( $king->cfg['logo'] ); ?>" alt="<?php bloginfo('description'); ?>" />
	    	</a>
	    </div>
	    
		<!-- Menu button -->
	    <div class="menuopv2">
	    	<a href="#menu" id="menu-vp2" class="menu-link"><span></span></a> 
		</div>  
		
	    <div class="clearfix margin_top_one_res2"></div>
	    
	</div>
</div>
<script type="text/javascript">
	
	jQuery(document).ready(function($){
		$('#menu-vp2').click(function(){
			if( !$(this).hasClass('menu-open') ){
				$('#menu').css({ 'left' : '0px' });
				$(this).addClass('menu-open');
			}else{
				$('#menu').css({ 'left' : '-100%' });
				$(this).removeClass('menu-open');
			}	
		});
		$('#menu,#menu a').click(function(){$('#menu-vp2').click();});
	});
	
</script>