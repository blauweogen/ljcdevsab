<?php
/**
*	This file has been preloaded, so you can wp_enqueue_style to out in wp_head();
*/	

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $king;

	wp_enqueue_style('king-menu-23');
	
?>
<!--Header Layout 23: Location /templates/header/-->
<div class="top_section3">
	<div class="container">
	    <div class="left">
	        <!-- Logo -->
	        <div class="logo">
		        <a href="<?php echo SITE_URI; ?>">
					<img src="<?php echo esc_url( $king->cfg['logo'] ); ?>" alt="<?php bloginfo('description'); ?>" />
				</a>
	        </div>
	    </div><!-- end left -->
	    <div class="right">
			<ul class="tinfo last">
	            <li><i class="fa fa-phone"></i></li>
	            <li><em><?php _e( 'Call Us', KING_DOMAIN ); ?></em>
	            <strong><?php print( !empty($king->cfg['topInfoPhone']) ? $king->cfg['topInfoPhone'] : '' ); ?></strong></li>
	            
	        </ul>
	        <ul class="tinfo">
	            <a href="mailto:<?php print( !empty($king->cfg['topInfoEmail']) ? $king->cfg['topInfoEmail'] : '' ); ?>"><?php _e( 'Make an Appointment', KING_DOMAIN ); ?></a>
	        </ul>
	    </div><!-- end right -->
	</div>
</div>
<div class="clearfix"></div>
<header class="header">
	<div class="container">
		<!-- Navigation Menu -->
	    <div class="menu_main_full2">
	      <div class="navbar yamm navbar-default">
	          <div class="navbar-header">
	            <div class="navbar-toggle .navbar-collapse .pull-right " data-toggle="collapse" data-target="#navbar-collapse-1">
	              <button type="button"> <i class="fa fa-bars"></i></button>
	            </div>
	          </div>
	          <div id="navbar-collapse-1" class="navbar-collapse collapse pull-left">
		          <nav><?php $king->mainmenu(); ?></nav>
	          </div>
	      </div>
	    </div>
	<!-- end Navigation Menu -->
	</div>
</header>
<div class="clearfix margin_top6 margin_top6_23"></div>