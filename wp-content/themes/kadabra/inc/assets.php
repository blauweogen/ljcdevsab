<?php
/**
 * Scripts and stylesheets
 */

if (!function_exists('dfd_kadabra_scripts')) {
	/**
	 * Enqueue front scripts and styles
	 * @global obj $woocommerce
	 */
	function dfd_kadabra_scripts() {
		/*
		 * Css styles
		 */
		wp_enqueue_style( 'dfd_preloader_indicator', get_template_directory_uri() . '/assets/css/site-preloader.css' );
		wp_enqueue_style( 'main-style', get_stylesheet_uri() );
		wp_register_script('dfd_queryloader2', get_template_directory_uri() . '/assets/js/jquery.queryloader2.js', array('jquery'), '2', false);
		
		wp_register_style('dfd_preloader_style', get_template_directory_uri() . '/assets/css/preloader.css', false, null);
		wp_register_style('dfd_site_style', get_template_directory_uri() . '/assets/css/app.css', false, null);
		
		wp_enqueue_style('dfd_preloader_style');
		wp_enqueue_style('dfd_site_style');
		
		/**
		 * Check if WooCommerce is active
		 * */
		if (is_plugin_active('woocommerce/woocommerce.php')) {
			wp_register_style('dfd_wocommerce_style', get_template_directory_uri() . '/assets/css/woocommerce.css', false, null);
			wp_enqueue_style('dfd_wocommerce_style');

			global $woocommerce;
			if (intval($woocommerce->version) >= 2) {
				wp_deregister_script('wc-add-to-cart-variation');
				wp_register_script('wc-add-to-cart-variation', get_template_directory_uri() . '/assets/js/woocommerce/add-to-cart-variation.js', array( 'jquery' ), $woocommerce->version, true);
			}
		}

		/* TODO: move to less */
		wp_enqueue_style('crum_effects', get_template_directory_uri() . '/assets/css/animate-custom.css', false, null);
		wp_enqueue_style('crum_bbpress', get_template_directory_uri() . '/assets/css/bbpress.css', false, null);
		wp_enqueue_style('isotope_style', get_template_directory_uri() . '/assets/css/jquery.isotope.css', false, null);
		wp_enqueue_style('prettyphoto_style', get_template_directory_uri() . '/assets/css/prettyPhoto.css', false, null);
		/*********** ^ ^ ^ ^ ^ ^ ^ ^ ^ ^ ^ ^ ^ ^ ^ ^ ^ ^ ^ ^ *********/
		wp_enqueue_style('flexslider_style', get_template_directory_uri() . '/assets/css/flexslider.css', false, null);

		wp_enqueue_style('crum_theme_options', get_template_directory_uri() . '/css/options.css', false, null);

		/*
		 * JS register
		 */
		wp_register_script('crum_foundation', get_template_directory_uri() . '/assets/js/foundation.min.js', false, null, true);
		wp_register_script('crum_effects', get_template_directory_uri() . '/assets/js/animation.js', false, null, true);
		wp_register_script('dfd_slide_parallax', get_template_directory_uri() . '/assets/js/jquery.slide_parallax.js', false, null, true);

		wp_register_script('smooth-scroll', get_template_directory_uri() . '/assets/js/jquery.smothscroll.min.js', false, '1.4.11', true);
		wp_register_script('crum_main', get_template_directory_uri() . '/assets/js/app.js', false, null, true);
		wp_register_script('isotope', get_template_directory_uri() . '/assets/js/jquery.isotope.min.js', false, null, true);
		wp_register_script('isotope_recenworks', get_template_directory_uri() . '/assets/js/jquery.isotope.recentworks.js', false, null, true);
		wp_register_script('isotope-run-2col', get_template_directory_uri() . '/assets/js/jquery.isotope.2col.run.js', false, null, true);
		wp_register_script('isotope-run-3col', get_template_directory_uri() . '/assets/js/jquery.isotope.3col.run.js', false, null, true);
		wp_register_script('isotope-run-4col', get_template_directory_uri() . '/assets/js/jquery.isotope.4col.run.js', false, null, true);
		wp_register_script('masonry', get_template_directory_uri() . '/assets/js/jquery.masonry.min.js', true, null, false);
		wp_deregister_script('flexslider');
		wp_register_script('flexslider', get_template_directory_uri() . '/assets/js/jquery.flexslider-min.js', false, null, false);
		wp_register_script('jcarousel', get_template_directory_uri() . '/assets/js/jquery.jcarousel.min.js', false, null, false);
		if (!wp_script_is('themepunchtools') && !wp_script_is('revslider-jquery.themepunch.plugins.min')) {
			wp_register_script('hammer', get_template_directory_uri() . '/assets/js/hammer.min.js', false, null, false);
		}
		wp_register_script('jCarouselSwipe', get_template_directory_uri() . '/assets/js/jCarouselSwipe.min.js', array('jcarousel', 'crum_foundation'), null, false);

		wp_register_script('gmaps', '//maps.google.com/maps/api/js?sensor=false', false, null, false);
		wp_register_script('gmap3', get_template_directory_uri() . '/assets/js/gmap3.min.js', false, null, true);
		wp_register_script('prettyphoto', get_template_directory_uri() . '/assets/js/jquery.prettyPhoto.js', false, null, true);
		wp_register_script('qr_code', get_template_directory_uri() . '/assets/js/qrcode.min.js', false, null, true);
		wp_register_script('custom-share', get_template_directory_uri() . '/assets/js/jquery.sharrre.js', array('jquery'), '1.3.5', true);

		wp_register_script('feature-image-box-transform', get_template_directory_uri() . '/assets/js/jquery.feature-image-box-transform.js', false, null, true);

		wp_register_script('vertical_js', get_template_directory_uri() . '/assets/js/vertical.js', false, null, false);
		wp_register_script('woocommerce_hack', get_template_directory_uri() . '/assets/js/woocommerce_hack.js', false, null, false);

		wp_register_script('dropdown', get_template_directory_uri() . '/assets/js/dropdown.js', false, null, true);
		wp_register_script('dropkick', get_template_directory_uri() . '/assets/js/jquery.dropkick-min.js', false, null, true);

		// Audioplayer
		wp_register_script('js-audio', get_template_directory_uri().'/assets/js/audioplayer.js', false, null, true);
		wp_register_script('js-audio-run', get_template_directory_uri().'/assets/js/audioplayer.run.js', false, null, true);
		
		// Video Player
		wp_register_script('dfd_self_hosted_videos_js', '//vjs.zencdn.net/c/video.js');
		wp_register_style('dfd_self_hosted_videos_css', '//vjs.zencdn.net/c/video-js.css');
		
		// Facebook Widget
		wp_register_script('dfd_facebook_widget_script', get_template_directory_uri().'/assets/js/widget-facebook.js', false, null, true);

		wp_register_script('mega_menu', get_template_directory_uri().'/assets/js/jquery.mega-menu.js', false, null, false);
		wp_register_script('mega_menu_run', get_template_directory_uri().'/assets/js/jquery.mega-menu.run.js', false, null, false);
		wp_register_script('dl_menu', get_template_directory_uri().'/assets/js/jquery.dlmenu.js', false, '1.0.1', true);

		/**
		 * Enqueue Preloader
		 */
		if (strcmp(DfdThemeSettings::get('site_preloader_enabled'),'1')===0) {
			wp_enqueue_style('dfd_preloader_indicator');
		}
		
		/*
		 * JS enquene
		 */
		wp_enqueue_script('jquery');
		if (strcmp(DfdThemeSettings::get('site_preloader_enabled'),'1')===0) {
			wp_enqueue_script('dfd_queryloader2');
		}
		wp_enqueue_script('crum_foundation');

		wp_enqueue_script('flexslider');
		wp_enqueue_script('jcarousel');
		wp_enqueue_script('hammer');
		wp_enqueue_script('jCarouselSwipe');

		wp_enqueue_script('crum_effects');

		if (strcmp(DfdThemeSettings::get('scroll_animation'),'off')!==0) {
			wp_enqueue_script('smooth-scroll');
		}

		wp_enqueue_script('custom-share');
		wp_enqueue_script('vertical_js');

		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
			wp_enqueue_script('dropdown');
			wp_enqueue_script('dropkick');
			wp_enqueue_script('woocommerce_hack');
		}

		wp_enqueue_script('crum_main');
		wp_enqueue_script('prettyphoto');
		
		wp_enqueue_script('dl_menu');
		
		wp_register_style('dfd_zencdn_video_css', '//vjs.zencdn.net/c/video-js.css', false, null);
		wp_register_script('dfd_zencdn_video_js', '//vjs.zencdn.net/c/video.js', false, null);
		
		wp_register_script('dfd-masonry-2cols-run', get_template_directory_uri() . '/assets/js/jquery.masonry.2cols.run.js', true, null, true);
		wp_register_script('dfd-masonry-3cols-run', get_template_directory_uri() . '/assets/js/jquery.masonry.3cols.run.js', true, null, true);
		wp_register_script('dfd-masonry-mini-3cols-run', get_template_directory_uri() . '/assets/js/jquery.masonry.mini.3cols.run.js', true, null, true);
		wp_register_script('dfd-masonry-mini-4cols-run', get_template_directory_uri() . '/assets/js/jquery.masonry.mini.4cols.run.js', true, null, true);
		
		wp_register_script('dfd-isotope-enable', get_template_directory_uri() . '/assets/js/jquery.isotope.enable.js', true, null, true);
		
		// deprecated
		wp_register_script('dfd-masonry-enable', get_template_directory_uri() . '/assets/js/jquery.masonry.enable.js', true, null, true);
		
		# Load script/styles for page templates
		if (is_page()) {
			$curr_page_template = basename(get_page_template());

			switch($curr_page_template) {
				case 'page-contacts.php':
					wp_enqueue_script('gmaps');
					wp_enqueue_script('gmap3');
					wp_enqueue_script('qr_code');
					break;

				case 'tmp-posts-masonry-2.php':
				case 'tmp-posts-masonry-2-side.php':
				case 'tmp-posts-masonry-2-left-side.php':
					wp_enqueue_script('isotope');
					wp_enqueue_script('dfd-masonry-2cols-run');
					break;

				case 'tmp-posts-masonry-3-left-sidebar.php':
				case 'tmp-posts-masonry-3-right-sidebar.php':
				case 'tmp-posts-masonry-3.php':
					wp_enqueue_script('isotope');
					wp_enqueue_script('dfd-masonry-3cols-run');
					break;

				case 'tmp-portfolio-masonry-full-width.php':
				case 'tmp-portfolio-masonry-full-width-bordered.php':
				case 'tmp-portfolio-masonry-1.php':
				case 'tmp-portfolio-masonry-1-bordered.php':
					wp_enqueue_script('isotope');
					wp_enqueue_script('dfd-isotope-enable');
					break;

				case 'tmp-portfolio-template-2mini.php':
				case 'tmp-portfolio-template-2excerpt.php':
				case 'tmp-portfolio-template-2.php':
				case 'tmp-portfolio-template-2-right-sidebar.php':
					wp_enqueue_script('isotope');
					wp_enqueue_script('isotope-run-2col');
					break;
				case 'tmp-portfolio-template-3excerpt.php':
				case 'tmp-portfolio-template-3.php':
				case 'tmp-portfolio-template-3-right-sidebar.php':
				case 'tmp-portfolio-template-3mini.php':
					wp_enqueue_script('isotope');
					wp_enqueue_script('isotope-run-3col');
					break;
				case 'tmp-portfolio-template-4mini.php':
				case 'tmp-portfolio-template-4excerpt.php':
				case 'tmp-portfolio-template-4.php':
					wp_enqueue_script('isotope');
					wp_enqueue_script('isotope-run-4col');
					break;

				case 'tmp-portfolio-masonry_mini.php':
				case 'tmp-portfolio-masonry_excerpt.php':
				case 'tmp-portfolio-masonry.php':
				case 'tmp-portfolio-masonry-sidebar_mini.php':
				case 'tmp-portfolio-masonry-sidebar_excerpt.php':
				case 'tmp-portfolio-masonry-sidebar.php':
					wp_enqueue_script('isotope');
					wp_enqueue_script('dfd-masonry-mini-3cols-run');
					break;

				case 'tmp-portfolio-masonry-4mini.php':
				case 'tmp-portfolio-masonry-4excerpt.php':
				case 'tmp-portfolio-masonry-4.php':
					wp_enqueue_script('isotope');
					wp_enqueue_script('dfd-masonry-mini-4cols-run');
					break;
			}
		}
		
		if (function_exists('post_like_scripts')) {
			post_like_scripts();
		}
	}
}

