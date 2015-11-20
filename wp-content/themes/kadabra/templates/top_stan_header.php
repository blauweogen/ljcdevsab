<?php

global $post;
if (!empty($post) && isset($post->ID)) {
	$custom_head_img = get_post_meta($post->ID, 'crum_headers_bg_img', true);
	$custom_head_color = get_post_meta($post->ID, 'crum_headers_bg_color', true);
	$custom_head_subtitle = get_post_meta($post->ID, 'crum_headers_subtitle', true);
} else {
	$custom_head_img = '';
	$custom_head_color = '';
	$custom_head_subtitle = '';
}

if (DfdThemeSettings::get('stan_header')) {
	echo '<div id="stuning-header" style="';
	
	if ($custom_head_color && ($custom_head_color != '#ffffff') && ($custom_head_color != '#')) {
		echo ' background-color: ' . $custom_head_color . '; ';
	} elseif (DfdThemeSettings::get('stan_header_color')) {
		echo ' background-color: ' . DfdThemeSettings::get('stan_header_color') . '; ';
	}
	
	if (!empty($custom_head_img)) {
		echo 'background-image: url(' . $custom_head_img . ');  background-position: center;';
	} elseif (
			DfdThemeSettings::get('stan_header_image') && 
			!(
				$custom_head_color && 
				($custom_head_color != '#ffffff') && ($custom_head_color != '#')
			)
	) {
		echo 'background-image: url(' . DfdThemeSettings::get('stan_header_image') . ');  background-position: center;';
	}

	if (DfdThemeSettings::get('stan_header_fixed')) {
		echo 'background-attachment: fixed; background-position:  center -10%;';
	}

	echo '">';
}