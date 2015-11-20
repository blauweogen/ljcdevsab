<?php get_header(); ?>

<body <?php body_class(''); ?>>
<?php if (function_exists('dfd_site_preloader_html')) { dfd_site_preloader_html();} ?>
	<div id="main-wrap">
	
		<div id="change_wrap_div" class="<?php if (DfdThemeSettings::get("site_boxed")) { echo ' boxed_lay'; } ?>">

			<?php get_template_part('templates/section', 'header'); ?>

			<?php include crum_template_path(); ?>
			
		</div>
		
		<?php
		switch(DfdThemeSettings::get('show_body_back_to_top')) {
			case 'left':
			case 'right':
				echo '<div class="body-back-to-top align-'.DfdThemeSettings::get('show_body_back_to_top').'"></div>';
				break;
		}
		?>

		<?php get_template_part('templates/section', 'twitter-panel'); ?>

<?php get_footer(); ?>