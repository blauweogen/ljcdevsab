<?php

class MVB_Gmaps {

	/**
	 * The modules settings
	 *
	 * @access public
	 * @param none
	 * @return array settings
	 */
	public static function settings() {
		return array(
			'title' => __('Google Maps', 'dfd'),
			'description' => __('Adds a Google map', 'dfd'),
			'identifier' => __CLASS__,
			'icon' => 'appbar.map.png',
			'class' => 'fa fa-map-marker',
			'section' => 'presentation',
			'color' => 'gray'
		);
	}

//end settings()

	/**
	 * The shortcodes attributes with the field options
	 *
	 * @access private
	 * @param array $atts
	 * @return shortcode output
	 */
	public static function fields() {
		global $mvb_metro_factory;

		$the_fields = array(
			'main_title' => array(
				'type' => 'text',
				'label' => __('Title', 'dfd'),
			),
			'address' => array(
				'type' => 'text',
				'label' => __('Address to display on map', 'dfd'),
			),
			'height' => array(
				'type' => 'text',
				'label' => __('Height (in px)', 'dfd'),
				'col_span' => 'lbl_half'
			),
			'zoom' => array(
				'type' => 'text',
				'label' => __('Map zoom (from 0 to 21+)', 'dfd'),
				'col_span' => 'lbl_half',
				'default' => '14',
			),
			'map_style' => array(
				'type' => 'select',
				'label' => __('Map Style', 'dfd'),
				'help' => __('Select one of map styles', 'dfd'),
				'default' => 'apple-maps-esque',
				'options' => array(
					'0' => __('Default', 'dfd'),
					'apple-maps-esque' => __('Apple Maps-esque', 'dfd'),
					'blue-water' => __('Blue water', 'dfd'),
					'midnight-commander' => __('Midnight Commander', 'dfd'),
					'pale-dawn' => __('Pale Dawn', 'dfd'),
					'retro' => __('Retro', 'dfd'),
					'subtle-grayscale' => __('Subtle Grayscale', 'dfd'),
				),
				'col_span' => 'lbl_third'
			),
			'content' => array(
				'type' => 'textarea',
				'editor' => TRUE,
				'label' => __('Additional information', 'dfd'),
			),
			'infowindow_content' => array(
				'type' => 'textarea',
				'editor' => true,
				'label' => __('Infowindow content', 'dfd'),
			),
			'separator-effects' => array('type' => 'separator'),
			'effects' => array(
				'type' => 'select',
				'label' => __('Appear effects', 'dfd'),
				'help' => __('Select one of appear effects for block', 'dfd'),
				'default' => 0,
				'options' => crum_appear_effects(),
				'col_span' => 'lbl_third'
			),
			'css' => array(
				'type' => 'text',
				'label' => __('Additional CSS classes', 'dfd'),
				'help' => __('Separated by space', 'dfd'),
				'col_span' => 'lbl_third'
			),
			'css_styles' => array(
				'type' => 'text',
				'label' => __('Additional CSS styles', 'dfd'),
				'help' => __('Separated by <b>;</b>', 'dfd'),
				'col_span' => 'lbl_full'
			),
			'unique_id' => array(
				'type' => 'text',
				'default' => uniqid('mvbtab_'),
				'label' => __('Unique ID', 'dfd'),
				'help' => __('Must be unique for every tab on the page.', 'dfd'),
				'col_span' => 'lbl_third'
			),
		);

		$the_fields = apply_filters('mvb_fields_filter', $the_fields);

		return $the_fields;
	}

