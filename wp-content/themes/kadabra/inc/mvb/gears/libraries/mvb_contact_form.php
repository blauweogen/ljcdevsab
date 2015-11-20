<?php

class MVB_Contact_Form
{
    /**
	 * The modules settings
	 *
	 * @access public
	 * @param none
	 * @return array settings
	 */

    public static function settings()
    {
        return array(
            'title'           =>      __('The contact form module', 'dfd'),
            'description'     =>      __('Adds the contact form', 'dfd'),
            'identifier'      =>      __CLASS__,
            'icon'            =>      'appbar.page.text.png',
            'class'            =>      'fa fa-file-text-o',
            'section'         =>      'content',
            'color'           =>      'gray'
        );
    }//end settings()

    /**
	 * The shortcodes attributes with the field options
	 *
	 * @access private
	 * @param array $atts
	 * @return shortcode output
	 */

    public static function fields()
    {
        global $mvb_metro_factory;

        $the_fields = array(
            'main_title' => array(
                'type'      =>      'text',
				's_title'   =>      TRUE,
                'label'     =>      __('Title', 'dfd'),
            ),
			'sub_title' => array(
                'type'      =>      'text',
                'label'     =>      __('Sub Title', 'dfd'),
            ),
			'contacts_form_mail' => array(
                'type'      =>      'text',
                'label'     =>      __('Сontacts form mail', 'dfd'),
            ),
			'show_captcha' => array(
				'type'		=>		'select',
				'label'		=>		__('Captcha', 'dfd'),
				'default'	=>		'0',
				'options'	=>		mvb_yes_no(),
				'col_span'	=>		'lbl_half'
			),
			'submit_button_align' => array(
				'type'		=>		'select',
				'label'		=>		__('Submit Button Align', 'dfd'),
				'default'	=>		0,
				'options'	=>		crum_get_align_ext(),
				'col_span'	=>		'lbl_half'
			),
	        'size' => array(
		        'type'      =>      'select',
		        'label'     =>      __('Show half-size form', 'dfd'),
		        'default'   =>      0,
			'options' => mvb_yes_no(),
		        'col_span'  =>      'lbl_half'
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
        );
		
		$the_fields = apply_filters('mvb_fields_filter', $the_fields);

        return $the_fields;
    }//end fields();


    /**
	 * The private code for the shortcode. used in the custom editor
	 *
	 * @access public
	 * @param array $atts
	 * @return shortcode output
	 */

    public static function admin_render( $atts = array(), $content = '' )
    {
        global $mvb_metro_factory;
        global $mvb_metro_form_builder;
        $form_fields = self::fields();

        $load = shortcode_atts( $mvb_metro_factory->defaults($form_fields), $atts );
        $load['content'] = $content;

        if( $mvb_metro_factory->show_pill_sc OR $mvb_metro_factory->show_pill_sc_column )
        {
            if( method_exists(__CLASS__, 'the_pill') )
            {
                return self::the_pill($load, self::settings());
            }
            else
            {
                return $mvb_metro_factory->the_pill($load, self::settings());
            }

        }
        else
        {
            $load['form_fields_html'] = $mvb_metro_form_builder->build_form($form_fields, $load);
            $load['settings'] = self::settings();
            $load['form_fields'] = $form_fields;
            $load['module_action'] = $mvb_metro_factory->module_action;
            $load['content'] = $content;

            return $mvb_metro_factory->_load_view('html/private/mvb_form.php', $load);
        }//endif

    }//end admin_render();

    /**
	 * The public code for the shortcode
	 *
	 * @access public
	 * @param array $atts
	 * @return shortcode output
	 */
    public static function render( $atts )
    {
        global $mvb_metro_factory;

        $load = $atts;

        return $mvb_metro_factory->_load_view('html/public/mvb_contact_form.php', $load);
    }//end render();
}//end class