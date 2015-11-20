<?php if (DfdThemeSettings::get("footer_tw_disp")): ?>
	<?php if (DfdThemeSettings::get("site_boxed")): ?><div class="boxed_lay"><?php endif; ?>
		<section id="bot-twitter" style="
				<?php if(DfdThemeSettings::get('t_panel_padding')) {echo 'padding:30px 0;';} ?>
				<?php if(DfdThemeSettings::get('t_panel_bg_color')) {echo 'background-color:' . DfdThemeSettings::get('t_panel_bg_color') . ';';} ?>
				<?php if(DfdThemeSettings::get('t_panel_bg_image')) {echo 'background-image:url(' . DfdThemeSettings::get('t_panel_bg_image') . '); background-position: center; background-attachment: fixed;'; } ?>
		">
			<div class="row">
				<?php echo do_shortcode('[dfd_twitter_row]'); ?>
			</div>
		</section>
	<?php if (DfdThemeSettings::get("site_boxed")): ?></div><?php endif; ?>
<?php endif; ?>