	public static function map_config() {
		return array(
			'apple-maps-esque' => array(
				__('Apple Maps-esque', 'dfd'),
				"[{featureType:'water',elementType:'geometry',stylers:[{color:'#a2daf2'}]},{featureType:'landscape.man_made',elementType:'geometry',stylers:[{color:'#f7f1df'}]},{featureType:'landscape.natural',elementType:'geometry',stylers:[{color:'#d0e3b4'}]},{featureType:'landscape.natural.terrain',elementType:'geometry',stylers:[{visibility:'off'}]},{featureType:'poi.park',elementType:'geometry',stylers:[{color:'#bde6ab'}]},{featureType:'poi',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'poi.medical',elementType:'geometry',stylers:[{color:'#fbd3da'}]},{featureType:'poi.business',stylers:[{visibility:'off'}]},{featureType:'road',elementType:'geometry.stroke',stylers:[{visibility:'off'}]},{featureType:'road',elementType:'labels',stylers:[{visibility:'off'}]},{featureType:'road.highway',elementType:'geometry.fill',stylers:[{color:'#ffe15f'}]},{featureType:'road.highway',elementType:'geometry.stroke',stylers:[{color:'#efd151'}]},{featureType:'road.arterial',elementType:'geometry.fill',stylers:[{color:'#ffffff'}]},{featureType:'road.local',elementType:'geometry.fill',stylers:[{color:'black'}]},{featureType:'transit.station.airport',elementType:'geometry.fill',stylers:[{color:'#cfb2db'}]}]",
			),
			'blue-water' => array(
				__('Blue water', 'dfd'),
				"[	{		featureType:'water',		stylers:[{color:'#46bcec'},{visibility:'on'}]	},{		featureType:'landscape',		stylers:[{color:'#f2f2f2'}]	},{		featureType:'road',		stylers:[{saturation:-100},{lightness:45}]	},{		featureType:'road.highway',		stylers:[{visibility:'simplified'}]	},{		featureType:'road.arterial',		elementType:'labels.icon',		stylers:[{visibility:'off'}]	},{		featureType:'administrative',		elementType:'labels.text.fill',		stylers:[{color:'#444444'}]	},{		featureType:'transit',		stylers:[{visibility:'off'}]	},{		featureType:'poi',		stylers:[{visibility:'off'}]	}]",
			),
			'midnight-commander' => array(
				__('Midnight Commander', 'dfd'),
				"[{'featureType':'water','stylers':[{'color':'#021019'}]},{'featureType':'landscape','stylers':[{'color':'#08304b'}]},{'featureType':'poi','elementType':'geometry','stylers':[{'color':'#0c4152'},{'lightness':5}]},{'featureType':'road.highway','elementType':'geometry.fill','stylers':[{'color':'#000000'}]},{'featureType':'road.highway','elementType':'geometry.stroke','stylers':[{'color':'#0b434f'},{'lightness':25}]},{'featureType':'road.arterial','elementType':'geometry.fill','stylers':[{'color':'#000000'}]},{'featureType':'road.arterial','elementType':'geometry.stroke','stylers':[{'color':'#0b3d51'},{'lightness':16}]},{'featureType':'road.local','elementType':'geometry','stylers':[{'color':'#000000'}]},{'elementType':'labels.text.fill','stylers':[{'color':'#ffffff'}]},{'elementType':'labels.text.stroke','stylers':[{'color':'#000000'},{'lightness':13}]},{'featureType':'transit','stylers':[{'color':'#146474'}]},{'featureType':'administrative','elementType':'geometry.fill','stylers':[{'color':'#000000'}]},{'featureType':'administrative','elementType':'geometry.stroke','stylers':[{'color':'#144b53'},{'lightness':14},{'weight':1.4}]}]",
			),
			'pale-dawn' => array(
				__('Pale Dawn', 'dfd'),
				"[{'featureType':'water','stylers':[{'visibility':'on'},{'color':'#acbcc9'}]},{'featureType':'landscape','stylers':[{'color':'#f2e5d4'}]},{'featureType':'road.highway','elementType':'geometry','stylers':[{'color':'#c5c6c6'}]},{'featureType':'road.arterial','elementType':'geometry','stylers':[{'color':'#e4d7c6'}]},{'featureType':'road.local','elementType':'geometry','stylers':[{'color':'#fbfaf7'}]},{'featureType':'poi.park','elementType':'geometry','stylers':[{'color':'#c5dac6'}]},{'featureType':'administrative','stylers':[{'visibility':'on'},{'lightness':33}]},{'featureType':'road'},{'featureType':'poi.park','elementType':'labels','stylers':[{'visibility':'on'},{'lightness':20}]},{},{'featureType':'road','stylers':[{'lightness':20}]}]",
			),
			'retro' => array(
				__('Retro', 'dfd'),
				'[{featureType:"administrative",stylers:[{visibility:"off"}]},{featureType:"poi",stylers:[{visibility:"simplified"}]},{featureType:"road",elementType:"labels",stylers:[{visibility:"simplified"}]},{featureType:"water",stylers:[{visibility:"simplified"}]},{featureType:"transit",stylers:[{visibility:"simplified"}]},{featureType:"landscape",stylers:[{visibility:"simplified"}]},{featureType:"road.highway",stylers:[{visibility:"off"}]},{featureType:"road.local",stylers:[{visibility:"on"}]},{featureType:"road.highway",elementType:"geometry",stylers:[{visibility:"on"}]},{featureType:"water",stylers:[{color:"#84afa3"},{lightness:52}]},{stylers:[{saturation:-17},{gamma:0.36}]},{featureType:"transit.line",elementType:"geometry",stylers:[{color:"#3f518c"}]}]',
			),
			'subtle-grayscale' => array(
				__('Subtle Grayscale', 'dfd'),
				'[{featureType:"landscape",stylers:[{saturation:-100},{lightness:65},{visibility:"on"}]},{featureType:"poi",stylers:[{saturation:-100},{lightness:51},{visibility:"simplified"}]},{featureType:"road.highway",stylers:[{saturation:-100},{visibility:"simplified"}]},{featureType:"road.arterial",stylers:[{saturation:-100},{lightness:30},{visibility:"on"}]},{featureType:"road.local",stylers:[{saturation:-100},{lightness:40},{visibility:"on"}]},{featureType:"transit",stylers:[{saturation:-100},{visibility:"simplified"}]},{featureType:"administrative.province",stylers:[{visibility:"off"}]/**/},{featureType:"administrative.locality",stylers:[{visibility:"off"}]},{featureType:"administrative.neighborhood",stylers:[{visibility:"on"}]/**/},{featureType:"water",elementType:"labels",stylers:[{visibility:"on"},{lightness:-25},{saturation:-100}]},{featureType:"water",elementType:"geometry",stylers:[{hue:"#ffff00"},{lightness:-25},{saturation:-97}]}]',
			),
		);
	}
	
