<?php if (DfdThemeSettings::get('custom_logo_image')): ?>
<?php
	$_logo_size = array(164, 104);
	$custom_logo_image = aq_resize(DfdThemeSettings::get('custom_logo_image'), $_logo_size[0], $_logo_size[1]);
	
	if (empty($custom_logo_image)) {
		$custom_logo_image = DfdThemeSettings::get('custom_logo_image');
	}
	
	$custom_retina_logo_image = '';
	$logo_image_w = '';
	$logo_image_h = '';
	
	$upload_info = wp_upload_dir();
	$rel_path = str_replace( $upload_info['baseurl'], '', $custom_logo_image);
	$custom_logo_image_path = $upload_info['basedir'] . $rel_path;

	if (
		DfdThemeSettings::get('custom_retina_logo_image') &&
		file_exists(DfdThemeSettings::get('custom_retina_logo_image')) &&
		getimagesize(DfdThemeSettings::get('custom_retina_logo_image'))
	)
	{
		# Retina ready logo
		$custom_retina_logo_image = aq_resize(DfdThemeSettings::get('custom_retina_logo_image'), $_logo_size[0]*2, $_logo_size[1]*2);
		list($logo_image_w, $logo_image_h) = getimagesize(DfdThemeSettings::get('custom_retina_logo_image'));
	}
	
?>
	<div class="logo-for-panel">
		<a href="<?php echo home_url(); ?>/">
			<img src="<?php echo $custom_logo_image; ?>" alt="<?php bloginfo('name'); ?>" data-retina="<?php echo $custom_retina_logo_image; ?>" data-retina_w="<?php echo $logo_image_w; ?>" data-retina_h="<?php echo $logo_image_h; ?>" />
		</a>
	</div>
<?php endif; ?>