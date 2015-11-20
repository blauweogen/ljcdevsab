<?php
/**
*	This file has been preloaded, so you can wp_enqueue_style to out in wp_head();
*/	

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $king;
	
	wp_enqueue_style('king-menu-12');
	
?>
<!--Header Layout 12: Location /templates/header/-->
<header class="header header_res12">
	<div class="container_fhstyle2">
	    <!-- Logo -->
	    <div class="logo2">
		    <a href="<?php echo SITE_URI; ?>" id="logo12">
				<img src="<?php echo esc_url( $king->cfg['logo'] ); ?>" alt="<?php bloginfo('description'); ?>" />
			</a>
	    </div>	
	    <div class="menu_rlinks7">
    		<a href="phone:<?php echo esc_attr( $king->cfg['topInfoPhone'] ); ?>">
    			<i class="fa fa-phone"></i> 
    			<em><?php _e( 'Call Us Now!', KING_DOMAIN ); ?></em> 
    			<?php echo esc_attr( $king->cfg['topInfoPhone'] ); ?>
    		</a>
    	</div>
		<!-- Navigation Menu -->
	    <div class="menu_main rslinks7">
	      <div class="navbar yamm navbar-default">
	          <div class="navbar-header">
	            <div class="navbar-toggle .navbar-collapse .pull-right " data-toggle="collapse" data-target="#navbar-collapse-1"> 
	            	<span><?php _e( 'Menu', KING_DOMAIN ); ?></span>
					<button type="button"> <i class="fa fa-bars"></i></button>
	            </div>
	          </div>
	          <div id="navbar-collapse-1" class="navbar-collapse collapse pullleft">
	          		<nav><?php $king->mainmenu(); ?></nav>
	          </div>
	      </div>
	    </div>
		<!-- end Navigation Menu -->
	</div>
</header>
<div class="clearfix margin_top10 margin_top_res12"></div>