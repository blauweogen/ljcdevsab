<?php

/*
*	Register extend component for Visual Composer
*	king-theme.com
*/


if (function_exists('vc_map')) {

	if(!function_exists('king_extend_visual_composer')){
		
		add_action( 'init', 'king_extend_visual_composer' );
		function king_extend_visual_composer(){
			
			global $vc_column_width_list, $king;
			$vc_is_wp_version_3_6_more = version_compare( preg_replace( '/^([\d\.]+)(\-.*$)/', '$1', get_bloginfo( 'version' ) ), '3.6' ) >= 0;
			 
			vc_map( array(
		    
		        "name" => __("Row", "js_composer"),
		        "base" => "vc_row",
		        "is_container" => true,
		        "icon" => "icon-wpb-row",
		        "show_settings_on_create" => true,
		        "category" => THEME_NAME.' Theme',
		        "description" => __('Place content elements inside the row', 'js_composer'),
		        "params" => array(
		          array(
		            "type" => "textfield",
		            "heading" => __("ID Name for Navigation", "js_composer"),
		            "param_name" => "king_id",
		            "description" => __("If this row wraps the content of one of your sections, set an ID. You can then use it for navigation. Ex: work", "js_composer")
		          ),
		           array(
		            "type" => "attach_image",
		            "heading" => __("Background Image", "js_composer"),
		            "param_name" => "bg_image",
		            "description" => __("Select backgound color for the row.", "js_composer")
		          ),
		          array(
		            "type" => "dropdown",
		            "heading" => __('Background Repeat', 'js_composer'),
		            "param_name" => "king_bg_repeat",
		            "value" => array(
		              __("Repeat-Y", 'js_composer') => 'repeat-y',
		              __("Repeat", 'js_composer') => 'repeat',
		              __('No Repeat', 'js_composer') => 'no-repeat',
		              __('Repeat-X', 'js_composer') => 'repeat-x'
		            )
		          ),
		          array(
		            "type" => "colorpicker",
		            "heading" => __('Background Color', 'js_composer'),
		            "param_name" => "bg_color",
		            "description" => __("You can set a color over the background image. You can make it more or less opaque, by using the next setting. Default: white ", "js_composer")
		          ),
		          array(
		            "type" => "textfield",
		            "heading" => __('Background Color Opacity', 'js_composer'),
		            "param_name" => "king_color_opacity",
		            "description" => __("Set an opacity value for the color(values between 0-100). 0 means no color while 100 means solid color. Default: 70 ", "js_composer")
		          ),
		          array(
		            "type" => "textfield",
		            "heading" => __("Padding Top", "js_composer"),
		            "param_name" => "king_padding_top",
		            "description" => __("Enter a value and it will be used for padding-top(px). As an alternative, use the 'Space' element.", "js_composer")
		          ),
		          array(
		            "type" => "textfield",
		            "heading" => __("Padding Bottom", "js_composer"),
		            "param_name" => "king_padding_bottom",
		            "description" => __("Enter a value and it will be used for padding-bottom(px). As an alternative, use the 'Space' element.", "js_composer")
		          ),
		          array(
		            "type" => "textfield",
		            "heading" => __("Container class name", "js_composer"),
		            "param_name" => "king_class_container",
		            "description" => __("Custom class name for container of this row", "js_composer")
		          ),		          
		          array(
		            "type" => "textfield",
		            "heading" => __("Section class name", "js_composer"),
		            "param_name" => "king_class",
		            "description" => __("Custom class for outermost wrapper.", "js_composer")
		          ),
		          array(
		            "type" => "dropdown",
		            "heading" => __('Type', 'js_composer'),
		            "param_name" => "king_row_type",
		            "description" => __("Select template full-width if you want to background full of screen", "js_composer"),
		            "value" => array(
		              __("Content In Container", 'js_composer') => 'container',
		              __("Fullwidth All", 'js_composer')    => 'container_full',
		              __("Parallax", 'js_composer')     => 'parallax'
		            )
		          ),
		        ),
		        "js_view" => 'VcRowView'
		      ) );
		      
		      
		      vc_map( array(
				'name' => __( 'Row', 'js_composer' ), //Inner Row
				'base' => 'vc_row_inner',
				'content_element' => false,
				'is_container' => true,
				'icon' => 'icon-wpb-row',
				'weight' => 1000,
				'show_settings_on_create' => false,
				'description' => __( 'Place content elements inside the row', 'js_composer' ),
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'js_composer' ),
						'param_name' => 'king_class',
						'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' )
					)
				),
				'js_view' => 'VcRowView'
			) );
		      
		      
		      vc_map( array(
				'name' => __( 'Column', 'js_composer' ),
				'base' => 'vc_column',
				'is_container' => true,
				'content_element' => false,
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'js_composer' ),
						'param_name' => 'el_class',
						'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' )
					),					
					array(
						'type' => 'dropdown',
						'heading' => __( 'Animate Effect', 'js_composer' ),
						'param_name' => 'el_animate',
						'value' => array(
							'---Select an animate---' => '',
							'Fade In' => 'animated eff-fadeIn',
							'From bottom up' => 'animated eff-fadeInUp',
							'From top down' => 'animated eff-fadeInDown',
							'From left' => 'animated eff-fadeInLeft',
							'From right' => 'animated eff-fadeInRight',
							'Zoom In' => 'animated eff-zoomIn',
							'Bounce In' => 'animated eff-bounceIn',
							'Bounce In Up' => 'animated eff-bounceInUp',
							'Bounce In Down' => 'animated eff-bounceInDown',
							'Bounce In Out' => 'animated eff-bounceInOut',
							'Flip In X' => 'animated eff-flipInX',
							'Flip In Y' => 'animated eff-flipInY',
						),
						'description' => __( 'Select animate effects to show this column when port-viewer scroll over', 'js_composer' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Animate Delay', 'js_composer' ),
						'param_name' => 'el_delay',
						'description' => __( 'Delay animate effect after number of mili seconds, e.g: 200 ', 'js_composer' )
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'Css', 'js_composer' ),
						'param_name' => 'css',
						'group' => __( 'Design options', 'js_composer' )
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Width', 'js_composer' ),
						'param_name' => 'width',
						'value' => $vc_column_width_list,
						'group' => __( 'Width & Responsiveness', 'js_composer' ),
						'description' => __( 'Select column width.', 'js_composer' ),
						'std' => '1/1'
					),
					array(
						'type' => 'column_offset',
						'heading' => __( 'Responsiveness', 'js_composer' ),
						'param_name' => 'offset',
						'group' => __( 'Width & Responsiveness', 'js_composer' ),
						'description' => __( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'js_composer' )
					)
				),
				'js_view' => 'VcColumnView'
			) );
			
			
			vc_map( array(
				"name" => __( "Column", "js_composer" ),
				"base" => "vc_column_inner",
				"class" => "",
				"icon" => "",
				"wrapper_class" => "",
				"controls" => "full",
				"allowed_container_element" => false,
				"content_element" => false,
				"is_container" => true,
				"params" => array(
					array(
						"type" => "textfield",
						"heading" => __( "Extra class name", "js_composer" ),
						"param_name" => "el_class",
						"value" => "",
						"description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" )
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Animate Effect', 'js_composer' ),
						'param_name' => 'el_animate',
						'value' => array(
							'---Select an animate---' => '',
							'Fade In' => 'animated eff-fadeIn',
							'From bottom up' => 'animated eff-fadeInUp',
							'From top down' => 'animated eff-fadeInDown',
							'From left' => 'animated eff-fadeInLeft',
							'From right' => 'animated eff-fadeInRight',
							'Zoom In' => 'animated eff-zoomIn',
							'Bounce In' => 'animated eff-bounceIn',
							'Bounce In Up' => 'animated eff-bounceInUp',
							'Bounce In Down' => 'animated eff-bounceInDown',
							'Bounce In Out' => 'animated eff-bounceInOut',
							'Flip In X' => 'animated eff-flipInX',
							'Flip In Y' => 'animated eff-flipInY',
						),
						'description' => __( 'Select animate effects to show this column when port-viewer scroll over', 'js_composer' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Animate Delay', 'js_composer' ),
						'param_name' => 'el_delay',
						'description' => __( 'Delay animate effect after number of mili seconds, e.g: 200 ', 'js_composer' )
					),
					array(
						"type" => "css_editor",
						"heading" => __( 'Css', "js_composer" ),
						"param_name" => "css",
						// "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer"),
						"group" => __( 'Design options', 'js_composer' )
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Width', 'js_composer' ),
						'param_name' => 'width',
						'value' => $vc_column_width_list,
						'group' => __( 'Width & Responsiveness', 'js_composer' ),
						'description' => __( 'Select column width.', 'js_composer' ),
						'std' => '1/1'
					)
				),
				"js_view" => 'VcColumnView'
			) );
			
		    vc_map( array(
				'name' => __( 'X-Code Editor', 'js_composer' ),
				'base' => 'vc_raw_html',
				'icon' => 'icon-wpb-raw-html',
				'category' => THEME_NAME.' Theme',
				'wrapper_class' => 'clearfix',
				'description' => __( 'Custom code php, html, javascript, css, shortcodes', 'js_composer' ),
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Title', 'js_composer' ),
						'param_name' => 'title',
						'holder' => 'i',
						'description' => __( 'Label will display at VisualComposer admin', 'js_composer' ),
						'admin_label' => true,
					),
					array(
						'type' => 'textarea_raw_html',
						'heading' => __( 'X-Code - PHP, HTML, Javascript, CSS, ShortCodes', 'js_composer' ),
						'param_name' => 'content',
						'holder' => 'div',
						'value' => $king->ext['be']( '<p>I am X-Code Editor (king-theme.com)<br/>Click edit button to change this code</p>' ),
						'description' => __( 'Enter your HTML, PHP, JavaScript, Css, Shortcodes.', 'js_composer' )
					),
				)
			));	
				      
		    vc_map( array(
				'name' => __( 'FAQs', 'js_composer' ),
				'base' => 'faq',
				'icon' => 'fa fa-question-circle',
				'category' => THEME_NAME.' Theme',
				'wrapper_class' => 'clearfix',
				'description' => __( 'Output FAQs as accordion from faqs post type.', 'js_composer' ),
				'params' => array(
					array(
						'type' => 'multiple',
						'heading' => __( 'Select Categories ( hold ctrl or shift to select multiple )', 'js_composer' ),
						'param_name' => 'category',
						'values' => Su_Tools::get_terms( 'faq-category', 'slug' ),
						'admin_label' => true,
						'description' => __( 'Select category which you chosen for FAQs', 'js_composer' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Amount', 'js_composer' ),
						'param_name' => 'amount',
						'value' => 20,
						'admin_label' => true,
						'description' => __( 'Enter number of FAQs that you want to display. To edit FAQs, go to ', 'js_composer' ).'/wp-admin/edit.php?post_type=faq'
					),
				)
			));
			
			
			/* Empty Space Element
			---------------------------------------------------------- */
			$mrt = array( '120px' => '120px', '---Select Margin Top---' => '' );
			$mrb = array( '---Select Margin Bottom---' => '');
			for( $i=1; $i <=15; $i++ ){
				$mrt[ $i.'0px'] =  $i.'0px';
				$mrb[ $i.'0px'] =  $i.'0px';
			}
			vc_map( array(
				'name' => __( 'Margin Spacing', 'js_composer' ),
				'base' => 'margin',
				'icon' => 'fa fa-arrows-v',
				'show_settings_on_create' => true,
				'category' => THEME_NAME.' Theme',
				'description' => __( 'Blank spacing', 'js_composer' ),
				'params' => array(
					array(
						'type' => 'dropdown',
						'heading' => __( 'Margin Top', 'js_composer' ),
						'param_name' => 'margin_top',
						'admin_label' => true,
						'value' => $mrt
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Margin Bottom', 'js_composer' ),
						'param_name' => 'margin_bottom',
						'admin_label' => true,
						'value' => $mrb
					),
				),
			) );
				      										      
		    vc_map( array(
				'name' => __( 'King Loop', 'js_composer' ),
				'base' => 'king_loop',
				'icon' => 'fa fa-star-o',
				'category' => THEME_NAME.' Theme',
				'wrapper_class' => 'clearfix',
				'description' => __( 'Output list of item template', 'js_composer' ),
				'params' => array(
					array(
						'type' => 'taxonomy',
						'heading' => __( 'Categories', 'js_composer' ),
						'param_name' => 'category',
						'values' => '',
						'admin_label' => true,
						'description' => __( 'Select Post type & categories  (Hold ctrl or command to select multiple)', 'js_composer' )
					),
					array(
						'type' => 'radio',
						'heading' => __( 'How showing', 'js_composer' ),
						'param_name' => 'showing',
						'value' => array(
							'Normal as Grids &nbsp; &nbsp; ' => 'grid',
							'Showing As Sliders' => 'slider',
						),
						'description' => ''
					),
					array(
						'type' => 'textarea_raw_html',
						'heading' => __( 'Item Format', 'js_composer' ),
						'param_name' => 'format',
						'description' => __( 'Available params: {title}, {position}, {img}, {des}, {link}, {social}, {date}, {category}, {author}, {comment}, {price}, {per}, {submit-link}, {submit-text}, {des-li}, {des-br}, {day}, {month}', 'js_composer' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Number of items', 'js_composer' ),
						'param_name' => 'items',
						'value' => 20,
						'admin_label' => true,
						'description' => __( 'Enter number of people to show', 'js_composer' )
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Number per row', 'js_composer' ),
						'param_name' => 'per_row',
						'value' => array(
							'Four' => 4,
							'One' => 1,
							'Two' => 2,
							'Three' => 3,
							'Five' => 5,
						),
						'admin_label' => true,
						'description' => 'Number people display on 1 row'
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Class of Wrapper', 'js_composer' ),
						'param_name' => 'class',
						'value' => '',
						'description' => __( 'Custom class name for wrapper', 'js_composer' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Class of Odd Columns', 'js_composer' ),
						'param_name' => 'odd_class',
						'value' => '',
						'description' => __( 'Custom class name for odd columns', 'js_composer' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Class of Even Columns', 'js_composer' ),
						'param_name' => 'even_class',
						'value' => '',
						'description' => __( 'Custom class name for even columns', 'js_composer' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Size ( width x height )', 'js_composer' ),
						'param_name' => 'img_size',
						'value' => '245x245',
						'description' => __( 'Set thumbnail size e.g: 245x245', 'js_composer' )
					),	
					array(
						'type' => 'dropdown',
						'heading' => __( 'Hightlight Column', 'js_composer' ),
						'param_name' => 'highlight',
						'value' => array(
							'Three' => 3,
							'None' => 0,
							'One' => 1,
							'Two' => 2,
							'Four' => 4,
							'Five' => 5,
						),
						'description' => 'Select column to set highlight (using for pricing table)'
					),				
					array(
						'type' => 'textfield',
						'heading' => __( 'Words Limit', 'js_composer' ),
						'param_name' => 'words',
						'value' => 20,
						'description' => __( 'Limit words you want show as short description', 'js_composer' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Offset', 'js_composer' ),
						'param_name' => 'offset',
						'value' => 0,
						'description' => __( 'Set offset to start select sql from', 'js_composer' )
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order By', 'js_composer' ),
						'param_name' => 'order',
						'value' => array(
							'Descending' => 'DESC',
							'Ascending' => 'ASC'
						),
						'description' => ' &nbsp; '
					)
				)
			));
			
						
			 vc_map( array(
				'name' => __( 'Our Team', 'js_composer' ),
				'base' => 'team',
				'icon' => 'fa fa-group',
				'category' => THEME_NAME.' Theme',
				'wrapper_class' => 'clearfix',
				'description' => __( 'Output our team template', 'js_composer' ),
				'params' => array(
					array(
						'type' => 'multiple',
						'heading' => __( 'Select Category ( hold ctrl or shift to select multiple )', 'js_composer' ),
						'param_name' => 'category',
						'values' => Su_Tools::get_terms( 'our-team-category', 'slug' ),
						'height' => '150px',
						'description' => __( 'Select category to display team', 'js_composer' )
					),					
					array(
						'type' => 'dropdown',
						'heading' => __( 'Choose Style', 'js_composer' ),
						'param_name' => 'style',
						'admin_label' => true,
						'value' => array(
							'Grids'				=> 'grids',
							'2 Columns'		=> '2-columns',
							'Circle' 			=> 'circle',
							'Circle Style 2'	=> 'circle-2',
							'Grids Style 2'		=> 'grids-2',
							'Grids Style 3'		=> 'grids-3',
							'Grids Style 4'		=> 'grids-4',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Amount', 'js_composer' ),
						'param_name' => 'items',
						'value' => 20,
						'description' => __( 'Enter number of people to show', 'js_composer' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Words Limit', 'js_composer' ),
						'param_name' => 'words',
						'value' => 20,
						'description' => __( 'Limit words you want show as short description', 'js_composer' )
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order By', 'js_composer' ),
						'param_name' => 'order',
						'value' => array(
							'Descending' => 'desc',
							'Ascending' => 'asc'
						),
						'description' => ' &nbsp; '
					)
				)
			));
			
			vc_map( array(
				'name' => __( 'Our Work (Portfolio)', 'js_composer' ),
				'base' => 'work',
				'icon' => 'fa fa-send-o',
				'category' => THEME_NAME.' Theme',
				'wrapper_class' => 'clearfix',
				'description' => __( 'Our work for portfolio template.', 'js_composer' ),
				'params' => array(
					array(
						'type' => 'multiple',
						'heading' => __( 'Select Categories ( hold ctrl or shift to select multiple )', 'js_composer' ),
						'param_name' => 'tax_term',
						'values' => Su_Tools::get_terms( 'our-works-category', 'slug' ),
						'height' => '120px',
						'admin_label' => true,
						'description' => __( 'Select category which you chosen for Team items', 'js_composer' )
					),					
					array(
						'type' => 'dropdown',
						'heading' => __( 'Show Filter', 'js_composer' ),
						'param_name' => 'filter',
						'value' => array(
							'Yes'	=> 'Yes',
							'No'	=> 'No',
						),
					),
					array(
						'type' => 'select',
						'heading' => __( 'Items on Row', 'js_composer' ),
						'param_name' => 'column',
						'values' => array(
							'two' => 2,
							' ' => 3,
							'four' => 4,
							'five' => 5,
							'masonry' => 'Masonry Layout',
							'sliders' => 'Sliders'
						),
						'description' => __( 'Choose number of items display on a row', 'js_composer' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Items Limit', 'js_composer' ),
						'param_name' => 'items',
						'value' => get_option( 'posts_per_page' ),
						'description' => __( 'Specify number of team that you want to show. Enter -1 to get all team', 'js_composer' )
					),
					array(
						'type' => 'select',
						'heading' => __( 'Order By', 'js_composer' ),
						'param_name' => 'order',
						'values' => array(
								'desc' => __( 'Descending', KING_DOMAIN ),
								'asc' => __( 'Ascending', KING_DOMAIN )
						),
						'description' => ' &nbsp; '
					)
				)
			));
			
			vc_map( array(
				'name' => __( 'Testimonials', 'js_composer' ),
				'base' => 'testimonials',
				'icon' => 'fa fa-group',
				'category' => THEME_NAME.' Theme',
				'wrapper_class' => 'clearfix',
				'description' => __( 'Out testimonians post type.', 'js_composer' ),
				'params' => array(
					array(
						'type' => 'multiple',
						'heading' => __( 'Select Categories ( hold ctrl or shift to select multiple )', 'js_composer' ),
						'param_name' => 'category',
						'values' => Su_Tools::get_terms( 'testimonials-category', 'slug' ),
						'height' => '120px',
						'admin_label' => true,
						'description' => __( 'Select category which you chosen for Team items', 'js_composer' )
					),	
					array(
						'type' => 'select',
						'heading' => __( 'Select Layout', 'js_composer' ),
						'param_name' => 'layout',
						'values' => array(
							'slider-1' => 'Slider Style 1',
							'slider-2' => 'Slider Style 2',
							'slider-3' => 'Slider Style 3',
							'slider-4' => 'Slider Style 4',
							'slider-5' => 'Slider Style 5',
							'slider-6' => 'Slider Style 6',
							'slider-ms' => 'MS Slider',
							'2-columns' => '2 Columns',
							'3-columns' => '3 Columns',
						),
						'admin_label' => true,
						'description' => __( 'Select layout to display testimonials', 'js_composer' )
					),	
					array(
						'type' => 'textfield',
						'heading' => __( 'Items Limit', 'js_composer' ),
						'param_name' => 'items',
						'value' => get_option( 'posts_per_page' ),
						'description' => __( 'Specify number of team that you want to show. Enter -1 to get all', 'js_composer' )
					),						
					array(
						'type' => 'textfield',
						'heading' => __( 'Limit Words', 'js_composer' ),
						'param_name' => 'words',
						'value' => 20,
						'description' => __( 'Limit words you want show as short description', 'js_composer' )
					),
					array(
						'type' => 'select',
						'heading' => __( 'Order By', 'js_composer' ),
						'param_name' => 'order',
						'values' => array(
								'desc' => __( 'Descending', KING_DOMAIN ),
								'asc' => __( 'Ascending', KING_DOMAIN )
						),
						'description' => ' &nbsp; '
					)
				)
			));
						
			vc_map( array(
			
				'name' => __( 'Pie Chart', 'js_composer' ),
				'base' => 'piechart',
				'icon' => 'fa fa-pie-chart',
				'category' => THEME_NAME.' Theme',
				'wrapper_class' => 'clearfix',
				'description' => __( 'Out testimonians post type.', 'js_composer' ),
				'params' => array(

					array(
						'type' => 'select',
						'param_name' => 'size',
						'values' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
							'7' => '7',
							'8' => '8',
						),
						'value' => 7,
						'heading' => __( 'Size', KING_DOMAIN ),
						'description' => __( 'Size of chart', KING_DOMAIN )
					),
					
					array(
						'type' => 'select',
						'param_name' => 'style',
						'values' => array(
							'piechart1' => 'Pie Chart 1',
							'piechart2' => 'Pie Chart 2 (auto width by size)',
							'piechart3' => 'Pie Chart 3 (white color)'
						),
						'value' => 7,
						'heading' => __( 'Size', KING_DOMAIN ),
						'description' => __( 'Size of chart', KING_DOMAIN )
					),
					array(
						'param_name' => 'percent',
						'type' 	=> 'textfield',
						'value' => 75,
						'admin_label' => true,
						'heading' => __( 'Percent', KING_DOMAIN ),
						'description' => __( 'Percent value of chart', KING_DOMAIN )
					),
					array(
			            "type" => "colorpicker",
			            "heading" => __('Color', 'js_composer'),
			            "param_name" => "color",
			            "description" => __("Color of chart", "js_composer")
			        ),
					array(
						'param_name' => 'text',
						'type' 	=> 'textfield',
						'heading' => __( 'Text', KING_DOMAIN ),
						'description' => __( 'The text bellow chart', KING_DOMAIN ),
						'admin_label' => true,
					),
					array(
						'param_name' => 'class',
						'type' 	=> 'textfield',
						'heading' => __( 'Class', KING_DOMAIN ),
						'description' => __( 'Extra CSS class', KING_DOMAIN )
					)
					
				)
			));

			vc_map( array(
			
				'name' => __( 'Pricing Table', 'js_composer' ),
				'base' => 'pricing',
				'icon' => 'fa fa-table',
				'category' => THEME_NAME.' Theme',
				'wrapper_class' => 'clearfix',
				'description' => __( 'Display Pricing Plan Table', 'js_composer' ),
				'params' => array(
					array(
						'type' => 'select',
						'heading' => __( 'Select Categories ( hold ctrl or shift to select multiple )', 'js_composer' ),
						'param_name' => 'category',
						'values' => Su_Tools::get_terms( 'pricing-tables-category', 'slug', null, '---Select Category---' ),
						'admin_label' => true,
						'description' => __( 'Select category which you chosen for Pricing Table', 'js_composer' )
					),
					array(
						'type' => 'select',
						'param_name' => 'amount',
						'values' => array(
								'1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
						),
						'value' => 4,
						'heading' => __( 'Amount', KING_DOMAIN ),
						'description' => __( 'Number of columns', KING_DOMAIN )
					),	
					array(
						'type' => 'select',
						'param_name' => 'active',
						'values' => array(
								'1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
						),
						'value' => 3,
						'heading' => __( 'Active Column', KING_DOMAIN ),
						'description' => __( 'Select column to highlight', KING_DOMAIN )
					),
					array(
						'type' => 'select',
						'param_name' => 'style',
						'values' => array(
								'1' => 'Style 1 - 4 columns',
								'2' => 'Style 2 - 3 columns',
								'3' => 'Style 3 - 3 columns',
								'4' => 'Style 4 Cyan- 4 columns',
								'5' => 'Style 5 with label'
						),
						'heading' => __( 'Style', KING_DOMAIN ),
						'description' => __( 'Select style for pricing table', KING_DOMAIN )
					),
					array(
						'param_name' => 'icon',
						'type' 	=> 'icon',
						'heading' => __( 'Icon', KING_DOMAIN ),
						'description' => __( 'the icon display on per row', KING_DOMAIN )
					),
					array(
						'param_name' => 'class',
						'type' 	=> 'textfield',
						'heading' => __( 'Class', KING_DOMAIN ),
						'description' => __( 'Extra CSS class', KING_DOMAIN )
					)
					
				)
			));

			vc_map( array(
			
				'name' => __( 'Progress Bars', 'js_composer' ),
				'base' => 'progress',
				'icon' => 'fa fa-line-chart',
				'category' => THEME_NAME.' Theme',
				'wrapper_class' => 'clearfix',
				'description' => __( 'Display Progress Bars', 'js_composer' ),
				'params' => array(

					array(
						'type' => 'select',
						'param_name' => 'style',
						'values' => array(
								'1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
						),
						'heading' => __( 'Style', KING_DOMAIN ),
						'description' => __( 'Style of progress bar', KING_DOMAIN )
					),
					array(
						'type' => 'textfield',
						'param_name' => 'percent',
						'value' => 75,
						'admin_label' => true,
						'heading' => __( 'Percent', KING_DOMAIN ),
						'description' => __( 'Percent value of progress bar', KING_DOMAIN )
					),
					array(
						'type' => 'colorpicker',
						'param_name' => 'color',
						'value' => '#333333',
						'heading' => __( 'Color', KING_DOMAIN ),
						'description' => __( 'Color of progress bar', KING_DOMAIN )
					),
					array(
						'type' => 'textfield',
						'param_name' => 'text',
						'admin_label' => true,
						'heading' => __( 'Text', KING_DOMAIN ),
						'description' => __( 'The text bellow chart', KING_DOMAIN )
					),
					array(
						'type' => 'textfield',
						'param_name' => 'class',
						'heading' => __( 'Class', KING_DOMAIN ),
						'description' => __( 'Extra CSS class', KING_DOMAIN )
					)
					
				)
			));
			
			vc_map( array(
			
				'name' => __( 'Divider', 'js_composer' ),
				'base' => 'divider',
				'icon' => 'icon-wpb-ui-separator',
				'category' => THEME_NAME.' Theme',
				'wrapper_class' => 'clearfix',
				'description' => __( 'List of horizontal divider line', 'js_composer' ),
				'params' => array(

					array(
						'type' => 'select',
						'param_name' => 'style',
						'values' => array(
								'1' => 'Style 1',
								'2' => 'Style 2',
								'3' => 'Style 3',
								'4' => 'Style 4',
								'5' => 'Style 5',
								'6' => 'Style 6',
								'7' => 'Style 7',
								'8' => 'Style 8',
								'9' => 'Style 9',
								'10' => 'Style 10',
								'11' => 'Style 11',
								'12' => 'Style 12',
								'13' => 'Style 13',
								' ' => 'Divider Line',
						),
						'admin_label' => true,
						'heading' => __( 'Style', KING_DOMAIN ),
						'description' => __( 'Style of divider', KING_DOMAIN )
					),
					array(
						'type' => 'icon',
						'param_name' => 'icon',
						'heading' => __( 'Icon', KING_DOMAIN ),
						'description' => __( 'Select icon on divider', KING_DOMAIN )
					),
					array(
						'type' => 'textfield',
						'param_name' => 'class',
						'heading' => __( 'Class', KING_DOMAIN ),
						'description' => __( 'Extra CSS class', KING_DOMAIN )
					)
					
				)
			));
					
			vc_map( array(
			
				'name' => __( 'Title Styles', 'js_composer' ),
				'base' => 'titles',
				'category' => THEME_NAME.' Theme',
				'wrapper_class' => 'clearfix',
				'icon' => 'fa fa-university',
				'description' => __( 'List of Title Styles', 'js_composer' ),
				'params' => array(

					array(
						'type' => 'select',
						'param_name' => 'type',
						'values' => array(
								'h1' => 'H1',
								'h2' => 'H2',
								'h3' => 'H3',
								'h4' => 'H4',
								'h5' => 'H5',
								'h6' => 'H6',
						),
						'admin_label' => true,
						'heading' => __( 'Head Tag', KING_DOMAIN ),
						'description' => __( 'Select Header Tag', KING_DOMAIN )
					),
					array(
						'type' => 'textarea_raw_html',
						'param_name' => 'text',
						'heading' => __( 'Title Text', KING_DOMAIN ),
						'holder' => 'div'
					),
					array(
						'type' => 'textfield',
						'param_name' => 'class',
						'heading' => __( 'Class', KING_DOMAIN ),
						'description' => __( 'Extra CSS class', KING_DOMAIN )
					)
					
				)
			));
			
			vc_map( array(
			
				'name' => __( 'Flip Clients', 'js_composer' ),
				'base' => 'flip_clients',
				'icon' => 'fa fa-apple',
				'category' => THEME_NAME.' Theme',
				'wrapper_class' => 'clearfix',
				'description' => __( 'Display clients with flip styles', 'js_composer' ),
				'params' => array(

					array(
						'type' => 'attach_image',
						'param_name' => 'img',
						'heading' => __( 'Logo Image', KING_DOMAIN ),
						'description' => __( 'Upload the client\'s logo', KING_DOMAIN )
					),
					array(
						'type' => 'textfield',
						'param_name' => 'title',
						'heading' => __( 'Title', KING_DOMAIN ),
						'admin_label' => true,
						'description' => __( 'The name of client', KING_DOMAIN )
					),						
					array(
						'type' => 'textfield',
						'param_name' => 'link',
						'heading' => __( 'Link', KING_DOMAIN ),
						'description' => __( 'Link to client website', KING_DOMAIN )
					),					
					array(
						'type' => 'textfield',
						'param_name' => 'des',
						'heading' => 'Description',
						'description' => __( 'Short Descript will show when hover', KING_DOMAIN ),
						'admin_label' => true,
					),
					array(
						'type' => 'textfield',
						'param_name' => 'class',
						'heading' => __( 'Class', KING_DOMAIN ),
						'description' => __( 'Extra CSS class', KING_DOMAIN )
					)
					
				)
			));
						
			vc_map( array(
			
				'name' => __( 'Posts - '.THEME_NAME, 'js_composer' ),
				'base' => 'posts',
				'icon' => 'fa fa-th-list',
				'category' => THEME_NAME.' Theme',
				'wrapper_class' => 'clearfix',
				'description' => __( 'List posts by other layouts of theme', 'js_composer' ),
				'params' => array(

					array(
						'type' => 'select', 
						'param_name' => 'template',
						'values' => array(
								'default-loop.php' => 'Default Loop',
								'single-post.php' => 'Single Post',
								'list-loop.php' => 'List Loop',
								'home-news-4-columns.php' => 'Home News 4 columns',
								'home-news-3-columns.php' => 'Home News 3 columns',
								'home-news-3-columns-2.php' => 'Home News 3 columns Style 2',
								'home-news-3-columns-3.php' => 'Home News 3 columns Style 3',
								'home-news-2-columns.php' => 'Home News 2 columns',
								'home-news-1-column.php' => 'Home News 1 column',
								'home-news-slider.php' => 'Home News Slider',
								'home-onepage-news-list.php' => 'Latest news 3 columns (6 posts)',
																
						),
						'admin_label' => true,
						'heading' => __( 'Template', KING_DOMAIN ),
						'description' => __( 'List posts under templates of theme', KING_DOMAIN )
					),
					array(
						'type' => 'textfield',
						'param_name' => 'id',
						'heading' => __( 'Post ID\'s', KING_DOMAIN ),
						'description' => __( 'Enter comma separated ID\'s of the posts that you want to show', KING_DOMAIN )
					),				
					array(
						'type' => 'textfield',
						'param_name' => 'posts_per_page',
						'value' => get_option( 'posts_per_page' ),
						'heading' => __( 'Posts per page', KING_DOMAIN ),
						'description' => __( 'Specify number of posts that you want to show. Enter -1 to get all posts', KING_DOMAIN )
					),					
					array(
						'type' => 'select',
						'param_name' => 'post_type',
						'values' => Su_Tools::get_types(),
						'value' => 'post',
						'heading' => __( 'Post types', KING_DOMAIN ),
						'description' => __( 'Select post types. Hold Ctrl key to select multiple post types', KING_DOMAIN )
					),					
					array(
						'type' => 'select',
						'param_name' => 'taxonomy',
						'values' => Su_Tools::get_taxonomies(),
						'value' => 'category',
						'heading' => __( 'Taxonomy', KING_DOMAIN ),
						'description' => __( 'Select taxonomy to show posts from', KING_DOMAIN )
					),
					array(
						'type' => 'multiple',
						'param_name' => 'tax_term',
						'values' => Su_Tools::get_terms( 'category', 'slug' ),
						'heading' => __( 'Terms', KING_DOMAIN ),
						'description' => __( 'Select terms to show posts from', KING_DOMAIN )
					),					
					array(
						'type' => 'select',
						'param_name' => 'tax_operator',
						'values' => array( 'IN' => 'IN', 'NOT IN' => 'NOT IN', 'AND' => 'AND' ),
						'value' => 'IN',
						'heading' => __( 'Taxonomy term operator', KING_DOMAIN ),
						'description' => __( 'IN - posts that have any of selected categories terms<br/>NOT IN - posts that is does not have any of selected terms<br/>AND - posts that have all selected terms', KING_DOMAIN )
					),					
					array(
						'type' => 'multiple',
						'param_name' => 'author',
						'values' => Su_Tools::get_users(),
						'value' => 'default',
						'heading' => __( 'Authors', KING_DOMAIN ),
						'description' => __( 'Choose the authors whose posts you want to show', KING_DOMAIN )
					),
					array(
						'type' => 'textfield',
						'param_name' => 'meta_key',
						'heading' => __( 'Meta key', KING_DOMAIN ),
						'description' => __( 'Enter meta key name to show posts that have this key', KING_DOMAIN )
					),					
					array(
						'type' => 'textfield',
						'param_name' => 'offset',
						'value' => '0',
						'heading' => __( 'Offset', KING_DOMAIN ),
						'description' => __( 'Specify offset to start posts loop not from first post', KING_DOMAIN )
					),
					array(
						'type' => 'select',
						'values' => array(
							'desc' => __( 'Descending', KING_DOMAIN ),
							'asc' => __( 'Ascending', KING_DOMAIN )
						),
						'param_name' => 'order',
						'heading' => __( 'Offset', KING_DOMAIN ),
						'description' => __( 'Posts order', KING_DOMAIN )
					),
					array(
						'type' => 'select',
						'values' => array(
							'none' => __( 'None', KING_DOMAIN ),
							'id' => __( 'Post ID', KING_DOMAIN ),
							'author' => __( 'Post author', KING_DOMAIN ),
							'title' => __( 'Post title', KING_DOMAIN ),
							'name' => __( 'Post slug', KING_DOMAIN ),
							'date' => __( 'Date', KING_DOMAIN ), 'modified' => __( 'Last modified date', KING_DOMAIN ),
							'parent' => __( 'Post parent', KING_DOMAIN ),
							'rand' => __( 'Random', KING_DOMAIN ), 'comment_count' => __( 'Comments number', KING_DOMAIN ),
							'menu_order' => __( 'Menu order', KING_DOMAIN ), 'meta_value' => __( 'Meta key values', KING_DOMAIN ),
						),
						'value' => 'date',
						'param_name' => 'orderby',
						'heading' => __( 'Order by', KING_DOMAIN ),
						'description' => __( 'Order posts by', KING_DOMAIN )
					),					
					array(
						'type' => 'textfield',
						'param_name' => 'post_parent',
						'heading' => __( 'Post parent', KING_DOMAIN ),
						'description' => __( 'Show childrens of entered post (enter post ID)', KING_DOMAIN )
					),					
					array(
						'type' => 'select',
						'values' => array(
							'publish' => __( 'Published', KING_DOMAIN ),
							'pending' => __( 'Pending', KING_DOMAIN ),
							'draft' => __( 'Draft', KING_DOMAIN ),
							'auto-draft' => __( 'Auto-draft', KING_DOMAIN ),
							'future' => __( 'Future post', KING_DOMAIN ),
							'private' => __( 'Private post', KING_DOMAIN ),
							'inherit' => __( 'Inherit', KING_DOMAIN ),
							'trash' => __( 'Trashed', KING_DOMAIN ),
							'any' => __( 'Any', KING_DOMAIN ),
						),
						'value' => 'publish',
						'param_name' => 'post_status',
						'heading' => __( 'Post status', KING_DOMAIN ),
						'description' => __( 'Show only posts with selected status', KING_DOMAIN )
					),					
					array(
						'type' => 'select',
						'values' => array( 'no' => 'no', 'yes' => 'yes' ),
						'param_name' => 'ignore_sticky_posts',
						'heading' => __( 'Ignore sticky', KING_DOMAIN ),
						'description' => __( 'Select Yes to ignore posts that is sticked', KING_DOMAIN )
					),
					
				)
			));

			vc_map( array(
				'name' => __( 'Accordion', 'js_composer' ),
				'base' => 'vc_accordion',
				'show_settings_on_create' => false,
				'is_container' => true,
				'icon' => 'icon-wpb-ui-accordion',
				'category' => THEME_NAME.' Theme',
				'description' => __( 'Collapsible content panels', 'js_composer' ),
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Widget title', 'js_composer' ),
						'param_name' => 'title',
						'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', 'js_composer' )
					),
					array(
						'type' => 'select',
						'heading' => __( 'Style', 'js_composer' ),
						'param_name' => 'style',
						'values' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'2 white' => 'white color',
						),
						'description' => __( 'Select style of accordion.', 'js_composer' )
					),
					array(
						'type' => 'select',
						'heading' => __( 'Icon', 'js_composer' ),
						'param_name' => 'icon',
						'values' => array(
							'icon-plus' => 'Icon Plus',
							'icon-plus-circle' => 'Plus Circle',
							'icon-plus-square-1' => 'Plus Square 1',
							'icon-plus-square-2' => 'Plus Square 2',
							'icon-arrow' => 'Icon Arrow',
							'icon-arrow-circle-1' => 'Arrow Circle 1',
							'icon-arrow-circle-2' => 'Arrow Circle 2',
							'icon-chevron' => 'Icon Chevron',
							'icon-chevron-circle' => 'Icon Chevron Circle',
							'icon-caret' => 'Icon Caret',
							'icon-caret-square' => 'Icon Caret Square',
							'icon-folder-1' => 'Icon Folder 1',
							'icon-folder-2' => 'Icon Folder 2',
						),
						'description' => __( 'Select icon display on each spoiler', 'js_composer' )
					),	
					array(
						'type' => 'textfield',
						'heading' => __( 'Active section', 'js_composer' ),
						'param_name' => 'active_tab',
						'description' => __( 'Enter section number to be active on load or enter false to collapse all sections.', 'js_composer' )
					),
					array(
						'type' => 'checkbox',
						'heading' => __( 'Allow collapsible all', 'js_composer' ),
						'param_name' => 'collapsible',
						'description' => __( 'Select checkbox to allow all sections to be collapsible.', 'js_composer' ),
						'value' => array( __( 'Allow', 'js_composer' ) => 'yes' )
					),
					array(
						'type' => 'checkbox',
						'heading' => __( 'Disable keyboard interactions', 'js_composer' ),
						'param_name' => 'disable_keyboard',
						'description' => __( 'Disables keyboard arrows interactions LEFT/UP/RIGHT/DOWN/SPACES keys.', 'js_composer' ),
						'value' => array( __( 'Disable', 'js_composer' ) => 'yes' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'js_composer' ),
						'param_name' => 'el_class',
						'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' )
					)
				),
				'custom_markup' => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
					%content%
					</div>
					<div class="tab_controls">
					    <a class="add_tab" title="' . __( 'Add section', 'js_composer' ) . '"><span class="vc_icon"></span> <span class="tab-label">' . __( 'Add section', 'js_composer' ) . '</span></a>
					</div>
				',
					'default_content' => '
				    [vc_accordion_tab title="' . __( 'Section 1', 'js_composer' ) . '"][/vc_accordion_tab]
				    [vc_accordion_tab title="' . __( 'Section 2', 'js_composer' ) . '"][/vc_accordion_tab]
				',
				'js_view' => 'VcAccordionView'
			));

			
			$tab_id_1 = 'def' . time() . '-1-' . rand( 0, 100 );
			$tab_id_2 = 'def' . time() . '-2-' . rand( 0, 100 );
			vc_map( array(
				"name" => __( 'Tabs - Sliders', 'js_composer' ),
				'base' => 'vc_tabs',
				'show_settings_on_create' => false,
				'is_container' => true,
				'icon' => 'icon-wpb-ui-tab-content',
				'category' => THEME_NAME.' Theme',
				'description' => __( 'Custom Tabs, Sliders', 'js_composer' ),
				'params' => array(
					array(
						'type' => 'select',
						'heading' => __( 'Display as', 'js_composer' ),
						'values' => array(
							'tabs' => 'Display as Tabs',
							'vertical' => 'Vertical Style',
							'detached' => 'Display as Tabs Detached',
							'ipad-sliders' => 'Display as iPad Sliders',
							'sliders' => 'Display as Flex Sliders'
						),
						'admin_label' => true,
						'param_name' => 'type',
						'description' => __( 'You can choose to display as tabs or sliders', 'js_composer' )
					),					
					array(
						'type' => 'dropdown',
						'heading' => __( 'Auto rotate tabs', 'js_composer' ),
						'param_name' => 'interval',
						'value' => array( __( 'Disable', 'js_composer' ) => 0, 3, 5, 10, 15 ),
						'std' => 0,
						'description' => __( 'Auto rotate tabs each X seconds.', 'js_composer' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'js_composer' ),
						'param_name' => 'el_class',
						'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' )
					)
				),
				'custom_markup' => '
			<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
			<ul class="tabs_controls">
			</ul>
			%content%
			</div>'
			, 
			'default_content' => '
			[vc_tab title="' . __( 'Tab 1', 'js_composer' ) . '" tab_id="' . $tab_id_1 . '"][/vc_tab]
			[vc_tab title="' . __( 'Tab 2', 'js_composer' ) . '" tab_id="' . $tab_id_2 . '"][/vc_tab]
			',
				'js_view' => $vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35'
			) );


			vc_map( array(
				'name' => __( 'Tab', 'js_composer' ),
				'base' => 'vc_tab',
				'allowed_container_element' => 'vc_row',
				'is_container' => true,
				'content_element' => false,
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Title', 'js_composer' ),
						'param_name' => 'title',
						'description' => __( 'Tab title.', 'js_composer' )
					),
					array(
						'type' => 'icon',
						'param_name' => 'icon',
						'heading' => __( 'Awesome Icon ', 'js_composer' ),
						'description' => __( 'Select Icon for service box', 'js_composer' ),
						'admin_label' => true,
					),
					array(
						'type' => 'icon-simple',
						'param_name' => 'icon_simple_line',
						'heading' => __( 'Simple-line Icon ', 'js_composer' ),
						'description' => __( 'Select Icon for service box', 'js_composer' ),
						'admin_label' => true,
					),
					array(
						'type' => 'icon-etline',
						'param_name' => 'icon_etline',
						'heading' => __( 'Etline Icon ', 'js_composer' ),
						'description' => __( 'Select Icon for service box', 'js_composer' ),
						'admin_label' => true,
					),
					array(
						'type' => 'tab_id',
						'heading' => __( 'Tab ID', 'js_composer' ),
						'param_name' => "tab_id"
					)
				),
				'js_view' => $vc_is_wp_version_3_6_more ? 'VcTabView' : 'VcTabView35'
			) );

			vc_map( array(
				'name' => __( 'Video Background', 'js_composer' ),
				'base' => 'videobg',
				
				'allowed_container_element' => 'vc_row',
				'content_element' => true,
				'is_container' => true,
				'show_settings_on_create' => false,
				
				'icon' => 'fa fa-file-video-o',
				'category' => THEME_NAME.' Theme',
				
				'description' => __( 'Background video for sections', 'js_composer' ),
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Background Video ID', 'js_composer' ),
						'param_name' => 'id',
						'admin_label' => true,
						'description' => __( 'Imput video id from you, E.g: cUhPA5qIxDQ', 'js_composer' )
					),					
					array(
						'type' => 'select',
						'heading' => __( 'Sound', 'js_composer' ),
						'param_name' => 'sound',
						'values' => array(
							'no' => 'No, Thanks!',
							'yes' => 'Yes, Please!',
						),
						'admin_label' => true,
						'description' => __( 'Play sound or mute mode when video playing', 'js_composer' )
					),
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Height', 'js_composer' ),
						'param_name' => "height",
						'description' => __( 'Height of area video. E.g: 500', 'js_composer' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'js_composer' ),
						'param_name' => 'class',
						'description' => __( 'Use this field to add a class name and then refer to it in your css file.', 'js_composer' )
					)
				),
				'js_view' => 'VcColumnView'
				
			) );

			vc_map( array(
			
				'name' => THEME_NAME.' Elements',
				'base' => 'elements',
				'icon' => 'fa fa-graduation-cap',
				'category' => THEME_NAME.' Theme',
				'description' => __( 'All elements use in theme', 'js_composer' ),
				'params' => array(

					array(
						'type' => 'attach_image',
						'param_name' => 'image',
						'heading' => __( 'Image ', 'js_composer' ),
						'description' => __( 'Select image for service box', 'js_composer' ),
						'admin_label' => true,
					),
					array(
						'type' => 'icon',
						'param_name' => 'icon_awesome',
						'heading' => __( 'Awesome Icon ', 'js_composer' ),
						'description' => __( 'Select Icon for service box', 'js_composer' ),
						'admin_label' => true,
					),					
					array(
						'type' => 'icon-simple',
						'param_name' => 'icon_simple_line',
						'heading' => __( 'Simple-line Icon ', 'js_composer' ),
						'description' => __( 'Select Icon for service box', 'js_composer' ),
						'admin_label' => true,
					),
					array(
						'type' => 'icon-etline',
						'param_name' => 'icon_etline',
						'heading' => __( 'Etline Icon ', 'js_composer' ),
						'description' => __( 'Select Icon for service box', 'js_composer' ),
						'admin_label' => true,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon Class name', 'js_composer' ),
						'param_name' => "icon_class"
					),
					array(
						'type' => 'textarea_raw_html',
						'heading' => __( 'Short Description', 'js_composer' ),
						'param_name' => 'des'
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'External link', 'js_composer' ),
						'param_name' => 'link',
						'description' => __( 'External link read more', 'js_composer' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'External link class name', 'js_composer' ),
						'param_name' => 'linkclass'
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'js_composer' ),
						'param_name' => 'class',
						'description' => __( 'Use this field to add a class name and then refer to it in your css file.', 'js_composer' )
					)
				)
			) );
			
			
		}
	}

}
      