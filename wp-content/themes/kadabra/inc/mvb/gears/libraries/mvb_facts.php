<?php

class MVB_Facts {

	/**
	 * The modules settings
	 *
	 * @access public
	 * @param none
	 * @return array settings
	 */
	public static function settings() {
		return array(
			'title' => __('Facts', 'dfd'),
			'description' => __('Add facts', 'dfd'),
			'identifier' => __CLASS__,
			'icon' => 'appbar.interface.list.png',
			'class' => 'fa fa-list-ul',
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
			'sub_title' => array(
				'type' => 'text',
				'label' => __('Sub Title', 'dfd'),
			),
			'diagram_type' => array(
				'type' => 'select',
				'label' => __('Diagram Type', 'dfd'),
				'default' => 0,
				'options' => array(
					'none' => __('None', 'dfd'),
					'circle' => __('Circle', 'dfd'),
				),
			),
			
			'separator-animate' => array('type'     =>  'separator'),
					
			'animate_facts' => array(
				'type'		=> 'select',
				'label'		=> __('Animate facts', 'dfd'),
				'default'	=> 0,
				'options'	=> mvb_yes_no(),
			),
			
			'separator1' => array('type' => 'separator'),
			
			'fact' => array(
				'type' => 'repeater',
				'button' => __('Add fact', 'dfd'),
				'label' => __('Fact', 'dfd'),
				'lbl_d' => __('Fact Name', 'dfd'),
				'fields' => array(
					'icon' => array(
					    'type'      =>      'icon',
					    'label'     =>      __('Icon', 'dfd'),
					    'col_span'  =>      'lbl_third',
					),
					'number' => array(
						'type' => 'text',
						'label' => __('Number', 'dfd'),
						'col_span' => 'lbl_third'
					),
					'title' => array(
						'type' => 'text',
						's_title' => TRUE,
						'label' => __('Title', 'dfd'),
						'col_span' => 'lbl_third'
					),
					'subtitle' => array(
						'type' => 'text',
						'label' => __('Subtitle', 'dfd'),
						'col_span' => 'lbl_third'
					),
					
					'separator' => array('type' => 'separator'),
					
				)//end repeater_fields
			),
			'description' => array(
				'type' => 'textarea',
				'editor' => true,
				'label' => __('Description', 'dfd')
			),
			'css' => array(
				'type'      =>      'text',
				'label'     =>      __('Additional CSS classes', 'dfd'),
				'help'      =>      __('Separated by space', 'dfd'),
				'col_span'  =>      'lbl_half'
			),

			'css_styles' => array(
				'type'      =>      'text',
				'label'     =>      __('Additional CSS styles', 'dfd'),
				'help'      =>      __('Separated by <b>;</b>', 'dfd'),
				'col_span'  =>      'lbl_full'
			),
			'separator' => array('type' => 'separator'),
			'unique_id' => array(
				'type' => 'text',
				'default' => uniqid('mvbf_'),
				'label' => __('Unique ID', 'dfd'),
				'help' => __('Must be unique for every module on the page.', 'dfd'),
				'col_span' => 'lbl_half'
			),
		);
		
		$the_fields = apply_filters('mvb_fields_filter', $the_fields);

		return $the_fields;
	}

	//end fields();

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
			$load['content'] = $mvb_metro_factory->do_repeater_shortcode($content);

			$load['form_fields_html'] = $mvb_metro_form_builder->build_form($form_fields, $load);
			$load['settings'] = self::settings();
			$load['form_fields'] = $form_fields;
			$load['module_action'] = $mvb_metro_factory->module_action;

			return $mvb_metro_factory->_load_view('html/private/mvb_form.php', $load);
		}
		//endif
	}

	//end admin_render();

	/**
	 * The private code for the repeater shortcode. used in the custom editor
	 *
	 * @access public
	 * @param array $atts
	 * @return shortcode output
	 */
	public static function repeater_admin_render($atts = array(), $content = '') {
		global $__metro_core;

		if (!isset($atts['sh_keys']) OR trim($atts['sh_keys']) == '')
			return;

		$keys = explode(",", $atts['sh_keys']);
		$tmp = array();

		foreach ($keys as $key) {
			if ($key != 'content') {
				$tmp[$key] = (isset($atts[$key])) ? $atts[$key] : '';
			}
		}
		//endforeach;

		if (in_array('content', $keys))
			$tmp['content'] = $content;

		$__metro_core->sh_tmp_repeater[] = $tmp;
	}

	//end repeater_admin_render();

	public static function repeater_render($atts = array(), $content = '') {
		global $mvb_metro_factory;
		self::repeater_admin_render($atts, $content);
	}

	//end repeater_render()

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
		$load['r_items'] = $mvb_metro_factory->do_repeater_shortcode($content);

		$no_of_panels = count($load['r_items']);
		if ($no_of_panels == 0 OR $no_of_panels > 12) {
			return;
		}

		$load['column_width'] = floor(100 / $no_of_panels);

		if (count($load['r_items']) > 0)
			$_sh_js = array(
				'depends' => array('jquery'),
				'js' => array(
					'knob' => get_template_directory_uri() . '/assets/js/jquery.knob.js',
				)
			);
		$mvb_metro_factory->queue_scripts($_sh_js, __CLASS__);

		return $mvb_metro_factory->_load_view('html/public/mvb_facts.php', $load);
	}

	//end render();
}

//end class
