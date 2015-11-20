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
<footer class="footer7">
	<div class="container">
	    <div class="one_third animated eff-fadeInUp delay-100ms">
	    	<?php if ( is_active_sidebar( 'footer7-1' ) ) : ?>
				<div id="footer7-column-1" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer7-1' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	    
	    <div class="one_third animated eff-fadeInUp delay-200ms">
	    	<?php if ( is_active_sidebar( 'footer7-2' ) ) : ?>
				<div id="footer7-column-2" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer7-2' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	    
	    <div class="one_third last animated eff-fadeInUp delay-300ms">
	    	<?php if ( is_active_sidebar( 'footer7-3' ) ) : ?>
				<div id="footer7-column-3" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer7-3' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>

	</div>
</footer>
<div class="clearfix"></div>
<div class="copyright_info5">
	<div class="container">
		<div class="clearfix divider_dashed10"></div>
		<p><?php echo king::esc_js( $king->cfg['footerText'] ); ?></p>  
		<span>
		    <a href="<?php echo esc_url( $king->cfg['footerTerms'] ); ?>"> 
    			<?php _e('Terms of Use', KING_DOMAIN ); ?>
	    	</a> 
	    	| 
	    	<a href="<?php echo esc_url( $king->cfg['footerPrivacy'] ); ?>">
	    		<?php _e('Privacy Policy', KING_DOMAIN ); ?>
	    	</a>
	    </span>
	</div>
</div>