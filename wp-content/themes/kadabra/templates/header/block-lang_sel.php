<div class="lang-sel">
	<?php if (DfdThemeSettings::get("wpml_lang_show")): ?>
	<a href="#"><?php echo (defined('ICL_LANGUAGE_CODE'))?  ucfirst(ICL_LANGUAGE_CODE):''; ?></a>

		<ul>
			<?php
			function dfd_language_selector_flags() {
				if (function_exists('icl_get_languages')) {
					$languages = icl_get_languages('skip_missing=0&orderby=code');

					if (!empty($languages)) {
						foreach ($languages as $l) {
							echo '<li>';
							echo '<a href="' . $l['url'] . '">';
							echo $l['translated_name'];
							echo '</a>';
							echo '</li>';
						}
					}
				}
			}

			dfd_language_selector_flags();
			?>
		</ul>
	<?php elseif (DfdThemeSettings::get("lang_shortcode")): ?>
		<?php echo do_shortcode(DfdThemeSettings::get("lang_shortcode")); ?>
	<?php endif; ?>
</div>
