<?php
/**
*	This file has been preloaded, so you can wp_enqueue_style to out in wp_head();
*/	

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $king;
	$king->main_class = 'leftmenuv1';
	wp_enqueue_style('king-menu-onepage-leftmenu');
	
?>
<!--Header Layout Onepage Left Menu: Location /templates/header/-->
<div class="leftmenu1_links">
	<div class="logo_lmv1"><img src="<?php echo esc_url( $king->cfg['logo'] ); ?>" alt="<?php bloginfo('description'); ?>" /></div>
	
	<div id="menu-onepage" class="float-menu">
		<a href="#" class="nav-toggle" aria-hidden="true"><?php _e( 'Menu', KING_DOMAIN ); ?></a>
		<div class="nav-collapse" style="display: none;">
		<?php 
			if ( has_nav_menu( 'onepage' ) ){
		    	wp_nav_menu( array( 
					'theme_location' 	=> 'onepage', 
					'menu_class' 		=> 'nav navbar-nav',
					'menu_id'			=> 'menu-onepage-left',
					'walker' 			=> new king_Walker_Onepage_Nav_Menu()
					)
				);
			}else{
				echo 'Missing onepage menu, go to /wp-admin/nav-menus.php and set theme locations of one menu as One-Page';
			}
		?>	
		</div>
	</div>
</div>
<div class="clearfix margin_top_left_res1"></div>