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
<!--Footer Layout 8: Location /templates/footer/-->
<div class="featured_section82">
	<div class="container">
	
		<div class="box">
			<span class="icon-screen-smartphone animated eff-zoomIn delay-200ms"></span>
	        <b class="animated eff-fadeInRight delay-300ms"><?php _e( 'CALL US', KING_DOMAIN ); ?></b>
			<strong class="animated eff-fadeInRight delay-300ms"><?php echo esc_attr( $king->cfg['topInfoPhone'] ); ?></strong>
	    </div><!-- end section -->
	    
	    <div class="box">
			<span class="icon-envelope-letter animated eff-zoomIn delay-200ms"></span>
	        <b class="animated eff-fadeInRight delay-300ms"><?php _e( 'Mail Us', KING_DOMAIN ); ?></b>
			<strong class="animated eff-fadeInRight delay-300ms">
				<a href="mailto:<?php echo esc_attr( $king->cfg['topInfoEmail'] ); ?>">
					<?php echo esc_attr( $king->cfg['topInfoEmail'] ); ?>
				</a>
			</strong>
	    </div><!-- end section -->
	    
	    <?php $king->socials( 'box last', 4 ); ?>
	
	</div>
</div>
<div class="clearfix"></div>
<footer class="footer6">
	<div class="container">
	    <div class="column1 animated eff-fadeInUp delay-100ms">
	    	<?php if ( is_active_sidebar( 'footer2-1' ) ) : ?>
				<div id="footer2-column-1" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer2-1' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	    
	    <div class="column2 animated eff-fadeInUp delay-200ms">
	    	<?php if ( is_active_sidebar( 'footer2-2' ) ) : ?>
				<div id="footer2-column-2" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer2-2' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	    
	    <div class="column1 animated eff-fadeInUp delay-300ms">
	    	<?php if ( is_active_sidebar( 'footer2-3' ) ) : ?>
				<div id="footer2-column-3" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer2-3' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	    
	    <div class="column1 last animated eff-fadeInUp delay-400ms">
	    	<?php if ( is_active_sidebar( 'footer2-4' ) ) : ?>
				<div id="footer2-column-4" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'footer2-4' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
	
	</div>
	<div class="clearfix"></div>
	<div class="copyright_info4">
		<div class="container">
			<div class="clearfix divider_dashed10"></div>
			<div class="one_half"><?php echo king::esc_js( $king->cfg['footerText'] ); ?></div>
		    <div class="one_half last">
		    	<a href="<?php echo esc_url( $king->cfg['footerTerms'] ); ?>">
		    		<?php _e('Terms of Use', KING_DOMAIN ); ?>
		    	</a> 
		    	| 
		    	<a href="<?php echo esc_url( $king->cfg['footerPrivacy'] ); ?>">
		    		<?php _e('Privacy Policy', KING_DOMAIN ); ?>
		    	</a>
		    </div>
		</div>
	</div>
</footer>