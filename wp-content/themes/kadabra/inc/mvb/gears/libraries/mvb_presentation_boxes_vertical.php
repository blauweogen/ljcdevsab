<?php

class MVB_Presentation_Boxes_Vertical
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
            'title'           =>      __('Presentation Vertical', 'dfd'),
            'description'     =>      __('Add a presentation box', 'dfd'),
            'identifier'      =>      __CLASS__,
            'icon'            =>      'appbar.interface.list.png',
            'class'            =>      'fa fa-list-ul',
            'section'         =>      'presentation',
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

            'separator1' => array('type'     =>  'separator'),

            'presentation_box' => array(
                'type'      =>      'repeater',
                'button'    =>      __('Add box', 'dfd'),
                'label'     =>      __('Box', 'dfd'),
                'lbl_d'     =>      __('Box Title', 'dfd'),
                'fields'    =>      array(

                    'image' => array(
                        'type'      =>      'image',
                        'label'     =>      __('Image', 'dfd'),
                        'col_span'  =>      'lbl_half'
                    ),

                    'main_title' => array(
                        'type'      =>      'text',
                        's_title'   =>      TRUE,
                        'label'     =>      __('Title', 'dfd'),
                        'col_span'  =>      'lbl_half'
                    ),
                    'sub_title' => array(
                        'type'      =>      'text',
                        'label'     =>      __('Subtitle', 'dfd'),
                        'col_span'  =>      'lbl_half'
                    ),

                    'separator1' => array('type'     =>  'separator'),


                    'link_url' => array(
                        'type'      =>      'text',
                        'label'     =>      __('Link (URL)', 'dfd'),
                        'col_span'  =>      'lbl_half'
                    ),
                    'page_id' => array(
                        'type'      =>      'mvb_dropdown',
                        'label'     =>      __('Link to page', 'dfd'),
                        'what'      =>      'pages',
                        'default'   =>      0,
                        'col_span'  =>      'lbl_half',
                    ),

                    'separator' => array('type'     =>  'separator'),
					
					'link_title' => array(
                        'type'      =>      'text',
                        'label'     =>      __('Link Title', 'dfd'),
                        'col_span'  =>      'lbl_half'
                    ),
					
					'link_in_main_title' => array(
                        'type'      =>      'select',
                        'label'     =>      __('Link In Main Title', 'dfd'),
						'default'	=>		0,
						'options'	=>		array(0=>__('No', 'dfd'), 1=>__('Yes', 'dfd')),
                        'col_span'  =>      'lbl_half',
                    ),
					
					'separator' => array('type'     =>  'separator'),

                    'icon' => array(
                        'type'      =>      'icon',
                        'label'     =>      __('Icon', 'dfd'),
                    ),

                    'separator01' => array('type'     =>  'separator'),

//                    'icon_color' => array(
//                        'type'      =>      'colorpicker',
//                        'label'     =>      __('Icon Color', 'dfd'),
//                        'default'   =>      '61727b',
//                        'col_span'  =>      'lbl_third',
//                    ),

//                    'icon_hover_color' => array(
//                        'type'      =>      'colorpicker',
//                        'label'     =>      __('Icon Hover Color', 'dfd'),
//                        'default'   =>      'FF6565',
//                        'col_span'  =>      'lbl_third',
//                    ),

                    'icon_size' => array(
                        'type'      =>      'text',
                        'label'     =>      __('Icon size (in px)', 'dfd'),
                        'default'   =>      '120',
                        'col_span'  =>      'lbl_third',
                    ),


                    'content' => array(
                        'type'      =>      'textarea',
                        'label'     =>      __('Content', 'dfd'),
                    ),

                    'css' => array(
                        'type'      =>      'text',
                        'label'     =>      __('Additional CSS classes', 'dfd'),
                        'help'      =>      __('Separated by space', 'dfd'),
                    ),

                )//end repeater_fields
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

	    'image_align' => array(
				'type' => 'select',
				'label' => __('Icon or image align', 'dfd'),
				'help'      =>      __('The image or cion will appear to the left or to the right from content', 'dfd'),
				'options' => crum_get_align(),
				'col_span' => 'lbl_half'
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
            $load['content'] = $mvb_metro_factory->do_repeater_shortcode($content);

            $load['form_fields_html'] = $mvb_metro_form_builder->build_form($form_fields, $load);
            $load['settings'] = self::settings();
            $load['form_fields'] = $form_fields;
            $load['module_action'] = $mvb_metro_factory->module_action;

            return $mvb_metro_factory->_load_view('html/private/mvb_form.php', $load);
        }//endif

    }//end admin_render();

    /**
	 * The private code for the repeater shortcode. used in the custom editor
	 *
	 * @access public
	 * @param array $atts
	 * @return shortcode output
	 */

    public static function repeater_admin_render( $atts = array(), $content = '' )
    {
        global $mvb_metro_factory;
        global $__metro_core;

        if( !isset($atts['sh_keys']) OR trim($atts['sh_keys']) == '' )
            return;

        $keys = explode(",", $atts['sh_keys']);
        $tmp = array();

        foreach( $keys as $key )
        {
            if( $key != 'content' )
                $tmp[$key] = (isset($atts[$key])) ? $atts[$key] : '';
        }//endforeach;

        if( in_array('content', $keys) )
            $tmp['content'] = $content;

        $__metro_core->sh_tmp_repeater[] = $tmp;
    }//end repeater_admin_render();

    public static function repeater_render( $atts = array(), $content = '' )
    {
        global $mvb_metro_factory;
        self::repeater_admin_render($atts, $content);
    }//end repeater_render()

    /**
	 * The public code for the shortcode
	 *
	 * @access public
	 * @param array $atts
	 * @return shortcode output
	 */
    public static function render( $atts, $content = null )
    {
        global $mvb_metro_factory;

        $load = $atts;
        $load['r_items'] = $mvb_metro_factory->do_repeater_shortcode($content);

        return $mvb_metro_factory->_load_view('html/public/mvb_presentation_boxes_vertical.php', $load);
    }//end render();
}//end class
