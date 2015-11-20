/** 
	ATTENTION

	!!! DO NOT EDIT ANY FILES IN THIS FOLDERS !!!

	This files will be generated automatically!
*/

<?php if (DfdThemeSettings::get('main_site_color')): ?>
// Main Color
@main-site-light-color: <?php echo DfdThemeSettings::get('main_site_color'); ?>;
<?php endif; ?>
<?php if (DfdThemeSettings::get('secondary_site_color')): ?>
// Second Color
@second-site-light-color: <?php echo DfdThemeSettings::get('secondary_site_color'); ?>;
<?php endif; ?>
<?php if (DfdThemeSettings::get('third_site_color')): ?>
// Third Color
@addition-color: <?php echo DfdThemeSettings::get('third_site_color'); ?>;
<?php endif; ?>
<?php if (DfdThemeSettings::get('font_site_color')): ?>
// Text Color
@font-site-light-color: <?php echo DfdThemeSettings::get('font_site_color'); ?>;
<?php endif; ?>
<?php if (DfdThemeSettings::get('link_site_color')): ?>
// Link Color
@link-color: <?php echo DfdThemeSettings::get('link_site_color'); ?>;
<?php endif; ?>

<?php if (DfdThemeSettings::get('header_background_color')): ?>
@header-background-color: <?php echo DfdThemeSettings::get('header_background_color'); ?>;
<?php endif; ?>

<?php if (DfdThemeSettings::get('fixed_header_background_color')): ?>
@fixed-header-background-color: <?php echo DfdThemeSettings::get('fixed_header_background_color'); ?>;
<?php endif; ?>

<?php if (DfdThemeSettings::get('fixed_header_background_opacity')): ?>
@fixed-header-background-opacity: <?php echo DfdThemeSettings::get('fixed_header_background_opacity'); ?>;
<?php endif; ?>

<?php if (DfdThemeSettings::get('news_page_slider_background_hover')): ?>
@news-page-slider-background-hover: <?php echo DfdThemeSettings::get('news_page_slider_background_hover'); ?>;
<?php endif; ?>

<?php if (DfdThemeSettings::get('news_page_slider_opacity_hover')): ?>
@news-page-slider-opacity-hover: <?php echo DfdThemeSettings::get('news_page_slider_opacity_hover'); ?>;
<?php endif; ?>

<?php if (DfdThemeSettings::get('read_more_color')): ?>
@read-more-color: <?php echo DfdThemeSettings::get('read_more_color'); ?>;
<?php endif; ?>

<?php if (DfdThemeSettings::get('button_bg_color')): ?>
@button-bg-color: <?php echo DfdThemeSettings::get('button_bg_color'); ?>;
<?php endif; ?>
