<?php
/**
 * (c) king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( is_user_logged_in() ){
	echo '<p>'.__( 'You are logged in', KING_DOMAIN ).'</p>';
	return;
}

global $post;

?>

<div class="logregform">        
	<div class="title">        
		<h3><?php _e('Account Login', KING_DOMAIN ); ?></h3>        		
		<p><?php _e('Not member yet? &nbsp; <a href="'. site_url() .'?action=register">Sign Up.</a>', KING_DOMAIN ); ?></p>            
	</div>
	
	<div class="feildcont">        
		<form id="king-form" method="post" name="loginform" action="" class="king-form" novalidate="novalidate">      
			<label><i class="fa fa-user"></i> <?php _e('Username / Email', KING_DOMAIN ); ?></label>       
			<input type="text" name="log" value="" />
			
			<label><i class="fa fa-lock"></i> <?php _e('Password', KING_DOMAIN ); ?></label>
			<input type="password" name="pwd" value="" />
			
			<div class="checkbox">
				<label>
					<input type="checkbox" name="rememberme" />
				</label>
				<label><?php _e('Remember Me', KING_DOMAIN ); ?></label>
				<label><a href="<?php echo site_url() ?>?action=forgot"><strong><?php _e('Forgot Password?', KING_DOMAIN ); ?></strong></a></label>
			</div>
			
			<p class="status"></p>
			
			<button type="button" class="fbut btn-login"><?php _e('Login Now!', KING_DOMAIN ); ?></button>  

			<input type="hidden" name="action" value="king_user_login" />
			<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
		</form>        
	</div>  
</div>

