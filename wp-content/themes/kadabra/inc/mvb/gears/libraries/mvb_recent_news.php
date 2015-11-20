<?php

class MVB_recent_news
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
            'title'           =>      __('Recent news', 'dfd'),
            'description'     =>      __('Add recent news list', 'dfd'),
            'identifier'      =>      __CLASS__,
            'icon'            =>      'appbar.newspaper.png',
			'class'            =>      'fa fa-list-alt',
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
                'label'     =>      __('Title', 'dfd'),
            ),

            'categories' => array(
                'type'      =>      'select_multi',
                'title'     =>      __('Select category', 'dfd'),
                'label'     =>      __('Categories', 'dfd'),
                'callback'  =>      'mvb_get_select_options_multi',
                'options'   =>      'category',
            ),
			
			'separator' => array('type'     =>  'separator'),
			
			'description' => array(
                'type'      =>      'textarea',
                'label'     =>      __('Description', 'dfd'),
            ),
			
			'separator1' => array('type'     =>  'separator'),
			
			'link_title' => array(
				'type'		=>		'text',
				'label'		=>		__('Link title', 'dfd'),
				'col_span'  =>      'lbl_half',
			),
			
			'show_link' => array(
				'type'		=>		'select',
				'label'		=>		__('Show link', 'dfd'),
				'options'	=>		array(0 => __('No', 'dfd'), 1 => __('Yes', 'dfd')),
				'default'	=>		0,
				'col_span'  =>      'lbl_half',
			),

            'separator-effects' => array('type'     =>  'separator'),

            'effects' => array(
                'type'      =>      'select',
                'label'     =>      __('Appear effects', 'dfd'),
                'help'      =>      __('Select one of appear effects for block', 'dfd'),
                'default'   =>      '0',
                'options'   =>      crum_appear_effects(),
                'col_span'  =>      'lbl_half'
            ),

	        'css' => array(
		        'type'      =>      'text',
		        'label'     =>      __('Additional CSS classes', 'dfd'),
		        'help'      =>      __('Separated by space', 'dfd'),
		        'col_span'  =>      'lbl_third'
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

        return $mvb_metro_factory->_load_view('html/public/mvb_recent_news.php', $load);
    }//end render();
}//end class