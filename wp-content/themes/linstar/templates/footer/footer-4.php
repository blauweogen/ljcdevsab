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
<!--Footer Layout 4: Location /templates/footer/-->
<footer class="footer4">
    <div class="container">
        <div class="fmlinks">
        	<?php 
        		if ( has_nav_menu( 'footer' ) ){
		        	wp_nav_menu( array( 
						'theme_location' 	=> 'footer', 
						'menu_class' 		=> '',
						'menu_id'			=> 'king-footer-nav',
						'walker' 			=> new king_Walker_Footer_Nav_Menu()
						)
					);
				}	
        	?>
        </div>
        <?php echo king::esc_js( $king->cfg['footerText'] ); ?> 
        <a href="<?php echo esc_url( $king->cfg['footerTerms'] ); ?>"> 
    		<?php _e('Terms of Use', KING_DOMAIN ); ?>
    	</a> 
    	| 
    	<a href="<?php echo esc_url( $king->cfg['footerPrivacy'] ); ?>">
    		<?php _e('Privacy Policy', KING_DOMAIN ); ?>
    	</a>
        <?php $king->socials( 'footer_social_links4' , 5 ); ?>
    </div>
    <!-- end footer -->
</footer>
