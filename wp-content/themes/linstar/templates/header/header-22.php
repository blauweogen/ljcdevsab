<?php
/**
*	This file has been preloaded, so you can wp_enqueue_style to out in wp_head();
*/	

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $king;

	wp_enqueue_style('king-menu-22');
	
?>
<!--Header Layout 22: Location /templates/header/-->
<div class="top_nav4">
	<div class="container">
	    <div class="left">
	        <?php $king->socials('topsocial', 5, false); ?>
	    </div><!-- end left -->
	    <div class="right">
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
	        <strong>
				<a href="mailto:<?php print( !empty($king->cfg['topInfoEmail']) ? $king->cfg['topInfoEmail'] : '' ); ?>">
					<i class="fa fa-envelope"></i> &nbsp; 
					<?php print( !empty($king->cfg['topInfoEmail']) ? $king->cfg['topInfoEmail'] : '' ); ?>
				</a> 
	        </strong>
	        <strong>
	        	<i class="fa fa-phone-square"></i>&nbsp; 
	        	<?php print( !empty($king->cfg['topInfoPhone']) ? $king->cfg['topInfoPhone'] : '' ); ?>
	        </strong>     
	    </div><!-- end right -->  
	</div>
</div>
<div class="clearfix"></div>
<header class="header">
	<div class="container">
    <!-- Logo -->
    <div class="logo">
	    <a href="<?php echo SITE_URI; ?>">
			<img src="<?php echo esc_url( $king->cfg['logo'] ); ?>" alt="<?php bloginfo('description'); ?>" />
		</a>
    </div>
	<!-- Navigation Menu -->
    <div class="menu_main">
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
<div class="clearfix margin_top8"></div>