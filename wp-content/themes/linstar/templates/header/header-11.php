<?php
/**
*	This file has been preloaded, so you can wp_enqueue_style to out in wp_head();
*/	

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $king;
	
	wp_enqueue_style('king-menu-11');
	
?>
<!--Header Layout 11: Location /templates/header/-->
<div class="top_section">
	<div class="container">
	    <div class="left">
	        <!-- Logo -->
	        <div class="logo">
	        	<a href="<?php echo SITE_URI; ?>" id="logo">
					<img src="<?php echo esc_url( $king->cfg['logo'] ); ?>" alt="<?php bloginfo('description'); ?>" />
				</a>
			</div>
	    </div>
	    <div class="right">
	    	<a href="mailto:<?php echo esc_attr( $king->cfg['topInfoEmail'] ); ?>"><i class="fa fa-envelope"></i>&nbsp; 
	    	<?php echo esc_attr( $king->cfg['topInfoEmail'] ); ?></a> <i class="fa fa-phone-square"></i>&nbsp; 
	    	<?php echo esc_attr( $king->cfg['topInfoPhone'] ); ?>
	        <?php $king->socials('topsocial'); ?>
	    </div>
	</div>
</div>
<div class="clearfix"></div>
<header class="header">
	<div class="container">
		<!-- Navigation Menu -->
	    <div class="menu_main_full">
	      <div class="navbar yamm navbar-default">
	          <div class="navbar-header">
	            <div class="navbar-toggle .navbar-collapse .pull-right " data-toggle="collapse" data-target="#navbar-collapse-1">
		             <span><?php _e( 'Menu', KING_DOMAIN ); ?></span>
		             <button type="button"> <i class="fa fa-bars"></i></button>
	            </div>
	          </div> 
	          <div id="navbar-collapse-1" class="navbar-collapse collapse">
				  <nav><?php $king->mainmenu(); ?></nav>
			  </div>
	      </div>
	    </div>
	    <!-- end Navigation Menu -->
	    <div class="menu_rlinks">
	        <?php 
	        	if ( has_nav_menu( 'top_nav' ) ){
					wp_nav_menu( array( 
						'theme_location'  => 'top_nav', 
						'menu_class'   => '',
						'menu_id'   => 'king-top-nav',
						'walker'    => new king_Walker_Footer_Nav_Menu()
						)
					);
				}	
			?>
	    </div><!-- end menu right links -->
	</div>
</header>
<div class="clearfix margin_top_res11"></div>