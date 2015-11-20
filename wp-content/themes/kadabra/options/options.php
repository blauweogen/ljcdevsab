<?php

/*
 *
 * Set the text domain for the theme or plugin.
 *
 */

/*
 *
 * Require the framework class before doing anything else, so we can use the defined URLs and directories.
 * If you are running on Windows you may have URL problems which can be fixed by defining the framework url first.
 *
 */
//define('Redux_OPTIONS_URL', site_url('path the options folder'));
if (!class_exists('Redux_Options')) {
	require_once(dirname(__FILE__) . '/options/option_values.php');
	require_once(dirname(__FILE__) . '/options/defaults.php');
}

/*
 *
 * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 *
 * NOTE: the defined constansts for URLs, and directories will NOT be available at this point in a child theme,
 * so you must use get_template_directory_uri() if you want to use any of the built in icons
 *
 */
/*
  function add_another_section($sections) {
  //$sections = array();
  $sections[] = array(
  'title' => __('A Section added by hook', 'dfd'),
  'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'dfd'),
  'icon' => 'paper-clip',
  'icon_class' => 'awesome-large',
  // Leave this as a blank section, no options just some intro text set above.
  'fields' => array()
  );

  return $sections;
  }
  //add_filter('redux-opts-sections-twenty_eleven', 'add_another_section');
 */



/*
 * 
 * Custom function for filtering the args array given by a theme, good for child themes to override or add to the args array.
 *
 */
/*
  function change_framework_args($args) {
  $args['dev_mode'] = true;

  return $args;
  }
  //add_filter('redux-opts-args-twenty_eleven', 'change_framework_args');


  /*
 *
 * Most of your editing will be done in this section.
 *
 * Here you can override default values, uncomment args and change their values.
 * No $args are required, but they can be over ridden if needed.
 *
 */

