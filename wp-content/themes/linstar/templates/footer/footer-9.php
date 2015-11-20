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
<!--Footer Layout 1: Location /templates/footer/-->
<footer class="footer stwo">
	
	<?php $king->socials( 'fbsocials', 'color-style' ); ?>

    <div class="container">
        <div class="one_fourth animated eff-fadeInUpdelay-100ms">
            <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<div id="footer-column-1" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer-1' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
        </div>
        <!-- end address -->

        <div class="one_fourth animated eff-fadeInUp delay-200ms">
            <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
				<div id="footer-column-2" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer-2' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
       </div>
        <!-- end links -->

        <div class="one_fourth animated eff-fadeInUp delay-300ms">
        	<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
				<div id="footer-column-3" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer-3' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>    
        </div>
        <!-- end site info -->
        <div class="one_fourth last animated eff-fadeInUp delay-400ms">
			<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
				<div id="footer-column-4" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer-4' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
        </div>
        <!-- end flickr -->
    </div>
    <!-- end footer -->
    <div class="clearfix"></div>
    <div class="copyright_info two">
        <div class="container">
            <div class="clearfix divider_dashed10"></div>
            <div class="one_half">
                <?php echo king::esc_js( $king->cfg['footerText'] ); ?>
                <a href="<?php echo esc_url( $king->cfg['footerTerms'] ); ?>"> 
		    		<?php _e('Terms of Use', KING_DOMAIN ); ?>
		    	</a> 
		    	| 
		    	<a href="<?php echo esc_url( $king->cfg['footerPrivacy'] ); ?>">
		    		<?php _e('Privacy Policy', KING_DOMAIN ); ?>
		    	</a>
            </div>
            <div class="one_half last">
				<?php $king->socials( 'footer_social_links', 10, false ); ?>
            </div>
        </div>
    </div>
    <!-- end copyright info -->
</footer>
