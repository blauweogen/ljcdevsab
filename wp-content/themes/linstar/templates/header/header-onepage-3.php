<?php
/**
*	This file has been preloaded, so you can wp_enqueue_style to out in wp_head();
*/	

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $king;
	
	wp_enqueue_style('king-menu');
	wp_enqueue_style('king-menu-onepage-3');
	
?>
<!--Header Layout Onepage 3: Location /templates/header/-->
<div id="wrap">
	<div class="fixednav3">
		<div class="navbar navbar-default pinning-nav pinned top">
		  <div class="container-fluid">
		    <div class="navbar-header">
		        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only"><?php _e('Toggle navigation', KING_DOMAIN); ?></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        </button>
				<a href="#home" class="navbar-brand">
					<img src="<?php echo esc_url( $king->cfg['logo'] ); ?>" alt="<?php bloginfo('description'); ?>" />
				</a>
		    </div>
		
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		       	 <?php 
		       		 if ( has_nav_menu( 'onepage' ) ){
				    	wp_nav_menu( array( 
							'theme_location' 	=> 'onepage', 
							'menu_class' 		=> 'nav navbar-nav',
							'menu_id'			=> 'menu-onepage',
							'walker' 			=> new king_Walker_Onepage_Nav_Menu()
							)
						);
					}else{
						echo 'Missing onepage menu, go to /wp-admin/nav-menus.php and set theme locations of one menu as One-Page';
					}	
				?>		
			 </div>
			 	<ul class="nav navbar-nav navbar-right">
		        	<li>
		        		<a href="mailto:<?php print( !empty($king->cfg['topInfoEmail']) ? $king->cfg['topInfoEmail'] : '' ); ?>" class="grl">
		        			<?php print( !empty($king->cfg['topInfoEmail']) ? $king->cfg['topInfoEmail'] : '' ); ?>
		        		</a>
		        	</li>
		        	<li>
		        		<b><?php print( !empty($king->cfg['topInfoPhone']) ? $king->cfg['topInfoPhone'] : '' ); ?></b>
		        	</li>
		        </ul>
		  </div>
		</div>
	</div>
</div>	
<div class="clearfix margin_top8 margin_top_one_res3"></div>
