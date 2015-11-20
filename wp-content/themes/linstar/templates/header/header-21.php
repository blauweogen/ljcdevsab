<?php
/**
*	This file has been preloaded, so you can wp_enqueue_style to out in wp_head();
*/	

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $king;

	wp_enqueue_style('king-menu-21');
	
?>
<!--Header Layout Construction: Location /templates/header/-->
<header class="header">
	<div class="container_fhstyle2">
	    <!-- Logo -->
	    <div class="logo3">
	   		<a href="<?php echo SITE_URI; ?>" id="logo21">
				<img src="<?php echo esc_url( $king->cfg['logo'] ); ?>" alt="<?php bloginfo('description'); ?>" />
			</a>
	    </div>	
		<!-- Navigation Menu -->
	    <div class="menu_main_full three">
		  <div class="top_nav3"> 
			  <i class="fa fa-phone"></i> <?php print( !empty($king->cfg['topInfoPhone']) ? $king->cfg['topInfoPhone'] : '' ); ?>
			  &nbsp;&nbsp; 
			  <a href="mailto:<?php print( !empty($king->cfg['topInfoEmail']) ? $king->cfg['topInfoEmail'] : '' ); ?>">
			  	<i class="fa fa-envelope"></i> <?php print( !empty($king->cfg['topInfoEmail']) ? $king->cfg['topInfoEmail'] : '' ); ?>
			  </a> 
			  &nbsp;&nbsp;
		      
		      <?php $king->socials('topsocial', 5, false); ?>
	      
	      </div>
	      <div class="navbar yamm navbar-default">
	          <div class="navbar-header">
	            <div class="navbar-toggle .navbar-collapse .pull-right " data-toggle="collapse" data-target="#navbar-collapse-1"> 
					<button type="button"> <i class="fa fa-bars"></i></button>
	            </div>
	          </div>
	          <div id="navbar-collapse-1" class="navbar-collapse collapse pull-right">
	            <nav><?php $king->mainmenu(); ?></nav>
	          </div>
	      </div>
	    </div>
		<!-- end Navigation Menu -->
	</div>
</header>
<div class="clearfix margin_top12"></div>