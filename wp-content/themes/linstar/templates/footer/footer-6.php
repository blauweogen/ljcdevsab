<?php
/*
*	(c) king-theme.com
*/	
	
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
	global $king;
	if( empty( $king->cfg['footerText'] ) ){
		$king->cfg['footerText'] = 'Add footer copyrights text via <a href="'.admin_url('admin.php?page='.strtolower(THEME_NAME).'-panel').'"><strong>theme-panel</strong></a>';
	}
	
?>
<!--Footer Layout 2: Location /templates/footer/-->
<div class="clearfix"></div>
<footer class="footer two">
	<div class="container">
	    <div class="one_fourth animated eff-fadeInUp delay-100ms">
	    	<?php if ( is_active_sidebar( 'footer1-1' ) ) : ?>
				<div id="footer1-column-1" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer1-1' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	    
	    <div class="one_fourth animated eff-fadeInUp delay-200ms">
	    	<?php if ( is_active_sidebar( 'footer1-2' ) ) : ?>
				<div id="footer1-column-2" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer1-2' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	    
	    <div class="one_fourth animated eff-fadeInUp delay-300ms">
	    	<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
				<div id="footer-column-3" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer-3' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	        
		<div class="one_fourth last animated eff-fadeInUp delay-500ms">
	    	<?php if ( is_active_sidebar( 'footer1-5' ) ) : ?>
				<div id="footer1-column-5" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer1-5' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="copyright_info white">
		<div class="container">
			<div class="clearfix divider_dashed10"></div>
		    <div class="one_half"><?php echo king::esc_js( $king->cfg['footerText'] ); ?></div>
		    <div class="one_half last"><?php $king->socials('footer_social_links5', 10, false); ?></div>
		</div>
	</div>
</footer>