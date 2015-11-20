<?php
/**
 * Timeline Horizontal
 */
class MVB_Timeline_Horizontal
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
            'title'           =>      __('Timeline Horizontal', 'dfd'),
            'description'     =>      __('Display horizontal timeline items', 'dfd'),
            'identifier'      =>      __CLASS__,
            'icon'            =>      'appbar.interface.textbox.png',
			'class'            =>      'fa fa-align-justify',
            'section'         =>      'presentation',
            'color'           =>      'gray'
        );
    }

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
				'col_span'  =>      'lbl_full'
            ),
			
            'separator-icon' => array('type'     =>  'separator'),
			
			'icon' => array(
				'type'      =>      'icon',
				'label'     =>      __('Icon', 'dfd'),
				'default'   =>      'moon-rocket',
				'col_span'  =>      'lbl_half',
			),
			
            'separator-animation' => array('type'     =>  'separator'),
			
			'animation_dates_speed' => array(
                'type'      =>      'select',
                'label'     =>      __('Dates Speed', 'dfd'),
                'help'      =>      __('Dates animation Speed', 'dfd'),
                'default'   =>      'normal',
                'options'   =>      array(
					'slow' => __('Slow', 'dfd'),
					'normal' => __('Normal', 'dfd'),
					'fast' => __('Fast', 'dfd'),
				),
                'col_span'  =>      'lbl_third'
            ),
			
			'animation_start_at' => array(
                'type'      =>      'text',
                'label'     =>      __('Start at', 'dfd'),
				'default'   =>      1,
				'col_span'  =>      'lbl_third'
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
    public static function render( $atts, $content = null )
    {
        global $mvb_metro_factory;

        $load = self::loadContent($atts, $content);
        $load['content'] = $content;
		$load['uniqid'] = '_'.uniqid();
		
		$share_js = array(
			'depends' => array('jquery'),
			'js' => array(
				'mvb_timelinr' => $mvb_metro_factory->app_url . '/assets/js/timelinr/jquery.timelinr.js'
			),
		);
		$mvb_metro_factory->queue_scripts($share_js, __CLASS__);

		return $mvb_metro_factory->_load_view('html/public/mvb_timeline_horizontal.php', $load);
    }
	
	private static function loadContent($atts, $content = null) {
		if (!empty($atts)) {
            extract($atts);
		}
		
		$post_order = (isset($post_order)) ? $post_order : 'ASC';
        $limit_number = (isset($limit_number)) ? $limit_number : '-1';

        $args = array(
            'posts_per_page' => $limit_number,
            'post_type' => 'timeline',
            'orderby' => 'menu_order title',
            'order' => $post_order
        );

		$dates = array();
		$issues = array();
		
		$items = array();
		
        $the_query = new WP_Query($args);
		
        while ($the_query->have_posts()) {
			$the_query->the_post();
			$item = array();
			
			$item['title'] = get_the_title();
			$item['content'] = get_the_content();
			$item['subtitle'] = get_post_meta(get_the_ID(), "crum_timeline_subtitle", true);
			
			$enable_composer = get_post_meta(get_the_ID(), '_bshaper_activate_metro_builder', true);
			
			if ($enable_composer) {
				$meta_value = get_post_meta(get_the_ID(), '_bshaper_artist_content', true);

				if (!empty($meta_value)) {
					$item['content'] = do_shortcode($meta_value[0]['columns'][0]['shortcodes']);
				}
			}

			$items[] = $item;
		}

        wp_reset_postdata();
		
		$atts['items'] = $items;
		
		return $atts;
	}

}//end class