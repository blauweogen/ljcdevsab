<?php
/**
*	This file has been preloaded, so you can wp_enqueue_style to out in wp_head();
*/	

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $king;
	
	wp_enqueue_style('king-menu-onepage-4');
	
?>
<!--Header Layout 24: Location /templates/header/-->
<div class="fixednav4">
	<div class="navbar navbar-default pinning-nav pinned top">
		<div class="container">
			<div class="navbar-header">
			    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu-onepage" aria-expanded="false">
				    <span class="sr-only"><?php _e('Toggle navigation', KING_DOMAIN ); ?></span>
			    	<span class="icon-bar"></span>
					<span class="icon-bar"></span>
			    	<span class="icon-bar"></span>
			    </button>
				<a data-scroll="" class="navbar-brand home" href="#home">
					<img src="<?php echo esc_url( $king->cfg['logo'] ); ?>" alt="<?php bloginfo('description'); ?>" />
				</a>
			</div>
			<div class="collapse navbar-collapse" id="menu-onepage" aria-expanded="false">
				<nav>
				    <?php
				    	if ( has_nav_menu( 'onepage-2' ) ){
					    	wp_nav_menu( array( 
								'theme_location' 	=> 'onepage-2', 
								'menu_class' 		=> 'nav navbar-nav',
								'menu_id'			=> 'king-onepage-nav',
								'walker' 			=> new king_Walker_Onepage_Nav_Menu()
								)
							);
						}else{
							echo 'Missing onepage-2 menu, go to /wp-admin/nav-menus.php and set theme locations of one menu as One-Page-2';
						}	
					?>
				</nav>	
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container -->
	</div>
</div>

<div class="clearfix margin-res4"></div>