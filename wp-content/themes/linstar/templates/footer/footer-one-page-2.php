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
<div class="featured_section207" id="contact">
	<div class="fgmapfull3">
    	<?php echo '<ifr'.'ame src="'.esc_url( $king->cfg['footerMap'] ).'" frameborder="0" style="border:0"></ifra'.'me>'; ?>
	</div>
    <div class="ongmp_contact"></div>
    <div class="container">
        <div class="box">
            <h1 class="white"><b><?php _e( 'Contact', KING_DOMAIN ); ?></b></h1>
            <div class="cforms three">
            <div id="form_status"></div>
				<?php echo do_shortcode( '[contact-form-7 id="95"]' ); ?>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="featured_section208">
	<div class="ctmidarea">
	
		<?php echo king::esc_js( $king->cfg['footerText'] ); ?>
		<?php _e( 'Telephone', KING_DOMAIN ); ?>: <?php print( !empty($king->cfg['topInfoPhone']) ? $king->cfg['topInfoPhone'] : '' ); ?>   
		<?php _e( 'E-mail', KING_DOMAIN ); ?>: 
			<a href="mailto:<?php echo esc_attr( !empty($king->cfg['topInfoEmail']) ? $king->cfg['topInfoEmail'] : '' ); ?>">
				<?php echo esc_attr( !empty($king->cfg['topInfoEmail']) ? $king->cfg['topInfoEmail'] : '' ); ?>
			</a> &nbsp;
		<?php $king->socials( 'footer_social-op2', 10, false ); ?>
	</div>
</div>
