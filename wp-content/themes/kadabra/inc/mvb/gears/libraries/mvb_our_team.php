<?php

class MVB_Our_Team
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
            'title'           =>      __('Our team', 'dfd'),
            'description'     =>      __('Team members', 'dfd'),
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
                'button'    =>      __('Add team member', 'dfd'),
                'label'     =>      __('Member', 'dfd'),
                'lbl_d'     =>      __('Mamber name', 'dfd'),
                'fields'    =>      array(
                    'image' => array(
                        'type'      =>      'image',
                        'label'     =>      __('Image', 'dfd'),
                        'col_span'  =>      'lbl_half'
                    ),

                    'separator11' => array('type'     =>  'separator', 'border' => true),

                    'main_title' => array(
                        'type'      =>      'text',
                        's_title'   =>      TRUE,
                        'label'     =>      __('Member name', 'dfd'),
                        'col_span'  =>      'lbl_half'
                    ),
                    'sub_title' => array(
                        'type'      =>      'text',
                        'label'     =>      __('Member additional', 'dfd', 'dfd'),
                        'col_span'  =>      'lbl_half'
                    ),

                    'content' => array(
                        'type'      =>      'textarea',
                        'label'     =>      __('Content', 'dfd'),
                    ),
					
					'separator-link' => array('type'     =>  'separator'),

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
					
					'link_title' => array(
                        'type'      =>      'text',
                        'label'     =>      __('Link Title', 'dfd'),
						'default'	=>		'Read more',
                        'col_span'  =>      'lbl_half'
                    ),

                    'separator0' => array('type'     =>  'separator', 'border' => true),

                    'tw_link' => array(
                        'type'      =>      'text',
                        'label'     =>      'Twitter',
                        'col_span'  =>      'lbl_half'
                    ),'fb_link' => array(
                        'type'      =>      'text',
                        'label'     =>      'Facebook',
                        'col_span'  =>      'lbl_half'
                    ),'li_link' => array(
                        'type'      =>      'text',
                        'label'     =>      'LinkedIN',
                        'col_span'  =>      'lbl_half'
                    ),'gp_link' => array(
                        'type'      =>      'text',
                        'label'     =>      'Google +',
                        'col_span'  =>      'lbl_half'
                    ),'in_link' => array(
                        'type'      =>      'text',
                        'label'     =>      'Instagram',
                        'col_span'  =>      'lbl_half'
                    ),'vi_link' => array(
                        'type'      =>      'text',
                        'label'     =>      'Vimeo',
                        'col_span'  =>      'lbl_half'
                    ),'lf_link' => array(
                        'type'      =>      'text',
                        'label'     =>      'Last FM',
                        'col_span'  =>      'lbl_half'
                    ),'vk_link' => array(
                        'type'      =>      'text',
                        'label'     =>      'Vkontakte',
                        'col_span'  =>      'lbl_half'
                    ),'yt_link' => array(
                        'type'      =>      'text',
                        'label'     =>      'YouTube',
                        'col_span'  =>      'lbl_half'
                    ),'de_link' => array(
                        'type'      =>      'text',
                        'label'     =>      'Devianart',
                        'col_span'  =>      'lbl_half'
                    ),'pi_link' => array(
                        'type'      =>      'text',
                        'label'     =>      'Picasa',
                        'col_span'  =>      'lbl_half'
                    ),'pt_link' => array(
                        'type'      =>      'text',
                        'label'     =>      'Pinterest',
                        'col_span'  =>      'lbl_half'
                    ),'wp_link' => array(
                        'type'      =>      'text',
                        'label'     =>      'Wordpress',
                        'col_span'  =>      'lbl_half'
                    ),'db_link' => array(
                        'type'      =>      'text',
                        'label'     =>      'Dropbox',
                        'col_span'  =>      'lbl_half'
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
					
                )//end repeater_fields
            ),

            'separator' => array('type'     =>  'separator'),

            'css' => array(
                'type'      =>      'text',
                'label'     =>      __('Additional CSS classes', 'dfd'),
                'help'      =>      __('Separated by space', 'dfd')
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

        $no_of_panels = count($load['r_items']);
		if ($no_of_panels > 4) {
			$no_of_panels = 4;
		}

        $load['column_number'] = mvb_num_to_string(ceil(12/$no_of_panels));
        $load['sizes'] = mvb_foundation_columns(ceil($mvb_metro_factory->no_of_columns/$no_of_panels));

        return $mvb_metro_factory->_load_view('html/public/mvb_our_team.php', $load);
    }//end render();
}//end class