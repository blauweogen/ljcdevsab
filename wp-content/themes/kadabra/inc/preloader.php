<?php

if (!function_exists('dfd_site_preloader_html')) {
	
	function dfd_site_preloader_html() {
		if (strcmp(DfdThemeSettings::get('site_preloader_enabled'),'1')===0) {
			if (DfdThemeSettings::get("custom_logo_image")) {
				echo '<img id="qLoverlay_logo" src="'.DfdThemeSettings::get("custom_logo_image").'" style="display: none" alt="" />';
			}
			
			echo '<div id="qLoverlay">';
			
			if (
				DfdThemeSettings::get("site_preloader_logo_1") &&
				DfdThemeSettings::get("site_preloader_logo_2")
			) {
				$logo_1 = DfdThemeSettings::get("site_preloader_logo_1");
				$logo_2 = DfdThemeSettings::get("site_preloader_logo_2");

				if (getimagesize($logo_1) && getimagesize($logo_2))
				{
					list($logo_1_w, $logo_1_h) = getimagesize($logo_1);
					list($logo_2_w, $logo_2_h) = getimagesize($logo_2);
					
					$logo_w = min($logo_1_w, $logo_2_w);
					$logo_h = min($logo_1_h, $logo_2_h);
					
					$img1 = '<img src="'.$logo_1.'" width="'.$logo_w.'" height="'.$logo_h.'" alt="" />';
					$img2 = '<img src="'.$logo_2.'" width="'.$logo_w.'" height="'.$logo_h.'" alt="" />';
					
					echo '<div id="qLbar_wrap" style="width:'.$logo_w.'px; height:'.$logo_h.'px; margin-top: -'.ceil($logo_h/2).'px;">';
					echo '<div id="qLbar" style="width: 0%">'.$img1.'</div>'
						. '<div id="qLbar_bg">'.$img2.'</div>';
					echo '</div>';
					echo '<div id="qLbar_wrap_label">Theme is loading...</div>';
				}
			}
			
			echo '<div id="qLpercentage"></div></div>';
		}
		
	}
}