<?php
/**
*	This file has been preloaded, so you can wp_enqueue_style to out in wp_head();
*/	

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $king;
	
	wp_enqueue_style('king-menu-onepage-1');
	
?>
<!--Header Layout Onepage 1: Location /templates/header/-->
<div class="container_full opstycky1">
    <!-- Logo -->
    <div class="logoopv1">
    	<a href="#home" data-scroll="">
    		<img src="<?php echo esc_url( $king->cfg['logo'] ); ?>" alt="<?php bloginfo('description'); ?>" />
    	</a>
    </div>
    <!-- Menu -->
    <div class="menuopv1" id="menu-onepage">
        <a href="#" class="nav-toggle" aria-hidden="true"><?php _e( 'Menu', KING_DOMAIN ); ?></a>
        <nav class="nav-collapse" style="display: none;">
            <?php 
            	if ( has_nav_menu( 'onepage' ) ){
			    	wp_nav_menu( array( 
						'theme_location' 	=> 'onepage', 
						'menu_class' 		=> '',
						'menu_id'			=> 'king-onepage-nav',
						'walker' 			=> new king_Walker_Onepage_Nav_Menu()
						)
					);
				}else{
					echo 'Missing onepage menu, go to /wp-admin/nav-menus.php and set theme locations of one menu as One-Page';
				}	
			?>
        </nav>
    </div><!-- end menu -->  
</div>
<div class="clearfix margin_top9 margin_top_one_res1"></div>