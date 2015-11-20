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
<footer class="footer3">
	<div class="container">
	    <div class="one_fifth animated eff-fadeInUp delay-100ms">
	    	<?php if ( is_active_sidebar( 'footer1-1' ) ) : ?>
				<div id="footer1-column-1" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer1-1' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	    
	    <div class="one_fifth animated eff-fadeInUp delay-200ms">
	    	<?php if ( is_active_sidebar( 'footer1-2' ) ) : ?>
				<div id="footer1-column-2" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer1-2' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	    
	    <div class="one_fifth animated eff-fadeInUp delay-300ms">
	    	<?php if ( is_active_sidebar( 'footer1-3' ) ) : ?>
				<div id="footer1-column-3" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer1-3' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	    
	    <div class="one_fifth animated eff-fadeInUp delay-400ms">
	    	<?php if ( is_active_sidebar( 'footer1-4' ) ) : ?>
				<div id="footer1-column-4" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer1-4' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	        
		<div class="one_fifth last animated eff-fadeInUp delay-500ms">
	    	<?php if ( is_active_sidebar( 'footer1-5' ) ) : ?>
				<div id="footer1-column-5" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer1-5' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	</div>
</footer>
<div class="clearfix"></div>
<div class="fgmapfull">
	<?php echo '<ifr'.'ame src="'.esc_url( $king->cfg['footerMap'] ).'" style="border:0"></ifr'.'ame>'; ?>
</div>
<div class="clearfix"></div>
<div class="copyright_info3">
	<div class="container">
		<?php echo king::esc_js( $king->cfg['footerText'] ); ?>
		<a href="<?php echo esc_url( $king->cfg['footerTerms'] ); ?>"> 
    		<?php _e('Terms of Use', KING_DOMAIN ); ?>
    	</a> 
    	| 
    	<a href="<?php echo esc_url( $king->cfg['footerPrivacy'] ); ?>">
    		<?php _e('Privacy Policy', KING_DOMAIN ); ?>
    	</a>
	</div>
</div>