/**
 * Enqueue the Souce sans font.
 */
function dfd_kadabra_enq_fonts() {
    wp_enqueue_style('dfd_font_roboto', "//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic");
}

if (!function_exists('dfd_kadabra_admin_css')) {
	/**
	 * Enqueue admin scripts and styles
	 */
	function dfd_kadabra_admin_css() {
		wp_register_style('crum-admin-style', get_template_directory_uri() . '/assets/css/admin-panel.css');
		wp_enqueue_style('crum-admin-style');
	}
}

if (!function_exists('dfd_custom_page_style')) {
	function dfd_custom_page_style() {
		if(is_page()) {
			global $post;
			
			if (empty($post)) return;
			
			$p_bg_color = get_post_meta( $post->ID, 'crum_page_custom_bg_color', true );
			$p_bg_image = get_post_meta( $post->ID, 'crum_page_custom_bg_image', true );
			$p_bg_fixed = get_post_meta( $post->ID, 'crum_page_custom_bg_fixed', true );
			$p_bg_repeat = get_post_meta( $post->ID, 'crum_page_custom_bg_repeat', true );
			?>
			<style type="text/css">
				body {
					<?php if ((strcmp($p_bg_color,'#')!==0) && !empty($p_bg_color) && (strcmp($p_bg_color,'#ffffff')!==0)): ?>
						background-color: <?php echo $p_bg_color; ?> !important;
					<?php endif; ?>

					<?php if(!empty($p_bg_image)): ?>
						background-image: <?php echo "url('{$p_bg_image}')"; ?> !important;
						background-position: center 0 !important;
					<?php endif; ?>

					<?php if(!empty($p_bg_repeat)): ?>
						background-repeat: <?php echo $p_bg_repeat; ?> !important;
					<?php endif; ?>

					<?php if ($p_bg_fixed): ?>
						background-attachment: fixed !important;
					<?php endif; ?>
				}
			</style>
			<?php
		}
	}
}