function setup_framework_options($get_sections=false) {
	$args = array();

	// Setting dev mode to true allows you to view the class settings/info in the panel.
	// Default: true
	$args['dev_mode'] = false;

	// Set the icon for the dev mode tab.
	// If $args['icon_type'] = 'image', this should be the path to the icon.
	$args['icon_type'] = 'iconfont';
	// Default: info-sign
	//$args['dev_mode_icon'] = 'info-sign';
	// Set the class for the dev mode tab icon.
	// This is ignored unless $args['icon_type'] = 'iconfont'
	// Default: null
	$args['dev_mode_icon_class'] = 'icon-large';

	// If you want to use Google Webfonts, you MUST define the api key.
	//$args['google_api_key'] = 'xxxx';
	// Define the starting tab for the option panel.
	// Default: '0';
	//$args['last_tab'] = '0';
	// Define the option panel stylesheet. Options are 'standard', 'custom', and 'none'
	// If only minor tweaks are needed, set to 'custom' and override the necessary styles through the included custom.css stylesheet.
	// If replacing the stylesheet, set to 'none' and don't forget to enqueue another stylesheet!
	// Default: 'standard'
	//$args['admin_stylesheet'] = 'standard';
	// Add HTML before the form.
	/*
	  $args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'dfd');

	  // Add content after the form.
	  $args['footer_text'] = __('<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'dfd');

	  // Set footer/credit line.
	  //$args['footer_credit'] = __('<p>This text is displayed in the options panel footer across from the WordPress version (where it normally says \'Thank you for creating with WordPress\'). This field accepts all HTML.</p>', 'dfd');

	  // Setup custom links in the footer for share icons
	  $args['share_icons']['twitter'] = array(
	  'link' => 'http://twitter.com/ghost1227',
	  'title' => __('Follow me on Twitter', 'dfd'),
	  'img' => Redux_OPTIONS_URL . 'img/social/Twitter.png'
	  );
	  $args['share_icons']['linked_in'] = array(
	  'link' => 'http://www.linkedin.com/profile/view?id=52559281',
	  'title' => __('Find me on LinkedIn', 'dfd'),
	  'img' => Redux_OPTIONS_URL . 'img/social/LinkedIn.png'
	  );
	 */
	// Enable the import/export feature.
	// Default: true
	$args['show_import_export'] = true;

	// Set the icon for the import/export tab.
	// If $args['icon_type'] = 'image', this should be the path to the icon.
	$args['import_icon_type'] = 'iconfont';
	// Default: refresh
	$args['import_icon'] = 'refresh';

	// Set the class for the import/export tab icon.
	// This is ignored unless $args['icon_type'] = 'iconfont'
	// Default: null
	$args['import_icon_class'] = 'icon-large';

	// Set a custom option name. Don't forget to replace spaces with underscores!
	$args['opt_name'] = DFD_THEME_SETTINGS_NAME;

	// Set a custom menu icon.
	//$args['menu_icon'] = '';
	// Set a custom title for the options page.
	// Default: Options
	$args['menu_title'] = __('Options', 'dfd');

	// Set a custom page title for the options page.
	// Default: Options
	$args['page_title'] = __('Options', 'dfd');

	// Set a custom page slug for options page (wp-admin/themes.php?page=***).
	// Default: redux_options
	$args['page_slug'] = 'redux_options';

	// Set a custom page capability.
	// Default: manage_options
	//$args['page_cap'] = 'manage_options';
	// Set the menu type. Set to "menu" for a top level menu, or "submenu" to add below an existing item.
	// Default: menu
	//$args['page_type'] = 'submenu';
	// Set the parent menu.
	// Default: themes.php
	// A list of available parent menus is available at http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	//$args['page_parent'] = 'options-general.php';
	// Set a custom page location. This allows you to place your menu where you want in the menu order.
	// Must be unique or it will override other items!
	// Default: null
	//$args['page_position'] = null;
	// Set a custom page icon class (used to override the page icon next to heading)
	//$args['page_icon'] = 'icon-themes';
	// Set the icon type. Set to "iconfont" for Font Awesome, or "image" for traditional.
	// Redux no longer ships with standard icons!
	// Default: iconfont
	//$args['icon_type'] = 'image';
	//$args['dev_mode_icon_type'] = 'image';
	//$args['import_icon_type'] == 'image';
	// Disable the panel sections showing as submenu items.
	// Default: true
	//$args['allow_sub_menu'] = false;

	$assets_folder = get_template_directory_uri() . '/assets/';

	// Set ANY custom page help tabs, displayed using the new help tab API. Tabs are shown in order of definition.
	/* $args['help_tabs'][] = array(
	  'id' => 'redux-opts-1',
	  'title' => __('Theme Information 1', 'dfd'),
	  'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'dfd')
	  );
	  $args['help_tabs'][] = array(
	  'id' => 'redux-opts-2',
	  'title' => __('Theme Information 2', 'dfd'),
	  'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'dfd')
	  );

	  // Set the help sidebar for the options page.
	  $args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'dfd');
	 */
	$sections = array();

	$sections[] = array(
		// Redux uses the Font Awesome iconfont to supply its default icons.
		// If $args['icon_type'] = 'iconfont', this should be the icon name minus 'icon-'.
		// If $args['icon_type'] = 'image', this should be the path to the icon.
		// Icons can also be overridden on a section-by-section basis by defining 'icon_type' => 'image'
		'icon_type' => 'image',
		'icon' => Redux_OPTIONS_URL . 'img/home.png',
		// Set the class for this icon.
		// This field is ignored unless $args['icon_type'] = 'iconfont'
		'icon_class' => 'icon-large',
		'title' => __('Getting Started', 'dfd'),
		'desc' => __('<p class="description">This is the description field for this section. HTML is allowed</p>', 'dfd'),
		'fields' => array(
			array(
				'id' => 'font_awesome_info',
				'type' => 'raw_html',
				'html' => '<h3 style="text-align: center; border-bottom: none;">Welcome to the Options panel of the Kadabra theme!</h3>
				<h4 style="text-align: center; font-size: 1.3em;">What does this mean to you?</h4>
				<p>From here on you will be able to regulate the main options of all the elements of the theme. </p>
				<p>Theme documentation you will find in the archive with the theme I the "Documentation" folder. </p>
				<p>If you have some questions on the theme, you can send them to our PM on <a href="http://themeforest.net/user/DFDevelopment">Themeforest.net</a>, you can send us email directly to <a href="mailto:dynamicframeworks@gmail.com">dynamicframeworks@gmail.com</a>, or you can post your questions on our <a href="http://support.dfd.name">Support Forum</a>.</p>'
			),
			array(
				'id' => 'import_demo_data',
				'type' => 'raw_html',
				'html' => (!$get_sections && is_plugin_active('sb-import/sb-import.php')) ? '<br /><br /><br /><h4 style="text-align: center; font-size: 1.3em;">Import Demo Data</h4>'
						. '<p style="text-align: center; color: red">If you click on this button, all current settings and posts will be removed!</p>'
						. '<p style="text-align: center;"><a onclick="return confirm(\'Continue import demo content?\')" href="admin.php?page=sb-import&action=import&_nonce=' . wp_create_nonce('sb_import_nonce') . '" class="button button-primary">' . __('Import demo content', 'dfd') . '</a></p>' : '',
			),
		),
	);
	$sections[] = array(
		'title' => __('Main Options', 'dfd'),
		'desc' => __('<p class="description">Main options of site</p>', 'dfd'),
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it blank for default.
		'icon' => 'globe',
		//Lets leave this as a blank section, no options just some intro text set above.
		'fields' => array(
			array(
				'id' => 'scroll_animation',
				'type' => 'button_set',
				'title' => __('Page scroll animation', 'dfd'),
				'desc' => __('Enable or disable page scroll animation', 'dfd'),
				'options' => array('on' => 'On', 'off' => 'Off'),
				'std' => 'on'
			),
			array(
				'id' => 'custom_favicon',
				'type' => 'upload',
				'title' => __('Favicon', 'dfd'),
				'desc' => __('Select a 16px X 16px image from the file location on your computer and upload it as a favicon of your site', 'dfd')
			),
			array(
				'id' => 'custom_logo_image',
				'type' => 'upload',
				'title' => __('Header Logotype image', 'dfd'),
				'desc' => __('Select an image from the file location on your computer and upload it as a header logotype', 'dfd'),
				'std' => $assets_folder . 'img/logo.png',
			),
			array(
				'id' => 'custom_retina_logo_image',
				'type' => 'upload',
				'title' => __('Header Logotype image for retina', 'dfd'),
				'desc' => __('Select an image from the file location on your computer and upload it as a header logotype', 'dfd'),
				'std' => $assets_folder . 'img/logo_retina.png',
			),
			array(
				'id' => 'custom_logo_image_second',
				'type' => 'upload',
				'title' => __('Header Logotype second image', 'dfd'),
				'desc' => __('Select an image from the file location on your computer and upload it as a header logotype. If this logo wasn\'t uploaded will be displayed first logo.', 'dfd'),
				'std' => $assets_folder . 'img/logo_second.png',
			),
			array(
				'id' => 'custom_retina_logo_image_second',
				'type' => 'upload',
				'title' => __('Header Logotype second image for retina', 'dfd'),
				'desc' => __('Select an image from the file location on your computer and upload it as a header logotype. If this logo wasn\'t uploaded will be displayed first logo.', 'dfd'),
				'std' => $assets_folder . 'img/logo_retina.png',
			),
			array(
				'id' => 'site_preloader_enabled',
				'type' => 'button_set',
				'title' => __('Site Preloader', 'dfd'),
				'desc' => __('Enable or disable site preloader counter', 'dfd'),
				'options' => array('1' => 'On', '0' => 'Off'),
				'std' => '1'
			),
			array(
				'id' => 'site_preloader_logo_1',
				'type' => 'upload',
				'title' => __('Site Preloader Logotype image 1', 'dfd'),
				'desc' => __('Select an image from the file location on your computer and upload it as a preloader logotype 1', 'dfd'),
				'std' => $assets_folder . 'img/logo_preloader_1.png',
			),
			array(
				'id' => 'site_preloader_logo_2',
				'type' => 'upload',
				'title' => __('Site Preloader Logotype image 2', 'dfd'),
				'desc' => __('Select an image from the file location on your computer and upload it as a preloader logotype 2', 'dfd'),
				'std' => $assets_folder . 'img/logo_preloader_2.png',
			),
			array(
				'id' => 'wpml_lang_show',
				'type' => 'button_set',
				'title' => __('WPML language switcher', 'dfd'),
				'desc' => __('WPML plugin must be installed. It is not packed with theme. You can find it here: http://wpml.org/', 'dfd'),
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '0'
			),
//			array(
//				'id' => 'show_login_form',
//				'type' => 'button_set',
//				'title' => __('Show login form in header', 'dfd'),
//				'desc' => '',
//				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
//				'std' => '1'
//			),
			array(
				'id' => 'show_search_form',
				'type' => 'button_set',
				'title' => __('Show search form in header', 'dfd'),
				'desc' => '',
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '1'
			),
			array(
				'id' => 'custom_js',
				'type' => 'textarea',
				'title' => __('Custom JS', 'dfd'),
				'desc' => __('Generate your tracking code at Google Analytics Service and insert it here.', 'dfd'),
			),
			array(
				'id' => 'custom_css',
				'type' => 'textarea',
				'title' => __('Custom CSS', 'dfd'),
				'desc' => __('You may add any other styles for your theme to this field.', 'dfd'),
			),
			array(
				'id' => 'show_body_back_to_top',
				'type' => 'button_set',
				'title' => __('Show "To top" button', 'dfd'),
				'desc' => __('', 'dfd'),
				'options' => array(
					'no' => __('No', 'dfd'),
					'left' => __('Left', 'dfd'),
					'right' => __('Right', 'dfd')),
				'std' => 'right'
			),
			array(
				'id' => 'shop_title',
				'type' => 'text',
				'title' => __('Shop Title', 'dfd'),
				'std' => 'Shop'
			)
		),
	);

	$sections[] = array(
		'title' => __('Social accounts', 'dfd'),
		'desc' => __('<p class="description">Type links for social accounts</p>', 'dfd'),
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it blank for default.
		'icon' => 'user',
		//Lets leave this as a blank section, no options just some intro text set above.
		'fields' => array(
			array(
				'id' => 'fb_link',
				'type' => 'text',
				'title' => __('Facebook link', 'dfd'),
				'desc' => __('Paste link to your account', 'dfd'),
				'std' => 'http://facebook.com'
			),
			array(
				'id' => 'fb_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '1'// 1 = on | 0 = off
			),
			array(
				'id' => 'tw_link',
				'type' => 'text',
				'title' => __('Twitter link', 'dfd'),
				'desc' => __('Paste link to your account', 'dfd'),
				'std' => 'http://twitter.com'
			),
			array(
				'id' => 'tw_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '1'// 1 = on | 0 = off
			),
			array(
				'id' => 'in_link',
				'type' => 'text',
				'title' => __('Instagram link', 'dfd'),
				'desc' => __('Paste link to your account', 'dfd'),
				'std' => 'http://instagram.com'
			),
			array(
				'id' => 'in_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '0'// 1 = on | 0 = off
			),
			array(
				'id' => 'vi_link',
				'type' => 'text',
				'title' => __('Vimeo link', 'dfd'),
				'desc' => __('Paste link to your account', 'dfd'),
				'std' => 'http://vimeo.com'
			),
			array(
				'id' => 'vi_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '0'// 1 = on | 0 = off
			),
			array(
				'id' => 'lf_link',
				'type' => 'text',
				'title' => __('Last FM link', 'dfd'),
				'desc' => __('Paste link to your account', 'dfd'),
				'std' => 'http://lastfm.com'
			),
			array(
				'id' => 'lf_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '0'// 1 = on | 0 = off
			),
			array(
				'id' => 'vk_link',
				'type' => 'text',
				'title' => __('Vkontakte link', 'dfd'),
				'desc' => __('Paste link to your account', 'dfd'),
				'std' => 'http://vk.com'
			),
			array(
				'id' => 'vk_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '1'// 1 = on | 0 = off
			),
			array(
				'id' => 'yt_link',
				'type' => 'text',
				'title' => __('YouTube link', 'dfd'),
				'desc' => __('Paste link to your account', 'dfd'),
				'std' => 'http://youtube.com'
			),
			array(
				'id' => 'yt_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '0'// 1 = on | 0 = off
			),
			array(
				'id' => 'de_link',
				'type' => 'text',
				'title' => __('Deviantart link', 'dfd'),
				'desc' => __('Paste link to your account', 'dfd'),
				'std' => 'https://deviantart.com/'
			),
			array(
				'id' => 'de_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '0'// 1 = on | 0 = off
			),
			array(
				'id' => 'li_link',
				'type' => 'text',
				'title' => __('LinkedIN link', 'dfd'),
				'desc' => __('Paste link to your account', 'dfd'),
				'std' => 'http://linkedin.com'
			),
			array(
				'id' => 'li_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '1'// 1 = on | 0 = off
			),
			array(
				'id' => 'gp_link',
				'type' => 'text',
				'title' => __('Google + link', 'dfd'),
				'desc' => __('Paste link to your account', 'dfd'),
				'std' => 'https://accounts.google.com/'
			),
			array(
				'id' => 'gp_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '1'// 1 = on | 0 = off
			),
			array(
				'id' => 'pi_link',
				'type' => 'text',
				'title' => __('Picasa link', 'dfd'),
				'desc' => __('Paste link to your account', 'dfd'),
				'std' => 'http://picasa.com'
			),
			array(
				'id' => 'pi_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '0'// 1 = on | 0 = off
			),
			array(
				'id' => 'pt_link',
				'type' => 'text',
				'title' => __('Pinterest link', 'dfd'),
				'desc' => __('Paste link to your account', 'dfd'),
				'std' => 'http://pinterest.com'
			),
			array(
				'id' => 'pt_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '0'// 1 = on | 0 = off
			),
			array(
				'id' => 'wp_link',
				'type' => 'text',
				'title' => __('Wordpress link', 'dfd'),
				'desc' => __('Paste link to your account', 'dfd'),
				'std' => 'http://wordpress.com'
			),
			array(
				'id' => 'wp_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '0'// 1 = on | 0 = off
			),
			array(
				'id' => 'db_link',
				'type' => 'text',
				'title' => __('Dropbox link', 'dfd'),
				'desc' => __('Paste link to your account', 'dfd'),
				'std' => 'http://dropbox.com'
			),
			array(
				'id' => 'db_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '0'// 1 = on | 0 = off
			),
			array(
				'id' => 'rss_link',
				'type' => 'text',
				'title' => __('RSS', 'dfd'),
				'desc' => __('Paste alternative link to Rss', 'dfd'),
				'std' => ''
			),
			array(
				'id' => 'rss_show',
				'type' => 'checkbox',
				'title' => __('Show in footer', 'dfd'),
				'sub_desc' => __('If checked - will be display in header of theme ', 'dfd'),
				'std' => '0'// 1 = on | 0 = off
			),
		),
	);

	$sections[] = array(
		'title' => __('Posts list options', 'dfd'),
		'desc' => __('<p class="description">Parameters for posts and archives (social share etc)</p>', 'dfd'),
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it blank for default.
		'icon' => 'folder-open-alt',
		//Lets leave this as a blank section, no options just some intro text set above.
		'fields' => array(
			array(
				'id' => 'info_msc',
				'type' => 'info',
				'desc' => __('<h3 class="description">Inner post page options</h3>', 'dfd')
			),
			array(
				'id' => 'post_share_button',
				'type' => 'button_set',
				'title' => __('Social share buttons', 'dfd'),
				'desc' => __('With this option you may activate or deactivate social share buttons. and date on inner post page', 'dfd'),
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '1'// 1 = on | 0 = off
			),
			array(
				'id' => 'custom_share_code',
				'type' => 'textarea',
				'title' => __('Custom share code', 'dfd'),
				'desc' => __('You may add any other social share buttons to this field.', 'dfd'),
			),
			array(
				'id' => 'autor_box_disp',
				'type' => 'button_set',
				'title' => __('Author Info', 'dfd'),
				'desc' => __('This option enables you to insert information about the author of the post.', 'dfd'),
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '1'// 1 = on | 0 = off
			),
			array(
				'id' => 'thumb_inner_disp',
				'type' => 'button_set', //the field type
				'title' => __('Thumbnail on inner page', 'dfd'),
				'desc' => __('Display featured image on single post', 'dfd'),
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '0'//this should be the key as defined above
			),
			array(
				'id' => 'info_msc',
				'type' => 'info',
				'desc' => __('<h3 class="description">Archive page options</h3>', 'dfd')
			),
			array(
				'id' => 'thumb_image_crop',
				'type' => 'button_set',
				'title' => __('Crop thumbnails', 'dfd'),
				'desc' => __('Post thumbnails image crop', 'dfd'),
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '1'
			),
			array(
				'id' => 'post_thumbnails_width',
				'type' => 'text',
				'title' => __('Post thumbnail width (in px)', 'dfd'),
				'validate' => 'numeric',
				'std' => '900'
			),
			array(
				'id' => 'post_thumbnails_height',
				'type' => 'text',
				'title' => __('Post  thumbnail height (in px)', 'dfd'),
				'validate' => 'numeric',
				'std' => '400',
			),
			array(
				'id' => 'post_header',
				'type' => 'button_set',
				'title' => __('Post info', 'dfd'),
				'desc' => __('It is information about the post (time and date of creation, author, comments on the post).', 'dfd'),
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '1'//this should be the key as defined above
			),
		),
	);

	$sections[] = array(
		'title' => __('Portfolio Options', 'dfd'),
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it blank for default.
		'icon' => 'camera',
		//Lets leave this as a blank section, no options just some intro text set above.
		'fields' => array(
			array(
				'id' => 'portfolio_page_select',
				'type' => 'pages_select',
				'title' => __('Portfolio page', 'dfd'),
				'desc' => __('Please select main portfolio page (for proper urls)', 'dfd'),
				'args' => array()//uses get_pages
			),
			array(
				'id' => 'folio_sorting',
				'type' => 'button_set', //the field type
				'title' => __('Panel for items sorting ', 'dfd'),
				'sub_desc' => __('Display panel for portfolio isotope items sorting by category', 'dfd'),
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '1'//this should be the key as defined above
			),
			array(
				'id' => 'portfolio_single_style',
				'type' => 'button_set', //the field type
				'title' => __('Portfolio text location', 'dfd'),
				'sub_desc' => __('Select text layout on inner page', 'dfd'),
				'options' => array(
					'left' => 'To the right',
					'full' => 'Full width',
				),
				'std' => 'left',
			),
			array(
				'id' => 'portfolio_single_slider',
				'type' => 'button_set', //the field type
				'title' => __('Portfolio image display', 'dfd'),
				'sub_desc' => __('Display attached images of inner portfolio page as:', 'dfd'),
				'options' => array(
					'slider' => 'Slider',
					'full' => 'Items',
				),
				'std' => 'slider',
			),
			array(
				'id' => 'recent_items_disp',
				'type' => 'button_set', //the field type
				'title' => __('Display block under single item', 'dfd'),
				'sub_desc' => __('Block with recent items', 'dfd'),
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '1'//this should be the key as defined above
			),
			array(
				'id' => 'block_single_folio_item',
				'type' => 'textarea',
				'title' => __('Block shortcode', 'dfd'),
				'desc' => '',
				'sub_desc' => __('By default here is displayed Block with recent items [mvb_recent_works  main_title="Recent projects"][/mvb_recent_works]', 'dfd'),
				'std' => '[mvb_recent_works  main_title="Recent projects"][/mvb_recent_works]'
			),
		),
	);

	$sections[] = array(
		'title' => __('Styling Options', 'dfd'),
		'desc' => __('<p class="description">Style parameters of body and footer</p>', 'dfd'),
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it blank for default.
		'icon' => 'cogs',
		//Lets leave this as a blank section, no options just some intro text set above.
		'fields' => array(
			array(
				'id' => 'info_msc',
				'type' => 'info',
				'desc' => __('<h3 class="description">Main site colors setup</h3>', 'dfd')
			),
			array(
				'id' => 'main_site_color',
				'type' => 'color',
				'title' => __('Main site color', 'dfd'),
				'desc' => __('Color of buttons, tabs, links, etc.  For example: #ff5f60', 'dfd'),
				'std' => '',
			),
			array(
				'id' => 'secondary_site_color',
				'type' => 'color',
				'title' => __('Second site color', 'dfd'),
				'desc' => __('Color of inactive elements or borders.  For example: #e5e5e5', 'dfd'),
				'std' => '',
			),
			array(
				'id' => 'third_site_color',
				'type' => 'color',
				'title' => __('Third site color', 'dfd'),
				'desc' => __('Color of inactive elements or borders.  For example: #e5e5e5', 'dfd'),
				'std' => '',
			),
			array(
				'id' => 'font_site_color',
				'type' => 'color',
				'title' => __('Color of text', 'dfd'),
				'desc' => __('Main text color. For example: #545454', 'dfd'),
				'std' => '',
			),
			array(
				'id' => 'link_site_color',
				'type' => 'color',
				'title' => __('Color of link', 'dfd'),
				'desc' => __('Main link color. For example: #545454', 'dfd'),
				'std' => '',
			),
			array(
				'id' => 'header_background_color',
				'type' => 'color',
				'title' => __('Header default background color', 'dfd'),
				'std' => '',
			),
			array(
				'id' => 'fixed_header_background_color',
				'type' => 'color',
				'title' => __('Fixed Header background color', 'dfd'),
				'std' => '',
			),
			array(
				'id' => 'fixed_header_background_opacity',
				'type' => 'text',
				'title' => __('Fixed Header background opacity ', 'dfd'),
				'std' => '',
				'desc' => __('Insert value from 0% to 100%. Default: <b>95%</b>', 'dfd'),
			),
			array(
				'id' => 'news_page_slider_background_hover',
				'type' => 'color',
				'title' => __('News Page Slider background on hover', 'dfd'),
				'std' => '',
			),
			array(
				'id' => 'news_page_slider_opacity_hover',
				'type' => 'text',
				'title' => __('News Page Slider opacity on hover ', 'dfd'),
				'std' => '',
				'desc' => __('Insert value from 0% to 100%. Default: <b>95%</b>', 'dfd'),
			),
			
			array(
				'id' => 'read_more_color',
				'type' => 'color',
				'title' => __('Read More text color', 'dfd'),
				'std' => '',
			),
			array(
				'id' => 'button_bg_color',
				'type' => 'color',
				'title' => __('Default button color', 'dfd'),
				'std' => '',
			),
			
			array(
				'id' => 'info_sth',
				'type' => 'info',
				'desc' => __('<h3 class="description">Page title background options</h3>', 'dfd')
			),
			array(
				'id' => 'stan_header',
				'type' => 'button_set',
				'title' => __('Page title background', 'dfd'),
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '1',// 1 = on | 0 = off
			),
			array(
				'id' => 'stan_header_color',
				'type' => 'color',
				'title' => __('Default background color for header', 'dfd'),
				'std' => '#ff7362',
			),
			array(
				'id' => 'stan_header_image',
				'type' => 'upload',
				'title' => __('Default background image for header', 'dfd'),
				'desc' => __('Upload your own background image or pattern.', 'dfd'),
				'std' => $assets_folder . 'img/page-header-default.gif',
			),
			array(
				'id' => 'stan_header_fixed',
				'type' => 'button_set',
				'title' => __('Fix image position', 'dfd'),
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '1',// 1 = on | 0 = off
			),
			array(
				'id' => 'info_sth',
				'type' => 'info',
				'desc' => __('<h3 class="description">Body styling options</h3>', 'dfd'),
			),
			array(
				'id' => 'site_boxed',
				'type' => 'button_set',
				'title' => __('Body layout', 'dfd'),
				'options' => array('0' => 'Full width', '1' => 'Boxed'),
				'std' => '0',
			),
			array(
				'id' => 'header_layout',
				'type' => 'button_set',
				'title' => __('Header Layout', 'dfd'),
				'options' => dfd_header_layouts(),
				'std' => 'boxed',
			),
			array(
				'id' => 'info_bxd',
				'type' => 'info',
				'desc' => __('<h4 class="description">body site options</h4>', 'dfd'),
			),
			//Body wrapper
			array(
				'id' => 'wrapper_bg_color',
				'type' => 'color',
				'title' => __('Content background color', 'dfd'),
				'desc' => __('Select background color.', 'dfd'),
				'std' => ''
			),
			array(
				'id' => 'wrapper_bg_image',
				'type' => 'upload',
				'title' => __('Content background image', 'dfd'),
				'desc' => __('Upload your own background image or pattern.', 'dfd')
			),
			array(
				'id' => 'wrapper_custom_repeat',
				'type' => 'select',
				'title' => __('Content bg image repeat', 'dfd'),
				'desc' => __('Select type background image repeat', 'dfd'),
				'options' => array('repeat-y' => 'vertically', 'repeat-x' => 'horizontally', 'no-repeat' => 'no-repeat', 'repeat' => 'both vertically and horizontally',), //Must provide key => value pairs for select options
				'std' => 'repeat'
			),
			array(
				'id' => 'info_bxd',
				'type' => 'info',
				'desc' => __('<h4 class="description">Boxed site options</h4>', 'dfd')
			),
			array(
				'id' => 'body_bg_color',
				'type' => 'color',
				'title' => __('Body background color', 'dfd'),
				'desc' => __('Select background color.', 'dfd'),
				'std' => ''
			),
			array(
				'id' => 'body_bg_image',
				'type' => 'upload',
				'title' => __('Custom background image', 'dfd'),
				'desc' => __('Upload your own background image or pattern.', 'dfd')
			),
			array(
				'id' => 'body_custom_repeat',
				'type' => 'select',
				'title' => __('Background image repeat', 'dfd'),
				'desc' => __('Select type background image repeat', 'dfd'),
				'options' => array('repeat-y' => 'vertically', 'repeat-x' => 'horizontally', 'no-repeat' => 'no-repeat', 'repeat' => 'both vertically and horizontally',), //Must provide key => value pairs for select options
				'std' => ''
			),
			array(
				'id' => 'body_bg_fixed',
				'type' => 'button_set',
				'title' => __('Fixed body background', 'dfd'),
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '0'// 1 = on | 0 = off
			),
			
			// Footer section
			array(
				'id' => 'info_foot',
				'type' => 'info',
				'desc' => __('<h3 class="description">Footer section options</h3>', 'dfd')
			),
			array(
				'id' => 'footer_bg_color',
				'type' => 'color',
				'title' => __('Footer background color', 'dfd'),
				'desc' => __('Select footer background color. ', 'dfd'),
				'std' => ''
			),
			array(
				'id' => 'footer_font_color',
				'type' => 'color',
				'title' => __('Footer font color', 'dfd'),
				'desc' => __('Select footer font color.', 'dfd'),
				'std' => ''
			),
			array(
				'id' => 'footer_bg_image',
				'type' => 'upload',
				'title' => __('Custom footer background image', 'dfd'),
				'desc' => __('Upload your own footer background image or pattern.', 'dfd')
			),
			array(
				'id' => 'footer_custom_repeat',
				'type' => 'select',
				'title' => __('Footer background image repeat', 'dfd'),
				'desc' => __('Select type background image repeat', 'dfd'),
				'options' => array('repeat-y' => 'vertically', 'repeat-x' => 'horizontally', 'no-repeat' => 'no-repeat', 'repeat' => 'both vertically and horizontally',), //Must provide key => value pairs for select options
				'std' => ''
			),
			
			// SubFooter section
			array(
                'id' => 'info_sub_foot',
                'type' => 'info',
                'desc' => __('<h3 class="description">Sub footer section options</h3>', 'dfd')
            ),

            array(
                'id' => 'sub_footer_bg_color',
                'type' => 'color',
                'title' => __('Sub footer background color', 'dfd'),
                'desc' => __('Select sub footer background color. ', 'dfd'),
                'std' => '#31343b',
            ),
            array(
                'id' => 'sub_footer_font_color',
                'type' => 'color',
                'title' => __('Sub footer font color', 'dfd'),
                
                'desc' => __('Select sub footer font color.', 'dfd'),
                'std' => ''
            ),
            array(
                'id' => 'sub_footer_bg_image',
                'type' => 'upload',
                'title' => __('Custom sub footer background image', 'dfd'),
                
                'desc' => __('Upload your own footer background image or pattern.', 'dfd')
            ),
            array(
                'id' => 'sub_footer_custom_repeat',
                'type' => 'select',
                'title' => __('Sub footer background image repeat', 'dfd'),
                
                'desc' => __('Select type background image repeat', 'dfd'),
                'options' => array('repeat' => 'both vertically and horizontally', 'repeat-y' => 'vertically','repeat-x' => 'horizontally','no-repeat' => 'no-repeat', ),//Must provide key => value pairs for select options
                'std' => 'repeat'
            ),
		),
	);

	$sections[] = array(
		'title' => __('Contact page options', 'dfd'),
		'desc' => __('<p class="description">Contact page options</p>', 'dfd'),
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it blank for default.
		'icon' => 'map-marker',
		//Lets leave this as a blank section, no options just some intro text set above.
		'fields' => array(
			array(
				'id' => 'custom_form_shortcode',
				'type' => 'text',
				'title' => __('Custom Form Shortcode', 'dfd'),
				'desc' => __('You can paste your shorcode custom form', 'dfd'),
				'std' => ''
			),
			array(
				'id' => 'cont_m_disp',
				'type' => 'button_set',
				'title' => __('Display map on contacts page?', 'dfd'),
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '1'// 1 = on | 0 = off
			),
			array(
				'id' => 'cont_m_height',
				'type' => 'text',
				'title' => __('Height of Google Map (in px)', 'dfd'),
				'std' => ''
			),
			array(
				'id' => 'cont_m_zoom',
				'type' => 'text',
				'title' => __('Zoom Level', 'dfd'),
				'std' => '14'
			),
			array(
				'id' => 'map_address',
				'type' => 'multi_text',
				'title' => __('Address on Google Map ', 'dfd'),
				'desc' => __('Fill in your address to be shown on Google map.', 'dfd'),
				'std' => 'London, Downing street, 10'
			),
		),
	);

	$sections[] = array(
		'icon' => 'wrench',
		'title' => __('Layouts Settings', 'dfd'),
		'desc' => __('<p class="description">Configure layouts of different pages</p>', 'dfd'),
		'fields' => array(
			array(
				'id' => 'pages_layout',
				'type' => 'radio_img',
				'title' => __('Single pages layout', 'dfd'),
				'sub_desc' => __('Select one type of layout for single pages', 'dfd'),
				'options' => dfd_page_layouts(),
				'std' => '1col-fixed'
			),
			array(
				'id' => 'pages_head_type',
				'type' => 'select',
				'title' => __('Single pages header', 'dfd'),
				'options' => dfd_headers_type(),
				'std' => '1',
			),
			array(
				'id' => 'archive_layout',
				'type' => 'radio_img',
				'title' => __('Archive Pages Layout', 'dfd'),
				'sub_desc' => __('Select one type of layout for archive pages', 'dfd'),
				'options' => dfd_page_layouts(),
				'std' => '2c-l-fixed'
			),
			array(
				'id' => 'archive_head_type',
				'type' => 'select',
				'title' => __('Archive Pages header', 'dfd'),
				'options' => dfd_headers_type(),
				'std' => '1',
			),
			array(
				'id' => 'single_layout',
				'type' => 'radio_img',
				'title' => __('Single posts layout', 'dfd'),
				'sub_desc' => __('Select one type of layout for single posts', 'dfd'),
				'options' => dfd_page_layouts(),
				'std' => '2c-l-fixed'
			),
			array(
				'id' => 'single_head_type',
				'type' => 'select',
				'title' => __('Single posts header', 'dfd'),
				'options' => dfd_headers_type(),
				'std' => '1',
			),
			array(
				'id' => 'search_layout',
				'type' => 'radio_img',
				'title' => __('Search results layout', 'dfd'),
				'sub_desc' => __('Select one type of layout for search results', 'dfd'),
				'options' => dfd_page_layouts(),
				'std' => '2c-l-fixed'
			),
			array(
				'id' => 'search_head_type',
				'type' => 'select',
				'title' => __('Search results header', 'dfd'),
				'options' => dfd_headers_type(),
				'std' => '1',
			),
			array(
				'id' => '404_layout',
				'type' => 'radio_img',
				'title' => __('404 Page Layout', 'dfd'),
				'sub_desc' => __('Select one of layouts for 404 page', 'dfd'),
				'options' => dfd_page_layouts(),
				'std' => '2c-l-fixed'
			),
			array(
				'id' => '404_head_type',
				'type' => 'select',
				'title' => __('404 Page header', 'dfd'),
				'options' => dfd_headers_type(),
				'std' => '1',
			),
		),
	);

	$sections[] = array(
		'title' => __('Twitter panel options', 'dfd'),
		'desc' => __('<p class="description">More information about api keys and how to get it you can find in that tutorial <a href="http://dfd.name/twitter-settings">http://dfd.name/twitter-settings/</a></p>', 'dfd'),
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it blank for default.
		'icon' => 'twitter',
		//Lets leave this as a blank section, no options just some intro text set above.
		'fields' => array(
			array(
				'id' => 't_panel_padding',
				'type' => 'button_set',
				'title' => __('Section padding', 'dfd'),
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '0'// 1 = on | 0 = off
			),
			array(
				'id' => 't_panel_bg_color',
				'type' => 'color',
				'title' => __('Background color for twitter panel', 'dfd'),
				'std' => '#20bce3'
			),
			array(
				'id' => 't_panel_bg_image',
				'type' => 'upload',
				'title' => __('Background image for twitter panel', 'dfd'),
				'desc' => __('Upload your own background image or pattern.', 'dfd'),
				'std' => $assets_folder . 'pic/twitter-row-bg.jpg'
			),
			array(
				'id' => 'footer_tw_disp',
				'type' => 'button_set',
				'title' => __('Display twitter statuses before footer', 'dfd'),
				'options' => array('1' => __('On', 'dfd'), '0' => __('Off', 'dfd')),
				'std' => '0'// 1 = on | 0 = off
			),
			array(
				'id' => 'cachetime',
				'type' => 'text',
				'title' => __('Cache Tweets in every:', 'dfd'),
				'sub_desc' => __('In minutes', 'dfd'),
				'std' => '1'
			),
			array(
				'id' => 'numb_lat_tw',
				'type' => 'text',
				'title' => __('Number of latest tweets display:', 'dfd'),
				'std' => '10'
			),
			array(
				'id' => 'username',
				'type' => 'text',
				'title' => __('Username:', 'dfd'),
				'std' => 'Envato'
			),
			array(
				'id' => 'twiiter_consumer',
				'type' => 'text',
				'title' => __('Consumer key:', 'dfd'),
				'std' => '',
			),
			array(
				'id' => 'twiiter_con_s',
				'type' => 'text',
				'title' => __('Consumer secret:', 'dfd'),
				'std' => '',
			),
			array(
				'id' => 'twiiter_acc_t',
				'type' => 'text',
				'title' => __('Access token:', 'dfd'),
				'std' => '',
			),
			array(
				'id' => 'twiiter_acc_t_s',
				'type' => 'text',
				'title' => __('Access token secret:', 'dfd'),
				'std' => '',
			),
		),
	);

	$sections[] = array(
		'title' => __('Footer section options', 'dfd'),
		'desc' => __('<p class="description">Footer section options</p>', 'dfd'),
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it blank for default.
		'icon' => 'tasks',
		//Lets leave this as a blank section, no options just some intro text set above.
		'fields' => array(
			array(
				'id' => 'logo_footer',
				'type' => 'upload',
				'title' => __('Logotype in footer', 'dfd'),
				'desc' => __('Will be displayed before copyright text', 'dfd'),
				'std' => '', // $assets_folder
			),
			array(
				'id' => 'copyright_footer',
				'type' => 'text',
				'title' => __('Show copyright', 'dfd'),
				'desc' => __('Fill in the copyright text.', 'dfd'),
				'validate' => 'html',
				'std' => '&copy; 2014 <a href="http://dev.dfd.name/">Dynamic Frameworks Development</a>'
			),
			array(
				'id' => 'social_counters_code',
				'type' => 'textarea',
				'title' => __('Social Counters code', 'dfd'),
				'desc' => __('This code will be displayed before &lt;/body&gt;', 'dfd'),
				'std' => '',
			),
		),
	);

	$envato_toolkit_has_update = get_option('envato-toolkit-has-update');
	$envato_toolkit_last_errors = get_option('envato-toolkit-last-errors');

	$sections[] = array(
		'title' => __('Theme Update', 'dfd'),
//        'desc' => __('<p class="description"></p>', 'dfd'),
		'icon' => 'cogs',
		'fields' => array(
			array(
				'id' => 'themeforest_username',
				'type' => 'text',
				'title' => __('ThemeForest Username', 'dfd'),
				'std' => ''
			),
			array(
				'id' => 'themeforest_api_key',
				'type' => 'text',
				'title' => __('Secret API Key', 'dfd'),
				'std' => ''
			),
			array(
				'id' => 'dfd_upgrde_theme',
				'type' => 'raw_html',
				'html' => (!$get_sections && !empty($envato_toolkit_has_update) && empty($envato_toolkit_last_errors)) ? '<p style="text-align: center;">'
						. '<a onclick="return confirm(\'Start theme upgrade?\')" href="' . add_query_arg(array(
							'_nonce' => wp_create_nonce('sb_theme_upgrade'),
							'upgrade_theme' => 1,
						)) . '" class="button button-primary">' . __('Upgrade theme', 'dfd') . '</a></p>' : '',
			),
		),
	);
	
	if ($get_sections === true) {
		return $sections;
	}

	$tabs = array();

	if (function_exists('wp_get_theme')) {
		$theme_data = wp_get_theme();
		$item_uri = $theme_data->get('ThemeURI');
		$description = $theme_data->get('Description');
		$author = $theme_data->get('Author');
		$author_uri = $theme_data->get('AuthorURI');
		$version = $theme_data->get('Version');
		$tags = $theme_data->get('Tags');
	} else {
		$theme_data = get_theme_data(trailingslashit(get_stylesheet_directory()) . 'style.css');
		$item_uri = $theme_data['URI'];
		$description = $theme_data['Description'];
		$author = $theme_data['Author'];
		$author_uri = $theme_data['AuthorURI'];
		$version = $theme_data['Version'];
		$tags = $theme_data['Tags'];
	}

	$item_info = '<div class="redux-opts-section-desc">';
	$item_info .= '<p class="redux-opts-item-data description item-uri">' . __('<strong>Theme URL:</strong> ', 'dfd') . '<a href="' . $item_uri . '" target="_blank">' . $item_uri . '</a></p>';
	$item_info .= '<p class="redux-opts-item-data description item-author">' . __('<strong>Author:</strong> ', 'dfd') . ($author_uri ? '<a href="' . $author_uri . '" target="_blank">' . $author . '</a>' : $author) . '</p>';
	$item_info .= '<p class="redux-opts-item-data description item-version">' . __('<strong>Version:</strong> ', 'dfd') . $version . '</p>';
	$item_info .= '<p class="redux-opts-item-data description item-description">' . $description . '</p>';
	$item_info .= '<p class="redux-opts-item-data description item-tags">' . __('<strong>Tags:</strong> ', 'dfd') . implode(', ', $tags) . '</p>';
	$item_info .= '</div>';

	$tabs['item_info'] = array(
		'icon' => 'info-sign',
		'icon_class' => 'icon-large',
		'title' => __('Theme Information', 'dfd'),
		'content' => $item_info
	);

	if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
		$tabs['docs'] = array(
			'icon' => 'book',
			'icon_class' => 'icon-large',
			'title' => __('Documentation', 'dfd'),
			'content' => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
		);
	}

	global $Redux_Options;
	$Redux_Options = new Redux_Options($sections, $args, $tabs);
}

add_action('init', 'setup_framework_options', 0, 0);

/*
 * 
 * Custom function for the callback referenced above
 *
 */

function my_custom_field($field, $value) {
	print_r($field);
	print_r($value);
}

/*
 * 
 * Custom function for the callback validation referenced above
 *
 */

function validate_callback_function($field, $value, $existing_value) {
	$error = false;
	$value = 'just testing';
	/*
	  do your validation

	  if(something) {
	  $value = $value;
	  } elseif(somthing else) {
	  $error = true;
	  $value = $existing_value;
	  $field['msg'] = 'your custom error message';
	  }
	 */

	$return['value'] = $value;
	if ($error == true) {
		$return['error'] = $field;
	}
	return $return;
}
