<?php if (strcmp(DfdThemeSettings::get('show_login_form'),'1') === 0) : ?>
<div class="login-header">
	<?php if (!is_user_logged_in()): ?>
		<div class="links">
			<a href="<?php echo esc_url( wp_login_url() ); ?>" class="drop-login" data-reveal-id="loginModal">
				<i class="icon-user-filled icon-hover"></i>
				<i class="icon-user-lined"></i>
			</a>
		</div>

		<div id="loginModal" class="reveal-modal">
			<?php crum_login_form(''); ?>
			<a class="close-reveal-modal">&#215;</a>
		</div>
	<?php else: ?>

		<div class="links">
			<a href="<?php echo esc_url( wp_logout_url() ); ?>">
				<i class="icon-user-filled icon-hover"></i>
				<i class="icon-user-lined"></i>
			</a>
		</div>

	<?php endif; ?>
</div>
<?php endif; ?>
