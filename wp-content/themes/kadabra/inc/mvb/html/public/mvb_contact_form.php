<?php
if (!empty($effects)) {
	$cr_effect = ' cr-animate-gen"  data-gen="' . $effects . '" data-gen-offset="bottom-in-view';
} else {
	$cr_effect = '';
}

# Addition CSS styles
$addition_css_styles = '';

if (!empty($css_styles)) {
	$addition_css_styles .= $css_styles;
}
if (!empty($addition_css_styles)) {
	$addition_css_styles = 'style="' . $addition_css_styles . '"';
}

$submit_button_class = '';

if (isset($submit_button_align) && !empty($submit_button_align)) {
	if (in_array($submit_button_align, array('left', 'right'))) {
		$submit_button_class .= ' '. $submit_button_align;
	}
}

$recaptcha_lib = get_template_directory().'/inc/recaptcha/recaptchalib.php';
$publickey = "6Ld_hO8SAAAAAAaLGaiekovfeewTg6pp1-edOhLi";
$privatekey = "6Ld_hO8SAAAAAC7YDaB-CHlR8KPuzP9zcVbCGnpd";
if (file_exists($recaptcha_lib)) {
	require_once($recaptcha_lib);
}
?>

<div class="module module-contact-form <?php echo (!empty($css)) ? $css : ''; ?> <?php echo $cr_effect; ?>"  <?php echo $addition_css_styles; ?>>
	
	<?php include(dirname(__FILE__).'/_title.php');?>

	<div class="row">
		<?php 
		if(!empty($size) && $size == '1') {?>
		    <div class="column six push-three">
		<?php }else{ ?>
		    <div class="column twelve">
		<?php }?>
	<?php
	$admin_email = $contacts_form_mail;

	if (empty($admin_email)) {
		echo __('You need enter email in options panel', 'dfd');
	} else {
		
		if (isset($_POST["recaptcha_challenge_field"])) {
			$resp = recaptcha_check_answer($privatekey,
				$_SERVER["REMOTE_ADDR"],
				$_POST["recaptcha_challenge_field"],
				$_POST["recaptcha_response_field"]);
			
			if (!$resp->is_valid) {
				unset($_POST['sender_name']);
			}
		}
		
		if (isset($_POST['sender_name'])) {
			if (wp_mail($admin_email, 
					"Subject: " . $_POST['letter_subject'] . "\tAuthor: " . $_POST['sender_name'] . "/" . $_POST['sender_email'], 
					$_POST['letter_text'])) {
				echo '<h2>' . __('Thank you for your message!', 'dfd') . '</h2>';
			} else {
				echo '<h2>' . __('Unknown error, during message sending.', 'dfd') . '</h2>';
			}
		} else {
			?>
			<form action="<?php echo (isset($_SERVER["REQUEST_URI"]))?$_SERVER["REQUEST_URI"]:''; ?>" method="POST" name="page_feedback" id="<?php echo uniqid('page_feedback_'); ?>">
				<div class="input-wrap">
					<input id="<?php echo uniqid('sender_name_'); ?>" name="sender_name" type="text" required="required" placeholder="<?php _e('Name', 'dfd'); ?>" />
				</div>
				<div class="input-wrap">
					<input id="<?php echo uniqid('sender_email_'); ?>" name="sender_email" type="email" required="required" placeholder="<?php _e('Email', 'dfd'); ?>" />
				</div>
				<div class="input-wrap">
					<input id="<?php echo uniqid('letter_subject_'); ?>" name="letter_subject" type="text" required="required" placeholder="<?php _e('Subject', 'dfd'); ?>" />
				</div>
				<div class="input-wrap">
					<textarea id="<?php echo uniqid('letter_text_'); ?>" rows="5" name="letter_text" required="required" placeholder="<?php _e('Message', 'dfd'); ?>"></textarea>
				</div>
				<?php if (!empty($show_captcha)) : ?>
				<div class="input-wrap">
					<script type="text/javascript">
						var RecaptchaOptions = {
							theme : 'white'
						};
					</script>
					<?php
					echo recaptcha_get_html($publickey);
					?>
					<?php if (!empty($resp) && !$resp->is_valid) {
						echo '<p>' . __('The CAPTCHA wasn\'t entered correctly.', 'dfd') . '</p>';
					} ?>
				</div>
				<?php endif; ?>
				<div class="clearfix">
					<button class="button <?php echo $submit_button_class; ?>" name="submit">
						<i class="icon-check"></i>
						<?php _e('Leave reply', 'dfd'); ?>
					</button>
				</div>
			</form>
			<?php
		}
	}
	?>
		</div>
	</div>
</div>