	public static function map_select_values() {
		$opts = self::map_config();
		
		$values = array(
			0 => __('Default', 'dfd'),
		);
		
		foreach($opts as $k=>$opt) {
			if (!isset($opt[0])) {
				continue;
			}
			
			$values[$k] = $opt[0];
		}
		
		return $values;
	}
	
	public static function getStyleVal($map_style) {
		$opts = self::map_config();
		
		if (empty($map_style)) return false;
		if (!isset($opts[$map_style])) return false;
		if (!isset($opts[$map_style][1])) return false;
		
		return $opts[$map_style][1];
	}

	/**
	 * The private code for the shortcode. used in the custom editor
	 *
	 * @access public
	 * @param array $atts
	 * @return shortcode output
	 */
	public static function admin_render($atts = array(), $content = '') {
		global $mvb_metro_factory;
		global $mvb_metro_form_builder;
		$form_fields = self::fields();

		$load = shortcode_atts($mvb_metro_factory->defaults($form_fields), $atts);
		$load['content'] = $content;


		if ($mvb_metro_factory->show_pill_sc OR $mvb_metro_factory->show_pill_sc_column) {
			if (method_exists(__CLASS__, 'the_pill')) {
				return self::the_pill($load, self::settings());
			} else {
				return $mvb_metro_factory->the_pill($load, self::settings());
			}
		} else {
			$load['form_fields_html'] = $mvb_metro_form_builder->build_form($form_fields, $load);
			$load['settings'] = self::settings();
			$load['form_fields'] = $form_fields;
			$load['module_action'] = $mvb_metro_factory->module_action;
			$load['content'] = $content;


			return $mvb_metro_factory->_load_view('html/private/mvb_form.php', $load);
		}//endif
	}

	/**
	 * The public code for the shortcode
	 *
	 * @access public
	 * @param array $atts
	 * @return shortcode output
	 */
	public static function render($atts, $content = null) {
		global $mvb_metro_factory;

		$load = $atts;
		$load['content'] = $content;


		$_sh_js = array(
			'depends' => array('jquery'),
			'js' => array(
				'gmap3' => get_template_directory_uri() . '/assets/js/gmap3.min.js'
			)
		);

		$mvb_metro_factory->queue_scripts($_sh_js, __CLASS__);


		return $mvb_metro_factory->_load_view('html/public/mvb_gmaps.php', $load);
	}

}
